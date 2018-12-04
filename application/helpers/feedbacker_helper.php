<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('strip_tags_with_whitespace'))
{
    function strip_tags_with_whitespace($string, $allowable_tags = null){
		$string = str_replace('<', ' <', $string);
		$string = strip_tags($string, $allowable_tags);
		$string = str_replace('  ', ' ', $string);
		//$string = trim($string);
		return $string;
	}  
}