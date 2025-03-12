<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Senhas extends CI_controller {

    public function gerar() {
        $this->load->model('TiposSenha_model');
        $data['tipos'] = $this->TiposSenha_model->get_all();
        $this->load->view('gerar_senha', $data);
    }
    
    public function gerar_senha($tipo_id) {
        // Verifica se o ID do tipo de senha foi recebido corretamente
        if (!$tipo_id) {
            echo json_encode(["error" => "ID do tipo de senha não recebido."]);
            return;
        }
    
        // Carrega os modelos
        $this->load->model('Senhas_model');
        $this->load->model('TiposSenha_model');
    
        // Obtém o tipo de senha
        $tipo = $this->TiposSenha_model->get_tipo_by_id($tipo_id);
    
        // Verifica se o tipo de senha foi encontrado
        if (!$tipo) {
            echo json_encode(["error" => "Tipo de senha não encontrado."]);
            return;
        }
    
        // Pega o próximo número para o tipo de senha
        $next_number = $this->Senhas_model->get_next_number($tipo->prefixo);
    
        // Gera a senha
        $senha = $tipo->prefixo . '-' . str_pad($next_number, 2, '0', STR_PAD_LEFT);
    
        // Insere a senha no banco de dados
        $insert = $this->Senhas_model->insert_senha($tipo_id, $next_number, $senha);
    
        // Verifica se a inserção foi bem-sucedida
        if ($insert) {
            echo json_encode(["success" => "Senha gerada: " . $senha]);
        } else {
            echo json_encode(["error" => "Erro ao salvar senha no banco de dados."]);
        }
    }
    
}