<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function get_sektor_usaha_pangan()
{
	$jenis = array(
				array ('id'=>'1', 'deskripsi'=>'PERDAGANGAN HASIL PERTANIAN'),
				array ('id'=>'2', 'deskripsi'=>'INDUSTRI HASIL PERTANIAN'),
				array ('id'=>'3', 'deskripsi'=>'BUDIDAYA PERTANIAN'),
				array ('id'=>'4', 'deskripsi'=>'PERKEBUNAN'),
				array ('id'=>'5', 'deskripsi'=>'PERIKANAN'),
				array ('id'=>'6', 'deskripsi'=>'PETERNAKAN'),
				array ('id'=>'7', 'deskripsi'=>'JASA'),
				array ('id'=>'8', 'deskripsi'=>'LAINNYA')
			);			
	return $jenis;
}

function get_keperluan_kredit_pangan()
{
	$jenis = array(
				array ('id'=>'1', 'deskripsi'=>'MODAL KERJA'),
				array ('id'=>'2', 'deskripsi'=>'INVESTASI')
			);			
	return $jenis;
}

function send_ws($branch,$email,$appname,$jenis_pinjaman,$content, $status='1') {
	$CI =& get_instance();
	$CI->load->model('helper_model');
	$client = new SoapClient("http://10.35.65.111/SKPP/dbws?wsdl", array("trace" => 1, 'cache_wsdl' => WSDL_CACHE_NONE));
	
	$id_jenis = $CI->helper_model->get_exp_kredit($jenis_pinjaman);
	$expired_date = '';
	$expired_date_pimp = '';
	$jenis = '';
	if(!empty($id_jenis)) {
		$expired_date = date('Y-m-d', strtotime("+".$id_jenis->expired." days"));
		$expired_date_pimp = date('Y-m-d', strtotime("+".$id_jenis->expired_pimpinan." days"));
		$jenis = $id_jenis->id_jenis_pinjaman;
	}
	
	if($status == ''){
		$status = '1';
	}
	
	$param['appname'] = $appname;
	$param['email'] = $email;
	$param['jenis_pinjaman'] = $jenis;
	$param['branch'] = $branch;
	$param['expdate'] = $expired_date;
	$param['expdate_pimp'] = $expired_date_pimp;
	$param['content'] = $content;
	$param['status'] = $status;
	
	$result = $client->insertDropbox($param);
	return $result;
}

function update_ws($appname, $trxid, $refno, $branch){
	$CI =& get_instance();
	$client = new SoapClient("http://10.35.65.111/SKPP/dbws?wsdl", array("trace" => 1, 'cache_wsdl' => WSDL_CACHE_NONE));
	
	$param['branch'] = $branch;
	$param['appname'] = $appname;
	$param['trxid'] = $trxid;
	$param['refno'] = $refno;
	$result = $client->updateStatusDropbox($param); 	
	return $result;
}