<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;

class Questionnaire extends MY_Controller {

    public $data;
    public function __construct() {

        parent::__construct();

        //$GLOBALS['record_per_page']=10;
        //site setting details
        $this->load->model('common');
		$this->load->model('survey_model');
		$this->load->library('googlemaps');
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
		$this->data['title'] = 'Questionnaire - Feedbacker.com';
        $this->data['module_name'] = 'Questionnaires';
        $this->data['section_title'] = 'Questionnaire';
        $rows=$this->survey_model->get_location_surveys(0,20);	
		$this->data['surveys']=$rows;
        $this->template->admin_render('admin/survey/index', $this->data);
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
		$titles=$this->survey_model->get_location_surveys($start,$length,$search);		
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
    public function add() {
		$this->data['title'] = 'Create Questionnaire';
		$config['center'] = '31.275684, 36.704657';
		$config['apiKey'] = 'AIzaSyBse_YbHQtSAuKinZfQnzq1jM7w7HPLkm0';
		$config['https'] = TRUE;
		$config['zoom'] = 'auto';
		$config['places'] = TRUE;
		
		$config['placesAutocompleteInputID'] = 'location';
		$config['placesAutocompleteBoundsMap'] = TRUE; 
		$config['placesAutocompleteOnChange'] = 'var place = placesAutocomplete.getPlace();
             if (place.geometry.viewport) {
                 map.fitBounds(place.geometry.viewport);
             } else {
                 map.setCenter(place.geometry.location);
                
             }
			 jQuery("#location_lat").val(place.geometry.location.lat());
			 jQuery("#location_lng").val(place.geometry.location.lng());
			 jQuery("#location_address").val(place.formatted_address);
			 console.log(place);
			 marker_0.setPosition(place.geometry.location);
			marker_0.setVisible(true);
			 ';
		$this->googlemaps->initialize($config);
		$marker = array();
		$marker['position'] ='0, -29';
		$this->googlemaps->add_marker($marker);
		$this->data['map'] = $this->googlemaps->create_map();
		
        if ($this->input->post('btn_save')) {
			$questions=$this->input->post('questions');
			$options=$this->input->post('options');
			$choices=$this->input->post('choices');
			$qtype=$this->input->post('qtype');
			$qtype_label=$this->input->post('qtype_label');
            $update_array = array(
				'title' => trim($this->input->post('title')),
				'location' => trim($this->input->post('location')),
				'lat' => trim($this->input->post('lat')),
				'lng' => trim($this->input->post('lng')),
				'address' => trim($this->input->post('address'))
            );
			$survey_id=$this->common->insert_data($update_array, $tablename = 'survey');
			$this->common->update_data($update_array, 'survey', 'survey_id',$survey_id);
			foreach($questions as $key=>$value){
				if(!empty($value)){
					$post=array('question'=>$value,'survey_id'=>$survey_id);
					$qoptions=$options[$key];
					$qchoices=$choices[$key];
					$q_type=$qtype[$key];
					$q_ans_label=$qtype_label[$key];
					if(!empty($qoptions)){						
						foreach($qoptions as $k=>$opt){
							$o=$k+1;;
							if(!empty($opt))
								$post['option'.$o]=$opt;
						}
					}							
					if(!empty($q_type))	
						$post['qtype']=$q_type;
					if(!empty($q_ans_label))	
						$post['answer_label']=$q_ans_label;
					
					if(!empty($qchoices) && is_array($qchoices)){
						$ci=0;
						$choicesArr=array();
						foreach($qchoices as $choice){
							if(!empty($choice)){
								$ci++;
								$choicesArr[]=array('id'=>$ci,'option'=>$choice);
							}
						}
						if(!empty($choicesArr))
							$post['checkbox']=serialize($choicesArr);
					}
					$this->common->insert_data($post, $tablename = 'survey_questions');
				}
				
			}
			redirect('admin/questionnaire/edit/'.$survey_id, 'refresh');
			
        }
		     
       
         $this->data['module_name'] = 'Questionnaire Management';
         $this->data['section_title'] = 'Add Questionnaire';
		$this->data['title'] = 'Add Questionnaire';
		$this->data['country_list'] = $this->common->select_data_by_condition('countries', $contition_array = array(), '*', $short_by = 'country_name', $order_by = 'ASC', $limit = '', $offset = '');
            $this->template->admin_render('admin/survey/add', $this->data);
       
    }
    public function qrcode($id){
		$survey=$this->survey_model->get_location_survey_by_id($id); 
		$this->data['title'] = 'Questionnaire QR Code';
		$qrcode_name='qrcode'.$id.'.png';
		$qrcode_img=FCPATH.'qrcode/'.$qrcode_name;
		$this->load->library('ciqrcode');
		$config['cacheable']	= true; //boolean, the default is true
		$config['cachedir']		= ''; //string, the default is application/cache/
		$config['errorlog']		= ''; //string, the default is application/logs/
		$config['quality']		= true; //boolean, the default is true
		$config['size']			= ''; //interger, the default is 1024	
		$this->ciqrcode->initialize($config);
		$params['data'] = 'https://feedbacker.me/questionnaire/'.md5($id);
		$params['level'] = 'H';
		$params['size'] = 8;
		$params['savename'] = $qrcode_img;
		//header("Content-Type: image/png");
		$this->ciqrcode->generate($params);	
		$this->data['qrcode_name']=$qrcode_name;
		$this->data['module_name'] = $survey['location'];
		$this->load->view('admin/survey/qrcode',$this->data);
	}
	public function results($id){					
		$this->data['module_name'] = 'Questionnaire';
        $this->data['section_title'] = 'Questionnaire Results';	
		
		
		$survey=$this->survey_model->get_location_survey_by_id($id); 
		$results=$this->survey_model->get_location_survey_results($id);
		$this->data['results']=$results;	
		$this->data['survey']=$survey;	
		$this->data['title'] = $survey['title'].' - Questionnaire Results';		
		$this->template->admin_render('admin/survey/results', $this->data);
		
	}
    public function edit($id = '') {
		$survey=$this->survey_model->get_location_survey_by_id($id); 
		$this->data['title'] = 'Questionnaire Detail';	
		$config['center'] = $survey['lat'].",".$survey['lng'];
		$config['apiKey'] = 'AIzaSyBse_YbHQtSAuKinZfQnzq1jM7w7HPLkm0';
		$config['https'] = TRUE;
		$config['zoom'] = 'auto';
		$config['places'] = TRUE;
		
		$config['placesAutocompleteInputID'] = 'location';
		$config['placesAutocompleteBoundsMap'] = TRUE; 
		$config['placesAutocompleteOnChange'] = 'var place = placesAutocomplete.getPlace();
             if (place.geometry.viewport) {
                 map.fitBounds(place.geometry.viewport);
             } else {
                 map.setCenter(place.geometry.location);
                
             }
			 jQuery("#location_lat").val(place.geometry.location.lat());
			 jQuery("#location_lng").val(place.geometry.location.lng());
			 jQuery("#location_address").val(place.formatted_address);
			 console.log(place);
			 marker_0.setPosition(place.geometry.location);
			marker_0.setVisible(true);
			 ';
		$this->googlemaps->initialize($config);
		$marker = array();
		$marker['position'] = $survey['lat'].",".$survey['lng'];
		$this->googlemaps->add_marker($marker);
		$this->data['map'] = $this->googlemaps->create_map();
		
		
        if ($this->input->post('survey_id')) {
			$question=$this->input->post('question');
			$option=$this->input->post('option');
			$questions=$this->input->post('questions');
			$options=$this->input->post('options');
			$choices=$this->input->post('choices');
			$survey_id=$this->input->post('survey_id');
			$qtype=$this->input->post('qtype');
			$qtypes=$this->input->post('qtypes');
			$qtype_label=$this->input->post('qtype_label');
			$qtype_labels=$this->input->post('qtype_labels');
            $update_array = array(
				'title' => trim($this->input->post('title')),
				'location' => trim($this->input->post('location')),
				'lat' => trim($this->input->post('lat')),
				'lng' => trim($this->input->post('lng')),
				'address' => trim($this->input->post('address'))
            );			
			$this->common->update_data($update_array, 'survey', 'survey_id',$survey_id);
			foreach($questions as $key=>$value){
				if(!empty($value)){
					$post=array('question'=>$this->emoji->Encode($value),'survey_id'=>$survey_id);
					$qoptions=$options[$key];					
					$qchoices=$choices[$key];
					$q_type=$qtypes[$key];
					$q_ans_label=$qtype_labels[$key];												
					if(!empty($q_type))	
						$post['qtype']=$q_type;
					if(!empty($q_ans_label))	
						$post['answer_label']=$q_ans_label;
					if(!empty($qoptions)){						
						foreach($qoptions as $k=>$opt){
							$o=$k+1;;
							if(!empty($opt))
								$post['option'.$o]=$this->emoji->Encode($opt);
						}
					}			
					if(!empty($qchoices) && is_array($qchoices)){
						$ci=0;
						$choicesArr=array();
						foreach($qchoices as $cid=>$choice){
							if(!empty($choice)){
								$ci++;
								$choicesArr[]=array('id'=>$cid,'option'=>$choice);
							}
						}
						if(!empty($choicesArr))
							$post['checkbox']=serialize($choicesArr);
					}
					$this->common->insert_data($post, $tablename = 'survey_questions');
				}
				
			}
			foreach($question as $key=>$value){
				if(!empty($value)){
					$post=array('question'=>$value);
					$oplist=$option[$key];
					$qchoices=$choices[$key];
					$q_type=$qtype[$key];
					$q_ans_label=$qtype_label[$key];
					foreach($oplist as $k=>$opt){
						$j=$k+1;
						$post['option'.$j]=$opt;
					}
					if(!empty($q_type))	
						$post['qtype']=$q_type;
					if(!empty($q_ans_label))	
						$post['answer_label']=$q_ans_label;
					if(!empty($qchoices) && is_array($qchoices)){
						$ci=0;
						$choicesArr=array();
						foreach($qchoices as $cid=>$choice){
							if(!empty($choice)){
								$ci++;
								$choicesArr[]=array('id'=>$cid,'option'=>$choice);
							}
						}
						if(!empty($choicesArr))
							$post['checkbox']=serialize($choicesArr);
					}	
					//print_r($post);
					$this->common->update_data($post, 'survey_questions', 'question_id',$key);
				}else{
					
					$this->common->delete_data('survey_questions', 'question_id',$key);					
				}
			}
		
			
        }
		$survey=$this->survey_model->get_location_survey_by_id($id);      
        if (!empty($survey)) {
            $this->data['module_name'] = 'Questionnaire Management';
            $this->data['section_title'] = 'Edit Questionnaire';
			$this->data['title'] = 'Edit Questionnaire';
            $this->data['survey'] = $survey;
			$this->data['country_list'] = $this->common->select_data_by_condition('countries', $contition_array = array(), '*', $short_by = 'country_name', $order_by = 'ASC', $limit = '', $offset = '');
            $this->template->admin_render('admin/survey/edit', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
            redirect('admin/questionnaire', 'refresh');
        }
    }
    
    public function delete($id) {		
		$this->common->update_data(array('deleted'=>'1'), 'survey', 'survey_id',$id);	
		$this->session->set_flashdata('success', 'Survey successfully deleted');		
		redirect('admin/questionnaire');	
        
    }
	


}

?>
