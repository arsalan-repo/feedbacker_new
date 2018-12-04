<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    /*public $data;
*/
    public function __construct() {

        parent::__construct();

        //site setting details
        $this->load->model('common');
        $site_name_values = $this->common->select_data_by_id('settings', 'setting_id', '1', '*');

        $this->data['site_name'] = $site_name = $site_name_values[0]['setting_value'];
        //set header, footer and leftmenu
        $this->data['title'] = 'Dashboard | ' . $site_name;

        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    //display dashboard
    public function index() {
        $this->data['section_title'] = 'Dashboard';
		
		// Get users count
        $this->data['users_count']=  count($this->common->select_data_by_condition('users', $condition_array=array(),'id'));
		
		// Get titles count
		$this->data['titles_count']=  count($this->common->select_data_by_condition('titles', $condition_array=array(),'title_id'));
		
		// Get feedbacks count
		$this->data['feedbacks_count']=  count($this->common->select_data_by_condition('feedback', $condition_array=array(),'feedback_id'));
        
        /* Load Template */
        $this->template->admin_render('admin/dashboard/index', $this->data);
    }

    //logout user
    public function logout() {
        if ($this->session->userdata('mec_admin')) {
            $this->session->unset_userdata('mec_admin');
            redirect('secureadmin20185132', 'refresh');
        } else {
            redirect('secureadmin20185132', 'refresh');
        }
    }

    public function edit_profile() {
        
        if($this->data['loged_in_user'][0]['level'] != '1'){
            $this->session->set_flashdata('error','You are not authorized.');
            redirect('dashboard','refresh');
        }
        
        if($this->input->post('email')){
            $email=$this->input->post('email');
            $user_name=$this->input->post('user_name');
            $name=$this->input->post('name');
            $user_id=$this->session->userdata('mec_admin');
            
            $update_result=  $this->common->update_data($this->input->post(),'user','user_id',$user_id);
            if($update_result){
                $this->session->set_flashdata('success','Profile detail successfully updated.');
                redirect('dashboard','refresh');
            }
            else{
                $this->session->set_flashdata('error','Error Occurred. Try Again!');
                redirect('dashboard','refresh');
            }
        }
        
        $this->data['module_name'] = 'Dashboard';
        $this->data['section_title'] = 'Edit Profile';
        $this->template->admin_render('admin/dashboard/edit_profile', $this->data);
    }

    

    public function change_password() {

 
        if($this->input->post('old_pass')){
            
            $user_id = ($this->session->userdata('mec_admin'));
            $old_password=$this->input->post('old_pass');
            $new_password=  $this->input->post('new_pass');
          
            $admin_old_password = $this->common->select_data_by_id('admin','id',$user_id,'password');
            $admin_password = $admin_old_password[0]['password'];

            if($admin_password == md5($old_password)){
                $update_array=array('password'=> md5($new_password));
                $update_result=  $this->common->update_data($update_array,'admin','id',$user_id);
                if($update_result){
                    $this->session->set_flashdata('success','Your password is successfully changed.');
                    redirect('admin/dashboard/change_password','refresh');
                }
                else{
                    $this->session->set_flashdata('error','Error Occurred. Try Again!');
                    redirect('admin/dashboard/change_password','refresh');
                }
            }
            else{
                $this->session->set_flashdata('error','Old password does not match');
                redirect('admin/dashboard/change_password','refresh');
            }
        }
        
        $this->data['module_name'] = 'Dashboard';
        $this->data['section_title'] = 'Change Password';
        $this->template->admin_render('admin/dashboard/change_password', $this->data);
    }

    
    //check old password
    public function check_old_pass() {
        if ($this->input->is_ajax_request() && $this->input->post('old_pass')) {
            $user_id = ($this->session->userdata('mec_admin'));

            $old_pass = $this->input->post('old_pass');
            $check_result = $this->common->select_data_by_id('user','user_id',$user_id,'password');
            if ($check_result[0]['password'] === md5($old_pass)) {
                echo 'true';
                die();
            } else {
                echo 'false';
                die();
            }
        }
    }
    
    public function check_email() {
        if ($this->input->is_ajax_request() && $this->input->post('email')) {
            $user_id = ($this->session->userdata('mec_admin'));

            $email = $this->input->post('email');
            $check_result = $this->common->check_unique_avalibility('user','email',$email,'user_id',$user_id);
            if ($check_result) {
                echo 'true';
                die();
            } else {
                echo 'false';
                die();
            }
        }
    }
    
    public function check_username() {
        if ($this->input->is_ajax_request() && $this->input->post('user_name')) {
            $user_id = ($this->session->userdata('mec_admin'));

            $user_name = $this->input->post('user_name');
            $check_result = $this->common->check_unique_avalibility('user','user_name',$user_name,'user_id',$user_id);
            if ($check_result) {
                echo 'true';
                die();
            } else {
                echo 'false';
                die();
            }
        }
    }

}

?>