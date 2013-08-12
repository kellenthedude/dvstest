<?php
class Experiment_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function get_experiment($id = FALSE)
	{
		$this->db->join('reactivity', 'reactivity.react_id = experiment.reactivity', 'left');
		
		if ($id === FALSE)
		{
			$query = $this->db->get('experiment');
			return $query->result_array();
		}
	
		$query = $this->db->get_where('experiment', array('experiment.id' => $id));
		return $query->row_array();
	}
	
	public function set_experiment()
	{
		$this->load->helper('url');
		
		$data = array(
		   'name' => $this->input->post('name') ,
		   'reactivity' => $this->input->post('reactivity'),
		   'num_zones' => $this->input->post('num_zones')
		);
		
		$this->db->insert('experiment', $data); 
	}
	
	public function set_experiment_hidden($id, $hide)
	{
		$this->db->set('hide_inactive', $hide);
		$this->db->where('id', $id);	
		$this->db->update('experiment'); 
	}
	
	public function delete_experiment($id)
	{
		$this->db->delete('experiment', array('id' => $id)); 
	}
}