<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends CI_Controller {
	
	public $data;

    public function __construct() {
        parent::__construct();

        if ($this->session->userdata('mec_user')) {
            redirect('user/dashboard');
        }

        $this->data['title'] = "Sign Up | Feedbacker ";

        // Load Login Model
        $this->load->model('common');
		
		// Load Language File
		$this->lang->load('message','english');
		$this->lang->load('label','english');		
		
		if (isset($this->session->userdata['fb_lang'])) {
			if ($this->session->userdata['fb_lang'] == 'ar') {
				$this->lang->load('message','arabic');
				$this->lang->load('label','arabic');
			}
		}

        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

	public function index() {
		
		$this->data['country_list'] = $this->common->select_data_by_condition('countries', $contition_array = array(), '*', $short_by = 'country_name', $order_by = 'ASC', $limit = '', $offset = '');
		
		$this->data['module_name'] = 'Sign Up';
		$this->data['section_title'] = 'Sign Up';
		
		$this->load->view('user/signup', $this->data);
	}
	public function confirm() {		
		$this->data['module_name'] = 'Sign Up';
		$this->data['section_title'] = 'Sign Up';		
		$this->load->view('user/confirm', $this->data);
	}
	public function language($code,$method='') {
		$this->session->set_userdata('fb_lang', $code);
		if(empty($method))
			redirect('signup');
		else
			redirect('signup/'.$method);
	}
	
	public function submit() {
        $name = $this->input->post('name');
		$email = $this->input->post('email');
		$country = $this->input->post('country');
        $password = $this->input->post('password');
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('country', 'Country', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
		
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('signup');
		}
		
		// Add User in database
		$md5_password = md5($password);

		$insert_array['name'] = trim($name);
		$insert_array['email'] = trim($email);
		$insert_array['country'] = $country;
		$insert_array['password'] = trim($md5_password);
		$insert_array['status'] = 1;
		$insert_array['create_date'] = date('Y-m-d h:i:s');
		$insert_array['modify_date'] = date('Y-m-d h:i:s');
		$insert_array['verified']=0;
		if (isset($this->session->userdata['fb_lang'])) {			
			$lang_condition = array('lang_code' => $this->session->userdata['fb_lang']);
			$lang_info = $this->common->select_data_by_condition('languages', $lang_condition, 'lang_id, lang_code');
			
			$insert_array['lang_id'] = $lang_info[0]['lang_id'];
		}

		$insert_result = $this->common->insert_data_getid($insert_array, $tablename = 'users');
		
		if (!$insert_result) {
			$this->session->set_flashdata('error', $this->lang->line('error_something_wrong'));
			redirect('signup');
		}
		
		// Add User Notifications Preferences
		$insert_pref_1['user_id'] = $insert_result;
		$insert_pref_1['notification_id'] = 1;
		$insert_pref_1['status'] = 'on';
		$insert_pref_1['updated_on'] = date('Y-m-d h:i:s');
		
		$pref_result_1 = $this->common->insert_data($insert_pref_1, $tablename = 'user_preferences');
		
		$insert_pref_2['user_id'] = $insert_result;
		$insert_pref_2['notification_id'] = 2;
		$insert_pref_2['status'] = 'on';
		$insert_pref_2['updated_on'] = date('Y-m-d h:i:s');
		
		$pref_result_2 = $this->common->insert_data($insert_pref_2, $tablename = 'user_preferences');
		
		$insert_pref_3['user_id'] = $insert_result;
		$insert_pref_3['notification_id'] = 3;
		$insert_pref_3['status'] = 'on';
		$insert_pref_3['updated_on'] = date('Y-m-d h:i:s');
		
		$pref_result_3 = $this->common->insert_data($insert_pref_3, $tablename = 'user_preferences');
		
		$insert_pref_4['user_id'] = $insert_result;
		$insert_pref_4['notification_id'] = 4;
		$insert_pref_4['status'] = 'on';
		$insert_pref_4['updated_on'] = date('Y-m-d h:i:s');
		
		$pref_result_4 = $this->common->insert_data($insert_pref_4, $tablename = 'user_preferences');
		
		$condition_array = array('emailid' => '6');
        $emailformat = $this->common->select_data_by_condition('emails', $condition_array, '*');
		$mail_body = $emailformat[0]['varmailformat'];
		$activation_link=site_url('signup/confirm_email/'.md5($insert_result));
		$mail_body = html_entity_decode(str_replace("%activation_link%", $activation_link,stripslashes($mail_body)));
		$send_mail = $this->common->sendMail($email, '', $emailformat[0]['varsubject'], $mail_body);
		if ($send_mail) {
             $this->session->set_flashdata('success', $this->lang->line('success_msg_confirm_email'));
	         redirect('signup/confirm');
        } else {
            $this->session->set_flashdata('error', $this->lang->line('error_email_failed'));
			redirect('signup');
        }
		/*
		$user_info = array(
			'id'	=>	$insert_result,
			'name'	=>	$name,
			'email' =>	$email,
			'user_avatar'	=> ASSETS_URL . 'images/user-avatar.png',
			'country'		=> $country,
			'language'		=> 'en'
		);
		
		if (isset($this->session->userdata['fb_lang'])) {
			$user_info['language'] = $this->session->userdata['fb_lang'];
		}
		
		$this->session->set_userdata('mec_user', $user_info);
		
		$this->session->set_flashdata('success', $this->lang->line('success_msg_sinup_done'));
		
		if(!empty($this->session->userdata['redirect_url'])){
			$redirect_url=$this->session->userdata['redirect_url'];
			$this->session->unset_userdata('redirect_url');
			redirect($redirect_url);
		}		
		redirect('user/dashboard');*/
    }
	public function confirm_email($code){		
		$data = array('verified' => 1);
        $this->db->where('md5(id)',$code);
        $query=$this->db->update('users', $data);
		if($query){
			redirect();
		}else{
			redirect('signup');
		}
	}
}
