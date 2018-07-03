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
		 <ul class="mui-table-view Commonly-used-box">
		 	<?php if($charge != '' ): if(is_array($charge)): $i = 0; $__LIST__ = $charge;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li class="mui-table-view-cell">
	            <a href="#">
	              	<h6><?php echo ($list['charging_name']); ?></h6>
	              	<div class="mui-clearfix">
	              		<span class="mui-pull-left">距您<?php echo ($list['km']); ?>km</span>
	              		<div class="mui-pull-right myBtn-warrp">
	              			<button type="button" class="mui-btn mui-btn-outlined">导航</button>
	              			<button type="button" class="mui-btn mui-btn-outlined">充电</button>
	              		</div>
	              	</div>
	            </a>
	        </li><?php endforeach; endif; else: echo "" ;endif; ?>
	   	    <?php else: ?>
		   	    <div class="No-position-box"></div>
				<div class="No-position-btn">
					<a href="<?php echo U('Users/users');?>">返回个人中心</a>
				</div><?php endif; ?>
	    </ul>
	</div>
	<script src="/Public/Wcat/js/mui.min.js"></script>
	<script src="/Public/Wcat/js/jquery-1.11.1.min.js"></script>
	<script src="/Public/Wcat/js/admin.js"></script>
    <script type="text/javascript" charset="utf-8">
      	mui.init();
    </script>
</body>
</html>