<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;
class Survey extends CI_Controller {	
	public $data;	
	public $user;	
	private $aws_client;
    public function __construct() {
        parent::__construct();        
		if(!isset($this->session->userdata['mec_user'])){
			$this->session->set_userdata('redirect_url',current_url());
			$this->session->set_userdata('redirected',"1");
			redirect();
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
		$this->user = $this->session->userdata['mec_user'];
		$this->data['user_info'] = $this->user;
		
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
	public function submit(){		
		if($this->input->post('slug')){	
			$slug=$this->input->post('slug');
			$title_id=$this->input->post('title_id');
			$answer=$this->input->post('answer');
			if(!empty($answer) && is_array($answer)){
					foreach($answer as $question_id=>$ans){
						$questions=$this->survey_model->add_question_answer($this->user['id'],$title_id,$question_id,$ans);
					}
			}
			redirect('encrypted/'.$title_id);
		}
		redirect('user/encrypted_titles');
	}
	public function results($slug){					
		$this->data['module_name'] = 'Survey';
        $this->data['section_title'] = 'Survey Detail';		
		$id=$this->encrypted_model->get_title_id_from_slug($slug);
		$is_title_linked_user=$this->encrypted_model->is_title_linked_user($id,$this->user['id']);
		$is_title_owner=$this->encrypted_model->is_title_owner($id,$this->user['id']);
		if( $is_title_owner){
			$title=$this->encrypted_model->get_title($id,$this->user['id']);				
			$questions=$this->survey_model->get_survey_questions($id,$this->user['id']);	
			$linked_users=$this->encrypted_model->linked_users($id,$this->user['id']);
			$results=$this->survey_model->get_survey_results($id);
			$this->data['results']=$results;	
			$this->data['linked_users']=$linked_users;						
			$this->data['title']=$title;
			$this->data['questions']=$questions;
			$this->data['is_title_owner']=$is_title_owner;
			$this->data['is_title_linked_user']=$is_title_linked_user;
			$this->data['section_title'] = $title['title'].' - Survey Results';
		}else{
			$this->data['no_record_found'] = "You are not authorized to manage questions for this survey.";
			redirect('user/encrypted_titles');
		}				
		$this->template->front_render('user/survey-results',$this->data);
		
	}
	public function survey_questions($slug){
		
		if($this->input->post('question')){	
		
			$questions=$this->input->post('question');
			$title_id=$this->input->post('title_id');
			$first_p=$this->input->post('first_p');
			$second_p=$this->input->post('second_p');
			$third_p=$this->input->post('third_p');
			$fourth_p=$this->input->post('fourth_p');
			$correct=$this->input->post('correct');
			
			
			if(!empty($questions) && is_array($questions) && count($questions)>0){
				
				
				foreach($questions as $key=>$question){
					if(isset($_FILES['question_'.$key.'_images'])){
						$question_images=$_FILES['question_'.$key.'_images'];
					}else{
						$question_images=array();
					}
				
					if(!empty($question)){
						$p1=isset($first_p[$key])? $first_p[$key]:'' ;
						$p2=isset($second_p[$key])? $second_p[$key]:'' ;
						$p3=isset($third_p[$key])? $third_p[$key]:'' ;
						$p4=isset($fourth_p[$key])? $fourth_p[$key]:'' ;
						$correct_option=isset($correct[$key])? $correct[$key]:'' ;
						
						$ans='';
						/*if($correct==1)
							$ans=$p1;
						else if($correct==2)
							$ans=$p2;
						else if($correct==3)
							$ans=$p3;
						else if($correct==4)
							$ans=$p4;*/
						$insert_array=array('title_id'=>$title_id,
						'question'=>$question,
						'first_p'=>$p1,
						'second_p'=>$p2,
						'third_p'=>$p3,
						'fourth_p'=>$p4,
						'user_id'=>$this->user['id'],
						'correct'=>$correct_option
						);
						
						$question_id = $this->common->insert_data($insert_array, $tablename = 'questions');
						$feedback_imges=array();
						if (isset($question_images['name']) && count($question_images['name'])>0) {
							$cpt = count($question_images['name']);
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
								$_FILES['feedback_img']['name']= $question_images['name'][$i];
								$_FILES['feedback_img']['type']= $question_images['type'][$i];
								$_FILES['feedback_img']['tmp_name']= $question_images['tmp_name'][$i];
								$_FILES['feedback_img']['error']= $question_images['error'][$i];
								$_FILES['feedback_img']['size']= $question_images['size'][$i];					
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
						if(count($feedback_imges)>0){
							foreach($feedback_imges as $img){
								$insarr=array('question_id'=>$question_id,
								'photo'=>$img['image'],
								'thumb'=>$img['thumb']);
								$img_id=$this->common->insert_data_getid($insarr, $tablename = 'question_images');
							}
						}
					}					
				}
				redirect(current_url());
			}else{
				$this->data['error'] = "Please enter question data.";
			}
		}			
		$this->data['module_name'] = 'Survey';
        $this->data['section_title'] = 'Survey Detail';		
		$id=$this->encrypted_model->get_title_id_from_slug($slug);
		$is_title_linked_user=$this->encrypted_model->is_title_linked_user($id,$this->user['id']);
		$is_title_owner=$this->encrypted_model->is_title_owner($id,$this->user['id']);
		if( $is_title_owner){
			$title=$this->encrypted_model->get_title($id,$this->user['id']);				
			$questions=$this->survey_model->get_survey_questions($id,$this->user['id']);	
			$linked_users=$this->encrypted_model->linked_users($id,$this->user['id']);		
			$this->data['linked_users']=$linked_users;						
			$this->data['title']=$title;
			$this->data['questions']=$questions;
			$this->data['is_title_owner']=$is_title_owner;
			$this->data['is_title_linked_user']=$is_title_linked_user;
			$this->data['section_title'] = $title['title'];
		}else{
			$this->data['no_record_found'] = "You are not authorized to manage questions for this survey.";
			redirect('user/encrypted_titles');
		}				
		$this->template->front_render('user/survey-questions',$this->data);
		
	}
	public function delete_question(){
		$slug=$this->input->post('slug');
		if($this->input->post('question_id')){	
			$question_id=$this->input->post('question_id');
			$title_id=$this->input->post('title_id');			
			$this->survey_model->delete_survey_question($title_id,$question_id);			
		}
		redirect('survey-questions/'.$slug);		
	}
	public function title_by_slug($slug){	
		$this->data['module_name'] = 'Encrypted';
        $this->data['section_title'] = 'Encrypted Title Detail';
		$id=$this->encrypted_model->get_title_id_from_slug($slug);
		$is_title_linked_user=$this->encrypted_model->is_title_linked_user($id,$this->user['id']);
		$is_title_owner=$this->encrypted_model->is_title_owner($id,$this->user['id']);
		if( $is_title_owner || $is_title_linked_user){			
			$title=$this->encrypted_model->get_title($id,$this->user['id']);
			if(!empty($title)){	
				$titles=$this->encrypted_model->get_encrypted_titles($this->user['id'],0,-1);
				$feedbacks=$this->encrypted_model->get_title_feedbacks($id,$this->user['id']);			
				$linked_users=$this->encrypted_model->linked_users($id,$this->user['id']);		
				$this->data['linked_users']=$linked_users;
				$this->data['feedbacks']=$feedbacks;			
				$this->data['title']=$title;
				$this->data['is_title_owner']=$is_title_owner;
				$this->data['is_title_linked_user']=$is_title_linked_user;
				$this->data['section_title'] = $title['title'];
				$this->data['user_titles'] = $titles;
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
				
		$this->template->front_render('user/encrypted_title_single',$this->data);
	}
	public function create() {
		
        if ($this->input->post('title')) {
			$this->form_validation->set_rules('title', 'Title', 'trim|required');
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
				redirect('survey/create');
			}else{				
				$is_survey=1;								
				$title = $this->input->post('title');				
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
				if($users){
					foreach($users as $user_id){
						
						if($this->common->get_count_of_table('encrypted_titles_users',array('title_id'=>$title_id,'user_id'=>$user_id))<1){
							$post=array('title_id'=>$title_id,'user_id'=>$user_id,'sender_id'=>$this->user['id']);
							$this->common->insert_data($post, $tablename = 'encrypted_titles_users');
						}
						 
					}					
				}
				if($user_groups && is_numeric($user_groups)){
					$users=$this->common->get_group_users($user_groups);
					foreach($users as $user){
						if($this->common->get_count_of_table('encrypted_titles_users',array('title_id'=>$title_id,'user_id'=>$user_id))<1){
							$post=array('title_id'=>$title_id,'user_id'=>$user['user_id'],'sender_id'=>$this->user['id']);
							$this->common->insert_data($post, $tablename = 'encrypted_titles_users');
						}						
					}
				}
				$this->session->set_flashdata('success', '<p>Encrypted title has been created successfully.</p>');
				redirect('survey-questions/'.$slug);
							
			}
		}		
		$groups = $this->common->get_user_groups($this->user['id']);
		$this->data['groups']=$groups;	
		$this->data['module_name'] = 'Post';
        $this->data['section_title'] = 'Create Survey';		
		/* Load Template */
		$this->template->front_render('post/create_encrypted', $this->data);
	}
	
}
