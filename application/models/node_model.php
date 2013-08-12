<?php
class Node_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function get_node($id = FALSE)
	{
		if ($id === FALSE)
		{
			$query = $this->db->get('node');
			return $query->result_array();
		}
	
		$query = $this->db->get_where('node', array('id' => $id));
		return $query->row_array();
	}
	
	public function get_experiment_nodes($exp_id, $order = null)
	{
		$order_by = '';
		switch($order) {
			case 'added':
				$order_by = 'active desc, id asc';
				break;
			case 'target':
				$order_by = 'active desc, target asc';
				break;
			case 'channel':
				$order_by = 'active desc, label asc';
				break;
			case 'abundance':
				$order_by = 'active desc, abundance_factor asc';
				break;
			case 'importance':
				$order_by = 'active desc, important desc';
				break;
			default:
				$order_by = 'active desc, id asc';
		}
		$this->db->order_by($order_by);
		$query = $this->db->get_where('node', array('experiment_id' => $exp_id));
		
		$result_array = array();
		foreach($query->result_array() as $element) {
			$element['target'] = html_entity_decode(urldecode($element['target']));
			$element['label'] = html_entity_decode(urldecode($element['label']));
			$result_array[] = $element;
		}
		
		return $result_array;
	}
	
	public function set_node($exp_id, $reactivity, $target, $active, $custom, $label, $abundance, $important, $id = null)
	{
		if($target == 'null') $target = "";

		if($id == null) { //INSERT
			$data = array(
				'target' => $target,
				'experiment_id' => $exp_id,
				'reactivity' => $reactivity,
				'active' => $active,
				'custom' => $custom,
				'important' => $important,
				'label' => $label,
				'abundance_factor' => $abundance
			);
			
			$this->db->insert('node', $data); 
		} else { //UPDATE
			$this->db->set('target', $target);
			$this->db->set('experiment_id', $exp_id);	
			$this->db->set('reactivity', $reactivity);
			$this->db->set('active', $active);
			$this->db->set('custom', $custom);
			$this->db->set('label', $label);
			$this->db->set('important', $important);
			$this->db->set('abundance_factor', $abundance);
			$this->db->where('id', $id);	
			$this->db->update('node'); 
		}
		return $this->db->insert_id();
	}
	
	public function delete_node($id)
	{
		$this->db->delete('node', array('id' => $id)); 
	}
}