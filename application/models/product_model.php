<?php
class Product_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function get_antibody($id = null, $reactivity = null)
	{
		if($reactivity != null) {
			$this->db->where('product !=', '');
			$this->db->where('active =', 'yes');
			$this->db->where('reagent_class =', 'Metal-Conjugated Antibodies');
			
			$this->db->join('species', 'species.spec_name = products.species', 'left');
			$this->db->join('reactivity_species', 'species.spec_id = reactivity_species.spec_id', 'left');
			$this->db->where('reactivity_species.react_id =', $reactivity);
			$this->db->or_where('products.species = ', 'Cross');
			$this->db->order_by('products.target');
			$this->db->group_by('products.target');
			
			$query = $this->db->get('products');
			return $query->result_array();
		}
		
		if ($id === null)
		{
			$this->db->where('product !=', '');
			$this->db->where('active =', 'yes');
			$this->db->where('reagent_class =', 'Metal-Conjugated Antibodies');
			
			$this->db->join('species', 'species.spec_name = products.species', 'left');
			
			$query = $this->db->get('products');
			return $query->result_array();
		}
		
		$query = $this->db->get_where('products', array('id' => $id));
		return $query->row_array();
	}
	
	public function get_reactivity() {
		$query = $this->db->get('reactivity');
		return $query->result_array();
	}
	
	public function get_labels() {
		$this->db->where('reagent_class =', 'MAXPAR&reg; Labeling Reagents');
		$this->db->where('active =', 'yes');
		$this->db->where('tag !=', '');
		$this->db->group_by(array("tag")); 
		$query = $this->db->get('products');
		return $query->result_array();
	}
	
	public function get_labels_by_target($target) {
		
		//print_r(html_entity_decode(urldecode($target)));
		$target = html_entity_decode(urldecode($target));
		//print_r($target);
		
		$this->db->where('reagent_class =', 'Metal-Conjugated Antibodies');
		$this->db->where('active =', 'yes');
		$this->db->where('target =', $target);
		$this->db->where('tag !=', '');
		$this->db->order_by("tag");
		$this->db->group_by(array("tag")); 
		$query = $this->db->get('products');
		if(count($query->result_array()) > 0) {
			return $query->result_array();
		}
		
		$this->db->flush_cache();
		
		$this->db->where('reagent_class =', 'MAXPAR&reg; Labeling Reagents');
		$this->db->where('active =', 'yes');
		$this->db->where('tag !=', 'natIr');
		$this->db->where('tag !=', '103Rh');
		$this->db->where('tag !=', '');
		$this->db->group_by(array("tag")); 
		$query = $this->db->get('products');
		return $query->result_array();
		
		/*
		$this->db->where('reagent_class =', 'Metal-Conjugated Antibodies');
		$this->db->where('active =', 'yes');
		$this->db->where('tag !=', '');
		$this->db->order_by("tag"); 
		$this->db->group_by(array("tag")); 
		$query = $this->db->get('products');	
		return $query->result_array();
		 * */
	}
	
	public function get_target() {
		$this->db->where('species !=', '');
		$this->db->where('active =', 'yes');
		$this->db->group_by(array("target")); 
		$query = $this->db->get('products');
		return $query->result_array();
	}
}