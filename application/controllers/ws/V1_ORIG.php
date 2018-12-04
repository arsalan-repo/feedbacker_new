<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class V1 extends CI_Controller {

    //define global variables
    function __construct() {
        parent::__construct();
        $this->load->model('common');
        $this->load->model('pushNotifications');    
        
        // Load Library
        $this->load->library('s3'); 
        
        // Load Language File       
        if($this->input->post('language') == 'ar') {
            $this->lang->load('message','arabic');
        } else {
            $this->lang->load('message','english');
        }

        $GLOBALS['error_code'] = 0;
        $GLOBALS['api_debug_mode'] = false;

        date_default_timezone_set('Asia/Kolkata');
    }

    // Sign Up
    function signup() {
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');
        $country = $this->input->post('country');
        $language = $this->input->post('language');
        $device_type = $this->input->post('device_type');
        $token_key = $this->input->post('token_key');
        $fbid = $this->input->post('fbid');
        $twitterid = $this->input->post('twitterid');
        $is_social = $this->input->post('is_social');

        $error = '';

        if ($name == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_name'), 'STATUS' => 0));
            die();
        }
        if ($email == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_email'), 'STATUS' => 0));
            die();
        }
        if ($password == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_pass'), 'STATUS' => 0));
            die();
        }
        if ($confirm_password == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_cpass'), 'STATUS' => 0));
            die();
        }
        if ($confirm_password !== $password) {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_pass_not_match'), 'STATUS' => 0));
            die();
        }
        if ($country == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_select_country'), 'STATUS' => 0));
            die();
        }
        if ($device_type == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_select_device_type'), 'STATUS' => 0));
            die();
        }
        if ($email != '') {
            $condition_array = array('deleted' => 0, 'status !=' => 3);
            $check_result = $this->common->check_unique_avalibility('users', 'email', $email, '', '', $condition_array);

            if ($check_result == 1) {
                // CHECK IF LOGIN WITH FACEBOOK/TWITTER
                if($is_social == true) {
                    // GET USER INFORMATION
                    $contition_array_user = array('email' => $email);
                    $user_result = $this->common->select_data_by_condition('users', $contition_array_user, $data = '*', $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str = array());
                    // Check If user account is blocked by administrator
                    if ($user_result[0]['status'] == "0") {
                        echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_account_blocked'), 'STATUS' => 0));
                        exit();
                    }

                    // UPDATE FBID
                    if ($fbid != '') {
                        $data = array('fbid' => trim($fbid));
                        $this->common->update_data($data, 'users', 'id', $user_result[0]['id']);
                    }

                    // UPDATE TWITTER ID
                    if ($twitterid != '') {
                        $data = array('twitterid' => trim($twitterid));
                        $this->common->update_data($data, 'users', 'id', $user_result[0]['id']);
                    }
                    
                    // UPDATE DEVICE TYPE
                    if ($device_type != '') {
                        $data = array('device_type' => trim($device_type));
                        $this->common->update_data($data, 'users', 'id', $user_result[0]['id']);
                    }

                    // UPDATE TOKEN KEY
                    if ($token_key != '') {
                        $data = array('token_key' => trim($token_key));
                        $this->common->update_data($data, 'users', 'id', $user_result[0]['id']);
                    }

                    // RETURN USER INFORMATION
                    $return_array['id'] = $user_result[0]['id'];
                    $return_array['name'] = $user_result[0]['name'];
                    $return_array['email'] = $user_result[0]['email'];
                    if(isset($user_result[0]['photo'])) {
                        $return_array['user_avatar'] = S3_CDN . 'uploads/user/thumbs/' . $user_result[0]['photo'];
                    } else {
                        $return_array['user_avatar'] = ASSETS_URL . 'images/user-avatar.png';
                    }
                    if ($fbid != '') {
                        $return_array['fbid'] = trim($fbid);
                    }
                    if ($twitterid != '') {
                        $return_array['twitterid'] = trim($twitterid);
                    }
                    $return_array['country'] = $user_result[0]['country'];
                    $return_array['device_type'] = $user_result[0]['device_type'];
                    if ($user_result[0]['token_key'] != '') {
                        $return_array['token_key'] = $user_result[0]['token_key'];
                    }
                    
                    if($language != '') {
                        $return_array['language'] = $language;
                        
                        // Get language id from code
                        $contition_array = array('lang_code' => $language);
                        $languages = $this->common->select_data_by_condition('languages', $contition_array, $data = 'lang_id');
                        
                        $update_data = array('lang_id' => $languages[0]['lang_id']);
                        $this->common->update_data($update_data, 'users', 'id', $user_result[0]['id']);
                    } else {
                        $languages = $this->common->select_data_by_id('languages', 'lang_id', $user_result[0]['lang_id'], $data = 'lang_code', $join_str = array());
                        $return_array['language'] = $languages[0]['lang_code'];
                        
                        if($languages[0]['lang_code'] == 'ar') {
                            $this->lang->load('message','arabic');
                        }
                    }

                    // Null to Empty String
                    array_walk_recursive($return_array, function (&$item, $key) {
                        $item = null === $item ? '' : $item;
                    });

                    echo json_encode(array('RESULT' => $return_array, 'MESSAGE' => $this->lang->line('msg_login_success'), 'STATUS' => 1));
                    die();
                } else {
                    $error = 1;
                    echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_email_exits'), 'STATUS' => 0));
                    die();
                }
            }
        }

        if ($error == 1) {
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_something_wrong'), 'STATUS' => 0));
            die();
        } else {

            $md5_password = md5($password);
            $enc_pwd = base64_encode($password);

            $insert_array['name'] = $name;
            $insert_array['email'] = trim($email);
            $insert_array['password'] = trim($md5_password);
            if ($fbid != '') {
                $insert_array['fbid'] = trim($fbid);
            }
            if ($twitterid != '') {
                $insert_array['twitterid'] = trim($twitterid);
            }
            $insert_array['country'] = $country;
            $insert_array['device_type'] = $device_type;
            $insert_array['token_key'] = $token_key;
            $insert_array['status'] = 1;
            $insert_array['create_date'] = date('Y-m-d h:i:s');
            $insert_array['modify_date'] = date('Y-m-d h:i:s');

            $insert_result = $this->common->insert_data_getid($insert_array, $tablename = 'users');

            // Add User Notifications Preferences
            $insert_pref_1['user_id'] = $insert_result;
            $insert_pref_1['notification_id'] = 1;
            $insert_pref_1['status'] = 'on';
            $insert_pref_1['updated_on'] = date('Y-m-d h:i:s');
            
            $pref_result_1 = $this->common->insert_data($insert_pref_1, $tablename = 'user_preferences');
            
            $insert_pref_2['user_id'] = $insert_result;
            $insert_pref_2['notification_id'] = 2;
            $insert_pref_2['status'] = 'on';
            $insert_pref_2['updated_on'] = date('Y-m-d h:i:s');
            
            $pref_result_2 = $this->common->insert_data($insert_pref_2, $tablename = 'user_preferences');
            
            $insert_pref_3['user_id'] = $insert_result;
            $insert_pref_3['notification_id'] = 3;
            $insert_pref_3['status'] = 'on';
            $insert_pref_3['updated_on'] = date('Y-m-d h:i:s');
            
            $pref_result_3 = $this->common->insert_data($insert_pref_3, $tablename = 'user_preferences');
            
            $insert_pref_4['user_id'] = $insert_result;
            $insert_pref_4['notification_id'] = 4;
            $insert_pref_4['status'] = 'on';
            $insert_pref_4['updated_on'] = date('Y-m-d h:i:s');
            
            $pref_result_4 = $this->common->insert_data($insert_pref_4, $tablename = 'user_preferences');

            $contition_array = array('status' => '1', 'id' => $insert_result);
            $user_result = $this->common->select_data_by_condition('users', $contition_array, $data = '*', $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str = array());

            $return_array['id'] = $user_result[0]['id'];
            $return_array['name'] = $user_result[0]['name'];
            $return_array['email'] = $user_result[0]['email'];
            $return_array['user_avatar'] = ASSETS_URL . 'images/user-avatar.png';
            $return_array['fbid'] = $user_result[0]['fbid'];
            $return_array['twitterid'] = $user_result[0]['twitterid'];
            $return_array['country'] = $user_result[0]['country'];
            $return_array['device_type'] = $user_result[0]['device_type'];
            if ($user_result[0]['token_key'] != '') {
                $return_array['token_key'] = $user_result[0]['token_key'];
            }
            
            if($language != '') {
                $return_array['language'] = $language;
            } else {
                $return_array['language'] = 'en';
            }

            // Null to Empty String
            array_walk_recursive($return_array, function (&$item, $key) {
                $item = null === $item ? '' : $item;
            });

            echo json_encode(array('RESULT' => $return_array, 'MESSAGE' => $this->lang->line('success_msg_sinup_done'), 'STATUS' => 1));
            //$this->returnData($data = $return_array, $message = "Data has been successfully inserted", $status = 1);
            die();
        }
    }

    //Login User
    function login() {

        $username = $this->input->post('email');
        $password = $this->input->post('password');
        $language = $this->input->post('language');
        $device_type = $this->input->post('device_type');
        $token_key = $this->input->post('token_key');

        $error = '';

        if ($username == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_email'), 'STATUS' => 0));
            die();
        }
        if ($password == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_pass'), 'STATUS' => 0));
            die();
        }

        if ($error == 1) {
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_something_wrong'), 'STATUS' => 0));
            die();
        } else {

            //Check User Is valid or not
            $userinfo = $this->common->check_login($username, $password);

            if (count($userinfo) > 0) {
                if ($token_key != '') {
                    $data = array('token_key' => $this->input->post('token_key'));
                    $this->common->update_data($data, 'users', 'id', $userinfo[0]['id']);
                }
                if ($userinfo[0]['status'] == "0") {
                    echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_account_blocked'), 'STATUS' => 0));
                    exit();
                } else {
                    $userinfo[0]['username'] = $this->input->post('user_name');
                    unset($userinfo[0]['username']);
                    unset($userinfo[0]['password']);
                    
                    if(isset($userinfo[0]['photo'])) {
                        $userinfo[0]['user_avatar'] = S3_CDN . 'uploads/user/thumbs/' . $userinfo[0]['photo'];
                    } else {
                        $userinfo[0]['user_avatar'] = ASSETS_URL . 'images/user-avatar.png';
                    }
                    
                    if($language != '') {
                        $userinfo[0]['language'] = $language;
                        
                        // Get language id from code
                        $contition_array = array('lang_code' => $language);
                        $languages = $this->common->select_data_by_condition('languages', $contition_array, $data = 'lang_id');
                        
                        $update_data = array('lang_id' => $languages[0]['lang_id']);
                        $this->common->update_data($update_data, 'users', 'id', $userinfo[0]['id']);
                    } else {
                        $languages = $this->common->select_data_by_id('languages', 'lang_id', $userinfo[0]['lang_id'], $data = 'lang_code', $join_str = array());
                        $userinfo[0]['language'] = $languages[0]['lang_code'];
                        
                        if($languages[0]['lang_code'] == 'ar') {
                            $this->lang->load('message','arabic');
                        }
                    }
                    
                    // UPDATE DEVICE TYPE
                    if ($device_type != '') {
                        $data = array('device_type' => trim($device_type));
                        $this->common->update_data($data, 'users', 'id', $userinfo[0]['id']);
                    }

                    // UPDATE TOKEN KEY
                    if ($token_key != '') {
                        $data = array('token_key' => trim($token_key));
                        $this->common->update_data($data, 'users', 'id', $userinfo[0]['id']);
                    }
                    
                    // Null to Empty String
                    array_walk_recursive($userinfo[0], function (&$item, $key) {
                        $item = null === $item ? '' : $item;
                    });
                    
                    echo json_encode(array('RESULT' => $userinfo[0], 'MESSAGE' => $this->lang->line('msg_login_success'), 'STATUS' => 1));
                    exit();
//                  $this->returnData($data = $userinfo[0], $message = "success", $status = 1);
//                  die();
                }
            } else {
                echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_login'), 'STATUS' => 0));
                exit();
                //$this->returnData($data = array(), $message = "Please enter valid credential", $status = 0);
                //die();
            }
        }
    }

    //forgot password @Viral
    function forgotpassword() {

        $email = $this->input->post('email');
        if ($email == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_email'), 'STATUS' => 0));
            exit();
        } else {
            $conditionarray = array('email' => $email);

            $userdata = $this->common->select_data_by_id('users', 'email', $email, '*', '');
            /*   if ($userdata[0]['login_from'] == 'fb') {
              echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Sorry you are facebook user so you can not use this service.', 'STATUS' => 0));
              exit();
              } */
            if (count($userdata) > 0) {

                //    $emailsetting = $this->common->select_data_by_condition('emailsetting', array(), '*');

                $condition_array = array('emailid' => '2');
                $emailformat = $this->common->select_data_by_condition('emails', $condition_array, '*');

                $mail_body = $emailformat[0]['varmailformat'];

                $rand_password = $this->randomPassword();
                $md5_rand_password = md5($rand_password);

                $data['password'] = $md5_rand_password;

                $this->common->update_data($data, 'users', 'email', $email);

                $mail_body = html_entity_decode(str_replace("%name%", ucfirst($userdata[0]['name']), str_replace("%user_email%", $userdata[0]['email'], str_replace("%password%", $rand_password, stripslashes($mail_body)))));

                $condition_array = array('emailid' => '2');
                $emailformat = $this->common->select_data_by_condition('emails', $condition_array, '*');

                $send_mail = $this->sendEmail($userdata[0]['name'], $email, $emailformat[0]['varsubject'], $mail_body);

                if ($send_mail) {
                    echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_password_sent_to_email'), 'STATUS' => 1));
                    exit();
                } else {
                    echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_email_failed'), 'STATUS' => 2));
                    exit();
                }
            } else {
                echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_email_not_found'), 'STATUS' => 0));
                exit();
            }
        }
    }

    // Logout User
    function logout() {

        $user_id = $this->input->post('user_id');

        if ($user_id == '') {
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please provide user_id', 'STATUS' => 0));
            die();
        } else {
            // UPDATE DEVICE TYPE
            $data = array('device_type' => '');
            $this->common->update_data($data, 'users', 'id', $user_id);

            // UPDATE TOKEN KEY
            $data = array('token_key' => '');
            $this->common->update_data($data, 'users', 'id', $user_id);

            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('msg_logout_success'), 'STATUS' => 1));
            exit();
        }
    }

    // Contact Us @Viral
    function contact_us() {

        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');
        $subject = $this->input->post('subject');
        $message = $this->input->post('message');

        if ($name == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_name'), 'STATUS' => 0));
            exit();
        }
        if ($email == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_email'), 'STATUS' => 0));
            exit();
        }
/*        if ($phone == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter your phone number', 'STATUS' => 0));
            exit();
        }
        if ($subject == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter your subject', 'STATUS' => 0));
            exit();
        }*/
        if ($message == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_message'), 'STATUS' => 0));
            exit();
        } else {

            $insert_array['name'] = $name;
            $insert_array['email'] = trim($email);
//            $insert_array['phone'] = trim($phone);
//            $insert_array['subject'] = $subject;
            $insert_array['message'] = $message;
            $insert_array['posted_on'] = date('Y-m-d h:i:s');

            $insert_result = $this->common->insert_data_getid($insert_array, $tablename = 'contactus');
            $condition_array = array('emailid' => '3');
            $emailformat = $this->common->select_data_by_condition('emails', $condition_array, '*');

            $mail_body = $emailformat[0]['varmailformat'];

            $phone = 'N/A';
            $subject = 'You\'ve got new enquiry!';

            $mail_body = html_entity_decode(str_replace("%name%", ucfirst($name), str_replace("%user_email%", $email, str_replace("%phone%", $phone, str_replace("%subject%", $subject, str_replace("%message%", $message, stripslashes($mail_body)))))));

            // Find where to send new enquiry
            $settings = $this->common->getSettings('contact_mail');

            $send_mail = $this->common->sendMail($settings[0]['setting_value'], '', $emailformat[0]['varsubject'], $mail_body);

            if ($send_mail) {
                echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('success_msg_sent_message'), 'STATUS' => 1));
                exit();
            } else {
                // echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_not_able_to_send_msg'), 'STATUS' => 0));
                echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('success_msg_sent_message'), 'STATUS' => 1));
                exit();
            }
        }
    }

    // Stay Connected  @Viral
    function stay_connected() {
        $error = '';
        $condition_array = array('status' => 1);
        $stay_connect_data = $this->common->select_data_by_condition('stay_connect', $condition_array, 'id,name,description');

        echo json_encode(array('RESULT' => $stay_connect_data, 'MESSAGE' => '', 'STATUS' => 1));
        exit();
    }

    // About Feedbacker  @Viral
    function about_feedbacker() {

        $condition_array = array('id' => 1);
        $page_list = $this->common->select_data_by_condition('pages', $condition_array, 'id, name, description');
        echo json_encode(array('RESULT' => $page_list, 'MESSAGE' => '', 'STATUS' => 1));
        exit();
    }

    // Privacy Policy  @Viral
    function privacy_policy() {

        $condition_array = array('id' => 2);
        $page_list = $this->common->select_data_by_condition('pages', $condition_array, 'id, name, description');
        echo json_encode(array('RESULT' => $page_list, 'MESSAGE' => '', 'STATUS' => 1));
        exit();
    }

    // Terms And Conditions @Viral
    function terms_and_conditions() {

        $condition_array = array('id' => 4);
        $page_list = $this->common->select_data_by_condition('pages', $condition_array, 'id, name, description');
        echo json_encode(array('RESULT' => $page_list, 'MESSAGE' => '', 'STATUS' => 1));
        exit();
    }

    //Change Password @Viral
    function change_password() {

        $user_id = $this->input->post('user_id');
        $old_password = $this->input->post('old_password');
        $new_password = $this->input->post('new_password');

        $error = '';
        if ($user_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_email'), 'STATUS' => 0));
            die();
        }
        if ($old_password == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_old_pass_message'), 'STATUS' => 0));
            die();
        }
        if ($new_password == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_new_pass_message'), 'STATUS' => 0));
            die();
        }
        if ($error == 1) {
            echo (array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_details'), 'STATUS' => 0));
            die();
        } else {

            // check old password is correct or not
            $check_old_password = $this->common->select_data_by_id('users', 'id', $user_id, $data = 'password', $join_str = array());

            if (count($check_old_password) > 0) {
                $old_db_password = $check_old_password[0]['password'];

                if (md5($old_password) != $old_db_password) {
                    echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_correct_old_password'), 'STATUS' => 0));
                    die();
                } else {
                    $data = array('password' => md5($new_password));
                    $update = $this->common->update_data($data, 'users', 'id', $user_id);
                    echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('success_change_password'), 'STATUS' => 1));
                    die();
                }
            } else {
                echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('no_record_found'), 'STATUS' => 0));
                die();
            }
        }
    }

    // Show Notifications Settings @Viral
    function notifications() {
        $user_id = $this->input->post('user_id');
        if ($user_id == '') {
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            die();
        } else {

            $notification_data = $this->common->select_data_by_condition('notifications', $condition_array = array(), $data = 'notifications.notification_id,notifications.notification_title', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');

            $notification_array = array();
            foreach ($notification_data as $data) {
                $notification = array();
                $notification['id'] = $data['notification_id'];
                $notification['name'] = $data['notification_title'];

                $condition_array = array('notification_id' => $data['notification_id'], 'user_id' => $user_id);
                $notification_data = $this->common->select_data_by_condition('user_preferences', $condition_array, $data = 'status', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');

                if (count($notification_data) == 0) {
                    $notification_status = 'off';
                } else {
                    $notification_status = $notification_data[0]['status'];
                }

                $notification['status'] = $notification_status;

                array_push($notification_array, $notification);
            }

            array_walk_recursive($notification_array, function (&$item, $key) {
                $item = null === $item ? '' : $item;
            });

            echo json_encode(array('RESULT' => $notification_array, 'MESSAGE' => 'SUCCESS', 'STATUS' => 1));
            die();
        }
    }

    // Update Notification Settings @Viral
    function update_notifications() {
        $user_id = $this->input->post('user_id');
        $notification_id = $this->input->post('notification_id');
        $status = $this->input->post('status');

        $error = '';

        if ($user_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            die();
        }
        if ($notification_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter notification id', 'STATUS' => 0));
            die();
        }
        if ($status == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter status', 'STATUS' => 0));
            die();
        }

        if ($error == 1) {

            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_something_wrong'), 'STATUS' => 0));
            die();
        } else {

            $condition_array = array('notification_id' => $notification_id, 'user_id' => $user_id);
            $notification_data = $this->common->select_data_by_condition('user_preferences', $condition_array, $data = 'id', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');

            if (count($notification_data) > 0) {
                $update_data['status'] = $status;
                $this->common->update_data($update_data, 'user_preferences', 'id', $notification_data[0]['id']);
            } else {
                $insert_data['notification_id'] = $notification_id;
                $insert_data['user_id'] = $user_id;
                $insert_data['status'] = $status;
                $insert_data['updated_on'] = date('Y-m-d h:i:s');
                $insert_id = $this->common->insert_data_getid($insert_data, 'user_preferences');
            }
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('success_msg_notification_saved'), 'STATUS' => 1));
            die();
        }
    }

    // Get User Notifications
    function user_notifications() {
        // Implement Logic

        // 1. title i follow - [photo] john and 3 others wrote about XYZ - [ id, notification_id, user_id, title_id, guest_id, content ] - match user_id and title_id if user follows this title_id on new feedback post

        // 2. likes on feedback - john and 3 others liked your feedback - [ id, notification_id, user_id, guest_id, feedback_id, content ] - match user_id and feedback_id on like feedback event

        // 3. feedbacks on my title - john and 3 others commented on your feedback - [ id, notitication_id, user_id, title_id, guest_id, feedback_id, content ] - match user_id and title_id if user created this title_id on reply event

        $n_array = array();
        $user_id = $this->input->post('user_id');

        if ($user_id == '') {
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            die();
        } else {

            /* Titles I Follow */
            $n_follow = $this->common->get_notification($user_id, 2);
            if(count($n_follow) > 0) {
                $n_array = array_merge($n_array, $n_follow);
            }

            /* Likes on the Feedbacks */
            $n_likes = $this->common->get_notification($user_id, 3);
            if(count($n_likes) > 0) {
                $n_array = array_merge($n_array, $n_likes);
            }

            /* Feedbacks on my Titles */
            $n_reply = $this->common->get_notification($user_id, 4);
            if(count($n_reply) > 0) {
                $n_array = array_merge($n_array, $n_reply);
            }
            
            // Sort array by id
            usort($n_array, function($a, $b) {
                return $b['id'] - $a['id'];
            });
            
            if(!empty($n_array)) {
                echo json_encode(array('RESULT' => $n_array, 'TOTAL' => count($n_array),'MESSAGE' => 'SUCCESS', 'STATUS' => 1));
                exit();
            } else {
                echo json_encode(array('RESULT' => $n_array, 'MESSAGE' => $this->lang->line('no_record_found'), 'STATUS' => 0));
                die();
            }

        }
    }

    // Edit Profile @Viral
    function update_profile() {
        $user_id = $this->input->post('user_id');
        $gender = $this->input->post('gender');
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $country = $this->input->post('country');
        $dob = $this->input->post('dob');
        
        $update_data = array();
        $error = '';
        
        if ($user_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            die();
        } else {
            if ($email != '') {
                $condition_array = array('id !=' => $user_id);
                $check_result = $this->common->check_unique_avalibility('users', 'email', $email, '', '', $condition_array);
    
                if ($check_result == 1) {
                    $error = 1;
                    echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_email_exits'), 'STATUS' => 0));
                    //$this->returnData($data = array(), $message = "Email id already exits", $status = 0);
                    die();
                }
            }

            $condition_array = array('id' => $user_id);
            $user_data = $this->common->select_data_by_condition('users', $condition_array, $data = 'id, name, email, gender, dob, country, photo', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');

            if ($gender != '') {
                $update_data['gender'] = $gender;
            }
            if ($name != '') {
                $update_data['name'] = $name;
            }
            if ($email != '') {
                $update_data['email'] = $email;
            }
            if ($country != '') {
                $update_data['country'] = $country;
            }
            if ($dob != '') {
                $update_data['dob'] = $dob;
            }
            
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                $config['upload_path'] = $this->config->item('user_main_upload_path');
                $config['thumb_upload_path'] = $this->config->item('user_thumb_upload_path');
                $config['allowed_types'] = 'jpg|png|jpeg|gif';
                $config['file_name'] = time();

                $this->load->library('upload');
                $this->upload->initialize($config);
                
                //Uploading Image
                $this->upload->do_upload('image');
                
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
//                  echo $s3file = S3_CDN.$config_thumb['source_image'];
//                  echo "<br/>";
//                  echo $s3file = S3_CDN.$thumb_file_name; exit();

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
                    $main_old_file = $this->config->item('user_main_upload_path') . $user_data[0]['photo'];
                    $thumb_old_file = $this->config->item('user_thumb_upload_path') . $user_data[0]['photo'];

                    /*    if (file_exists($main_old_file)) {
                      unlink($main_old_file);
                      }
                      if (file_exists($thumb_old_file)) {
                      unlink($thumb_old_file);
                      } */
                    $error = array();
                }

                if ($error) {
                    echo json_encode(array('RESULT' => array(), 'MESSAGE' => $error[0], 'STATUS' => 0));
                    die();
                }
                $update_data['photo'] = $dataimage;
            }
            
            if(!empty($update_data)) {
                $this->common->update_data($update_data, 'users', 'id', $user_id);
                if(isset($update_data['gender'])) {
                    $user_data[0]['gender'] = $update_data['gender'];
                }
                if(isset($update_data['name'])) {
                    $user_data[0]['name'] = $update_data['name'];
                }
                if(isset($update_data['email'])) {
                    $user_data[0]['email'] = $update_data['email'];
                }
                if(isset($update_data['country'])) {
                    $user_data[0]['country'] = $update_data['country'];
                }
                if(isset($update_data['dob'])) {
                    $date = date_create($user_data[0]['dob']);
                    $user_data[0]['dob'] = date_format($date, 'd-M-Y');
                } else {
                    $user_data[0]['dob'] = "";
                }

                if(isset($update_data['photo'])) {
                    $user_data[0]['photo'] = S3_CDN . 'uploads/user/thumbs/' . $update_data['photo'];
                } elseif(isset($user_data[0]['photo'])) {
                    $user_data[0]['photo'] = S3_CDN . 'uploads/user/thumbs/' . $user_data[0]['photo'];
                } else {
                    $user_data[0]['photo'] = ASSETS_URL . 'images/user-avatar.png';
                }

                array_walk_recursive($user_data, function (&$item, $key) {
                    $item = null === $item ? '' : $item;
                });             
                                                
                echo json_encode(array('RESULT' => $user_data, 'MESSAGE' => $this->lang->line('success_msg_profile_saved'), 'STATUS' => 1));
                die();
            } else {
                if(isset($user_data[0]['photo'])) {
                    $user_data[0]['photo'] = S3_CDN . 'uploads/user/thumbs/' . $user_data[0]['photo'];
                } else {
                    $user_data[0]['photo'] = ASSETS_URL . 'images/user-avatar.png';
                }
                if(isset($user_data[0]['dob'])) {
                    $date = date_create($user_data[0]['dob']);
                    $user_data[0]['dob'] = date_format($date, 'd-M-Y');
                } else {
                    $user_data[0]['dob'] = "";
                }

                array_walk_recursive($user_data, function (&$item, $key) {
                    $item = null === $item ? '' : $item;
                });
                
                echo json_encode(array('RESULT' => $user_data, 'MESSAGE' => $this->lang->line('success_no_profile_update'), 'STATUS' => 0));
                //$this->returnData($data = array(), $message = "Email id already exits", $status = 0);
                die();
            }
        }
    }

    // Mail send @Viral
    function sendEmail($name = '', $to_email = '', $subject = '', $mail_body = '', $cc = '', $from = '') {

        //$emailsetting = $this->common->select_data_by_condition('emailsetting', array(), '*');
        //Loading E-mail Class
        $this->load->library('email');

        $this->email->from($from, 'Feedbacker');
        $this->email->to($to_email);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->set_mailtype("html");
        $this->email->message(html_entity_decode($mail_body));

        if ($this->email->send()) {
            return true;
        } else {
            return FALSE;
        }
    }

