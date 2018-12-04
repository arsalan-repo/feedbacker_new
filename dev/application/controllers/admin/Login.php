<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    public $data;

    public function __construct() {
        parent::__construct();

        if ($this->session->userdata('mec_admin')) {
            redirect('admin/dashboard', 'refresh');
        }

        $this->data['title'] = "Login | Feedbacker ";

        // Load Login Model
        $this->load->model('admin/auth_Model');

        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    public function index() {

        $this->load->view('admin/login/index', $this->data);
    }

    public function authenticate() {
        $admin_user = $this->input->post('admin_user');
        $admin_password = $this->input->post('admin_password');

        if($admin_user == '')
        {
            $this->session->set_flashdata('error', '<div class="callout callout-danger"><p>Please Enter Email Id</p></div>');
            redirect('secureadmin20185132', 'refresh');
        }
        if($admin_password == '')
        {
            $this->session->set_flashdata('error', '<div class="callout callout-danger"><p>Please Enter Password</p></div>');
            redirect('secureadmin20185132', 'refresh');
        }

        if ($admin_user != '' && $admin_password != '') {
            $admin_check = $this->auth_Model->check_authentication($admin_user, $admin_password);

            if (count($admin_check) > 0 && $admin_check != 0) {
                $this->session->set_userdata('mec_admin', $admin_check[0]['id']);
                $this->session->set_userdata('mec_admin_data', $admin_check[0]);

                redirect('admin/dashboard', 'refresh');
            
            } else {
                $this->session->set_flashdata('error', '<div class="callout callout-danger"><p>Please Enter Valid Credential</p></div>');
                redirect('secureadmin20185132', 'refresh');
            }
        } else {
            $this->session->set_flashdata('error', '<div class="callout callout-danger"><p>Please Enter Valid Login Detail</p></div>');
            redirect('secureadmin20185132', 'refresh');
        }
    }

    public function forgot_password() {
        $forgot_email = $this->input->post('forgot_email');

        if ($forgot_email != '' && $forgot_email != '') {

            $forgot_email_check = $this->common->select_data_by_id('admin', 'admin_email', $forgot_email, '*', '');


            if (count($forgot_email_check) > 0) {

                $email_formate = $this->common->select_data_by_id('emails', 'emailid', '2', 'varsubject,varmailformat');
                $mail_body = str_replace("%name%", $forgot_email_check[0]['admin_name'], str_replace("%user_email%", $forgot_email_check[0]['admin_email'], str_replace("%password%", base64_decode($forgot_email_check[0]['admin_pwd']), stripslashes($email_formate[0]['varmailformat']))));
                $this->sendEmail($this->data['main_site_name'], $this->data['main_site_email'], $forgot_email, $email_formate[0]['varsubject'], $mail_body);

                $this->session->set_flashdata('success', '<div class="alert alert-success">Password successfully send in your email id.</div>');
                redirect('login', 'refresh');
            } else {

                $this->session->set_flashdata('error', '<div class="alert alert-danger">Please enter register email id.</div>');
                redirect('login', 'refresh');
            }
        } else {
            $this->session->set_flashdata('error', '<div class="alert alert-danger">Please enter email id.</div>');
            redirect('login', 'refresh');
        }
    }

    public function sendEmail($app_name = '', $app_email = '', $to_email = '', $subject = '', $mail_body = '') {


        //Loading E-mail Class
        $this->load->library('email');

//        $emailsetting = $this->common->select_data_by_condition('email_settings', array(), '*');

        $mail_html = '<table width="100%" cellspacing="10" cellpadding="10" style="background:#f1f1f1;" style="border:2px solid #ccc;" >
    <tr>
	   <td valign="center"><img src="' . base_url('assets/img/logo.png') . '" alt="' . $this->data['main_site_name'] . '" style="margin:0px auto;display:block;width:150px;"/></td> 
	</tr> 
<tr>
	<td>
		 
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<p>
                            "' . $mail_body . '"
                        </p>
		</table>
	</td>
</tr>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
     
			<tr>
			<td style="font-family:Ubuntu, sans-serif;font-size:11px; padding-bottom:15px; padding-top:15px; border-top:1px solid #ccc;text-align:center;background:#eee;"> &copy; ' . date("Y") . ' <a href="' . $this->data['main_site_url'] . '" style="color:#268bb9;text-decoration:none;"> ' . $this->data['main_site_name'] . '</a></td>
			</tr>
</table> 
</table>';

        //Loading E-mail Class
        //        $config['protocol'] = "smtp";
//        $config['smtp_host'] = $emailsetting[0]['host_name'];
//        $config['smtp_port'] = $emailsetting[0]['out_going_port'];
//        $config['smtp_user'] = $emailsetting[0]['user_name'];
//        $config['smtp_pass'] = $emailsetting[0]['password'];
//        $config['charset'] = "utf-8";
//        $config['mailtype'] = "html";
//        $config['newline'] = "\r\n";
//        $this->email->initialize($config);

        $this->email->from('makadiyaankit.developer@gmail.com', 'Photo Share');
        $this->email->to($to_email);
    //    $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message(html_entity_decode($mail_body));
        
      
        //    $this->email->cc($cc);

        $this->email->subject($subject);
        $this->email->message(html_entity_decode($mail_body));

        if ($this->email->send()) {
            return true;
        } else {
            return FALSE;
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */