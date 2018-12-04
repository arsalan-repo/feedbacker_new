<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {
	
	public $data;
	public $user;	
	private $perPage = 10;
    public function __construct() {
        parent::__construct();
		$this->load->library('s3');
		$this->load->library('template', 'facebook');

        $this->data['title'] = "User | Feedbacker ";
		if(isset($this->session->userdata['mec_user'])){
			$this->user = $this->session->userdata['mec_user'];
			$this->data['user_info'] = $this->user;
		}
        // Load Login Model
        $this->load->model('common');
		$this->load->model('encrypted_model');
		$this->load->model('title_model');
		
		
		// Load Language File		
		if ($this->user['language'] == 'ar') {
			$this->lang->load('message','arabic');
			$this->lang->load('label','arabic');
		} else {
			$this->lang->load('message','english');
			$this->lang->load('label','english');
		}	
       
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');		
    }
	
	public function index() {	
		$this->data['section_title'] = 'Privacy Policy - Feedbacker.me';	
		$this->template->front_render('page/privacy',$this->data);
		/*$this->load->view('_templates/header');
		$this->load->view('page/privacy');
		$this->load->view('_templates/footer');*/
	}
	public function privacy(){
		$this->data['section_title'] = 'Privacy Policy - Feedbacker.me';	
		$this->template->front_render('page/privacy',$this->data);
	}
	public function terms(){
		$this->data['section_title'] = 'Terms of Service - Feedbacker.me';	
		$this->template->front_render('page/terms',$this->data);
	}
	
 
}
