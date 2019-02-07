<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Scheduler_model extends CI_Model 
{
    function __construct()
    {
        parent::__construct();
	}
	function insert_log_transaksi_wallet()
	{
		$db_wallet = $this->load->database('db_wallet', TRUE);
		$sqlCheckDate = "select *
						from LOG_ACTIVITY
						where datediff(day, DONE_DATE, GETDATE()) = 0";
		$output_check = $db_wallet->query($sqlCheckDate); 
		if($output_check->num_rows() == 0)
		{
			$sql = "select a.*, c.nama_fitur
					from wallet_transaksi a
					inner join wallet_mapping_kodewallet_fitur b on a.kode_wallet = b.kode_wallet
					inner join wallet_kode_fitur c on b.kode_fitur = c.kode_fitur
					where a.sequence not in
					(
						select sequence from wallet_transaksi where keterangan = 'Reverse Pembelian'
					)
					
					";
					//and datediff(SUBDATE(NOW(), 1),tanggal_transaksi ) = 0
			
			//$this->db->where('datediff(SUBDATE(NOW(), 1),tanggal_transaksi ) = 0');
			$output =$this->db->query($sql);		
			$dataInsert = array();
			$rowInsert = 0;
			if($output->num_rows() > 0)
			{
				foreach($output->result() as $row)
				{
					$dataInsert['no_kartu'] = $row->no_kartu;
					$dataInsert['nama'] = $row->nama;
					$dataInsert['nominal_wallet'] = $row->nominal_wallet;
					$dataInsert['kode_wallet'] = $row->kode_wallet;
					$dataInsert['nominal_transaksi'] = $row->nominal_transaksi;
					$dataInsert['sequence'] = $row->sequence;
					$dataInsert['tanggal_transaksi'] = $row->tanggal_transaksi;
					$dataInsert['mid'] = $row->mid;
					$dataInsert['tid'] = $row->tid;
					$dataInsert['id_transaksi'] = $row->id_transaksi;
					$dataInsert['keterangan'] = $row->nama_fitur;
					$db_wallet->insert('wallet_transaksi',$dataInsert);
					$rowInsert += $db_wallet->affected_rows();
				}
			}
			$data_insert_log['TANGGAL'] = date('Y-m-d H:i:s',strtotime("-1 days"));
			$data_insert_log['KET'] = "Insert log transaksi";
			//$data_insert_log['FLAG'] "";
			$data_insert_log['DONE_DATE'] = date('Y-m-d H:i:s');
			$db_wallet->insert('LOG_ACTIVITY',$data_insert_log);
			echo "sukses ".$rowInsert;
		}
		
	}
}