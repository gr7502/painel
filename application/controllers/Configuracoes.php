<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracoes extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Configuracoes_model'); 
    }
    

    public function midiaPainel() {
        $this->load->model('Configuracoes_model');
        $data['midias'] = $this->Configuracoes_model->get_all_midia();
    
        // Se não houver tipos de senha, define um array vazio
        if (!$data['midias']) {
            $data['midias'] = [];
        }
        $this->load->view('midia_painel, $data');
    } 

    public function criarmidia() {
        $this->load->view('form_midia');
    }

    

    public function upload_midia() {
        $config['upload_path'] = './uploads/painel/';
        $config['allowed_types'] = 'jpg|jpeg|png|mp4|avi';
        $config['max_size'] = 51200; 
    
        $this->load->library('upload', $config);
    
        if (!$this->upload->do_upload('arquivo_midia')) {
            $error = array('error' => $this->upload->display_errors());
            // Tratar o erro
        } else {
            $data = $this->upload->data();
            
            // Captura o nome enviado pelo formulário
            $nome_midia = $this->input->post('nome_midia', TRUE);
    
            // Salvar no banco de dados
            $midia_data = array(
                'nome' => $nome_midia,
                'caminho' => 'uploads/painel/' . $data['file_name'],
                'tipo' => $data['is_image'] ? 'imagem' : 'video',
                'ativo' => 1
            );
    
            $this->Configuracoes_model->salvar_midia($midia_data);
    
            redirect('painel');
        }
    }
    

    public function editarmidia($id) {
        // Carrega o modelo
        $this->load->model('Configuracoes_model');
    
        // Obtém os dados do tipo de senha pelo ID
        $data['midias'] = $this->Configuracoes_model->get_midia_by_id($id);
    
        if (!$data['tipo_senha']) {
            show_404();
        }
    
        $this->load->view('form_tipo_senha', $data);
    }

    public function atualizarmidia() {
        $id = $this->uri->segment(3);
        $data = array(
            'nome' => isset($_POST['nome']) ? $_POST['nome'] : '',

            'caminho' => isset($_POST['caminho']) ? $_POST['caminho'] : '',
            
        );
        $this->Configuracoes_model->update_midia($data, $id);
        redirect('configuracoes/midiaPainel');
    }
}