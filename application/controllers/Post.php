<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;

class Post extends CI_Controller {
	
	public $data;
	
	public $user;
	
	private $perPage = 10;
	
	private $aws_client;
	private $user_country;
	private $user_lang='en';
    public function __construct() {
        parent::__construct();

        // Prevent access without login
		
		
		// Load library
		$this->load->library('s3');
		$this->load->library('template');
		
		$this->aws_client = ClientBuilder::create()->setHosts(["search-feedbacker-q3gdcfwrt27ulaeee5gz3zbezm.eu-west-1.es.amazonaws.com:80"])->build();

        $this->data['title'] = "Post | Feedbacker ";

        // Load Login Model
        $this->load->model('common');
		
		if(isset($this->session->userdata['mec_user'])){
			$this->user = $this->session->userdata['mec_user'];
			$this->data['user_info'] = $this->user;
		}else{
			$this->data['user_info']=array();
		}
		
		if(isset($this->session->userdata['user_country'])){
			$this->user_country = $this->session->userdata['user_country'];			
		}else{
			if(!empty($this->user['country']))
				$this->user_country=$this->user['country'];
			else
				$this->user_country='jo';
		}
		
		if(isset($this->session->userdata['user_lang'])){
			$this->user_lang = $this->session->userdata['user_lang'];			
		}else{
			if(!empty($this->user['language']))
				$this->user_lang=$this->user['language'];
			else
				$this->user_lang='en';
		}
		
		// Load Language File		
		if ($this->user_lang == 'ar') {
			$this->lang->load('message','arabic');
			$this->lang->load('label','arabic');
		} else {
			$this->lang->load('message','english');
			$this->lang->load('label','english');
		}
		$this->data['language']=$this->user_lang;
		$this->data['user_info']['language']=$this->user_lang;

        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

	public function index() {
		/* Load Template */
		$this->template->front_render('user/dashboard');
	}
	
	public function get_location() {
		if ($this->input->is_ajax_request()) {
			$latitude = $this->input->post('latitude');
			$longitude = $this->input->post('longitude');			
			
			if(!empty($latitude) && !empty($longitude)){
				//Send request and receive json data by latitude and longitude
				$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&sensor=false';
				$json = @file_get_contents($url);
				$data = json_decode($json);
				$status = $data->status;
				if($status=="OK"){
					//Get address from json data
					$location = $data->results[0]->formatted_address;
				}else{
					$location =  '';
				}
				//Print address 
				echo json_encode(array("location" => $location));
			}
		}
	}
	
	// Like / Unlike Feedback
	public function remove_feedback_photo(){
		if ($this->input->is_ajax_request()) {
			$image_id = $this->input->post('image_id');
			$feedback_id = $this->input->post('feedback_id');
			//$this->common->delete_data('feedback_images', 'image_id', $image_id);
			$feedback_images = $this->common->select_data_by_id('feedback_images', 'feedback_id', $feedback_id, '*');
			echo json_encode(array('success'=>true,'images'=>$feedback_images));
		}
	}
	public function delete_feedback($id){
		if(!isset($this->session->userdata['mec_user'])){
			redirect('signin');
		}
		$update_data = array('deleted' => 1);
        $update_result = $this->common->update_data($update_data, 'feedback', 'feedback_id', $id);

        if ($update_result) {
            $docParams = [
                'index' => 'feedback',
                'type' => 'feedback_type',
                'id' => $id
            ]; 

            $response = $this->aws_client->delete($docParams);
            $this->session->set_flashdata('success', 'Feedback successfully deleted');
            redirect('user/profile', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
            redirect('user/profile', 'refresh');
        }
		
	}
	
    function like() {
		
		if ($this->input->is_ajax_request()) {
			$feedback_id = $this->input->post('feedback_id');
			$totl_likes = $this->input->post('totl_likes');
			
			$condition_array = array('user_id' => $this->user['id'], 'feedback_id' => $feedback_id);
			$likes = $this->common->select_data_by_condition('feedback_likes', $condition_array, $data = '*', $short_by = '', $order_by = '', $limit = '1', $offset = '', $join_str = array(), $group_by = '');
			
			if(count($likes) > 0) {
				// Unlike Feedback
				$this->common->delete_data('feedback_likes', 'like_id', $likes[0]['like_id']);
	
				// Check / Add Notification for users
				$this->common->notification('', $this->user['id'], $title_id = '', $feedback_id, $replied_to = '', 3);
	
				echo json_encode(array('is_liked' => 0, 'likes' => $totl_likes, 'message' => $this->lang->line('success_unlike_feedback'), 'status' => 1));
				die();
			} else {
				// Like Feedback
				$insert_array['user_id'] = $this->user['id'];
				$insert_array['feedback_id'] = $feedback_id;
				
				$insert_result = $this->common->insert_data($insert_array, $tablename = 'feedback_likes');
	
				// Check / Add Notification for users
				$this->common->notification('', $this->user['id'], $title_id = '', $feedback_id, $replied_to = '', 3);
	
				echo json_encode(array('is_liked' => 1, 'likes' => $totl_likes, 'message' => $this->lang->line('success_like_feedback'), 'status' => 1));
				die();
			}
		}
    }

    public function create() {
        if(!isset($this->session->userdata['mec_user'])){
            redirect('signin');
        }
        //check post and save data

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            if(empty($_FILES['feedback_video']['name']) && !empty($_FILES['feedback_img']['type'])){
                $i=0;
                foreach($_FILES['feedback_img']['type'] as $type){
                    if(preg_match('/video\/*/',$type)){
                        $_FILES['feedback_video']['name']=$_FILES['feedback_img']['name'][$i];
                        $_FILES['feedback_video']['type']=$_FILES['feedback_img']['type'][$i];
                        $_FILES['feedback_video']['tmp_name']=$_FILES['feedback_img']['tmp_name'][$i];
                        $_FILES['feedback_video']['error']=$_FILES['feedback_img']['error'][$i];
                        $_FILES['feedback_video']['size']=$_FILES['feedback_img']['size'][$i];
                    }
                    $i++;
                }
            }



            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('feedback_cont', 'Feedback', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('post/create');
            }

            $feedback_imges = array();
            $feedback_thumb = '';
            $feedback_video = '';
            $feedback_pdf = '';
            $feedback_pdf_name = '';
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
            // Image Upload End

            // Video Upload Start
            if (!empty($_FILES['feedback_video']['name'])) {

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

            // Video Upload End
            if (!empty($_FILES['feedback_pdf']['name'])) {
                $config_pdf['upload_path'] = $this->config->item('feedback_pdf_upload_path');
                $config_pdf['max_size'] = $this->config->item('feedback_pdf_max_size');
                $config_pdf['allowed_types'] = $this->config->item('feedback_allowed_pdf_types');
                $config_pdf['overwrite'] = FALSE;
                $config_pdf['remove_spaces'] = TRUE;
                $config_pdf['file_name'] = time();

                $this->load->library('upload', $config_pdf);
                $this->upload->initialize($config_pdf);

                if (!$this->upload->do_upload('feedback_pdf')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', strip_tags($error));
                    var_dump($error);
                    //redirect('post/create');
                } else {
                    $pdf_details = $this->upload->data();
                    $feedback_pdf = $pdf_details['file_name'];
                    $feedback_pdf_name=$_FILES['feedback_pdf']['name'];

                    // Generate video thumbnail
                    $pdf_path = $pdf_details['full_path'];

                    $this->s3->putObjectFile($pdf_details['full_path'], S3_BUCKET, $config_pdf['upload_path'].$pdf_details['file_name'], S3::ACL_PUBLIC_READ);
                    unlink($config_pdf['upload_path'].$pdf_details['file_name']);

                }
            }
            // Check / Add Title
            $title = trim($this->input->post('title'));
            $files_json = $this->input->post('files_json');
            $friend_list = $this->input->post('friend_list');

            $contition_array = array('title' => $title);
            $check_title = $this->common->select_data_by_condition('titles', $contition_array, $data = '*', $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str = array(), $group_by='');

            if(count($check_title) > 0) {
                // Restore If deleted
                $update_data = array('deleted' => 0);
                $update_result = $this->common->update_data($update_data, 'titles', 'title_id', $check_title[0]['title_id']);

                $insert_array['title_id'] = $check_title[0]['title_id'];
                $title_id=$check_title[0]['title_id'];
            } else {
                $insert_result = $this->common->insert_data_getid(array('title' => $title,'user_id'=>$this->user['id']), $tablename = 'titles');
                $title_id=$insert_result;

                $follow_array['user_id'] = $this->user['id'];
                $follow_array['title_id'] = $insert_result;
                $auto_follow = $this->common->insert_data($follow_array, $tablename = 'followings');
                $insert_array['title_id'] = $insert_result;
            }
            $insert_array['user_id'] = $this->user['id'];
            $str_feedback=trim(json_encode($this->input->post('feedback_cont')),'"');
            $str_feedback=str_replace("\r\n","",$str_feedback);
            $insert_array['feedback_cont'] = $this->emoji->Encode($str_feedback);
            print_r($_POST);;
            if(!empty($files_json) && is_array($files_json)){
                foreach($files_json as $file){
                    $fileObj=json_decode($file);
                    if($fileObj->type=='image'){
                        $feedback_imges[]=array('image'=>$fileObj->file_name,'thumb'=>$fileObj->thumb_name);
                    }
                    if($fileObj->type=='video'){
                        $feedback_thumb=$fileObj->thumb_name;
                        $feedback_video=$fileObj->file_name;
                    }
                    if($fileObj->type=='pdf'){
                        $feedback_pdf=$fileObj->file_name;
                        $feedback_pdf_name=$fileObj->name;
                    }
                }
            }
            if(!empty($friend_list) && is_array($friend_list)){
                $insert_array['tagged']=serialize($friend_list);
            }
            if(count($feedback_imges)>0){
                $insert_array['feedback_img'] = $feedback_imges[0]['image'];
                $insert_array['feedback_thumb'] = $feedback_imges[0]['thumb'];
            }
            /*if($feedback_img != '') {
                $insert_array['feedback_img'] = $feedback_img;
            }
			*/
            if(!empty($feedback_thumb)) {
                $insert_array['feedback_thumb'] = $feedback_thumb;
            }
            if(!empty($feedback_video)) {
                $insert_array['feedback_video'] = $feedback_video;
            }
            if($feedback_pdf != '') {
                $insert_array['feedback_pdf'] = $feedback_pdf;
            }
            if($feedback_pdf_name != '') {
                $insert_array['feedback_pdf_name'] = $feedback_pdf_name;
            }

            $insert_array['latitude'] = $this->input->post('latitude');
            $insert_array['longitude'] = $this->input->post('longitude');
            $insert_array['location'] = $this->input->post('location');
            $insert_array['country'] = $this->user['country'];
            $insert_array['datetime'] = date('Y-m-d H:i:s');
            $insert_array['tagged_friends'] = json_encode($this->input->post('tagged_friends'));
            $insert_array['feedback_status'] = $this->input->post('feedback_status');

//            var_dump($insert_array);die;
            $insert_result = $this->common->insert_data_getid($insert_array, $tablename = 'feedback');

            if(count($feedback_imges)>0){
                foreach($feedback_imges as $img){
                    $insarr=array('feedback_id'=>$insert_result,
                        'feedback_img'=>$img['image'],
                        'feedback_thumb'=>$img['thumb']);
                    $img_id=$this->common->insert_data_getid($insarr, $tablename = 'feedback_images');
                }
            }

            $insert_array['feedback_id'] = $insert_result;



            if ($insert_result) {
                // Check / Add Notification for users
                $this->common->notification('', $this->user['id'], $title_id, $insert_result, $replied_to = '', 2);
                $this->session->set_flashdata('success', '<p>'.$this->lang->line('success_feedback_submit').'</p>');
                redirect('user/dashboard');
            } else {
                $this->session->set_flashdata('error', '<p>'.$this->lang->line('error_feedback_submit').'</p>');
                redirect('post/create');
            }
            //
        }

        $this->data['module_name'] = 'Post';
        $this->data['section_title'] = 'Create Post';

        /* Load Template */
        $this->template->front_render('post/create', $this->data);
    }

	public function edit($id){
		if(!isset($this->session->userdata['mec_user'])){
			redirect('signin');
		}
		 if ($this->input->post('feedback_id')) {
			 $feedback_imges = array();			
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
			$update_array = array(
				'feedback_cont' => trim($this->input->post('feedback_cont')),
                'feedback_status' => trim($this->input->post('feedback_status')),
                'tagged_friends' => json_encode($this->input->post('tagged_friends')),
				'location' => trim($this->input->post('location')),
				'longitude' => trim($this->input->post('longitude')),
				'latitude' => trim($this->input->post('latitude')),
            );
			if(count($feedback_imges)>0){
				 $update_array['feedback_img'] = $feedback_imges[0]['image'];	
				 $update_array['feedback_thumb'] = $feedback_imges[0]['thumb'];
			}
			$update_result = $this->common->update_data($update_array, 'feedback', 'feedback_id', $this->input->post('feedback_id'));
			if(count($feedback_imges)>0){
				foreach($feedback_imges as $img){
					$insarr=array('feedback_id'=> $this->input->post('feedback_id'),
					'feedback_img'=>$img['image'],
					'feedback_thumb'=>$img['thumb']);
					$img_id=$this->common->insert_data_getid($insarr, $tablename = 'feedback_images');
				}
			}

           /* $params = ['index' => 'feedback'];
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
            }*/
            $feedback_detail = $this->common->select_data_by_id('feedback', 'feedback_id', $this->input->post('feedback_id'), '*');
			
          /*  $docParams = [
                'index' => 'feedback',
                'type' => 'feedback_type',
                'id' => $this->input->post('feedback_id'),
                'body' => $feedback_detail[0]
            ];

            $response = $this->aws_client->index($docParams);*/
           
            if ($update_result) {
                $this->session->set_flashdata('success', 'Feedback successfully updated.');
                redirect('post/detail/'.$id, 'refresh');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
                redirect('post/edit/'.$id, 'refresh');
            }
		 }
		$this->data['module_name'] = 'Post';
        $this->data['section_title'] = 'Edit Feedback';
		$feedback_detail = $this->common->select_data_by_id('feedback', 'feedback_id', $id, '*');
		$feedback_images = $this->common->select_data_by_id('feedback_images', 'feedback_id', $id, '*');


		$feedback_detail[0]['images']=$feedback_images;
		$this->data['feedback_detail'] = $feedback_detail;
        $tagged_friends = json_decode($this->data['feedback_detail'][0]['tagged_friends']);
        foreach ($tagged_friends as $v){
            $this->data['friends_details'][] = $this->common->fetch('db_users', array('id' => $v))[0];
        }
		$this->template->front_render('post/edit', $this->data);
	}
	public function reply() {
		//check post and save data
		$replied_to = $this->input->post('id');
		
		if (empty($replied_to)) {
			redirect('post/create');
        } else {
			$this->form_validation->set_rules('feedback_cont', 'Feedback', 'trim|required');
			
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
				redirect('post/detail/'.$title_id);
			}
			
           
            $feedback_imges = array();			
            $feedback_thumb = '';
            $feedback_video = '';	
			$feedback_pdf = '';	
			
			
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
            // Image Upload End
            
            // Video Upload Start
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
        
/*                  if($video_details['file_ext'] == ".mov" || $video_details['file_ext'] == ".MOV") {
                        // ffmpeg command to convert video
                        shell_exec("ffmpeg -i ".$video_details['full_path']." ".$video_details['file_path'].$video_details['raw_name'].".mp4");
                    
                        /// In the end update video name in DB
                        $feedback_video = $video_details['raw_name'].'.'.'mp4';
                    }*/
                    
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
                    
                $this->load->library('upload', $config_pdf);
                $this->upload->initialize($config_pdf);
                
                if (!$this->upload->do_upload('feedback_pdf')) {
                   $error = $this->upload->display_errors();
					$this->session->set_flashdata('error', strip_tags($error));
					var_dump($error);
					//redirect('post/create');
                } else {
                    $pdf_details = $this->upload->data();
					$feedback_pdf = $pdf_details['file_name'];
        
/*                  if($video_details['file_ext'] == ".mov" || $video_details['file_ext'] == ".MOV") {
                        // ffmpeg command to convert video
                        shell_exec("ffmpeg -i ".$video_details['full_path']." ".$video_details['file_path'].$video_details['raw_name'].".mp4");
                    
                        /// In the end update video name in DB
                        $feedback_video = $video_details['raw_name'].'.'.'mp4';
                    }*/
                    
                    // Generate video thumbnail
                    $pdf_path = $pdf_details['full_path'];               
                    
                    $this->s3->putObjectFile($pdf_details['full_path'], S3_BUCKET, $config_pdf['upload_path'].$pdf_details['file_name'], S3::ACL_PUBLIC_READ);
					unlink($config_pdf['upload_path'].$pdf_details['file_name']);
                    
                }
            }
           
            
			$gettitle = $this->common->select_data_by_id('feedback', 'feedback_id', $replied_to, 'title_id', '');
            if (count($gettitle) > 0) {
                $insert_array['title_id'] = $gettitle[0]['title_id'];
            }
			
			$insert_array['user_id'] = $this->user['id'];
			$str_feedback=trim(json_encode($this->input->post('feedback_cont')),'"');
			$str_feedback=str_replace("\r\n","",$str_feedback);
            $insert_array['feedback_cont'] = $this->emoji->Encode($str_feedback);
           // $insert_array['feedback_cont'] = $this->input->post('feedback_cont');
			if(count($feedback_imges)>0){
				 $insert_array['feedback_img'] = $feedback_imges[0]['image'];	
				 $insert_array['feedback_thumb'] = $feedback_imges[0]['thumb'];
			}
           /* if($feedback_img != '') {
                $insert_array['feedback_img'] = $feedback_img;
            }
            if($feedback_thumb != '') {
                $insert_array['feedback_thumb'] = $feedback_thumb;
            }*/
            if($feedback_video != '') {
                $insert_array['feedback_video'] = $feedback_video;
            }
			if($feedback_pdf != '') {
                $insert_array['feedback_pdf'] = $feedback_pdf;
            }
            $insert_array['latitude'] = $this->input->post('latitude');
            $insert_array['longitude'] = $this->input->post('longitude');
            $insert_array['location'] = $this->input->post('location');
			$insert_array['replied_to'] = $replied_to;
            $insert_array['country'] = $this->user['country'];
            $insert_array['datetime'] = date('Y-m-d H:i:s');

            $insert_result = $this->common->insert_data_getid($insert_array, $tablename = 'feedback');
			if(count($feedback_imges)>0){
				foreach($feedback_imges as $img){
					$insarr=array('feedback_id'=>$insert_result,
					'feedback_img'=>$img['image'],
					'feedback_thumb'=>$img['thumb']);
					$img_id=$this->common->insert_data_getid($insarr, $tablename = 'feedback_images');
				}
			}
            // AWS Elastic Search
            /*$params = ['index' => 'feedback'];
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
            } */
            $insert_array['feedback_id'] = $insert_result;

            /*$docParams = [
                'index' => 'feedback',
                'type' => 'feedback_type',
                'id' => $insert_result,
                'body' => $insert_array
            ]; 

            $response = $this->aws_client->index($docParams);*/

            if ($insert_result) {
				// Check / Add Notification for users
                $this->common->notification('', $this->user['id'], $title_id = '', $insert_result, $replied_to, 4);

                $this->session->set_flashdata('success', '<p>'.$this->lang->line('success_reply_submit').'</p>');
//				redirect('post/detail/'.$replied_to);
                echo json_encode(array('message' => 'You have commented the post', 'status' => 1));
            } else {
				$this->session->set_flashdata('error', '<p>'.$this->lang->line('error_reply_submit').'</p>');
                echo json_encode(array('error_message' => 'An error occurred', 'status' => 0));
            }
			//
		}
	}
	
