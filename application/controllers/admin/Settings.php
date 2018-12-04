<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings extends MY_Controller {

    public $data;

    public function __construct() {

        parent::__construct();

        //$GLOBALS['record_per_page']=10;
        //site setting details
		$this->load->model('common');
        $site_name_values = $this->common->select_data_by_id('settings', 'setting_id', '1', '*');
		
        $this->data['site_name'] = $site_name = $site_name_values[0]['setting_value'];
        //set header, footer and leftmenu
        $this->data['title'] = 'Settings | ' . $site_name;

        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    //display users list
    public function index() {

        $this->data['module_name'] = 'Settings';
        $this->data['section_title'] = 'Settings';

        $this->data['setting_list'] = $this->common->select_data_by_condition('settings', $contition_array = array(), $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array());
        /* Load Template */
        $this->template->admin_render('admin/settings/index', $this->data);
    }

    //add new counselor
    public function add() {
        //check post and save data
        if ($this->input->post('btn_save')) {

            $insert_array = array(
                
                // 'profile_pic' =>  $this->upload->data()['photo'],
                'first_name' => trim($this->input->post('first_name')),
                'last_name' => trim($this->input->post('last_name')),
                'email' => trim($this->input->post('email')),
                'username' => trim($this->input->post('username')),
                'password' => md5($this->input->post('password')),
                'group_id' => 2,
                'status' => 1
                
            );
            
            $insert_result = $this->common->insert_data_getid($insert_array, 'users');

            $counselor_array = array(
                
                'user_id' => $insert_result,
                'phone_number' => trim($this->input->post('phone_number')),
                'counselor_fee' => trim($this->input->post('counselor_fee')),
                'gender' => trim($this->input->post('gender')),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'zipcode' => $this->input->post('zipcode'),
                
            );

            $counselor_result = $this->common->insert_data($counselor_array, 'settings');
            
            if ($insert_result) {
                // Send Welcome Mail
                $this->load->library('email');
                $config['protocol'] = "smtp";
                $config['smtp_host'] = "ssl://smtp.gmail.com";
                $config['smtp_port'] = "465";
                $config['smtp_user'] = "zaptest12@gmail.com"; 
                $config['smtp_pass'] = "zaptest123#";
                $config['charset'] = "utf-8";
                $config['mailtype'] = "html";
                $config['newline'] = "\r\n";

                $this->email->initialize($config);

                $this->email->from('noreply@mec.com', 'MEC');
                $this->email->to($this->input->post('email'));
                $this->email->subject('Welcome to MEC');

                $data['site_url'] = base_url();
                $data['logo'] = base_url('images/footer-logo.png');
                $data['name'] = ucfirst($this->input->post('first_name'));
                $data['username'] = trim($this->input->post('username'));
                $data['password'] = $this->input->post('password');
                
                $body = $this->load->view('emails/welcome_counselor.php',$data,TRUE);
                $this->email->message($body);
                $this->email->send();

                $this->session->set_flashdata('success', 'Counselor successfully inserted.');
                redirect('settings');
            } else {
                $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
                redirect('settings');
            }
        }

        $this->data['module_name'] = 'Counselor Management';
        $this->data['section_title'] = 'Add Counselor';

        /* Load Template */
        $this->template->admin_render('admin/settings/add', $this->data);
    }

    //update the user detail
    public function edit($id = '') {

        if ($this->input->post('setting_id')) {
          
            $settings_array = array(
                
                'setting_id' => $this->input->post('setting_id'),
                'setting_value' => $this->input->post('setting_value'),

            );

            $update_settings = $this->common->update_data($settings_array, 'settings', 'setting_id', $this->input->post('setting_id'));
           
            if ($update_settings) {
                $this->session->set_flashdata('success', 'Settings successfully updated.');
                redirect('admin/settings');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
                redirect('admin/settings');
            }
        }

        $setting_detail = $this->common->select_data_by_id('settings', 'setting_id', $id, '*');
        if (!empty($setting_detail)) {
            $this->data['module_name'] = 'Settings Management';
            $this->data['section_title'] = 'Edit Settings';
            $this->data['setting_detail'] = $setting_detail;
			
            /* Load Template */
	        $this->template->admin_render('admin/settings/edit', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
            redirect('admin/settings');
        }
    }

    // users delete
    public function delete($id = '') {
        $delete_result = $this->common->delete_data('users', 'id', $id);
        $delete_counselor = $this->common->delete_data('settings', 'user_id', $id);

        if ($delete_result) {
            $this->session->set_flashdata('success', 'Counselor successfully deleted');
            redirect('admin/settings');
        } else {
            $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
            redirect('admin/settings');
        }
    }

}

?>
