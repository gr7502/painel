<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paciente extends CI_Controller {
public function __construct() {
    parent::__construct();
    $this->load->model('Pacientes_model');
    $this->load->model('Configuration_model');
    $this->load->library('form_validation');
}
public function index() {
    $data['pacientes'] = $this->Pacientes_model->get_all_pacientes();
    $data['config'] = $this->Configuration_model->get_config();
    $this->load->view('pacientes', $data);
}
public function home(){
$this->load->view('home');
}

public function create_paciente() {
    if ($this->form_validation->run('paciente_validation') == FALSE) {
        $this->load->view('paciente_form');
    } else {
        $this->Pacientes_model->create_paciente();
        redirect('paciente/index');
    }
}
public function store() {
    // Verifica se há dados enviados via POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       
        $data = [];

       
        if (!empty($_POST['nome'])) {
            $data['nome'] = $_POST['nome'];
        } else {
            echo "O campo 'nome' é obrigatório!";
            return;
        }

        if (!empty($_POST['nascimento'])) {
            $data['nascimento'] = $_POST['nascimento'];
        } else {
            echo "O campo 'Data de nascimento' é obrigatório!";
            return;
        }

        if (!empty($_POST['cpf'])) {
            $data['cpf'] = str_replace(',', '.', $_POST['cpf']);
        } else {
            echo "O campo 'CPF' é obrigatório!";
            return;
        }

        if (!empty($_POST['email'])) {
            $data['email'] =  $_POST['email'];
        } else {
            echo "O campo 'Email' é obrigatório!";
            return;
        }

        if (!empty($_POST['endereco'])) {
            $data['endereco'] = $_POST['endereco'];
        } else {
            echo "O campo 'Endereço' é obrigatório!";
            return;
        }

       
        $this->db->trans_start();

        // Insere os dados no banco de dados
        $this->Pacientes_model->insert_pacientes($data);

        // Finaliza a transação
        $this->db->trans_complete();

        // Verifica o status da transação
        if ($this->db->trans_status() === FALSE) {
            echo "Erro ao criar paciente!";
        } else {
            redirect('paciente/index');
        }
    }

    
    $this->load->view('create_paciente');
}

public function editar() {
    $id = $this->uri->segment(3);
    $data['paciente'] = $this->Pacientes_model->get_all_pacientes_by_id($id);
    $this->load->view('paciente_form', $data);
}

public function atualizar() {
    $id = $this->uri->segment(3);
    $data = array(
        'nome' => $this->input->post('nome'),
        'nascimento' => $this->input->post('nascimento'),
        'cpf' => $this->input->post('cpf'),
        'email' => $this->input->post('email'),
        'endereco' => $this->input->post('endereco')
    );
    $this->Pacientes_model->update_pacientes($data, $id);
    redirect('paciente/index');
}

public function delete_paciente() {
    $id = $this->uri->segment(3);
    $this->Pacientes_model->delete_pacientes($id);
    redirect('paciente/index'); 
}
}