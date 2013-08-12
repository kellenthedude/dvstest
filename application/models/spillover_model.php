<?php
class Spillover_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function get_spillover($tag) {
		if($tag == null) {
			$query = $this->db->get('spillover');
			return $query->result_array();
		}
		$tag = preg_replace("/[^0-9]/", "", html_entity_decode(urldecode($tag)));
		$this->db->where('tag_id =', $tag);
		$query = $this->db->get('spillover');
		return $query->row_array();
	}
}