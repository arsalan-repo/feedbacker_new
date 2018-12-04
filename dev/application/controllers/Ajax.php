<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ajax extends CI_Controller {
	public $data;
	public $user;
	function __construct() {
        parent::__construct();
        $this->load->model('common');
		$this->load->model('encrypted_model');
		$this->load->model('survey_model');	
		
        $this->load->model('pushNotifications'); 
        $this->load->library('s3'); 
		$this->load->library('template');      
         
        if($this->input->post('language') == 'ar') {
            $this->lang->load('message','arabic');
        } else {
            $this->lang->load('message','english');
        }
        $GLOBALS['error_code'] = 0;
        $GLOBALS['api_debug_mode'] = false;
        $this->user = $this->session->userdata['mec_user'];
		$this->data['user_info'] = $this->user;
    }
	public function index() {
		
		echo 'ajax file';
	}
	public function get_users(){
		$users=array();
		if ($this->input->is_ajax_request()) {
			$search_string = $this->input->get('term');			
			$this->db->like('name', $search_string);
			$this->db->select("id,name,photo");
			$this->db->from('users');
			$this->db->where('searchable',1);
			$this->db->where('status',1);
			$this->db->where('verified',1);
			$query=$this->db->get();
			$users=$query->result_array();
			echo json_encode($users);
			die();
		}		
		echo json_encode(array('0' => array('id' => '', 'name' => '','photo'=>'')));		
		die();
	}
	public function find_friends(){
		$users=array();
		if ($this->input->is_ajax_request()) {
			$search_string = $this->input->get('term');	
			$user_id = $this->user['id'];
			$friends=$this->common->user_find_friends($search_string,$user_id);		
			echo json_encode($friends);
			die();
		}		
		echo json_encode(array('0' => array('user_id' => '', 'name' => '','photo'=>'')));		
		die();
	}
	
	public function get_friends(){
		$users=array();
		if ($this->input->is_ajax_request() && !empty($this->user['id'])) {
			$search_string = $this->input->get('term');	
			$user_id = $this->user['id'];
			$friends=$this->common->user_get_friends($search_string,$user_id);
			echo json_encode($friends);
			die();
		}	
			
		echo json_encode(array('0' => array('id' => '', 'name' => '','photo'=>'')));		
		die();
	}
	public function delete_feedback(){
		$fid = $this->input->post('fid');		
		if ($this->input->is_ajax_request() && !empty($this->user['id']) && !empty($fid)) {
				
			$user_id = $this->user['id'];
			$update_data = array('deleted' => 1);
			$update_result = $this->common->update_data($update_data, 'feedback', 'feedback_id', $fid);
			echo $this->db->last_query();
			echo json_encode(array('success'=>true));
			die();
		}	
			
		echo json_encode(array('success'=>false));	
		die();
	}
	
	public function upload_file(){	
		$uploadedFile=array('name'=>'','path'=>'','thumb'=>'','type'=>'');
		if(!empty($_FILES['file']['name'])){
			$uploadedFile['name']=$_FILES['file']['name'];
			$mime = $_FILES['file']['type'];
			if(strstr($mime, "video/")){
				$config['upload_path'] = $this->config->item('feedback_video_upload_path');
                $config['thumb_upload_path'] = $this->config->item('feedback_thumb_upload_path');
                $config['max_size'] = $this->config->item('feedback_video_max_size');
                $config['allowed_types'] = $this->config->item('feedback_allowed_video_types');
                $config['overwrite'] = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['file_name'] = time();  
				$this->load->library('upload', $config);
                $this->upload->initialize($config);                
                if (!$this->upload->do_upload('file')) {
                    $error = $this->upload->display_errors();
                    echo json_encode(array('RESULT' => array(), 'MESSAGE' => strip_tags($error), 'STATUS' => 0));
                    exit();
                } else {
                    $video_details = $this->upload->data(); 
                    $feedback_video = $video_details['file_name'];
					
                    $video_path = $video_details['full_path'];
                    $thumb_name = $video_details['raw_name']."_video.jpg";
                    $thumb_path = $config['thumb_upload_path'].$thumb_name;
                    shell_exec("ffmpeg -itsoffset -3 -i ".$video_path."  -y -an -f image2 -s 400x270 ".$thumb_path."");
                    $feedback_thumb = $thumb_name;
					$thumb_file_path = str_replace("video", "thumbs", $video_details['file_path']);
                    
                    $this->s3->putObjectFile($video_details['full_path'], S3_BUCKET, $config['upload_path'].$video_details['file_name'], S3::ACL_PUBLIC_READ);
                    $this->s3->putObjectFile($thumb_file_path.$feedback_thumb, S3_BUCKET, $thumb_path, S3::ACL_PUBLIC_READ);
                    unlink($config['upload_path'].$video_details['file_name']);
                    unlink($thumb_path);
					$uploadedFile['type']='video';
					$uploadedFile['file_name']=$feedback_video;
					$uploadedFile['thumb_name']=$thumb_name;
					$uploadedFile['path']=S3_CDN.$config['upload_path'].$video_details['file_name'];
					$uploadedFile['thumb']=S3_CDN.$thumb_path;
				}
			}elseif(strstr($mime, "image/")){
				$config['upload_path'] = $this->config->item('feedback_main_upload_path');
                $config['thumb_upload_path'] = $this->config->item('feedback_thumb_upload_path');
                $config['allowed_types'] = $this->config->item('feedback_allowed_types');
                $config['max_size'] = $this->config->item('feedback_main_max_size');
                $config['max_width'] = $this->config->item('feedback_main_max_width');
                $config['max_height'] = $this->config->item('feedback_main_max_height');
				$config['encrypt_name'] = TRUE;
				$this->load->library('upload', $config);
				$this->load->library('image_lib');
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('file')) {
					$error = $this->upload->display_errors();
					echo json_encode(array('RESULT' => array(), 'MESSAGE' => strip_tags($error), 'STATUS' => 0));
					exit();
				} else {
					$imgdata = $this->upload->data();	
					$feedback_image=$imgdata['file_name'];
					$thumb_file_path = str_replace("main", "thumbs", $imgdata['file_path']);
					$thumb_file_name = $config['thumb_upload_path'] . $imgdata['raw_name'].'_thumb'.$imgdata['file_ext'];
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
					if($this->image_lib->resize()) {
						$feedback_thumb = $imgdata['raw_name'].'_thumb'.$imgdata['file_ext'];
						$feedback_thumb=$feedback_thumb;
						if(file_exists($imgdata['full_path'])){
							$this->s3->putObjectFile($imgdata['full_path'], S3_BUCKET, $config_thumb['source_image'], S3::ACL_PUBLIC_READ);
								unlink($config_thumb['source_image']);
						}
						if(file_exists($thumb_file_path.$feedback_thumb)){
								$this->s3->putObjectFile($thumb_file_path.$feedback_thumb, S3_BUCKET, $thumb_file_name, S3::ACL_PUBLIC_READ);								
								unlink($thumb_file_name);								
						}
					}
					 $uploadedFile['type']='image';
					$uploadedFile['file_name']=$feedback_image;
					$uploadedFile['thumb_name']=$feedback_thumb;
					$uploadedFile['path']=S3_CDN . $config_thumb['source_image'];		
					$uploadedFile['thumb']=S3_CDN . $thumb_file_name;
				}						
			}else if($mime=='application/pdf'){
				$config['upload_path'] = $this->config->item('feedback_pdf_upload_path');
                $config['max_size'] = $this->config->item('feedback_pdf_max_size');
                $config['allowed_types'] = $this->config->item('feedback_allowed_pdf_types');
                $config['overwrite'] = FALSE;
                $config['remove_spaces'] = TRUE;
                $config['file_name'] = time(); 
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('file')) {
					$error = $this->upload->display_errors();
					echo json_encode(array('RESULT' => array(), 'MESSAGE' => strip_tags($error), 'STATUS' => 0));
					exit();
				} else {					
					$pdf_details = $this->upload->data();
					$feedback_pdf = $pdf_details['file_name'];					
                    $pdf_path = $pdf_details['full_path'];
                    $this->s3->putObjectFile($pdf_details['full_path'], S3_BUCKET, $config['upload_path'].$pdf_details['file_name'], S3::ACL_PUBLIC_READ);
					unlink($config['upload_path'].$pdf_details['file_name']);
					
					$uploadedFile['type']='pdf';
                    $uploadedFile['file_name']=$feedback_pdf;
					$uploadedFile['thumb_name']='';
					$uploadedFile['path']=S3_CDN . 'uploads/feedback/pdf/' . $pdf_details['file_name'];						
					$uploadedFile['thumb']='';
				}
			}
			
			echo json_encode(array('success'=>true,'file'=>$uploadedFile));
			exit;
		}
		
		echo json_encode(array('success'=>false,'file'=>''));
		exit;
	}
}
