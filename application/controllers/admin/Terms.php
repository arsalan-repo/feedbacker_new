<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Terms extends MY_Controller {

    public $data;

    public function __construct() {

        parent::__construct();

        //$GLOBALS['record_per_page']=10;
        //site setting details
        $this->load->model('common');
        $site_name_values = $this->common->select_data_by_id('settings', 'setting_id', '1', '*');

        $this->data['site_name'] = $site_name = $site_name_values[0]['setting_value'];
        //set header, footer and leftmenu
        $this->data['title'] = 'Reports | ' . $site_name;

        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }
	
	// get terms and conditions
    public function index() {
        $this->data['module_name'] = 'Terms And Conditions';
        $this->data['section_title'] = 'Terms And Conditions';
		
		$join_str = array(
			array(
				'table' => 'languages',
				'join_table_id' => 'languages.lang_code',
				'from_table_id' => 'pages.lang_code',
				'join_type' => 'inner'
			)
		);

        $condition_array = array('page_id' => 'terms_cond');
		$terms = $this->common->select_data_by_condition('pages', $condition_array, $data = 'languages.lang_name, pages.*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str, $group_by = '');

        $this->data['terms'] = $terms;

        /* Load Template */
        $this->template->admin_render('admin/terms/index', $this->data);
    }

    //update the terms
    public function edit($lang = '') {

        if ($this->input->post('btn_save')) {
          
            $update_array = array(
                'description' => $this->input->post('description')
            );

            $update_settings = $this->common->update_data($update_array, 'pages', 'id', $this->input->post('id'));
           
            if ($update_settings) {
                $this->session->set_flashdata('success', 'Terms successfully updated.');
                redirect('admin/terms');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
                redirect('admin/terms');
            }
        }
		
		$join_str = array(
			array(
				'table' => 'languages',
				'join_table_id' => 'languages.lang_code',
				'from_table_id' => 'pages.lang_code',
				'join_type' => 'inner'
			)
		);

        $condition_array = array('page_id' => 'terms_cond', 'pages.lang_code' => $lang);
		$terms = $this->common->select_data_by_condition('pages', $condition_array, $data = 'languages.lang_name, pages.*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str, $group_by = '');
		
        if (!empty($terms)) {
            $this->data['module_name'] = 'Terms And Conditions';
            $this->data['section_title'] = 'Edit Terms And Conditions';
            $this->data['terms'] = $terms;
			
            /* Load Template */
	        $this->template->admin_render('admin/terms/edit', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
            redirect('admin/terms');
        }
    }

}

?>
