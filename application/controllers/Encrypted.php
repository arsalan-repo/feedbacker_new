<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;
class Encrypted extends CI_Controller {	
	public $data;	
	public $user;	
	private $aws_client;
    public function __construct() {
        parent::__construct();
        // Prevent access without login
		
		//print_r($_SERVER);
		//print_r($_REQUEST);
		$referer=isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:'';
		
		
		if (strpos($referer, 'feedbacker.me/join/encrypted/') == false) {
			
			if(!isset($this->session->userdata['mec_user'])){
				$this->session->set_userdata('redirect_url',current_url());
				$this->session->set_userdata('redirected',"1");
				redirect();
			}
			$this->user = $this->session->userdata['mec_user'];
			$this->data['user_info'] = $this->user;
		}
		
				
		// Load library
		$this->load->library('s3');
		$this->load->library('template');		
		$this->aws_client = ClientBuilder::create()->setHosts(["search-feedbacker-q3gdcfwrt27ulaeee5gz3zbezm.eu-west-1.es.amazonaws.com:80"])->build();
        $this->data['title'] = "Encrypted Title | Feedbacker ";
        // Load Login Model
        $this->load->model('common');
		$this->load->model('encrypted_model');
		$this->load->model('survey_model');	
		// Session data
		
		
		// Load Language File		
		if ($this->user['language'] == 'ar') {
			$this->lang->load('message','arabic');
			$this->lang->load('label','arabic');
		}else {
			$this->lang->load('message','english');
			$this->lang->load('label','english');
		}
        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }	
	public function index() {		
		$this->template->front_render('user/encrypted_titles');
	}
	public function join_title($title_id,$sender_id){		
		$title=$this->encrypted_model->get_title($title_id,$this->user['id']);
		if(!empty($title)){
			if(!$this->encrypted_model->is_title_linked_user($title_id,$this->user['id']) && $this->user['id']!=$sender_id){
				$post=array('title_id'=>$title_id,'sender_id'=>$sender_id,'user_id'=>$this->user['id']);
				$this->common->insert_data($post, $tablename = 'encrypted_titles_users');
				redirect('encrypted/'.$title['title_id']);
			}		
		}
		redirect();	
	}
	
