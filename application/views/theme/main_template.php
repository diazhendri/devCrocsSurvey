<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title><?=$this->config->item('application_name')?></title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="<?=base_url();?>plugins/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?=base_url();?>plugins/css/font-awesome.min.css" rel="stylesheet">
		<link href="<?=base_url();?>plugins/css/styles.css" rel="stylesheet">
		<!--link href="<?//=base_url();?>plugins/css/main.css" rel="stylesheet"-->
		<link href="<?=base_url();?>plugins/css/jquery.smartmenus.bootstrap.css" rel="stylesheet">
		<link href="<?=base_url();?>plugins/css/select2.css" rel="stylesheet">
		<script>(function(w,d,u){w.readyQ=[];w.bindReadyQ=[];function p(x,y){if(x=="ready"){w.bindReadyQ.push(y);}else{w.readyQ.push(x);}};var a={ready:p,bind:p};w.$=w.jQuery=function(f){if(f===d||f===u){return a}else{p(f)}}})(window,document)</script>
		<style>
			a {
				color: black;
			}
			a:hover{
				color: #F7941E !important;
				font-weight: bold;
				background-color: #00549D !important; 
			}
			.hover_orange:hover{
				color: #FFFFFF !important;
				font-weight: normal;
			}
		</style>
	</head>
	<body style="background-color:#777;">
	<div id="wrapper">
		<div class="overlay"></div>
		<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
			<?php $this->menu->generate_menu_new(2,0,0); ?>
		</nav>
		<div id="page-content-wrapper">
			<header class="section_pattern" style="background-color:#FFF;">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-sm-push-6 extra-navbar-wrapper">
							<nav>
								<ul id="extra-navbar" class="list-inline">
								  <li><a href="<?=site_url();?>"><span class="glyphicon glyphicon-envelope"></span>  Kontak Kami</a></li>
								  <li><a href="<?=site_url();?>"><span class="glyphicon glyphicon-phone-alt"></span>  Call Centre 14017</a></li>
								</ul>
							  </nav>
						</div>
						<div class="col-xs-9 col-sm-6 col-sm-pull-6 extra-header-wrapper">
							<a href="http://bri.co.id" target="_ext"  style="text-decoration:none;color:#FFF;">
								<img src="<?=base_url().'images/home/bri-logo.png'?>" width="150px" style="margin:5px;">
							</a>
						</div>
					</div>
				</div>
			</header>
			<div class="navbar navbar-default bar" id="subnav" style="background-color:#013161;margin-top:0px;margin-bottom:0px;">
				<div class="container">
				<div style="color:#FFF;">
					<div class="col-md-12">
						<div class="navbar-header navbar-left">
							<button type="button" class="hamburger is-closed navbar-toggle pull-left" data-toggle="offcanvas" data-target="#navbar-collapse2">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a href="<?=base_url();?>" class="navbar-brand pull-right">
								<!--img src="<?//=base_url().'images/home/bri-logo.png'?>" width="150px" style="margin-top:-5px"-->
								<span style="color:#e26e29;font-family: Helvetica Neue,Helvetica,Arial,sans-serif;">e-FORM</span><span style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif;color:#FFF;">BRI</span>
							</a>
						</div>
						<div class="collapse navbar-collapse navbar-right" id="navbar-collapse2">
							<?=$this->menu->generate_menu_new(1,0,0);?>
						</div>
					</div>
				</div>
				</div>
				<div class="col-md-12" style="background:rgba(0, 0, 0, 0) linear-gradient(to bottom, #f7a723 0%, #f4961c 100%) repeat scroll 0 0;min-height:10px;">
					<div style="color:#FFFFFF;text-align:center;font-size: 150%;margin:auto; padding-top:10px;">
						<!--strong><?//=(isset($title)?$title:'')?></strong-->
					</div>
				</div>
			</div>
			<?=(isset($carousel)?'<div id="container-mid" class="col-md-12" style="margin-bottom:10px;padding:0px;">'.$carousel.'</div>':'')?>   
			<div class="container" style="margin-bottom:10px;margin-top:10px;">
				<!--main-->
				<span id="ajax-loader" style="display:none;">
					<div id="ajax-loader-layer"></div>
					<div id="image-ajax-loader"><span class="loader"><i class="fa fa-cog fa-spin"></i></span>
					<div class="progress">
					  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
						<span>Loading ...</span>
					  </div>
					</div>
					</div>
				</span>
				<div class="container-main col-md-12" id="main" style="background-color:#E1EEF7;min-height:750px;">
				   <div class="row main_row" style="margin-top:10px;">
					<?php if(isset($col) && $col=="3"){ ?>
					   <div class="col-md-3 col-sm-6">
							<?=(isset($left_content)?$left_content:'')?>
						</div>
						<div class="col-md-6 col-sm-6">
							  <?=(isset($main_content)?$main_content:'')?>     
						</div>
						<div class="col-md-3 col-sm-6">
							 <?=(isset($right_content)?$right_content:'')?>        
						</div>
					<?php }else if(isset($col) && $col=="2"){ ?>
						<div class="col-md-3 col-sm-6">
							<?=(isset($left_content)?$left_content:'')?>
						</div>
						<div class="col-md-9 col-sm-6">
							  <?=(isset($main_content)?$main_content:'')?>     
						</div>
					<?php }else if(isset($col) && $col=="1"){ ?>
						<div class="col-md-12 col-sm-6">
							<div class="section_dot">
								<?php echo (isset($_SESSION[$this->config->item('session_prefix')]['link'])?$this->libs->breadcrumb($_SESSION[$this->config->item('session_prefix')]['link']):''); unset($_SESSION[$this->config->item('session_prefix')]['link']); ?>  
							</div>  
							  <?=(isset($main_content)?$main_content:'')?>     
						</div>
					<?php }else{?>
						<div class="col-md-12 col-sm-12">
							<div class="section_dot">
								<?php echo (isset($_SESSION[$this->config->item('session_prefix')]['link'])?$this->libs->breadcrumb($_SESSION[$this->config->item('session_prefix')]['link']):''); unset($_SESSION[$this->config->item('session_prefix')]['link']);?>  
							</div> 
							<?=(isset($main_content)?$main_content:'')?>     
						</div>
					<?php }?>
				  </div><!--/row-->    
				</div><!--/main-->
			</div>
			<div class="">
				<footer class="footer">
					<div class="text-center" style="background-color : #013161;">
						<div class="container">
							<div class="section_footer">
								<div class="row">
									<div class="col-md-6" style="text-align:left;">
										<h4 style="color:#fff;">Site Map : </h4>
										<ul class="site-map" style="color:#fff;border-bottom: 1px solid #344468;list-style: outside none none;padding-bottom:20px;">
											<li><a href="<?=site_url();?>">Beranda</a></li>
											<li>Produk Pinjaman
												<ul>
													<li><a href="<?=site_url('produk/ritel');?>">Pinjaman Ritel</a></li>
													<li><a href="<?=site_url('produk/mikro');?>">Pinjaman Mikro</a></li>
												</ul>
											</li>
											<li>Simulasi
												<ul>
													<li><a href="<?=site_url('simulasi/briguna');?>">Briguna</a></li>
													<li><a href="<?=site_url('simulasi/kemitraan');?>">Kemitraan</a></li>
												</ul>
											</li>
											<li>Pengajuan
												<ul>
													<li><a href="<?=site_url('pengajuan/ritel');?>">Pinjaman Ritel</a></li>
													<li><a href="<?=site_url('pengajuan/mikro');?>">Pinjaman Mikro</a></li>
												</ul>
											</li>
										</ul>
									</div>
									<div class="col-md-6 foot-icon">
										<a href="http://bri.co.id" target="_ext"  style="text-decoration:none;color:#FFF;">
											<img src="<?=base_url().'images/home/bri-logo.png'?>" width="150px" style="margin-top:10px;z-index:0;">
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div style="text-align:center;background-color:#fff;color:#000;padding:10px;">
						<a class="hover_orange" href="http://bri.co.id" target="_ext"  style="text-decoration:none;">Copyright &copy; 2015, PT Bank Rakyat Indonesia Tbk.</a>
					</div>
				</footer>
			</div>
		</div>
	</div>
	<!--script src="<--?=//base_url();?>plugins/js/jquery-1.11.3.min.js"></script-->
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="<?=base_url();?>plugins/js/bootstrap.min.js"></script>
	<script src="<?=base_url();?>plugins/js/select2.min.js"></script>		
	<script src="<?=base_url();?>plugins/js/scripts.js"></script>
	<script src="<?=base_url();?>plugins/js/jquery.smartmenus.min.js"></script>
	<script src="<?=base_url();?>plugins/js/jquery.smartmenus.bootstrap.min.js"></script>
	<script src="<?=base_url();?>plugins/js/jquery.number.min.js"></script>
	<script src="<?=base_url();?>plugins/js/sticky/jquery.sticky.js"></script>
	<script src="<?=base_url();?>plugins/js/ajaxupload_3_5.js"></script>
	<script type="text/javascript"  src="https://code.highcharts.com/maps/highmaps.js"></script>
	<script type="text/javascript"  src="https://code.highcharts.com/maps/modules/exporting.js"></script>
	<script type="text/javascript"  src="https://code.highcharts.com/mapdata/countries/id/id-all.js"></script>
	<script>(function($,d){$.each(readyQ,function(i,f){$(f)});$.each(bindReadyQ,function(i,f){$(d).bind("ready",f)})})(jQuery,document)</script>
	<script>
	$(document).ready(function(){
		$('.alert').click(function(){
			$(this).remove();
		});
		// $(this).bind("contextmenu", function(e) {
			// e.preventDefault();
		// });
		
		// $(this).requestPage(self.location.hash);
		var trigger = $('.hamburger'),overlay = $('.overlay'),isClosed = false;
		trigger.click(function () {
			hamburger_cross();      
		});
		function hamburger_cross() {
			if (isClosed == true) {          
				overlay.hide();
				isClosed = false;
			} else {   
				overlay.show();
				isClosed = true;
			}
		}
		$('.burger_close').click(function() {
			$('#wrapper').toggleClass('toggled');
			overlay.hide();
			isClosed = false;
		});
		$('[data-toggle="offcanvas"]').click(function () {
			$('#wrapper').toggleClass('toggled');
		});
		$(".dropdown-toggle.side-dropdown").click(function(e){
		  $(this).closest('li').toggleClass('open') //show dropdown
		  e.stopPropagation(); //stops from hiding menu
		});
		$('#myCarousel').carousel({
			interval:   15000
		});
		setInterval(function(){
			$('a[data-slide=next]').click();
		}, 15000);
		
		$('.carousel-controls').hide();
		$('#myCarousel').mouseenter(function(){
			$('.carousel-controls').show();
		});
		$('#myCarousel').mouseleave(function(){
			$('.carousel-controls').hide();
		});
		$("#subnav").sticky({topSpacing:0});
	});
	</script>
	</body>
</html>