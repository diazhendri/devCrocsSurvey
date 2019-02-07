<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function send_email($id_draft, $fromAddress, $toAddress, $ccAddress, $subject, $messageBody, $attach, $tfile)
{
	// $client = new SoapClient("http://10.35.65.61:7022/Service.asmx?wsdl", array("trace" => 1));
	
	// $param_send_email->id_draft = $id_draft;
	// $param_send_email->fromAddress = $fromAddress;
	// $param_send_email->toAddress = $toAddress;
	// $param_send_email->ccAddress = $ccAddress;
	// $param_send_email->subject = $subject;
	// $param_send_email->messageBody = $messageBody;
	// $param_send_email->attach = $attach;
	// $param_send_email->tfile = $tfile;
	
	// try{
		// $result = $client->SendMail(
			// $param_send_email
		// );		
		// $return = $result->SendMailResult;
		// return $return;
	// } catch(SoapFault $f){
		// return "Email not sent!";
	// }
	$CI =& get_instance();
	$CI->load->library('email');
	$CI->email->from($fromAddress, 'Bank BRI');
	$CI->email->to($toAddress);
	$CI->email->subject($subject);
	$CI->email->message($messageBody);
	$CI->email->send();

}

function send_sms($hp,$msg)
{
	// $client = new SoapClient("http://10.35.65.61:9997/Service.asmx?wsdl", array("trace" => 1));
	$client = new SoapClient("http://172.21.56.34:9994/Service.asmx?wsdl", array("trace" => 1));
	$param_send_email = new stdClass();
	$param_send_email->norek = '0';
	$param_send_email->divisi = 'TSI';
	$param_send_email->produk = 'SIT';
	$param_send_email->fitur = '';
	$param_send_email->hp = $hp;
	$param_send_email->pesan = $msg;
	$param_send_email->flag = '0';
	
	try{
		$result = $client->FCD_SMS(
			$param_send_email
		);		
		$return = $result->FCD_SMSResult;
		return $return;
	} catch(SoapFault $f){
		return "Sms not sent!";
	}
}