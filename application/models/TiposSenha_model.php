<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TiposSenha_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->load->database();
    }

    public function get_all() {
        return $this->db->get('tipos_senha')->result();
    }

    public function insert_tipo_senha($nome, $prefixo) {
        $data = [
            'nome' => $nome,
            'prefixo' => $prefixo
        ];
        $this->db->insert('tipos_senha', $data);
    }

    public function update_tipo($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('tipos_senha', $data);
    }

    public function get_tipo_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('tipos_senha'); // Certifique-se de que 'tipos_senha' é o nome correto da tabela no BD

        if ($query->num_rows() > 0) {
            return $query->row(); // Retorna um único objeto com os dados do tipo de senha
        } else {
            return null; // Retorna null se não encontrar
        }
    }
}

