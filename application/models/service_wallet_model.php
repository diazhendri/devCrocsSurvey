<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Service_wallet_model extends CI_Model 
{
    function __construct()
    {
        parent::__construct();
	}
	/*start deploy*/
	function proses_insert_wallet($param)
	{
		$this->db->from('wallet_penerima');
		$this->db->where('rekening_pinjaman',$param['norek_pinjaman']);

		$out = $this->db->get();
		if($out->num_rows() > 0)
		{
			$ret['responsecode'] 	= "04";
			$ret['responsedesc'] 	= "Rekening pinjaman telah disalurkan ke wallet";
		}
		else
		{
			$this->db->trans_begin();
			foreach($param['wallet'] as $id=>$res)
			{
				$dataInsert['no_kartu'] = $param['no_kartu'];
				$dataInsert['nama'] = $param['nama'];
				$dataInsert['nominal'] = $res->nominal;
				$dataInsert['kode_wallet'] = $res->kode_wallet;
				$dataInsert['realisasi'] = 0;
				$dataInsert['rekening_pinjaman'] = $param['norek_pinjaman'];
				$dataInsert['rekening_simpanan'] = $param['norek_simpanan'];
				$this->db->insert('wallet_penerima', $dataInsert);

				$dataInsertLog['no_kartu'] = $param['no_kartu'];
				$dataInsertLog['nama'] = $param['nama'];
				$dataInsertLog['nominal'] = $res->nominal;
				$dataInsertLog['kode_wallet'] = $res->kode_wallet;
				$dataInsertLog['remark'] = $param['remark'];
				$dataInsertLog['no_IA'] = $param['no_ia'];
				$dataInsertLog['rekening_pinjaman'] = $param['norek_pinjaman'];
				$dataInsertLog['rekening_simpanan'] = $param['norek_simpanan'];
				$dataInsertLog['sequence'] = $param['sequence'];
				$dataInsertLog['tanggal_insert'] = date('Y-m-d H:i:s');
				
				$this->db->insert('wallet_log_penambahan', $dataInsertLog);
			}
			if($this->db->trans_status()===FALSE)
			{
				$this->db->trans_rollback();
				$ret['responsecode'] 	= "02";
				$ret['responsedesc'] 	= "Gagal update database";
			}
			else
			{
				$this->db->trans_commit();
				$ret['responsecode'] 	= "00";
				$ret['responsedesc'] 	= "Proses Insert Wallet berhasil.";
			}
		}
		
		return $ret;
	}
	function proses_delete_wallet($dataInsert)
	{
		$this->db->where('no_kartu',$dataInsert['no_kartu']);
		$this->db->where('remark',$dataInsert['remark']);
		$this->db->where('rekening_pinjaman',$dataInsert['rekening_pinjaman']);
		$this->db->delete('wallet_log_penambahan');
		if($this->db->affected_rows() > 0)
		{
			$this->db->where('no_kartu',$dataInsert['no_kartu']);
			$this->db->where('rekening_pinjaman',$dataInsert['rekening_pinjaman']);
			$this->db->delete('wallet_penerima');
			if($this->db->affected_rows() > 0)			
				return true;			
			else
				return false;
		}
		else
			return false;
	}
	function get_proses_rekon($param, $row)
	{
		$ret["responsecode"] 	= "99";
		$ret["responsedesc"] 	= "Unknown Error";
		$this->db->trans_begin();

		$nominal_wallet = $row->nominal;
		$nama = $row->nama;
		$nominal_transaksi = $param['nominal_transaksi'];
		$no_kartu = $param['no_kartu'];
		$kode = $param['kode_wallet'];
		if($nominal_wallet > $nominal_transaksi)
		{
			$sql = "update wallet_penerima 
			set realisasi = realisasi + ".$nominal_transaksi.", 
			nominal = nominal - ".$nominal_transaksi."
			where no_kartu = '".$no_kartu."' 
			and kode_wallet = '".$kode."'";	
			$this->db->query($sql);

			$data_insert['no_kartu']	= $param['no_kartu'];
			$data_insert['nama']		= $nama;
			$data_insert['nominal_wallet']=$nominal_wallet;
			$data_insert['kode_wallet']	= $param['kode_wallet'];
			$data_insert['nominal_transaksi']	= $param['nominal_transaksi'];
			$data_insert['sequence']	= $param['seqnum'];
			$data_insert['tanggal_transaksi'] = date('Y-m-d H:i:s');
			$data_insert['mid']	= '';
			$data_insert['tid']	= '';
			$data_insert['keterangan'] = 'Rekon Wallet';
			
			$this->db->insert('wallet_transaksi', $data_insert);
			if($this->db->trans_status()===FALSE)
			{
				$this->db->trans_rollback();
				$ret['responsecode'] 	= "06";
				$ret['responsedesc'] 	= "Gagal update database";
			}
			else
			{
				$this->db->trans_commit();
				$ret['responsecode'] 	= "00";
				$ret['responsedesc'] 	= "Proses Pembelian Berhasil";
			}
		}
		else
		{
			$ret['responsecode'] 	= "51";
			$ret['responsedesc'] 	= "Kuota Kurang";
		}
		return $ret;
	}
	/*end deploy*/
	function get_kuota_pupuk($param)
	{
		$this->db->select('a.nik, a.nama, cardnum, sum(sisa_urea) as urea,sum(sisa_sp) as sp,sum(sisa_za) as za,sum(sisa_npk) as npk
		, sum(sisa_organik) as organik, group_concat(a.nm_kelompok_tani) as nama_kt',false);
		$this->db->from('petani a');
		$this->db->where('cardnum',$param['no_kartu']);
		$this->db->where('nm_mid',$param['mid']);
		$this->db->group_by('a.nik, cardnum');
		$output = $this->db->get();
		return $output;
	}
	function insert_data_wallet($param)
	{
		$this->db->from('wallet_penerima');
		$this->db->where('rekening_pinjaman',$param['norek_pinjaman']);

		$out = $this->db->get();
		if($out->num_rows() > 0)
		{
			$ret['responsecode'] 	= "04";
			$ret['responsedesc'] 	= "Rekening pinjaman telah disalurkan ke wallet";
		}
		else
		{
			$this->db->trans_begin();
			foreach($param['wallet'] as $id=>$res)
			{
				/*$this->db->from('wallet_penerima');
				$this->db->where('kode_wallet',$res->kode_wallet);
				$this->db->where('no_kartu',$param['no_kartu']);
				$out = $this->db->get();
				if($out->num_rows() > 0)
				{
					$result_cari = $out->row();
					$sql = "update 
					wallet_penerima 
					set nominal = nominal + ".$res->nominal."
					where id = ".$result_cari->id."";
					$this->db->query($sql);
				}
				else
				{
					$dataInsert['no_kartu'] = $param['no_kartu'];
					$dataInsert['nama'] = $param['nama'];
					$dataInsert['nominal'] = $res->nominal;
					$dataInsert['kode_wallet'] = $res->kode_wallet;
					$dataInsert['realisasi'] = 0;
					$dataInsert['rekening_pinjaman'] = $param['norek_pinjaman'];
					$dataInsert['rekening_simpanan'] = $param['norek_simpanan'];
					$this->db->insert('wallet_penerima', $dataInsert);
				}*/
				$dataInsert['no_kartu'] = $param['no_kartu'];
				$dataInsert['nama'] = $param['nama'];
				$dataInsert['nominal'] = $res->nominal;
				$dataInsert['kode_wallet'] = $res->kode_wallet;
				$dataInsert['realisasi'] = 0;
				$dataInsert['rekening_pinjaman'] = $param['norek_pinjaman'];
				$dataInsert['rekening_simpanan'] = $param['norek_simpanan'];
				$this->db->insert('wallet_penerima', $dataInsert);

				$dataInsertLog['no_kartu'] = $param['no_kartu'];
				$dataInsertLog['nama'] = $param['nama'];
				$dataInsertLog['nominal'] = $res->nominal;
				$dataInsertLog['kode_wallet'] = $res->kode_wallet;
				$dataInsertLog['remark'] = $param['remark'];
				$dataInsertLog['no_IA'] = $param['no_ia'];
				$dataInsertLog['rekening_pinjaman'] = $param['norek_pinjaman'];
				$dataInsertLog['rekening_simpanan'] = $param['norek_simpanan'];
				$dataInsertLog['sequence'] = $param['sequence'];
				$dataInsertLog['tanggal_insert'] = date('Y-m-d H:i:s');
				
				$this->db->insert('wallet_log_penambahan', $dataInsertLog);
			}
			if($this->db->trans_status()===FALSE)
			{
				$this->db->trans_rollback();
				$ret['responsecode'] 	= "02";
				$ret['responsedesc'] 	= "Gagal update database";
			}
			else
			{
				$this->db->trans_commit();
				$ret['responsecode'] 	= "00";
				$ret['responsedesc'] 	= "Proses Insert Wallet berhasil.";
			}
		}
		
		return $ret;
	}
	function get_validasi_petani_mid($param)
	{
		$this->db->select('id_petani',false);
		$this->db->from('petani');
		$this->db->where('cardnum',$param['no_kartu']);
		$this->db->where('nm_mid',$param['mid']);
		$this->db->where('status','1');
		$output = $this->db->get();
		if($output->num_rows() > 0)
			return true;
		else
			return false;
	}
	function get_informasi_pembelian_pupuk($param)
	{
		$this->db->select('a.nik, a.nama, cardnum, sum(sisa_urea) as urea,sum(sisa_sp) as sp,sum(sisa_za) as za,sum(sisa_npk) as npk
		, sum(sisa_organik) as organik, group_concat(a.nm_kelompok_tani) as nama_kt',false);
		$this->db->from('petani a');
		$this->db->where('cardnum',$param['no_kartu']);
		$this->db->where('status','1');
		$this->db->where('nm_mid',$param['mid']);
		$this->db->group_by('a.nik, cardnum');
		return $this->db->get();
	}
	function get_list_wallet($param)
	{
		$this->db->select('b.no_kartu,b.nama,coalesce(b.nominal,0) as nominal,a.kode_wallet, a.nama_wallet', false);
		$this->db->from('wallet_kode a');
		$this->db->join('wallet_penerima b',"a.kode_wallet = b.kode_wallet and b.no_kartu = '".$param['no_kartu']."'",'LEFT');
		$this->db->where('a.jenis', 'nonsubsidi');	
		return $this->db->get();
	}
	function get_proses_pembelian_pupuk($param, $row_wallet)
	{
		$ret["responsecode"] 	= "99";
		$ret["responsedesc"] 	= "Unknown Error";
		$this->db->from('petani');
		$this->db->where('cardnum',$param['no_kartu']);
		$this->db->where('nm_mid',$param['mid']);
		$output = $this->db->get();
		$sisa_kg = 0;
		$pengurang_kg = $param['kg_beli'] * 100;
		$this->db->trans_begin();
		$ret["kg_beli"] = $param['kg_beli'];
		$ret["nama_kt"] = "";
		$ret["sisa_kg"] 	= "0";
		$ret['nama_petani']  = "";
		$nama_kt = "";
		foreach($output->result() as $res)
		{
			if($param['kode_pupuk'] == '01')
			{
				$sisa_kg += ($res->sisa_urea);
			}
			else if($param['kode_pupuk'] == '02')
			{
				$sisa_kg += ($res->sisa_sp);
			}
			else if($param['kode_pupuk'] == '03')
			{
				$sisa_kg += ($res->sisa_za);
			}
			else if($param['kode_pupuk'] == '04')	
			{
				$sisa_kg += ($res->sisa_npk);
			}
			else if($param['kode_pupuk'] == '05')		
			{	
				$sisa_kg += ($res->sisa_organik);
			}
			$ret["nama_petani"] 	= $res->nama;
			$nama_kt .= $res->nm_kelompok_tani.",";
		}
		$total_sisa_kg = $sisa_kg;
		if($nama_kt != "")
			$ret["nama_kt"] = substr($nama_kt,0,strlen($nama_kt)-1);
		
		if($sisa_kg >= $pengurang_kg)
		{
			$sisa_kg = 0;
			foreach($output->result() as $res)
			{
				$txt_kolom  = '';
				$kuota_kg = 0;
				$sisa_db_kg = 0;
				if($param['kode_pupuk'] == '01')
				{
					$txt_kolom  = 'sisa_urea';
					$sisa_kg = ($res->sisa_urea) - $pengurang_kg;
					$kuota_kg = $res->kuota_urea;
					$sisa_db_kg = $res->sisa_urea;
				}
				else if($param['kode_pupuk'] == '02')
				{
					$sisa_kg = ($res->sisa_sp) - $pengurang_kg;
					$txt_kolom  = 'sisa_sp';
					$kuota_kg = $res->kuota_sp;
					$sisa_db_kg = $res->sisa_sp;
				}
				else if($param['kode_pupuk'] == '03')
				{
					$sisa_kg = ($res->sisa_za) - $pengurang_kg;
					$txt_kolom  = 'sisa_za';
					$kuota_kg = $res->kuota_za;
					$sisa_db_kg = $res->sisa_za;
				}
				else if($param['kode_pupuk'] == '04')	
				{
					$sisa_kg = ($res->sisa_npk) - $pengurang_kg;
					$txt_kolom  = 'sisa_npk';
					$kuota_kg = $res->kuota_npk;
					$sisa_db_kg = $res->sisa_npk;
				}
				else if($param['kode_pupuk'] == '05')		
				{	
					$sisa_kg = ($res->sisa_organik) - $pengurang_kg;
					$txt_kolom  = 'sisa_organik';
					$kuota_kg = $res->kuota_organik;
					$sisa_db_kg = $res->sisa_organik;
				}
				
				if($sisa_kg > 0)
				{
					$sqlUpdate = "update petani set ".$txt_kolom." = ".$txt_kolom." - ".$pengurang_kg." where cardnum = '".$param['no_kartu']."' and id_petani = ".$res->id_petani."";
					$this->db->query($sqlUpdate);
					
					$dataInsert['id_petani'] = $res->id_petani;
					$dataInsert['id_penyalur'] = 0;
					$dataInsert['jumlah'] = $pengurang_kg;
					$dataInsert['tanggal'] = date('Y-m-d H:i:s');
					$dataInsert['kuota'] = $kuota_kg;
					$dataInsert['kuota_sisa'] = ($sisa_kg < 0)?0:$sisa_kg;
					$dataInsert['id_pupuk'] = $param['kode_pupuk'];
					$dataInsert['id_merchant'] = $param['mid'];
					$dataInsert['id_komoditi'] = $param['komoditi'];
					$dataInsert['seqnum'] = $param['seqnum'];
					$dataInsert['keterangan'] = 'Pembelian';
					$dataInsert['tid_merchant'] = $param['tid'];	
					$dataInsert['no_kartu'] = $param['no_kartu'];	
					//$dataInsert['abc'] = 'Pembelian';				
					$this->db->insert('transaksi',$dataInsert);
					break;
				}
				else
				{
					if($sisa_db_kg != 0)
					{
						$sqlUpdate = "update petani 
						set ".$txt_kolom." = case when ".$txt_kolom." - ".$pengurang_kg." < 0 THEN 0 ELSE ".$txt_kolom." - ".$pengurang_kg."  end
						where cardnum = '".$param['no_kartu']."' 
						and id_petani = ".$res->id_petani."";
						$this->db->query($sqlUpdate);
						
						$dataInsert['id_petani'] = $res->id_petani;
						$dataInsert['id_penyalur'] = 0;
						$dataInsert['jumlah'] = ($sisa_kg < 0)?($sisa_kg + $pengurang_kg):$pengurang_kg;
						$dataInsert['tanggal'] = date('Y-m-d H:i:s');
						$dataInsert['kuota'] = $kuota_kg;
						$dataInsert['kuota_sisa'] = ($sisa_kg < 0)?0:$sisa_kg;
						$dataInsert['id_pupuk'] = $param['kode_pupuk'];
						$dataInsert['id_merchant'] = $param['mid'];
						$dataInsert['id_komoditi'] = $param['komoditi'];
						$dataInsert['seqnum'] = $param['seqnum'];
						$dataInsert['keterangan'] = 'Pembelian';
						$dataInsert['tid_merchant'] = $param['tid'];	
						$dataInsert['no_kartu'] = $param['no_kartu'];	
						$this->db->insert('transaksi',$dataInsert);
						$pengurang_kg = ($sisa_kg * -1);	
					}	
				}
			}
			$ret["sisa_kg"] 	= $total_sisa_kg - ($param['kg_beli'] * 100);

			$nominal_transaksi = $param['nominal_transaksi'];
			$no_kartu = $param['no_kartu'];
			$kode = $param['kode_wallet'];
			$nominal_wallet = $row_wallet->nominal;
			$nama = $row_wallet->nama;

			$sql = "update wallet_penerima 
			set realisasi = realisasi + ".$nominal_transaksi.", 
			nominal = nominal - ".$nominal_transaksi."
			where no_kartu = '".$no_kartu."' 
			and kode_wallet = '9002'";	
			
			$this->db->query($sql);
			$data_insert['no_kartu']	= $param['no_kartu'];
			$data_insert['nama']		= $nama;
			$data_insert['nominal_wallet']=$nominal_wallet;
			$data_insert['kode_wallet']	= $kode;
			$data_insert['nominal_transaksi']	= $param['nominal_transaksi'];
			$data_insert['sequence']	= $param['seqnum'];
			$data_insert['tanggal_transaksi'] = date('Y-m-d H:i:s');
			$data_insert['mid']	= $param['mid'];
			$data_insert['tid']	= $param['tid'];
			$data_insert['keterangan'] = 'Pembelian';			
			$this->db->insert('wallet_transaksi', $data_insert);
			if($this->db->trans_status()===FALSE)
			{
				$this->db->trans_rollback();
				$ret['responsecode'] 	= "08";
				$ret['responsedesc'] 	= "Gagal update database";
			}
			else
			{
				$this->db->trans_commit();
				$ret['responsecode'] 	= '00';
				$ret['responsedesc'] 	= "Proses Pembelian Berhasil";
			}

		}
		else
		{
			$ret['responsecode'] 	= "06";
			$ret['responsedesc'] 	= "Kuota pupuk anda kurang";
		}
		return $ret;
	}
	function get_proses_pembelian($param, $row)
	{
		$ret["responsecode"] 	= "99";
		$ret["responsedesc"] 	= "Unknown Error";
		$this->db->trans_begin();

		$nominal_wallet = $row->nominal;
		$nama = $row->nama;

		$nominal_transaksi = $param['nominal_transaksi'];
		$no_kartu = $param['no_kartu'];
		$kode = $param['kode_wallet'];
		if($nominal_wallet > $nominal_transaksi)
		{
			$sql = "update wallet_penerima 
			set realisasi = realisasi + ".$nominal_transaksi.", 
			nominal = nominal - ".$nominal_transaksi."
			where no_kartu = '".$no_kartu."' 
			and kode_wallet = '".$kode."'";	
			$this->db->query($sql);

			$data_insert['no_kartu']	= $param['no_kartu'];
			$data_insert['nama']		= $nama;
			$data_insert['nominal_wallet']=$nominal_wallet;
			$data_insert['kode_wallet']	= $param['kode_wallet'];
			$data_insert['nominal_transaksi']	= $param['nominal_transaksi'];
			$data_insert['sequence']	= $param['seqnum'];
			$data_insert['tanggal_transaksi'] = date('Y-m-d H:i:s');
			$data_insert['mid']	= $param['mid'];
			$data_insert['tid']	= $param['tid'];
			$data_insert['keterangan'] = 'Pembelian';
			
			$this->db->insert('wallet_transaksi', $data_insert);
			if($this->db->trans_status()===FALSE)
			{
				$this->db->trans_rollback();
				$ret['responsecode'] 	= "06";
				$ret['responsedesc'] 	= "Gagal update database";
			}
			else
			{
				$this->db->trans_commit();
				$ret['responsecode'] 	= "00";
				$ret['responsedesc'] 	= "Proses Pembelian Berhasil";
			}
		}
		else
		{
			$ret['responsecode'] 	= "51";
			$ret['responsedesc'] 	= "Kuota Kurang";
		}
		return $ret;
	}
	function check_no_kartu($param)
	{
		$this->db->select('a.no_kartu,a.nama,a.nominal,a.kode_wallet, b.nama_wallet');
		$this->db->from('wallet_penerima a');
		$this->db->join('wallet_kode b','a.kode_wallet = b.kode_wallet','INNER');
		$this->db->where('a.no_kartu', $param['no_kartu']);
		$this->db->where('a.kode_wallet', $param['kode_wallet']);
		return $this->db->get();
	}
	function get_inquiry_pembelian_wallet($param)
	{
		$this->db->select('a.no_kartu,a.nama,a.nominal,a.kode_wallet, b.nama_wallet');
		$this->db->from('wallet_penerima a');
		$this->db->join('wallet_kode b','a.kode_wallet = b.kode_wallet','INNER');
		$this->db->where('a.no_kartu', $param['no_kartu']);
		$this->db->where('a.kode_wallet', $param['kode_wallet']);
		return $this->db->get();
	}
	function get_inquiry_pembelian_wallet_subsidi($param)
	{
		$this->db->select('a.no_kartu,a.nama,a.nominal,a.kode_wallet, b.nama_wallet');
		$this->db->from('wallet_penerima a');
		$this->db->join('wallet_kode b','a.kode_wallet = b.kode_wallet','INNER');
		$this->db->where('a.no_kartu', $param['no_kartu']);
		$this->db->where('a.kode_wallet', '9002');
		return $this->db->get();
	}
	function get_kode_is_subsidi($param)
	{
		$this->db->from('wallet_kode');
		$this->db->where('kode_wallet', $param['kode_wallet']);
		$this->db->where('jenis', 'subsidi');

		$out = $this->db->get();
		if($out->num_rows() > 0)
			return true;
		else
			return false; 
	}
	function get_harga_pupuk($param)
	{
		$this->db->from('pupuk');
		$this->db->where('id_pupuk', substr($param['kode_wallet'],-2));
		$out = $this->db->get();
		if($out->num_rows() > 0)
		{
			return $out->row()->harga;
		}
		else
			return 0; 
	}
	function validasi_kode_fitur($param)
	{
		$this->db->from('wallet_mapping_kodewallet_fitur');
		$this->db->where('kode_wallet', $param['kode_wallet']);
		$this->db->where('kode_fitur', $param['jenis']);

		$out = $this->db->get();
		if($out->num_rows() > 0)
			return true;
		else
			return false; 
	}
	function insertActivity($arrdata){
		$this->db->insert('wallet_log_activity',$arrdata);
	}
	function reversal_pembelian($param)
	{
		$this->db->from('wallet_transaksi');
		$this->db->where('sequence',$param['seqnum']);
		$this->db->where('tid',$param['tid']);
		$this->db->where("keterangan in('Pembelian','Reverse Pembelian')");
		$output = $this->db->get();
		if($output->num_rows() > 0)
		{			
			$is_reverse = false;
			foreach($output->result() as $res)
			{
				if($res->keterangan == 'Reverse Pembelian')
				{
					$is_reverse = true;
				}
			}
			if($is_reverse)				
			{
				$ret['responsecode'] 	= "07";
				$ret['responsedesc'] 	= "Telah direversal";
			}
			else
			{
				$this->db->trans_begin();
				$res_wallet = $output->row();

				$nominal_wallet = $res_wallet->nominal_wallet;
				$nama = $res_wallet->nama;
				$nominal_transaksi = $res_wallet->nominal_transaksi;
				$no_kartu = $res_wallet->no_kartu;
				$kode = $res_wallet->kode_wallet;

				$this->db->from('wallet_kode');
				$this->db->where('kode_wallet', $res_wallet->kode_wallet);
				$this->db->where('jenis', 'subsidi');
				$out = $this->db->get();
				if($out->num_rows() > 0)
				{
					$this->db->from('transaksi');
					$this->db->where('seqnum',$param['seqnum']);
					$this->db->where('tid_merchant',$param['tid']);
					$this->db->where("keterangan in('Pembelian','Reverse Pembelian')");
					$output = $this->db->get();
					if($output->num_rows() > 0)
					{			
						$is_reverse = false;
						foreach($output->result() as $res)
						{
							if($res->keterangan == 'Reverse Pembelian')
							{
								$is_reverse = true;
							}
						}
						if($is_reverse)				
						{
							$ret['responsecode'] 	= "07";
							$ret['responsedesc'] 	= "Telah direversal";
						}
						else
						{
							$this->db->trans_begin();
							//$res = $output->row();
							foreach($output->result() as $res)
							{
								$txt_kolom_sisa  = '';
								$txt_kolom_kuota  = '';
								if($res->id_pupuk == '01')
								{
									$txt_kolom_sisa  = 'sisa_urea';
									$txt_kolom_kuota  = 'kuota_urea';
								}
								else if($res->id_pupuk == '02')
								{
									$txt_kolom_sisa  = 'sisa_sp';
									$txt_kolom_kuota  = 'kuota_sp';
								}
								else if($res->id_pupuk == '03')
								{
									$txt_kolom_sisa  = 'sisa_za';
									$txt_kolom_kuota  = 'kuota_za';
								}
								else if($res->id_pupuk == '04')	
								{
									$txt_kolom_sisa  = 'sisa_npk';
									$txt_kolom_kuota  = 'kuota_npk';
								}
								else if($res->id_pupuk == '05')		
								{	
									$txt_kolom_sisa  = 'sisa_organik';
									$txt_kolom_kuota  = 'kuota_organik';
								}
								$sqlUpdate = "update petani 
										set ".$txt_kolom_sisa." = case when ".$txt_kolom_sisa." + ".$res->jumlah." > ".$txt_kolom_kuota." 
										THEN ".$txt_kolom_kuota." ELSE ".$txt_kolom_sisa." + ".$res->jumlah." end
										where id_petani = '".$res->id_petani."'";
								$this->db->query($sqlUpdate);
								
								$dataInsert['id_petani'] = $res->id_petani;
								$dataInsert['id_penyalur'] = 0;
								$dataInsert['jumlah'] = $res->jumlah;
								$dataInsert['tanggal'] = date('Y-m-d H:i:s');
								$dataInsert['kuota'] = $res->kuota;
								$dataInsert['kuota_sisa'] = $res->kuota_sisa + $res->jumlah;
								$dataInsert['id_pupuk'] = $res->id_pupuk;
								$dataInsert['id_merchant'] = $param['mid'];
								$dataInsert['tid_merchant'] = $param['tid'];
								$dataInsert['id_komoditi'] = $res->id_komoditi;
								$dataInsert['no_kartu'] = $param['no_kartu'];				
								$dataInsert['seqnum'] = $param['seqnum'];
								$dataInsert['keterangan'] = 'Reverse Pembelian';
								$this->db->insert('transaksi',$dataInsert);
							}
						}


						$sql = "update wallet_penerima 
						set realisasi = realisasi - ".$nominal_transaksi.", 
						nominal = nominal + ".$nominal_transaksi."
						where no_kartu = '".$no_kartu."' 
						and kode_wallet = '9002'";	
						$this->db->query($sql);

						$data_insert['no_kartu']	= $param['no_kartu'];
						$data_insert['nama']		= $nama;
						$data_insert['nominal_wallet']=$nominal_wallet;
						$data_insert['kode_wallet']	= $kode;
						$data_insert['nominal_transaksi']	= $nominal_transaksi;
						$data_insert['sequence']	= $param['seqnum'];
						$data_insert['tanggal_transaksi'] = date('Y-m-d H:i:s');
						$data_insert['mid']	= $param['mid'];
						$data_insert['tid']	= $param['tid'];
						$data_insert['keterangan']	= 'Reverse Pembelian';
						$this->db->insert('wallet_transaksi', $data_insert);
					}
					else
					{
						$ret["responsecode"] 	= "09";
						$ret["responsedesc"] 	= "Seqnum subsidi tidak ditemukan";
					}
				}
				else
				{				

					$sql = "update wallet_penerima 
					set realisasi = realisasi - ".$nominal_transaksi.", 
					nominal = nominal + ".$nominal_transaksi."
					where no_kartu = '".$no_kartu."' 
					and kode_wallet = '".$kode."'";	
					$this->db->query($sql);

					$data_insert['no_kartu']	= $param['no_kartu'];
					$data_insert['nama']		= $nama;
					$data_insert['nominal_wallet']=$nominal_wallet;
					$data_insert['kode_wallet']	= $kode;
					$data_insert['nominal_transaksi']	= $nominal_transaksi;
					$data_insert['sequence']	= $param['seqnum'];
					$data_insert['tanggal_transaksi'] = date('Y-m-d H:i:s');
					$data_insert['mid']	= $param['mid'];
					$data_insert['tid']	= $param['tid'];
					$data_insert['keterangan']	= 'Reverse Pembelian';
					$this->db->insert('wallet_transaksi', $data_insert);					
					
				}
				if($this->db->trans_status()===FALSE)
				{
					$this->db->trans_rollback();
					$ret['responsecode'] 	= "06";
					$ret['responsedesc'] 	= "Gagal update data";
				}
				else
				{
					$this->db->trans_commit();
					$ret['responsecode'] 	= "00";
					$ret['responsedesc'] 	= "Berhasil";
				}
			}
		}
		else
		{
			$ret["responsecode"] 	= "02";
			$ret["responsedesc"] 	= "Seqnum tidak ditemukan";
		}
		return $ret;
	}
}