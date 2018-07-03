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
		<div class="integral-recharge">
			<div class="integral-recharge-div1">
				<h5>积分余额</h5>
				<p><?php echo ($users['integral']); ?></p>
				<input type="hidden" name="openId" value="<?php echo ($users['openid']); ?>">
			</div>
			<div class="integral-recharge-div2">
				<input type="number" name="money" placeholder="请在此输入充值金额(元)" id="Inp" oninput="NumMethod();" />
				<div><p>*1元=10积分&nbsp;&nbsp;&nbsp;当前积分：<span id="InpNub">0</span></p></div>
			</div>
			<div class="integral-recharge-div3">
				<h4>充值优惠规则</h4>
				<p>1.充值满100元送80积分</p>
				<p>2.充值满200元送200积分</p>
			</div>
		</div>
		<div class="myBtnSur_1">
	   		<button class="mui-btn mui-btn-outlined " id="paymentIntegral">购买积分</button>
	   </div>
	</div>
	<div id="popover2" class="Scavenging-charging-Popup2 mui-popover">
		<i class="mui-icon mui-icon-closeempty" id="icon-closeempty2"></i>
		<p id="data1"></p>
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
	function NumMethod(){
  			var oInp=document.getElementById('Inp');
  			var oInpNub=document.getElementById('InpNub');
  			var oNub=null;
  			console.log(oInp.value)
  			if(oInp.value >= 100 && oInp.value < 200 ){
  				oNub=oInp.value*10+80;
  				console.log(oNub);
  				oInpNub.innerHTML=oNub;
  			}
  			else if( oInp.value >= 200){
  				oNub=oInp.value*10+200;
  				console.log(oNub);
  				oInpNub.innerHTML=oNub;
  			}
  			else{
  				oNub=oInp.value*10;
  				console.log(oNub);
  				oInpNub.innerHTML=oNub;
  			}
  		}
	/* 积分充值 */
	$('#paymentIntegral').click(function(){
		var money = $("input[name='money']").val();
		var type = 1;
		var body = '积分充值';
		var openId =  $("input[name='openId']").val();
		if(!money){
			mui('#popover2').popover('show');
			data1.innerHTML="请输入金额！";
			return false;
		}
		$.ajax({
		    cache: true,
		    type: "POST",
		    url:"/Wcat/Wechat/payment",
		    data:{money:money,type:type,body:body,openId:openId},
		    async: false,
		    dataType : "json",
		    error: function(request) {

		    },
		    success: function(data) {
		        if(data['code'] == 200){
		        	function jsApiCall()
				    {
				        WeixinJSBridge.invoke(
				            'getBrandWCPayRequest',
				            $.parseJSON(data['data']),
				            function(res){
				                WeixinJSBridge.log(res.err_msg);
				                if(res.err_msg == "get_brand_wcpay_request:ok"){    
				                    $(location).attr('href', '/Wcat/Index/index');  
				                }else if(res.err_msg == "get_brand_wcpay_request:cancel"){    
				                    mui('#popover2').popover('show');
									data1.innerHTML="用户取消支付!";
				                }else{     
				                    mui('#popover2').popover('show');
									data1.innerHTML="支付失败!"; 
				                }    
				            }
				        );
				    }				   
			        if (typeof WeixinJSBridge == "undefined"){
			            if( document.addEventListener ){
			                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			            }else if (document.attachEvent){
			                document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			            }
			        }else{
			            jsApiCall();
			        }
		        }else{
		        	mui('#popover2').popover('show');
					data1.innerHTML=data['msg'];
		        }
		    }
		});
	});
	
</script>