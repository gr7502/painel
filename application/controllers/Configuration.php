<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuration extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Configuration_model');
        $this->load->helper(array('form', 'url'));
    }
    
    public function index(){
        $data['config'] = $this->Configuration_model->get_config();
        $this->load->view('configuration_view', $data);
    }
    
    public function update() {
        $data = [
            'primary_color' => $this->input->post('primary_color'),
            'image_url' => $this->input->post('image_url')
        ];
        var_dump($_FILES);
        // die();
        if (!empty($_FILES['image_file']['name'])) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 2048; // 2MB limite (opcional)
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('image_file')) {

                $upload_data = $this->upload->data();
                $data['image_url'] = base_url('uploads/' . $upload_data['file_name']);
            } else {
                var_dump('não');
                
                $error = $this->upload->display_errors();

                redirect('configuration?error=' . urlencode($error));
            }
        }
    
        if ($this->Configuration_model->update_settings($data)) {
            redirect('configuration?success=1');
        } else {
            redirect('configuration?error=1');
        }
    }
    
    public function painel_2(){
        $data['config'] = $this->Configuration_model->get_config();
        $this->load->view('painel_2', $data);
    }
}