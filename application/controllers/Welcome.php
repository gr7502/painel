<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();;
		$this->load->model('Configuration_model');
    }

    public function index() {
        // Carrega os dados de configuração do banco de dados
        $data['config'] = $this->Configuration_model->get_config();

        // Carrega a view
        $this->load->view('home', $data);
    }
}