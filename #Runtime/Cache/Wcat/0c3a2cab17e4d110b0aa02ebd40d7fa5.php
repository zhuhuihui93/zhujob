<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title><?php echo ($name); ?></title>
    <link href="/Public/Wcat/css/clear.css" rel="stylesheet"/>
    <link href="/Public/Wcat/css/mui.min.css" rel="stylesheet"/>
    <link href="/Public/Wcat/css/style.css" rel="stylesheet"/>
</head>
<body>
	<header class="mui-bar mui-bar-nav">
	    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
	    <h1 class="mui-title">我的管理中心</h1>
	</header>
	<div class="mui-content">
		<div class="urse_pic_box">
			<img src="/Public/Wcat/images/urse_pic.jpg" />
			<div class="urse_name mui-clearfix">
				<div class="Head_portrait mui-pull-left">
					<img src="<?php echo ($users["user_img"]); ?>" />
				</div>
				<p class="mui-pull-right"><?php echo ($users["user_name"]); ?></p>
			</div>
			<a href="<?php echo U('Users/users_info');?>" class="Binding_phone_number mui-clearfix">
				<span></span>
				<p class="mui-pull-right">绑定手机号</p>
			</a>
			<div class="integral-box">
				<span>积分余额(分)</span>
				<p><?php echo ($users["integral"]); ?></p>
			</div>
		</div>
		<div class="my-tab-box">
			<div class="my-tab-tit">
				<a href="<?php echo U('Charge/contact_us');?>">关于我们</a>
				<a href="<?php echo U('Jssdk/integral_record');?>">积分充值</a>
			</div>
			<div class="mab-tab-ul-box">
				<ul>
					<li class="mui-clearfix">
						<span class="mui-pull-left"></span>
						<a href="<?php echo U('Charge/now_charge');?>" class="mui-pull-left">正在充电</a>
					</li>
					<li class="mui-clearfix">
						<span class="mui-pull-left"></span>
						<a href="<?php echo U('Charge/my_charge');?>" class="mui-pull-left">常用电站</a>
					</li>
					<li class="mui-clearfix">
						<span class="mui-pull-left"></span>
						<a href="<?php echo U('Charge/charge_list');?>" class="mui-pull-left">充电记录</a>
					</li>
					<li class="mui-clearfix">
						<span class="mui-pull-left"></span>
						<a href="<?php echo U('Charge/record_list');?>" class="mui-pull-left">充值记录</a>
					</li>
					<li class="mui-clearfix">
						<span class="mui-pull-left"></span>
						<a href="<?php echo U('Users/month_card');?>" class="mui-pull-left">我的月卡</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<script src="/Public/Wcat/js/mui.min.js"></script>
    <script type="text/javascript" charset="utf-8">
      	mui.init();
    </script>
</body>
</html>