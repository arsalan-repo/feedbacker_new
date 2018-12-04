<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Emailtemplate extends MY_Controller {

    public $data;
   

    public function __construct() {

        parent::__construct();
        
       
        //site setting details
        $site_name_values = $this->common->select_data_by_id('settings', 'setting_id', '1', '*');
        $this->data['site_name'] = $site_name = $site_name_values[0]['setting_value'];
        //set header, footer and leftmenu
        $this->data['title'] = 'EmailTemplate : ' . $site_name;
        $this->data['header'] = $this->load->view('header', $this->data);
        $this->data['leftmenu'] = $this->load->view('leftmenu', $this->data);
        $this->data['footer'] = $this->load->view('footer', $this->data,true);


        $this->load->model('common');

        //Loadin Pagination Custome Config File
        $this->config->load('paging', TRUE);
        $this->paging = $this->config->item('paging');
        //print_r($this->paging);
        //die();

        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    //display user list
   public function index() {

        if ($this->session->userdata('user_search_keyword')) {
            $this->session->unset_userdata('user_search_keyword');
        }

        $this->data['module_name'] = 'EmailTemplate';
        $this->data['section_title'] = 'EmailTemplate';
        
        $limit =$this->paging['per_page'];
        
        if ($this->uri->segment(3) != '' && $this->uri->segment(4) != '') {
            $offset = ($this->uri->segment(5) != '') ? $this->uri->segment(5) : 0;
            $short_by = $this->uri->segment(3);
            $order_by = $this->uri->segment(4);
        } else {
            $offset = ($this->uri->segment(3) != '') ? $this->uri->segment(3) : 0;
            $short_by = 'template_title';
            $order_by = 'asc';
        }

        $this->data['offset'] = $offset;
        $contition_array=array();
        //$contition_array = array('username != ' => 'client');
        
        $this->data['emailtemplate_list'] = $this->common->select_data_by_condition('emailtemplate', $contition_array, '*', $short_by, $order_by, $limit, $offset);
        if ($this->uri->segment(3) != '' && $this->uri->segment(4) != '') {
            $this->paging['base_url'] = site_url("emailtemplate/index/" . $short_by . "/" . $order_by);
        } else {
            $this->paging['base_url'] = site_url("emailtemplate/index/");
        }
        if ($this->uri->segment(3) != '' && $this->uri->segment(4) != '') {
            $this->paging['uri_segment'] = 5;
        } else {
            $this->paging['uri_segment'] = 3;
        }
        //for my use
        
        //$this->data['offset']=$offset;
        
        
        
        
        $this->paging['total_rows'] = count($this->common->select_data_by_condition('emailtemplate', $contition_array, 'templateid'));
        $this->data['total_rows']=$this->paging['total_rows'];
        $this->data['limit']=$limit;
        //$this->paging['per_page'] = 2;

        $this->pagination->initialize($this->paging);
        $this->data['search_keyword'] = '';
        $this->load->view('emailtemplate/index', $this->data);
    }

    //search the user
   

    //add new user
  

    //update the user detail
    public function edit($id = '') {
       
        if ($this->input->post('templateid')) {
          
            $update_array = array(
                'subject'=>trim($this->input->post('subject')),
                'emailformat'=>trim($this->input->post('emailformat')),
            );
           
            
            $update_result = $this->common->update_data($update_array, 'emailtemplate', 'templateid', $this->input->post('templateid'));
           
            if ($this->input->post('redirect_url')) {
                $redirect_url = $this->input->post('redirect_url');
            } else {
                $redirect_url = site_url('emailtemplate') ;
            }

            if ($update_result) {
                
                $this->session->set_flashdata('success', 'EmailTemplate successfully updated.');
                redirect($redirect_url, 'refresh');
            } else {
                $this->session->set_flashdata('error', 'Errorin Occurred. Try Again!');
                redirect($redirect_url, 'refresh');
            }
        }

        $emailtemplate_detail = $this->common->select_data_by_id('emailtemplate', 'templateid', $id, '*');
        if (!empty($emailtemplate_detail)) {
            $this->data['module_name'] = 'EmailTemplate';
            $this->data['section_title'] = 'Edit';
            
            $this->data['emailtemplate_detail'] = $emailtemplate_detail;
            $this->load->view('emailtemplate/edit', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Errorout Occurred. Try Again.');
            redirect('emailtemplate', 'refresh');
        }
    }
   

}

?>