//    function sendEmail_toclient($name = '', $to_email = 'feedbacker.bid@gmail.com', $subject = '', $mail_body = '', $cc = '', $from ='') {
    function sendEmail_toclient($name = '', $to_email = '', $subject = '', $mail_body = '', $cc = '', $from = '') {

        //$emailsetting = $this->common->select_data_by_condition('emailsetting', array(), '*');
        //Loading E-mail Class
        $this->load->library('email');

        $this->email->from($from, $name);
        $this->email->to($to_email);
        $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->set_mailtype("html");
        $this->email->message(html_entity_decode($mail_body));

        if ($this->email->send()) {
            return true;
        } else {
            return FALSE;
        }
    }

    // Random password @Viral
    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    // Get Feedbacks for Home Page
    function home() {


        $user_id = $this->input->post('user_id');
        $country = $this->input->post('country');
        $limit = $this->input->post('limit');
        $offset = $this->input->post('offset');     
                
        if ($user_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            exit();
        } else {        
            // Get User name
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
            
            $showall = false;

            // Get user country
            if($country == '') {
                $getcountry = $this->common->select_data_by_id('users', 'id', $user_id, 'country', '');
                $country = $getcountry[0]['country'];
                $showall = true;
            }
            
            $contition_array = array('replied_to' => NULL, 'feedback.country' => $country, 'feedback.deleted' => 0, 'feedback.status' => 1);
            $data = 'feedback_id, feedback.title_id, title, name, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video, replied_to, location, feedback.datetime as time';
            if ($limit != '' && $offset != '') {
                $feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $limit, $offset, $join_str, $group_by = '');
            } else {
                $feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $limit = '', $offset = '', $join_str, $group_by = '');
            }
            
            if(count($feedback) == 0 && $showall == true) {
                $contition_array = array('replied_to' => NULL, 'feedback.deleted' => 0, 'feedback.status' => 1);
                $data = 'feedback_id, feedback.title_id, title, name, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video, replied_to, location, feedback.datetime as time';
                if ($limit != '' && $offset != '') {
                    $feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $limit, $offset, $join_str, $group_by = '');
                } else {
                    $feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $limit = '', $offset = '', $join_str, $group_by = '');
                }               
            }

            // echo "<pre>";
            // print_r($feedback);
            // exit();

            $return_array = array();
            $total_records = count($feedback);
            
            if($total_records > 0) {
                foreach ($feedback as $item) {
                    $return = array();
                    $return['id'] = $item['feedback_id'];
                    $return['title_id'] = $item['title_id'];                
                    $return['title'] = $item['title'];
                    
                    // Get likes for this feedback
                    $contition_array_lk = array('feedback_id' => $item['feedback_id']);
                    $flikes = $this->common->select_data_by_condition('feedback_likes', $contition_array_lk, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
                    
                    $return['likes'] = "";
                    
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
                    $contition_array_li = array('feedback_id' => $item['feedback_id'], 'user_id' => $user_id);
                    $likes = $this->common->select_data_by_condition('feedback_likes', $contition_array_li, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
                                
                    if(count($likes) > 0) {
                        $return['is_liked'] = TRUE;
                    } else {
                        $return['is_liked'] = FALSE;
                    }
                    
                    // Check If user followed this title
                    $contition_array_ti = array('title_id' => $item['title_id'], 'user_id' => $user_id);
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
                    } elseif($item['feedback_img'] !== "") {
                        $return['feedback_thumb'] = S3_CDN . 'uploads/feedback/main/' . $item['feedback_img'];
                    } else {
                        $return['feedback_thumb'] = "";
                    }
                    
                    if($item['feedback_video'] !== "") {
                        $return['feedback_video'] = S3_CDN . 'uploads/feedback/video/' . $item['feedback_video'];
                        //$return['feedback_thumb'] = S3_CDN . 'uploads/feedback/thumbs/video_thumbnail.png';
                    } else {
                        $return['feedback_video'] = "";
                    }
    
                    $return['location'] = $item['location'];
                    $return['feedback'] = $item['feedback_cont'];
                    $return['time'] = $this->common->timeAgo($item['time']);
    
                    array_push($return_array, $return);
                }
    
                // Null to Empty String
                array_walk_recursive($return_array, function (&$item, $key) {
                    $item = null === $item ? '' : $item;
                });
    
                echo json_encode(array('RESULT' => $return_array, 'TOTAL' => $total_records,'MESSAGE' => 'SUCCESS', 'STATUS' => 1));
                exit();
            } else {
                echo json_encode(array('RESULT' => array(), 'TOTAL' => 0,'MESSAGE' => $this->lang->line('no_record_found'), 'STATUS' => 0));
                exit();
            }
        }
    }

    // Get All Feedbacks for selected title
    function get_feedbacks() {
        $user_id = $this->input->post('user_id');
        $title_id = $this->input->post('title_id');     
        $limit = $this->input->post('limit');
        $offset = $this->input->post('offset');     
                
        if ($user_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            exit();
        } else {
            if($title_id != '') {
                $where = array('feedback.title_id' => $title_id, 'feedback.replied_to' => NULL, 'feedback.deleted' => 0, 'feedback.status' => 1);
            } elseif ($user_id != '') {
                $where = array('feedback.user_id' => $user_id, 'feedback.replied_to' => NULL, 'feedback.deleted' => 0, 'feedback.status' => 1);
            }
            $total_records = $this->common->get_count_of_table('feedback', $where);
            
            // Get User name
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

            if($title_id != '') {
                $contition_array = array('feedback.title_id' => $title_id, 'feedback.replied_to' => NULL, 'feedback.deleted' => 0, 'feedback.status' => 1);
            } elseif($user_id != '') {
                $contition_array = array('feedback.user_id' => $user_id, 'feedback.replied_to' => NULL, 'feedback.deleted' => 0, 'feedback.status' => 1);
            }
            
            $data = 'feedback_id, feedback.title_id, title, name, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video, replied_to, location, feedback.datetime as time';
            if ($limit != '' && $offset != '') {
                $feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $limit, $offset, $join_str, $group_by = '');
            } else {
                $feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $limit = '', $offset = '', $join_str, $group_by = '');
            }

