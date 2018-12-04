<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends MY_Controller {

    public $data;

    public function __construct() {

        parent::__construct();

        //$GLOBALS['record_per_page']=10;
        //site setting details
        $this->load->model('common');

        // Load Library
        $this->load->library('s3');

        $site_name_values = $this->common->select_data_by_id('settings', 'setting_id', '1', '*');

        $this->data['site_name'] = $site_name = $site_name_values[0]['setting_value'];
        //set header, footer and leftmenu
        $this->data['title'] = 'Users | ' . $site_name;

        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    //display users list
    public function index() {

        $this->data['module_name'] = 'Users';
        $this->data['section_title'] = 'Users';
		
		$join_str = array(
			array(
				'table' => 'feedback',
				'join_table_id' => 'feedback.user_id',
				'from_table_id' => 'users.id',
				'join_type' => 'left'
			)
		);

        $contition_array = array('users.deleted' => 0);
        $this->data['user_list'] = $this->common->select_data_by_condition('users', $contition_array, 'users.id, users.name, users.email, users.photo, users.gender, users.dob, users.country, users.status, users.last_login, count(feedback_id) as total_feedback', $short_by = 'id', $order_by = 'ASC', $limit = '', $offset = '', $join_str, $group_by = 'users.id');

        /* Load Template */
        $this->template->admin_render('admin/users/index', $this->data);
    }

    //update the user detail
    public function view($id = '') {

        $users_detail = $this->common->select_data_by_id('users', 'id', $id, '*');
        if ($users_detail[0]['id'] != '') {
            $this->data['module_name'] = 'Users';
            $this->data['section_title'] = 'View Users';
            $this->data['users_detail'] = $users_detail;

            $this->load->view('admin/users/view', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Errorout Occurred. Try Again.');
            redirect('admin/users', 'refresh');
        }
    }
    
    // Reset User Password
    public function reset_password($id = '') {

        $transactions_detail = $this->common->select_data_by_id('user_transactions', 'user_id', $id, '*');
        
        if (count($transactions_detail) > 0) {
            $this->data['module_name'] = 'View User Transaction';
            $this->data['section_title'] = 'View User Transaction';
            $this->data['transactions_detail'] = $transactions_detail;

            $this->load->view('users/transaction', $this->data);
        } else {
            $this->session->set_flashdata('error', 'No transaction found.');
            redirect('admin/users', 'refresh');
        }
    }
    
    // users status change
    public function change_status($users_id = '', $status = '') {
        if ($users_id == '' || $status == '') {
            $this->session->set_flashdata('error', 'Error Occurred. Try Agaim!');
            redirect('admin/users', 'refresh');
        }
        if ($status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $update_data = array('status' => $status);

        $update_result = $this->common->update_data($update_data, 'users', 'id', $users_id);
        if ($update_result) {
            $this->session->set_flashdata('success', 'Users status successfully updated');
            redirect('admin/users', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
            redirect('admin/users', 'refresh');
        }
    }
	
	//feedbacks by a user
    public function feedbacks($user_id = '') {
		$this->data['module_name'] = 'Feedbacks';
        $this->data['section_title'] = 'Feedbacks';

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
		
		// $contition_array = array('replied_to' => NULL, 'deleted' => 0);
        $contition_array = array('feedback.deleted' => 0, 'users.id' => $user_id);
		
		$data = 'feedback_id, feedback.title_id, title, name, email, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video, replied_to, location, feedback.status, feedback.datetime as time';
		
		$this->data['feedback_list'] = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $limit = '', $offset = '', $join_str, $group_by = '');

        /* Load Template */
        $this->template->admin_render('admin/feedbacks/index', $this->data);
	}

    //add new user
    public function add() {
        //check post and save data
        if ($this->input->post('btn_save')) {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');

            if ($this->form_validation->run() === FALSE){
                $this->session->set_flashdata('error', validation_errors());
                redirect('admin/users');
            } else {

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
                     // echo $s3file = S3_CDN.$config_thumb['source_image'];
                     // echo "<br/>";
                     // echo $s3file = S3_CDN.$thumb_file_name; exit();

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
                        // $main_old_file = $this->config->item('user_main_upload_path') . $user_data[0]['photo'];
                        // $thumb_old_file = $this->config->item('user_thumb_upload_path') . $user_data[0]['photo'];

                        /*    if (file_exists($main_old_file)) {
                          unlink($main_old_file);
                          }
                          if (file_exists($thumb_old_file)) {
                          unlink($thumb_old_file);
                          } */
                        $error = array();
                    }

                    if ($error) {
                        $this->session->set_flashdata('error', $error[0]);
                        redirect('admin/users', 'refresh');
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
                    redirect('admin/users', 'refresh');
                } else {
                    $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
                    redirect('admin/users', 'refresh');
                }
            }
        }

        $this->data['module_name'] = 'User Management';
        $this->data['section_title'] = 'Add User';
		$this->data['country_list'] = $this->common->select_data_by_condition('countries', $contition_array = array(), '*', $short_by = 'country_name', $order_by = 'ASC', $limit = '', $offset = '');
		
        /* Load Template */
        $this->template->admin_render('admin/users/add', $this->data);
    }

    //update the user detail
    public function edit($id = '') {

        if ($this->input->post('user_id')) {
			
			$dataimage = '';

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
                 // echo $s3file = S3_CDN.$config_thumb['source_image'];
                 // echo "<br/>";
                 // echo $s3file = S3_CDN.$thumb_file_name; exit();

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
                    // $main_old_file = $this->config->item('user_main_upload_path') . $user_data[0]['photo'];
                    // $thumb_old_file = $this->config->item('user_thumb_upload_path') . $user_data[0]['photo'];

                    /*    if (file_exists($main_old_file)) {
                      unlink($main_old_file);
                      }
                      if (file_exists($thumb_old_file)) {
                      unlink($thumb_old_file);
                      } */
                    $error = array();
                }

                if ($error) {
                    $this->session->set_flashdata('error', $error[0]);
                    redirect('users', 'refresh');
                }
            }
          
            $update_array = array(
				'name' => trim($this->input->post('name')),
				'email' => trim($this->input->post('email')),
				'gender' => $this->input->post('gender'),
				'country' => $this->input->post('country'),
                'modify_date' => date('Y-m-d H:i:s')
            );

			if($this->input->post('dob') !== '') {
				$update_array['dob'] = $this->input->post('dob');
			}
			
			if($this->input->post('reset_password') != '') {
				$update_array['password'] = md5($this->input->post('reset_password'));
			}
			
            if($dataimage) {
                $update_array['photo'] = $dataimage;
            }
            
            $update_result = $this->common->update_data($update_array, 'users', 'id', $this->input->post('user_id'));
           
            if ($update_result) {
                $this->session->set_flashdata('success', 'User successfully updated.');
                redirect('admin/users', 'refresh');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
                redirect('admin/users', 'refresh');
            }
        }

        $user_detail = $this->common->select_data_by_id('users', 'id', $id, '*');
        if (!empty($user_detail)) {
            $this->data['module_name'] = 'User Management';
            $this->data['section_title'] = 'Edit User';
            $this->data['user_detail'] = $user_detail;
			$this->data['country_list'] = $this->common->select_data_by_condition('countries', $contition_array = array(), '*', $short_by = 'country_name', $order_by = 'ASC', $limit = '', $offset = '');

            /* Load Template */
            $this->template->admin_render('admin/users/edit', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
            redirect('admin/users', 'refresh');
        }
    }

    // users delete
    public function delete($id = '') {
        //$delete_result = $this->common->delete_data('users', 'id', $id);
        $update_data = array('deleted' => 1);
        $update_result = $this->common->update_data($update_data, 'users', 'id', $id);

        if ($update_result) {
            $this->session->set_flashdata('success', 'Users successfully deleted');
            redirect('admin/users', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
            redirect('admin/users', 'refresh');
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

    public function remove_photo($user_id = '') {
        if ($user_id != '') {

            $get_image = $this->common->select_data_by_id('users', 'id', $user_id, $data = 'photo', $join_str = array());
            $image_name = $get_image[0]['photo'];

            $main_image = $this->config->item('user_main_upload_path') . $image_name;
            $thumb_image = $this->config->item('user_thumb_upload_path') . $image_name;

            if (file_exists($main_image)) {
                unlink($main_image);
            }
            if (file_exists($thumb_image)) {
                unlink($thumb_image);
            }

            $update_array['photo'] = '';
            $this->common->update_data($update_array, 'users', 'id', $user_id);

            $this->session->set_flashdata('success', 'Photo successfully removed.');
            redirect('admin/users/edit/' . $user_id);
        }
    }

}

?>
