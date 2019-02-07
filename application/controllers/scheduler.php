<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Scheduler extends CI_Controller {
	function __construct()
	{
		parent::__construct();		
	}
	function insert_log_transaksi_wallet()
	{
		$this->load->model("Scheduler_model");		
		$this->Scheduler_model->insert_log_transaksi_wallet();
	}
}