	public function title($id) {
		// Trends
		
		$this->data['trends'] = $this->common->getTrends($this->user['country']);
		
		// What to Follow
		$this->data['to_follow'] = $this->common->whatToFollow($this->user['id'], $this->user['country']);
		
		// Get Feedbacks From Title ID
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
			
		//$contition_array = array('feedback.title_id' => $id, 'feedback.replied_to' => NULL, 'feedback.deleted' => 0, 'feedback.status' => 1);
		
		$contition_array = array('feedback.title_id' => $id, 'feedback.deleted' => 0, 'feedback.status' => 1);
		
		$data = 'feedback_id, feedback.title_id, title, users.id as user_id, name, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video, replied_to, location, feedback.datetime as time, is_hidden';
		if (!empty($this->input->get("page"))) {
			$page = ceil($this->input->get("page") - 1);
			$start = ceil($page * $this->perPage);
			
			$feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $this->perPage, $start, $join_str, $group_by = '');
			if(count($feedback) > 0) {
				// Get Likes, Followings and Other details
				$result = $this->common->getFeedbacks($feedback, $this->user['id']);
				
				// Append Ad Banners
				$return_array = $this->common->adBanners($result, $this->user['country'], 'title', $this->input->get("page"), $id);
				
				$this->data['module_name'] = 'Post';
				$this->data['section_title'] = $feedback[0]['title'];
				
				$this->data['feedbacks'] = $return_array;
			} else {
				$this->data['feedbacks'] = array();
				$this->data['no_record_found'] = $this->lang->line('no_results');
			}
			
			$response = $this->load->view('post/ajax', $this->data);
			echo json_encode($response);
		} else {
			$feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $this->perPage, 0, $join_str, $group_by = '');
			
