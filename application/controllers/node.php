<?php
class Node extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('node_model');
	}
	
	public function set($exp_id, $reactivity, $target, $active, $custom, $label, $abundance, $important, $id = null)
	{
		$this->load->helper('url');
	
		if($active == 'true') $active = 1;
		if($active == 'false') $active = 0;
		if($custom == 'true') $custom = 1;
		if($custom == 'false') $custom = 0;
		if($important == 'true') $important = 1;
		if($important == 'false') $important = 0;
		echo $this->node_model->set_node($exp_id, $reactivity, $target, $active, $custom, $label, $abundance, $important, $id);
	}
	
	public function get_exp_nodes($exp_id, $order)
	{
		$this->load->helper('url');
		
		echo json_encode($this->node_model->get_experiment_nodes($exp_id, $order));
	}

}