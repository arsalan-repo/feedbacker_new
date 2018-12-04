<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Survey_model extends CI_Model{
	function __construct() {
        parent::__construct();             
    }
	function get_location_survey_results($survey_id){
		/*$this->db->select("a.user_id, u.name, u.photo, a.email, a.ip, a.browser");
		$this->db->where('a.survey_id',$survey_id);
		$this->db->from('survey_users as a');
		$this->db->join('users as u','u.id=a.user_id','left');		
		$this->db->order_by('u.name', 'ASC');*/
		$this->db->select("*");
		$this->db->where('a.survey_id',$survey_id);
		$this->db->from('survey_users as a');			
		$this->db->order_by('a.result_id', 'DESC');		
		$query = $this->db->get();		
		$users=$query->result_array();		
		$this->db->select("*");
		$this->db->where('survey_id',$survey_id);	
		$this->db->from('survey_questions as q');
		$this->db->order_by("q.question_id", "ASC");
		$query = $this->db->get();
		$questions=$query->result_array();
		$results=array();
		foreach($users as $user){
			$item=$user;
			$answers=array();
			$ans='';
			foreach($questions as $question){
				$answer=$this->get_survey_question_answer($question['question_id'],$user['survey_id'],$user['result_id']);
				$ans=$answer;
				if($question['qtype']=='mcq'){
					$option_number=	'option'.$answer;
					if(isset($question[$option_number])){
						$ans=$question[$option_number];
					}else{
						$ans='';
					}
				}				
				$qtitle=$question['question'];
				if($question['qtype']=='single'){
					$ans=$answer;					
					if(!empty($question['answer_label']))
						$qtitle.='('.$question['answer_label'].')';
				}
				$choices=array();
				if($question['qtype']=='checkbox'){
					$anslized=unserialize($answer);
					
					if ($anslized !== false) {
						$ansArr=$anslized;
					}else{
						$ans=$answer;
					}
					$choicesItems=unserialize($question['checkbox']);
					foreach($choicesItems as $citem){
						$choices[$citem['id']]=$citem['option'];
					}	
					$cAns=array();					
					foreach($ansArr as $an){
						$cAns[]=$choices[$an];
						
					}
					if(!empty($cAns)){
						$ans=implode(" , ",$cAns);
					}
				}
				$answers[]=array('question_id'=>$question['question_id'],'question'=>$qtitle,'answer'=>$ans);
				
			}
			$item['questions']=$answers;
			$results[]=$item;
		}
		return $results;
	}
	public function get_location_surveys(){
		$this->db->where('deleted',0);	
		$this->db->from('survey');
		$this->db->order_by('survey_id','DESC');
		$query=$this->db->get();
		return $query->result_array();
	}
	public function add_survey_visit($user_id,$survey_id){		
		$this->db->where('user_id',$user_id);
		$this->db->where('survey_id',$survey_id);
		$query = $this->db->get('survey_visits');
		if ($query->num_rows() <1){
			$post=array('user_id'=>$user_id,
			'survey_id'=>$survey_id
			);
			$this->db->insert('survey_visits', $post);
		}
		
	}
	public function get_location_survey($lat,$lng,$user_id=''){
		$miles=1;
		$this->db->select("*, ( 3959 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance");                         
		$this->db->having('distance <= ' . $miles);
		$this->db->where('deleted',0);		
		$this->db->order_by('distance');  
		$this->db->where('s.survey_id NOT IN (select survey_id from db_survey_visits as a where a.user_id='.$user_id.')',NULL,FALSE);
		$this->db->from('survey as s');		
		$this->db->limit(1, 0);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$survey=array();
		if ($query->num_rows()>0){
			$survey=$query->row_array();
			$this->db->select("q.question_id, q.question, q.option1,q.option2,q.option3,q.option4,,q.option5,q.option6,q.option7,q.option8,q.checkbox");
			$this->db->where('q.survey_id',$survey['survey_id']);		
			$this->db->from('survey_questions as q');		
			$this->db->order_by("q.question_id", "ASC");
			$query = $this->db->get();
			$questions=array();
			foreach($query->result_array() as $question){
				$question['answer']=$this->get_location_question_answer($question['question_id'],$user_id);
				$questions[]=$question;
			}
			$survey['questions']=$questions;
			if($user_id!='851')
				$this->add_survey_visit($user_id,$survey['survey_id']);
		}		
		return $survey;	
		
	}
	function get_survey_question_answer($question_id,$survey_id,$result_id){
		
		$this->db->where('question_id',$question_id);
		$this->db->where('survey_id',$survey_id);
		$this->db->where('result_id',$result_id);	
		$this->db->from('survey_answers as a');
		$query = $this->db->get();
		 if ($query->num_rows()>0){
			 $row=$query->row_array();
			 return $row['answer'];
		 }
		 return 0;
	}
	function get_location_question_answer($question_id,$user_id='',$email='',$ip=''){
		if(empty($user_id) && empty($email) && empty($ip))
			return 0;
		
		$this->db->where('question_id',$question_id);
		if(!empty($user_id))
			$this->db->where('user_id',$user_id);
		if(!empty($email))
			$this->db->where('email',$email);
		if(!empty($email))
			$this->db->where('email',$email);
		$this->db->from('survey_answers as a');
		$query = $this->db->get();
		 if ($query->num_rows()>0){
			 $row=$query->row_array();
			 return $row['answer'];
		 }
		 return 0;
	}
	public function get_location_survey_by_code($code,$user_id='',$email=''){
		$miles=1;		   
		$this->db->where('md5(s.survey_id)',$code); 		 
		$this->db->from('survey as s');				
		$this->db->limit(1, 0);
		$query = $this->db->get();		
		$survey=array();
		if ($query->num_rows()>0){
			$survey=$query->row_array();
			$result_id=0;
			if(!empty($user_id) || !empty($email)){
				if(!empty($user_id))
					$this->db->where('user_id',$user_id); 
				if(!empty($email))
					$this->db->where('email',$email); 
				$this->db->where('survey_id',$survey['survey_id']);
				$this->db->from('survey_users as u');
				$this->db->limit(1, 0);
				$query = $this->db->get();
				if ($query->num_rows()>0){
					$user=$query->row_array();
					$result_id=$user['result_id'];
				}
			}
			
			$this->db->select("q.question_id, q.question, q.option1,q.option2,q.option3,q.option4,q.option5,q.option6,q.option7,q.option8,q.checkbox,q.qtype,q.answer_label");
			$this->db->where('q.survey_id',$survey['survey_id']);		
			$this->db->from('survey_questions as q');		
			$this->db->order_by("q.question_id", "ASC");
			$query = $this->db->get();
			$questions=array();
			foreach($query->result_array() as $question){
				$answer=$this->get_survey_question_answer($question['question_id'],$survey['survey_id'],$result_id);
				
				$anslized=unserialize($answer);
				if ($anslized !== false) {
					$question['answer']=$anslized;
				}else{
					$question['answer']=$answer;
				}
				if(!empty($question['checkbox']))
					$question['choices']=unserialize($question['checkbox']);
				$questions[]=$question;
			}
			$survey['questions']=$questions;
		}		
		return $survey;	
		
	}
	public function get_location_survey_by_id($id,$user_id='',$email=''){
		$miles=1;		   
		$this->db->where('survey_id',$id); 		 
		$this->db->from('survey as s');		
		$this->db->limit(1, 0);
		$query = $this->db->get();
		$survey=array();
		if ($query->num_rows()>0){
			$survey=$query->row_array();
			$this->db->select("q.question_id, q.question, q.option1,q.option2,q.option3,q.option4,q.option5,q.option6,q.option7,q.option8,q.checkbox,q.qtype,q.answer_label");
			$this->db->where('q.survey_id',$survey['survey_id']);		
			$this->db->from('survey_questions as q');		
			$this->db->order_by("q.question_id", "ASC");
			$query = $this->db->get();
			$questions=array();
			foreach($query->result_array() as $question){
				$question['answer']=$this->get_location_question_answer($question['question_id'],$user_id,$email);
				if(!empty($question['checkbox']))
					$question['choices']=unserialize($question['checkbox']);
				$questions[]=$question;
				
			}
			$survey['questions']=$questions;
		}		
		return $survey;	
		
	}
	public function is_survey_completed($user_id,$survey_id){
		$this->db->where('survey_id',$survey_id);		
		$count_q=$this->db->count_all_results('survey_questions');	
		
		$this->db->where('user_id',$user_id);
		$this->db->where('survey_id',$survey_id);		
		$count_a=$this->db->count_all_results('survey_answers');
		if($count_a>1)
			return true;
		return false;
		
		
	}
	public function user_answered_survey($survey_id,$user_id=0,$email=''){
		if(empty($user_id) && empty($email))
			return false;
		if(!empty($user_id)){
			$this->db->where('user_id',$user_id);
		}else{
			if(!empty($email))
				$this->db->where('email',$email);
		}
		$this->db->where('survey_id',$survey_id);
		$query = $this->db->get('survey_users');		
		if ($query->num_rows() <1){
			return false;
		}else{
			return true;
		}
	}
	public function add_survey_user($survey_id,$user_id=0,$email=''){
		$answered=$this->user_answered_survey($survey_id,$user_id,$email);
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		$browser=$_SERVER['HTTP_USER_AGENT'];
		if(!$answered){
			$post=array('user_id'=>$user_id,
				'survey_id'=>$survey_id,
				'email'=>$email,
				'ip'=>$ipAddress,
				'browser'=>$browser				
				);
			$this->db->insert('survey_users', $post);
			return $insert_id = $this->db->insert_id();
		}
		return 0;
	}
	public function add_location_survey($user_id,$survey_id,$question_id,$answer=0,$email='',$result_id=0){
		if(!empty($answer) && (!empty($user_id) OR !empty($email) OR !empty($result_id))){
			if(!empty($user_id)){
				$this->db->where('user_id',$user_id);
			}else{
				if(!empty($email))
					$this->db->where('email',$email);
				if(!empty($result_id))
					$this->db->where('result_id',$result_id);
			}			
			$this->db->where('survey_id',$survey_id);
			$this->db->where('question_id',$question_id);
			$query = $this->db->get('survey_answers');
			//echo $this->db->last_query();
			if ($query->num_rows() <1){
				$post=array('user_id'=>$user_id,
				'survey_id'=>$survey_id,
				'question_id'=>$question_id,
				'answer'=>$answer,
				'email'=>$email,
				'result_id'=>$result_id
				);
				$this->db->insert('survey_answers', $post);
			//	echo $this->db->last_query();
			}else{
				$data = array(
					   'answer' => $answer
					);
				if(!empty($user_id)){
					$this->db->where('user_id',$user_id);
				}else{
					if(!empty($email))
						$this->db->where('email',$email);
				}
				$this->db->where('survey_id', $survey_id);
				$this->db->where('result_id', $result_id);
				$this->db->where('question_id', $question_id);				
				$this->db->update('survey_answers', $data);
			}
		}		
		
	}
	
	function questions_count($title_id){		
		$this->db->where('title_id',$title_id);		
		$count=$this->db->count_all_results('questions');		
		return $count;
	}
	function get_survey_results($title_id){
		$this->db->select("a.user_id, u.name, u.photo");
		$this->db->where('a.title_id',$title_id);
		$this->db->from('answers as a');
		$this->db->join('users as u','u.id=a.user_id');
		$this->db->group_by('a.user_id');
		$this->db->order_by('u.name', 'ASC');
		$query = $this->db->get();		
		$users=$query->result_array();
		
		$this->db->select("question, question_id,first_p ,second_p ,third_p ,fourth_p ,correct");
		$this->db->where('title_id',$title_id);	
		$this->db->from('questions as q');
		$this->db->order_by("q.question_id", "ASC");
		$query = $this->db->get();
		$questions=$query->result_array();
		$results=array();
		foreach($users as $user){
			$item=$user;
			$answers=array();
			foreach($questions as $question){
				$answer=$this->get_question_answer($user['user_id'],$title_id,$question['question_id']);	
				if(!empty($answer)){
					if($answer==1){
						$ans=$question['first_p'];
					}
					if($answer==2){
						$ans=$question['second_p'];
					}
					if($answer==3){
						$ans=$question['third_p'];
					}
					if($answer==4){
						$ans=$question['fourth_p'];
					}
				}else{
					$ans='';
				}
			
				
				
				
				$answers[]=array('question_id'=>$question['question_id'],'question'=>$question['question'],'answer'=>$ans);
			}
			$item['questions']=$answers;
			$results[]=$item;
		}
		return $results;
	}
	function delete_survey_question($title_id,$question_id){
		$tables = array('question_images');
		$this->db->where('question_id', $question_id);
		$this->db->delete($tables);
		
		$tables = array('answers','questions');
		$this->db->where('title_id', $title_id);
		$this->db->where('question_id', $question_id);
		$this->db->delete($tables);
	}
	function add_question_answer($user_id,$title_id,$question_id,$answer){
		$this->db->where('user_id',$user_id);
		$this->db->where('title_id',$title_id);
		$this->db->where('question_id',$question_id);
		$query = $this->db->get('answers');
		if ($query->num_rows() <1){
			$post=array('user_id'=>$user_id,
			'title_id'=>$title_id,
			'question_id'=>$question_id,
			'answer'=>$answer
			);
			$this->db->insert('answers', $post);
		}
	}
	function get_question_answer($user_id,$title_id,$question_id){
		$this->db->select("id,answer,created");
		$this->db->where('question_id',$question_id);
		$this->db->where('title_id',$title_id);	
		$this->db->where('user_id',$user_id);			
		$this->db->from('answers');
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) {
			$answer=$query->row_array();
			return $answer['answer'];
		}
		return array();
	}
	function get_survey_questions($title_id,$user_id=''){
		$this->db->select("*");
		$this->db->where('q.title_id',$title_id);	
		$this->db->from('questions as q');
		$this->db->order_by("q.question_id", "ASC");
		$query = $this->db->get();
		$rows=array();
		foreach ($query->result_array() as $row){
			$row['answered']=false;
			$row['answer']="";
			if(!empty($user_id)){
				$answer=$this->get_question_answer($user_id,$title_id,$row['question_id']);
				if(!empty($answer)){
					$row['answered']=true;
					$row['answer']=$answer;
				}
			}
			$row['images'] = $this->get_question_images($row['question_id']);
			$rows[]=$row;
		}
		return $rows;
	}	
	public function get_question_images($question_id){
		$this->db->select("image_id, photo, thumb");
		$this->db->where('question_id',$question_id);	
		$this->db->from('question_images');
		$query = $this->db->get();
		$rows=array();
		foreach ($query->result_array() as $row){
			$item=array('image_id'=>$row['image_id']);	
			$item['photo'] = S3_CDN . 'uploads/feedback/main/' . $row['photo'];
			$item['thumb'] = S3_CDN . 'uploads/feedback/thumbs/' . $row['thumb'];			
			$rows[]=$item;
		}
		return $rows;
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