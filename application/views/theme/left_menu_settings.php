<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script>
$(document).ready(function(){
	$('#sidebar > a').on('click', function (e) {
		e.preventDefault();
		if(!$(this).hasClass("active")){
			var lastActive = $(this).closest("#sidebar").children(".active");
			lastActive.removeClass("active");
			lastActive.next('div').collapse('hide');
			$(this).addClass("active");
			$(this).next('div').collapse('show');
		}
	});
});
</script>
<div class="panel panel-default">
	<?php echo $this->menu->generate_menu_settings('0');?>
</div>