//           echo "<pre>";
//           print_r($feedback);
//           exit();

            $return_array = array();
            foreach ($feedback as $item) {
                $return = array();
                $return['id'] = $item['feedback_id'];
                $return['title_id'] = $item['title_id'];                
                $return['title'] = $item['title'];
                
                // Get likes for this feedback
                $contition_array_lk = array('feedback_id' => $item['feedback_id']);
                $flikes = $this->common->select_data_by_condition('feedback_likes', $contition_array_lk, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
                
                $return['likes'] = "";
                
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
                $contition_array_rs = array('feedback_id' => $item['feedback_id'], 'user_id' => $user_id);
                $spam = $this->common->select_data_by_condition('spam', $contition_array_rs, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
                            
                if(count($spam) > 0) {
                    $return['report_spam'] = TRUE;
                } else {
                    $return['report_spam'] = FALSE;
                }
                
                // Check If user liked this feedback
                $contition_array_li = array('feedback_id' => $item['feedback_id'], 'user_id' => $user_id);
                $likes = $this->common->select_data_by_condition('feedback_likes', $contition_array_li, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
                            
                if(count($likes) > 0) {
                    $return['is_liked'] = TRUE;
                } else {
                    $return['is_liked'] = FALSE;
                }
                
                // Check If user followed this title
                $contition_array_ti = array('title_id' => $item['title_id'], 'user_id' => $user_id);
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

                $return['feedback'] = $item['feedback_cont'];
                $return['location'] = $item['location'];                
                $return['time'] = $this->common->timeAgo($item['time']);

                array_push($return_array, $return);
            }

            // Null to Empty String
            array_walk_recursive($return_array, function (&$item, $key) {
                $item = null === $item ? '' : $item;
            });

            echo json_encode(array('RESULT' => $return_array, 'TOTAL' => $total_records,'MESSAGE' => 'SUCCESS', 'STATUS' => 1));
            exit();
        }
    }
    
    // Get All Feedbacks for a Query string
    function search_results() {
        $user_id = $this->input->post('user_id');
        $qs = $this->input->post('qs');     
        $limit = $this->input->post('limit');
        $offset = $this->input->post('offset');     
        
        if ($user_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            exit();
        } else {
            // Search Report Log
            if ($qs != '') {
                // Get User's Country
                $get_country = $this->common->user_country($user_id);
                $country_id = $get_country[0]['country'];

                // Check If keyword Exists
                $condition_log = "`search_keyword` LIKE '".$qs."'";
                $search_log = $this->common->select_data_by_search('search_log', $condition_log, $condition_array = array(), '*', $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str = array());

                if(count($search_log) > 0) {
                    // Append User ID
                    if(strpos($search_log[0]['user_ids'], ',') === false) {
                        if($user_id !== $search_log[0]['user_ids']) {
                            $update_array['user_ids'] = $search_log[0]['user_ids'].",".$user_id;
                        }
                    } else {
                        $userArr = explode(',', $search_log[0]['user_ids']);

                        if(!in_array($user_id, $userArr)) {
                            $update_array['user_ids'] = $search_log[0]['user_ids'].",".$user_id;
                        }
                    }

                    // Append Country ID
                    if(strpos($search_log[0]['country_ids'], ',') === false) {
                        if($country_id !== $search_log[0]['country_ids']) {
                            $update_array['country_ids'] = $search_log[0]['country_ids'].",".$country_id;
                        }
                    } else {
                        $userArr = explode(',', $search_log[0]['country_ids']);

                        if(!in_array($country_id, $userArr)) {
                            $update_array['country_ids'] = $search_log[0]['country_ids'].",".$country_id;
                        }
                    }

                    // Update Entry
                    $search_count = $search_log[0]['search_count'] + 1;
                    $update_array['search_count'] = $search_count;
                    $this->common->update_data($update_array, 'search_log', 'slog_id', $search_log[0]['slog_id']);
                } else {
                    // Add New Entry
                    $insert_array['search_keyword'] = $qs;
                    $insert_array['search_count'] = 1;
                    $insert_array['user_ids'] = $user_id;
                    $insert_array['country_ids'] = $country_id;
                    
                    $this->common->insert_data($insert_array, $tablename = 'search_log');
                }
            }
            
            // Get User name
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

            // Get Search Results
            $search_condition = "(`title` LIKE '%".$qs."%' OR `feedback_cont` LIKE '%".$qs."%') AND db_feedback.deleted = 0 AND feedback.status = 1";
            $data = 'feedback_id, feedback.title_id, title, name, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video, replied_to, location, feedback.datetime as time';

            if ($limit != '' && $offset != '') {
                $feedback = $this->common->select_data_by_search('feedback', $search_condition, $condition_array = array(), $data, $sortby = '', $orderby = '', $limit, $offset, $join_str);
            } else {
                $feedback = $this->common->select_data_by_search('feedback', $search_condition, $condition_array = array(), $data, $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str);
            }
            
            if(count($feedback) > 0) {
                
                $return_array = array();
                foreach ($feedback as $item) {
                    $return = array();
                    $return['id'] = $item['feedback_id'];
                    $return['title_id'] = $item['title_id'];                
                    $return['title'] = $item['title'];
                    
                    // Get likes for this feedback
                    $contition_array_lk = array('feedback_id' => $item['feedback_id']);
                    $flikes = $this->common->select_data_by_condition('feedback_likes', $contition_array_lk, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
                    
                    $return['likes'] = "";
                    
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
                    $contition_array_li = array('feedback_id' => $item['feedback_id'], 'user_id' => $user_id);
                    $likes = $this->common->select_data_by_condition('feedback_likes', $contition_array_li, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
                                
                    if(count($likes) > 0) {
                        $return['is_liked'] = TRUE;
                    } else {
                        $return['is_liked'] = FALSE;
                    }
                    
                    // Check If user followed this title
                    $contition_array_ti = array('title_id' => $item['title_id'], 'user_id' => $user_id);
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
    
                    $return['feedback'] = $item['feedback_cont'];
                    $return['location'] = $item['location'];                
                    $return['time'] = $this->common->timeAgo($item['time']);
    
                    array_push($return_array, $return);
                }
    
                // Null to Empty String
                array_walk_recursive($return_array, function (&$item, $key) {
                    $item = null === $item ? '' : $item;
                });
                
                $total_records = count($return_array);
    
                // API LOG
                // $insert_array['post_request'] = json_encode(array('RESULT' => $this->input->post(), 'MESSAGE' => '', 'STATUS' => 0));
                // $insert_array['json_response'] = json_encode(array('RESULT' => $return_array, 'MESSAGE' => '', 'STATUS' => 0));
                // $insert_array['log_time'] = date('Y-m-d H:i:s');
                
                // $insert_result = $this->common->insert_data($insert_array, $tablename = 'api_log');
            
                echo json_encode(array('RESULT' => $return_array, 'TOTAL' => $total_records,'MESSAGE' => 'SUCCESS', 'STATUS' => 1));
                exit();
            } else {
                echo json_encode(array('RESULT' => array(), 'TOTAL' => 0,'MESSAGE' => $this->lang->line('no_record_found'), 'STATUS' => 0));
                exit();
            }
        }
    }
    
    // Get All Followings for selected user
    function get_followings() {
        $user_id = $this->input->post('user_id');
        $limit = $this->input->post('limit');
        $offset = $this->input->post('offset');             
                
        if ($user_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            exit();
        } else {
//          $where = array('followings.user_id' => $user_id, 'feedback.title_id' => 'followings.title_id');
//          $total_records = $this->common->get_count_of_table('feedback', $where);
            
            // Get User name
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
                ),
                array(
                    'table' => 'followings',
                    'join_table_id' => 'followings.title_id',
                    'from_table_id' => 'feedback.title_id',
                    'join_type' => 'left'
                )
            );

            $contition_array = array('followings.user_id' => $user_id, 'feedback.replied_to' => NULL, 'feedback.deleted' => 0, 'feedback.status' => 1);
            $data = 'feedback_id, feedback.title_id, title, name, photo, feedback_cont, feedback_img, feedback_thumb, feedback_video, replied_to, location, feedback.datetime as time';
            
            if ($limit != '' && $offset != '') {
                $feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $limit, $offset, $join_str, $group_by = '');
            } else {
                $feedback = $this->common->select_data_by_condition('feedback', $contition_array, $data, $sortby = 'feedback.datetime', $orderby = 'DESC', $limit = '', $offset = '', $join_str, $group_by = '');
            }
            
            $total_records = count($feedback);

//           echo "<pre>";
//           print_r($feedback);
//           exit();

            $return_array = array();
            foreach ($feedback as $item) {
                $return = array();
                $return['id'] = $item['feedback_id'];
                $return['title_id'] = $item['title_id'];                
                $return['title'] = $item['title'];
                
                // Get likes for this feedback
                $contition_array_lk = array('feedback_id' => $item['feedback_id']);
                $flikes = $this->common->select_data_by_condition('feedback_likes', $contition_array_lk, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
                
                $return['likes'] = "";
                
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
                $contition_array_li = array('feedback_id' => $item['feedback_id'], 'user_id' => $user_id);
                $likes = $this->common->select_data_by_condition('feedback_likes', $contition_array_li, $data = '*', $short_by = '', $order_by = '', $limit = '', $offset = '', $join_str = array(), $group_by = '');
                            
                if(count($likes) > 0) {
                    $return['is_liked'] = TRUE;
                } else {
                    $return['is_liked'] = FALSE;
                }
                
                // Check If user followed this title
                $contition_array_ti = array('title_id' => $item['title_id'], 'user_id' => $user_id);
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

                $return['feedback'] = $item['feedback_cont'];
                $return['location'] = $item['location'];
                $return['time'] = $this->common->timeAgo($item['time']);

                array_push($return_array, $return);
            }

            // Null to Empty String
            array_walk_recursive($return_array, function (&$item, $key) {
                $item = null === $item ? '' : $item;
            });

            echo json_encode(array('RESULT' => $return_array, 'TOTAL' => $total_records,'MESSAGE' => 'SUCCESS', 'STATUS' => 1));
            exit();
        }
    }
    
    // Like / Unlike Feedback
    function like() {
        $user_id = $this->input->post('user_id');
        $feedback_id = $this->input->post('feedback_id');
        
        $error = '';
        if ($user_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            die();
        }
        if ($feedback_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter feedback id', 'STATUS' => 0));
            //$this->returnData($data = array(), $message = "Please enter your email", $status = 0);
            die();
        }

        $condition_array = array('user_id' => $user_id, 'feedback_id' => $feedback_id);
        $likes = $this->common->select_data_by_condition('feedback_likes', $condition_array, $data = '*', $short_by = '', $order_by = '', $limit = '1', $offset = '', $join_str = array(), $group_by = '');
        
        if(count($likes) > 0) {
            // Unlike Feedback
            $this->common->delete_data('feedback_likes', 'like_id', $likes[0]['like_id']);

            // Check / Add Notification for users
            $this->common->notification('', $user_id, $title_id = '', $feedback_id, $replied_to = '', 3);

            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('success_unlike_feedback'), 'STATUS' => 1));
            die();
        } else {
            // Like Feedback
            $insert_array['user_id'] = $user_id;
            $insert_array['feedback_id'] = $feedback_id;
            
            $insert_result = $this->common->insert_data($insert_array, $tablename = 'feedback_likes');

            // Check / Add Notification for users
            $this->common->notification('', $user_id, $title_id = '', $feedback_id, $replied_to = '', 3);

            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('success_like_feedback'), 'STATUS' => 1));
            die();
        }
    }
    
    // Follow / Unfollow Title
    function follow() {
        $user_id = $this->input->post('user_id');
        $title_id = $this->input->post('title_id');
        
        $error = '';
        if ($user_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            die();
        }
        if ($title_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter title id', 'STATUS' => 0));
            //$this->returnData($data = array(), $message = "Please enter your email", $status = 0);
            die();
        }

        $condition_array = array('user_id' => $user_id, 'title_id' => $title_id);
        $followings = $this->common->select_data_by_condition('followings', $condition_array, $data = '*', $short_by = '', $order_by = '', $limit = '1', $offset = '', $join_str = array(), $group_by = '');
        
        if(count($followings) > 0) {
            // Unfollow Title
            $this->common->delete_data('followings', 'follow_id', $followings[0]['follow_id']);
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('success_unfollow_title'), 'STATUS' => 1));
            die();
        } else {
            // Follow Title
            $insert_array['user_id'] = $user_id;
            $insert_array['title_id'] = $title_id;
            
            $insert_result = $this->common->insert_data($insert_array, $tablename = 'followings');
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('success_follow_title'), 'STATUS' => 1));
            die();
        }
    }
    
    // Report Spam / Undo
    function report() {
        $user_id = $this->input->post('user_id');
        $feedback_id = $this->input->post('feedback_id');
        
        $error = '';
        if ($user_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            die();
        }
        if ($feedback_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter feedback id', 'STATUS' => 0));
            die();
        }

        $condition_array = array('user_id' => $user_id, 'feedback_id' => $feedback_id);
        $spams = $this->common->select_data_by_condition('spam', $condition_array, $data = '*', $short_by = '', $order_by = '', $limit = '1', $offset = '', $join_str = array(), $group_by = '');
        
        if(count($spams) > 0) {
            // Undo Report
            $this->common->delete_data('spam', 'spam_id', $spams[0]['spam_id']);
            echo json_encode(array('RESULT' => array('report_spam' => FALSE), 'MESSAGE' => $this->lang->line('success_undo_report'), 'STATUS' => 1));
            die();
        } else {
            // Report Spam
            $insert_array['user_id'] = $user_id;
            $insert_array['feedback_id'] = $feedback_id;
            
            $insert_result = $this->common->insert_data($insert_array, $tablename = 'spam');
            echo json_encode(array('RESULT' => array('report_spam' => TRUE), 'MESSAGE' => $this->lang->line('success_report_spam'), 'STATUS' => 1));
            die();
        }
    }

    // Get Titles/Suggestions
    function titles() {
        $search_string = $this->input->post('search');
        $titles = $this->common->getTitles($search_string, $order=null, $order_type='ASC', $offset='', $limit='');

        echo json_encode(array('RESULT' => $titles, 'MESSAGE' => 'SUCCESS', 'STATUS' => 1));
        die();
    }

    // Create a title
    function addtitle() {
        $user_id = $this->input->post('user_id');
        $title = trim($this->input->post('title'));

        if ($title == '') {
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_msg_title_blank'), 'STATUS' => 0));
            die();
        }
        
        // Check If title exists
        $contition_array = array('title' => $title);
        $check_title = $this->common->select_data_by_condition('titles', $contition_array, $data = '*', $sortby = '', $orderby = '', $limit = '', $offset = '', $join_str = array(), $group_by='');
        
        if(count($check_title) > 0) {
            $update_data = array('deleted' => 0);
            $update_result = $this->common->update_data($update_data, 'titles', 'title_id', $check_title[0]['title_id']);

            $return_array['id'] = $check_title[0]['title_id'];
            $return_array['title'] = $check_title[0]['title'];

            echo json_encode(array('RESULT' => $return_array, 'MESSAGE' => $this->lang->line('error_title_exist'), 'STATUS' => 0));
            die();
        }

        $insert_array['title'] = $title;
        $insert_result = $this->common->insert_data_getid($insert_array, $tablename = 'titles');
        
        // Auto Follow Title
        if ($user_id != '') {
            $follow_array['user_id'] = $user_id;
            $follow_array['title_id'] = $insert_result;
            
            $auto_follow = $this->common->insert_data($follow_array, $tablename = 'followings');
        }

        $return_array['id'] = $insert_result;
        $return_array['title'] = $title;

        echo json_encode(array('RESULT' => $return_array, 'MESSAGE' => $this->lang->line('success_msg_title_added'), 'STATUS' => 1));
        die();
    }

    // Write a feedback
    function feedback() {
        $title_id = $this->input->post('title_id');
        $user_id = $this->input->post('user_id');
        $feedback = $this->input->post('feedback');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $location = $this->input->post('location');
        $country = $this->input->post('country');       
        
        $error = 0;
        if ($title_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please select your title', 'STATUS' => 0));
            exit();
        }
        if ($user_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            exit();
        }
        if ($feedback == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter your feedback', 'STATUS' => 0));
            exit();
        }
        
        if ($error == 1) {
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_something_wrong'), 'STATUS' => 0));
            die();
        } else {
            $feedback_img = '';
            $feedback_thumb = '';
            $feedback_video = '';
            
            // Image Upload Start
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                $config['upload_path'] = $this->config->item('feedback_main_upload_path');
                $config['thumb_upload_path'] = $this->config->item('feedback_thumb_upload_path');
                $config['allowed_types'] = $this->config->item('feedback_allowed_types');
                $config['max_size'] = $this->config->item('feedback_main_max_size');
                $config['max_width'] = $this->config->item('feedback_main_max_width');
                $config['max_height'] = $this->config->item('feedback_main_max_height');
                $config['file_name'] = time();
    
                $this->load->library('upload', $config);
    
                // Uploading Image
                if (!$this->upload->do_upload('image')) {
                    $error = array('error' => $this->upload->display_errors());
                    echo json_encode(array('RESULT' => $error, 'MESSAGE' => 'ERROR', 'STATUS' => 0));
                    exit();
                } else {
                    // Getting Uploaded Image File Data
                    $imgdata = $this->upload->data();                   
                    $feedback_img = $imgdata['file_name'];

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
                        $feedback_thumb = $imgdata['raw_name'].'_thumb'.$imgdata['file_ext'];
                    }
                    
                    // AWS S3 Upload
                    $thumb_file_path = str_replace("main", "thumbs", $imgdata['file_path']);
                    $thumb_file_name = $config['thumb_upload_path'] . $imgdata['raw_name'].'_thumb'.$imgdata['file_ext'];
                    
                    $this->s3->putObjectFile($imgdata['full_path'], S3_BUCKET, $config_thumb['source_image'], S3::ACL_PUBLIC_READ);
                    $this->s3->putObjectFile($thumb_file_path.$feedback_thumb, S3_BUCKET, $thumb_file_name, S3::ACL_PUBLIC_READ);
//                  echo $s3file = S3_CDN.$config_thumb['source_image'];
//                  echo "<br/>";
//                  echo $s3file = S3_CDN.$thumb_file_name; exit();

                    // Remove File from Local Storage
                    unlink($config_thumb['source_image']);
                    unlink($thumb_file_name);
                }
            }
            // Image Upload End
            
            // Video Upload Start
            if (isset($_FILES['video']['name']) && $_FILES['video']['name'] != '') {
                $config_video['upload_path'] = $this->config->item('feedback_video_upload_path');
                $config_video['thumb_upload_path'] = $this->config->item('feedback_thumb_upload_path');
                $config_video['max_size'] = $this->config->item('feedback_video_max_size');
                $config_video['allowed_types'] = $this->config->item('feedback_allowed_video_types');
                $config_video['overwrite'] = FALSE;
                $config_video['remove_spaces'] = TRUE;
                $config_video['file_name'] = time();    
                    
                $this->load->library('upload', $config_video);
                $this->upload->initialize($config_video);
                
                if (!$this->upload->do_upload('video')) {
                    $error = $this->upload->display_errors();
                    echo json_encode(array('RESULT' => array(), 'MESSAGE' => strip_tags($error), 'STATUS' => 0));
                    exit();
                } else {
                    $video_details = $this->upload->data();

                    if($this->input->post('debug') == 'true') {
                        echo json_encode(array('RESULT' => $video_details, 'MESSAGE' => '', 'STATUS' => 0));
                        exit();
                    }       
                                
                    $feedback_video = $video_details['file_name'];
        
/*                  if($video_details['file_ext'] == ".mov" || $video_details['file_ext'] == ".MOV") {
                        // ffmpeg command to convert video
                        shell_exec("ffmpeg -i ".$video_details['full_path']." ".$video_details['file_path'].$video_details['raw_name'].".mp4");
                    
                        /// In the end update video name in DB
                        $feedback_video = $video_details['raw_name'].'.'.'mp4';
                    }*/
                    
                    // Generate video thumbnail
                    $video_path = $video_details['full_path'];
                    $thumb_name = $video_details['raw_name']."_video.jpg";
                    $thumb_path = $config_video['thumb_upload_path'].$thumb_name;

                    shell_exec("ffmpeg -itsoffset -3 -i ".$video_path."  -y -an -f image2 -s 400x270 ".$thumb_path."");
                    $feedback_thumb = $thumb_name;
                    
                    // AWS S3 Upload
                    $thumb_file_path = str_replace("video", "thumbs", $video_details['file_path']);
                    
                    $this->s3->putObjectFile($video_details['full_path'], S3_BUCKET, $config_video['upload_path'].$video_details['file_name'], S3::ACL_PUBLIC_READ);
                    $this->s3->putObjectFile($thumb_file_path.$feedback_thumb, S3_BUCKET, $thumb_path, S3::ACL_PUBLIC_READ);
//                  echo $s3file = S3_CDN.$config_video['upload_path'].$video_details['file_name'];
//                  echo "<br/>";
//                  echo $s3file = S3_CDN.$thumb_path; exit();

                    // Remove File from Local Storage
                    unlink($config_video['upload_path'].$video_details['file_name']);
                    unlink($thumb_path);
                }
            }
            // Video Upload End

            $insert_array['title_id'] = $title_id;
            $insert_array['user_id'] = $user_id;
            $insert_array['feedback_cont'] = $feedback;
            if($feedback_img != '') {
                $insert_array['feedback_img'] = $feedback_img;
            }
            if($feedback_thumb != '') {
                $insert_array['feedback_thumb'] = $feedback_thumb;
            }
            if($feedback_video != '') {
                $insert_array['feedback_video'] = $feedback_video;
            }
            $insert_array['latitude'] = $latitude;
            $insert_array['longitude'] = $longitude;
            $insert_array['location'] = $location;
            
            if($country != '') {
                $insert_array['country'] = $country;
            } else {
                $getcountry = $this->common->select_data_by_id('users', 'id', $user_id, 'country', '');
                $insert_array['country'] = $getcountry[0]['country'];
            }
            
            $insert_array['datetime'] = date('Y-m-d H:i:s');

            $insert_result = $this->common->insert_data_getid($insert_array, $tablename = 'feedback');

            if ($insert_result) {
                $return_array['id'] = $insert_result;
                $return_array['feedback'] = $feedback;
                $return_array['latitude'] = $latitude;
                $return_array['longitude'] = $longitude;
                $return_array['location'] = $location;

                if($feedback_img != '') {
                    $return_array['image'] = S3_CDN . 'uploads/feedback/main/' . $feedback_img;
                }

                if($feedback_thumb != '') {
                    $return_array['thumb'] = S3_CDN . 'uploads/feedback/thumbs/' . $feedback_thumb;
                }
                
                if($feedback_video != '') {
                    $return_array['video'] = S3_CDN . 'uploads/feedback/video/' . $feedback_video;
                }
                
                if($country != '') {
                    $return_array['country'] = $country;
                }

                // Null to Empty String
                array_walk_recursive($return_array, function (&$item, $key) {
                    $item = null === $item ? '' : $item;
                });

                // Check / Add Notification for users
                $this->common->notification('', $user_id, $title_id, $insert_result, $replied_to = '', 2);

                echo json_encode(array('RESULT' => $return_array, 'MESSAGE' => $this->lang->line('success_feedback_submit'), 'STATUS' => 1));
                exit();
            } else {
                echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_feedback_submit'), 'STATUS' => 0));
                exit();
            }
        }
    }

    // Reply to feedback
    function reply() {
        $user_id = $this->input->post('user_id');
        $replied_to = $this->input->post('feedback_id');
        $feedback = $this->input->post('feedback');
        $location = $this->input->post('location');
        $country = $this->input->post('country');       

        if ($user_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            exit();
        }
        if ($replied_to == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter feedback id', 'STATUS' => 0));
            exit();
        }
        if ($feedback == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter your feedback', 'STATUS' => 0));
            exit();
        } else {
            $feedback_img = '';
            $feedback_thumb = '';
            $feedback_video = '';
            
            // Image Upload Start
            if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                $config['upload_path'] = $this->config->item('feedback_main_upload_path');
                $config['thumb_upload_path'] = $this->config->item('feedback_thumb_upload_path');
                $config['allowed_types'] = $this->config->item('feedback_allowed_types');
                $config['max_size'] = $this->config->item('feedback_main_max_size');
                $config['max_width'] = $this->config->item('feedback_main_max_width');
                $config['max_height'] = $this->config->item('feedback_main_max_height');
                $config['file_name'] = time();
    
                $this->load->library('upload', $config);
    
                //Uploading Image
                if (!$this->upload->do_upload('image')) {
                    $error = array('error' => $this->upload->display_errors());
                    echo json_encode(array('RESULT' => $error, 'MESSAGE' => 'ERROR', 'STATUS' => 0));
                    exit();
                } else {
                    //Getting Uploaded Image File Data
                    $imgdata = $this->upload->data();
                    $feedback_img = $imgdata['file_name'];

                    //Configuring Thumbnail 
                    $config_thumb['image_library'] = 'gd2';
                    $config_thumb['source_image'] = $config['upload_path'] . $imgdata['file_name'];
                    $config_thumb['new_image'] = $config['thumb_upload_path'] . $imgdata['file_name'];
                    $config_thumb['create_thumb'] = TRUE;
                    $config_thumb['maintain_ratio'] = TRUE;
                    $config_thumb['thumb_marker'] = '_thumb';
                    $config_thumb['width'] = $this->config->item('feedback_thumb_width');
                    $config_thumb['height'] = $this->config->item('feedback_thumb_height');

                    //Loading Image Library
                    $this->load->library('image_lib', $config_thumb);

                    //Creating Thumbnail
                    if(!$this->image_lib->resize()) {
                        $error = array('error' => $this->image_lib->display_errors());
                        echo json_encode(array('RESULT' => $error, 'MESSAGE' => 'ERROR', 'STATUS' => 0));
                        exit();
                    } else {
                        $feedback_thumb = $imgdata['raw_name'].'_thumb'.$imgdata['file_ext'];
                    }
                    
                    // AWS S3 Upload
                    $thumb_file_path = str_replace("main", "thumbs", $imgdata['file_path']);
                    $thumb_file_name = $config['thumb_upload_path'] . $imgdata['raw_name'].'_thumb'.$imgdata['file_ext'];
                    
                    $this->s3->putObjectFile($imgdata['full_path'], S3_BUCKET, $config_thumb['source_image'], S3::ACL_PUBLIC_READ);
                    $this->s3->putObjectFile($thumb_file_path.$feedback_thumb, S3_BUCKET, $thumb_file_name, S3::ACL_PUBLIC_READ);
//                  echo $s3file = S3_CDN.$config_thumb['source_image'];
//                  echo "<br/>";
//                  echo $s3file = S3_CDN.$thumb_file_name; exit();

                    // Remove File from Local Storage
                    unlink($config_thumb['source_image']);
                    unlink($thumb_file_name);
                }
            }
            
            // Video Upload Start
            if (isset($_FILES['video']['name']) && $_FILES['video']['name'] != '') {
                $config_video['upload_path'] = $this->config->item('feedback_video_upload_path');
                $config_video['thumb_upload_path'] = $this->config->item('feedback_thumb_upload_path');
                $config_video['max_size'] = $this->config->item('feedback_video_max_size');
                $config_video['allowed_types'] = $this->config->item('feedback_allowed_video_types');
                $config_video['overwrite'] = FALSE;
                $config_video['remove_spaces'] = TRUE;
                $config_video['file_name'] = time();    
                    
                $this->load->library('upload', $config_video);
                $this->upload->initialize($config_video);
                
                if (!$this->upload->do_upload('video')) {
                    $error = array('error' => $this->upload->display_errors());
                    echo json_encode(array('RESULT' => $error, 'MESSAGE' => 'ERROR', 'STATUS' => 0));
                    exit();
                } else {
                    $video_details = $this->upload->data();
                    $feedback_video = $video_details['file_name'];
                    
                    // Generate video thumbnail
                    $video_path = $video_details['full_path'];
                    $thumb_name = $video_details['raw_name']."_video.jpg";
                    $thumb_path = $config_video['thumb_upload_path'].$thumb_name;

                    shell_exec("ffmpeg -itsoffset -3 -i ".$video_path."  -y -an -sameq -f image2 -s 400x270 ".$thumb_path."");
                    $feedback_thumb = $thumb_name;
                    
                    // AWS S3 Upload
                    $thumb_file_path = str_replace("video", "thumbs", $video_details['file_path']);
                    
                    $this->s3->putObjectFile($video_details['full_path'], S3_BUCKET, $config_video['upload_path'].$video_details['file_name'], S3::ACL_PUBLIC_READ);
                    $this->s3->putObjectFile($thumb_file_path.$feedback_thumb, S3_BUCKET, $thumb_path, S3::ACL_PUBLIC_READ);
//                  echo $s3file = S3_CDN.$config_video['upload_path'].$video_details['file_name'];
//                  echo "<br/>";
//                  echo $s3file = S3_CDN.$thumb_path; exit();

                    // Remove File from Local Storage
                    unlink($config_video['upload_path'].$video_details['file_name']);
                    unlink($thumb_path);
                }
            }
            // Video Upload End

            $gettitle = $this->common->select_data_by_id('feedback', 'feedback_id', $replied_to, 'title_id', '');
            if (count($gettitle) > 0) {
                $insert_array['title_id'] = $gettitle[0]['title_id'];
            }

            $insert_array['user_id'] = $user_id;
            $insert_array['feedback_cont'] = $feedback;
            if($feedback_img != '') {
                $insert_array['feedback_img'] = $feedback_img;
            }
            if($feedback_thumb != '') {
                $insert_array['feedback_thumb'] = $feedback_thumb;
            }
            if($feedback_video != '') {
                $insert_array['feedback_video'] = $feedback_video;
            }
            $insert_array['location'] = $location;
            $insert_array['replied_to'] = $replied_to;
            $insert_array['datetime'] = date('Y-m-d H:i:s');
            
            if($country != '') {
                $insert_array['country'] = $country;
            } else {
                $getcountry = $this->common->select_data_by_id('users', 'id', $user_id, 'country', '');
                $insert_array['country'] = $getcountry[0]['country'];
            }

            $insert_result = $this->common->insert_data_getid($insert_array, $tablename = 'feedback');

            if ($insert_result) {
                $return_array['id'] = $insert_result;
                $return_array['feedback'] = $feedback;
                $return_array['location'] = $location;
                if($feedback_img != '') {
                    $return_array['image'] = S3_CDN . 'uploads/feedback/main/' . $feedback_img;
                }

                if($feedback_thumb != '') {
                    $return_array['thumb'] = S3_CDN . 'uploads/feedback/thumbs/' . $feedback_thumb;
                }
                
                if($feedback_video != '') {
                    $return_array['video'] = S3_CDN . 'uploads/feedback/video/' . $feedback_video;
                }
                
                if($country != '') {
                    $return_array['country'] = $country;
                }

                // Null to Empty String
                array_walk_recursive($return_array, function (&$item, $key) {
                    $item = null === $item ? '' : $item;
                });

                // Check / Add Notification for users
                $this->common->notification('', $user_id, $title_id = '', $insert_result, $replied_to, 4);

                echo json_encode(array('RESULT' => $return_array, 'MESSAGE' => $this->lang->line('success_reply_submit'), 'STATUS' => 1));
                exit();
            } else {
                echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_reply_submit'), 'STATUS' => 0));
                exit();
            }
        }
    }
    
    // Feedback Detail
    function feedback_detail() {
        $user_id = $this->input->post('user_id');
        $feedback_id = $this->input->post('feedback_id');

        if ($user_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            exit();
        }
        if ($feedback_id == '') {
            $error = 1;
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter feedback id', 'STATUS' => 0));
            exit();
        }
        
        // Get Feedback Details
        $return_array = $this->common->getFeedbackDetail($user_id, $feedback_id);

        // Get all replies for this feedback
        $contition_array = array('status' => 1, 'replied_to' => $feedback_id, 'feedback.deleted' => 0, 'feedback.status' => 1);
        $replies = $this->common->select_data_by_condition('feedback', $contition_array, 'feedback_id', $sortby = 'feedback.datetime', $orderby = 'DESC', $limit = '', $offset = '', $join_str = array(), $group_by = '');
        
        $return_array['replies'] = array();
        foreach($replies as $reply) {
            $feedback = $this->common->getFeedbackDetail($user_id, $reply['feedback_id']);
            array_push($return_array['replies'], $feedback);
        }

        // Null to Empty String
        array_walk_recursive($return_array, function (&$item, $key) {
            $item = null === $item ? '' : $item;
        });
        
        echo json_encode(array('RESULT' => $return_array, 'MESSAGE' => 'SUCCESS', 'STATUS' => 1));
        exit();
    }

    // Get Languages (Languages Screen)
    function languages() {
        $user_id = $this->input->post('user_id');

        if ($user_id == '') {
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            die();
        } else {
            $contition_array = array('lang_status' => 1);
            $languages = $this->common->select_data_by_condition('languages', $contition_array, $data = 'lang_id, lang_name');

            if(!empty($languages)) {
                $return_array = array();
                foreach($languages as $lang) {  
                    $return = array();
                    $return['lang_id'] = $lang['lang_id'];
                    $return['lang_name'] = $lang['lang_name'];
                
                    // Check for user preferred language
                    $contition_array = array('id' => $user_id);       
                    $user_lang = $this->common->select_data_by_condition('users', $contition_array, $data = 'lang_id');
                    if($user_lang[0]['lang_id'] == $lang['lang_id']) {
                        $return['selected'] = true;
                    } else {
                        $return['selected'] = false;    
                    }
                    
                    array_push($return_array, $return);
                }
                
                echo json_encode(array('RESULT' => $return_array, 'MESSAGE' => 'SUCCESS', 'STATUS' => 1));
                die();
            } else {
                echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_something_wrong'), 'STATUS' => 0));
                die();
            }
        }
    }

    // Set Language
    function set_language() {
        $user_id = $this->input->post('user_id');
        $lang_id = $this->input->post('lang_id');

        if ($user_id == '') {
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please enter user id', 'STATUS' => 0));
            die();
        }

        if ($lang_id == '') {
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => 'Please select your language', 'STATUS' => 0));
            die();
        }

        $data = array('lang_id' => $lang_id);
        $update_settings = $this->common->update_data($data, 'users', 'id', $user_id);

        if($update_settings) {
            $return_array['user_id'] = $user_id;
            $return_array['lang_id'] = $lang_id;

            echo json_encode(array('RESULT' => $return_array, 'MESSAGE' => $this->lang->line('success_language_set'), 'STATUS' => 1));
            die();
        } else {
            echo json_encode(array('RESULT' => array(), 'MESSAGE' => $this->lang->line('error_something_wrong'), 'STATUS' => 0));
            die();
        }
    }
    
    // Push Notification
    function push_notification() {
        
        // Message payload
        $msg_payload = array (
            'mtitle' => 'Feedbacker',
            'mdesc' => 'This is test push notification',
            'title_id' => '',
            'feedback_id' => 568
        );
        
        // For Android
        $regId = array('dDwjvBi6qqk:APA91bFFpFpWYz19ZzWy78XxApchKu17Ht1Vf4tAlHoJURMIje6ofkTXtBt0SY1TFqbtggXxDk5yu2m6GHZhCSudlITlwG2M0rUz1cxNqpsTgvujVYnhzdILmHHRqPKYLhITfPELYsQR');
        
        // For iOS
        // $deviceToken = 'b77db4458b084daacdec602865cd274114affc24995a4d612dd9f4cda8f6c13a'; // This will be dynamic
        $deviceToken = '21063305a31fc6f9d66bf33558636bae850e31f68cb2ff64afd78015d3442b20';
        // For WP8
        // $uri = 'http://s.notify.live.net/u/1/sin/HmQAAAD1XJMXfQ8SR0b580NcxIoD6G7hIYP9oHvjjpMC2etA7U_xy_xtSAh8tWx7Dul2AZlHqoYzsSQ8jQRQ-pQLAtKW/d2luZG93c3Bob25lZGVmYXVsdA/EKTs2gmt5BG_GB8lKdN_Rg/WuhpYBv02fAmB7tjUfF7DG9aUL4';
        
        // Replace the above variable values
        // $responseText = $this->pushNotifications->android($msg_payload, $regId);
        //$this->pushNotifications->WP8($msg_payload, $uri);
        $responseText = $this->pushNotifications->iOS($msg_payload, $deviceToken);  
        echo json_encode(array('RESULT' => array(), 'MESSAGE' => $responseText, 'STATUS' => 1));
        die();
    }
    
    function aws_s3() {
        // List Buckets
        // var_dump($this->s3->listBuckets());
        
        $name = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $tmp = $_FILES['file']['tmp_name'];

        $i = strrpos($name,".");
        if (!$i) { return ""; } 
        
        $l = strlen($name) - $i;
        $ext = substr($name,$i+1,$l);
        
        if(strlen($name) > 0) {
         
        if($size<(1024*1024))
        {
        //Rename image name. 
        $actual_image_name = "uploads/feedback/thumbs/".time().".".$ext;
        if($this->s3->putObjectFile($tmp, S3_BUCKET, $actual_image_name, S3::ACL_PUBLIC_READ) )
        {
        $msg = "S3 Upload Successful."; 
        $s3file= S3_CDN.$actual_image_name;
        //echo "<img src='$s3file' style='max-width:400px'/><br/>";
        echo '<b>S3 File URL:</b>'.$s3file;
        
        }
        else
        $msg = "S3 Upload Fail.";
        
        
        }
        else
        $msg = "Image size Max 1 MB";
        
        }
    }

}