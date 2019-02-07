<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mysession {
	var $CI;
	
	function __construct()
	{
		$this->CI =& get_instance();
	}
	function check_no_session()
	{
		if(!isset($_SESSION['is_login'])){
			redirect('/home/logout');
		}
	}
}