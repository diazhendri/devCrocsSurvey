<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Libs {
	var $CI;
	
	function __construct() {
		$this->CI =& get_instance();
	}
	function check_icon_number($flag)
	{
		if ($flag == 0)
			return '<span class="glyphicon glyphicon-ok"></span>';
		else
			return '<span class="glyphicon glyphicon-remove"></span>';
	}
	function check_main_template($view='home/welcome_message',$data='')
	{
		if($this->CI->input->post('mt')==1){
			$this->CI->load->view($view,$data);
		}else{
			$data['main_content'] = $this->CI->load->view($view,$data,TRUE);
			$this->CI->load->view('theme/new_template',$data);
		}
	}
	
	function num2namemonthen($num)
	{
		$num = '00'.$num;
		$num = substr($num,-2);
		switch($num)
		{
			case '01': return 'January'; break;
			case '02': return 'February'; break;
			case '03': return 'March'; break;
			case '04': return 'April'; break;
			case '05': return 'May'; break;
			case '06': return 'June'; break;
			case '07': return 'July'; break;
			case '08': return 'August'; break;
			case '09': return 'September'; break;
			case '10': return 'October'; break;
			case '11': return 'November'; break;
			case '12': return 'Desember'; break;
			default : return ''; break;
		}
	}
	
	function num2namemonthid($num)
	{
		$num = '00'.$num;
		$num = substr($num,-2);
		switch($num)
		{
			case '01': return 'Januari'; break;
			case '02': return 'Februari'; break;
			case '03': return 'Maret'; break;
			case '04': return 'April'; break;
			case '05': return 'Mei'; break;
			case '06': return 'Juni'; break;
			case '07': return 'Juli'; break;
			case '08': return 'Agustus'; break;
			case '09': return 'September'; break;
			case '10': return 'Oktober'; break;
			case '11': return 'November'; break;
			case '12': return 'Desember'; break;
			default : return ''; break;
		}
	}
	
	function date2dMYid($date)
	{
		$bulan = $this->num2namemonthid(substr($date,5,2));
		return substr($date,8,2).' '.$bulan.' '.substr($date,0,4);
	}
	
	function get_client_ip() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';

		return $ipaddress;
	}
	
	function seoURL($string){
		return preg_replace('/[^\w-]/', '', $string);
	}
	
	function alphaSpaceOnly($string){
		return preg_replace('/[^\w ]/', '', $string);
	}
	
	function quote_smart($string)
	{
		$string = html_entity_decode($string);
		$string = strip_tags($string);
		$string = trim($string);
		$string = htmlentities($string);
		$string = preg_replace('/\s+/', ' ',$string); // Removing more than one space/Tab.

		// Quote if not integer
		if (!is_numeric($string)) 
		{
			$string = mysql_real_escape_string($string);
		}

		return $string;
	}
	
	function highlight_string($string='',$length_char=0,$link='')
	{
		$string = strip_tags($string);
		$string = trim($string);
		if(strlen($string)>$length_char){
			$string = substr($string,0,$length_char);
			$string = substr($string,0,strrpos($string,' ')).' ... '.$link; 
		}
		return $string;
	}
	
	function myEncode($s){
		return urlencode(base64_encode($this->CI->encrypt->encode($s)));
	}
	function myDecode($s){
		return $this->CI->encrypt->decode(base64_decode(urldecode($s)));
	}
	
	function breadcrumb($param = "")
	{
		$bread = '<div id="braed-wrapper">';
		$bread .= '<ul id="bread-md" class="breadcrumb">';
		$bread .= '<li><a href="'.base_url("home").'" class="ajax"><i class="fa fa-home"></i> Beranda</a></li>';
		$i = count($param);
		if(!empty($param)) {
			foreach($param as $id=>$val) { $i--;
				$link = ($val!="#")?base_url($val):$val;
				$txt = (strlen($id) > 15)?'...'.substr($id,-15):$id;
				if($i != 0) {
					$bread .= '<li><a href="'.$link.'" class="ajax" >'.$txt.'</a></li>';
				} else {
					$bread .= '<li>'.$txt.'</li>';
				}
			}
		}
		
		$bread .= '</ul>';
		$bread .= '<div id="bread-sm" class="btn-group">';
		$bread .= '<button class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-target="#bread-sm">';
		$bread .= 'Breadcrumb <span class="caret"></span></button>';
		$bread .= '<ul id="bread_down" class="dropdown-menu">';
		$bread .= '<li><a href="'.base_url("home").'" class="ajax"><i class="fa fa-home"></i> Beranda</a></li>';
		if(!empty($param)) {
			foreach($param as $id=>$val) { $i--;
				$link = ($val!="#")?base_url($val):$val;
				$txt = (strlen($id) > 15)?'...'.substr($id,-15):$id;
				if($i != 0) {
					$bread .= '<li><a href="'.$link.'" class="ajax" >'.$txt.'</a></li>';
				} else {
					$bread .= '<li>'.$txt.'</li>';
				}
			}
		}
		$bread .= '</ul>';
		$bread .= '</div>';
		$bread .= '</div>';
		
		return $bread;
	}
	
}

/* End of file Menu.php */