<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function get_jenis_briguna()
{
	$jenis = array(
				array ('id'=>'1', 'deskripsi'=>'Pegawai'),
				array ('id'=>'2', 'deskripsi'=>'Pensiun')
			);			
	
	return $jenis;
}

function get_max_jangka_waktu_briguna()
{
	$jangka = 121;			
	
	return $jangka;
}

function get_min_jangka_waktu_briguna()
{
	$jangka = 0;			
	
	return $jangka;
}