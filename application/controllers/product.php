<?php
class Product extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
	}
	
	public function get_labels_by_target($target)
	{
		$this->load->helper('url');
	
		echo json_encode($this->product_model->get_labels_by_target($target));
	}

}