<style type='text/css' media='all'>@import url('<?=base_url();?>plugins/css/userguide.css');</style>
<link rel='stylesheet' type='text/css' media='all' href='<?=base_url();?>plugins/css/userguide.css' />
<style>
code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}
</style>
<div id="content">
	<h1>Dropbox Pinjaman WS Documentation</h1>
	Web Service yang ada saat ini pada aplikasi Dropbox Pinjaman adalah sebagai berikut:
	<ul>
		<li>Update Status Payment</li>
	</ul>
	
	<h2>URL Web Service</h2>
	Berikut adalah contoh penggunaan web service di sisi client:
	<code>
	$client = new SoapClient("<?=site_url('dbws');?>?wsdl", array("trace" => 1, 'cache_wsdl' => WSDL_CACHE_NONE));
	</code>
	
	
	<h2>Update Status Dropbox</h2>
	Web Service yang berfungsi untuk mengupdate status  data dropbox dengan parameter tertentu.
	<code>
	$param['reffno'] = "XXXXX";<br>
	$param['rescode'] = "xxxx";<br>
	$param['resdesc'] = "success";<br>
	$param['datepay'] = "2016-04-26";<br>
	$result = $client->paymentResponse($param);
	</code>
	<p class="important"><strong>Notes:</strong>  Reffno harus diisi.</p>
	
</div>
<div id="footer">
	<p><a href="#">TSI-PSB</a> &nbsp;&middot;&nbsp; Copyright &#169; 2014 &nbsp;&middot;&nbsp; <a href="#">PT. Bank Rakyat Indonesia (Persero), Tbk.</a></p>
</div>
<br>