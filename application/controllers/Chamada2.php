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
                    'sala'     => isset($chamada['sala']) ? $chamada['sala'] : null
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
    
    

    public function ultimas_chamadas() {
        header('Content-Type: application/json');
    
        // Última chamada de senha
        $this->db->select('fc.id, fc.senha, fc.guiche, fc.data_entrada, fc.senha_id');
        $this->db->from('fila_chamadas fc');
        $this->db->join('senhas s', 's.id = fc.senha_id');
        $this->db->where('s.status !=', 'finalizada');
        $this->db->order_by('fc.id', 'DESC');
        $this->db->limit(1);
        $ultima_senha = $this->db->get()->row_array();
    
        // Última chamada de paciente
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $ultima_paciente = $this->db->get('pacientes')->row_array();
    
        // Fila de atendimento
        $this->db->select('fc.id, fc.senha, fc.guiche, fc.data_entrada, fc.senha_id');
        $this->db->from('fila_chamadas fc');
        $this->db->join('senhas s', 's.id = fc.senha_id');
        $this->db->where('s.status !=', 'finalizada');
        $this->db->order_by('fc.id', 'DESC');
        $this->db->limit(10);
        $fila_atendimento = $this->db->get()->result_array();
    
        echo json_encode(array(
            'status' => 'success',
            'ultima_senha' => $ultima_senha,
            'ultima_paciente' => $ultima_paciente,
            'fila_atendimento' => $fila_atendimento
        ));
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

   
    public function finalizar_atendimento() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        
        log_message('debug', 'Dados recebidos em finalizar_atendimento: ' . print_r($data, true));
        
        if (!$data) {
            echo json_encode(array('status' => 'error', 'message' => 'Nenhum dado recebido'));
            return;
        }
        
        $id = isset($data['id']) ? $data['id'] : null;
        $status = isset($data['status']) ? $data['status'] : null;
        $motivo = isset($data['motivo']) ? $data['motivo'] : null;
        
        if (!$id || !$status) {
            echo json_encode(array('status' => 'error', 'message' => 'ID ou status inválido'));
            return;
        }
        
        $this->db->where('id', $id);
        $query = $this->db->get('senhas');
        log_message('debug', 'Linhas encontradas para ID ' . $id . ': ' . $query->num_rows());
        
        if ($query->num_rows() == 0) {
            echo json_encode(array('status' => 'error', 'message' => 'ID ' . $id . ' não encontrado na tabela senhas'));
            return;
        }
        
        $current_data = $query->row_array();
        log_message('debug', 'Dados atuais da senha ID ' . $id . ': ' . print_r($current_data, true));
        
        $this->db->where('id', $id)->update('senhas', array(
            'status' => $status,
            'motivo' => $motivo,
            'hora_finalizacao' => date('Y-m-d H:i:s')
        ));
        
        $error = $this->db->error();
        if ($error['code'] != 0) {
            log_message('error', 'Erro no banco ao atualizar ID ' . $id . ': ' . $error['message']);
            echo json_encode(array('status' => 'error', 'message' => 'Erro no banco: ' . $error['message']));
            return;
        }
        
        if ($this->db->affected_rows() > 0) {
            echo json_encode(array('status' => 'success', 'message' => 'Atendimento finalizado'));
        } else {
            log_message('error', 'Nenhuma alteração realizada para ID ' . $id . '. Dados enviados: ' . print_r(array(
                'status' => $status,
                'motivo' => $motivo,
                'data_finalizacao' => date('Y-m-d H:i:s')
            ), true));
            echo json_encode(array('status' => 'error', 'message' => 'Nenhuma alteração realizada. O status já pode ser o mesmo ou o ID não existe.'));
        }
    }

    public function get_ultimas_chamadas() {
        $this->load->model('Chamada_model');
        
        // Busca os últimos 5 chamados (ajuste o limite conforme necessário)
        $chamadas = $this->Chamada_model->get_ultimas_chamadas(5);
        
        if ($chamadas) {
            echo json_encode([
                'status' => 'success',
                'chamadas' => $chamadas
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'chamadas' => []
            ]);
        }
    }

}
