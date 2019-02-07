<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ws_model extends CI_Model 
{
    function __construct()
    {
        parent::__construct();
	}
	/*start rekon*/
	function insertActivity($arrdata){
		$this->db->insert('ws_log_activity',$arrdata);
	}
	function convert_nominal_to_kg($rupiah, $id_pupuk)
	{
		$this->db->from('pupuk');
		$this->db->where('id_pupuk',$id_pupuk);
		$output = $this->db->get();
		if($output->num_rows() > 0)
		{
			$hasil = $output->row();
			return number_format($rupiah/$hasil->harga,"2",".","");
		}
		else
			return 0;
	}
	function get_harga_pupuk()
	{
		$this->db->from('pupuk');
		return $this->db->get();
	}
	function reversal_pembelian_pupuk($param)
	{
		$this->db->from('transaksi');
		$this->db->where('seqnum',$param['seqnum']);
		$this->db->where('tid_merchant',$param['tid']);
		$output = $this->db->get();
		if($output->num_rows() > 0)
		{
			$this->db->trans_begin();
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
				$dataInsert['seqnum'] = '';
				$dataInsert['keterangan'] = 'Reverse Pembelian';
				$this->db->insert('transaksi',$dataInsert);
			}
			if($this->db->trans_status()===FALSE)
			{
				$this->db->trans_rollback();
				$ret['responsecode'] 	= "005";
				$ret['responsedesc'] 	= "Gagal update data";
			}
			else
			{
				$this->db->trans_commit();
				$ret['responsecode'] 	= "001";
				$ret['responsedesc'] 	= "Berhasil";
			}
		}
		else
		{
			$ret["responsecode"] 	= "002";
			$ret["responsedesc"] 	= "Seqnum tidak ditemukan";
		}
		return $ret;
	}
	function get_proses_pembelian_pupuk($param)
	{
		$ret["responsecode"] 	= "999";
		$ret["responsedesc"] 	= "Unknown Error";
		$this->db->from('petani');
		$this->db->where('cardnum',$param['no_kartu']);
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
					$sqlUpdate = "update petani set ".$txt_kolom." = ".$txt_kolom." - ".$pengurang_kg." where cardnum = '".$param['no_kartu']."' and kelompok_tani = ".$res->kelompok_tani."";
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
						and kelompok_tani = ".$res->kelompok_tani."";
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
			$ret["sisa_kg"] 	= $sisa_kg;
			if($this->db->trans_status()===FALSE)
			{
				$this->db->trans_rollback();
				$ret['responsecode'] 	= "005";
				$ret['responsedesc'] 	= "Gagal update data";
			}
			else
			{
				$this->db->trans_commit();
				$ret['responsecode'] 	= "001";
				$ret['responsedesc'] 	= "Berhasil";
			}
		}
		else
		{
			$ret['responsecode'] 	= "006";
			$ret['responsedesc'] 	= "Kuota Kurang";
		}
		return $ret;
	}
	function get_harga_pembelian($param)
	{
		$this->db->select("harga * ".$param['kg_beli']." as total_harga", false);
		$this->db->from('pupuk');
		$this->db->where('id_pupuk',$param['kode_pupuk']);
		$output = $this->db->get();
		if($output->num_rows() > 0)
			return $output->row()->total_harga;
		else
			return 0;
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
		$this->db->group_by('a.nik, cardnum');
		return $this->db->get();
	}
	function get_kuota_pupuk($param)
	{
		$this->db->select('a.nik, a.nama, cardnum, sum(sisa_urea) as urea,sum(sisa_sp) as sp,sum(sisa_za) as za,sum(sisa_npk) as npk
		, sum(sisa_organik) as organik, group_concat(a.nm_kelompok_tani) as nama_kt',false);
		$this->db->from('petani a');
		$this->db->where('cardnum',$param['no_kartu']);
		$this->db->where('status','1');
		$this->db->group_by('a.nik, cardnum');
		$output = $this->db->get();
		return $output;
	}
	function get_jenis_kode()
	{
		$this->db->from('master_kode_bantuan');
		return $this->db->get();
	}
}