<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Encrypted_model extends CI_Model{
	var $image_types=array('png','jpg','jpeg','gif');
	function __construct() {
        parent::__construct();             
    }
	
	function send_invitaion($title_id,$sender_id,$emails){
		$title=$this->get_title($title_id,$sender_id);
		$sender=$this->get_user($sender_id);
		
		foreach($emails as $email){
			$invitation_id=$this->add_title_invitation($title_id,$sender_id,$email);
			
			$this->load->library('email');
			$this->email->clear();
			$subject='Invitation to join "'.$title['title'].'" at feedbacker.me';	
			
			$this->email->set_newline("\r\n");
			$this->email->from('info@machkit.com', 'Feedbacker');
			$this->email->subject($subject);
			$this->email->to($email);
			$email_body ='<div>Hi,
		<p>'.$sender['name'].' has invited to join the Encrypted Title <strong>'.$title['title'].'</strong> at <a href="'.site_url().'">feedbacker.me</a></p>
		<p style="text-align:center;"><a style="background: #005db9;    color: #FFF;    border-radius: 20px;    padding: 10px 20px;
    text-decoration: none;" href="'.site_url("join/encrypted/$invitation_id").'">Click Here To Join Now</a></p>
		</div>';
			$this->email->message($email_body);
			$this->email->send();
			
		}
		
		
	}
	function get_user_by_email($email){
		$this->db->select("*");	
		$this->db->where('email', $email);		
		$query = $this->db->get('users');	
		if ($query->num_rows()>0){
			return $query->row_array();
		}			
		return array();
	}
	public function get_invitation($id){
		$this->db->select("*");	
		$this->db->where('invitation_id', $id);		
		$query = $this->db->get('encrypted_invitations');		
		return $query->row_array();
	}
	function add_title_invitation($title_id,$sender_id,$email){
			
		$this->db->where('sender_id', $sender_id);
		$this->db->where('title_id', $title_id);
		$this->db->where('email', $email);
		$this->db->from('encrypted_invitations');
		$query = $this->db->get();
		if ($query->num_rows()<1){
			$data = array(
				'title_id' => $title_id,
				'email' => $email,
				'sender_id' => $sender_id
			);
			$this->db->insert('encrypted_invitations', $data);
			return $this->db->insert_id();
		}else{
			$row=$query->row_array();
			return $row['invitation_id'];
			
		}
		return 0;
	}
	function update_title_views($title_id,$user_id){
		$this->db->select("id");	
		$this->db->where('user_id', $user_id);
		$this->db->where('title_id', $title_id);
		$this->db->from('encrypted_titles_views');
		$query = $this->db->get();
		if ($query->num_rows() <1){
			$data = array(
					'title_id' => $title_id,
					'user_id' => $user_id
			);
			$this->db->insert('encrypted_titles_views', $data);
		}
	}
	function get_user($user_id){
		$this->db->select("id, name, email, photo");	
		$this->db->where('id', $user_id);		
		$query = $this->db->get('users');		
		return $query->row_array();
	}
	function search_users($text,$user_id='',$title_id=''){		
		$this->db->select("id, name as text, photo");	
		$this->db->where('searchable', 1);
		$this->db->where('deleted', 0);
		if(!empty($user_id))
			$this->db->where('id !=', $user_id);
		if(!empty($title_id))
			$this->db->where("id NOT IN (SELECT user_id FROM db_encrypted_titles_users WHERE db_encrypted_titles_users.title_id='".$title_id."')");
		$this->db->where("id NOT IN (SELECT blocked_by FROM db_blocked WHERE db_blocked.user_id='".$user_id."')");		
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
	function delete_feedback($title_id,$feedback_id){
		/*$this->db->where('encrypted_feedback_files.feedback_id',$feedback_id);
		$this->db->where('encrypted_feedback_images.feedback_id',$feedback_id);
		$this->db->where('encrypted_titles_feedbacks.feedback_id',$feedback_id);
		$this->db->where('encrypted_titles_feedbacks.title_id',$title_id);
		$this->db->delete(array('encrypted_feedback_files','encrypted_feedback_images','encrypted_titles_feedbacks'));	*/	
		$this->db->delete('encrypted_titles_feedbacks', array('title_id' => $title_id,'feedback_id'=>$feedback_id));
	}
	
	function delete_encrypted_title($title_id,$user_id=''){
		$this->db->set('deleted', 1);
		$this->db->where('title_id', $title_id);
		if(!empty($user_id))
		$this->db->where('user_id', $user_id);
		$this->db->update('encrypted_titles'); 
	}
	function get_documents($id){
		$this->db->where('id',$id);	
		$query = $this->db->get('user_notifications');
		if ($query->num_rows() > 0){
			$notification=$query->row_array();
			$files=$this->get_feedback_files($notification['feedback_id']);
			return $files;
		}
		return array();
	}
	function share_feedback_to_users($user_id,$title_id,$feedback_id,$users){
		if(!empty($users) && !empty($feedback_id)){
			$this->db->where('feedback_id', $feedback_id);
			$query = $this->db->get('encrypted_titles_feedbacks');
			
			if($query->num_rows()>0){
				
				$feedback=$query->row_array();
				$sender=$this->get_user($user_id);
				$subject=$sender['name'].' shared documents with you at feedbacker.me';	
				$this->load->library('email');
				$files=$this->get_feedback_files($feedback_id);
				$files_html='';
				foreach($files as $file){
					$files_html.='<p><a href="'.$file['url'].'">'.$file['name'].'</a></p>';
				}
				foreach($users as $uid){
						$post=array('title_id'=>$title_id,'user_id'=>$uid,'guest_id'=>$user_id,'feedback_id'=>$feedback_id,'notification_id'=>7,'datetime'=>date('Y-m-d H:i:s'));
						$notification_id=$this->common->insert_data($post, $tablename = 'user_notifications');						
						$receiver=$this->get_user($uid);						
					$email_body ='<div>Hi,
					<p>'.$sender['name'].' has shared following documents with you at <a href="'.site_url().'">feedbacker.me</a></p>
					'.$files_html.'
					<p style="text-align:center;">
					<a style="background: #005db9; color: #FFF; border-radius: 20px; padding: 10px 20px;
    text-decoration: none;" href="'.site_url("encrypted/documents/$notification_id").'">View/Download Documents</a></p>
					</div>';					
					$this->email->clear();
					$this->email->set_newline("\r\n");
					$this->email->from('info@machkit.com', 'Feedbacker');
					$this->email->to($receiver['email']);
					$this->email->subject($subject);
					$this->email->message($email_body);
					$this->email->send();	
				}	
			}
		}
	}
	function share_feedback_to_titles($user_id,$feedback_id,$titles){
		if(!empty($titles) && !empty($feedback_id)){
			$this->db->where('feedback_id', $feedback_id);
			$query = $this->db->get('encrypted_titles_feedbacks');
			if($query->num_rows()>0){
				$feedback=$query->row_array();
				foreach($titles as $title_id){
					$this->db->where('user_id', $user_id);
					$this->db->where('feedback_id', $feedback_id);
					$this->db->where('title_id', $title_id);
					$query = $this->db->get('encrypted_titles_feedbacks');
					if($query->num_rows() < 1){						
						$feedback['title_id']=$title_id;
						$feedback['feedback_id']=$feedback_id;
						$feedback['user_id']=$user_id;
						$feedback['datetime']=date('Y-m-d H:i:s');
						unset($feedback['feedback_id']);
						$this->db->insert('encrypted_titles_feedbacks', $feedback);
						$fb_id=$this->db->insert_id();
						
						if($fb_id){
							$title=$this->get_title($title_id,$user_id);
							$this->db->select("file_name,file_url,privacy");
							$this->db->where('feedback_id',$feedback_id);	
							$this->db->from('encrypted_feedback_files');
							$query = $this->db->get();
							$files=$query->result_array();
							foreach($files as $f){
								$f['feedback_id']=$fb_id;
								$this->db->insert('encrypted_feedback_files', $f);
							}
							$this->common->notification($title['user_id'], $user_id, $title_id, $fb_id, $replied_to = '', 6);
							$linked_users=$this->linked_users($title_id,$user_id);
							if($feedback['privacy']==0){
								foreach($linked_users as $usr){
									$this->common->notification($usr['id'], $user_id, $title_id, $fb_id, $replied_to = '', 6);
								}
							}
						}
						
						//echo $this->db->last_query();
					}
				}
			}
		}
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
	function linked_users($title_id,$user_id,$limit=-1){
		$this->db->select("u.id, u.name, u.photo, u.country, tu.sender_id, tu.datetime");
		$this->db->where('tu.title_id',$title_id);	
		$this->db->from('encrypted_titles_users as tu');
		$this->db->join('users as u','u.id = tu.user_id');
		$this->db->order_by("u.name", "ASC");
		if($limit>0)
			$this->db->limit($limit);
		$result = $this->db->get();
		$users=array();
		foreach ($result->result_array() as $user){
			$user['time'] = $this->timeAgo($user['datetime']);
			if(isset($user['photo'])) {
				$user['photo'] = S3_CDN . 'uploads/user/thumbs/' . $user['photo'];
			} else {
				$user['photo'] = ASSETS_URL . 'images/user-avatar.png';
			}
			$users[]=$user;
		}
		return $users;
	}
	function get_title_views($title_id,$user_id,$limit=-1){
		$this->db->select("u.id, u.name, u.photo, u.country, v.created");
		$this->db->where('v.title_id',$title_id);	
		$this->db->from('encrypted_titles_views as v');
		$this->db->join('users as u','u.id = v.user_id');
		$this->db->order_by("u.name", "ASC");
		if($limit>0)
			$this->db->limit($limit);
		$result = $this->db->get();
		$users=array();
		foreach ($result->result_array() as $user){
			$user['time'] = $this->timeAgo($user['created']);
			$users[]=$user;
		}
		return $users;
	}
	public function get_feedback_images($feedback_id){
		/*$this->db->select("image_id, feedback_img, feedback_thumb");
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
		return $rows;*/
		$this->db->select("file_id, file_name, file_url");
		$this->db->where('feedback_id',$feedback_id);	
		$this->db->from('encrypted_feedback_files');
		$query = $this->db->get();
		$rows=array();
		foreach ($query->result_array() as $row){
			$ext = pathinfo($row['file_url'], PATHINFO_EXTENSION);
			if(in_array($ext,$this->image_types)){				
				$item=array('image_id'=>$row['file_id'],'name'=>$row['file_name']);	
				$item['photo'] = S3_CDN . 'uploads/feedback/files/' . $row['file_url'];	
				$item['thumb'] = S3_CDN . 'uploads/feedback/files/' . $row['file_url'];	
				$rows[]=$item;			
			}
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
			$ext = pathinfo($row['file_url'], PATHINFO_EXTENSION);
			if(!in_array($ext ,$this->image_types)){
				$item=array('file_id'=>$row['file_id'],'name'=>$row['file_name']);	
				$item['url'] = S3_CDN . 'uploads/feedback/files/' . $row['file_url'];				
				$rows[]=$item;
			}
		}
		return $rows;
	}
	
	function get_title_feedbacks($title_id,$user_id='',$start=0,$limit=20,$s=''){
		$this->db->select("f.*,u.name, u.photo, u.country");
		$this->db->where('f.title_id',$title_id);
		$this->db->from('encrypted_titles_feedbacks as f');			
		$this->db->join('users as u','u.id = f.user_id');
		if(!empty($s)){
			$this->db->group_start();
			$this->db->like('feedback_cont', $s);
			$this->db->or_like('file_name', $s);			
			$this->db->group_end();
			$this->db->join('encrypted_feedback_files as d','f.feedback_id = d.feedback_id','LEFT');
		}
		$this->db->order_by("f.feedback_id", "DESC");
		if($limit>0)
			$this->db->limit($limit,$start);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$rows=array();
		foreach ($query->result_array() as $row){
			if(isset($row['photo'])) {
				$row['user_avatar'] = S3_CDN . 'uploads/user/thumbs/' . $row['photo'];
				$row['photo'] = S3_CDN . 'uploads/user/thumbs/' . $row['photo'];
			} else {
				$row['user_avatar'] = ASSETS_URL . 'images/user-avatar.png';
				$row['photo'] = ASSETS_URL . 'images/user-avatar.png';
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
			$row['is_feedback_owner'] = false;
			if($user_id==$row['user_id']){
				$row['is_feedback_owner'] = true;
			}
			$rows[]=$row;
		}
		return $rows;
	}
	function get_encrypted($start=0,$limit=20,$search='',$order='t.title_id',$order_type='DESC'){
		$this->db->select('t.*,u.name, u.photo, u.country'); 
		$this->db->from('encrypted_titles as t');
		$this->db->join('users as u','u.id=t.user_id','INNER');
		if(!empty($search)){
			$this->db->like('t.title', $search);
		}
		$this->db->where('t.deleted',0);
		$this->db->order_by('t.title_id', 'DESC');
		if($limit>0)
			$this->db->limit($limit,$start);
		$query = $this->db->get();
		$titles=array();
		foreach ($query->result_array() as $title){			
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
	public function accept_request($title_id,$user_id){		
		$this->db->set('status', 1);
		$this->db->where('title_id', $title_id);		
		$this->db->where('user_id', $user_id);
		$this->db->update('encrypted_title_requests');
		
	}
	public function get_title_join_requests($title_id,$user_id=0,$status=0){
		$this->db->select("r.user_id, r.created, u.name, u.email, u.photo, u.gender");
		$this->db->where('title_id',$title_id);	
		$this->db->where('r.status',$status);	
		$this->db->from('encrypted_title_requests as r');
		$this->db->join('users as u','u.id=r.user_id');
		if(!empty($user_id)){
			$this->db->where('r.user_id',$user_id);	
		}
		$result = $this->db->get();
			
		$users=array();
		foreach ($result->result_array() as $user){
			$user['time'] = $this->timeAgo($user['created']);
			if(isset($user['photo'])) {
				$user['photo'] = S3_CDN . 'uploads/user/thumbs/' . $user['photo'];
			} else {
				$user['photo'] = ASSETS_URL . 'images/user-avatar.png';
			}
			$users[]=$user;
		}
		return $users;
	
	}
	function get_encrypted_titles($user_id,$start=0,$limit=20,$search='',$include_survey=true,$include_public=true){
		/*$query = $this->db->query("SELECT t.*,u.name, u.photo, u.country FROM encrypted_titles as t WHERE t.user_id={$user_id} OR t.title_id IN(SELECT title_id FROM db_encrypted_titles_users WHERE db_encrypted_titles_users.user_id={$user_id}) LIMIT {$start}, {$limit}");*/
			/*$sql=mysql_fetch_array(mysql_query("SELECT * 
    FROM content_table 
    WHERE country_code = '' 
    OR country_code REGEXP ',?". $user_country_code .",?'"));
	*/
		
		$this->db->select('t.*,u.name, u.photo, u.country'); 
		$this->db->from('encrypted_titles as t');
		$this->db->join('users as u','u.id=t.user_id','INNER');
		//$this->db->join('user_notifications as un','un.title_id=t.title_id','LEFT');
		//$this->db->where_in('t.user_id', "SELECT title_id FROM db_encrypted_titles_users WHERE db_encrypted_titles_users.user_id={$user_id}");
		$this->db->group_start();
		$this->db->where('t.deleted',0);
		$this->db->group_end();
		
		$this->db->group_start();		
		$this->db->where('t.title_id IN (SELECT title_id FROM db_encrypted_titles_users WHERE db_encrypted_titles_users.user_id='.$user_id.')');
		$this->db->or_where("t.is_public",1);
		$this->db->or_where("t.user_id",$user_id);		
		$this->db->group_end();
		/*$this->db->group_start();
		$this->db->where("t.allowed_countries",'');
		$this->db->or_where("t.is_public",1);
		$this->db->group_end();*/
		
		if(!empty($search)){
			$this->db->group_start();
			$this->db->like('t.title', $search);
			$this->db->or_where("t.title_id IN (SELECT title_id FROM db_encrypted_titles_feedbacks WHERE db_encrypted_titles_feedbacks.feedback_cont LIKE '%".$search."%')");
			$this->db->group_end();
		}
		if($include_survey==false){
			$this->db->where('t.is_survey',0);
		}
		//$this->db->having('eu.user_id',$user_id);
		$this->db->group_by('t.title_id'); 
		$this->db->order_by("t.modified DESC");
		if($limit>0)
			$this->db->limit($limit,$start);
		
		
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
			$title['unread_count']=$this->get_title_notifications_count($title['title_id'],$user_id);
			$requests=$this->get_title_join_requests($title['title_id'],$user_id);			
			$title['is_request_pending']=(count($requests)>0)? true:false;	
			$titles[]=$title;
		}
		return $titles;
	}
	function get_title_notifications_count($title_id,$user_id){		
		$this->db->where('title_id',$title_id);
		$this->db->where('user_id',$user_id);
		$this->db->where('notification_id',6);
		$this->db->where('is_unread',1);
		$count=$this->db->count_all_results('user_notifications');
		return $count;
		
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
	function get_title($title_id,$user_id=''){
		$this->db->select('encrypted_titles.*, users.name, users.photo, users.country'); 
		$this->db->where('title_id', $title_id);
		$this->db->where('encrypted_titles.deleted', 0);
		$this->db->from('encrypted_titles');
		$this->db->join('users', 'encrypted_titles.user_id = users.id');
        $query = $this->db->get();
		//echo $this->db->last_query();
		$title=array();
        if ($query->num_rows() > 0) {
            $title=$query->row_array();
        }
		if(!empty($title)){
			if(!empty($user_id)){
				$title['is_title_owner']=$this->is_title_owner($title_id,$user_id);
				$title['is_title_linked_user']=$this->is_title_linked_user($title_id,$user_id);
			}else{
				$title['is_title_owner']=false;
				$title['is_title_linked_user']=false;
			}
		
			$title['linked_users_count']=$this->linked_users_count($title_id);
			$title['feedbacks_count']=$this->get_title_feedbacks_count($title_id);	
			$title['likes'] = 0;
			$title['is_liked'] = false;		
			$title['time'] = $this->timeAgo($title['datetime']);
			if(isset($title['photo'])) {
				$title['photo'] = S3_CDN . 'uploads/user/thumbs/' . $title['photo'];
			} else {
				$title['photo'] = ASSETS_URL . 'images/user-avatar.png';
			}	
		}
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