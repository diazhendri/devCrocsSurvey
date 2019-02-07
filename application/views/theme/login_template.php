<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script>
$(document).ready(function(){
	$('#recaptcha').click(function() {
		$.ajax({
			type:"POST",
			url:"<?php echo site_url("home/recaptcha"); ?>",
			data:"<?=$this->security->get_csrf_token_name()?>=<?=$this->security->get_csrf_hash()?>",
			dataType:"html",
			beforeSend : function() {
				$('#ajax-loader').show();
			},
			success:function(response){
				$('#span_captcha').html(response);
			},
			error :function(jqXHR, textStatus, errorThrown){
				$('#ajax-loader').hide();
			}
		});
		return false;
	});
});
</script>
<div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
	<div class="panel panel-default">
		<div class="panel-heading" style="padding-top:0px;padding-bottom:0px;">
			<div class="s13">
				<div class="s13_fr s13_content"><h4 class="great"><?=$title;?></h4></div>
				<div class="s13_fl s13_ribbon"></div>
				<div class="s13_fr s13_ribbon"></div>
			</div>
		</div>
		<div class="panel-body">
		<div id="notif" style="margin-top:-10px;text-align:center;" class="<?php echo (!empty($class_notif))?$class_notif:''; ?>" role="alert"><label id="notif"><?php echo (!empty($notif))?$notif:''; ?></label></div>
		<?=form_open(site_url('home/login'),array('role'=>'form','class'=>'form-horizontal user','id'=>'login','name'=>'login'));?>
			<div class="form-group form-group-sm">
				<label for="user" class="col-sm-2" style="vertical-align:top;">User</label>
				<div class="col-sm-10">
					<input type="text" id="user" name="user" class="form-control input" value="<?=(!empty($_POST['user']))?$_POST['user']:'';?>" />
					<span id="msg_user" class="msg_user red"><?=form_error('user',' ',' ')?></span>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<label for="user" class="col-sm-2" style="vertical-align:top;">Password</label>
				<div class="col-sm-10">
					<input type="password" id="pass" name="pass" class="form-control input" value="" />
					<span id="msg_pass" class="msg_pass red"><?=form_error('pass',' ',' ')?></span>
				</div>
			</div>
			<div class="form-group form-group-sm">
				<div class="col-sm-10 col-sm-offset-2">
					<div class="btn-group btn-group-sm pull-right ">
						<input type="submit" class="btn btn-sm btn-primary" id="login" name="login" style="height:30px;" value="Login"/>
					</div>
				</div>
			</div>	
		<?=form_close();?>
		</div>
	</div>
</div>