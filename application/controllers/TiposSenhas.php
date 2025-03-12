<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TiposSenhas extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('TiposSenha_model');
    }

    public function index()
    {
        $this->load->model('TiposSenha_model');
        $data['tipos_senha'] = $this->TiposSenha_model->get_all();
    
        // Se não houver tipos de senha, define um array vazio
        if (!$data['tipos_senha']) {
            $data['tipos_senha'] = [];
        }
    
        $this->load->view('tp_senha', $data);
    }
    

    public function criar() {
        $this->load->view('form_tipo_senha');
    }

    public function salvar() {

    $nome = $this->input->post('nome');
    $prefixo = $this->input->post('prefixo'); // Pegando o prefixo do formulário

    if (!empty($nome) && !empty($prefixo)) {
        $this->TiposSenha_model->insert_tipo_senha($nome, $prefixo);
        echo "Tipo de senha cadastrado com sucesso!";
    } else {
        echo "Erro: Nome e prefixo são obrigatórios.";
    }
    redirect("tiposSenhas/index");
}
public function editar($id) {
    // Carrega o modelo
    $this->load->model('TiposSenha_model');

    // Obtém os dados do tipo de senha pelo ID
    $data['tipo_senha'] = $this->TiposSenha_model->get_tipo_by_id($id);

    if (!$data['tipo_senha']) {
        show_404();
    }

    $this->load->view('form_tipo_senha', $data);
}

  
    public function atualizar() {
        $id = $this->uri->segment(3);
        $data = array(
            'nome' => $this->input->post('nome'),
            'prefixo' => $this->input->post('prefixo'),
            
        );
        $this->TiposSenha_model->update_tipo($data, $id);
        redirect('tiposSenhas/index');
    }

    public function delete($id) {
        $this->TiposSenha_model->delete($id);
        redirect('tp_senha');
    }
}
