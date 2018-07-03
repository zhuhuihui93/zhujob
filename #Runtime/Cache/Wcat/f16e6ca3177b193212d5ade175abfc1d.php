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
	    <ul class="mui-table-view Recharge-record-list">
	    	<?php if($list != '' ): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li class="mui-table-view-cell">
	                <a class="mui-navigate-right" href="<?php echo U('Charge/now_charge');?>?order_sn=<?php echo ($list['order_sn']); ?>">
	                	<div class="mui-clearfix">
		                	<h5 class="mui-pull-left"><?php echo ($list['address']); ?><span style="margin-left: 10px;">插座<?php echo ($list['number']); ?></span></h5>
		                </div>
		                <div class="mui-clearfix">
		                	<div class="mui-pull-left">
		                		<p class="Charge-record-number">
		                			<i class="icon-chongdian"></i>充电功率：<span><?php echo ($list['power']); ?>w</span>
		                		</p>
		                		<p class="Charge-record-number">
		                			<i class="icon-shizhong"></i>剩余时间（h）： <span><?php echo ($list['time']); ?></span>
		                		</p>
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