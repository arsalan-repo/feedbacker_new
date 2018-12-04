<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;

class Titles extends MY_Controller {

    public $data;

    public function __construct() {

        parent::__construct();

        //$GLOBALS['record_per_page']=10;
        //site setting details
        $this->load->model('common');
        $site_name_values = $this->common->select_data_by_id('settings', 'setting_id', '1', '*');

        $this->data['site_name'] = $site_name = $site_name_values[0]['setting_value'];
        //set header, footer and leftmenu
        $this->data['title'] = 'Titles | ' . $site_name;

        $this->aws_client = ClientBuilder::create()->setHosts(["search-feedbacker-q3gdcfwrt27ulaeee5gz3zbezm.eu-west-1.es.amazonaws.com:80"])->build(); 

        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    //display titles list
    public function index() {

        $this->data['module_name'] = 'Titles';
        $this->data['section_title'] = 'Titles';

		$join_str = array(
			array(
				'table' => 'followings',
				'join_table_id' => 'followings.title_id',
				'from_table_id' => 'titles.title_id',
				'join_type' => 'left'
			),
			array(
				'table' => 'users',
				'join_table_id' => 'users.id',
				'from_table_id' => 'titles.user_id',
				'join_type' => 'left'
			)
		);
		
		$data = 'titles.title_id, titles.title, titles.datetime, users.name as created_by, count('.$this->db->dbprefix('followings').'.title_id) as followers';
		
        $contition_array = array('titles.deleted' => 0);
		$this->data['title_list'] = $this->common->select_data_by_condition('titles', $contition_array, $data, $sortby = 'titles.datetime', $orderby = 'DESC', $limit = '', $offset = '', $join_str, $group_by = 'titles.title_id');

        /* Load Template */
        $this->template->admin_render('admin/titles/index', $this->data);
    }

    //update the user detail
    public function view($id = '') {

        $titles_detail = $this->common->select_data_by_id('titles', 'title_id', $id, '*');

        if ($titles_detail[0]['title_id'] != '') {
            $this->data['module_name'] = 'Titles';
            $this->data['section_title'] = 'View Titles';
            $this->data['titles_detail'] = $titles_detail;

            $this->load->view('admin/titles/view', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Error Occurred. Try Again.');
            redirect('admin/titles', 'refresh');
        }
    }
    
    // titles status change
    public function change_status($title_id = '', $status = '') {
        if ($title_id == '' || $status == '') {
            $this->session->set_flashdata('error', 'Error Occurred. Try Agaim!');
            redirect('titles', 'refresh');
        }
        if ($status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $update_data = array('status' => $status);

        $update_result = $this->common->update_data($update_data, 'titles', 'id', $title_id);
        if ($update_result) {
            $this->session->set_flashdata('success', 'Titles status successfully updated');
            redirect('admin/titles', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
            redirect('admin/titles', 'refresh');
        }
    }

    //add new user
    public function add() {
        //check post and save data
        if ($this->input->post('btn_save')) {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('title', 'Title', 'required|is_unique[titles.title]');

            if ($this->form_validation->run() === FALSE){
                $this->session->set_flashdata('error', validation_errors());
                redirect('admin/titles');
            } else {

                $insert_array = array(
                    'title' => trim($this->input->post('title')),
					'datetime' => date('Y-m-d H:i:s'),
                );
				
				$insert_result = $this->common->insert_data($insert_array, 'titles');


               /* $params = ['index' => 'title'];
                $response = $this->aws_client->indices()->exists($params);

                if(!$response){
                    $indexParams = [
                        'index' => 'title',
                        'body' => [
                            'settings' => [
                                'number_of_shards' => 5,
                                'number_of_replicas' => 1
                            ]
                        ]
                    ];

                    $response = $this->aws_client->indices()->create($indexParams);
                }

                $docParams = [
                    'index' => 'title',
                    'type' => 'title_type',
                    'id' => $insert_result,
                    'body' => ['title' => trim($this->input->post('title')),'title_id' => $insert_result]
                ]; 

                $response = $this->aws_client->index($docParams); 
*/
                if ($insert_result) {                    
                    $this->session->set_flashdata('success', 'Title successfully inserted.');
                    redirect('admin/titles', 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
                    redirect('admin/titles', 'refresh');
                }
            }
        }

        $this->data['module_name'] = 'Title Management';
        $this->data['section_title'] = 'Add Title';
		
        /* Load Template */
        $this->template->admin_render('admin/titles/add', $this->data);
    }

    //update the user detail
    public function edit($id = '') {

        if ($this->input->post('title_id')) {
			
			$titles = $this->common->select_data_by_id('titles', 'titles.title_id', $this->input->post('title_id'), 'title');
            $original_value = $titles[0]['title'];

            // echo trim($this->input->post('title')) ."!=". $original_value; exit();
            
            if(trim($this->input->post('title')) != $original_value) {
               $is_unique =  '|is_unique[titles.title]';
            } else {
               $is_unique =  '';
            }

            $this->form_validation->set_rules('title', 'Title', 'required'.$is_unique);

            if ($this->form_validation->run() === FALSE){
                $this->session->set_flashdata('error', validation_errors());
                redirect('admin/titles');
            } else {
          
                $update_array = array(
    				'title' => trim($this->input->post('title'))
                );
                
                $update_result = $this->common->update_data($update_array, 'titles', 'title_id', $this->input->post('title_id'));


               /* $params = ['index' => 'title'];
                $response = $this->aws_client->indices()->exists($params);

                if(!$response){
                    $indexParams = [
                        'index' => 'title',
                        'body' => [
                            'settings' => [
                                'number_of_shards' => 5,
                                'number_of_replicas' => 1
                            ]
                        ]
                    ];

                    $response = $this->aws_client->indices()->create($indexParams);
                }

                $docParams = [
                    'index' => 'title',
                    'type' => 'title_type',
                    'id' => $this->input->post('title_id'),
                    'body' => ['title' => trim($this->input->post('title')),'title_id' => $this->input->post('title_id')]
                ]; 

                $response = $this->aws_client->index($docParams);
               */
                if ($update_result) {
                    $this->session->set_flashdata('success', 'Title successfully updated.');
                    redirect('admin/titles', 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
                    redirect('admin/titles', 'refresh');
                }
            }
        }

        $title_detail = $this->common->select_data_by_id('titles', 'title_id', $id, '*');
        if (!empty($title_detail)) {
            $this->data['module_name'] = 'Title Management';
            $this->data['section_title'] = 'Edit Title';
            $this->data['title_detail'] = $title_detail;

            /* Load Template */
            $this->template->admin_render('admin/titles/edit', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
            redirect('admin/titles', 'refresh');
        }
    }

    // titles delete
    public function delete($id = '') {
        // $delete_result = $this->common->delete_data('titles', 'id', $id);
        $update_data = array('deleted' => 1);
        $update_result = $this->common->update_data($update_data, 'titles', 'title_id', $id);

        if ($update_result) {
          /*  $docParams = [
                'index' => 'title',
                'type' => 'title_type',
                'id' => $id
            ]; 

            $response = $this->aws_client->delete($docParams);
*/
            $this->session->set_flashdata('success', 'Title successfully deleted');
            redirect('admin/titles', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
            redirect('admin/titles', 'refresh');
        }
    }

    public function make_aws_index() {
        $title_detail = $this->common->select_data_by_condition('titles',['deleted' => 0]);

        foreach ($title_detail as $key => $value) {
            $params = ['index' => 'title'];
            $response = $this->aws_client->indices()->exists($params);

            if(!$response){
                $indexParams = [
                    'index' => 'title',
                    'body' => [
                        'settings' => [
                            'number_of_shards' => 5,
                            'number_of_replicas' => 1
                        ]
                    ]
                ];

                $response = $this->aws_client->indices()->create($indexParams);
            }

            $docParams = [
                'index' => 'title',
                'type' => 'title_type',
                'id' => $value['title_id'],
                'body' => ['title' => trim($value['title']),'title_id' => $value['title_id']]
            ]; 

            $response = $this->aws_client->index($docParams);
            //echo 'one done';die;
        }
        echo "Done all title indexing";die;
    }

}

?>
