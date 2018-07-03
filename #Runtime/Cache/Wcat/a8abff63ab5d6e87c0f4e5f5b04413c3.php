<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <!-- <title><?php echo ($name); ?></title> -->
    <link href="/Public/Wcat/css/clear.css" rel="stylesheet"/>
    <link href="/Public/Wcat/css/mui.min.css" rel="stylesheet"/>
    <link href="/Public/Wcat/css/style.css" rel="stylesheet"/>
    <script src="/Public/Wcat/js/self-adaption.js"></script>
</head>
<body>
	<header class="mui-bar mui-bar-nav">
	    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
	    <h1 class="mui-title"><?php echo ($name); ?></h1>
	</header>
	<div class="mui-content">
		<div class="Contact_us_box"></div>
		<div class="Contact_us_phoneNumber">
			<h4>联系电话</h4>
			<a>4001038811</a>
		</div>
		<div class="Contact_us_Address">
			<h4>公司住址</h4>
			<p>北京市大兴区西红门镇嘉悦广场5号楼815</p>
		</div>
		<div class="Contact_us_map">
			<img src="/Public/Wcat/images/map.jpg" />
		</div>
	</div>
	<script src="/Public/Wcat/js/mui.min.js"></script>
    <script type="text/javascript" charset="utf-8">
      	mui.init();
    </script>
</body>
</html>