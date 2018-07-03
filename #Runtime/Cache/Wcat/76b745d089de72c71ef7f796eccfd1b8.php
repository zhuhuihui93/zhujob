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
		<ul class="mui-table-view">
	        <li class="mui-table-view-cell Recharge-record-tit">
	           <i class="mui-pull-left"></i>
	           <!-- <h6 class="mui-pull-left">本月充电记录</h6> -->
	        </li>
	    </ul>
	    <ul class="mui-table-view Recharge-record-list">
            <?php if($list != '' ): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li class="mui-table-view-cell">
                <div class="mui-clearfix">
                	<h5 class="mui-pull-left"><?php echo ($list['charging_name']); ?></h5>
                    <?php if($list["order_status"] == '3' ): ?><h6 class="mui-pull-right RechargeRecordSuccess">订单完结，已付款</h6>
                    <?php elseif($list["order_status"] == '2'): ?>
                    <h6 class="mui-pull-right RechargeRecordSuccess">订单已支付</h6>
                    <?php else: ?>
                    <h6 class="mui-pull-right RechargeRecordSuccess">订单未支付</h6><?php endif; ?>
                </div>
                <div class="mui-clearfix">
                	<div class="mui-pull-left">
                		<p class="Charge-record-number">
                			<i class="icon-chongdian"></i>充电电量（度）：<span><?php echo ($list['electricity']); ?></span>
                		</p>
                		<p class="Charge-record-number">                			
                            <?php if($list["money"] == '0' ): ?><i class="icon-xiaofei"></i>消费积分：
                                <span><?php echo ($list['integral']); ?></span>
                            <?php else: ?>
                                <i class="icon-xiaofei"></i>消费金额（元）：
                                <span><?php echo ($list['money']); ?></span><?php endif; ?>
                		</p>
                	</div>
                	<div class="mui-pull-right Charge-record-time"><?php echo date('Y-m-d H:s',$list['create_time']);?></div>
                </div>
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