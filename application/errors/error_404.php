<!DOCTYPE html>
<html lang="en">
<head>
<title>404 Page Not Found</title>
<style type="text/css">

::selection{ background-color: #E13300; color: white; }
::moz-selection{ background-color: #E13300; color: white; }
::webkit-selection{ background-color: #E13300; color: white; }

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

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

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	-webkit-box-shadow: 0 0 8px #D0D0D0;
}
.section_dot , .section_pattern {
	content: "";
    height: 100%;
	/*position: absolute;*/
    left: 0;
    top: 0;
    width: 100%;
}
.section_dot {
	background-image: url("../../images/home/pattern-dotted.png");
}
.section_pattern {
	background-image: url("../../images/home/pattern-diagonal.png");
}
.container-main {
	box-shadow: 0px 1px 2px rgba(0,0,0,0.2);
    background-color: #E1EEF7;
    border-radius: 3px;
}

p {
	margin: 12px 15px 12px 15px;
}
</style>
</head>
<body>
	<div id="container container-main">
		<div class="section_pattern">
			<!--h1><?php // echo $heading; ?></h1-->
			<?php // echo $message; ?>
			<div id="construct" style="text-align:center;z-index:1;position:relative;">
				<img src="<?=base_url().'images/home/underconstruction.jpg'?>" width="100%x">
				<a href="<?=site_url();?>" style="display:block;">Go Back</a>
			</div>
		</div>
	</div>
</body>
</html>