	public function send_join_request(){
		$title_id = $this->input->post('title_id');		
		$this->load->library('form_validation');		
		$this->form_validation->set_rules('title_id', 'title_id', 'required');
		if ($this->form_validation->run() != FALSE){
			$requests=$this->encrypted_model->get_title_join_requests($title_id,$this->user['id']);	
			if(count($requests)<1){
				$post=array('title_id'=>$title_id,'user_id'=>$this->user['id']);
				$this->common->insert_data($post, $tablename = 'encrypted_title_requests');
				$this->session->set_flashdata('success', '<p>Your Request has been sent successfully to admin.</p>');
			}	 
		
		}
		redirect('user/encrypted_titles');	
	}
	public function documents($notification_id){		
		$documents=$this->encrypted_model->get_documents($notification_id);
		if(!empty($documents)){
			$this->data['documents']=$documents;
			$this->template->front_render('user/documents',$this->data);					
		}		
	}
	public function reply() {
		$title_id = $this->input->post('id');
		$slug = $this->input->post('slug');
		$privacy = $this->input->post('privacy');
		$privacy=($privacy==1)? 1: 0;
		
		if (!empty($slug)){
			/*$this->form_validation->set_rules('feedback_cont', 'Feedback', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
				redirect('title/'.$slug);
			}*/
			$feedback_imges = array();
			$feedback_files = array();
            $feedback_thumb = '';
            $feedback_video = '';	
			$feedback_pdf = '';	
			$feedback_pdf_name = '';
			
			if (isset($_FILES['feedback_files']['name']) && count($_FILES['feedback_files']['name'])>0) {
				$cpt = count($_FILES['feedback_files']['name']);
				$config['upload_path'] = $this->config->item('feedback_files_upload_path');
                $config['allowed_types'] = $this->config->item('feedback_allowed_files_types');
                $config['max_size'] = $this->config->item('feedback_files_max_size');
				$config['encrypt_name'] = TRUE;   
				
				$files=$_FILES;
				$this->load->library('upload');				
				for($i=0; $i<$cpt; $i++){				
					$_FILES['feedback_files']['name']= $files['feedback_files']['name'][$i];
					$_FILES['feedback_files']['type']= $files['feedback_files']['type'][$i];
					$_FILES['feedback_files']['tmp_name']= $files['feedback_files']['tmp_name'][$i];
					$_FILES['feedback_files']['error']= $files['feedback_files']['error'][$i];
					$_FILES['feedback_files']['size']= $files['feedback_files']['size'][$i];					
					$this->upload->initialize($config);
					if($this->upload->do_upload('feedback_files')){
						$filedata = $this->upload->data();						
						$feedback_files[$i]['file_url']=$filedata['file_name'];
						$feedback_files[$i]['file_name'] = $_FILES['feedback_files']['name'];   						
						$this->s3->putObjectFile($filedata['full_path'], S3_BUCKET, $config['upload_path'].$filedata['file_name'], S3::ACL_PUBLIC_READ);
						unlink($config['upload_path'].$filedata['file_name']);
					}					
				}
            }
			
			if (isset($_FILES['feedback_img']['name']) && count($_FILES['feedback_img']['name'])>0) {
				$cpt = count($_FILES['feedback_img']['name']);
				$config['upload_path'] = $this->config->item('feedback_main_upload_path');
                $config['thumb_upload_path'] = $this->config->item('feedback_thumb_upload_path');
                $config['allowed_types'] = $this->config->item('feedback_allowed_types');
                $config['max_size'] = $this->config->item('feedback_main_max_size');
                $config['max_width'] = $this->config->item('feedback_main_max_width');
                $config['max_height'] = $this->config->item('feedback_main_max_height');
				$config['encrypt_name'] = TRUE;
                
				$files=$_FILES;
				$this->load->library('upload');
				$this->load->library('image_lib');
				for($i=0; $i<$cpt; $i++){				
					$_FILES['feedback_img']['name']= $files['feedback_img']['name'][$i];
					$_FILES['feedback_img']['type']= $files['feedback_img']['type'][$i];
					$_FILES['feedback_img']['tmp_name']= $files['feedback_img']['tmp_name'][$i];
					$_FILES['feedback_img']['error']= $files['feedback_img']['error'][$i];
					$_FILES['feedback_img']['size']= $files['feedback_img']['size'][$i];					
					$this->upload->initialize($config);
					if($this->upload->do_upload('feedback_img')){
						$imgdata = $this->upload->data();						
						$feedback_imges[$i]['image']=$imgdata['file_name'];
						$thumb_file_path = str_replace("main", "thumbs", $imgdata['file_path']);
						$thumb_file_name = $config['thumb_upload_path'] . $imgdata['raw_name'].'_thumb'.$imgdata['file_ext'];
						
						// Configuring Thumbnail 
						$config_thumb['image_library'] = 'gd2';
						$config_thumb['source_image'] = $config['upload_path'] . $imgdata['file_name'];
						$config_thumb['new_image'] = $config['thumb_upload_path'] . $imgdata['file_name'];
						$config_thumb['create_thumb'] = TRUE;
						$config_thumb['maintain_ratio'] = TRUE;
						$config_thumb['thumb_marker'] = '_thumb';
						$config_thumb['width'] = $this->config->item('feedback_thumb_width');
						$config_thumb['height'] = $this->config->item('feedback_thumb_height');
						$this->image_lib->clear();
						$this->image_lib->initialize($config_thumb);
						// Creating Thumbnail
						if($this->image_lib->resize()) {
							$feedback_thumb = $imgdata['raw_name'].'_thumb'.$imgdata['file_ext'];
							$feedback_imges[$i]['thumb']=$feedback_thumb;
							if(file_exists($imgdata['full_path'])){
								$this->s3->putObjectFile($imgdata['full_path'], S3_BUCKET, $config_thumb['source_image'], S3::ACL_PUBLIC_READ);
								unlink($config_thumb['source_image']);
							}
							if(file_exists($thumb_file_path.$feedback_thumb)){
								$this->s3->putObjectFile($thumb_file_path.$feedback_thumb, S3_BUCKET, $thumb_file_name, S3::ACL_PUBLIC_READ);								
								unlink($thumb_file_name);								
							}
						}
						
					}
					
				}
            }
			if (isset($_FILES['feedback_video']['name']) && $_FILES['feedback_video']['name'] != '') {
                $config_video['upload_path'] = $this->config->item('feedback_video_upload_path');
                $config_video['thumb_upload_path'] = $this->config->item('feedback_thumb_upload_path');
                $config_video['max_size'] = $this->config->item('feedback_video_max_size');
                $config_video['allowed_types'] = $this->config->item('feedback_allowed_video_types');
                $config_video['overwrite'] = FALSE;
                $config_video['remove_spaces'] = TRUE;
                $config_video['file_name'] = time();    
                    
                $this->load->library('upload', $config_video);
                $this->upload->initialize($config_video);
                
                if (!$this->upload->do_upload('feedback_video')) {
                    $error = $this->upload->display_errors();
					$this->session->set_flashdata('error', strip_tags($error));
					redirect('encrypted/'.$title_id);
                } else {
                    $video_details = $this->upload->data();
					$feedback_video = $video_details['file_name'];        
                    
                    // Generate video thumbnail
                    $video_path = $video_details['full_path'];
                    $thumb_name = $video_details['raw_name']."_video.jpg";
                    $thumb_path = $config_video['thumb_upload_path'].$thumb_name;

                    shell_exec("ffmpeg -itsoffset -3 -i ".$video_path."  -y -an -f image2 -s 400x270 ".$thumb_path."");
                    $feedback_thumb = $thumb_name;
                    
                    // AWS S3 Upload
                    $thumb_file_path = str_replace("video", "thumbs", $video_details['file_path']);
                    
                    $this->s3->putObjectFile($video_details['full_path'], S3_BUCKET, $config_video['upload_path'].$video_details['file_name'], S3::ACL_PUBLIC_READ);
                    $this->s3->putObjectFile($thumb_file_path.$feedback_thumb, S3_BUCKET, $thumb_path, S3::ACL_PUBLIC_READ);

                    // Remove File from Local Storage
                    unlink($config_video['upload_path'].$video_details['file_name']);
                    unlink($thumb_path);
                }
            }
			if (isset($_FILES['feedback_pdf']['name']) && $_FILES['feedback_pdf']['name'] != '') {
                $config_pdf['upload_path'] = $this->config->item('feedback_pdf_upload_path');
                $config_pdf['max_size'] = $this->config->item('feedback_pdf_max_size');
                $config_pdf['allowed_types'] = $this->config->item('feedback_allowed_pdf_types');
                $config_pdf['overwrite'] = FALSE;
                $config_pdf['remove_spaces'] = TRUE;
                $config_pdf['file_name'] = time();    
                $feedback_pdf_name=$_FILES['feedback_pdf']['name']; 
                $this->load->library('upload', $config_pdf);
                $this->upload->initialize($config_pdf);                
                if (!$this->upload->do_upload('feedback_pdf')) {
                   $error = $this->upload->display_errors();
					$this->session->set_flashdata('error', strip_tags($error));
					var_dump($error);
					redirect('encrypted/'.$title_id);
                } else {
                    $pdf_details = $this->upload->data();
					$feedback_pdf = $pdf_details['file_name'];
                    $pdf_path = $pdf_details['full_path'];               
                    
                    $this->s3->putObjectFile($pdf_details['full_path'], S3_BUCKET, $config_pdf['upload_path'].$pdf_details['file_name'], S3::ACL_PUBLIC_READ);
					unlink($config_pdf['upload_path'].$pdf_details['file_name']);
                    
                }
            }
			$feedback_cont = $this->input->post('feedback_cont');
			$insert_array=array('title_id'=>$title_id,'user_id'=>$this->user['id'],'feedback_cont'=>$feedback_cont);
			if(count($feedback_imges)>0){
				 $insert_array['feedback_img'] = $feedback_imges[0]['image'];	
				 $insert_array['feedback_thumb'] = $feedback_imges[0]['thumb'];
			}
			if(!empty($feedback_video)) {
                $insert_array['feedback_video'] = $feedback_video;
            }
			if(!empty($feedback_pdf)) {
                $insert_array['feedback_pdf'] = $feedback_pdf;
            }
			if(!empty($feedback_pdf_name)) {
                $insert_array['feedback_pdf_name'] = $feedback_pdf_name;
            }
			$insert_array['privacy'] = $privacy;	
			$insert_array['datetime'] = date('Y-m-d H:i:s');
			$feedback_id = $this->common->insert_data($insert_array, $tablename = 'encrypted_titles_feedbacks');
			
			if(count($feedback_imges)>0){
				foreach($feedback_imges as $img){
					$insarr=array('feedback_id'=>$feedback_id,
					'feedback_img'=>$img['image'],
					'feedback_thumb'=>$img['thumb']);
					$img_id=$this->common->insert_data_getid($insarr, $tablename = 'encrypted_feedback_images');
				}
			}
			
			if(count($feedback_files)>0){
				foreach($feedback_files as $file){
					$insarr=array('feedback_id'=>$feedback_id,
					'file_name'=>$file['file_name'],
					'file_url'=>$file['file_url'],
					'privacy'=>$privacy);
					$fileid=$this->common->insert_data_getid($insarr, $tablename = 'encrypted_feedback_files');
				}
			}
			$title=$this->encrypted_model->get_title($title_id,$this->user['id']);
			$post=array('title_id'=>$title_id,'user_id'=>$title['user_id'],'guest_id'=>$this->user['id'],'feedback_id'=>$feedback_id,'notification_id'=>6,'datetime'=>date('Y-m-d H:i:s'));
			//$this->common->insert_data($post, $tablename = 'user_notifications');
			$linked_users=$this->encrypted_model->linked_users($title_id,$this->user['id']);
			
			$this->common->notification($title['user_id'], $this->user['id'], $title_id, $feedback_id, $replied_to = '', 6);
			if($privacy==0){
				foreach($linked_users as $usr){
					$this->common->notification($usr['id'], $this->user['id'], $title_id, $feedback_id, $replied_to = '', 6);
				}
			}			
			redirect('encrypted/'.$title_id);
		}
	}
	public function readby($slug){
		$this->data['module_name'] = 'Encrypted';
        $this->data['section_title'] = 'Encrypted Title Followers';
		$id=$this->encrypted_model->get_title_id_from_slug($slug);
		$is_title_linked_user=$this->encrypted_model->is_title_linked_user($id,$this->user['id']);
		$is_title_owner=$this->encrypted_model->is_title_owner($id,$this->user['id']);
		if( $is_title_owner || $is_title_linked_user){	
			$title=$this->encrypted_model->get_title($id,$this->user['id']);
			if(!empty($title)){	
				$titles=$this->encrypted_model->get_encrypted_titles($this->user['id'],0,-1);
			
				$reacted_users=$this->encrypted_model->get_title_views($id,$this->user['id'],-1);			
			
				$this->data['reacted_users']=$reacted_users;						
				$this->data['title']=$title;
				$this->data['is_title_owner']=$is_title_owner;
				$this->data['is_title_linked_user']=$is_title_linked_user;
				$this->data['section_title'] = $title['title'];			
			}else{
				$this->data['section_title'] = 'Encrypted Title Not Found';
				$this->data['title']=array();
				$this->data['feedbacks']=array();
				$this->data['no_record_found'] = "No title found.";
				redirect('user/encrypted_titles');
			}
		}
		$this->template->front_render('user/title_readby_users',$this->data);
	}
	public function linked_users($slug){
		$this->data['module_name'] = 'Encrypted';
        $this->data['section_title'] = 'Encrypted Title Followers';
		$id=$this->encrypted_model->get_title_id_from_slug($slug);
		$is_title_linked_user=$this->encrypted_model->is_title_linked_user($id,$this->user['id']);
		$is_title_owner=$this->encrypted_model->is_title_owner($id,$this->user['id']);
		if( $is_title_owner || $is_title_linked_user){	
			$title=$this->encrypted_model->get_title($id,$this->user['id']);
			if(!empty($title)){	
				$titles=$this->encrypted_model->get_encrypted_titles($this->user['id'],0,-1);				
				$linked_users=$this->encrypted_model->linked_users($id,$this->user['id'],-1);
				$reacted_users=$this->encrypted_model->get_title_views($id,$this->user['id'],3);			
				$this->data['linked_users']=$linked_users;
				$this->data['reacted_users']=$reacted_users;						
				$this->data['title']=$title;
				$this->data['is_title_owner']=$is_title_owner;
				$this->data['is_title_linked_user']=$is_title_linked_user;
				$this->data['section_title'] = $title['title'];			
			}else{
				$this->data['section_title'] = 'Encrypted Title Not Found';
				$this->data['title']=array();
				$this->data['feedbacks']=array();
				$this->data['no_record_found'] = "No title found.";
				redirect('user/encrypted_titles');
			}
		}
		$this->template->front_render('user/title_linked_users',$this->data);
	}
	public function title_by_slug($slug){	
		$this->data['module_name'] = 'Encrypted';
        $this->data['section_title'] = 'Encrypted Title Detail';		
		$slug=urldecode($slug);		
		$id=$this->encrypted_model->get_title_id_from_slug($slug);		
		$is_title_linked_user=$this->encrypted_model->is_title_linked_user($id,$this->user['id']);
		$is_title_owner=$this->encrypted_model->is_title_owner($id,$this->user['id']);
		if( $is_title_owner || $is_title_linked_user){			
			$title=$this->encrypted_model->get_title($id,$this->user['id']);
			if(!empty($title)){	
				$titles=$this->encrypted_model->get_encrypted_titles($this->user['id'],0,-1);
				$feedbacks=$this->encrypted_model->get_title_feedbacks($id,$this->user['id']);			
				$linked_users=$this->encrypted_model->linked_users($id,$this->user['id'],3);
				$reacted_users=$this->encrypted_model->get_title_views($id,$this->user['id'],3);			
				$this->data['linked_users']=$linked_users;
				$this->data['reacted_users']=$reacted_users;
				$this->data['feedbacks']=$feedbacks;			
				$this->data['title']=$title;
				$this->data['is_title_owner']=$is_title_owner;
				$this->data['is_title_linked_user']=$is_title_linked_user;
				$this->data['section_title'] = $title['title'];
				$this->data['user_titles'] = $titles;
				if(!$is_title_owner)
					$this->encrypted_model->update_title_views($id,$this->user['id']);
			}else{
				$this->data['section_title'] = 'Encrypted Title Not Found';
				$this->data['title']=array();
				$this->data['feedbacks']=array();
				$this->data['no_record_found'] = "No title found.";
				redirect('user/encrypted_titles');
			}
			
		}else{
			$this->data['no_record_found'] = "This is Encrypted Title and you don't have permission to see it.";
			redirect('user/encrypted_titles');
		}
		if($title['is_survey']){
			$questions=$this->survey_model->get_survey_questions($title['title_id'],$this->user['id']);	
			$this->data['questions']=$questions;
			$this->template->front_render('user/encrypted_survey_single',$this->data);	
		}else{
			$this->template->front_render('user/encrypted_title_single',$this->data);
		}			
			
		
	}
	public function block (){		
		$user_id=$this->input->post('user_id');
		$blocked_by=$this->input->post('blocked_by');
		$title_id=$this->input->post('title_id');
		$slug=$this->input->post('slug');
		$block_and_leave=$this->input->post('block_and_leave');
		$referral_url=$this->input->post('referral_url');
		$this->common->block_user($blocked_by,$user_id);
		if($block_and_leave==1){
			 $this->encrypted_model->remove_linked_user($title_id,$blocked_by);
			 redirect('user/encrypted_titles');
		}
		redirect('encrypted/'.$title_id);
	}
	public function title($id,$country=''){	
		$this->data['module_name'] = 'Encrypted';
        $this->data['section_title'] = 'Encrypted Title Detail';
		if($country == '') {
			$getcountry = $this->common->select_data_by_id('users', 'id', $this->user['id'], 'country', '');	
			$country = $this->user['country'];
		} else {
			$this->user['country'] = $country; 			
			$this->session->set_userdata('mec_user', $this->user);	
			$this->data['user_info'] = $this->user;
		}
		$is_title_linked_user=$this->encrypted_model->is_title_linked_user($id,$this->user['id']);
		$is_title_owner=$this->encrypted_model->is_title_owner($id,$this->user['id']);
		$s='';
		if($this->input->post("search")){			
			$s=$this->input->post("s");
			$this->data['is_search'] = true;			
		}
		$this->data['s'] = $s;
		if( $is_title_owner || $is_title_linked_user){			
			$title=$this->encrypted_model->get_title($id,$this->user['id']);
			if(!empty($title)){	
				
				$titles=$this->encrypted_model->get_encrypted_titles($this->user['id'],0,-1,'',false);
				$feedbacks=$this->encrypted_model->get_title_feedbacks($id,$this->user['id'],0,40,$s);
				$return_array = $this->common->adBannersEncrypted($feedbacks, $country, 'encrypted',$id,1,40);			
				$linked_users=$this->encrypted_model->linked_users($id,$this->user['id'],3);
				$reacted_users=$this->encrypted_model->get_title_views($id,$this->user['id'],3);			
				$this->data['linked_users']=$linked_users;
				$this->data['reacted_users']=$reacted_users;
				$this->data['feedbacks']=$return_array;			
				$this->data['title']=$title;
				$this->data['is_title_owner']=$is_title_owner;
				$this->data['is_title_linked_user']=$is_title_linked_user;
				$this->data['section_title'] = $title['title'];
				$this->data['user_titles'] = $titles;
				if(!$is_title_owner)
					$this->encrypted_model->update_title_views($id,$this->user['id']);
			}else{
				$this->data['section_title'] = 'Encrypted Title Not Found';
				$this->data['title']=array();
				$this->data['feedbacks']=array();
				$this->data['no_record_found'] = "No title found.";
				redirect('user/encrypted_titles');
			}
			
		}else{
			$this->data['no_record_found'] = "This is Encrypted Title and you don't have permission to see it.";
			redirect('user/encrypted_titles');
		}
		$this->common->update_notification_status($this->user['id'],6,$title['title_id']);
		$groups = $this->common->get_user_groups($this->user['id']);
		$this->data['groups']=$groups;
		if($title['is_survey']){
			$questions=$this->survey_model->get_survey_questions($title['title_id'],$this->user['id']);	
			
			$return_array = $this->common->adBannersSurvey($questions, $country, 'encrypted',$title['title_id'],1,count($questions)+10);			
			$this->data['questions']=$return_array;
			$this->template->front_render('user/encrypted_survey_single',$this->data);	
		}else{
			$this->template->front_render('user/encrypted_title_single',$this->data);
		}	
	}
	public function link_users(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('users[]', 'Users', 'required');
		$this->form_validation->set_rules('title_id', 'Title', 'required');	
		$this->form_validation->set_rules('slug', 'Slug', 'required');			
		 if ($this->form_validation->run() != FALSE){
			 $title_id = $this->input->post('title_id');
			 $title=$this->encrypted_model->get_title($title_id);
			 $slug = $this->input->post('slug');
			 $users = $this->input->post('users');
			 if($users){
					foreach($users as $user_id){
						if(!$this->encrypted_model->is_title_linked_user($title_id,$user_id)){
							$post=array('title_id'=>$title_id,'user_id'=>$user_id,'sender_id'=>$this->user['id']);
							$this->common->insert_data($post, $tablename = 'encrypted_titles_users');
							$post=array('title_id'=>$title_id,'user_id'=>$user_id,'guest_id'=>$this->user['id'],'notification_id'=>5,'datetime'=>date('Y-m-d H:i:s'));
							//$this->common->insert_data($post, $tablename = 'user_notifications');
							if($title['is_survey']){
								$this->common->notification($user_id, $this->user['id'], $title_id, $feedback_id='', $replied_to = '', 8);
							}else{
								$this->common->notification($user_id, $this->user['id'], $title_id, $feedback_id='', $replied_to = '', 5);
							}
						}						
					}					
				}
		}
		redirect('encrypted/'.$title_id);
	}
	public function share_feedback(){
		if ($this->input->is_ajax_request()) {
			$titles=$this->input->post('titles');
			$users=$this->input->post('users');
			$title_id=$this->input->post('title_id');
			$feedback_id=$this->input->post('feedback_id');	
			if(!empty($titles))			
			$this->encrypted_model->share_feedback_to_titles($this->user['id'],$feedback_id,$titles);
			if(!empty($users))			
			$this->encrypted_model->share_feedback_to_users($this->user['id'],$title_id,$feedback_id,$users);
			//echo $this->db->last_query();
			$response=array('success'=>true);
			echo json_encode($response);	
		}else{
			$response=array('success'=>false);
			echo json_encode($response);			
		}
	}
	public function link_group(){
		$title_id = $this->input->post('title_id');
		$user_groups = $this->input->post('user_groups');
		$title=$this->encrypted_model->get_title($title_id);
		if($user_groups && is_numeric($user_groups)){
			$users=$this->common->get_group_users($user_groups);
			foreach($users as $user){
				
				if($this->common->get_count_of_table('encrypted_titles_users',array('title_id'=>$title_id,'user_id'=>$user['user_id']))<1){
					$post=array('title_id'=>$title_id,'user_id'=>$user['user_id'],'sender_id'=>$this->user['id']);
					$this->common->insert_data($post, $tablename = 'encrypted_titles_users');
					$post=array('title_id'=>$title_id,'user_id'=>$user['user_id'],'guest_id'=>$this->user['id'],'notification_id'=>5,'datetime'=>date('Y-m-d H:i:s'));
					if($title['is_survey']){
						$this->common->notification($user['user_id'], $this->user['id'], $title_id, $feedback_id='', $replied_to = '', 8);
					}else{
						$this->common->notification($user['user_id'], $this->user['id'], $title_id, $feedback_id='', $replied_to = '', 5);
					}
				}						
			}
		}
		redirect('encrypted/'.$title_id);	
	}
	public function send_invitaion(){
		$title_id = $this->input->post('title_id');
		$invite_email = $this->input->post('invite_email');
		$slug = $this->input->post('slug');
		$this->form_validation->set_rules('title_id', 'title_id', 'required');
		$this->form_validation->set_rules('invite_email', 'invite_email', 'required');
		$this->form_validation->set_rules('slug', 'Slug', 'required');
		if ($this->form_validation->run() != FALSE){		
			$emails=json_decode($_POST['invite_email']);
			$this->encrypted_model->send_invitaion($title_id,$this->user['id'],$emails);	
		}
		redirect('encrypted/'.$title_id);		
	}
	public function remove_user(){
		$title_id = $this->input->post('title_id');
		$user_id = $this->input->post('user_id');
		$slug = $this->input->post('slug');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_id', 'user_id', 'required');
		$this->form_validation->set_rules('slug', 'Slug', 'required');
		$this->form_validation->set_rules('title_id', 'title_id', 'required');
		if ($this->form_validation->run() != FALSE){				
			 $this->encrypted_model->remove_linked_user($title_id,$user_id);	
		}
		redirect('encrypted/'.$title_id);	
	}
	public function delete_feedback(){
		$title_id = $this->input->post('title_id');		
		$feedback_id = $this->input->post('feedback_id');
		$slug = $this->input->post('slug');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('feedback_id', 'feedback_id', 'required');
		$this->form_validation->set_rules('slug', 'Slug', 'required');
		$this->form_validation->set_rules('title_id', 'title_id', 'required');
		if ($this->form_validation->run() != FALSE){				
			 $this->encrypted_model->delete_feedback($title_id,$feedback_id);	
		}
		redirect('encrypted/'.$title_id);	
	}
	
