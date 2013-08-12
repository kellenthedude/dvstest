<?php
class Spillover extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('spillover_model');
	}
	
	public function get_spillover($tag = null)
	{
		$this->load->helper('url');
	
		echo json_encode($this->spillover_model->get_spillover($tag));
	}

}