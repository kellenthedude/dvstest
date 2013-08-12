<?php
class Experiment extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('experiment_model');
		$this->load->model('product_model');
		$this->load->model('node_model');
	}

	public function index()
	{
		$data['experiments'] = $this->experiment_model->get_experiment();
	
		$this->load->view('templates/header', $data);
		$this->load->view('experiment/index', $data);
		$this->load->view('templates/footer');
	}

	public function view($id)
	{
		$data['experiment'] = $this->experiment_model->get_experiment($id);
		$data['products'] = $this->product_model->get_antibody();
		$data['reactivities'] = $this->product_model->get_reactivity();
		$data['targets'] = $this->product_model->get_target();
		$data['exp_nodes'] = $this->node_model->get_experiment_nodes($id);
		
		$this->load->view('templates/header', $data);
		$this->load->view('experiment/view', $data);
		$this->load->view('templates/footer');
	}
	
	public function set_hide($id, $hide)
	{
		if($hide == 'true') $hide = 1;
		if($hide == 'false') $hide = 0;
		echo $this->experiment_model->set_experiment_hidden($id, $hide);
	}
	
	public function create()
	{
		$this->load->helper('url');
		
		$data['reactivities'] = $this->product_model->get_reactivity();
		
		$this->load->helper('form');
		$this->load->library('form_validation');
	
		$data['title'] = 'Create Experiment';
	
		$this->form_validation->set_rules('name', 'Name', 'required');
	
		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header', $data);
			$this->load->view('experiment/create', $data);
			$this->load->view('templates/footer');
	
		}
		else
		{
			$this->experiment_model->set_experiment();
			redirect('/experiment/', 'refresh');
		}
	}

	public function delete($id)
	{
		$this->load->helper('url');
		
		$this->experiment_model->delete_experiment($id);
		redirect('/experiment/', 'refresh');
	}
}