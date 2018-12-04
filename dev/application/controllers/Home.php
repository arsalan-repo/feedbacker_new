<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	private $perPage = 10;
	public $data;
	private $connection;
    public function __construct() {
        parent::__construct();        
        $this->data['title'] = "Welcome to feedbacker | Feedbacker ";		
        $this->load->model('common');		
		// Load Language File
		$this->lang->load('message','english');
		$this->lang->load('label','english');		
		$this->load->library('template', 'facebook');
		$this->load->library('twitteroauth');
		$this->load->library('facebook');
		$this->load->model('common');
		$this->load->model('encrypted_model');
		$this->load->model('survey_model');
		
        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }
	public function country($country) {
		$this->load->library('user_agent');
		$refer = $this->agent->referrer();
		$this->session->set_userdata('user_country', $country);	
		if(isset($this->session->userdata['mec_user'])){			
			$this->user = $this->session->userdata['mec_user'];
			$this->data['user_info'] = $this->user;
			$this->user['country'] = $country; 
			$this->session->set_userdata('mec_user', $this->user);			
		}
			
		redirect($refer);
	}
	public function language($code) {
		$this->load->library('user_agent');
		$refer = $this->agent->referrer();
		$this->session->set_userdata('user_lang', $code);	
		if(isset($this->session->userdata['mec_user'])){			
			$this->user = $this->session->userdata['mec_user'];
			$this->data['user_info'] = $this->user;
			$this->user['language'] = $code; 
			$this->session->set_userdata('mec_user', $this->user);			
		}
			
		redirect($refer);
	}
	public function social(){
		$this->template->front_render('social_sharing', $this->data);
	}
	public function index() {
		
		$this->data['module_name'] = 'Welcome to Feedbacker';
		$this->data['section_title'] = 'Welcome to Feedbacker';
		$this->data['is_search']=false;
		$this->config->load('twitter');		
		if($this->facebook->logged_in()){
			redirect('user/dashboard');
		}else{
			$this->data['authUrl'] = $this->facebook->loginUrl();			
		}
		
		if($this->input->post("search")){			
			$s=$this->input->post("s");
			$this->data['is_search'] = true;
			$this->data['module_name'] = 'Welcome to Feedbacker';
			$this->data['section_title'] = sprintf('Search Results for "%s"',$s);
			$title_id=array();
			$feedback_id=array();
			$title_comma = implode(',', $title_id);
			$feedback_comma = implode(',', $feedback_id);

			// Get Search Results
			$custom_in_sql = '';
			
			
			$join_str = array(
				array(
					'table' => 'users',
					'join_table_id' => 'users.id',
					'from_table_id' => 'feedback.user_id',
					'join_type' => 'left'
				),
				array(
					'table' => 'titles',
					'join_table_id' => 'titles.title_id',
					'from_table_id' => 'feedback.title_id',
					'join_type' => 'left'
				)
			);			
			
			$custom_in_sql="db_feedback.feedback_cont LIKE '%".$s."%' OR db_feedback.title_id IN (SELECT title_id FROM `db_titles` WHERE `title` LIKE '%".$s."%')";
			
			$search_condition = "(".$custom_in_sql.") AND db_feedback.deleted = 0 AND feedback.status = 1";
			$data = 'feedback_id, feedback.title_id, title, users.id as user_id, name, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video, replied_to, location, feedback.datetime as time';
			if (!empty($this->input->get("page"))) {
				$page = ceil($this->input->get("page") - 1);
				$start = ceil($page * $this->perPage);				
				$feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $this->perPage, $start, $join_str, $group_by = '');
				
				if(count($feedback) > 0) {	
					$result = $this->common->getFeedbacks($feedback, 0);					
					$return_array = $this->common->adBanners($result, $country, 'home', $this->input->get("page"));					
					$this->data['feedbacks'] = $return_array;
				} else {
					$this->data['feedbacks'] = array();
					$this->data['no_record_found'] = $this->lang->line('no_results');
				}				
				$response = $this->load->view('post/ajax-search', $this->data);
				echo json_encode($response);
			} else {
				$feedback = $this->common->select_data_by_search('feedback', $search_condition, $condition_array = array(), $data, $sortby = '', $orderby = '', $this->perPage, 0, $join_str,'feedback_id DESC');
				//echo $this->db->last_query();
				if(count($feedback) > 0) {
					// Get Likes, Followings and Other details
					$return_array = $this->common->getFeedbacks($feedback, 0);
					
					// Append Ad Banners
					$return_array = $this->common->adBanners($return_array, '', 'search');
								
					$this->data['s'] = $s;
					$this->data['feedbacks'] = $return_array;
				} else {
					$this->data['s'] = $s;
					$this->data['feedbacks'] = array();
				}			
				
			}
		}
		/* Load Template */
		//$this->load->view('user/signin', $this->data);
		$this->template->front_render('home', $this->data);
	}
	public function questionnaire($code){
		$email='';
		$user_id=0;
		if(!empty($this->session->userdata['mec_user'])){
			$this->user = $this->session->userdata['mec_user'];
			$this->data['user_info'] = $this->user;
			$user_id=$this->user['id'];
		}else{
			$this->data['user_info']=array();
			$user_id=0;
		}
		$this->data['email']='';
		if(isset($_POST['submit-survey'])){
			
			$survey_id=$this->input->post('survey_id');
			$email=$this->input->post('email');
			$answers=$this->input->post('answers');
			if(empty($email))
				$email='';
			$this->data['email']=$email;
			if(!empty($email)){
				$user=$this->encrypted_model->get_user_by_email($email);
				if(!empty($user)){
					$this->user=$user;
					$user_id=$this->user['id'];
				}
			}		
			$already_submited=$this->survey_model->user_answered_survey($survey_id,$user_id,$email);
			if($already_submited){
				$this->session->set_flashdata('error', '<p>You are already submited this survey.</p>');
			}else{
				if(!empty($answers)){
					$result_id=$this->survey_model->add_survey_user($survey_id,$user_id,$email);
					
					foreach($answers as $question_id=>$opt){						
						if(is_array($opt)){	
							$choicAns=array();
							foreach($opt as $k=>$v){
								$choicAns[]=$k;
							}
							$opt=serialize($choicAns);							
						}
						
						$this->survey_model->add_location_survey($user_id,$survey_id,$question_id,$opt,$email,$result_id);
					}
					
					$this->session->set_flashdata('success', '<p>Your answers are successfully submited for this survey.</p>');
				}
			}
			
		}
		$survey=$this->survey_model->get_location_survey_by_code($code,$user_id,$email);
		if(!empty($survey)){
			$this->data['survey']=$survey;	
			$this->template->front_render('guest/survey',$this->data);
		}else{		
			//redirect('/404/');
			show_404();
			//CI_Exceptions::show_error()
		}
	}
	public function join_title($invitation_id){
		$invitation=$this->encrypted_model->get_invitation($invitation_id);
		$user=$this->encrypted_model->get_user_by_email($invitation['email']);
		$id=$invitation['title_id'];
		if(empty($user)){
			$insert_array=array('email'=>$invitation['email'],
			'create_date'=>date('Y-m-d H:i:s'),
			'modify_date'=>date('Y-m-d H:i:s'),
			'last_login'=>date('Y-m-d H:i:s'),
			);
			$user_id = $this->common->insert_data_getid($insert_array, $tablename = 'users');
			$user=$this->encrypted_model->get_user_by_email($invitation['email']);
			
		}
		$this->user=$user;
		
		if($this->input->post('slug')){	
			$slug=$this->input->post('slug');
			$title_id=$this->input->post('title_id');
			$answer=$this->input->post('answer');
			if(!empty($answer) && is_array($answer)){
					foreach($answer as $question_id=>$ans){
						
						$questions=$this->survey_model->add_question_answer($this->user['id'],$title_id,$question_id,$ans);
					}
			}
			redirect(current_url());
		}
		
		$this->data['module_name'] = 'Encrypted';
        $this->data['section_title'] = 'Encrypted Title Detail';
		
		$getcountry = $this->common->select_data_by_id('users', 'id', $this->user['id'], 'country', '');	
		$country = $this->user['country'];
		
		$is_title_linked_user=$this->encrypted_model->is_title_linked_user($id,$this->user['id']);
		$is_title_owner=$this->encrypted_model->is_title_owner($id,$this->user['id']);
		$s='';
		if($this->input->post("search")){			
			$s=$this->input->post("s");
			$this->data['is_search'] = true;			
		}
		$this->data['s'] = $s;
		
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
			
		
		$this->common->update_notification_status($this->user['id'],6,$title['title_id']);
		$groups = $this->common->get_user_groups($this->user['id']);
		$this->data['groups']=$groups;
		
		if($title['is_survey']){
			$questions=$this->survey_model->get_survey_questions($title['title_id'],$this->user['id']);	
			$this->data['form_action']='';
			$return_array = $this->common->adBannersSurvey($questions, $country, 'encrypted',$title['title_id'],1,count($questions)+10);			
			$this->data['questions']=$return_array;
			$this->template->front_render('user/encrypted_survey_single',$this->data);	
		}else{
			$this->data['form_action']='home/encrypted_reply/'.$invitation_id;
			$this->template->front_render('user/encrypted_title_single',$this->data);
		}	
	}
	public function encrypted_reply($invitation_id) {
		$invitation=$this->encrypted_model->get_invitation($invitation_id);
		$user=$this->encrypted_model->get_user_by_email($invitation['email']);
		$id=$invitation['title_id'];
		if(empty($user)){
			$insert_array=array('email'=>$invitation['email'],
			'create_date'=>date('Y-m-d H:i:s'),
			'modify_date'=>date('Y-m-d H:i:s'),
			'last_login'=>date('Y-m-d H:i:s'),
			);
			$user_id = $this->common->insert_data_getid($insert_array, $tablename = 'users');
			$user=$this->encrypted_model->get_user_by_email($invitation['email']);
			
		}
		$this->user=$user;
		$title_id = $this->input->post('id');
		$slug = $this->input->post('slug');
		$privacy = $this->input->post('privacy');
		$privacy=($privacy==1)? 1: 0;
		
		if (!empty($slug)){			
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
					
					redirect('join/encrypted/'.$invitation_id);
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
			redirect('join/encrypted/'.$invitation_id);
		}
	}
		

}
