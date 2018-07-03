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
	<div class="mui-content" style="position: relative;">
		<div class="Scavenging-charging-box">
			<h6><?php echo ($charge['charge']['charging_name']); ?></h6>
			<h4><i></i>插座<?php echo ($charge['code']['number']); ?></h4>
			<ul class="mui-clearfix">
				<li class="mui-pull-left">
					<span class="mui-badge">1</span>
					连接电源
				</li>
				<li class="mui-pull-left">
					<span class="mui-badge">2</span>
					选择充电时长
				</li>
				<li class="mui-pull-left">
					<span class="mui-badge">3</span>
					开始充电
				</li>
			</ul>
		</div>
		<div class="Scavenging-charging-time">
			<div class="TileNumber-Box">
				<p><i></i>您的设备功率为：<span><?php echo ($charge['code']['power']); ?></span>w</p>
			</div>
			<ul class="mui-clearfix" id="Scavenging-time">
				<li class="mui-pull-left">
					<button class="mui-btn mui-btn-outlined"><span>1</span>小时</button>
				</li>
				<li class="mui-pull-left">
					<button class="mui-btn mui-btn-outlined"><span>2</span>小时</button>
				</li>
				<li class="mui-pull-left active">
					<button class="mui-btn mui-btn-outlined"><span>4</span>小时</button>
				</li>
				<li class="mui-pull-left">
					<button class="mui-btn mui-btn-outlined"><span>8</span>小时</button>
				</li>
			</ul>
			<input type="hidden" name="power" value="<?php echo ($charge['code']['power']); ?>" id="power">
			<input type="hidden" name="code_id" value="<?php echo ($charge['code_id']); ?>" id="code_id">
			<input type="hidden" name="charge_id" value="<?php echo ($charge['charge']['id']); ?>" id="charge_id">
			<input type="hidden" name="is_free" value="<?php echo ($charge['charge']['is_free']); ?>">
			<div class="Scavenging-charging-btn">
				<button type="button" id="bottomPopover1" class="mui-btn mui-btn-outlined">开始充电</button>
			</div>
			<!-- <div class="Scavenging-charging-Explain">
				<h6>计费优惠说明</h6>
				<p>一. 0.5元/1小时，1元/2小时，1.5元/4小时, 1.8元/8小时（仅限于200瓦之内包含200瓦)</p>
				<p>二. 0.8元/1小时，1.5元/2小时，2.4元/4小时, 3元/8小时(仅限于200瓦以上500瓦以内)</p>
				<p>三.其他功率电源以单价计费为准</p>
			</div> -->
		</div>
		<!-- <div id="popover" class="Scavenging-charging-Popup mui-popover">
			<i class="mui-icon mui-icon-closeempty" id="icon-closeempty"></i>
			<div class="mui-input-row mui-radio mui-left">
				<label>微信支付</label>
				<input id="3" name="radio1" type="radio" checked="checked" class="rds"/>
			</div>
			<p>使用以下方式支付要先充值哦！</p>
			<div class="mui-input-row mui-radio mui-left">
				<label>积分抵扣 （优惠）</label>
				<input id="1" name="radio1" type="radio" class="rds"/>
			</div>
			<div class="mui-input-row mui-radio mui-left Scavenging-charging-margin">
				<label>月卡抵扣（优惠）</label>
				<input id="2" name="radio1" type="radio" class="rds"/>
			</div>
			<div class="Scavenging-charging-Popup-btn mui-clearfix">
				<p class="mui-pull-left" id="alertCont"></p>
				<a class="mui-pull-right mui-btn mui-btn-outlined" id="myBtn_sur">确定</a>
			</div>
		</div> -->
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
	  	var Len=mui('#Scavenging-time li');
	  	var oPowerVal=document.getElementById('power').value;      //功率值
	  	for(var i=0; i<Len.length; i++)
  			{
  				Len[i].index=i;
  				if(Len[i].className=='mui-pull-left active')
  				{
  					console.log(oPowerVal);
  					TimeNum=parseInt(Len[i].children["0"].children["0"].innerHTML, 10);
  					if(oPowerVal <= 200)
  					{
  						console.log('小于200');
  						if(TimeNum == 1)
  						{
  							money=0.5;
  						}
  						else if(TimeNum == 2)
  						{
  							money=1;
  						}
  						else if(TimeNum == 4)
  						{
  							money=1.5;
  						}
  						else if(TimeNum == 8) 
  						{
  							money=1.8;
  						}
  						
  						console.log(money);
  					}
  					else if(oPowerVal > 200 &&  oPowerVal <= 500)
  					{
  						console.log('大于200小于等于500');
  						if(TimeNum == 1)
  						{
  							money=0.8;
  						}
  						else if(TimeNum == 2)
  						{
  							money=1.5;
  						}
  						else if(TimeNum == 4)
  						{
  							money=2.4;
  						}
  						else if(TimeNum == 8) 
  						{
  							money=3;
  						}
  						
  						console.log(money);
  					}
  					else{
  						console.log('其他功率');
  						if(TimeNum == 1)
  						{
  							money=1.5;
  						}
  						else if(TimeNum == 2)
  						{
  							money=3;
  						}
  						else if(TimeNum == 4)
  						{
  							money=4;
  						}
  						else if(TimeNum == 8) 
  						{
  							money=10;
  						}
  						
  						console.log(money);
  					}					
  				}
				Len[i].onclick=function(){
					for(var j=0;j<Len.length;j++){
						Len[j].className='mui-pull-left';
					}
					this.className='mui-pull-left active';	
					TimeNum=parseInt(this.children[0].children[0].innerHTML, 10);
					console.log(oPowerVal);
					if(oPowerVal <= 200)
  					{
  						console.log('小于200');
  						if(TimeNum == 1)
  						{
  							money=0.5;
  						}
  						else if(TimeNum == 2)
  						{
  							money=1;
  						}
  						else if(TimeNum == 4)
  						{
  							money=1.5;
  						}
  						else if(TimeNum == 8) 
  						{
  							money=1.8;
  						}
  						
  						console.log(money);
  					}
  					else if(oPowerVal > 200 &&  oPowerVal <= 500)
  					{
  						console.log('大于200小于等于500');
  						if(TimeNum == 1)
  						{
  							money=0.8;
  						}
  						else if(TimeNum == 2)
  						{
  							money=1.5;
  						}
  						else if(TimeNum == 4)
  						{
  							money=2.4;
  						}
  						else if(TimeNum == 8) 
  						{
  							money=3;
  						}
  						
  						console.log(money);
  					}
  					else{
  						console.log('其他功率');
  						if(TimeNum == 1)
  						{
  							money=1.5;
  						}
  						else if(TimeNum == 2)
  						{
  							money=3;
  						}
  						else if(TimeNum == 4)
  						{
  							money=4;
  						}
  						else if(TimeNum == 8) 
  						{
  							money=10;
  						}
  						console.log(money);
  					}
				};
				
  			}
  			/* 无支付下单 */
			$('#bottomPopover1').click(function(){
				var power = $("input[name='power']").val();
				var charge_id = $("input[name='charge_id']").val();
				var code_id = $("input[name='code_id']").val();
				var is_free = $("input[name='is_free']").val();
				$.ajax({
				    cache: true,
				    type: "POST",
				    url:"/Wcat/Jssdk/orders",
				    data:{power:power,charge_id:charge_id,code_id:code_id,is_free:is_free,charge_time:TimeNum},
				    async: false,
				    dataType : "json",
				    error: function(request) {
				    },
				    success: function(data) {
				        if(data['code']==200){
				        	mui('#popover2').popover('show');
							data1.innerHTML= data['msg'];
				        	$(location).attr('href', '/Wcat/Charge/now_charge?order_sn='+data['data']['order_sn']);
				        }else{
				        	mui('#popover2').popover('show');
							data1.innerHTML= data['msg'];
				        }
				    }
				});
			});
	}
</script>