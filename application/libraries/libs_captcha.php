<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Libs_captcha {
	var $CI;
	
	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->helper('captcha');
	}
	
	function _create_captcha($height)
	{
		$dir = "images/tmp_captcha";
		$enc_string = "0123456789";
		$len_string = 4;
		$img_width = 160;
		$img_height = $height;
		$expiration = 1800;
		$ipaddr = $this->CI->libs->get_client_ip();
		$options = array(
						'word' => substr(str_shuffle($enc_string),0,$len_string),
						'img_path' => './'.$dir.'/',
						'img_url' => site_url().$dir.'/',
						'img_width' => $img_width,
						'img_height' => $img_height,	
						'expiration' => $expiration
					);
		$cap = create_captcha($options);
		$image = $cap['image'];
		// $this->CI->session->set_userdata('captchakata', $cap['word']);
		$_SESSION['captchakata'] = $cap['word'];
		// $this->CI->session->set_userdata('capimg', $cap['time'].'.jpg');
		$_SESSION['capimg'] = $cap['time'].'.jpg';
		
		return $image;
	}
	
	function check_captcha($string)
	{
		if($string==$_SESSION['captchakata'])
		{
			$array_items = array('captchakata' => '', 'capimg' => '');
			$this->CI->session->unset_userdata($array_items);
			return TRUE;
		}else{
			return FALSE;
		}
	}
}

/* End of file Menu.php */