<?php

class Settings extends CI_Model
{
    //get all settings details
    function getSettingDetails()
    {
        $this->db->select('setting_id,setting_name,setting_value');
        $this->db->where('setting_id !=',8);
        return $this->db->get('settings')->result_array();
    }
    
    //get admin setting by id
    function getSettingById($setting_id)
    {
        $this->db->select('setting_id,setting_name,setting_value');
        $this->db->where('setting_id',$setting_id);
        return $this->db->get('settings')->result_array();
    }
    
    //update setting value
    function update_setting($setting_id,$setting_val)
    {
        $this->db->where('setting_id',$setting_id);
        if($this->db->update('settings',$setting_val))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
    function getSemDetails()
    {
        $this->db->select('semid,semfieldname,semfieldvalue');
        return $this->db->get('sem')->result_array();
    }
    
    //get admin setting by id
    function getSemById($semid)
    {
        $this->db->select('semid,semfieldname,semfieldvalue');
        $this->db->where('semid',$semid);
        return $this->db->get('sem')->result_array();
    }
    
    //update setting value
    function update_sem($semid,$semfieldvalue)
    {
        $this->db->where('semid',$semid);
        if($this->db->update('sem',$semfieldvalue))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
    
    
}