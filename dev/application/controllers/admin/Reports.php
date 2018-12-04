<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reports extends MY_Controller {

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
	
	//display search keywords
    public function search($country = '') {

        $this->data['module_name'] = 'Reports | Search Log';
        $this->data['section_title'] = 'Reports | Search Log';

        if($country != '') {
            $this->data['selected'] = $country;

            $search_array = array('deleted' => 0);
            $condition_log = "find_in_set('".$country."', country_ids) <> 0";
            $this->data['search_log'] = $this->common->select_data_by_search('search_log', $condition_log, $search_array, '*', $sortby = 'search_count', $orderby = 'DESC', $limit = '', $offset = '', $join_str = array());    
        } else {		
            $this->data['selected'] = '';

            $contition_array = array('deleted' => 0);
    		$this->data['search_log'] = $this->common->select_data_by_condition('search_log', $contition_array, '*', $sortby = 'search_count', $orderby = 'DESC', $limit = '', $offset = '', $join_str = array(), $group_by = '');
        }

		$this->data['country_list'] = $this->common->select_data_by_condition('countries', $contition_array = array(), '*', $short_by = 'country_name', $order_by = 'ASC', $limit = '', $offset = '');

        /* Load Template */
        $this->template->admin_render('admin/reports/search', $this->data);
    }

    //update the user detail
    public function view($id = '') {

        $users_detail = $this->common->select_data_by_id('users', 'id', $id, '*');
        if ($users_detail[0]['id'] != '') {
            $this->data['module_name'] = 'Users';
            $this->data['section_title'] = 'View Users';
            $this->data['users_detail'] = $users_detail;

            $this->load->view('admin/users/view', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Errorout Occurred. Try Again.');
            redirect('admin/users', 'refresh');
        }
    }
    
    // Reset User Password
    public function reset_password($id = '') {

        $transactions_detail = $this->common->select_data_by_id('user_transactions', 'user_id', $id, '*');
        
        if (count($transactions_detail) > 0) {
            $this->data['module_name'] = 'View User Transaction';
            $this->data['section_title'] = 'View User Transaction';
            $this->data['transactions_detail'] = $transactions_detail;

            $this->load->view('users/transaction', $this->data);
        } else {
            $this->session->set_flashdata('error', 'No transaction found.');
            redirect('admin/users', 'refresh');
        }
    }

    // get users from keyword
    public function users($id = '', $country = '') {
        // Get Log Details
        $log_detail = $this->common->select_data_by_id('search_log', 'slog_id', $id, '*');

        $this->data['module_name'] = 'Search Log | '.$log_detail[0]['search_keyword'];
        $this->data['section_title'] = 'Search Log | '.$log_detail[0]['search_keyword'];

        $data = 'id, name, email, photo, gender, dob, country';
        
        $condition_log = "`id` IN (".$log_detail[0]['user_ids'].")";
        $this->data['user_list'] = $this->common->select_data_by_search('users', $condition_log, $condition_array = array(), $data, $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str = array());

        $this->data['country'] = $country;

        /* Load Template */
        $this->template->admin_render('admin/reports/users', $this->data);
    }

    // users delete
    public function delete($id = '') {
        // $delete_result = $this->common->delete_data('search_log', 'slog_id', $id);
        $update_data = array('deleted' => 1);
        $update_result = $this->common->update_data($update_data, 'search_log', 'slog_id', $id);

        if ($update_result) {
            $this->session->set_flashdata('success', 'Log successfully removed');
            redirect('admin/reports/search', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
            redirect('admin/reports/search', 'refresh');
        }
    }

}

?>
