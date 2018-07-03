<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <!-- <title><?php echo ($name); ?></title> -->
    <link href="/Public/Wcat/css/clear.css" rel="stylesheet"/>
    <link href="/Public/Wcat/css/mui.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/Public/Wcat/css/iconfont.css"/>
    <link rel="stylesheet" href="/Public/Wcat/css/style.css"/>
    <script src="/Public/Wcat/js/self-adaption.js"></script>
</head>
<body>
		<nav class="mui-bar mui-bar-tab">
			<a class="mui-tab-item" href="#tabbar">
				<span class="mui-icon iconfont icon-guanyuwomen" style="font-size: 25px; display: inline-block;"></span>
				<span class="mui-tab-label">关于我们</span>
			</a>
			<a class="mui-tab-item" href="#tabbar-with-chat">
				<span class="mui-icon iconfont icon-dingwei1"></span>
				<span class="mui-tab-label">附近电站</span>
			</a>
			<a class="mui-tab-item mui-active" href="#tabbar-with-contact">
				<span class="mui-icon iconfont icon-iconfonticon5"></span>
				<span class="mui-tab-label">个人中心</span>
			</a>
		</nav>
		<div class="mui-content">
			<div id="tabbar" class="mui-control-content">
				<header class="mui-bar mui-bar-nav">
					<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
					<h1 class="mui-title">联系我们</h1>
				</header>
				<div class="Contact_us_box"></div>
				<div class="Contact_us_phoneNumber">
					<h4>联系电话</h4>
					<a>4001038811</a>
				</div>
				<div class="Contact_us_Address">
					<h4>公司住址</h4>
					<p>北京市大兴区西红门镇嘉悦广场5号楼815</p>
				</div>
				<div class="Contact_us_map" style="margin-bottom:2rem;">
					<img src="/Public/Wcat/images/map.jpg" />
				</div>
			</div>
			<div id="tabbar-with-chat" class="mui-control-content">
				<header class="mui-bar mui-bar-nav">
					<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
					<h1 class="mui-title">附近电站</h1>
				</header> 
				<ul class="mui-table-view nearby-ul" style="margin-top: 45px;">
			        <?php if($info != '' ): if(is_array($info)): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li class="mui-table-view-cell nearby-li">
			            <div class="mui-clearfix">
			            	<h6 class="mui-pull-left"><?php echo ($list['charging_name']); ?></h6>
			            	<p class="mui-pull-right">可用插座：<?php echo ($list['number']); ?></p>
			            </div>
			            <div class="mui-clearfix">
			            	<p class="mui-pull-left"><?php echo ($list['address']); ?></p>
			            	<p class="mui-pull-left">距您<span><?php echo ($list['km']); ?></span>km</p>
			            </div>
			            <div>
			            	<button type="button" class="mui-btn mui-btn-outlined">导航</button>
			            	<button type="button" class="mui-btn mui-btn-outlined">充电</button>
			            </div>
			        </li><?php endforeach; endif; else: echo "" ;endif; ?>
			   	    <?php else: ?>
				   	    <div class="No-position-box"></div>
						<div class="No-position-btn">
							<a href="<?php echo U('Users/users');?>">返回个人中心</a>
						</div><?php endif; ?>
				</ul>
			</div>
			<div id="tabbar-with-contact" class="mui-control-content mui-active">
				<header class="mui-bar mui-bar-nav">
					<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
					<h1 class="mui-title">个人中心</h1>
				</header>
				<div class="urse_pic_box" style="margin-top: 45px;">
					<img src="/Public/Wcat/images/urse_pic.jpg" />
					<div class="urse_name mui-clearfix">
						<a href="<?php echo U('Users/users_info');?>" class="Head_portrait mui-pull-left">
							<img src="<?php echo ($users["user_img"]); ?>" />
						</a>
						<p class="mui-pull-right"><?php echo ($users["user_name"]); ?></p>
					</div>
					<a href="<?php echo U('Users/binding_phone');?>" class="Binding_phone_number mui-clearfix">
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
						<a href="<?php echo U('Users/integral_record');?>">积分充值</a>
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
		</div>
		<script src="/Public/Wcat/js/mui.min.js"></script>
		<script>
			mui.init({
				swipeBack:true //启用右滑关闭功能
			});
		</script>
</body>
</html>