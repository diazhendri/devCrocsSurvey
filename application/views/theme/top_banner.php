<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="" style=" margin-top:0px;padding-right:0px;padding-left:0px;">
<div class="">
	<div class="row" style="margin:0px;">
		<div class="col-sm-12" style="padding-right:0px;padding-left:0px;">
			<div id="myCarousel" class="carousel slide section_pattern" height="300px" width="100%">
				<div class="carousel-inner">
					<?php if(!empty($banner_top)) { $i=0;foreach($banner_top as $val) : $file = explode(".",$val); $i++;?>
					<article class="item <?php echo ($i == 1)?'active':''; ?>">
						<a href="<?=$val?>" target="blank"><img src="<?=base_url('images/carousel/'.$val);?>" height="300px"></a>
					</article>
					<?php endforeach; } ?>
			  </div>
			  <!-- Indicators -->
			  <!--ol class="carousel-indicators">
				<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
				<li data-target="#myCarousel" data-slide-to="1"></li>
				<li data-target="#myCarousel" data-slide-to="2"></li>
				<li data-target="#myCarousel" data-slide-to="3"></li>
			  </ol-->        
			  <!-- Controls -->
			  <div class="carousel-controls">
				  <a class="carousel-control left" href="#myCarousel" data-slide="prev">
					<span class="fa fa-chevron-left"></span>
				  </a>
				  <a class="carousel-control right" href="#myCarousel" data-slide="next">
					<span class="fa fa-chevron-right"></span>
				  </a>
			  </div>
			</div>
		</div>
		<!--div class="col-sm-3" style="padding-left:0px;">
			<div class="panel list-group-panel">
				<div class="panel-heading list-group-populer list-group-item-success">POPULER</div>
				<div class="panel-body list-group-body-populer">
				</div>
			</div>
		</div-->
	</div>
</div>
</div>