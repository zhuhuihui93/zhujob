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
		<form id="PhoneFormId">
		<ul class="mui-table-view myMuiInpList">
	        <li class="mui-table-view-cell">
	           <input type="number" name="phone" placeholder="请输入您的手机号" maxlength="11"/>
	        </li>
	        <li class="mui-table-view-cell mui-clearfix">
	        	<input type="number" name="sms_code" placeholder="请输入您的验证码" class="mui-pull-left" maxlength="9"/>
	        	<button type="button" class="mui-pull-right mui-btn mui-btn-outlined" id="binding_phone_code">获取验证码</button>
	        </li>
	    </ul>
		</form>
	   <div class="myBtnSur_1">
	   		<button class="mui-btn mui-btn-outlined " id="PhoneSubmit">确定</button>
	   </div>
	   <div id="popover2" class="Scavenging-charging-Popup2 mui-popover">
			<i class="mui-icon mui-icon-closeempty" id="icon-closeempty2"></i>
			<p id="data1"></p>
		</div> 
	</div>
	<script src="/Public/Wcat/js/mui.min.js"></script>
	<script src="/Public/Wcat/js/jquery-1.11.1.min.js"></script>
	<script src="/Public/Wcat/js/admin.js"></script>
    <script type="text/javascript" charset="utf-8">
      	mui.init();
    </script>
</body>
</html>
<script type="text/javascript">
	mui.init();
	window.onload=function(){
		var data1 = document.getElementById('data1');
  		var oIcon_closeempty2=document.getElementById('icon-closeempty2');
		oIcon_closeempty2.addEventListener('tap',function(){
	  		mui('#popover2').popover('hide');//弹出层关闭按钮
	  	});
	}
</script>