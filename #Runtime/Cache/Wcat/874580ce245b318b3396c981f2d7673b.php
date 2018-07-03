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
	           <h6 class="mui-pull-left"><?php echo ($name); ?></h6>
	        </li>
	    </ul>
	    <ul class="mui-table-view Recharge-record-list">
            <?php if($list != '' ): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i; if($list["recharge_type"] == 1 ): ?><li class="mui-table-view-cell">
                        <div class="mui-clearfix">
                            <?php if($list["record_status"] == 1 ): ?><h5 class="mui-pull-left">充值成功</h5>
                            <h6 class="mui-pull-right RechargeRecordSuccess">+<?php echo ($list['recharge_money']); ?></h6>
                            <?php else: ?>
                            <h5 class="mui-pull-left">充值失败</h5>
                            <h6 class="mui-pull-right RechargeRecordFail">-<?php echo ($list['recharge_money']); ?></h6><?php endif; ?>                       
                        </div>
                        <div class="mui-clearfix">
                            <p class="mui-pull-left"><?php echo date('Y-m-d H:s',$list['create_time']);?></p>
                            <p class="mui-pull-right">余额 <?php echo ($list['total_money']); ?></p>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="mui-table-view-cell">
                        <div class="mui-clearfix">
                            <?php if($list["record_status"] == 1 ): ?><h5 class="mui-pull-left">充值成功</h5>
                            <h6 class="mui-pull-right RechargeRecordSuccess">+<?php echo ($list['integral']); ?></h6>
                            <?php else: ?>
                            <h5 class="mui-pull-left">充值失败</h5>
                            <h6 class="mui-pull-right RechargeRecordFail">-<?php echo ($list['integral']); ?></h6><?php endif; ?>    
                        </div>
                        <div class="mui-clearfix">
                            <p class="mui-pull-left"><?php echo date('Y-m-d H:s',$list['create_time']);?></p>
                            <p class="mui-pull-right">积分 <?php echo ($list['total_integral']); ?></p>
                        </div>
                    </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
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