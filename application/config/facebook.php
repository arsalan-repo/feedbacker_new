<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	//facebook api information goes here


	$config['api_id']       = '1771197583195502';
	$config['api_secret']   = '92847c3b75e50fcd15042419c74874dc';
	$config['redirect_url'] = base_url('signin/fbauth');  //change this if you are not using my fb controller
	$config['logout_url']   = base_url('signin/');          //User will be redirected here when he logs out.
	$config['permissions']  = array(
                                        'email', 
                                        'public_profile'
                                        

                                      );
