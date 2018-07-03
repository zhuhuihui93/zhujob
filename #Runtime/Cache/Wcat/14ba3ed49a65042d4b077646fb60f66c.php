<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <!-- <title><?php echo ($name); ?> </title> -->
    <link href="/Public/Wcat/css/clear.css" rel="stylesheet"/>
    <link href="/Public/Wcat/css/mui.min.css" rel="stylesheet"/>
    <link href="/Public/Wcat/css/style.css" rel="stylesheet"/>
    <script src="/Public/Wcat/js/self-adaption.js"></script>
</head>
<body>
	<header class="mui-bar mui-bar-nav">
	    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
	    <h1 class="mui-title">个人资料</h1>
	</header>
	<div class="mui-content">
		<ul class="mui-table-view">
		    <li class="mui-table-view-cell mui-media Head-portrait">
		        <div class="mui-pull-right">
		        	<img src="<?php echo ($users["user_img"]); ?>"/>
		        </div>
		        <p class="mui-pull-left">头像</p>
		    </li>
		</ul>
		<ul class="mui-table-view personal-data">
	        <li class="mui-table-view-cell">
	            <a class="mui-navigate-right mui-clearfix">
	                <p class="mui-pull-left">用户名</p>
	                <span class="mui-pull-right"><?php echo ($users["user_name"]); ?></span>
	            </a>
	        </li>
	        <li class="mui-table-view-cell">
	            <a href="<?php echo U('Users/binding_phone');?>" class="mui-navigate-right mui-clearfix">
	                <p class="mui-pull-left">手机号</p>
	                <span class="mui-pull-right"><?php echo ($users["phone"]); ?></span>
	            </a>
	        </li>
	    </ul>
	</div>
	<script src="/Public/Wcat/js/mui.min.js"></script>
    <script type="text/javascript" charset="utf-8">
      	mui.init();
    </script>
</body>
</html>