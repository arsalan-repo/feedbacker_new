<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use Elasticsearch\ClientBuilder;

class UserProfile extends CI_Controller
{

    public $data;

    public $user;

    private $perPage = 10;

    private $aws_client;
    private $user_country;
    private $user_lang = 'en';

    public function __construct()
    {
        parent::__construct();

        // Prevent access without login


        // Load library
        $this->load->library('s3');
        $this->load->library('template');

        $this->aws_client = ClientBuilder::create()->setHosts(["search-feedbacker-q3gdcfwrt27ulaeee5gz3zbezm.eu-west-1.es.amazonaws.com:80"])->build();

        $this->data['title'] = "Post | Feedbacker ";

        // Load Login Model
        $this->load->model('common');

        if (isset($this->session->userdata['mec_user'])) {
            $this->user = $this->session->userdata['mec_user'];
            $this->data['user_info'] = $this->user;
        } else {
            $this->data['user_info'] = array();
        }

        if (isset($this->session->userdata['user_country'])) {
            $this->user_country = $this->session->userdata['user_country'];
        } else {
            if (!empty($this->user['country']))
                $this->user_country = $this->user['country'];
            else
                $this->user_country = 'jo';
        }

        if (isset($this->session->userdata['user_lang'])) {
            $this->user_lang = $this->session->userdata['user_lang'];
        } else {
            if (!empty($this->user['language']))
                $this->user_lang = $this->user['language'];
            else
                $this->user_lang = 'en';
        }

        // Load Language File
        if ($this->user_lang == 'ar') {
            $this->lang->load('message', 'arabic');
            $this->lang->load('label', 'arabic');
        } else {
            $this->lang->load('message', 'english');
            $this->lang->load('label', 'english');
        }
        $this->data['language'] = $this->user_lang;
        $this->data['user_info']['language'] = $this->user_lang;

        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    public function profile(){
        $profile_id = $this->uri->segment(3);
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
        $contition_array = array('id' => $profile_id);
        $user_result = $this->common->select_data_by_condition('users', $contition_array, $data = '*', $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str = array());

        $this->data['user_data'] = $user_result[0];
        $this->data['country_list'] = $this->common->select_data_by_condition('countries', $contition_array = array(), '*', $short_by = 'country_name', $order_by = 'ASC', $limit = '', $offset = '');

        $this->data['module_name'] = 'User';
        $this->data['section_title'] = $user_result[0]['name'];
        $is_restricted = $this->common->fetch('db_restrict_user', array('session_id' => $this->session->userdata['mec_user']['id'], 'user_id' => $profile_id));
        if(!empty($is_restricted)){
            $this->data['is_restricted'] = 1;
        }else{
            $this->data['is_restricted'] = 0;
        }
        /* Load Template */
        $this->template->front_render('user/friend_profile', $this->data);
    }

    public function feedbacks() {
        $profile_id = $_POST['user_id'];
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

            $contition_array = array('feedback.user_id' => $profile_id, 'feedback.replied_to' => NULL, 'feedback.deleted' => 0, 'feedback.status' => 1);
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

    public function followings() {
        $profile_id = $_POST['user_id'];
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

            $contition_array = array('followings.user_id' => $profile_id, 'feedback.replied_to' => NULL, 'feedback.deleted' => 0, 'feedback.status' => 1);
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

    public function showFriendsInProfile(){
        $friends = $this->common->user_friends($_POST['user_id']);
        $this->data['friends']=$friends;
        $this->load->view('user/show_friend_list', $this->data);
    }

    public function block_user(){
        if(isset($_POST['user_id']) && isset($_POST['session_id'])){
            $data = array(
                'session_id' => $_POST['session_id'],
                'user_id' => $_POST['user_id']
            );
            $this->common->insert('db_restrict_user', $data);
            echo json_encode(array('message' => 'User has been blocked', 'status' => 1));
            die;
        }
        echo json_encode(array('error_message' => 'An error occurred', 'status' => 0));
    }

    public function unblock_user(){
        if(isset($_POST['user_id']) && isset($_POST['session_id'])){
            $data = array(
                'session_id' => $_POST['session_id'],
                'user_id' => $_POST['user_id']
            );
            $this->common->delete('db_restrict_user', $data);
            echo json_encode(array('message' => 'User has been unblocked', 'status' => 1));
            die;
        }
        echo json_encode(array('error_message' => 'An error occurred', 'status' => 0));
    }
}