			if(count($feedback) > 0) {
				// Get Likes, Followings and Other details
				$result = $this->common->getFeedbacks($feedback, $this->user['id']);
				
				// Append Ad Banners
				$return_array = $this->common->adBanners($result, $this->user['country'], 'title', '', $id);
				
				$this->data['feedbacks'] = $return_array;
			} else {
				$this->data['feedbacks'] = array();
				$this->data['no_record_found'] = $this->lang->line('no_results');
			}
			
			$this->data['module_name'] = 'Post';
			$this->data['section_title'] = $feedback[0]['title'];
			
			/* Load Template */
			$this->template->front_render('post/title', $this->data);
		}
	}
	
	public function detail($id) {
		$this->data['module_name'] = 'Post';
        $this->data['section_title'] = 'Detail';
		
		// Get Feedback Details
        $return_array = $this->common->getFeedbackDetail($this->user['id'], $id);

        // Get all replies for this feedback
        $contition_array = array('replied_to' => $id, 'feedback.deleted' => 0, 'feedback.status' => 1);
        $replies = $this->common->select_data_by_condition('feedback', $contition_array, 'feedback_id', $sortby = 'feedback.datetime', $orderby = 'DESC', $limit = '', $offset = '', $join_str = array(), $group_by = '');
        
        $return_array['replies'] = array();
        foreach($replies as $reply) {
            $feedback = $this->common->getFeedbackDetail($id, $reply['feedback_id']);		
            array_push($return_array['replies'], $feedback);
        }
		
		$this->data['feedback'] = $return_array;
		
		// Get feedbacks from same User
		$others_array = array();
		
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
		
		$data = 'feedback_id, feedback.title_id, title, name, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video, replied_to, location, feedback.datetime as time';
		
		$search_condition = "feedback_id != ".$id." AND users.id = ".$return_array['user_id']." AND replied_to IS NULL AND feedback.deleted = 0 AND feedback.status = 1";
        $others = $this->common->select_data_by_search('feedback', $search_condition, $condition_array = array(), $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $limit = '2', $offset = '', $join_str);
		
		if(count($others) > 0) {
			foreach ($others as $item) {
				$return = array();
				$return['id'] = $item['feedback_id'];
				$return['title_id'] = $item['title_id'];                
				$return['title'] = $item['title'];
				$return['feedback_images'] = array();
				
				$feedback_images = $this->common->select_data_by_id('feedback_images', 'feedback_id', $item['feedback_id'], '*');	
			
				// Get likes for this feedback
				$contition_array_lk = array('feedback_id' => $item['feedback_id']);
				$flikes = $this->common->select_data_by_condition('feedback_likes', $contition_array_lk, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
				
				$return['likes'] = "";
				
				if(count($flikes) > 1000) {
					$return['likes'] = (count($flikes)/1000)."k";
				} else {
					$return['likes'] = count($flikes);
				}
				
				// Get followers for this title
				$contition_array_fo = array('title_id' => $item['title_id']);
				$followings = $this->common->select_data_by_condition('followings', $contition_array_fo, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
				
				$return['followers'] = "";
				
				if(count($followings) > 1000) {
					$return['followers'] = (count($followings)/1000)."k";
				} else {
					$return['followers'] = count($followings);
				}
				
				// Check If user liked this feedback
				$contition_array_li = array('feedback_id' => $item['feedback_id'], 'user_id' =>$this->user['id']);
				$likes = $this->common->select_data_by_condition('feedback_likes', $contition_array_li, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
							
				if(count($likes) > 0) {
					$return['is_liked'] = TRUE;
				} else {
					$return['is_liked'] = FALSE;
				}
				
				// Check If user followed this title
				$contition_array_ti = array('title_id' => $item['title_id'], 'user_id' =>$this->user['id']);
				$followtitles = $this->common->select_data_by_condition('followings', $contition_array_ti, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
							
				if(count($followtitles) > 0) {
					$return['is_followed'] = TRUE;
				} else {
					$return['is_followed'] = FALSE;
				}
				
				$return['name'] = $item['name'];
				
				if(isset($item['photo'])) {
					$return['user_avatar'] = S3_CDN . 'uploads/user/thumbs/' . $item['photo'];
				} else {
					$return['user_avatar'] = ASSETS_URL . 'images/user-avatar.png';
				}
				if(count($feedback_images)>0){
					foreach($feedback_images as $img){
						$imagearr=array();
						$imagearr['feedback_img'] = S3_CDN . 'uploads/feedback/main/' . $img['feedback_img'];
						$imagearr['feedback_thumb'] = S3_CDN . 'uploads/feedback/thumbs/' . $img['feedback_thumb'];
						$return['feedback_images'][]=$imagearr;
						
					}
				}
				if($item['feedback_img'] !== "") {
					$return['feedback_img'] = S3_CDN . 'uploads/feedback/main/' . $item['feedback_img'];
					
				} else {
					$return['feedback_img'] = "";
				}

				if($item['feedback_thumb'] !== "") {
					$return['feedback_thumb'] = S3_CDN . 'uploads/feedback/thumbs/' . $item['feedback_thumb'];
				} elseif($item['feedback_img'] !== "") {
					$return['feedback_thumb'] = S3_CDN . 'uploads/feedback/main/' . $item['feedback_img'];
				} else {
					$return['feedback_thumb'] = "";
				}
				
				if($item['feedback_video'] !== "") {
					$return['feedback_video'] = S3_CDN . 'uploads/feedback/video/' . $item['feedback_video'];
					//$return['feedback_thumb'] = S3_CDN . 'uploads/feedback/thumbs/video_thumbnail.png';
				} else {
					$return['feedback_video'] = "";
				}

				$return['location'] = $item['location'];
				$return['feedback'] = $item['feedback_cont'];
				$return['time'] = $this->common->timeAgo($item['time']);

				array_push($others_array, $return);
			}
			
			$this->data['others'] = $others_array;
			
		} else {
			$this->data['others'] = array();
			$this->data['no_record_found'] = $this->lang->line('no_record_found');
		}
		
		$this->data['module_name'] = 'Post';
		$this->data['section_title'] = $return_array['title'];
		$this->data['meta']['description'] =str_replace("\\n","<br>",str_replace("\\r\\n","<br>",$this->emoji->Decode(nl2br($return_array['feedback']))));
		
		if(!empty($return_array['feedback_images']) && count($return_array['feedback_images'])>0){
			
			$this->data['meta']['image']=$return_array['feedback_images'][0]['feedback_thumb'];
		}
		if(!empty($return_array['feedback_img'])){
			
			$this->data['meta']['image']=$return_array['feedback_img'];
		}
		if(!empty($return_array['feedback_video']) && !empty($return_array['feedback_thumb']) ){
			
			$this->data['meta']['image']=$return_array['feedback_thumb'];
			$this->data['meta']['type']='video';
			
		}
		
		/* Load Template */
		$this->template->front_render('post/detail', $this->data);
	}

    public function share_post(){
        $session_data = $this->session->userdata['mec_user'];
        $session_id = $session_data['id'];
        if(isset($_POST['feedback_id']) && isset($_POST['title_id'])){
            $post_id = $_POST['feedback_id'];
            $title_id = $_POST['title_id'];
            $data = array(
                'user_id' => $session_id,
                'feedback_id' => $post_id,
                'title_id' => $title_id,
            );
            $this->common->insert('db_share_post', $data);
            echo json_encode(array('message' => 'Post shared successfully', 'status' => 1));
            die;
        }
        echo json_encode(array('error_message' => 'An error occurred', 'status' => 0));
    }

    public function delete_feedback_post(){
        if(isset($_POST['feedback_id'])){
            $feedback_id = $_POST['feedback_id'];

            $data = array(
              'feedback_id' => $feedback_id,
            );

            $this->common->delete('db_feedback', $data);
            echo json_encode(array('message' => 'Feedback deleted successfully', 'status' => 1));
            die;
        }
        echo json_encode(array('error_message' => 'An error occurred', 'status' => 0));
    }

//    public function edit_feedback_post(){
//        if(isset($_POST['feedback_id'])){
//            $feedback_id = $_POST['feedback_id'];
//        }
//    }

    public function hide_feedback_post(){
        if(isset($_POST['feedback_id']) && isset($_POST['is_hidden'])){
            $feedback_id = $_POST['feedback_id'];
            $is_hidden = $_POST['is_hidden'];
            $where = array(
                'feedback_id' => $feedback_id,
            );
            $data = array(
                'is_hidden' => $is_hidden,
            );
            $this->common->update('db_feedback', $where, $data);
            echo json_encode(array('message' => 'Success', 'status' => 1));
            die;
        }
        echo json_encode(array('error_message' => 'Error', 'status' => 0));
    }

    public function report_feedback(){
        if(isset($_POST['report_feedback_id']) && isset($_POST['report_title_id']) && isset($_POST['report_content'])){
            $data = array(
                'feedback_id' => $_POST['report_feedback_id'],
                'title_id' => $_POST['report_title_id'],
                'report_content' => $_POST['report_content'],
            );
            $this->common->insert('db_report_feedback', $data);
            echo json_encode(array('message' => 'Reported Successfully', 'status' => 1));
            die;
        }
        echo json_encode(array('error_message' => 'An error occurred', 'status' => 0));
    }

    public function hide_all_user_feedbacks(){
        if(isset($_POST['session_id']) && isset($_POST['user_id'])){
            $data = array(
                'session_id' => $_POST['session_id'],
                'user_id' => $_POST['user_id'],
            );
            $this->common->insert('db_hide_all_user_feedbacks', $data);
            echo json_encode(array('message' => 'All feedbacks of this user are hidden', 'status' => 1));
            die;
        }
        echo json_encode(array('error_message' => 'An error occurred', 'status' => 0));
    }
}
