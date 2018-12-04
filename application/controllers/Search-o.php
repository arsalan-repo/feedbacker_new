<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;

class Search extends CI_Controller {

	public $data;
	
	public $user;
	
	private $perPage = 10;
	
	private $aws_client;

    public function __construct() {
        parent::__construct();

        // Prevent access without login
		if(!isset($this->session->userdata['mec_user'])){
			redirect();
		}
		
		// Load library
		$this->load->library('s3');
		$this->load->library('template');
		
		$this->aws_client = ClientBuilder::create()->setHosts(["search-feedbacker-q3gdcfwrt27ulaeee5gz3zbezm.eu-west-1.es.amazonaws.com:80"])->build();

        $this->data['title'] = "Search | Feedbacker ";

        // Load Login Model
        $this->load->model('common');
		
		// Session data
		$this->user = $this->session->userdata['mec_user'];
		$this->data['user_info'] = $this->user;
		
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
    }
	
	public function index() {
		$this->data['module_name'] = 'Search';
        $this->data['section_title'] = 'Search Results';
		
		// Trends
		$this->data['trends'] = $this->common->getTrends($this->user['country']);
		
		// What to Follow
		$this->data['to_follow'] = $this->common->whatToFollow($this->user['id'], $this->user['country']);
	
		$qs = $this->input->get('qs');
		
		// Search Report Log
		if ($qs != '') {
			$this->common->searchLog($qs, $this->user['id']);
		}
		
		//Get All Ids
		$params = ['index' => 'title'];
		$response = $this->aws_client->indices()->exists($params);

		if(!$response){
			$indexParams = [
				'index' => 'title',
				'body' => [
					'settings' => [
						'number_of_shards' => 5,
						'number_of_replicas' => 1
					]
				]
			];

			$response = $this->aws_client->indices()->create($indexParams);
		} 

		$params = [
			'index' => 'title',
			'body' => [
				'query' => [
					'query_string' => [
						'query' => 'title:*'.$qs.'*',
						//"fuzzy_prefix_length" => 1,
					],
				]
			]
		];

		$title = $this->aws_client->search($params);

		$title_id = [];
		foreach ($title['hits']['hits'] as $key => $value) {
			$title_id[] = $value['_source']['title_id'];
		}

		$params = ['index' => 'feedback'];
		$response = $this->aws_client->indices()->exists($params);

		if(!$response){
		$indexParams = [
			'index' => 'feedback',
			'body' => [
				'settings' => [
					'number_of_shards' => 5,
					'number_of_replicas' => 1,

				]
			]
		];

		$response = $this->aws_client->indices()->create($indexParams);
		}

		$params = [
			'index' => 'feedback',
			'body' => [
				'query' => [
					'query_string' => [
						'query' => 'feedback_cont:*'.$qs.'*',
						//"fuzzy_prefix_length" => 1,
					],
				]
			]
		];

		$feedback = $this->aws_client->search($params);

		$feedback_id = [];
		foreach ($feedback['hits']['hits'] as $key => $value) {
			$feedback_id[] = $value['_source']['feedback_id'];
		}

		$title_comma = implode(',', $title_id);
		$feedback_comma = implode(',', $feedback_id);

		// Get Search Results
		$custom_in_sql = '';
		$custom_in_arr = [];
		
		if(!empty($title_comma)){
			$custom_in_arr[] = "db_titles.title_id IN (".$title_comma.")";
		} else {
			$custom_in_arr[] = "db_titles.title_id IN (0)";
		}

		if(!empty($feedback_comma)){
			$custom_in_arr[] = "db_feedback.feedback_id IN (".$feedback_comma.")";
		} else {
			$custom_in_arr[] = "db_feedback.feedback_id IN (0)";
		}

		$custom_in_sql = implode(' OR ', $custom_in_arr);
		
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
		
		$search_condition = "(".$custom_in_sql.") AND db_feedback.deleted = 0 AND feedback.status = 1";
		$data = 'feedback_id, feedback.title_id, title, users.id as user_id, name, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video, replied_to, location, feedback.datetime as time';

		if (!empty($this->input->get("page"))) {
			$page = ceil($this->input->get("page") - 1);
			$start = ceil($page * $this->perPage);
			
			$feedback = $this->common->select_data_by_search('feedback', $search_condition, $condition_array = array(), $data, $sortby = '', $orderby = '', $this->perPage, $start, $join_str,'(title = "'.$qs.'") DESC,(name = "'.$qs.'") DESC, length(title), length(name)');
			
			if(count($feedback) > 0) {
				// Get Likes, Followings and Other details
				$return_array = $this->common->getFeedbacks($feedback, $this->user['id']);
				
				// Append Ad Banners
				$return_array = $this->common->adBanners($return_array, $this->user['country'], 'search', $this->input->get("page"));
							
				$this->data['qs'] = $qs;
				$this->data['feedbacks'] = $return_array;
			} else {
				$this->data['qs'] = $qs;
				$this->data['feedbacks'] = array();
			}
			
			$response = $this->load->view('post/ajax', $this->data);
			echo json_encode($response);
		} else {
			$feedback = $this->common->select_data_by_search('feedback', $search_condition, $condition_array = array(), $data, $sortby = '', $orderby = '', $this->perPage, 0, $join_str,'(title = "'.$qs.'") DESC,(name = "'.$qs.'") DESC, length(title), length(name)');
		
			if(count($feedback) > 0) {
				// Get Likes, Followings and Other details
				$return_array = $this->common->getFeedbacks($feedback, $this->user['id']);
				
				// Append Ad Banners
				$return_array = $this->common->adBanners($return_array, $this->user['country'], 'search');
							
				$this->data['qs'] = $qs;
				$this->data['feedbacks'] = $return_array;
			} else {
				$this->data['qs'] = $qs;
				$this->data['feedbacks'] = array();
			}
		
			/* Load Template */
			$this->template->front_render('post/search', $this->data);
		}
	}
	
	public function results() {
		//check post and save data
        if ($this->input->is_ajax_request() && $this->input->post('btn_save')) {
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
			
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
				redirect('user/profile');
			}
		}
		
		$this->data['module_name'] = 'Post';
        $this->data['section_title'] = 'Search';
		
		/* Load Template */
		$this->template->front_render('post/search', $this->data);
	}
}