	public function leave_title(){
		$title_id = $this->input->post('title_id');
		$user_id = $this->input->post('user_id');
		$slug = $this->input->post('slug');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_id', 'user_id', 'required');
		$this->form_validation->set_rules('slug', 'Slug', 'required');
		$this->form_validation->set_rules('title_id', 'title_id', 'required');
		if ($this->form_validation->run() != FALSE){				
			 $this->encrypted_model->remove_linked_user($title_id,$user_id);	
		}
		redirect('user/encrypted_titles');	
	}
	public function delete_title(){
		$title_id = $this->input->post('title_id');		
		$this->load->library('form_validation');		
		$this->form_validation->set_rules('title_id', 'title_id', 'required');
		if ($this->form_validation->run() != FALSE){				
			 $this->encrypted_model->delete_encrypted_title($title_id,$this->user['id']);	
			// echo $this->db->last_query();
		}
		redirect('user/encrypted_titles');	
	}
	public function create() {
		//check post and save data		
        if ($this->input->post('title')) {
			$this->form_validation->set_rules('title', 'Title', 'trim|required');	
			//$this->form_validation->set_rules('feedback_cont', 'Feedback', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
				redirect('encrypted/create');
			}else{
				$feedback_imges = array();
				$feedback_files = array();
				$feedback_thumb = '';
				$feedback_video = '';	
				$feedback_pdf = '';	
				$feedback_pdf_name = '';
				$is_survey=$this->input->post('is_survey');
				$is_survey=empty($is_survey)? 0: 1;
				if (isset($_FILES['feedback_files']['name']) && count($_FILES['feedback_files']['name'])>0) {
					$cpt = count($_FILES['feedback_files']['name']);
					$config['upload_path'] = $this->config->item('feedback_files_upload_path');
					$config['allowed_types'] = $this->config->item('feedback_allowed_files_types');
					$config['max_size'] = $this->config->item('feedback_files_max_size');
					$config['encrypt_name'] = TRUE;   
					
					$files=$_FILES;
					$this->load->library('upload');				
					for($i=0; $i<$cpt; $i++){				
						$_FILES['feedback_files']['name']= $files['feedback_files']['name'][$i];
						$_FILES['feedback_files']['type']= $files['feedback_files']['type'][$i];
						$_FILES['feedback_files']['tmp_name']= $files['feedback_files']['tmp_name'][$i];
						$_FILES['feedback_files']['error']= $files['feedback_files']['error'][$i];
						$_FILES['feedback_files']['size']= $files['feedback_files']['size'][$i];					
						$this->upload->initialize($config);
						if($this->upload->do_upload('feedback_files')){
							$filedata = $this->upload->data();						
							$feedback_files[$i]['file_url']=$filedata['file_name'];
							$feedback_files[$i]['file_name'] = $_FILES['feedback_files']['name'];   						
							$this->s3->putObjectFile($filedata['full_path'], S3_BUCKET, $config['upload_path'].$filedata['file_name'], S3::ACL_PUBLIC_READ);
							unlink($config['upload_path'].$filedata['file_name']);
						}					
					}
				}
				
				if (isset($_FILES['feedback_img']['name']) && count($_FILES['feedback_img']['name'])>0) {
					$cpt = count($_FILES['feedback_img']['name']);
					$config['upload_path'] = $this->config->item('feedback_main_upload_path');
					$config['thumb_upload_path'] = $this->config->item('feedback_thumb_upload_path');
					$config['allowed_types'] = $this->config->item('feedback_allowed_types');
					$config['max_size'] = $this->config->item('feedback_main_max_size');
					$config['max_width'] = $this->config->item('feedback_main_max_width');
					$config['max_height'] = $this->config->item('feedback_main_max_height');
					$config['encrypt_name'] = TRUE;
					
					$files=$_FILES;
					$this->load->library('upload');
					$this->load->library('image_lib');
					for($i=0; $i<$cpt; $i++){				
						$_FILES['feedback_img']['name']= $files['feedback_img']['name'][$i];
						$_FILES['feedback_img']['type']= $files['feedback_img']['type'][$i];
						$_FILES['feedback_img']['tmp_name']= $files['feedback_img']['tmp_name'][$i];
						$_FILES['feedback_img']['error']= $files['feedback_img']['error'][$i];
						$_FILES['feedback_img']['size']= $files['feedback_img']['size'][$i];					
						$this->upload->initialize($config);
						if($this->upload->do_upload('feedback_img')){
							$imgdata = $this->upload->data();						
							$feedback_imges[$i]['image']=$imgdata['file_name'];
							$thumb_file_path = str_replace("main", "thumbs", $imgdata['file_path']);
							$thumb_file_name = $config['thumb_upload_path'] . $imgdata['raw_name'].'_thumb'.$imgdata['file_ext'];
							
							// Configuring Thumbnail 
							$config_thumb['image_library'] = 'gd2';
							$config_thumb['source_image'] = $config['upload_path'] . $imgdata['file_name'];
							$config_thumb['new_image'] = $config['thumb_upload_path'] . $imgdata['file_name'];
							$config_thumb['create_thumb'] = TRUE;
							$config_thumb['maintain_ratio'] = TRUE;
							$config_thumb['thumb_marker'] = '_thumb';
							$config_thumb['width'] = $this->config->item('feedback_thumb_width');
							$config_thumb['height'] = $this->config->item('feedback_thumb_height');
							$this->image_lib->clear();
							$this->image_lib->initialize($config_thumb);
							// Creating Thumbnail
							if($this->image_lib->resize()) {
								$feedback_thumb = $imgdata['raw_name'].'_thumb'.$imgdata['file_ext'];
								$feedback_imges[$i]['thumb']=$feedback_thumb;
								if(file_exists($imgdata['full_path'])){
									$this->s3->putObjectFile($imgdata['full_path'], S3_BUCKET, $config_thumb['source_image'], S3::ACL_PUBLIC_READ);
									unlink($config_thumb['source_image']);
								}
								if(file_exists($thumb_file_path.$feedback_thumb)){
									$this->s3->putObjectFile($thumb_file_path.$feedback_thumb, S3_BUCKET, $thumb_file_name, S3::ACL_PUBLIC_READ);								
									unlink($thumb_file_name);								
								}
							}
							
						}
						
					}
				}
				if (isset($_FILES['feedback_video']['name']) && $_FILES['feedback_video']['name'] != '') {
					$config_video['upload_path'] = $this->config->item('feedback_video_upload_path');
					$config_video['thumb_upload_path'] = $this->config->item('feedback_thumb_upload_path');
					$config_video['max_size'] = $this->config->item('feedback_video_max_size');
					$config_video['allowed_types'] = $this->config->item('feedback_allowed_video_types');
					$config_video['overwrite'] = FALSE;
					$config_video['remove_spaces'] = TRUE;
					$config_video['file_name'] = time();    
						
					$this->load->library('upload', $config_video);
					$this->upload->initialize($config_video);
					
					if (!$this->upload->do_upload('feedback_video')) {
						$error = $this->upload->display_errors();
						$this->session->set_flashdata('error', strip_tags($error));
						redirect('post/create');
					} else {
						$video_details = $this->upload->data();
						$feedback_video = $video_details['file_name'];        
						
						// Generate video thumbnail
						$video_path = $video_details['full_path'];
						$thumb_name = $video_details['raw_name']."_video.jpg";
						$thumb_path = $config_video['thumb_upload_path'].$thumb_name;

						shell_exec("ffmpeg -itsoffset -3 -i ".$video_path."  -y -an -f image2 -s 400x270 ".$thumb_path."");
						$feedback_thumb = $thumb_name;
						
						// AWS S3 Upload
						$thumb_file_path = str_replace("video", "thumbs", $video_details['file_path']);
						
						$this->s3->putObjectFile($video_details['full_path'], S3_BUCKET, $config_video['upload_path'].$video_details['file_name'], S3::ACL_PUBLIC_READ);
						$this->s3->putObjectFile($thumb_file_path.$feedback_thumb, S3_BUCKET, $thumb_path, S3::ACL_PUBLIC_READ);

						// Remove File from Local Storage
						unlink($config_video['upload_path'].$video_details['file_name']);
						unlink($thumb_path);
					}
				}				
				$title = $this->input->post('title');
				$feedback_cont = $this->input->post('feedback_cont');
				$users = $this->input->post('users');
				$user_groups = $this->input->post('user_groups');				
				$insert_array=array();
				$this->load->library('Slug');
				$slug=$this->slug->create_unique_slug($title, 'encrypted_titles');
				$insert_array['user_id'] = $this->user['id'];
				$insert_array['title'] = $title;
				$insert_array['slug'] = $slug;
				$insert_array['is_survey'] = $is_survey;				
				$title_id = $this->common->insert_data($insert_array, $tablename = 'encrypted_titles');
				$insert_array=array('title_id'=>$title_id,'user_id'=>$this->user['id'],'feedback_cont'=>$feedback_cont);
				if(count($feedback_imges)>0){
					 $insert_array['feedback_img'] = $feedback_imges[0]['image'];	
					 $insert_array['feedback_thumb'] = $feedback_imges[0]['thumb'];
				}
				if(!empty($feedback_video)) {
					$insert_array['feedback_video'] = $feedback_video;
				}
				if(!empty($feedback_pdf)) {
					$insert_array['feedback_pdf'] = $feedback_pdf;
				}
				if(!empty($feedback_pdf_name)) {
					$insert_array['feedback_pdf_name'] = $feedback_pdf_name;
				}
				$title=$this->encrypted_model->get_title($title_id);
				$feedback_id = $this->common->insert_data($insert_array, $tablename = 'encrypted_titles_feedbacks');
				if($users){
					foreach($users as $user_id){
						
						if($this->common->get_count_of_table('encrypted_titles_users',array('title_id'=>$title_id,'user_id'=>$user_id))<1){
							$post=array('title_id'=>$title_id,'user_id'=>$user_id,'sender_id'=>$this->user['id']);
							$this->common->insert_data($post, $tablename = 'encrypted_titles_users');
							$post=array('title_id'=>$title_id,'user_id'=>$user_id,'guest_id'=>$this->user['id'],'notification_id'=>5,'datetime'=>date('Y-m-d H:i:s'));
							//$this->common->insert_data($post, $tablename = 'user_notifications');
							if($title['is_survey']){
								$this->common->notification($user_id, $this->user['id'], $title_id, $feedback_id='', $replied_to = '', 8);
							}else{
								$this->common->notification($user_id, $this->user['id'], $title_id, $feedback_id='', $replied_to = '', 5);
							}
						}
						 
					}					
				}
				if($user_groups && is_numeric($user_groups)){
					$users=$this->common->get_group_users($user_groups);
					foreach($users as $user){
						if($this->common->get_count_of_table('encrypted_titles_users',array('title_id'=>$title_id,'user_id'=>$user_id))<1){
							$post=array('title_id'=>$title_id,'user_id'=>$user['user_id'],'sender_id'=>$this->user['id']);
							$this->common->insert_data($post, $tablename = 'encrypted_titles_users');
							$post=array('title_id'=>$title_id,'user_id'=>$user['user_id'],'guest_id'=>$this->user['id'],'notification_id'=>5,'datetime'=>date('Y-m-d H:i:s'));
							//$this->common->insert_data($post, $tablename = 'user_notifications');
							if($title['is_survey']){
								$this->common->notification($user_id, $this->user['id'], $title_id, $feedback_id='', $replied_to = '', 8);
							}else{
								$this->common->notification($user_id, $this->user['id'], $title_id, $feedback_id='', $replied_to = '', 5);
							}
						}						
					}
				}
				if(count($feedback_imges)>0){
					foreach($feedback_imges as $img){
						$insarr=array('feedback_id'=>$feedback_id,
						'feedback_img'=>$img['image'],
						'feedback_thumb'=>$img['thumb']);
						$img_id=$this->common->insert_data_getid($insarr, $tablename = 'encrypted_feedback_images');
					}
				}
				
				if(count($feedback_files)>0){
					foreach($feedback_files as $file){
						$insarr=array('feedback_id'=>$feedback_id,
						'file_name'=>$file['file_name'],
						'file_url'=>$file['file_url']);
						$fileid=$this->common->insert_data_getid($insarr, $tablename = 'encrypted_feedback_files');
					}
				}
				if($is_survey){
					$this->session->set_flashdata('success', '<p>Encrypted title has been created successfully.</p>');
					redirect('survey-questions/'.$slug);
				}else{
					$this->session->set_flashdata('success', '<p>Encrypted title has been posted successfully.</p>');
					redirect('encrypted/'.$title_id);	
				}				
			}
		}		
		$groups = $this->common->get_user_groups($this->user['id']);
		$this->data['groups']=$groups;	
		$this->data['module_name'] = 'Post';
        $this->data['section_title'] = 'Create Encrypted Title';		
		/* Load Template */
		$this->template->front_render('post/create_encrypted', $this->data);
	}
	
}
