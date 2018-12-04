<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }


    public function check_authentication($username, $admin_password) {
        $this->db->select('*');
        $this->db->where(array('username' => $username, 'password' => md5($admin_password)));
        $result = $this->db->get('admin')->result_array();

        if (!empty($result)) {
            if ($result[0]['username'] == $username && $result[0]['password'] == md5($admin_password)) {
                return $result;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

}
