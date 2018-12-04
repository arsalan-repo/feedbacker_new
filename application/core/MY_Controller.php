<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	public $data;
    public function __construct() {
        parent::__construct();
		
		if ($this->session->userdata('mec_admin')) {
			$admin_data=$this->session->userdata('mec_admin_data');		
			$admin_role=$admin_data['role'];	
			$this->data['admin_data']=$admin_data;			
			$this->data['admin_role']=$admin_role;
		}
        /* Load */
        $this->load->config('admin/dp_config');
        $this->load->library(array('form_validation', 'admin/template'));

        /* Data */
        $this->data['title']       = $this->config->item('title');
        $this->data['title_lg']    = $this->config->item('title_lg');
        $this->data['title_mini']  = $this->config->item('title_mini');
        $this->data['frameworks_dir'] = $this->config->item('frameworks_dir');
        $this->data['plugins_dir']    = $this->config->item('plugins_dir');
        $this->data['avatar_dir']     = $this->config->item('avatar_dir');
        
        // date_default_timezone_set('Africa/Cairo');
    }


    public function last_query() {
        echo "<pre>";
        echo $this->db->last_query();
        echo "</pre>";
    }
    
    public function sendEmail($app_name='',$app_email='',$to_email='',$subject='',$mail_body='')
    {
        $this->config->load('email', TRUE);
        $this->cnfemail = $this->config->item('email');

        //Loading E-mail Class
        $this->load->library('email');
        $this->email->initialize($this->cnfemail);
        
        $this->email->from($app_email,$app_name);
        $this->email->to($to_email);
        $this->email->subject($subject);

        $this->email->message("<table border='0' cellpadding='0' cellspacing='0'><tr><td></td></tr><tr><td>" . $mail_body . "</td></tr></table>");
        $this->email->send();
        return;
    }

}
