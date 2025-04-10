<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuration_model extends CI_Model {

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function get_config() {
        $query = $this->db->get_where('configurations', array('id' => 1));
        return $query->row();
    }

    public function update_settings($data) {
        $existing = $this->db->get_where('configurations', array('id' => 1))->row();
        
        if ($existing) {
            $this->db->where('id', 1);
            return $this->db->update('configurations', $data);
        } else {
            $data['id'] = 1; // Define o ID explicitamente para o insert
            return $this->db->insert('configurations', $data);
        }
    }
}