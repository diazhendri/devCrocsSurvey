<?php
class Client_soapad {
	// tambahkan NuSOAP code
	function validate($pn,$pass)
	{
		$client = new SoapClient("https://172.21.50.22/beranda/ldap/ws/ws_adUser.php?wsdl");
		try{
			$result = $client->validate_aduser($pn,$pass);
        }
        catch(SoapFault $f){
        	$result = 0;
        }
        return($result);
	}
	
	
	function getemail($pn)
	{
		$client = new SoapClient('https://172.21.50.22/beranda/ldap/ws/ws_searchLogon.php?wsdl', array("trace" => 1,'cache_wsdl' => WSDL_CACHE_NONE, 'location'=>'https://172.21.50.22/beranda/ldap/ws/ws_searchLogon.php?wsdl'));

		try{
			$result = $client->search_logonname($pn);
        }
        catch(SoapFault $f){
        	$result = 0;
        }
		
		return($result);
	}
	
}
?>