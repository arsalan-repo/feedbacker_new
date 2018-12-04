<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pages extends MY_Controller {

    public $data;

    public function __construct() {

        parent::__construct();

        //$GLOBALS['record_per_page']=10;
        //site setting details
        $site_name_values = $this->common->select_data_by_id('settings', 'setting_id', '1', '*');
        $this->data['site_name'] = $site_name = $site_name_values[0]['setting_value'];
        //set header, footer and leftmenu
        $this->data['title'] = 'Pages | ' . $site_name;
        $this->data['header'] = $this->load->view('header', $this->data);
        $this->data['leftmenu'] = $this->load->view('leftmenu', $this->data);
        $this->data['footer'] = $this->load->view('footer', $this->data, true);


        //remove catch so after logout cannot view last visited page if that page is this
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

    //Display Pages List
    public function index() {

        $this->data['module_name'] = 'Pages';
        $this->data['section_title'] = 'Pages';

        $contition_array = array();
        $this->data['pages_list'] = $this->common->select_data_by_condition('pages', $contition_array, '*', $short_by = '', $order_by = '', $limit = '', $offset = '');

        $this->load->view('pages/index', $this->data);
    }

    //Update Pages Data
    public function edit($id = '') {

        if ($this->input->post('page_id')) {

            $page_id = $this->input->post('page_id');
            if ($this->input->post('name') == '') {
                $this->session->set_flashdata('error', 'Page name is required');
                redirect('pages/edit/' . $page_id, 'refresh');
            }
        /*    if ($this->input->post('description') == '') {
                $this->session->set_flashdata('error', 'Page description is required');
                redirect('pages/edit/' . $page_id, 'refresh');
            }
            
         */   
            if ($_FILES['image']['name'] != '' && $_FILES['image']['error'] == 0) {
                $page_image = '';

                $page['upload_path'] = $this->config->item('page_main_upload_path');
                $page['allowed_types'] = $this->config->item('page_allowed_types');
                $page['max_size'] = $this->config->item('page_main_max_size');
                $page['max_width'] = $this->config->item('page_main_max_width');
                $page['max_height'] = $this->config->item('page_main_max_height');


                $this->load->library('upload');
                $this->upload->initialize($page);
                //Uploading Image
                $this->upload->do_upload('image');
                //Getting Uploaded Image File Data
                $imgdata = $this->upload->data();
                $imgerror = $this->upload->display_errors();
                if ($imgerror == '') {
                    //Configuring Thumbnail 
                    $page_thumb['image_library'] = 'gd2';
                    $page_thumb['source_image'] = $page['upload_path'] . $imgdata['file_name'];
                    $page_thumb['new_image'] = $this->config->item('page_thumb_upload_path') . $imgdata['file_name'];
                    $page_thumb['create_thumb'] = TRUE;
                    $page_thumb['maintain_ratio'] = TRUE;
                    $page_thumb['thumb_marker'] = '';
                    $page_thumb['width'] = $this->config->item('page_thumb_width');
                    //$page_thumb['height'] = $this->config->item('page_thumb_height');
                    $page_thumb['height'] = 2;
                    $page_thumb['master_dim'] = 'width';
                    $page_thumb['quality'] = "100%";
                    $page_thumb['x_axis'] = '0';
                    $page_thumb['y_axis'] = '0';
                    //Loading Image Library
                    $this->load->library('image_lib', $page_thumb);
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
                    $redirect_url = site_url('pages');
                    redirect($redirect_url, 'refresh');
                } else {
                    $page = $imgdata['file_name'];
                    
                    $old_main_image = $this->config->item('page_main_upload_path') . $this->input->post('old_image');
                    $old_thumb_image = $this->config->item('page_thumb_upload_path') . $this->input->post('old_image');
                    if (isset($old_main_image)) {
                        unlink($old_main_image);
                    }
                    if (isset($old_thumb_image)) {
                        unlink($old_thumb_image);
                    }
                }
            } else {
                $page = $this->input->post('old_image');
            }
            
            if ($_FILES['app_image']['name'] != '' && $_FILES['app_image']['error'] == 0) {
                $page_image1 = '';

                $page1['upload_path'] = $this->config->item('page_app_main_upload_path');
                $page1['allowed_types'] = $this->config->item('page_app_allowed_types');
                $page1['max_size'] = $this->config->item('page_app_main_max_size');
                $page1['max_width'] = $this->config->item('page_app_main_max_width');
                $page1['max_height'] = $this->config->item('page_app_main_max_height');


                $this->load->library('upload');
                $this->upload->initialize($page1);
                //Uploading Image
                $this->upload->do_upload('app_image');
                //Getting Uploaded Image File Data
                $imgdata1 = $this->upload->data();
                $imgerror = $this->upload->display_errors();
                if ($imgerror == '') {
                    //Configuring Thumbnail 
                    $page_thumb1['image_library'] = 'gd2';
                    $page_thumb1['source_image'] = $page1['upload_path'] . $imgdata1['file_name'];
                    $page_thumb1['new_image'] = $this->config->item('page_app_thumb_upload_path') . $imgdata1['file_name'];
                    $page_thumb1['create_thumb'] = TRUE;
                    $page_thumb1['maintain_ratio'] = TRUE;
                    $page_thumb1['thumb_marker'] = '';
                    $page_thumb1['width'] = $this->config->item('page_app_thumb_width');
                    //$page_thumb['height'] = $this->config->item('page_thumb_height');
                    $page_thumb1['height'] = 2;
                    $page_thumb1['master_dim'] = 'width';
                    $page_thumb1['quality'] = "100%";
                    $page_thumb1['x_axis'] = '0';
                    $page_thumb1['y_axis'] = '0';
                    //Loading Image Library
                    $this->load->library('image_lib', $page_thumb1);
                    $dataimage = $imgdata1['file_name'];
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
                    $redirect_url = site_url('pages');
                    redirect($redirect_url, 'refresh');
                } else {
                    $page1 = $imgdata1['file_name'];

                    $old_main_image = $this->config->item('page_app_main_upload_path') . $this->input->post('old_app_image');
                    $old_thumb_image = $this->config->item('page_app_thumb_upload_path') . $this->input->post('old_app_image');
                    if (isset($old_main_image)) {
                        unlink($old_main_image);
                    }
                    if (isset($old_thumb_image)) {
                        unlink($old_thumb_image);
                    }
                }
            } else {
                $page1 = $this->input->post('old_app_image');
            }
            
            
            $update_array = array(
                'name' => trim($this->input->post('name')),
                'title' => trim($this->input->post('title')),
                'description' => trim($this->input->post('description')),
                'description1' => trim($this->input->post('description1')),
                'image' => $page,
                'app_image' => $page1,
                'modify_date' => date('Y-m-d h:i:s')
            );
            
            $update_result = $this->common->update_data($update_array, 'pages', 'id', $this->input->post('page_id'));

            $redirect_url = site_url('pages');

            if ($update_result) {

                $this->session->set_flashdata('success', 'Pages successfully updated.');
                redirect($redirect_url, 'refresh');
            } else {
                $this->session->set_flashdata('error', 'Error in Occurred. Try Again!');
                redirect($redirect_url, 'refresh');
            }
        }

        $pages_detail = $this->common->select_data_by_id('pages', 'id', $id, 'id,name,title as page_title,description,description1,image,app_image');
        if (!empty($pages_detail)) {
            $this->data['module_name'] = 'Pages';
            $this->data['section_title'] = 'Edit Page';

            $this->data['pages_detail'] = $pages_detail;
            $this->load->view('pages/edit', $this->data);
        } else {
            $this->session->set_flashdata('error', 'Errorout Occurred. Try Again.');
            redirect('pages', 'refresh');
        }
    }

    public function changestatus($id = '', $status = '') {
        if ($status == "blocked") {
            $status = 'unblocked';
        } else {
            $status = 'blocked';
        }

        $update_array = array('status' => $status);
        $delete_result = $this->common->update_data($update_array, 'pages', 'page_id', $id);

        if (isset($_SERVER['HTTP_REFERER'])) {
            $redirect_url = $_SERVER['HTTP_REFERER'];
        } else {
            $redirect_url = site_url('pages');
        }
        if ($delete_result) {

            //$this->session->set_flashdata('success', 'Pages successfully deleted.');
            redirect($redirect_url, 'refresh');
        } else {
            //$this->session->set_flashdata('error', 'Error Occurred. Try Again!');
            redirect($redirect_url, 'refresh');
        }
    }

}

?>