<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function get_status_tempat()
{
	$status = array(
				'sewa'		=>'Sewa',
				'kontrak'	=>'Kontrak',
				'orangtua'	=>'Milik Orang Tua',
				'sendiri'	=>'Milik Sendiri'
			);			
	return $status;
}

function get_sektor_usaha()
{
	$sektor = array(
				'perdagangan'	=>'Perdagangan',
				'pertanian'		=>'Pertanian',
				'perkebunan'	=>'Perkebunan',
				'perikanan'		=>'Perikanan',
				'peternakan'	=>'Peternakan',
				'lainnya'		=>'Lainnya'
			);			
	return $sektor;
}
