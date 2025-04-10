<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Senhas extends CI_controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Senhas_model');
        $this->load->model('TiposSenha_model');
        $this->load->model('Configuration_model');
    }

    public function gerar() {
        $this->load->model('TiposSenha_model');
        $tipos = $this->TiposSenha_model->get_all();
        
        // Adiciona a flag 'has_subtipos' para cada tipo
        foreach ($tipos as $tipo) {
            $tipo->has_subtipos = $this->TiposSenha_model->has_subtipos($tipo->id);
        }
        
        $data['tipos'] = $tipos;
        $data['config'] = $this->Configuration_model->get_config();
        $this->load->view('gerar_senha', $data);
    }

    public function subtipos($tipo_id) {
        $this->load->model('TiposSenha_model');
        $tipo = $this->TiposSenha_model->get_tipo_by_id($tipo_id);
        if (!$tipo) {
            show_404();
        }
        
        $subtipos = $this->TiposSenha_model->get_subtipos_by_tipo($tipo_id);
        $data['tipo'] = $tipo;
        $data['subtipos'] = $subtipos;
        $data['config'] = $this->Configuration_model->get_config();
        $this->load->view('gerar_senha_subtipos', $data);
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

    public function gerar_senha_subtipo($subtipo_id) {
        if (!$subtipo_id) {
            echo json_encode(["error" => "ID do subtipo de senha não recebido."]);
            return;
        }
    
        $this->load->model('Senhas_model');
        $this->load->model('TiposSenha_model');
    
        // Obtém o subtipo e o tipo associado
        $subtipo = $this->TiposSenha_model->get_subtipo_by_id($subtipo_id);
        if (!$subtipo) {
            echo json_encode(["error" => "Subtipo de senha não encontrado."]);
            return;
        }
    
        $tipo = $this->TiposSenha_model->get_tipo_by_id($subtipo->tipo_senha_id);
        if (!$tipo) {
            echo json_encode(["error" => "Tipo de senha não encontrado."]);
            return;
        }
    
        // Pega o próximo número para o subtipo
        $next_number = $this->Senhas_model->get_next_number_subtipo($subtipo->id);
    
        // Gera a senha usando apenas o prefixo do subtipo
        $senha = $subtipo->prefixo . '-' . str_pad($next_number, 3, '0', STR_PAD_LEFT);
    
        // Insere a senha no banco de dados, incluindo o tipo_senha_id
        $insert = $this->Senhas_model->insert_senha_subtipo($subtipo->id, $subtipo->tipo_senha_id, $next_number, $senha);
    
        if ($insert) {
            echo json_encode(["success" => $senha]);
        } else {
            echo json_encode(["error" => "Erro ao salvar senha no banco de dados."]);
        }
    }
    

    public function reset_senhas_diario() {
        // Carrega o modelo
        $this->load->model('Senhas_model');
    
        // Reseta as senhas geradas (limpa a tabela senhas)
        $this->Senhas_model->reset_senhas();
    
        // Reseta a fila de chamadas (limpa a tabela fila_chamadas)
        $this->Senhas_model->reset_fila_chamadas();
    
        // Opcional: Log ou resposta para confirmar a execução
        log_message('info', 'Senhas e fila de chamadas resetadas com sucesso às ' . date('Y-m-d H:i:s'));
        echo "Senhas e fila de chamadas resetadas com sucesso!";
    }
}