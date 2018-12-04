<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ads extends MY_Controller {

    public $data;

    public function __construct() {

        parent::__construct();

        //$GLOBALS['record_per_page']=10;
        //site setting details
        $this->load->model('common');
		$this->load->model('encrypted_model');
        // Load Library
        $this->load->library('s3');

        $site_name_values = $this->common->select_data_by_id('settings', 'setting_id', '1', '*');

        $this->data['site_name'] = $site_name = $site_name_values[0]['setting_value'];
        //set header, footer and leftmenu
        $this->data['title'] = 'Ads | ' . $site_name;

        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    //display ads list
    public function index() {

        $this->data['module_name'] = 'Ads';
        $this->data['section_title'] = 'Ads';
		
		$join_str = array(
			array(
				'table' => 'titles',
				'join_table_id' => 'titles.title_id',
				'from_table_id' => 'ads.title_id',
				'join_type' => 'left'
			)
		);

        $contition_array = array('ads.deleted' => 0);
		
		$data = 'ads_id, ads.title_id, title, usr_name, usr_img, ads_cont, ads_img, ads_thumb, ads_video, ads.country, ads.show_on, ads.status, ads.datetime as time';
		
        $this->data['ads_list'] = $this->common->select_data_by_condition('ads', $contition_array, $data, $short_by = 'ads.datetime', $order_by = 'DESC', $limit = '', $offset = '', $join_str, $group_by = '');

        /* Load Template */
        $this->template->admin_render('admin/ads/index', $this->data);
    }
    
	// ads status change
    public function visibility($ads_id = '', $status = '') {
        if ($ads_id == '' || $status == '') {
            $this->session->set_flashdata('error', 'Error Occurred. Try Agaim!');
            redirect('admin/ads', 'refresh');
        }
        if ($status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $update_data = array('status' => $status);

        $update_result = $this->common->update_data($update_data, 'ads', 'ads_id', $ads_id);
        if ($update_result) {
            $this->session->set_flashdata('success', 'Ad status successfully updated');
            redirect('admin/ads', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
            redirect('admin/ads', 'refresh');
        }
    }
	
	//add new banner
    public function add() {
        //check post and save data
        if ($this->input->post('btn_save')) {
			
			//upload user image
			if (isset($_FILES['usr_image']['name']) && $_FILES['usr_image']['name'] != '') {
				$config['upload_path'] = $this->config->item('user_main_upload_path');
				$config['thumb_upload_path'] = $this->config->item('user_thumb_upload_path');
				$config['allowed_types'] = 'jpg|png|jpeg|gif';
				$config['file_name'] = time();

				$this->load->library('upload');
				$this->upload->initialize($config);
				
				//Uploading Image
				$this->upload->do_upload('usr_image');
				
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
					$error = array();
				}

				if ($error) {
					$this->session->set_flashdata('error', $error[0]);
					redirect('admin/ads');
				}
			}
			
			//upload banner image
			if (isset($_FILES['ads_image']['name']) && $_FILES['ads_image']['name'] != '') {
                $adconfig['upload_path'] = $this->config->item('feedback_main_upload_path');
                $adconfig['thumb_upload_path'] = $this->config->item('feedback_thumb_upload_path');
                $adconfig['allowed_types'] = $this->config->item('feedback_allowed_types');
                $adconfig['max_size'] = $this->config->item('feedback_main_max_size');
                $adconfig['max_width'] = $this->config->item('feedback_main_max_width');
                $adconfig['max_height'] = $this->config->item('feedback_main_max_height');
                $adconfig['file_name'] = time();
    
				$this->load->library('upload');
				$this->upload->initialize($adconfig);
    
                // Uploading Image
                if (!$this->upload->do_upload('ads_image')) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('error', $error);
					redirect('admin/ads');
                } else {
                    // Getting Uploaded Image File Data
                    $adsdata = $this->upload->data();                   
                    $ads_img = $adsdata['file_name'];

                    // Configuring Thumbnail 
                    $ad_thumb['image_library'] = 'gd2';
                    $ad_thumb['source_image'] = $adconfig['upload_path'] . $adsdata['file_name'];
                    $ad_thumb['new_image'] = $adconfig['thumb_upload_path'] . $adsdata['file_name'];
                    $ad_thumb['create_thumb'] = TRUE;
                    $ad_thumb['maintain_ratio'] = TRUE;
                    $ad_thumb['thumb_marker'] = '_thumb';
                    $ad_thumb['width'] = $this->config->item('feedback_thumb_width');
                    $ad_thumb['height'] = $this->config->item('feedback_thumb_height');

                    // Loading Image Library
                    $this->load->library('image_lib', $ad_thumb);

                    // Creating Thumbnail
					$this->image_lib->clear();
					$this->image_lib->initialize($ad_thumb);
					
                    if(!$this->image_lib->resize()) {
                        $error = array('error' => $this->image_lib->display_errors());
                        echo json_encode(array('RESULT' => $error, 'MESSAGE' => 'ERROR', 'STATUS' => 0));
                        exit();
                    } else {
                        $ads_thumb = $adsdata['raw_name'].'_thumb'.$adsdata['file_ext'];
                    }
                    
                    // AWS S3 Upload
                    $thumb_file_path = str_replace("main", "thumbs", $adsdata['file_path']);
                    $thumb_file_name = $adconfig['thumb_upload_path'] . $adsdata['raw_name'].'_thumb'.$adsdata['file_ext'];
                    
                    $this->s3->putObjectFile($adsdata['full_path'], S3_BUCKET, $ad_thumb['source_image'], S3::ACL_PUBLIC_READ);
                    $this->s3->putObjectFile($thumb_file_path.$ads_thumb, S3_BUCKET, $thumb_file_name, S3::ACL_PUBLIC_READ);

                    // Remove File from Local Storage
                    unlink($ad_thumb['source_image']);
                    unlink($thumb_file_name);
                }
			}
			
			$insert_array = array(
				'usr_name' => trim($this->input->post('usr_name')),
				'ads_cont' => trim($this->input->post('ads_cont')),
				'country' => $this->input->post('country'),
				'show_on' => $this->input->post('show_on'),
				'show_after' => $this->input->post('show_after'),
				'repeat_for' => $this->input->post('repeat_for'),
				'datetime' => date('Y-m-d H:i:s'),
				'status' => 1
			);
			
			if($this->input->post('show_on') == 'home' || $this->input->post('show_on') == 'search') {
				$insert_array['title_id'] = '';
			} else {
				$insert_array['title_id'] = $this->input->post('title_id');
			}
			if($this->input->post('ads_url') != '') {
                $insert_array['ads_url'] = $this->input->post('ads_url');
            }
			if($dataimage) {
				$insert_array['usr_img'] = $dataimage;
			}
			if($ads_img != '') {
                $insert_array['ads_img'] = $ads_img;
            }
            if($ads_thumb != '') {
                $insert_array['ads_thumb'] = $ads_thumb;
            }
			
			$insert_result = $this->common->insert_data($insert_array, 'ads');

            if ($insert_result) {
				$this->session->set_flashdata('success', 'Banner successfully inserted.');
				redirect('admin/ads');
			} else {
				$this->session->set_flashdata('error', 'Error Occurred. Try Again!');
				redirect('admin/ads');
			}
        }

        $this->data['module_name'] = 'Ads Management';
        $this->data['section_title'] = 'New Banner';
		$this->data['country_list'] = $this->common->select_data_by_condition('countries', $contition_array = array(), '*', $short_by = 'country_name', $order_by = 'ASC', $limit = '', $offset = '');
		
		$contition_array = array('titles.deleted' => 0);
		$this->data['title_list'] = $this->common->select_data_by_condition('titles', $contition_array, $data = 'title_id, title', $sortby = 'titles.title', $orderby = 'ASC', $limit = '', $offset = '', $join_str = array(), $group_by = 'titles.title_id');
		
		/*$contition_array = array('encrypted_titles.deleted' => 0,'encrypted_titles.is_survey'=>0);
		$this->data['encrypted_title_list'] = $this->common->select_data_by_condition('encrypted_titles', $contition_array, $data = 'title_id, title', $sortby = 'encrypted_titles.title', $orderby = 'ASC', $limit = '', $offset = '', $join_str = array(), $group_by = 'encrypted_titles.title_id');*/
		$this->data['encrypted_title_list']=$this->encrypted_model->get_encrypted(0,-1);
        /* Load Template */
        $this->template->admin_render('admin/ads/add', $this->data);
    }

    //update the ad detail
    public function edit($ads_id = '') {

        if ($this->input->post('ads_id')) {
			
			//upload user image
			if (isset($_FILES['usr_image']['name']) && $_FILES['usr_image']['name'] != '') {
				$config['upload_path'] = $this->config->item('user_main_upload_path');
				$config['thumb_upload_path'] = $this->config->item('user_thumb_upload_path');
				$config['allowed_types'] = 'jpg|png|jpeg|gif';
				$config['file_name'] = time();

				$this->load->library('upload');
				$this->upload->initialize($config);
				
				//Uploading Image
				$this->upload->do_upload('usr_image');
				
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
					$error = array();
				}

				if ($error) {
					$this->session->set_flashdata('error', $error[0]);
					redirect('admin/ads');
				}
			}
			
			//upload banner image
			if (isset($_FILES['ads_image']['name']) && $_FILES['ads_image']['name'] != '') {
                $config['upload_path'] = $this->config->item('feedback_main_upload_path');
                $config['thumb_upload_path'] = $this->config->item('feedback_thumb_upload_path');
                $config['allowed_types'] = $this->config->item('feedback_allowed_types');
                $config['max_size'] = $this->config->item('feedback_main_max_size');
                $config['max_width'] = $this->config->item('feedback_main_max_width');
                $config['max_height'] = $this->config->item('feedback_main_max_height');
                $config['file_name'] = time();
    
                $this->load->library('upload', $config);
    
                // Uploading Image
                if (!$this->upload->do_upload('ads_image')) {
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('error', $error);
					redirect('admin/ads');
                } else {
                    // Getting Uploaded Image File Data
                    $imgdata = $this->upload->data();                   
                    $ads_img = $imgdata['file_name'];

                    // Configuring Thumbnail 
                    $config_thumb['image_library'] = 'gd2';
                    $config_thumb['source_image'] = $config['upload_path'] . $imgdata['file_name'];
                    $config_thumb['new_image'] = $config['thumb_upload_path'] . $imgdata['file_name'];
                    $config_thumb['create_thumb'] = TRUE;
                    $config_thumb['maintain_ratio'] = TRUE;
                    $config_thumb['thumb_marker'] = '_thumb';
                    $config_thumb['width'] = $this->config->item('feedback_thumb_width');
                    $config_thumb['height'] = $this->config->item('feedback_thumb_height');

                    // Loading Image Library
                    $this->load->library('image_lib', $config_thumb);

                    // Creating Thumbnail
                    if(!$this->image_lib->resize()) {
                        $error = array('error' => $this->image_lib->display_errors());
                        echo json_encode(array('RESULT' => $error, 'MESSAGE' => 'ERROR', 'STATUS' => 0));
                        exit();
                    } else {
                        $ads_thumb = $imgdata['raw_name'].'_thumb'.$imgdata['file_ext'];
                    }
                    
                    // AWS S3 Upload
                    $thumb_file_path = str_replace("main", "thumbs", $imgdata['file_path']);
                    $thumb_file_name = $config['thumb_upload_path'] . $imgdata['raw_name'].'_thumb'.$imgdata['file_ext'];
                    
                    $this->s3->putObjectFile($imgdata['full_path'], S3_BUCKET, $config_thumb['source_image'], S3::ACL_PUBLIC_READ);
                    $this->s3->putObjectFile($thumb_file_path.$ads_thumb, S3_BUCKET, $thumb_file_name, S3::ACL_PUBLIC_READ);

                    // Remove File from Local Storage
                    unlink($config_thumb['source_image']);
                    unlink($thumb_file_name);
                }
			}
			
			$update_array = array(
				'usr_name' => trim($this->input->post('usr_name')),
				'ads_cont' => trim($this->input->post('ads_cont')),
				'country' => $this->input->post('country'),
				'show_on' => $this->input->post('show_on'),
				'show_after' => $this->input->post('show_after'),
				'repeat_for' => $this->input->post('repeat_for')
			);
			
			if($this->input->post('show_on') == 'home' || $this->input->post('show_on') == 'search') {
				$update_array['title_id'] = '';
			} else {
				$update_array['title_id'] = $this->input->post('title_id');
			}
			if($this->input->post('ads_url') != '') {
                $update_array['ads_url'] = $this->input->post('ads_url');
            }
			if(isset($dataimage)) {
				$update_array['usr_img'] = $dataimage;
			}
			if(isset($ads_img)) {
                $update_array['ads_img'] = $ads_img;
            }
            if(isset($ads_thumb)) {
                $update_array['ads_thumb'] = $ads_thumb;
            }
          
            $update_result = $this->common->update_data($update_array, 'ads', 'ads_id', $this->input->post('ads_id'));
           
            if ($update_result) {
                $this->session->set_flashdata('success', 'Ad successfully updated.');
                redirect('admin/ads', 'refresh');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
                redirect('admin/ads', 'refresh');
            }
        }

        $ads_detail = $this->common->select_data_by_id('ads', 'ads_id', $ads_id, '*');
		
        if (!empty($ads_detail)) {
            $this->data['module_name'] = 'Manage Ads';
            $this->data['section_title'] = 'Edit Banner';
            $this->data['ads_detail'] = $ads_detail;
			
			$this->data['country_list'] = $this->common->select_data_by_condition('countries', $contition_array = array(), '*', $short_by = 'country_name', $order_by = 'ASC', $limit = '', $offset = '');
			$contition_array = array('titles.deleted' => 0);
			$this->data['title_list'] = $this->common->select_data_by_condition('titles', $contition_array, $data = 'title_id, title', $sortby = 'titles.title', $orderby = 'ASC', $limit = '', $offset = '',$join_str = array(), $group_by = 'titles.title_id');
			/*$contition_array = array('encrypted_titles.deleted' => 0,'encrypted_titles.is_survey'=>0);
		$this->data['encrypted_title_list'] = $this->common->select_data_by_condition('encrypted_titles', $contition_array, $data = 'title_id, title', $sortby = 'encrypted_titles.title', $orderby = 'ASC', $limit = '', $offset = '', $join_str = array(), $group_by = 'encrypted_titles.title_id');*/
		
		  	
			$this->data['encrypted_title_list']=$this->encrypted_model->get_encrypted(0,-1);
            /* Load Template */
            $this->template->admin_render('admin/ads/edit', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong! Please try Again.');
            redirect('admin/ads', 'refresh');
        }
    }

    //ad delete
    public function delete($ads_id = '') {

        $update_data = array('deleted' => 1);
        $update_result = $this->common->update_data($update_data, 'ads', 'ads_id', $ads_id);

        if ($update_result) {
            $this->session->set_flashdata('success', 'Ad successfully deleted');
            redirect('admin/ads', 'refresh');
        } else {
            $this->session->set_flashdata('error', 'Error Occurred. Try Again!');
            redirect('admin/ads', 'refresh');
        }
    }

    public function remove_photo($ads_id = '') {
        if ($ads_id != '') {

            $get_image = $this->common->select_data_by_id('ads', 'ads_id', $ads_id, $data = 'ads_img', $join_str = array());
            $image_name = $get_image[0]['ads_img'];

            $main_image = $this->config->item('feedback_main_upload_path') . $image_name;
            $thumb_image = $this->config->item('feedback_thumb_upload_path') . $image_name;

            if (file_exists($main_image)) {
                unlink($main_image);
            }
            if (file_exists($thumb_image)) {
                unlink($thumb_image);
            }

            $update_array['ads_img'] = '';
            $this->common->update_data($update_array, 'ads', 'ads_id', $ads_id);

            $this->session->set_flashdata('success', 'Banner successfully removed.');
            redirect('admin/ads/edit/' . $ads_id);
        }
    }

}

?>
