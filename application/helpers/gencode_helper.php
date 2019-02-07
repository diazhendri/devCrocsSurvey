<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function applist(){
	$app = array(
				'SSB','LAS','BHM'
			);
	return $app;
}

function gencode_refno_penyetoran($id)
{
	$CI = & get_instance();
	$CI->load->model('helper_model');
	$code="0";
	$list_string = "123456789";
	$i=0;
	$len_string=8;
	// $code = substr(str_shuffle($list_string),0,2).$id.substr(str_shuffle($list_string),0,2);
	while($i<999999999999){
		if(!empty($id)) {
			$id = str_pad($id,4, "0", STR_PAD_LEFT);
			$code = substr(str_shuffle($list_string),0,4).$id.substr(str_shuffle($list_string),0,4);
			if($CI->helper_model->inquiry_refno_penyetoran($code) == 0){
				$result = $CI->helper_model->insert_refno_penyetoran($code,$id);
				if($result > 0) {
					break;
				}
			} else {
				break;
			}
			$i++;
		} else {
			break;
		}		
	}
	return $code;
}

function gencode_refno_penarikan($id)
{
	$CI = & get_instance();
	$CI->load->model('helper_model');
	$code="0";
	$list_string = "123456789";
	$i=0;
	$len_string=8;
	// $code = substr(str_shuffle($list_string),0,2).$id.substr(str_shuffle($list_string),0,2);
	while($i<999999999999){
		if(!empty($id)) {
			$id = str_pad($id,4, "0", STR_PAD_LEFT);
			$code = substr(str_shuffle($list_string),0,4).$id.substr(str_shuffle($list_string),0,4);
			if($CI->helper_model->inquiry_refno_penarikan($code) == 0){
				$result = $CI->helper_model->insert_refno_penarikan($code,$id);
				if($result > 0) {
					break;
				}
			} else {
				break;
			}
			$i++;
		} else {
			break;
		}		
	}
	return $code;
}

function gencode($param)
{
	$CI = & get_instance();
	$CI->load->model('helper_model');
	$code="0";
	$list_string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$i=0;
	$len_string=12;
	// $code = substr(str_shuffle($list_string),0,2).$id.substr(str_shuffle($list_string),0,2);
	while($i<999999999999){
		$param = str_pad($param,4, "0", STR_PAD_LEFT);
		$code = 'KT'.substr(str_shuffle($list_string),0,3).$param.substr(str_shuffle($list_string),0,3);
		if($CI->helper_model->inquiry_refno($code) == 0){
			break;
		} else {
			
		}
		$i++;
	}
	return $code;
}