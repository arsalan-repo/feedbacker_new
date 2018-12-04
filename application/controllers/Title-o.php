<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;

class Title extends CI_Controller {
	
	public $data;
	
	public $user;
	
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

        $this->data['title'] = "Title | Feedbacker ";

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
		/* Load Template */
		$this->template->front_render('user/dashboard');
	}
	
	// Follow / Unfollow Title
	public function follow() {
		if ($this->input->is_ajax_request()) {
			$title_id = $this->input->post('title_id');
			
			$condition_array = array('user_id' => $this->user['id'], 'title_id' => $title_id);
			$followings = $this->common->select_data_by_condition('followings', $condition_array, $data = '*', $short_by = '', $order_by = '', $limit = '1', $offset = '', $join_str = array(), $group_by = '');
			
			if(count($followings) > 0) {
				// Unfollow Title
				$this->common->delete_data('followings', 'follow_id', $followings[0]['follow_id']);
				echo json_encode(array('is_followed' => 0, 'message' => $this->lang->line('success_unfollow_title'), 'status' => 1));
				die();
			} else {
				// Follow Title
				$insert_array['user_id'] = $this->user['id'];
				$insert_array['title_id'] = $title_id;
				
				$insert_result = $this->common->insert_data($insert_array, $tablename = 'followings');
				echo json_encode(array('is_followed' => 1, 'message' => $this->lang->line('success_follow_title'), 'status' => 1));
				die();
			}
		}
	}
	
	public function create() {
		//check post and save data
        if ($this->input->is_ajax_request() && $this->input->post('btn_save')) {
			$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
			
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('error', validation_errors());
				redirect('user/profile');
			}
		}
		
		$this->data['module_name'] = 'Post';
        $this->data['section_title'] = 'Create';
		
		/* Load Template */
		$this->template->front_render('post/create', $this->data);
	}
	
	// Get Titles/Suggestions
    public function search() {
		if ($this->input->is_ajax_request()) {
			$search_string = $this->input->get('term');

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
							'query' => 'title:*'.$search_string.'*'
						],
					]
				]
			];
	
			$title = $this->aws_client->search($params);
	
			$titles = [];
			foreach ($title['hits']['hits'] as $key => $value) {
				$titles[] = $value['_source'];
			}
			
			/*$search_condition = 'title LIKE "'.$search_string.'%" AND deleted = 0';
			$titles = $this->common->select_data_by_search('titles', $search_condition, $contition_array = array(), $data = 'title_id, title', $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str = '', $custom_order_by = '', $group_by='');*/
			
			if(!empty($titles)) {
				echo json_encode($titles);
			} else {
				echo json_encode(array('0' => array('title_id' => '', 'title' => '')));
			}
			die();
		}
    }
	
}
