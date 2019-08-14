<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	// protected $logout_url, $login_url;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('model','',FALSE);
		$this->output->set_header( "Access-Control-Allow-Origin: *" );
		$this->output->set_header( "Access-Control-Allow-Credentials: true" );
		$this->output->set_header( "Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS" );
		$this->output->set_header( "Access-Control-Max-Age: 604800" );
		$this->output->set_header( "Access-Control-Request-Headers: x-requested-with" );
		$this->output->set_header( "Access-Control-Allow-Headers: x-requested-with, x-requested-by" );	

        // $this->load->model('login_model');
		$this->getMember = $this->model->get_where('user',array('usernameUser'=>$this->session->userdata('usernameUser')));
		$this->index = base_url(getModule().'/'.getController());

		if (!$this->session->userdata('logged_in')) {
			redirect_back(current_url());
			redirect('app/login');
		}	
		// $this->post = $this->input->post();
	}

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */