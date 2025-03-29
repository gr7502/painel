<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Configuracoes_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database(); 
    }
    public function get_midia_painel() {
        $this->db->where('ativo', TRUE);
        $query = $this->db->get('painel_midia');
        return $query->result();
    }
    
    public function get_all_midia() {
        return $this->db->get('painel_midia')->result();
    }
    public function salvar_midia($data) {
        
        $this->db->update('painel_midia', array('ativo' => 0));
        
      
        $this->db->insert('painel_midia', $data);
        return $this->db->insert_id();
    }

    public function get_midia_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('painel_midia'); 

        if ($query->num_rows() > 0) {
            return $query->row(); 
        } else {
            return null; 
        }
    }
    public function update_midia($data, $id) {
        $this->db->where('id', $id);
        $this->db->update('painel_midia', $data);
    }
}