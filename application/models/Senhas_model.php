<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Senhas_model extends CI_Model {
    public function __construct() {
        parent::__construct(); 
        $this->load->database();

     }

     public function get_next_number($prefixo) {
        // Encontra o maior número existente para o tipo de prefixo (CN, EX, etc.)
        $this->db->select_max('numero');
        $this->db->from('senhas');
        $this->db->join('tipos_senha', 'tipos_senha.id = senhas.tipo_senha_id');
        $this->db->where('tipos_senha.prefixo', $prefixo);
        $query = $this->db->get();
        
        $result = $query->row();
        $next_number = ($result) ? $result->numero + 1 : 1;

        return $next_number;
    }

    // Função para inserir a senha gerada
    public function insert_senha($tipo_senha_id, $numero, $senha) {
        $data = [
            'tipo_senha_id' => $tipo_senha_id,
            'numero' => $numero,
            'senha' => $senha
        ];
        
        return $this->db->insert('senhas', $data);
    }

    public function get_senhas_disponiveis() {
        return $this->db->where('status', 'pendente')->get('senhas')->result();
    }

    public function get_pacientes() {
        return $this->db->where('status', 'aguardando')->get('pacientes')->result();
    }

    public function update_status_senha($senha, $status) {
        $this->db->where('senha', $senha)->update('senhas', ['status' => $status]);
    }
    // Modelo Senhas_model.php

public function get_senha_atual() {
    // Exemplo de consulta para pegar a senha mais recente que esteja ativa
    $this->db->select('senha, guiche');
    $this->db->from('senhas');
    $this->db->where('status', 'pendente');  // Ou outro critério de status
    $this->db->order_by('id', 'desc');    // Ordena pela ID (ou outro campo)
    $this->db->limit(1);                  // Limita a 1 resultado

    $query = $this->db->get();

    // Verifica se existe algum resultado
    if ($query->num_rows() > 0) {
        return $query->row();  // Retorna o primeiro resultado encontrado
    }

    return null;  // Retorna null caso não tenha nenhuma senha ativa
}

    public function get_paciente_atual()
    {
        $this->db->select('nome_paciente, consultorio');
        $this->db->from('pacientes');
        $this->db->where('status', 'chamada');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);

        $query = $this->db->get();
        return $query->row();
    }

    
    
    public function salvar_chamada($paciente, $sala) {
        $this->db->insert('chamadas', [
            'paciente' => $paciente,
            'sala' => $sala,
            'chamado_em' => date('Y-m-d H:i:s')
        ]);
    }
    
    
    public function get_historico_chamadas($limit = 4) {
        return $this->db->order_by('chamado_em', 'DESC')
                        ->get_where('senhas', ['status' => 'chamado'], $limit)
                        ->result_array();
    }
    
    public function chamar_senha() {
        $id_senha = $this->input->post('id_senha');
        $guiche = $this->input->post('guiche');
    
        if ($id_senha && $guiche) {
            $this->load->model('Senhas_model');
            $this->Senhas_model->chamar_senha($id_senha, $guiche);
    
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao chamar senha.']);
        }
    }

    public function getUltimasChamadas()
{
    $this->db->order_by('data_hora', 'DESC');
    $this->db->limit(5);
    return $this->db->get('chamadas')->result_array();
}
}


    
    
    



