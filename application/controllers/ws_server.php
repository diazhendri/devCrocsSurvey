<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ws_server extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->library("soap");
		$this->load->model("Ws_model");		
	}
	function index()
	{
		$server = new soap_server();
		$server->configureWSDL('WS Kartu Tani','urn:dropboxWS',site_url('ws_server'));
		$server->wsdl->addComplexType('inqCard','complexType','struct','all','',
			array(	'no_kartu' 		=> array('name' => 'no_kartu',		'type'=> 'xsd:string'),
					'mid' 		=> array('name' => 'mid',		'type'=> 'xsd:string')
			)
		);	
		$server->wsdl->addComplexType('inqHarga','complexType','struct','all','',
			array(
				'no_kartu' 		=> array('name' => 'no_kartu',		'type'=> 'xsd:string'),
				'mid' 		=> array('name' => 'mid',		'type'=> 'xsd:string')
			)
		);
		$server->wsdl->addComplexType('inqConfirm','complexType','struct','all','',
			array(
				'no_kartu' 		=> array('name' => 'no_kartu',		'type'=> 'xsd:string'),
				'mid' 		=> array('name' => 'mid',		'type'=> 'xsd:string'),
				'kode_pupuk' 	=> array('name' => 'kode_pupuk',		'type'=> 'xsd:string'),
				'kg_beli' 		=> array('name' => 'nominal_beli',		'type'=> 'xsd:string'),
				'komoditi' 		=> array('name' => 'komoditi',		'type'=> 'xsd:string')
			)
		);
		$server->wsdl->addComplexType('inqProsesPembelian','complexType','struct','all','',
			array(
				'no_kartu' 		=> array('name' => 'no_kartu',		'type'=> 'xsd:string'),
				'mid' 		=> array('name' => 'mid',		'type'=> 'xsd:string'),
				'kode_pupuk' 	=> array('name' => 'kode_pupuk',		'type'=> 'xsd:string'),
				'nominal_beli' 		=> array('name' => 'nominal_beli',		'type'=> 'xsd:string'),
				'komoditi' 		=> array('name' => 'komoditi',		'type'=> 'xsd:string'),
				'seqnum' 		=> array('name' => 'seqnum',		'type'=> 'xsd:string'),
				'tid' 		=> array('name' => 'tid',		'type'=> 'xsd:string')
			)
		);
		$server->wsdl->addComplexType('inqReversalPembelian','complexType','struct','all','',
			array(
				'no_kartu' 		=> array('name' => 'no_kartu',		'type'=> 'xsd:string'),
				'mid' 		=> array('name' => 'mid',		'type'=> 'xsd:string'),
				'seqnum' 		=> array('name' => 'seqnum',		'type'=> 'xsd:string'),
				'tid' 		=> array('name' => 'tid',		'type'=> 'xsd:string')
			)
		);
		$server->register("reversalPembelian",
				array('inputData' => 'tns:inqReversalPembelian'),
				array('resultData' => 'xsd:string'),
				'urn:dropbox',
				'urn:dropbox#rejectDropbox',
				'rpc',
				'encoded',
				'<a href="'.site_url('dbws').'/docws" target="blank">'.site_url('dbws').'/docws</a>');
		$server->register("inquiryHarga",
				array('inputData' => 'tns:inqHarga'),
				array('resultData' => 'xsd:string'),
				'urn:dropbox',
				'urn:dropbox#rejectDropbox',
				'rpc',
				'encoded',
				'<a href="'.site_url('dbws').'/docws" target="blank">'.site_url('dbws').'/docws</a>');
				
		$server->register("inquirySaldo",
				array('inputData' => 'tns:inqCard'),
				array('resultData' => 'xsd:string'),
				'urn:dropbox',
				'urn:dropbox#rejectDropbox',
				'rpc',
				'encoded',
				'<a href="'.site_url('dbws').'/docws" target="blank">'.site_url('dbws').'/docws</a>');
				
		$server->register("inquiryPembelian",
				array('inputData' => 'tns:inqConfirm'),
				array('resultData' => 'xsd:string'),
				'urn:dropbox',
				'urn:dropbox#rejectDropbox',
				'rpc',
				'encoded',
				'<a href="'.site_url('dbws').'/docws" target="blank">'.site_url('dbws').'/docws</a>');
				
		$server->register("prosesPembelian",
				array('inputData' => 'tns:inqProsesPembelian'),
				array('resultData' => 'xsd:string'),
				'urn:dropbox',
				'urn:dropbox#rejectDropbox',
				'rpc',
				'encoded',
				'<a href="'.site_url('dbws').'/docws" target="blank">'.site_url('dbws').'/docws</a>');
		
		function reversalPembelian($param)
		{
			$CI =& get_instance();			
			$ret = Array();
			$ret["responsecode"] 	= "999";
			$ret["responsedesc"] 	= "Unknown Error";
			try
			{
				if(!empty($param['seqnum']))
				{					
					$ret = $CI->Ws_model->reversal_pembelian_pupuk($param);
				}
				else 
				{
					$ret["responsedesc"] = "Seqnum kosong.";
					$ret["responsecode"] = "003";
				}
			}
			catch(Exception $e)
			{
				$ret["responsedesc"] = "error";
				$ret["responsecode"] = "004";
			}
			$CI->Ws_model->insertActivity(array('ip_client'=>$CI->libs->get_client_ip(),'ket_user'=>json_encode($ret),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($param), 'fitur' => 'reversal pembelian'));
			return json_encode($ret);
		}		
		function prosesPembelian($param)
		{		
			$CI =& get_instance();			
			$ret = Array();
			$ret["responsecode"] 	= "999";
			$ret["responsedesc"] 	= "Unknown Error";
			
			try
			{
				if(!empty($param['no_kartu']))
				{					
					$param['kg_beli'] = $CI->Ws_model->convert_nominal_to_kg($param['nominal_beli'],$param['kode_pupuk']);
					$ret = $CI->Ws_model->get_proses_pembelian_pupuk($param);
					$ret["rupiah_beli"] = $param['nominal_beli'];
				}
				else 
				{
					$ret["responsedesc"] = "No kartu kosong.";
					$ret["responsecode"] = "003";
				}
			}
			catch(Exception $e)
			{
				$ret["responsedesc"] = "error";
				$ret["responsecode"] = "004";
			}
			$CI->Ws_model->insertActivity(array('ip_client'=>$CI->libs->get_client_ip(),'ket_user'=>json_encode($ret),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($param), 'fitur' => 'proses pembelian'));
			return json_encode($ret);
		}		
		function inquiryPembelian($param)
		{		
			$CI =& get_instance();			
			$ret = Array();
			$ret["responsecode"] 	= "999";
			$ret["responsedesc"] 	= "Unknown Error";
			$ret["nama_kt"] 	= "0";
			$ret["sisa_kg"] 	= "0";
			$ret["kg_beli"] 	= "0";
			$ret["rupiah_beli"] 	= "0";
			try
			{
				if(!empty($param['no_kartu']))
				{					
					$validasi =  $CI->Ws_model->get_validasi_petani_mid($param);
					//$param['kg_beli'] = $CI->Ws_model->convert_nominal_to_kg($param['nominal_beli'],$param['kode_pupuk']);
					if($validasi)
					{
						$result = $CI->Ws_model->get_informasi_pembelian_pupuk($param);
						$status = "";
						$res = $result->row();						
						$sisa_kg = 0;
						if($param['kode_pupuk'] == '01')
							$sisa_kg = ($res->urea/100) - $param['kg_beli'];
						else if($param['kode_pupuk'] == '02')
							$sisa_kg = ($res->sp/100) - $param['kg_beli'];
						else if($param['kode_pupuk'] == '03')
							$sisa_kg = ($res->za/100) - $param['kg_beli'];
						else if($param['kode_pupuk'] == '04')	
							$sisa_kg = ($res->npk/100) - $param['kg_beli'];
						else if($param['kode_pupuk'] == '05')		
							$sisa_kg = ($res->organik/100) - $param['kg_beli'];						
						if($sisa_kg < 0)
						{
							$ret['responsecode'] 	= "005";
							$ret['responsedesc'] 	= "Kuota anda kurang";
						}
						else
						{
							$ret['responsecode'] 	= "001";
							$ret['responsedesc'] 	= "Inquiry Successful";
							$ret['nama_petani'] = $res->nama;
							$ret['nama_kt'] = $res->nama_kt;
							$ret['sisa_kg'] = $sisa_kg;
							$ret['kg_beli'] = number_format($param['kg_beli'],"2",".","");
							$ret['rupiah_beli'] = $CI->Ws_model->get_harga_pembelian($param);
						}
					}
					else
					{
						$ret['responsecode'] 	= "002";
						$ret['responsedesc']  	= "No kartu tidak ditemukan";
					}
				}
				else 
				{
					$ret["responsedesc"] = "No kartu kosong.";
					$ret["responsecode"] = "003";
				}
			}
			catch(Exception $e)
			{
				$ret["responsedesc"] = "error";
				$ret["responsecode"] = "004";
			}
			$CI->Ws_model->insertActivity(array('ip_client'=>$CI->libs->get_client_ip(),'ket_user'=>json_encode($ret),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($param), 'fitur' => 'inq pembelian'));
			return json_encode($ret);
		}	
		function inquiryHarga($param)
		{
			$CI =& get_instance();			
			$ret = Array();
			$ret["responsecode"] 	= "999";
			$ret["responsedesc"] 	= "Unknown Error";
			$ret["jenis_pupuk"] 	= "";
			
			try
			{
				if(!empty($param['no_kartu']))
				{
					$result = $CI->Ws_model->get_harga_pupuk();					
					$status = "";
					if($result->num_rows() > 0)
					{
						$res = $result->result();
						$ret['responsecode'] 	= "001";
						$ret['responsedesc'] 	= "Inquiry Successful";
						$kode_bantuan = array();
						foreach($res as $id=>$val) {
							$kode_bantuan[$id]['nama_pupuk'] 		= $val->nama_pupuk;
							$kode_bantuan[$id]['harga'] 	= $val->harga;
						}	
						$ret['jenis_pupuk'] = $kode_bantuan;
					}					
				}
				else 
				{
					$ret["responsedesc"] = "No kartu kosong.";
					$ret["responsecode"] = "003";
				}
			}
			catch(Exception $e)
			{
				$ret["responsedesc"] = "error";
				$ret["responsecode"] = "004";
			}
			$CI->Ws_model->insertActivity(array('ip_client'=>$CI->libs->get_client_ip(),'ket_user'=>json_encode($ret),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($param), 'fitur' => 'info harga'));
			return json_encode($ret);
		}
		function inquirySaldo($param)
		{
			$CI =& get_instance();			
			$ret = Array();
			$ret["responsecode"] 	= "999";
			$ret["responsedesc"] 	= "Unknown Error";
			$ret["urea"] 	= "0";
			$ret["sp"] 	= "0";
			$ret["za"] 	= "0";
			$ret["npk"] 	= "0";
			$ret["organik"] 	= "0";
			$ret["nama_petani"] = "";
			$ret["nama_kt"] 	= "";
			try
			{
				if(!empty($param['no_kartu']))
				{
					$result = $CI->Ws_model->get_kuota_pupuk($param);
					if($result->num_rows() > 0)
					{
						$status = "";
						$res = $result->row();
						$ret['responsecode'] 	= "001";
						$ret['responsedesc'] 	= "Inquiry Successful";						
						$ret['urea'] = $res->urea;
						$ret['sp'] = $res->sp;
						$ret['za'] = $res->za;
						$ret['npk'] = $res->npk;
						$ret['organik'] = $res->organik;
						$ret['nama_petani'] = $res->nama;
						$ret['nama_kt'] = $res->nama_kt;
					}
					else
					{
						$ret['responsecode'] 	= "002";
						$ret['responsedesc']  	= "Anda tidak terdaftar di kelompok tani ini";
					}
				}
				else 
				{
					$ret["responsedesc"] = "No kartu kosong.";
					$ret["responsecode"] = "003";
				}
			}
			catch(Exception $e)
			{
				$ret["responsedesc"] = "error";
				$ret["responsecode"] = "004";
			}
			$CI->Ws_model->insertActivity(array('ip_client'=>$CI->libs->get_client_ip(),'ket_user'=>json_encode($ret),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($param), 'fitur' => 'info saldo'));
			return json_encode($ret);
		}
		$server->service(file_get_contents("php://input"));
		exit();
	}
	
}