<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Spam extends MY_Controller {

    public $data;

    public function __construct() {

        parent::__construct();

        //$GLOBALS['record_per_page']=10;
        //site setting details
        $this->load->model('common');
        $site_name_values = $this->common->select_data_by_id('settings', 'setting_id', '1', '*');

        $this->data['site_name'] = $site_name = $site_name_values[0]['setting_value'];
        //set header, footer and leftmenu
        $this->data['title'] = 'Spam | ' . $site_name;

        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    //display users list
    public function index() {

        $this->data['module_name'] = 'Spam';
        $this->data['section_title'] = 'Spam';

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
		
		$search_condition = "(feedback_id IN (select feedback_id from db_spam)) AND db_feedback.deleted = 0 AND feedback.status = 1";
		$data = 'feedback_id, feedback.title_id, title, email, name, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video, replied_to, location, feedback.status, feedback.datetime as time';
		
		$this->data['spam_list'] = $this->common->select_data_by_search('feedback', $search_condition, $condition_array = array(), $data, $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str);

        /* Load Template */
        $this->template->admin_render('admin/spam/index', $this->data);
    }

    //update the user detail
    public function view($id = '') {

        $users_detail = $this->common->select_data_by_id('users', 'id', $id, '*');
        if ($users_detail[0]['id'] != '') {
            $this->data['module_name'] = 'Users';
            $this->data['section_title'] = 'View Users';
            $this->data['users_detail'] = $users_detail;

            $this->load->view('admin/feedbacks/view', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Errorout Occurred. Try Again.');
            redirect('admin/feedbacks', 'refresh');
        }
    }
    
    // feedbacks status change
    public function visibility($feedback_id = '', $status = '') {
        if ($feedback_id == '' || $status == '') {
            $this->session->set_flashdata('error', 'Error Occurred. Try Agaim!');
            redirect('admin/feedbacks', 'refresh');
        }
        if ($status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $update_data = array('status' => $status);

        $update_result = $this->common->update_data($update_data, 'feedback', 'feedback_id', $feedback_id);
        if ($update_result) {
            $this->session->set_flashdata('success', 'Visibility successfully updated');
            redirect('admin/spam', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
            redirect('admin/spam', 'refresh');
        }
    }

    //add new feedback
    public function add() {
        //check post and save data
        if ($this->input->post('btn_save')) {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');

            if ($this->form_validation->run() === FALSE){
                $this->session->set_flashdata('error', validation_errors());
                redirect('admin/feedbacks');
            } else {

                if ($_FILES['photo']['name']) {
                    $config['upload_path'] = $this->config->item('user_main_upload_path');
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
                        $config_thumb['new_image'] = $this->config->item('user_thumb_upload_path') . $imgdata['file_name'];
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
                    } else {
                        $thumberror = '';
                    }

                    if ($imgerror != '' || $thumberror != '') {
                        $error[0] = $imgerror;
                        $error[1] = $thumberror;
                    } else {
                        $error = array();
                    }

                    if ($error) {
                        $this->session->set_flashdata('error', $error[0]);
                        redirect('users', 'refresh');
                    }
                }

                $insert_array = array(
                    'name' => trim($this->input->post('name')),
                    'email' => trim($this->input->post('email')),
                    'gender' => $this->input->post('gender'),
					'country' => $this->input->post('country'),
                    'password' =>md5($this->input->post('password')),
					'create_date' => date('Y-m-d H:i:s'),
                    'status' => 1
                );
				
				if($this->input->post('dob') !== '') {
					$insert_array['dob'] = $this->input->post('dob');
				}

                if($dataimage) {
                    $insert_array['photo'] = $dataimage;
                }
                
                $insert_result = $this->common->insert_data($insert_array, 'users');
                
                if ($insert_result) {
                    // Send Welcome Mail
                    //$this->load->library('email');
//                    $config['protocol'] = "smtp";
//                    $config['smtp_host'] = "ssl://smtp.gmail.com";
//                    $config['smtp_port'] = "465";
//                    $config['smtp_user'] = "viral.kanz@gmail.com"; 
//                    $config['smtp_pass'] = "Gmail#369";
//                    $config['charset'] = "utf-8";
//                    $config['mailtype'] = "html";
//                    $config['newline'] = "\r\n";
//
//                    $this->email->initialize($config);
//
//                    $this->email->from('noreply@feedbacker.com', 'MEC');
//                    $this->email->to($this->input->post('email'));
//                    $this->email->subject('Welcome to Feedbacker');
//
//                    $data['site_url'] = $this->config->item('MAIN_SITE_URL');
//                    $data['logo'] = $this->config->item('MAIN_SITE_URL').'images/footer-logo.png';
//                    $data['name'] = ucfirst($this->input->post('name'));
//                    $body = $this->load->view('emails/welcome_user.php',$data,TRUE);
//                    $this->email->message($body);
//                    $this->email->send();
                    
                    $this->session->set_flashdata('success', 'User successfully inserted.');
                    redirect('admin/feedbacks', 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
                    redirect('admin/feedbacks', 'refresh');
                }
            }
        }

        $this->data['module_name'] = 'User Management';
        $this->data['section_title'] = 'Add User';
		$this->data['country_list'] = $this->common->select_data_by_condition('countries', $contition_array = array(), '*', $short_by = 'country_name', $order_by = 'ASC', $limit = '', $offset = '');
		
        /* Load Template */
        $this->template->admin_render('admin/feedbacks/add', $this->data);
    }

    //update the feedback detail
    public function edit($id = '') {

        if ($this->input->post('feedback_id')) {
          
            $update_array = array(
				'feedback_cont' => trim($this->input->post('feedback_cont'))
            );

			$update_result = $this->common->update_data($update_array, 'feedback', 'feedback_id', $this->input->post('feedback_id'));
           
            if ($update_result) {
                $this->session->set_flashdata('success', 'Feedback successfully updated.');
                redirect('admin/feedbacks', 'refresh');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
                redirect('admin/feedbacks', 'refresh');
            }
        }

        $feedback_detail = $this->common->select_data_by_id('feedback', 'feedback_id', $id, '*');
        if (!empty($feedback_detail)) {
            $this->data['module_name'] = 'Feedback Management';
            $this->data['section_title'] = 'Edit Feedback';
            $this->data['feedback_detail'] = $feedback_detail;

            /* Load Template */
            $this->template->admin_render('admin/feedbacks/edit', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
            redirect('admin/feedbacks', 'refresh');
        }
    }

    // feedbacks delete
    public function delete($id = '') {
        // $delete_result = $this->common->delete_data('feedback', 'feedback_id', $id);
        $update_data = array('deleted' => 1);
        $update_result = $this->common->update_data($update_data, 'feedback', 'feedback_id', $id);

        if ($update_result) {
            $this->session->set_flashdata('success', 'Feedback successfully deleted');
            redirect('admin/feedbacks', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
            redirect('admin/feedbacks', 'refresh');
        }
    }

    public function do_upload_multiple_files($fieldName, $options) {

        $response = array();
        $files = $_FILES;
        $cpt = count($_FILES[$fieldName]['name']);
        for ($i = 0; $i < $cpt; $i++) {
            $_FILES[$fieldName]['name'] = $files[$fieldName]['name'][$i];
            $_FILES[$fieldName]['type'] = $files[$fieldName]['type'][$i];
            $_FILES[$fieldName]['tmp_name'] = $files[$fieldName]['tmp_name'][$i];
            $_FILES[$fieldName]['error'] = $files[$fieldName]['error'][$i];
            $_FILES[$fieldName]['size'] = $files[$fieldName]['size'][$i];

            $this->load->library('upload');
            $this->upload->initialize($options);

            //upload the image
            if (!$this->upload->do_upload($fieldName)) {
                $response['error'][] = $this->upload->display_errors();
            } else {
                $response['result'][] = $this->upload->data();
                $users_thumb[$i]['image_library'] = 'gd2';
                $users_thumb[$i]['source_image'] = $this->config->item('users_main_upload_path') . $response['result'][$i]['file_name'];
                $users_thumb[$i]['new_image'] = $this->config->item('users_thumb_upload_path') . $response['result'][$i]['file_name'];
                $users_thumb[$i]['create_thumb'] = TRUE;
                $users_thumb[$i]['maintain_ratio'] = FALSE;
                $users_thumb[$i]['thumb_marker'] = '';
                $users_thumb[$i]['width'] = $this->config->item('users_thumb_width');
                $users_thumb[$i]['height'] = $this->config->item('users_thumb_height');
                $instanse = "image_$i";
                //Loading Image Library
                $this->load->library('image_lib', $users_thumb[$i], $instanse);
                $dataimage = $response['result'][$i]['file_name'];

                //Creating Thumbnail
                $this->$instanse->resize();
                $response['error'][] = $thumberror = $this->$instanse->display_errors();
            }
        }

        return $response;
    }

    public function remove_photo($feedback_id = '') {
        if ($feedback_id != '') {

            $update_array['feedback_img'] = '';
            $update_array['feedback_thumb'] = '';

            $update_result = $this->common->update_data($update_data, 'feedback', 'feedback_id', $id);

            if ($update_result) {
                $this->session->set_flashdata('success', 'Photo successfully removed.');
                redirect('admin/feedbacks/edit/' . $user_id);
            } else {
                $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
                redirect('admin/feedbacks', 'refresh');
            }
        }
    }

    public function remove_video($user_id = '') {
        if ($feedback_id != '') {

            $update_array['feedback_video'] = '';
            $update_array['feedback_thumb'] = '';
            
            $update_result = $this->common->update_data($update_data, 'feedback', 'feedback_id', $id);

            if ($update_result) {
                $this->session->set_flashdata('success', 'Video successfully removed.');
                redirect('admin/feedbacks/edit/' . $user_id);
            } else {
                $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
                redirect('admin/feedbacks', 'refresh');
            }
        }
    }

}

?>
