<?php defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'libraries/REST_Controller.php');
class Service_prosw_wallet extends REST_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model("Service_wallet_model");		
	}
	public function index_post()
	{
		$datapost = json_decode($this->post('request'));
		switch($datapost->requestMethod){				
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
			case 'penambahanWallet':
				$this->penambahanWallet($datapost);
				break;	
				/*start deploy*/
			case 'rekonDeleteWallet':
				$this->rekonDeleteWallet($datapost);
				break;
			case 'rekonInsertWallet':
				$this->rekonInsertWallet($datapost);
				break;	
			case 'rekonPenguranganWallet':
				$this->rekonPenguranganWallet($datapost);
				break;	
				/*end deploy*/
			default:
				$this->response((object)array('responseCode'=>'08','responseDesc'=>'Unknown Request Method['.$datapost->requestMethod.']','responseData'=>array()), 404);
		}
	}
	/*start deploy*/
	function rekonDeleteWallet($datapost) /*{"requestMethod":"rekonDeleteWallet"
	,"no_kartu":"6013010628025364"
	,"remark":"9001"
	,"rekening_pinjaman":"213"
	}
	*/
	{
		$result = new stdClass;
		try
		{
			$param['no_kartu'] = $datapost->no_kartu;
			$param['remark'] = $datapost->remark;
			$param['rekening_pinjaman'] = $datapost->rekening_pinjaman;		
			$output = $this->Service_wallet_model->proses_delete_wallet($param);
			if($output)
			{			
				$result->responseCode='00';
				$result->responseDesc='Proses Delete Berhasil.';
			}
			else
			{
				$result->responseCode='06';
				$result->responseDesc='Proses Delete Gagal.';
			}
		}
		catch(Exception $e){
			$result->responseCode='04';
			$result->responseDesc = $e->getMessage();
		}
		$this->Service_wallet_model->insertActivity(array('ip_client'=>$this->libs->get_client_ip(),'ket_user'=>json_encode($result),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($datapost), 'fitur' => 'Rekon Delete Wallet'));
		$this->response($result);
	}
	function rekonInsertWallet($datapost) /*{"requestMethod":"rekonInsertWallet",	
	"no_kartu": "6013010628025364",
	"norek_simpanan": "6013010628025364",
	"norek_pinjaman": "6013010628025364",
	"nama": "tess",
	"no_ia": "",
	"sequence": "34234",
	"remark": "IAwalet123",
	"wallet": [
		{
			"kode_wallet": "9002",
			"nominal": "20000"
		},
		{
			"kode_wallet": "9003",
			"nominal": "20000"
		}
	]
	}
	01= pembelian, 02=pencairan
	*/
	{
		$result = new stdClass;
		try
		{
			$param['no_kartu'] = $datapost->no_kartu;
			$param['nama'] = $datapost->nama;
			$param['wallet'] = $datapost->wallet;
			$param['norek_simpanan'] = $datapost->norek_simpanan;
			$param['norek_pinjaman'] = $datapost->norek_pinjaman;
			$param['no_ia'] = $datapost->no_ia;
			$param['sequence'] = $datapost->sequence;
			$param['remark'] = $datapost->remark;
			$output = $this->Service_wallet_model->proses_insert_wallet($param);
			
			$result->responseCode= $output['responsecode'];
			$result->responseDesc= $output['responsedesc'];
		}
		catch(Exception $e){
			$result->responseCode='04';
			$result->responseDesc = $e->getMessage();
		}
		$this->Service_wallet_model->insertActivity(array('ip_client'=>$this->libs->get_client_ip(),'ket_user'=>json_encode($result),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($datapost), 'fitur' => 'Rekon Insert Wallet'));
		$this->response($result);
	}
	function rekonPenguranganWallet($datapost) /*{"requestMethod":"rekonPenguranganWallet","no_kartu":"6013010628025364","kode_wallet":"9001","nominal_transaksi":"1","seqnum":"213"}
	01= pembelian, 02=pencairan
	*/
	{
		$result = new stdClass;
		try
		{
			$param['no_kartu'] = $datapost->no_kartu;
			$param['kode_wallet'] = $datapost->kode_wallet;
			$param['nominal_transaksi'] = $datapost->nominal_transaksi;
			$param['seqnum'] = $datapost->seqnum;						
			$row_cari = $this->Service_wallet_model->check_no_kartu($param);
			if($row_cari->num_rows() > 0)
			{
				$row = $row_cari->row();
				$output = $this->Service_wallet_model->get_proses_rekon($param,$row);
			
				$result->responseCode= $output['responsecode'];
				$result->responseDesc= $output['responsedesc'];
			}
			else
			{
				$result->responseCode='02';
				$result->responseDesc='No Kartu tidak ditemukan.';
			}
		}
		catch(Exception $e){
			$result->responseCode='04';
			$result->responseDesc = $e->getMessage();
		}
		$this->Service_wallet_model->insertActivity(array('ip_client'=>$this->libs->get_client_ip(),'ket_user'=>json_encode($result),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($datapost), 'fitur' => 'Rekon Transaksi'));
		$this->response($result);
	}
	/*end deploy*/
	function penambahanWallet($datapost)/*{
	"requestMethod": "penambahanWallet",
	"no_kartu": "6013010628025364",
	"norek_simpanan": "6013010628025364",
	"norek_pinjaman": "6013010628025364",
	"nama": "tess",
	"no_ia": "",
	"sequence": "34234",
	"remark": "IAwalet123",
	"wallet": [
		{
			"kode_wallet": "9002",
			"nominal": "20000"
		},
		{
			"kode_wallet": "9003",
			"nominal": "20000"
		}
	]
	}*/
	{
		$result = new stdClass;
		try{
			$param['no_kartu'] = $datapost->no_kartu;
			$param['nama'] = $datapost->nama;
			$param['wallet'] = $datapost->wallet;
			$param['norek_simpanan'] = $datapost->norek_simpanan;
			$param['norek_pinjaman'] = $datapost->norek_pinjaman;
			$param['no_ia'] = $datapost->no_ia;
			$param['sequence'] = $datapost->sequence;
			$param['remark'] = $datapost->remark;
			$output = $this->Service_wallet_model->insert_data_wallet($param);
			
			$result->responseCode= $output['responsecode'];
			$result->responseDesc= $output['responsedesc'];
		}
		catch(Exception $e)
		{
			$result->responseCode='04';
			$result->responseDesc = $e->getMessage();
		}
		$this->Service_wallet_model->insertActivity(array('ip_client'=>$this->libs->get_client_ip(),'ket_user'=>json_encode($result),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($datapost), 'fitur' => 'penambahanWallet'));
		$this->response($result);
	}

	function inquiryPembelian($datapost)/*{"requestMethod":"inquiryPembelian","no_kartu":"6013010628025364","mid":"000001017850000","kode_wallet":"9001","nominal_transaksi":"20","tid":"345435","jenis":"01"}
	*/
	{
		$result = new stdClass;
		try{
		$param['no_kartu'] = $datapost->no_kartu;
		$param['mid'] = $datapost->mid;
		$param['kode_wallet'] = $datapost->kode_wallet;
		$param['nominal_transaksi'] = $datapost->nominal_transaksi;
		$param['tid'] = $datapost->tid;
		$param['jenis'] = $datapost->jenis;

		if($this->Service_wallet_model->validasi_kode_fitur($param))
		{
			if($this->Service_wallet_model->get_kode_is_subsidi($param))
			{
				$hargaperkg = $this->Service_wallet_model->get_harga_pupuk($param);
				$output_wallet = $this->Service_wallet_model->get_inquiry_pembelian_wallet_subsidi($param);
				if($output_wallet->num_rows() > 0)
				{
					$res_wallet = $output_wallet->row();
					$sisa_uang = $res_wallet->nominal - ($param['nominal_transaksi'] * $hargaperkg);

					if($sisa_uang > 0)
					{
						$validasi =  $this->Service_wallet_model->get_validasi_petani_mid($param);
						if($validasi)
						{
							$output = $this->Service_wallet_model->get_informasi_pembelian_pupuk($param);
							$res = $output->row();	
							$sisa_kg = 0;
							if($param['kode_wallet'] == '9901')
								$sisa_kg = ($res->urea/100) - $param['nominal_transaksi'];
							else if($param['kode_wallet'] == '9902')
								$sisa_kg = ($res->sp/100) - $param['nominal_transaksi'];
							else if($param['kode_wallet'] == '9903')
								$sisa_kg = ($res->za/100) - $param['nominal_transaksi'];
							else if($param['kode_wallet'] == '9904')	
								$sisa_kg = ($res->npk/100) - $param['nominal_transaksi'];
							else if($param['kode_wallet'] == '9905')		
								$sisa_kg = ($res->organik/100) - $param['nominal_transaksi'];
							if($sisa_kg < 0)
							{
								$result->responseCode= "06";
								$result->responseDesc= "Kuota pupuk anda kurang";
							}
							else
							{
								$result->responseCode = '00';
								$result->responseDesc = 'Inquiry data berhasil.';
								$result->sisa_dana = $sisa_uang;
								$result->nama = $res->nama;
								$result->rupiah_beli = $param['nominal_transaksi'] * $hargaperkg;
								$result->kg_beli = $param['nominal_transaksi'];
								$result->sisa_kg = number_format($sisa_kg,"2",".","");
							}
						}
						else
						{
							$result->responseCode='03';
							$result->responseDesc='No Kartu tidak terdaftar pada mid ini.';	
						}
					}
					else
					{
						$result->responseCode= "51";
						$result->responseDesc= "Kuota anda kurang";						
					}
				}				
				else
				{
					$result->responseCode='02';
					$result->responseDesc='No Kartu tidak ditemukan.';
				}
			}
			else
			{
				$output = $this->Service_wallet_model->get_inquiry_pembelian_wallet($param);
				if($output->num_rows() > 0)
				{
					$res = $output->row();
					$sisa_uang = $res->nominal - $param['nominal_transaksi'];
					if($sisa_uang > 0)
					{
						$result->responseCode = '00';
						$result->responseDesc = 'Inquiry data berhasil.';
						$result->sisa_dana = $sisa_uang;
						$result->nama = $res->nama;
						$result->rupiah_beli = $param['nominal_transaksi'];
						$result->kg_beli = 0;
						$result->sisa_kg = 0;
					}
					else
					{
						$result->responseCode= "51";
						$result->responseDesc= "Kuota anda kurang";
					}
				}
				else
				{
					$result->responseCode='02';
					$result->responseDesc='No Kartu tidak ditemukan.';
				}
			}
		}
		else
		{
			$result->responseCode= "07";
			$result->responseDesc= "Wallet dan fitur salah";
		}
		}catch(Exception $e){
			$result->responseCode='04';
			$result->responseDesc = $e->getMessage();
		}
		$this->Service_wallet_model->insertActivity(array('ip_client'=>$this->libs->get_client_ip(),'ket_user'=>json_encode($result),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($datapost), 'fitur' => 'inquiry pembelian'));
		$this->response($result);
	}
	function prosesPembelian($datapost) /*{"requestMethod":"prosesPembelian","no_kartu":"6013010628025364","mid":"000001017850000","kode_wallet":"9001","nominal_transaksi":"1","seqnum":"666","tid":"345435","jenis":"01"}
	01= pembelian, 02=pencairan
	*/
	{
		$result = new stdClass;
		try
		{
			$param['no_kartu'] = $datapost->no_kartu;
			$param['mid'] = $datapost->mid;
			$param['kode_wallet'] = $datapost->kode_wallet;
			$param['nominal_transaksi'] = $datapost->nominal_transaksi;
			$param['seqnum'] = $datapost->seqnum;
			$param['tid'] = $datapost->tid;
			$param['jenis'] = $datapost->jenis;
			if($this->Service_wallet_model->validasi_kode_fitur($param))
			{
				if($this->Service_wallet_model->get_kode_is_subsidi($param))
				{
					$hargaperkg = $this->Service_wallet_model->get_harga_pupuk($param);
					$output_wallet = $this->Service_wallet_model->get_inquiry_pembelian_wallet_subsidi($param);
					if($output_wallet->num_rows() > 0)
					{
						$res_wallet = $output_wallet->row();
						$sisa_uang = $res_wallet->nominal - ($param['nominal_transaksi'] * $hargaperkg);

						if($sisa_uang > 0)
						{
							$paramSubsidi['no_kartu'] = $param['no_kartu'];
							$paramSubsidi['mid'] = $param['mid'];
							$paramSubsidi['kode_pupuk'] = substr($param['kode_wallet'], -2);
							$paramSubsidi['kg_beli'] = $param['nominal_transaksi'];
							$paramSubsidi['komoditi'] = '01';
							$paramSubsidi['seqnum'] = $param['seqnum'];
							$paramSubsidi['tid'] = $param['tid'];
							$paramSubsidi['keterangan'] = $param['jenis'];
							$paramSubsidi['kode_wallet'] = $param['kode_wallet'];
							$paramSubsidi['nominal_transaksi'] = ($param['nominal_transaksi'] * $hargaperkg);
							$paramSubsidi['jenis'] = '01';
							$output = $this->Service_wallet_model->get_proses_pembelian_pupuk($paramSubsidi,$res_wallet);

							$result->responseCode= $output['responsecode'];
							$result->responseDesc= $output['responsedesc'];
						}
						else
						{
							$result->responseCode= "51";
							$result->responseDesc= "Kuota anda kurang";
						}
					}
					else
					{
						$result->responseCode='02';
						$result->responseDesc='No Kartu tidak ditemukan.';
					}
				}
				else
				{
					$row_cari = $this->Service_wallet_model->check_no_kartu($param);
					if($row_cari->num_rows() > 0)
					{
						$row = $row_cari->row();
						$output = $this->Service_wallet_model->get_proses_pembelian($param,$row);
					
						$result->responseCode= $output['responsecode'];
						$result->responseDesc= $output['responsedesc'];
					}
					else
					{
						$result->responseCode='02';
						$result->responseDesc='No Kartu tidak ditemukan.';
					}
				}
			}
			else
			{
				$result->responseCode= "07";
				$result->responseDesc= "Wallet dan fitur salah";
			}
			/*$result->nama_petani = $output['nama_petani'];
			$result->nama_kt = $output['nama_kt'];
			$result->sisa_kg = number_format($output['sisa_kg']/100,"2",".","");
			$result->kg_beli = number_format($output['kg_beli'],"2",".","");
			$result->rupiah_beli = number_format($this->Service_prosw_model->get_harga_pembelian($param),"2",".","");
		*/
		}
		catch(Exception $e){
			$result->responseCode='04';
			$result->responseDesc = $e->getMessage();
		}
		$this->Service_wallet_model->insertActivity(array('ip_client'=>$this->libs->get_client_ip(),'ket_user'=>json_encode($result),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($datapost), 'fitur' => 'Proses Pembelian'));
		$this->response($result);
	}
	function inquirySaldo($datapost)/*{	"requestMethod":"inquirySaldo","no_kartu":"6013010628025364","mid":"000001017850000"}*/
	{
		$result = new stdClass;
		try{
			$param['no_kartu'] = $datapost->no_kartu;
			$param['mid'] = $datapost->mid;
			$output = $this->Service_wallet_model->get_list_wallet($param);
			if($output->num_rows() > 0)
			{
				$res = $output->result();
				$bantuan = array();
				$num = 0;
				foreach($res as $id=>$val)
				{
					$bantuan[$id]['kode'] 	= $val->kode_wallet;
					$bantuan[$id]['kode_desc'] = $val->nama_wallet;
					$bantuan[$id]['saldo'] 	= $val->nominal;
					if($val->nama != '')
					{
						$result->nama 	= $val->nama;
						$result->cardno = $val->no_kartu;
					}
					$num++;
				}
				$output_pupuk = $this->Service_wallet_model->get_kuota_pupuk($param);
				$urea = 0;
				$sp36 = 0;
				$za = 0;
				$npk = 0;
				$organik = 0;
				if($output_pupuk->num_rows() > 0)
				{
					$res_pupuk = $output_pupuk->row();
					$urea = $res_pupuk->urea/100;
					$sp36 = $res_pupuk->sp/100;
					$za = $res_pupuk->za/100;
					$npk = $res_pupuk->npk/100;
					$organik = $res_pupuk->organik/100;
				}
				$bantuan[$num]['kode'] 	= "9901";
				$bantuan[$num]['kode_desc'] = "Urea";
				$bantuan[$num]['saldo'] 	=number_format($urea,"2",".","");

				$bantuan[$num+1]['kode'] 	= "9902";
				$bantuan[$num+1]['kode_desc'] = "SP-36";
				$bantuan[$num+1]['saldo'] 	= number_format($sp36,"2",".","");

				$bantuan[$num+2]['kode'] 	= "9903";
				$bantuan[$num+2]['kode_desc'] = "ZA";
				$bantuan[$num+2]['saldo'] 	= number_format($za,"2",".","");

				$bantuan[$num+3]['kode'] 	= "9904";
				$bantuan[$num+3]['kode_desc'] = "NPK";
				$bantuan[$num+3]['saldo'] 	= number_format($npk,"2",".","");

				$bantuan[$num+4]['kode'] 	= "9905";
				$bantuan[$num+4]['kode_desc'] = "Organik";
				$bantuan[$num+4]['saldo'] 	= number_format($organik,"2",".","");
				
				$result->bantuan = $bantuan;
				$result->responseCode='00';
				$result->responseDesc='Inquiry data berhasil.';
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
		$this->Service_wallet_model->insertActivity(array('ip_client'=>$this->libs->get_client_ip(),'ket_user'=>json_encode($result),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($datapost), 'fitur' => 'info saldo'));
		$this->response($result);
	}
	
	function reversalPembelian($datapost)/*{"requestMethod":"reversalPembelian","no_kartu":"6013010628025364","mid":"000001017850000","tid":"345435","seqnum":"666"}*/
	{
		$result = new stdClass;
		try
		{
			//$param['seqnum_to_reverse'] = $datapost->seqnum_to_reverse;
			$param['tid'] = $datapost->tid;
			$param['seqnum'] = $datapost->seqnum;
			$param['no_kartu'] = $datapost->no_kartu;
			$param['mid'] = $datapost->mid;
			$output = $this->Service_wallet_model->reversal_pembelian($param);
			$result->responseCode= $output['responsecode'];
			$result->responseDesc= $output['responsedesc'];
		}
		catch(Exception $e){
			$result->responseCode='04';
			$result->responseDesc = $e->getMessage();
		}
		$this->Service_wallet_model->insertActivity(array('ip_client'=>$this->libs->get_client_ip(),'ket_user'=>json_encode($result),'waktu'=>date('Y-m-d H:i:s'), 'ket_request'=>json_encode($datapost), 'fitur' => 'Reversal Pembelian'));
		$this->response($result);
	}
}