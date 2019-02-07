<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script>
	$(document).ready(function(){
		$('#recaptcha').click(function(){
			$.ajax({
				type:"post",
				url:"<?=site_url('home/recaptcha/')?>",
				data:'',
				error:function(){
					alert("Error\nGagal request data");
				},
				success:function(data){
					$('#span_captcha').html(data);
				}
			});
			return false;
		});
	});
</script>
<div class="container">
	<form method="post" accept-charset="utf-8" action="<?=base_url();?>login" id="login" />
		<div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
			<div class="panel panel-info" style="border-color:#F1F1F1 !important">
				<div class="panel-heading" style="background-color:#EEEEEE !important; color:#555555 !important; border-color:#F1F1F1 !important">
					<div class="panel-title">Login</div>
				</div>

				<div style="padding-top:30px" class="panel-body" >
					<div style="<?=($message!=''?'':'display:none');?>" id="login-alert" class="alert alert-danger col-sm-12"><?=$message;?></div>
					<form id="loginform" class="form-horizontal" role="form">
						<font color="#FBBE52" size="1"><?=form_error('username')?></font>
						<div style="margin-bottom: 25px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<input id="login-username" type="text" class="form-control"  name="pn" value="<?=($this->input->post('pn')!=''?$this->input->post('pn'):'')?>" placeholder="Personal Number">                                        
						</div>
						<font color="#FBBE52" size="1"><?=form_error('password')?></font>
						<div style="margin-bottom: 25px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<input id="login-password" type="password" class="form-control" autocomplete="off" name="password" placeholder="Password Email">
						</div>
						<font color="#FBBE52" size="1"><?=form_error('captcha')?></font>
						<div class="form-group" style="margin-bottom: 25px" >
							<div class="row" style="margin: 10px auto !important">
							<div class="col-sm-5" style="padding: 0px !important;">
								<div class="input-group pull-right col-sm-12" style="width:100%;">
								<span class="form-control" style="padding: 0px 0px; background-color: #FFF">
								<span id="span_captcha"><?=$captcha_image;?></span>
								&nbsp;<img src='<?=base_url();?>images/home/reload.png' id='recaptcha' width='14px' style='cursor:pointer;' />&nbsp;
								</span>
								</div>
							</div>
							<div class="col-sm-7" style="padding: 0px !important;">
								<div class="input-group pull-left" style="width:100%;">
								<input id="login-captcha" type="text" class="form-control" name="captcha" autocomplete="off" placeholder="captcha">
								</div>
							</div>
							</div>
						</div>
						<div style="margin-top:10px" class="form-group">
							<div class="col-sm-12 controls">
								<input type="submit" id="btn-login" name="btnsubmitlogin" class="btn btn-primary" value="Login" />
							</div>
						</div> 
						<div class="col-sm-12" align="center">
							<p>Tampilan terbaik menggunakan <a href="<?=base_url()."uploads/support/Firefox Setup 36.0.exe"?>">Mozilla</a> dan Chrome.</p>
						</div>
					</form>     
				</div>                     
			</div>  
		</div>
	</form>
</div>

</br></br>
