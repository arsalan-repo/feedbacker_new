<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	
	public $data;
	
	public $user;
	
	private $perPage = 10;
	private $user_country;
    public function __construct() {
        parent::__construct();

        // Prevent access without login
		if(!isset($this->session->userdata['mec_user'])){
			redirect('signin');
		}
		if(isset($this->session->userdata['user_country'])){
			$this->user_country = $this->session->userdata['user_country'];			
		}else{
			
		}
		
		// Load library
		$this->load->library('s3');
		$this->load->library('template', 'facebook');

        $this->data['title'] = "User | Feedbacker ";

        // Load Login Model
        $this->load->model('common');
		$this->load->model('encrypted_model');
		$this->load->model('title_model');
		
		// Session data
		$this->user = $this->session->userdata['mec_user'];
		$this->data['user_info'] = $this->user;
		if(isset($this->session->userdata['user_country'])){
			$this->user_country = $this->session->userdata['user_country'];			
		}else{
			if(!empty($this->user['country']))
				$this->user_country=$this->user['country'];
			else
				$this->user_country='jo';
		}
		// Load Language File		
		if ($this->user['language'] == 'ar') {
			$this->lang->load('message','arabic');
			$this->lang->load('label','arabic');
		} else {
			$this->lang->load('message','english');
			$this->lang->load('label','english');
		}
		
        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
		$this->data['notification_count'] = $this->common->get_unread_notification_count($this->user['id']);
    }
	
	public function index() {
		/* Load Template */
		$this->template->front_render('user/dashboard');
	}
	
	public function language($code) {
		$this->user['language'] = $code;  
		
		$this->session->set_userdata('mec_user', $this->user);
		
		$this->load->library('user_agent');
		
		//if ($this->agent->is_referral()) {
			$refer = $this->agent->referrer();
		//}

		redirect($refer);
	}
	public function unblock(){
		$user_id=$this->input->post("user_id");
		$this->common->unblock_user($this->user['id'],$user_id);
		redirect('user/settings/6');
	}
	public function delete_group(){		
		$group_id=$this->input->post('group_id');	
		$this->common->delete_group($group_id);
		redirect('user/settings/5');
	}
	public function edit_group($group_id){		
		if(isset($_POST['submit'])){
			$title=$this->input->post('title');	
			$users=$this->input->post('users');	
			$remove_users=$this->input->post('remove_users');	
			$this->common->update_group($group_id,$title,$users,$remove_users);
		}
		$group = $this->common->get_user_group($this->user['id'],$group_id);
		$group_users=$this->common->get_group_users($group_id);		
		$this->data['module_name'] = 'User';
		$this->data['section_title'] = 'Edit Group';
		$this->data['group']=$group;
		$this->data['group_users']=$group_users;
		$this->template->front_render('user/group',$this->data);
	}	
	public function encrypted_titles($country = '') {
		$this->data['module_name'] = 'User';
        $this->data['section_title'] = 'Encrypted Titles';		
			
		if($country == '') {
			$getcountry = $this->common->select_data_by_id('users', 'id', $this->user['id'], 'country', '');	
			$country = $this->user['country'];
		} else {
			$this->user['country'] = $country; 			
			$this->session->set_userdata('mec_user', $this->user);	
			$this->data['user_info'] = $this->user;
		}
		
		$this->data['trends'] = $this->common->getTrends($country);
		$this->data['to_follow'] = $this->common->whatToFollow($this->user['id'], $country);
		$this->data['is_search'] = false;
		$s='';
		$this->data['notification_count'] = $this->common->get_unread_notification_count($this->user['id']);
		if($this->input->post("search")){			
			$s=$this->input->post("s");
			$this->data['is_search'] = true;			
		}
		$this->data['s'] = $s;
		if (!empty($this->input->get("page"))) {
			$page = ceil($this->input->get("page") - 1);
			$start = ceil($page * $this->perPage);
			$titles=$this->encrypted_model->get_encrypted_titles($this->user['id'],$start,$this->perPage,$s);
			
			
			
			//echo $this->db->last_query();
			if(count($titles) > 0) {
				/*$return_array = $this->common->adBannersEncrypted($titles, $country, 'encrypted',$this->input->get("page"));*/	
				$this->data['titles'] = $titles;
			} else {
				$this->data['titles'] = array();
				$this->data['no_record_found'] = $this->lang->line('no_results');
			}			
			$response = $this->load->view('user/encrypted-titles-ajax', $this->data);
			echo json_encode($response);
		}else{			
			$titles=$this->encrypted_model->get_encrypted_titles($this->user['id'],0,$this->perPage,$s);
			/*$return_array = $this->common->adBannersEncrypted($titles, $country, 'encrypted');	
				*/
			$this->data['titles'] = $titles;		
			$this->template->front_render('user/encrypted_titles', $this->data);
		}					
    }
	public function private_titles() {
		$this->data['module_name'] = 'User';
        $this->data['section_title'] = 'Private Titles';		
		$country = $this->user['country'];		
		$this->data['trends'] = $this->common->getTrends($country);
		$this->data['to_follow'] = $this->common->whatToFollow($this->user['id'], $country);
		$this->data['is_search'] = false;
		$s='';
		$this->data['notification_count'] = $this->common->get_unread_notification_count($this->user['id']);
		if($this->input->post("search")){			
			$s=$this->input->post("s");
			$this->data['is_search'] = true;			
		}
		$this->data['s'] = $s;
		if (!empty($this->input->get("page"))) {
			$page = ceil($this->input->get("page") - 1);
			$start = ceil($page * $this->perPage);
			$titles=$this->title_model->get_private_titles($this->user['id'],$start,$this->perPage,$s);
			if(count($titles) > 0) {								
				$this->data['titles'] = $titles;
			} else {
				$this->data['titles'] = array();
				$this->data['no_record_found'] = $this->lang->line('no_results');
			}			
			$response = $this->load->view('user/private-titles-ajax', $this->data);
			echo json_encode($response);
		}else{			
			$titles=$this->title_model->get_private_titles($this->user['id'],0,$this->perPage,$s);
			$this->data['titles'] = $titles;		
			$this->template->front_render('user/private_titles', $this->data);
		}					
    }
	public function friends_list(){
		$friends=$this->common->user_friends($this->user['id']);
		$this->data['friends']=$friends;
		$response = $this->load->view('parts/friends_list', $this->data);
		echo json_encode($response);
	}
	public function unfriend(){
		$user_id=$this->user['id'];		
		$fid=$this->input->post('fid');	
		if(!empty($user_id) && !empty($fid)){			
			$friend_id=$this->common->user_delete_friend_request($user_id,$fid);
			if($friend_id){
				print json_encode(array('success'=>true,'msg'=>'Unfriend successfully'));
				die();
			}else{
				print json_encode(array('success'=>false,'msg'=>'unable to send'));
				die();
			}
		}
		print json_encode(array('success'=>false,'msg'=>'fields required.'));
		die();
	}
	public function send_friend_request(){
		$user_id=$this->user['id'];
		$uid=$this->input->post('uid');		
		if(!empty($user_id) && !empty($uid)){			
			$friend_id=$this->common->user_send_friend_request($user_id,$uid);
			if($friend_id){
				print json_encode(array('success'=>true,'msg'=>'Sent successfully'));
				die();
			}else{
				print json_encode(array('success'=>false,'msg'=>'unable to send'));
				die();
			}
		}
		print json_encode(array('success'=>false,'msg'=>'fields required.'));
		die();
	}
	public function accept_friend_request(){
		$user_id=$this->user['id'];
		$fid=$this->input->post('fid');		
		if(!empty($user_id) && !empty($fid)){			
			$accepted=$this->common->user_accept_friend_request($user_id,$fid);
			if($accepted){
				print json_encode(array('success'=>true,'msg'=>'Accepted successfully'));
				die();
			}else{
				print json_encode(array('success'=>false,'msg'=>'unable to send'));
				die();
			}
		}
		print json_encode(array('success'=>false,'msg'=>'fields required.'));
		die();
	}
	public function delete_friend_request(){
		$user_id=$this->user['id'];
		$fid=$this->input->post('fid');		
		if(!empty($user_id) && !empty($fid)){			
			$accepted=$this->common->user_delete_friend_request($user_id,$fid);
			if($accepted){
				print json_encode(array('success'=>true,'msg'=>'Deleted successfully'));
				die();
			}else{
				print json_encode(array('success'=>false,'msg'=>'unable to send'));
				die();
			}
		}
		print json_encode(array('success'=>false,'msg'=>'fields required.'));
		die();
	}
	public function friends(){
		$this->data['is_search']=false;
		$this->data['q']='';
		$q=$this->input->get('q');
		if(!empty($q)){
			$usersList=$this->common->user_find_friends($q,$this->user['id']);
			$this->data['q']=$q;
			$this->data['usersList']=$usersList;
			$this->data['is_search']=true;
		}
		
		$country = $this->user_country;	
		$this->data['trends'] = $this->common->getTrends($country);
		$this->data['to_follow'] = $this->common->whatToFollow($this->user['id'], $country);
		$this->data['module_name'] = 'User';
		$this->data['section_title'] = 'Friends';
		$friends=$this->common->user_friends($this->user['id']);
		$friend_requests=$this->common->user_friend_requests($this->user['id']);		
		$this->data['friends']=$friends;
		$this->data['friend_requests']=$friend_requests;		
		$this->template->front_render('user/friends', $this->data);
	}
	public function hide_title($title_id){
		$this->load->library('user_agent');
		$referrer= $this->agent->referrer();
		$this->common->hide_title($this->user['id'],$title_id);
		$this->session->set_flashdata('success', 'All feedbacks from the slected title will be hidden for you.');        
		redirect($referrer);	
		
		
	}
	public function hideuserfeedbacks($uid){
		$this->load->library('user_agent');
		$referrer= $this->agent->referrer();
		$this->common->hide_user_feedbacks($this->user['id'],$uid);
		$this->session->set_flashdata('success', 'All feedbacks from the slected users will be hidden for you.');        
		redirect($referrer);		
	}
	
	//display dashboard
    public function dashboard($country = '') {
		$this->data['module_name'] = 'User';
        $this->data['section_title'] = 'Dashboard';
		
					
		$country = $this->user_country;		 
		
		$this->data['notification_count'] = $this->common->get_unread_notification_count($this->user['id']);
		// Trends
		$this->data['trends'] = $this->common->getTrends($country);
		
		// What to Follow
		$this->data['to_follow'] = $this->common->whatToFollow($this->user['id'], $country);
		
		// Get all feedbacks
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
		
		if(!empty($country)) {
			$contition_array = array('replied_to' => NULL, 'feedback.deleted' => 0, 'feedback.status' => 1, 'feedback.country' => $country);
		} else {
			$contition_array = array('replied_to' => NULL, 'feedback.deleted' => 0, 'feedback.status' => 1);
		}		
		
		$data = 'feedback_id, feedback.title_id, title, users.id as user_id, name, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video,feedback_pdf, replied_to, location, feedback.datetime as time';
		$where_not_in=array();
		ini_set('display_errors',1);
		error_reporting(E_ALL);
		$hidden_titles=$this->common->get_hidden_titles($this->user['id']);
		$hidden_users=$this->common->get_hidden_users($this->user['id']);
		
		if(!empty($hidden_titles)){
			$where_not_in['feedback.title_id']=$hidden_titles;
		}
		if(!empty($hidden_users)){
			$where_not_in['feedback.user_id']=$hidden_users;
		}
		
		if (!empty($this->input->get("page"))) {
			$page = ceil($this->input->get("page") - 1);
			$start = ceil($page * $this->perPage);
			
			$feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $this->perPage, $start, $join_str, $group_by = '');
			
			if(count($feedback) > 0) {
				// Get Likes, Followings and Other details
				$result = $this->common->getFeedbacks($feedback, $this->user['id']);
				
				// Append Ad Banners
				$return_array = $this->common->adBanners($result, $country, 'home', $this->input->get("page"));
				
				$this->data['feedbacks'] = $return_array;
			} else {
				$this->data['feedbacks'] = array();
				$this->data['no_record_found'] = $this->lang->line('no_results');
			}
			
			$response = $this->load->view('post/ajax', $this->data);
			echo json_encode($response);
		} else {
			$feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $this->perPage, 0, $join_str, $group_by = '',$where_not_in);
			
			if(count($feedback) > 0) {
				// Get Likes, Followings and Other details
				$result = $this->common->getFeedbacks($feedback, $this->user['id']);
				
				// Append Ad Banners
				$return_array = $this->common->adBanners($result, $country, 'home');
				
				$this->data['feedbacks'] = $return_array;
			} else {
				$this->data['feedbacks'] = array();
				$this->data['no_record_found'] = $this->lang->line('no_results');
			}
		
			/* Load Template */
			$this->template->front_render('user/dashboard', $this->data);
		}			
    }
	public function feedbacks($country = '') {
		$this->data['module_name'] = 'User';
        $this->data['section_title'] = 'Feedbacks';
		
					
		$country = $this->user_country;		 
		
		$this->data['notification_count'] = $this->common->get_unread_notification_count($this->user['id']);
		// Trends
		$this->data['trends'] = $this->common->getTrends($country);
		
		// What to Follow
		$this->data['to_follow'] = $this->common->whatToFollow($this->user['id'], $country);
		
		// Get all feedbacks
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
		
		
		$contition_array = array('replied_to' => NULL, 'feedback.deleted' => 0, 'feedback.status' => 1,'feedback.user_id'=>$this->user['id']);
		
		
		$data = 'feedback_id, feedback.title_id, title, users.id as user_id, name, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video,feedback_pdf, replied_to, location, feedback.datetime as time';
		
		if (!empty($this->input->get("page"))) {
			$page = ceil($this->input->get("page") - 1);
			$start = ceil($page * $this->perPage);
			
			$feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $this->perPage, $start, $join_str, $group_by = '');
			
			if(count($feedback) > 0) {
				// Get Likes, Followings and Other details
				$result = $this->common->getFeedbacks($feedback, $this->user['id']);
				
				// Append Ad Banners
				$return_array = $this->common->adBanners($result, $country, 'home', $this->input->get("page"));
				
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
				$return_array = $this->common->adBanners($result, $country, 'home');
				
				$this->data['feedbacks'] = $return_array;
			} else {
				$this->data['feedbacks'] = array();
				$this->data['no_record_found'] = $this->lang->line('no_results');
			}
		
			/* Load Template */
			$this->template->front_render('user/dashboard', $this->data);
		}			
    }
	public function followings($country = '') {
		$this->data['module_name'] = 'User';
        $this->data['section_title'] = 'Followings';	
					
		$country = $this->user_country;		 
		
		$this->data['notification_count'] = $this->common->get_unread_notification_count($this->user['id']);	
		$this->data['trends'] = $this->common->getTrends($country);	
		$this->data['to_follow'] = $this->common->whatToFollow($this->user['id'], $country);
		
		$join_str = array(
				array(
					'table' => 'titles',
					'join_table_id' => 'titles.title_id',
					'from_table_id' => 'followings.title_id',
					'join_type' => 'inner'
				),
				array(
					'table' => 'users',
					'join_table_id' => 'users.id',
					'from_table_id' => 'titles.user_id',
					'join_type' => 'left'
				)
				
		);
	
		$contition_array = array('followings.user_id' => $this->user['id'], 'titles.deleted' => 0);			
		
		
		$data = 'followings.follow_id, followings.title_id, title, users.id as user_id, users.name, users.photo, users.country, followings.datetime as time';
		
		$feedback = $this->common->select_data_by_condition('followings', $contition_array, $data, $sortby = 'followings.datetime', $orderby = 'DESC', '', '', $join_str, $group_by = '');
		//echo $this->db->last_query();
		if(!empty($feedback)) {
				$return_array = array();				
				foreach ($feedback as $item) {
					$return = array();
					$return['id'] = $item['follow_id'];
					$return['title_id'] = $item['title_id'];                
					$return['title'] = $item['title'];
					$return['country'] = $item['country'];
					// Get followers for this title
					$contition_array_fo = array('title_id' => $item['title_id']);
					$followings = $this->common->select_data_by_condition('followings', $contition_array_fo, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
					
					$return['followers'] = "";
					
					if(count($followings) > 1000) {
						$return['followers'] = (count($followings)/1000)."k";
					} else {
						$return['followers'] = count($followings);
					}					
				
					
					// Check If user followed this title
					$contition_array_ti = array('title_id' => $item['title_id'], 'user_id' => $this->user['id']);
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
					$return['time'] = $this->common->timeAgo($item['time']);	
					array_push($return_array, $return);
				}
				
				$this->data['followings'] = $return_array;
			} else {
				$this->data['followings'] = array();
				$this->data['no_record_found'] = $this->lang->line('no_record_found');
		}
		
		$this->template->front_render('user/followings-list', $this->data);
					
    }
	public function profile() {
			
		// Check post and save data
        if ($this->input->is_ajax_request() && $this->input->post('btn_save')) {			
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
//			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
			$this->form_validation->set_rules('country', 'Country', 'trim|required');
			
			if ($this->form_validation->run() == FALSE) {
				echo json_encode(array('error' => validation_errors(), 'status' => 0));
                die();
			}
			
			$gender = $this->input->post('gender');
			$searchable = $this->input->post('searchable');
			$name = $this->input->post('name');
//			$email = $this->input->post('email');
			$country = $this->input->post('country');
			$dob = $this->input->post('dob');
			
			$update_data = array();
			
			if ($gender != '') {
                $update_data['gender'] = $gender;
            }
			if (!empty($searchable)) {
                $update_data['searchable'] = 1;
            }else{
				$update_data['searchable'] = 0;
			}
            if ($name != '') {
                $update_data['name'] = $name;
            }
            /*if ($email != '') {
                $update_data['email'] = $email;
            }*/
            if ($country != '') {
                $update_data['country'] = $country;
            }
            if ($dob != '') {
                $update_data['dob'] = $dob;
            }
            
			// Image Upload Starts
            if (isset($_FILES['photo']['name']) && $_FILES['photo']['name'] != '') {
                $config['upload_path'] = $this->config->item('user_main_upload_path');
                $config['thumb_upload_path'] = $this->config->item('user_thumb_upload_path');
                $config['allowed_types'] = 'jpg|png|jpeg|gif';
                $config['file_name'] = time();

                $this->load->library('upload');
                $this->upload->initialize($config);
                
                //Uploading Image
                $this->upload->do_upload('photo');
                
                //Getting Uploaded Image File Data
                $imgdata = $this->upload->data();
                $imgerror = $this->upload->display_errors();
                
                if ($imgerror == '') {
                    
                    //Configuring Thumbnail 
                    $config_thumb['image_library'] = 'gd2';
                    $config_thumb['source_image'] = $config['upload_path'] . $imgdata['file_name'];
                    $config_thumb['new_image'] = $config['thumb_upload_path'] . $imgdata['file_name'];
                    $config_thumb['create_thumb'] = TRUE;
                    $config_thumb['maintain_ratio'] = FALSE;
                    $config_thumb['thumb_marker'] = '';
                    $config_thumb['width'] = $this->config->item('user_thumb_width');
                    $config_thumb['height'] = $this->config->item('user_thumb_height');

                    //Loading Image Library
                    $this->load->library('image_lib', $config_thumb);
                    $dataimage = $imgdata['file_name'];
                    
                    //Creating Thumbnail
                    $this->image_lib->resize();
                    $thumberror = $this->image_lib->display_errors();
                    
                    // AWS S3 Upload
                    $thumb_file_path = str_replace("main", "thumbs", $imgdata['file_path']);
                    $thumb_file_name = $config['thumb_upload_path'] . $imgdata['raw_name'].$imgdata['file_ext'];
                    
                    $this->s3->putObjectFile($imgdata['full_path'], S3_BUCKET, $config_thumb['source_image'], S3::ACL_PUBLIC_READ);
                    $this->s3->putObjectFile($thumb_file_path.$dataimage, S3_BUCKET, $thumb_file_name, S3::ACL_PUBLIC_READ);

                    // Remove File from Local Storage
                    unlink($config_thumb['source_image']);
                    unlink($thumb_file_name);
                } else {
                    $thumberror = '';
                }

                if ($imgerror != '' || $thumberror != '') {
                    $error[0] = $imgerror;
                    $error[1] = $thumberror;
                } else {
                    $main_old_file = $this->config->item('user_main_upload_path') . $this->user['photo'];
                    $thumb_old_file = $this->config->item('user_thumb_upload_path') . $this->user['photo'];

                    $error = array();
                }

                if ($error) {
                    echo json_encode(array('message' => $error[0], 'status' => 0));
                    die();
                }
				
                $update_data['photo'] = $dataimage;
				
				$this->user['photo'] = $dataimage;  
            } // Image Upload Ends
			
			if(!empty($update_data)) {
				// Update Session Data
				$this->user['name'] = $name;
				$this->user['country'] = $country;
				$this->user['gender'] = $gender;
				$this->user['dob'] = $dob;
				
				$this->session->set_userdata('mec_user', $this->user);
                $this->common->update_data($update_data, 'users', 'id', $this->user['id']);
				
				echo json_encode(array('message' => $this->lang->line('success_msg_profile_saved'), 'status' => 1));
                die();
			} else {
				echo json_encode(array('message' => $this->lang->line('success_no_profile_update'), 'status' => 0));
                die();
			}
		}
		
		// Get User Information
		$contition_array = array('id' => $this->user['id']);
		$user_result = $this->common->select_data_by_condition('users', $contition_array, $data = '*', $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str = array());
		
		$this->data['user_data'] = $user_result[0];
		$this->data['country_list'] = $this->common->select_data_by_condition('countries', $contition_array = array(), '*', $short_by = 'country_name', $order_by = 'ASC', $limit = '', $offset = '');
		
		$this->data['module_name'] = 'User';
        $this->data['section_title'] = $user_result[0]['name'];
		
		/* Load Template */
		$this->template->front_render('user/profile', $this->data);
	}
	
	public function profile_feedbacks() {
		if ($this->input->is_ajax_request()) {
			// Get All Feedbacks by User
			$join_str = array(
				array(
					'table' => 'users',
					'join_table_id' => 'users.id',
					'from_table_id' => 'feedback.user_id',
					'join_type' => 'inner'
				),
				array(
					'table' => 'titles',
					'join_table_id' => 'titles.title_id',
					'from_table_id' => 'feedback.title_id',
					'join_type' => 'inner'
				)
			);
			
			$contition_array = array('feedback.user_id' => $this->user['id'], 'feedback.replied_to' => NULL, 'feedback.deleted' => 0, 'feedback.status' => 1);
			$data = 'feedback_id, feedback.title_id, title, name, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video, feedback_pdf,feedback_pdf_name, replied_to, location, feedback.datetime as time';
			
			$feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $limit = '', $offset = '', $join_str, $group_by = '');
			if(!empty($feedback)) {
				$return_array = array();
				
				foreach ($feedback as $item) {
					$return = array();
					$return['id'] = $item['feedback_id'];
					$return['title_id'] = $item['title_id'];                
					$return['title'] = $item['title'];
					$return['feedback_pdf_name'] = $item['feedback_pdf_name'];
					$return['feedback_pdf'] = $item['feedback_pdf'];	
					$return['feedback_images'] = array();
					// Get likes for this feedback
					$contition_array_lk = array('feedback_id' => $item['feedback_id']);
					$flikes = $this->common->select_data_by_condition('feedback_likes', $contition_array_lk, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
					
					$return['likes'] = "";
					$feedback_images = $this->common->select_data_by_id('feedback_images', 'feedback_id', $item['feedback_id'], '*');	
					
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
	
					// Check If user reported this feedback
					$contition_array_rs = array('feedback_id' => $item['feedback_id'], 'user_id' => $this->user['id']);
					$spam = $this->common->select_data_by_condition('spam', $contition_array_rs, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
								
					if(count($spam) > 0) {
						$return['report_spam'] = TRUE;
					} else {
						$return['report_spam'] = FALSE;
					}
					
					// Check If user liked this feedback
					$contition_array_li = array('feedback_id' => $item['feedback_id'], 'user_id' => $this->user['id']);
					$likes = $this->common->select_data_by_condition('feedback_likes', $contition_array_li, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
								
					if(count($likes) > 0) {
						$return['is_liked'] = TRUE;
					} else {
						$return['is_liked'] = FALSE;
					}
					
					// Check If user followed this title
					$contition_array_ti = array('title_id' => $item['title_id'], 'user_id' => $this->user['id']);
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
					
					if($item['feedback_img'] !== "") {
						$return['feedback_img'] = S3_CDN . 'uploads/feedback/main/' . $item['feedback_img'];
					} else {
						$return['feedback_img'] = "";
					}
	
					if($item['feedback_thumb'] !== "") {
						$return['feedback_thumb'] = S3_CDN . 'uploads/feedback/thumbs/' . $item['feedback_thumb'];
					} else {
						$return['feedback_thumb'] = "";
					}
					
					if($item['feedback_video'] !== "") {
						$return['feedback_video'] = S3_CDN . 'uploads/feedback/video/' . $item['feedback_video'];
						//$return['feedback_thumb'] = S3_CDN . 'uploads/feedback/thumbs/video_thumbnail.png';
					} else {
						$return['feedback_video'] = "";
					}
					if($item['feedback_pdf'] !== "") {
						$return['feedback_pdf'] = S3_CDN . 'uploads/feedback/pdf/' . $item['feedback_pdf'];						
					} else {
						$return['feedback_pdf'] = "";
					}
					if(count($feedback_images)>0){
						foreach($feedback_images as $img){
							$imagearr=array();
							$imagearr['feedback_img'] = S3_CDN . 'uploads/feedback/main/' . $img['feedback_img'];
							$imagearr['feedback_thumb'] = S3_CDN . 'uploads/feedback/thumbs/' . $img['feedback_thumb'];
							$return['feedback_images'][]=$imagearr;
						}
					}else{
						$return['feedback_images']=array();
						
					}
					$return['feedback'] = $item['feedback_cont'];
					$return['location'] = $item['location'];                
					$return['time'] = $this->common->timeAgo($item['time']);
	
					array_push($return_array, $return);
					
					$this->data['feedbacks'] = $return_array;
				}
			} else {
				$this->data['feedbacks'] = array();
				$this->data['no_record_found'] = $this->lang->line('no_record_found');
			}	
			
			$this->data['module_name'] = 'User';
			$this->data['section_title'] = 'Feedbacks';
			
			/* Load Template */
			$response = $this->load->view('user/feedbacks', $this->data);
			echo json_encode($response);
		}
	}
	
	public function profile_followings() {
		if ($this->input->is_ajax_request()) {
			// Get Followings for User
			$join_str = array(
				array(
					'table' => 'users',
					'join_table_id' => 'users.id',
					'from_table_id' => 'feedback.user_id',
					'join_type' => 'inner'
				),
				array(
					'table' => 'titles',
					'join_table_id' => 'titles.title_id',
					'from_table_id' => 'feedback.title_id',
					'join_type' => 'inner'
				),
				array(
					'table' => 'followings',
					'join_table_id' => 'followings.title_id',
					'from_table_id' => 'feedback.title_id',
					'join_type' => 'inner'
				)
			);
	
			$contition_array = array('followings.user_id' => $this->user['id'], 'feedback.replied_to' => NULL, 'feedback.deleted' => 0, 'feedback.status' => 1);
			$data = 'feedback_id, feedback.title_id, title, name, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video,feedback_pdf,feedback_pdf_name, replied_to, location, feedback.datetime as time';
			
			$feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $limit = '', $offset = '', $join_str, $group_by = '');
			if(!empty($feedback)) {
				$return_array = array();
				
				foreach ($feedback as $item) {
					$return = array();
					$return['id'] = $item['feedback_id'];
					$return['title_id'] = $item['title_id'];                
					$return['title'] = $item['title'];
					$return['feedback_pdf_name'] = $item['feedback_pdf_name'];
					$return['feedback_pdf'] = $item['feedback_pdf'];	
					$return['feedback_images'] = array();
					
					// Get likes for this feedback
					$contition_array_lk = array('feedback_id' => $item['feedback_id']);
					$flikes = $this->common->select_data_by_condition('feedback_likes', $contition_array_lk, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
					
					$return['likes'] = "";
					$feedback_images = $this->common->select_data_by_id('feedback_images', 'feedback_id', $item['feedback_id'], '*');	
					
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
					$contition_array_li = array('feedback_id' => $item['feedback_id'], 'user_id' => $this->user['id']);
					$likes = $this->common->select_data_by_condition('feedback_likes', $contition_array_li, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
								
					if(count($likes) > 0) {
						$return['is_liked'] = TRUE;
					} else {
						$return['is_liked'] = FALSE;
					}
					
					// Check If user followed this title
					$contition_array_ti = array('title_id' => $item['title_id'], 'user_id' => $this->user['id']);
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
					
					if($item['feedback_img'] !== "") {
						$return['feedback_img'] = S3_CDN . 'uploads/feedback/main/' . $item['feedback_img'];
					} else {
						$return['feedback_img'] = "";
					}
	
					if($item['feedback_thumb'] !== "") {
						$return['feedback_thumb'] = S3_CDN . 'uploads/feedback/thumbs/' . $item['feedback_thumb'];
					} else {
						$return['feedback_thumb'] = "";
					}
					
					if($item['feedback_video'] !== "") {
						$return['feedback_video'] = S3_CDN . 'uploads/feedback/video/' . $item['feedback_video'];
						//$return['feedback_thumb'] = S3_CDN . 'uploads/feedback/thumbs/video_thumbnail.png';
					} else {
						$return['feedback_video'] = "";
					}
					if($item['feedback_pdf'] !== "") {
						$return['feedback_pdf'] = S3_CDN . 'uploads/feedback/pdf/' . $item['feedback_pdf'];						
					} else {
						$return['feedback_pdf'] = "";
					}
					if(count($feedback_images)>0){
						foreach($feedback_images as $img){
							$imagearr=array();
							$imagearr['feedback_img'] = S3_CDN . 'uploads/feedback/main/' . $img['feedback_img'];
							$imagearr['feedback_thumb'] = S3_CDN . 'uploads/feedback/thumbs/' . $img['feedback_thumb'];
							$return['feedback_images'][]=$imagearr;
						}
					}
					$return['feedback'] = $item['feedback_cont'];
					$return['location'] = $item['location'];
					$return['time'] = $this->common->timeAgo($item['time']);
	
					array_push($return_array, $return);
				}
				
				$this->data['followings'] = $return_array;
			} else {
				$this->data['followings'] = array();
				$this->data['no_record_found'] = $this->lang->line('no_record_found');
			}
			
			$this->data['module_name'] = 'User';
			$this->data['section_title'] = 'Followings';
			
			/* Load Template */
			$response = $this->load->view('user/followings', $this->data);
			echo json_encode($response);
		}
	}
	
	public function settings($active_tab=1) {
		$this->data['module_name'] = 'User';
        $this->data['section_title'] = 'Settings';
		
		// Get Languages
		$contition_array = array('lang_status' => 1);
        $this->data['languages'] = $this->common->select_data_by_condition('languages', $contition_array, $data = 'lang_id, lang_code, lang_name');
		
		
		$groups = $this->common->get_user_groups($this->user['id']);
		$this->data['groups']=$groups;	
		// Get Settings
		$condition_array = array('page_id' => 'terms_cond', 'pages.lang_code' => $this->user['language']);
        $this->data['terms'] = $this->common->select_data_by_condition('pages', $condition_array, 'id, name, description');
		$this->data['active_tab']=$active_tab;	
		$this->data['search_text']='';		
		if($this->input->post('search')){
			$search_text=$this->input->post('s');
			$this->data['search_text']=$search_text;
			$users=$this->encrypted_model->search_users($search_text,$this->user['id'],'');			
			$this->data['users']=$users;
		}
		if($this->input->post('block_users')){			
			$users=$this->input->post('users');
			if(!empty($users) && is_array($users)){
				foreach($users as $u){
					$this->common->block_user($this->user['id'],$u);
				}
			}
		}
		$blocked = $this->common->get_blocked_users($this->user['id']);
		$this->data['blocked_users']=$blocked;	
		/* Load Template */
		$this->template->front_render('user/settings', $this->data);
	}
	
	// Set Language
    function set_language() {
        $lang_id = $this->input->post('lang_id');

        if ($lang_id == '') {
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please select your language', 'STATUS' => 0));
            die();
        }

        $data = array('lang_id' => $lang_id);
        $update_settings = $this->common->update_data($data, 'users', 'id', $this->user['id']);

        if($update_settings) {
			// Update Session Data
			$this->user['lang_id'] = $lang_id;
			
			$contition_array = array('lang_id' => $lang_id);
			$language = $this->common->select_data_by_condition('languages', $contition_array, $data = 'lang_code');
			$this->user['language'] = $language[0]['lang_code'];
			
			$this->session->set_userdata('mec_user', $this->user);
			
			echo json_encode(array('message' => $this->lang->line('success_language_set'), 'status' => 1));
            die();
        } else {
            echo json_encode(array('message' => $this->lang->line('error_something_wrong'), 'status' => 0));
            die();
        }
    }
	
	//Change Password
    function change_password() {
        $old_pass = $this->input->post('old_pass');
        $new_pass = $this->input->post('new_pass');

        $error = '';
        if ($old_pass == '') {
            $error = 1;
            echo json_encode(array('message' => $this->lang->line('error_msg_old_pass_message'), 'status' => 0));
            die();
        }
        if ($new_pass == '') {
            $error = 1;
            echo json_encode(array('message' => $this->lang->line('error_msg_new_pass_message'), 'status' => 0));
            die();
        }
        if ($error == 1) {
            echo json_encode(array('message' => $this->lang->line('error_msg_details'), 'status' => 0));
            die();
        } else {

            // check old password is correct or not
            $check_old_pass = $this->common->select_data_by_id('users', 'id', $this->user['id'], $data = 'password', $join_str = array());

            if (count($check_old_pass) > 0) {
                $old_db_pass = $check_old_pass[0]['password'];

                if (md5($old_pass) != $old_db_pass) {
                    echo json_encode(array('message' => $this->lang->line('error_msg_correct_old_password'), 'status' => 0));
                    die();
                } else {
                    $data = array('password' => md5($new_pass));
                    $update = $this->common->update_data($data, 'users', 'id', $this->user['id']);
					
                    echo json_encode(array('message' => $this->lang->line('success_change_password'), 'status' => 1));
                    die();
                }
            } else {
                echo json_encode(array('message' => $this->lang->line('no_record_found'), 'status' => 0));
                die();
            }
        }
    }
	
	// Contact Us
    function contact_us() {
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $message = $this->input->post('message');

        if ($name == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_name'), 'STATUS' => 0));
            exit();
        }
        if ($email == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_email'), 'STATUS' => 0));
            exit();
        }
		if ($message == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_message'), 'STATUS' => 0));
            exit();
        } else {

            $insert_array['name'] = $name;
            $insert_array['email'] = trim($email);
            $insert_array['message'] = $message;
            $insert_array['posted_on'] = date('Y-m-d h:i:s');

            $insert_result = $this->common->insert_data_getid($insert_array, $tablename = 'contactus');
            $condition_array = array('emailid' => '3');
            $emailformat = $this->common->select_data_by_condition('emails', $condition_array, '*');

            $mail_body = $emailformat[0]['varmailformat'];

            $phone = 'N/A';
            $subject = 'You\'ve got new enquiry!';

            $mail_body = html_entity_decode(str_replace("%name%", ucfirst($name), str_replace("%user_email%", $email, str_replace("%phone%", $phone, str_replace("%subject%", $subject, str_replace("%message%", $message, stripslashes($mail_body)))))));

            // Find where to send new enquiry
            $settings = $this->common->getSettings('contact_mail');

            $send_mail = $this->common->sendMail($settings[0]['setting_value'], '', $emailformat[0]['varsubject'], $mail_body);

            if ($send_mail) {
                echo json_encode(array('message' => $this->lang->line('success_msg_sent_message'), 'status' => 1));
                exit();
            } else {
                echo json_encode(array('message' => $this->lang->line('error_msg_not_able_to_send_msg'), 'status' => 0));
                exit();
            }
        }
    }
	
	public function notifications() {
		$this->data['module_name'] = 'User';
        $this->data['section_title'] = 'Notifications';
		
		$n_array = array();
		
		/* Titles I Follow */
		$n_follow = $this->common->get_notification($this->user['id'], 2);
		if(count($n_follow) > 0) {
			$n_array = array_merge($n_array, $n_follow);
		}

		/* Likes on the Feedbacks */
		$n_likes = $this->common->get_notification($this->user['id'], 3);
		if(count($n_likes) > 0) {
			$n_array = array_merge($n_array, $n_likes);
		}

		/* Feedbacks on my Titles */
		$n_reply = $this->common->get_notification($this->user['id'], 4);		
		if(count($n_reply) > 0) {
			$n_array = array_merge($n_array, $n_reply);
		}
		
		$n_encrypted = $this->common->get_notification_enc($this->user['id'], 6);		
		if(count($n_encrypted) > 0) {
			$n_array = array_merge($n_array, $n_encrypted);
		}
		/* Feedbacks on my Titles */
		$n_encrypted = $this->common->get_notification_enc($this->user['id'], 5);		
		if(count($n_encrypted) > 0) {
			$n_array = array_merge($n_array, $n_encrypted);
		}
		$n_encrypted = $this->common->get_notification_enc($this->user['id'], 8);		
		if(count($n_encrypted) > 0) {
			$n_array = array_merge($n_array, $n_encrypted);
		}
		
		$n_documents = $this->common->get_notification_enc($this->user['id'], 7);		
		if(count($n_documents) > 0) {
			$n_array = array_merge($n_array, $n_documents);
		}
		
		// Sort array by id
		usort($n_array, function($a, $b) {
			return $b['id'] - $a['id'];
		});
		
		if(!empty($n_array)) {
			$this->data['notifications'] = $n_array;
		} else {
			$this->data['notifications'] = array();
			$this->data['no_record_found'] = $this->lang->line('no_record_found');
		}
		$this->common->update_notification_status($this->user['id']);
		/* Load Template */
		$this->template->front_render('user/notifications', $this->data);
	}
	
    //logout user
    public function logout() {
		
		// Remove local Facebook session
		//$this->facebook->destroy_session();		
        if ($this->session->userdata('mec_user')) {
            $this->session->unset_userdata('mec_user');
			$this->session->unset_userdata('access_token');
			$this->session->unset_userdata('access_token_secret');
			$this->session->unset_userdata('request_token');
			$this->session->unset_userdata('request_token_secret');
			$this->session->unset_userdata('accessToken');
			$this->load->library('facebook');
			$this->facebook->logout();
        }
        redirect();
    }
	public function create_user_group(){
		if ($this->input->is_ajax_request()) {
			$name=$this->input->post('name');
			$users=$this->input->post('users');
			$group_id=$this->common->update_user_group($this->user['id'],$name,$users);						
			//echo $this->db->last_query();	
			$group = $this->common->get_user_group($this->user['id'],$group_id);
			echo json_encode($group);
		}else{
			$response="Invalid";
			echo json_encode($response);
		}
	}
	public function add_user_group(){	
		$name=$this->input->post('name');
		$users=$this->input->post('users');
		$group_id=$this->common->update_user_group($this->user['id'],$name,$users);						
		//echo $this->db->last_query();	
		$group = $this->common->get_user_group($this->user['id'],$group_id);
		redirect('user/settings/5');
		
	}
	public function group_users(){
		if ($this->input->is_ajax_request()) {
			$group_id=$this->input->post('id');			
			$groups = $this->common->get_group_users($group_id);
			echo json_encode($groups);
		}else{
			$response="Invalid";
			echo json_encode($response);
		}
	}
	public function search_users() {		
		if ($this->input->is_ajax_request()) {
			$s=$this->input->post('s');
			$title_id=$this->input->post('title_id');
			
			$text=$s['term'];			
			$users=$this->encrypted_model->search_users($text,$this->user['id'],$title_id);		
			$this->data['users'] = $users;		
			$this->data['module_name'] = 'User';
			$this->data['section_title'] = 'Followings';			
			//$response = $this->load->view('user/select_list', $this->data);
		
		$json=array();
		$json['users']=$users;
		$json['req']=$_REQUEST;	
		echo json_encode($json);
		}else{
			$response="Invalid";
			echo json_encode($response);			
		}
	}
	public function notification_count(){
		$notification_count = $this->common->get_unread_notification_count($this->user['id']);	
		$friend_requests_count = $this->common->user_friend_requests_count($this->user['id']);
		$friends_count = $this->common->user_friends_count($this->user['id'],1);
		$followings_count = $this->common->user_following_count($this->user['id']);
		$feedbacks_count = $this->common->user_feedbacks_count($this->user['id']);		
		$json=array();
		$json['unread_count']=$notification_count;
		$json['friend_requests_count']=$friend_requests_count;	
		$json['friends_count']=$friends_count;
		$json['feedbacks_count']=$feedbacks_count;	
		$json['followings_count']=$followings_count;	
		echo json_encode($json);
	}
}
