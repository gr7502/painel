<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chamada extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Senhas_model');
    }

    public function index() {
        $this->load->model('Pacientes_model');
        $this->load->model('Senhas_model');
    
        $data['senhas'] = $this->Senhas_model->get_senhas_disponiveis();
        $data['pacientes'] = $this->Pacientes_model->get_all_pacientes();
        $ultimos_chamados = $this->Senhas_model->get_historico_chamadas();
        $data['ultimos_chamados'] = !empty($ultimos_chamados) ? $ultimos_chamados : []; 
        $this->load->view('chamada', $data);
    }

    public function chamar() {
        {
            $this->load->model('Senha_model');
            
            $tipo = $this->input->get('tipo'); // Recebe 'senha' ou 'paciente'
            
            if ($tipo == 'senha') {
                $dados = $this->Senha_model->get_senha_atual();
                if ($dados) {
                    $response = [
                        'mensagem' => "Senha: {$dados->senha}, guichê {$dados->guiche}"
                    ];
                } else {
                    $response = ['erro' => 'Nenhuma senha chamada no momento.'];
                }
            } elseif ($tipo == 'paciente') {
                $dados = $this->Senha_model->get_paciente_atual();
                if ($dados) {
                    $response = [
                        'mensagem' => "Paciente: {$dados->nome_paciente}, consultório {$dados->consultorio}"
                    ];
                } else {
                    $response = ['erro' => 'Nenhum paciente chamado no momento.'];
                }
            } else {
                $response = ['erro' => 'Tipo de chamada inválido.'];
            }
        
            echo json_encode($response);
        }
    }

    public function salvar_chamada($tipo, $dados)
{
    $data = [
        'tipo' => $tipo,
        'data_hora' => date('Y-m-d H:i:s')
    ];

    if ($tipo == 'senha') {
        $data['senha'] = $dados['senha'];
        $data['guiche'] = $dados['guiche'];
    } elseif ($tipo == 'paciente') {
        $data['paciente'] = $dados['paciente'];
        $data['consultorio'] = $dados['consultorio'];
    }

    return $this->db->insert('chamadas', $data);
}


    public function get_chamadas() {
        $senha_atual = $this->Senhas_model->get_senha_atual();
        $historico = $this->Senhas_model->get_historico_chamadas();

        echo json_encode([
            "senha_atual" => $senha_atual,
            "historico" => $historico
        ]);
    }

   
    public function senha_chamada(){
    $this->load->model('Senha_model'); // Carregar o model

    $senha = $this->Senha_model->get_senha_atual(); // Buscar a senha atual

    if ($senha) {
        $response = [
            'senha' => $senha->codigo, // Exemplo: CN-01
            'paciente' => $senha->nome_paciente,
            'medico' => $senha->medico,
            'consultorio' => $senha->consultorio,
            'guiche' => $senha->guiche
        ];
    } else {
        $response = ['erro' => 'Nenhuma senha chamada no momento.'];
    }

    echo json_encode($response);
}


}
