<?php
class Chamada2 extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('chamada_model');
    }



    // Endpoint para obter a última chamada
    public function get_ultima_chamada()
    {
        $this->load->model('Chamada_model');
        $ultima_chamada = $this->Chamada_model->get_ultima_chamada();

        if ($ultima_chamada) {
            // Verifica se já foi falada recentemente
            $id = ($ultima_chamada['tipo'] === 'senha') ? $ultima_chamada['senha_id'] : $ultima_chamada['paciente_id'];
            $foi_falada = $this->Chamada_model->foi_falada_recentemente($id);

            $response = [
                'status' => 'success',
                'chamada' => $ultima_chamada,
                'chamada_nova' => !$foi_falada
            ];
        } else {
            $response = ['status' => 'empty'];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }


    public function get_proxima_chamada() {
        $this->load->model('Chamada_model');
        $chamada = $this->Chamada_model->get_proxima_chamada();
        
        if ($chamada) {
            // Marca como "falando" para evitar duplicação
            $this->Chamada_model->comecar_falar($chamada['id']);
            
            $response = [
                'status' => 'success',
                'mensagem' => $chamada['mensagem'], // aqui vem a mensagem montada
                'fila_id' => $chamada['id'],
                'tipo' => $chamada['tipo'],
                'dados' => [
                    'senha'    => isset($chamada['senha']) ? $chamada['senha'] : null,
                    'guiche'   => isset($chamada['guiche']) ? $chamada['guiche'] : null,
                    'paciente' => isset($chamada['paciente']) ? $chamada['paciente'] : null,
                    'sala'     => isset($chamada['consultorio']) ? $chamada['consultorio'] : null
                ]
            ];
        } else {
            $response = ['status' => 'empty'];
        }
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($response));
            
    }
    
    
    

    public function marcar_como_falada($fila_id) {
        $this->load->model('Chamada_model');
        $result = $this->Chamada_model->finalizar_fala($fila_id);
        
        $response = $result 
            ? ['status' => 'success'] 
            : ['status' => 'error', 'message' => 'Falha ao atualizar status'];
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
   

    private function formatar_mensagem($chamada)
    {
        if ($chamada['tipo'] === 'senha') {
            return "Senha {$chamada['senha']} - Guichê {$chamada['guiche']}";
        } else {
            return "Paciente {$chamada['paciente']} - Consultório {$chamada['consultorio']}";
        }
    }

    public function processar_chamada() {
        header('Content-Type: application/json');
        
        try {
            // Método mais confiável para receber JSON
            $json = $this->input->raw_input_stream;
            $data = json_decode($json, true);
    
            // Verificação robusta do JSON
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON inválido. Erro: ' . json_last_error_msg());
            }
    
            if (!isset($data['tipo'])) {
                throw new Exception('Tipo de chamada não especificado');
            }
    
            $this->load->model('Chamada_model');
    
            if ($data['tipo'] === 'senha') {
                // Validação reforçada
                if (empty($data['guiche'])) {
                    throw new Exception('Guichê não especificado');
                }
    
                $this->db->trans_start();
                $senha = $this->Chamada_model->buscar_proxima_senha();
                
                if ($senha) {
                    $dadosChamada = [
                        'senha_id' => $senha->id,
                        'senha' => $senha->senha,
                        'guiche' => $data['guiche'],
                        'data_entrada' => date('Y-m-d H:i:s'),
                        'status' => 'pendente',
                        'tipo' => 'senha',
                        'mensagem' => "Senha {$senha->senha}, Dirija-se ao {$data['guiche']}"
                    ];
                    $result = $this->Chamada_model->registrar_chamada($dadosChamada);
                }
                
                $this->db->trans_commit();
    
                if (!$senha || !$result) {
                    throw new Exception('Falha ao registrar chamada');
                }
    
                echo json_encode([
                    'status' => 'success',
                    'message' => "Senha {$senha->senha} - Guichê {$data['guiche']}"
                ]);
    
            } elseif ($data['tipo'] === 'paciente') {
                // Validação detalhada
                $camposRequeridos = ['paciente', 'sala'];
                foreach ($camposRequeridos as $campo) {
                    if (empty($data[$campo])) {
                        throw new Exception("Campo obrigatório: {$campo}");
                    }
                }
    
                $dadosPaciente = [
                    'tipo' => 'paciente',
                    'paciente' => $data['paciente'],
                    'sala' => $data['sala'],
                    'data_entrada' => date('Y-m-d H:i:s'),
                    'status' => 'pendente',
                    'mensagem' => "Paciente {$data['paciente']}, Dirija-se ao {$data['sala']}"
                ];
    
                $result = $this->Chamada_model->registrar_chamada($dadosPaciente);
                
                if (!$result) {
                    throw new Exception('Falha ao registrar paciente');
                }
    
                echo json_encode([
                    'status' => 'success',
                    'message' => "Paciente {$data['paciente']} - {$data['sala']}"
                ]);
    
            } else {
                throw new Exception('Tipo de chamada inválido');
            }
    
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Erro em processar_chamada: ' . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    

    public function ultimas_chamadas()
    {
        try {
            $this->load->model('Chamada_model');

            // Busca a última chamada de senha
            $ultima_senha = $this->Chamada_model->get_ultima_por_tipo('senha');

            // Busca a última chamada de paciente
            $ultima_paciente = $this->Chamada_model->get_ultima_por_tipo('paciente');

            $response = [
                'status' => 'success',
                'ultima_senha' => $ultima_senha,
                'ultima_paciente' => $ultima_paciente,
                'timestamp' => date('Y-m-d H:i:s')
            ];

        } catch (Exception $e) {
            log_message('error', 'Erro em ultimas_chamadas: ' . $e->getMessage());
            $response = [
                'status' => 'error',
                'message' => 'Erro ao buscar últimas chamadas'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function proxima_senha() {
        $this->load->model('Chamada_model');
        $guiche = $this->input->post('guiche'); // Recebe o guichê do POST
    
        $this->db->trans_start();
        $senha = $this->Chamada_model->buscar_proxima_senha();
        
        if($senha) {
            // Registra com o guichê recebido
            $this->Chamada_model->registrar_chamada([
                'senha_id' => $senha->id,
                'senha' => $senha->senha,
                'guiche' => $guiche, // Usa o guichê do POST
                'data_entrada' => date('Y-m-d H:i:s'),
                'status' => 'ativa'
            ]);
        }
        
        $this->db->trans_complete();
    
        echo json_encode(['status' => $senha ? 'success' : 'error']);
    }
    
    public function finalizar_senha() {
        $this->load->model('Chamada_model');
        $senha = $this->input->post('senha');
        $guiche = $this->input->post('guiche');
    
        $this->Chamada_model->finalizar_chamada($senha, $guiche);
        echo json_encode(['status' => 'success']);
    }
    public function obter_fila() {
        $this->load->model('Chamada_model');
        $fila = $this->Chamada_model->obter_chamadas_ativas();
        echo json_encode($fila);
    }

    public function feed_painel() {
        $this->load->model('Chamada_model');
        
        $response = [
            'status' => 'success',
            'ultima_chamada' => $this->Chamada_model->ultima_chamada_ativa(),
            'historico' => $this->Chamada_model->historico_chamadas(5)
        ];
    
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // No controller Chamada2.php
public function finalizar_atendimento() {
    $this->load->model('Chamada_model');
    
    $id = $this->input->post('id');
    $status = $this->input->post('status');
    $motivo = $this->input->post('motivo');

    // Atualiza ambas as tabelas em uma transação
    $this->db->trans_start();
    
    // 1. Atualiza a tabela fila_chamadas
    $this->db->where('id', $id)
             ->update('fila_chamadas', [
                 'status' => $status,
                 'data_finalizacao' => date('Y-m-d H:i:s'),
             ]);
    
    // 2. Atualiza a tabela senhas (assumindo que há um campo de relacionamento)
    $this->db->where('id_fila_chamada', $id)
             ->update('senhas', [
                 'status' => $status,
                 'hora_finalizacao' => date('Y-m-d H:i:s')
             ]);
    
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Falha ao atualizar ambas as tabelas'
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'message' => 'Atendimento finalizado em ambas as tabelas'
        ]);
    }
}

}
