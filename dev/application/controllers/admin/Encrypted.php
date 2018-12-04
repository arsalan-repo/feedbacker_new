<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;

class Encrypted extends MY_Controller {

    public $data;

    public function __construct() {

        parent::__construct();

        //$GLOBALS['record_per_page']=10;
        //site setting details
        $this->load->model('common');
		$this->load->model('encrypted_model');
        $site_name_values = $this->common->select_data_by_id('settings', 'setting_id', '1', '*');

        $this->data['site_name'] = $site_name = $site_name_values[0]['setting_value'];
        //set header, footer and leftmenu
        $this->data['title'] = 'Encrypted Titles | ' . $site_name;

        $this->aws_client = ClientBuilder::create()->setHosts(["search-feedbacker-q3gdcfwrt27ulaeee5gz3zbezm.eu-west-1.es.amazonaws.com:80"])->build(); 


        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    //display users list
    public function index() {
		
        $this->data['module_name'] = 'Encrypted Titles';
        $this->data['section_title'] = 'Encrypted';
        $titles=$this->encrypted_model->get_encrypted(0,20);	
		$this->data['titles']=$titles;
        $this->template->admin_render('admin/encrypted/index', $this->data);
    }
	
	public function link_user($title_id,$user_id){		
		$title=$this->encrypted_model->get_title($title_id,$user_id);
		
		if(!empty($title)){
			if(!$this->encrypted_model->is_title_linked_user($title_id,$user_id)){
				$post=array('title_id'=>$title_id,'sender_id'=>$user_id,'user_id'=>$user_id);
				$this->common->insert_data($post, $tablename = 'encrypted_titles_users');
				$this->encrypted_model->accept_request($title_id,$user_id);				
				redirect('admin/encrypted/join_requests/'.$title['title_id']);
			}		
		}
		redirect('admin/encrypted/join_requests/'.$title_id);	
	}
	public function feedbacks($id) {
        $this->data['module_name'] = 'Encrypted Title Feedbacks';
        $this->data['section_title'] = 'Encrypted Feedbacks';       
		$feedbacks=$this->encrypted_model->get_title_feedbacks($id);
		$this->data['feedbacks']=$feedbacks;
		$this->data['title_id']=$id;
        $this->template->admin_render('admin/encrypted/feedbacks', $this->data);
    }
	public function linked_users($id) {
        $this->data['module_name'] = 'Encrypted Title Linked Users';
        $this->data['section_title'] = 'Encrypted Linked Users';   				
		$linked_users=$this->encrypted_model->linked_users($id,'');
		$this->data['linked_users']=$linked_users;
		$this->data['title_id']=$id;
        $this->template->admin_render('admin/encrypted/linked_users', $this->data);
    }
	public function join_requests($id) {
        $this->data['module_name'] = 'Encrypted Title Join Requests';
        $this->data['section_title'] = 'Encrypted Join Requests';   				
		$linked_users=$this->encrypted_model->get_title_join_requests($id);
		$this->data['linked_users']=$linked_users;
		$this->data['title_id']=$id;
        $this->template->admin_render('admin/encrypted/join_requests', $this->data);
    }
	public function json_list(){
		
		$start=isset($_REQUEST['start'])? intval($_REQUEST['start']):0;
		$length=isset($_REQUEST['length'])? intval($_REQUEST['length']):20;
		$draw=isset($_REQUEST['draw'])? intval($_REQUEST['draw']):1;
		
		$orderArr=isset($_REQUEST['order'])? $_REQUEST['order']:array();
		$order=array('column'=>0,'dir'=>'DESC');
		if(count($orderArr)>0)
			$order=$orderArr[0];		
		
		
		$search='';		
		$titles=$this->encrypted_model->get_encrypted($start,$length,$search);		
		$rows=array();
		foreach ($titles as $title) {
            $row = array();			
            $row[] = $title['title_id'];
            $row[] = $title['title'];
            $row[] = $title['name'];
			$row[] = '<a href="'.site_url('admin/encrypted/linked_users/'.$title['title_id']).'">'.$title['linked_users_count'].'</a>';
			//$row[] = '<a href="'.site_url('admin/encrypted/feedbacks/'.$title['title_id']).'">'.$title['feedbacks_count'].'</a>';
            $row[] = ($title['is_survey'])? 'Yes': 'No';     
			 $row[] = ($title['is_public'])? 'Yes': 'No';  
            $row[] = $title['time'];
			$row[]='<a href="'.base_url('admin/encrypted/edit/' . $title['title_id']).'" id="edit_btn" title="Edit Feedback">
                                                <button type="button" class="btn btn-primary" style="margin-top: 3px;"><i class="icon-pencil"></i> <i class="fa fa-pencil-square-o"></i></button>
                                            </a>
                                            <a data-href="'.base_url('admin/encrypted/delete/' . $title['title_id']).'" id="delete_btn" data-toggle="modal" data-target="#confirm-delete" href="#" title="Delete Feedback">
                                                <button type="button" class="btn btn-primary" style="margin-top: 3px;"><i class="icon-trash"></i> <i class="fa fa-ban"></i></button>
                                            </a>';
            $rows[] = $row;
        }
		 $output = array(
                        "draw" => $draw,
                        "recordsTotal" => $this->common->get_count_of_table('db_encrypted_titles',array('deleted'=>0)),
                        "recordsFiltered" => $this->common->get_count_of_table('db_encrypted_titles',array('deleted'=>0)),
                        "data" => $rows,
                );
        
        echo json_encode($output);
	}
    //update the user detail
    public function view($id = '') {

        $users_detail = $this->common->select_data_by_id('users', 'id', $id, '*');
        if ($users_detail[0]['id'] != '') {
            $this->data['module_name'] = 'Users';
            $this->data['section_title'] = 'View Users';
            $this->data['users_detail'] = $users_detail;

            $this->load->view('admin/feedbacks/view', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Errorout Occurred. Try Again.');
            redirect('admin/feedbacks', 'refresh');
        }
    }
    
    // feedbacks status change
    public function visibility($feedback_id = '', $status = '') {
        if ($feedback_id == '' || $status == '') {
            $this->session->set_flashdata('error', 'Error Occurred. Try Agaim!');
            redirect('admin/feedbacks', 'refresh');
        }
        if ($status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $update_data = array('status' => $status);

        $update_result = $this->common->update_data($update_data, 'feedback', 'feedback_id', $feedback_id);
        if ($update_result) {
            $this->session->set_flashdata('success', 'Visibility successfully updated');
            redirect('admin/feedbacks', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
            redirect('admin/feedbacks', 'refresh');
        }
    }

   

    //update the feedback detail
    public function edit($id = '') {

        if ($this->input->post('title_id')) {          
            $update_array = array(
				'title' => trim($this->input->post('title'))
            );
			$countryList=$this->input->post('country');
			
			if(!empty($countryList) && count($countryList)>0){
				$update_array['is_public']=1;
				$update_array['allowed_countries']=implode(",",$countryList);
				
			}
			/*$sql=mysql_fetch_array(mysql_query("SELECT * 
    FROM content_table 
    WHERE country_code = '' 
    OR country_code REGEXP ',?". $user_country_code .",?'"));
	*/
			$update_result = $this->common->update_data($update_array, 'encrypted_titles', 'title_id', $this->input->post('title_id'));
            if ($update_result) {
                $this->session->set_flashdata('success', 'Encrypted successfully updated.');
                redirect('admin/encrypted/edit/'.$id, 'refresh');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
                redirect('admin/encrypted', 'refresh');
            }
        }
		$title=$this->encrypted_model->get_title($id);        
        if (!empty($title)) {
            $this->data['module_name'] = 'Encrypted Management';
            $this->data['section_title'] = 'Edit Encrypted';
			$this->data['title'] = 'Edit Encrypted';
            $this->data['title_detail'] = $title;
			$this->data['country_list'] = $this->common->select_data_by_condition('countries', $contition_array = array(), '*', $short_by = 'country_name', $order_by = 'ASC', $limit = '', $offset = '');
            $this->template->admin_render('admin/encrypted/edit', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
            redirect('admin/encrypted', 'refresh');
        }
    }

    // feedbacks delete
    public function delete($id = '') {		
		$this->encrypted_model->delete_encrypted_title($id);	
		$this->session->set_flashdata('success', 'Encrypted title successfully deleted');		
		redirect('admin/encrypted');	
        
    }
	public function delete_feedback($title_id,$feedback_id) {		
		$this->encrypted_model->delete_feedback($title_id,$feedback_id);	
		$this->session->set_flashdata('success', 'Feedback successfully deleted');		
		redirect('admin/encrypted/feedbacks/'.$title_id);	
        
    }
	public function remove_linked_user($title_id,$user_id) {		
		$this->encrypted_model->remove_linked_user($title_id,$user_id);	
		$this->session->set_flashdata('success', 'User successfully removed');		
		redirect('admin/encrypted/linked_users/'.$title_id);	
        
    }
	
	
	public function delete_trash($id = '') {
        $delete_result = $this->common->delete_data('feedback', 'feedback_id', $id);        
        if ($delete_result) {
            $this->session->set_flashdata('success', 'Feedback successfully deleted');
            redirect('admin/feedbacks/trash', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
            redirect('admin/feedbacks/trash', 'refresh');
        }
    }
    public function do_upload_multiple_files($fieldName, $options) {

        $response = array();
        $files = $_FILES;
        $cpt = count($_FILES[$fieldName]['name']);
        for ($i = 0; $i < $cpt; $i++) {
            $_FILES[$fieldName]['name'] = $files[$fieldName]['name'][$i];
            $_FILES[$fieldName]['type'] = $files[$fieldName]['type'][$i];
            $_FILES[$fieldName]['tmp_name'] = $files[$fieldName]['tmp_name'][$i];
            $_FILES[$fieldName]['error'] = $files[$fieldName]['error'][$i];
            $_FILES[$fieldName]['size'] = $files[$fieldName]['size'][$i];

            $this->load->library('upload');
            $this->upload->initialize($options);

            //upload the image
            if (!$this->upload->do_upload($fieldName)) {
                $response['error'][] = $this->upload->display_errors();
            } else {
                $response['result'][] = $this->upload->data();
                $users_thumb[$i]['image_library'] = 'gd2';
                $users_thumb[$i]['source_image'] = $this->config->item('users_main_upload_path') . $response['result'][$i]['file_name'];
                $users_thumb[$i]['new_image'] = $this->config->item('users_thumb_upload_path') . $response['result'][$i]['file_name'];
                $users_thumb[$i]['create_thumb'] = TRUE;
                $users_thumb[$i]['maintain_ratio'] = FALSE;
                $users_thumb[$i]['thumb_marker'] = '';
                $users_thumb[$i]['width'] = $this->config->item('users_thumb_width');
                $users_thumb[$i]['height'] = $this->config->item('users_thumb_height');
                $instanse = "image_$i";
                //Loading Image Library
                $this->load->library('image_lib', $users_thumb[$i], $instanse);
                $dataimage = $response['result'][$i]['file_name'];

                //Creating Thumbnail
                $this->$instanse->resize();
                $response['error'][] = $thumberror = $this->$instanse->display_errors();
            }
        }

        return $response;
    }

    public function remove_photo($feedback_id = '') {
        if ($feedback_id != '') {

            $update_array['feedback_img'] = '';
            $update_array['feedback_thumb'] = '';

            $update_result = $this->common->update_data($update_data, 'feedback', 'feedback_id', $id);

            if ($update_result) {
                $this->session->set_flashdata('success', 'Photo successfully removed.');
                redirect('admin/feedbacks/edit/' . $user_id);
            } else {
                $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
                redirect('admin/feedbacks', 'refresh');
            }
        }
    }

    public function remove_video($user_id = '') {
        if ($feedback_id != '') {

            $update_array['feedback_video'] = '';
            $update_array['feedback_thumb'] = '';
            
            $update_result = $this->common->update_data($update_data, 'feedback', 'feedback_id', $id);

            if ($update_result) {
                $this->session->set_flashdata('success', 'Video successfully removed.');
                redirect('admin/feedbacks/edit/' . $user_id);
            } else {
                $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
                redirect('admin/feedbacks', 'refresh');
            }
        }
    }


    public function make_aws_index() {
        $title_detail = $this->common->select_data_by_condition('feedback',['deleted' => 0]);

        foreach ($title_detail as $key => $value) {
            $params = ['index' => 'feedback'];
            $response = $this->aws_client->indices()->exists($params);

            if(!$response){
                $indexParams = [
                    'index' => 'feedback',
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
                'index' => 'feedback',
                'type' => 'feedback_type',
                'id' => $value['feedback_id'],
                'body' => $value
            ]; 

            $response = $this->aws_client->index($docParams);
        }
        echo "Done all feedback indexing";die;
    }

}

?>
