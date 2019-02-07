<?php defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'libraries/REST_Controller.php');
class Service_prosw extends REST_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model("Service_prosw_model");		
	}
	public function index_post()
	{
		$datapost = json_decode($this->post('request'));
		switch($datapost->requestMethod){
			case 'inquiryHarga':
				$this->inquiryHarga($datapost);
				break;
			case 'inquirySaldo':					
				$this->inquirySaldo($datapost);
				break;
			case 'inquiryPembelian':
				$this->inquiryPembelian($datapost);
				break;
			case 'prosesPembelian':
				$this->prosesPembelian($datapost);
				break;		
			case 'reversalPembelian':
				$this->reversalPembelian($datapost);
				break;
			default:
				$this->response((object)array('responseCode'=>'08','responseDesc'=>'Unknown Request Method['.$datapost->requestMethod.']','responseData'=>array()), 404);
		}
	}
	function reversalPembelian($datapost)/*{"requestMethod":"reversalPembelian","no_kartu":"6013010628025364","mid":"000001017850000","tid":"345435","seqnum":"6543"}*/
	{
		$result = new stdClass;
		try
		{
			//$param['seqnum_to_reverse'] = $datapost->seqnum_to_reverse;
			$param['tid'] = $datapost->tid;
			$param['seqnum'] = $datapost->seqnum;
			$param['no_kartu'] = $datapost->no_kartu;
			$param['mid'] = $datapost->mid;
			$output = $this->Service_prosw_model->reversal_pembelian_pupuk($param);
			$result->responseCode= $output['responsecode'];
			$result->responseDesc= $output['responsedesc'];
		}
		catch(Exception $e){
			$result->responseCode='04';
			$result->responseDesc = $e->getMessage();
		}
		$this->Service_prosw_model->insertActivity(array('ip_client'=>$this->libs->get_client_ip(),'ket_user'=>json_encode($result),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($datapost), 'fitur' => 'Reversal Pembelian'));
		$this->response($result);
	}
	function prosesPembelian($datapost) /*{"requestMethod":"prosesPembelian","no_kartu":"6013010628025364","mid":"000001017850000","kode_pupuk":"01","kg_beli":"1","komoditi":"01","seqnum":"666","tid":"345435"}*/
	{
		$result = new stdClass;
		try
		{
			$param['no_kartu'] = $datapost->no_kartu;
			$param['mid'] = $datapost->mid;
			$param['kode_pupuk'] = $datapost->kode_pupuk;
			$param['kg_beli'] = $datapost->kg_beli;
			$param['komoditi'] = $datapost->komoditi;
			$param['seqnum'] = $datapost->seqnum;
			$param['tid'] = $datapost->tid;
			$output = $this->Service_prosw_model->get_proses_pembelian_pupuk($param);
			$result->responseCode= $output['responsecode'];
			$result->responseDesc= $output['responsedesc'];
			$result->nama_petani = $output['nama_petani'];
			$result->nama_kt = $output['nama_kt'];
			$result->sisa_kg = number_format($output['sisa_kg']/100,"2",".","");
			$result->kg_beli = number_format($output['kg_beli'],"2",".","");
			$result->rupiah_beli = number_format($this->Service_prosw_model->get_harga_pembelian($param),"2",".","");
		}
		catch(Exception $e){
			$result->responseCode='04';
			$result->responseDesc = $e->getMessage();
		}
		$this->Service_prosw_model->insertActivity(array('ip_client'=>$this->libs->get_client_ip(),'ket_user'=>json_encode($result),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($datapost), 'fitur' => 'Proses Pembelian'));
		$this->response($result);
	}
	function inquiryPembelian($datapost)/*{"requestMethod":"inquiryPembelian","no_kartu":"6013010628025364","mid":"000001017850000","kode_pupuk":"01","kg_beli":"10"}*/
	{
		$result = new stdClass;
		try{
			$param['no_kartu'] = $datapost->no_kartu;
			$param['mid'] = $datapost->mid;
			$param['kode_pupuk'] = $datapost->kode_pupuk;
			$param['kg_beli'] = $datapost->kg_beli;
			$validasi =  $this->Service_prosw_model->get_validasi_petani_mid($param);
			if($validasi)
			{
				$output = $this->Service_prosw_model->get_informasi_pembelian_pupuk($param);
				$res = $output->row();	
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
					$result->responseCode= "05";
					$result->responseDesc= "Kuota anda kurang";
				}
				else
				{
					$result->responseCode='00';
					$result->responseDesc='Inquiry data berhasil.';
					$result->nama_petani = $res->nama;
					$result->nama_kt = $res->nama_kt;
					$result->sisa_kg = number_format($sisa_kg,"2",".","");
					$result->kg_beli = number_format($param['kg_beli'],"2",".","");
					$result->rupiah_beli = $this->Service_prosw_model->get_harga_pembelian($param);
				}
			}
			else
			{
				$result->responseCode='02';
				$result->responseDesc='No Kartu tidak terdaftar pada mid ini.';	
			}
		}catch(Exception $e){
			$result->responseCode='04';
			$result->responseDesc = $e->getMessage();
		}
		$this->Service_prosw_model->insertActivity(array('ip_client'=>$this->libs->get_client_ip(),'ket_user'=>json_encode($result),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($datapost), 'fitur' => 'inquiry Pembelian'));
		$this->response($result);
	}
	function inquirySaldo($datapost)/*{"requestMethod":"inquirySaldo","no_kartu":"6013010628025364","mid":"000001017850000"}*/
	{
		$result = new stdClass;
		try{
			$param['no_kartu'] = $datapost->no_kartu;
			$param['mid'] = $datapost->mid;
			$output = $this->Service_prosw_model->get_kuota_pupuk($param);
			if($output->num_rows() > 0)
			{
				$res = $output->row();
				$result->responseCode='00';
				$result->responseDesc='Inquiry data berhasil.';
				$result->urea = number_format($res->urea/100,"2",".","");
				$result->sp = number_format($res->sp/100,"2",".","");
				$result->za = number_format($res->za/100,"2",".","");
				$result->npk = number_format($res->npk/100,"2",".","");
				$result->organik = number_format($res->organik/100,"2",".","");
				$result->nama_petani = $res->nama;
				$result->nama_kt = $res->nama_kt;
			}
			else
			{
				$result->responseCode='02';
				$result->responseDesc='No Kartu tidak ditemukan.';
			}
		}catch(Exception $e){
			$result->responseCode='04';
			$result->responseDesc = $e->getMessage();
		}
		$this->Service_prosw_model->insertActivity(array('ip_client'=>$this->libs->get_client_ip(),'ket_user'=>json_encode($result),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($datapost), 'fitur' => 'info saldo'));
		$this->response($result);
	}
	function inquiryHarga($datapost)/*{"requestMethod":"inquiryHarga"}*/
	{
		$result = new stdClass;
		try{
			//throw new Exception('Division by zero.');
			$output = $this->Service_prosw_model->get_harga_pupuk();	
			if($output->num_rows() > 0)
			{
				$result->responseData=$output->result();
				$result->responseCode='00';
				$result->responseDesc='Inquiry data berhasil.';
			}
			else
			{
				$result->responseCode='02';
				$result->responseDesc='Inquiry data gagal.';
			}
			
		}catch(Exception $e){
			$result->responseCode='04';
			$result->responseDesc = 'Caught exception: '.$e->getMessage();
		}

		$this->Service_prosw_model->insertActivity(array('ip_client'=>$this->libs->get_client_ip(),'ket_user'=>json_encode($result),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($datapost), 'fitur' => 'info harga'));
		$this->response($result);
	}
}