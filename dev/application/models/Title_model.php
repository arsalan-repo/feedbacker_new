<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Title_model extends CI_Model{
	function __construct() {
        parent::__construct();             
    }
	function send_invitaion($title_id,$sender_id,$emails){
		$title=$this->get_title($title_id,$sender_id);
		$sender=$this->get_user($sender_id);
		$subject='Invitation to join "'.$title['title'].'" at feedbacker.me';
		
		$email_body ='<div>Hi,
		<p>'.$sender['name'].' has invited to join the Encrypted Title <strong>'.$title['title'].'</strong> at <a href="'.site_url().'">feedbacker.me</a></p>
		<p><a href="'.site_url("encrypted/join_title/$title_id/$sender_id").'">Click Here To Join Now</a></p>
		</div>';
		$this->load->library('email');
		$this->email->set_newline("\r\n");
		$this->email->from('info@machkit.com', 'Feedbacker');
		$this->email->to($emails);
		$this->email->subject($subject);
		$this->email->message($email_body);
		$this->email->send();
	}
	function get_user($user_id){
		$this->db->select("id, name, photo");	
		$this->db->where('id', $user_id);		
		$query = $this->db->get('users');		
		return $query->row_array();
	}
	function search_users($text,$user_id='',$title_id=''){		
		$this->db->select("id, name as text, photo");	
		$this->db->where('searchable', 1);
		if(!empty($user_id))
			$this->db->where('id !=', $user_id);
		if(!empty($title_id))
			$this->db->where("id NOT IN (SELECT user_id FROM db_encrypted_titles_users WHERE db_encrypted_titles_users.title_id='".$title_id."')");
		$this->db->group_start();
		$this->db->like('name', $text);		
		$this->db->or_like('email', $text);
		 $this->db->group_end();
		$this->db->from("users");
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_array();
	}
	function is_title_linked_user($title_id,$user_id){
		$this->db->where('user_id',$user_id);
		$this->db->where('title_id',$title_id);
		$query = $this->db->get('encrypted_titles_users');
		if ($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	function is_title_owner($title_id,$user_id){
		$this->db->where('user_id',$user_id);
		$this->db->where('title_id',$title_id);
		$query = $this->db->get('encrypted_titles');
		if ($query->num_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}
	function remove_linked_user($title_id,$user_id){
		$this->db->delete('encrypted_titles_users', array('user_id' => $user_id,'title_id'=>$title_id));
	}
	function linked_users_count($title_id){		
		$this->db->where('title_id',$title_id);		
		$count=$this->db->count_all_results('encrypted_titles_users');		
		return $count;
	}
	function get_title_feedbacks_count($title_id){
		$this->db->where('title_id',$title_id);		
		$count=$this->db->count_all_results('encrypted_titles_feedbacks');		
		return $count;
	}
	function linked_users($title_id,$user_id){
		$this->db->select("u.id, u.name, u.photo, u.country, tu.sender_id, tu.datetime");
		$this->db->where('tu.title_id',$title_id);	
		$this->db->from('encrypted_titles_users as tu');
		$this->db->join('users as u','u.id = tu.user_id');
		$this->db->order_by("u.name", "ASC");
		$result = $this->db->get();
		$users=array();
		foreach ($result->result_array() as $user){
			$user['time'] = $this->timeAgo($user['datetime']);
			$users[]=$user;
		}
		return $users;
	}
	public function get_feedback_images($feedback_id){
		$this->db->select("image_id, feedback_img, feedback_thumb");
		$this->db->where('feedback_id',$feedback_id);	
		$this->db->from('encrypted_feedback_images');
		$query = $this->db->get();
		$rows=array();
		foreach ($query->result_array() as $row){
			$item=array('image_id'=>$row['image_id']);	
			$item['photo'] = S3_CDN . 'uploads/feedback/main/' . $row['feedback_img'];
			$item['thumb'] = S3_CDN . 'uploads/feedback/thumbs/' . $row['feedback_thumb'];			
			$rows[]=$item;
		}
		return $rows;
	}
	public function get_feedback_files($feedback_id){
		$this->db->select("file_id, file_name, file_url");
		$this->db->where('feedback_id',$feedback_id);	
		$this->db->from('encrypted_feedback_files');
		$query = $this->db->get();
		$rows=array();
		foreach ($query->result_array() as $row){
			$item=array('file_id'=>$row['file_id'],'name'=>$row['file_name']);	
			$item['url'] = S3_CDN . 'uploads/feedback/files/' . $row['file_url'];				
			$rows[]=$item;
		}
		return $rows;
	}
	
	function get_title_feedbacks($title_id,$user_id='',$start=0,$limit=20){
		$this->db->select("f.*,u.name, u.photo, u.country");
		$this->db->where('f.title_id',$title_id);	
		$this->db->from('encrypted_titles_feedbacks as f');
		$this->db->join('users as u','u.id = f.user_id');
		$this->db->order_by("f.feedback_id", "DESC");
		$query = $this->db->get();
		$rows=array();
		foreach ($query->result_array() as $row){
			if(isset($row['photo'])) {
				$row['user_avatar'] = S3_CDN . 'uploads/user/thumbs/' . $row['photo'];
			} else {
				$row['user_avatar'] = ASSETS_URL . 'images/user-avatar.png';
			}	
			$row['time'] = $this->timeAgo($row['datetime']);
			$row['images'] = $this->get_feedback_images($row['feedback_id']);
			$row['files'] = $this->get_feedback_files($row['feedback_id']);
			
			if(!empty($row['feedback_video']))
				$row['feedback_video'] = S3_CDN . 'uploads/feedback/video/' . $row['feedback_video'];
			
			if(!empty($row['feedback_thumb'])) {
				$row['feedback_thumb'] = S3_CDN . 'uploads/feedback/thumbs/' . $row['feedback_thumb'];
			} 
			if(!empty($row['feedback_img'])) {
				$row['feedback_img'] = S3_CDN . 'uploads/feedback/main/' . $row['feedback_img'];
			} else {
				$row['feedback_img'] = "";
			}
			if(!empty($row['feedback_pdf'])) {
				$row['feedback_pdf'] = S3_CDN . 'uploads/feedback/pdf/' . $row['feedback_pdf'];
			} else {
				$row['feedback_pdf'] = "";
			}
			$rows[]=$row;
		}
		return $rows;
	}
	function get_private_titles($user_id,$start=0,$limit=20,$search=''){
		$this->db->select('t.*,u.name, u.photo, u.country'); 
		$this->db->from('private_titles as t');
		$this->db->join('users as u','u.id=t.user_id','INNER');
		$this->db->group_start();
		$this->db->where('t.title_id IN (SELECT title_id FROM db_encrypted_titles_users WHERE db_encrypted_titles_users.user_id='.$user_id.')');
		$this->db->or_where("t.user_id",$user_id);
		$this->db->group_end();
		if(!empty($search)){
			$this->db->group_start();
			$this->db->like('t.title', $search);
			$this->db->or_where("t.title_id IN (SELECT title_id FROM db_encrypted_titles_feedbacks WHERE db_encrypted_titles_feedbacks.feedback_cont LIKE '%".$search."%')");
			$this->db->group_end();
		}			
		$this->db->limit($start, $limit);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$titles=array();
		foreach ($query->result_array() as $title){
			$title['is_title_owner']=$this->is_title_owner($title['title_id'],$user_id);
			$title['is_title_linked_user']=$this->is_title_linked_user($title['title_id'],$user_id);
			$title['linked_users_count']=$this->linked_users_count($title['title_id']);
			$title['feedbacks_count']=$this->get_title_feedbacks_count($title['title_id']);			
			$title['time'] = $this->timeAgo($title['datetime']);
			if(isset($title['photo'])) {
				$title['user_avatar'] = S3_CDN . 'uploads/user/thumbs/' . $title['photo'];
			} else {
				$title['user_avatar'] = ASSETS_URL . 'images/user-avatar.png';
			}	
			$titles[]=$title;
		}
		return $titles;
	}
	function get_title_id_from_slug($slug){
		$this->db->select('title_id'); 		
		$this->db->where('slug', $slug);
		$this->db->from('encrypted_titles');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$title=$query->row_array();
			return $title['title_id'];
		}
		return 0;
	}
	function get_title_slug_from_id($id){
		$this->db->select('slug'); 		
		$this->db->where('title_id', $id);
		$this->db->from('encrypted_titles');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$title=$query->row_array();
			return $title['slug'];
		}
		return '';
	}
	function get_title($title_id,$user_id){
		$this->db->select('encrypted_titles.*, users.name, users.photo, users.country'); 
		$this->db->where('title_id', $title_id);		
		$this->db->from('encrypted_titles');
		$this->db->join('users', 'encrypted_titles.user_id = users.id');
        $query = $this->db->get();
		//echo $this->db->last_query();
		$title=array();
        if ($query->num_rows() > 0) {
            $title=$query->row_array();
        }
		$title['is_title_owner']=$this->is_title_owner($title_id,$user_id);
		$title['is_title_linked_user']=$this->is_title_linked_user($title_id,$user_id);
		$title['linked_users_count']=$this->linked_users_count($title_id);
		$title['feedbacks_count']=$this->get_title_feedbacks_count($title_id);	
		$title['likes'] = 0;
		$title['is_liked'] = false;		
		$title['time'] = $this->timeAgo($title['datetime']);
		return $title;
	}
	   function timeAgo($time_ago) {
        $time_ago = strtotime($time_ago);
        $cur_time   = time();
        $time_elapsed   = $cur_time - $time_ago;
        $seconds    = $time_elapsed ;
        $minutes    = round($time_elapsed / 60 );
        $hours      = round($time_elapsed / 3600);
        $days       = round($time_elapsed / 86400 );
        $weeks      = round($time_elapsed / 604800);
        $months     = round($time_elapsed / 2600640 );
        $years      = round($time_elapsed / 31207680 );
        // Seconds
        if($seconds <= 60){
            return "just now";
        }
        //Minutes
        else if($minutes <=60){
            if($minutes==1){
                return "1 min";
            }
            else{
                return "$minutes mins";
            }
        }
        //Hours
        else if($hours <=24){
            if($hours==1){
                return "an hour";
            }else{
                return "$hours hrs";
            }
        }
        //Days
        else if($days <= 7){
            if($days==1){
                return "yesterday";
            }else{
                return "$days days";
            }
        }
        //Weeks
        else if($weeks <= 4.3){
            if($weeks==1){
                return "a week";
            }else{
                return "$weeks weeks";
            }
        }
        //Months
        else if($months <=12){
            if($months==1){
                return "a month";
            }else{
                return "$months months";
            }
        }
        //Years
        else{
            if($years==1){
                return "one year";
            }else{
                return "$years years";
            }
        }
    }
}