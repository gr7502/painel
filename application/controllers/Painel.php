<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Painel extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Senhas_model');
    }

    public function index() {
        
    $data['senha_atual'] = $this->Senhas_model->get_senha_atual();
    $data['historico'] = $this->Senhas_model->get_historico_chamadas(5); // Últimos 5 chamados
        $this->load->view('painel', $data);
    }

}