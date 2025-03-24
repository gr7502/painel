<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chamada extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Senhas_model');
        $this->load->library('session');
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
        header('Content-Type: application/json');
        $tipo = $_POST('tipo');
        $senha = $_POST('senha');
        $guiche = $_POST('guiche');
        $paciente = $_POST['paciente'];
        $sala = $_POST['sala'];
    
        if ($tipo === 'senha' && $senha && $guiche) {
            $mensagem = "Senha: {$senha}, dirija-se ao {$guiche}";
    
            // Salvar no banco de dados (crie uma tabela 'chamadas' se necessário)
            $this->db->insert('chamadas', [
                'tipo' => 'senha',
                'senha' => $senha,
                'guiche' => $guiche,
                'mensagem' => $mensagem,
                'data_hora' => date('Y-m-d H:i:s')
            ]);
    
            echo json_encode(['status' => 'success', 'mensagem' => $mensagem]);
        } else {
            echo json_encode(['status' => 'error', 'mensagem' => 'Dados inválidos!']);
        }
    
        if ($tipo === 'paciente' && $paciente && $sala) {
            $mensagem = "Paciente: {$paciente}, dirija-se ao {$sala}";
    
            // Salvar no banco de dados (crie uma tabela 'chamadas' se necessário)
            $this->db->insert('chamadas', [
                'tipo' => 'paciente',
                'paciente' => $paciente,
                'consultorio' => $sala,
                'mensagem' => $mensagem,
                'data_hora' => date('Y-m-d H:i:s')
            ]);
    
            echo json_encode(['status' => 'success', 'mensagem' => $mensagem]);
        } else {
            echo json_encode(['status' => 'error', 'mensagem' => 'Dados inválidos!']);
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
    $this->load->model('Senhas_model'); // Carregar o model

    $senha = $this->Senhas_model->get_senha_atual(); // Buscar a senha atual

    if ($senha) {
        $response = [
            'senha' => $senha->codigo,
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

public function registrar_chamada() {
    $tipo = $this->input->post('tipo');
    $mensagem = $this->input->post('mensagem');

    // Salvar a mensagem na sessão
    $this->session->set_userdata('ultima_chamada', $mensagem);

    echo json_encode(['status' => 'success']);
}


public function getUltimaChamada() {
    header('Content-Type: application/json');

    $query = $this->db->order_by('data_hora', 'DESC')->get('chamadas', 1);
    $ultimaChamada = $query->row();

    if ($ultimaChamada) {
        echo json_encode(['status' => 'success', 'mensagem' => $ultimaChamada->mensagem]);
    } else {
        echo json_encode(['status' => 'error', 'mensagem' => 'Nenhuma senha chamada']);
    }
}

public function chamar_senha()
{
    $senha_id = $this->input->post('senha_id'); // Recebe a senha via POST

    // Busca os dados da senha no banco de dados
    $senha = $this->db->get_where('senhas', ['id' => $senha_id])->row();

    if ($senha) {
        // Salva a senha chamada na sessão
        $this->session->set_userdata('senha_chamada', $senha->nome);

        echo json_encode(['status' => 'success', 'senha' => $senha->nome]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Senha não encontrada']);
    }
}



}
