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
			<input type="hidden" name="openid" value="<?php echo ($charge['openid']); ?>" id="openid">
			<div class="Scavenging-charging-btn">
				<button type="button" id="bottomPopover" class="mui-btn mui-btn-outlined">开始充电</button>
			</div>
			<?php if($power == '' ): ?><div class="Scavenging-charging-Explain">
					<h6>计费优惠说明</h6>
					<p>一. 0.5元/1小时，1元/2小时，1.5元/4小时, 1.8元/8小时（仅限于200瓦之内包含200瓦)</p>
					<p>二. 0.8元/1小时，1.5元/2小时，2.4元/4小时, 3元/8小时(仅限于200瓦以上500瓦以内)</p>
					<p>三. 1.5元/1小时，3元/2小时，4元/4小时, 10元/8小时(仅限于500瓦以上1000瓦以内)</p>
					<p>四.其他功率电源以单价计费为准</p>
				</div>
			<?php else: ?>
				<div class="Scavenging-charging-Explain">
					<h6>计费优惠说明</h6>
					<p>一. <?php echo ($power['time1']); ?>元/1小时，<?php echo ($power['time2']); ?>元/2小时，<?php echo ($power['time3']); ?>元/4小时, <?php echo ($power['time4']); ?>元/8小时（仅限于200瓦之内包含200瓦)</p>
					<p>二. <?php echo ($power['time5']); ?>元/1小时，<?php echo ($power['time6']); ?>元/2小时，<?php echo ($power['time7']); ?>元/4小时, <?php echo ($power['time8']); ?>元/8小时(仅限于200瓦以上500瓦以内)</p>
					<p>三. <?php echo ($power['time9']); ?>元/1小时，<?php echo ($power['time10']); ?>元/2小时，<?php echo ($power['time11']); ?>元/4小时, <?php echo ($power['time12']); ?>元/8小时(仅限于500瓦以上1000瓦以内)</p>
					<p>四.其他功率电源以单价计费为准</p>
				</div><?php endif; ?>
		</div>
		<div id="popover" class="Scavenging-charging-Popup mui-popover">
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
    <script type="text/javascript" charset="utf-8">
      	mui.init();
      	window.onload=function(){
      		var Len=mui('#Scavenging-time li');
      		var data1 = document.getElementById('data1');
      		var TimeNum=-99;
      		var user_id=null;
      		var order_sn=null;
      		var checkId=null;
      		var money=null;
      		var openid = document.getElementById('openid').value;
      		var oAlertCont=document.getElementById('alertCont');
      		var oPowerVal=document.getElementById('power').value;      //功率值
      		var charge_id = document.getElementById('charge_id').value;
      		var code_id = document.getElementById('code_id').value;
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
  							TimeNum=1;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 2)
  						{
  							TimeNum=2;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 4)
  						{
  							TimeNum=4;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 8) 
  						{
  							TimeNum=8;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						
  						console.log(money);
  					}
  					else if(oPowerVal > 200 &&  oPowerVal <= 500)
  					{
  						console.log('大于200小于等于500');
  						if(TimeNum == 1)
  						{
  							TimeNum=1;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 2)
  						{
  							TimeNum=2;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 4)
  						{
  							TimeNum=4;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 8) 
  						{
  							TimeNum=8;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						
  						console.log(money);
  					}
  					else{
  						console.log('其他功率');
  						if(TimeNum == 1)
  						{
  							TimeNum=1;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 2)
  						{
  							TimeNum=2;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 4)
  						{
  							TimeNum=4;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 8) 
  						{
  							TimeNum=8;
  							mon(charge_id,oPowerVal,TimeNum);
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
  							TimeNum=1;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 2)
  						{
  							TimeNum=2;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 4)
  						{
  							TimeNum=4;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 8) 
  						{
  							TimeNum=8;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						
  						console.log(money);
  					}
  					else if(oPowerVal > 200 &&  oPowerVal <= 500)
  					{
  						console.log('大于200小于等于500');
  						if(TimeNum == 1)
  						{
  							TimeNum=1;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 2)
  						{
  							TimeNum=2;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 4)
  						{
  							TimeNum=4;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 8) 
  						{
  							TimeNum=8;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						
  						console.log(money);
  					}
  					else{
  						console.log('其他功率');
  						if(TimeNum == 1)
  						{
  							TimeNum=1;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 2)
  						{
  							TimeNum=2;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 4)
  						{
  							TimeNum=4;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						else if(TimeNum == 8) 
  						{
  							TimeNum=8;
  							mon(charge_id,oPowerVal,TimeNum);
  						}
  						console.log(money);
  					}
				};
				
  			}
  			console.log(TimeNum);
      		
      		function mon(id,pow,time){
      			mui.ajax('http://www.bjyilongxiang.com/Wcat/Jssdk/getPower',{
					data:{
						charge_id:id,
						powers:pow,
						times:time,
					},
					dataType:'json',//服务器返回json格式数据
					type:'post',//HTTP请求类型
					success:function(data){
						console.log('ssss');
						console.log(data.data.money);
						money=data.data.money;
					},
					error:function(xhr,type,errorThrown){
						//异常处理；
						console.log('异常');
					}
				});
      		}
      		
      		var oBottomPopover=document.getElementById('bottomPopover');
      		var oIcon_closeempty=document.getElementById('icon-closeempty');
      		var oIcon_closeempty2=document.getElementById('icon-closeempty2');
      		var oPopover=document.getElementById('popover');
      		var oMyBtn_sur=document.getElementById('myBtn_sur');
      		oBottomPopover.addEventListener('tap',function(){
		  		mui('#popover').popover('show');
		  		mui.ajax('http://www.bjyilongxiang.com/Wcat/Jssdk/orders',{
					data:{
						charge_time:TimeNum,
						charge_id:charge_id,
						code_id:code_id,
						power:oPowerVal,
					},
					dataType:'json',//服务器返回json格式数据
					type:'post',//HTTP请求类型
					success:function(data){
						//服务器返回响应，根据响应结果，分析是否登录成功；
						if(data.code == 103)
						{
							mui('#popover2').popover('show');
							data1.innerHTML=data.msg;
						}
						else
						{
							var oData_2=eval(data.data);
							user_id=oData_2.user_id;
      						order_sn=oData_2.order_sn;
      						mui('#popover').popover('show');
						}     					
					},
					error:function(xhr,type,errorThrown){
						//异常处理；
						console.log('失败');
					}
				});
				function getVals(){
	      			var rdsObj=document.getElementsByClassName('rds');
	      			for(var i=0; i<rdsObj.length; i++)
		  			{
		  				rdsObj[i].index=i;
		  				if(rdsObj[i].checked){
		  					checkId=rdsObj[i].getAttribute('id');
							mui.ajax('http://www.bjyilongxiang.com/Wcat/Jssdk/users_account',{
								data:{
									type:checkId,
								},
								dataType:'json',//服务器返回json格式数据
								type:'post',//HTTP请求类型
								success:function(data){
									console.log(data.code);
									if(data.code == 103)
									{
										var oData_3=eval(data.data);
										var oData_3=eval(data.data);
										oAlertCont.innerHTML=data.msg;
										// if( oData_3 == 1 )
										// {
										// 	console.log('没积分');
										// 	$(location).attr('href', '/Wcat/Users/integral_record');
										// 	return false;
										// }
										// else if( oData_3 == 2)
										// {
										// 	console.log('1212');
										// 	$(location).attr('href', '/Wcat/Users/buy_month_card');
										// 	return false;
										// }
									}
									else{
										var oData=eval(data.data);
										if(oData.type == 2)
										{
											oAlertCont.innerHTML='月卡使用还剩余'+oData.surplus_number+'次';
										}else if( oData.type == 1)
										{
											var oData=eval(data.data);
											//money=money*10;
											oAlertCont.innerHTML='积分还剩:'+oData.integral+',需支付:'+money*10+'积分';
										}
										else if( oData.type == 3)
										{
											var oData=eval(data.data);
											oAlertCont.innerHTML='需支付'+money+'元';
										}
									}
									
								},
								error:function(xhr,type,errorThrown){
									//异常处理；
									console.log('失败发送');
								}
							});		
		  				}
						rdsObj[i].onclick=function(){
							if(this.checked){
			  					checkId=this.getAttribute('id');
 								mui.ajax('http://www.bjyilongxiang.com/Wcat/Jssdk/users_account',{
									data:{
										type:checkId,
									},
									dataType:'json',//服务器返回json格式数据
									type:'post',//HTTP请求类型
									success:function(data){
										if(data.code == 103)
										{
											var oData_3=eval(data.data);
											var oData_3=eval(data.data);
											oAlertCont.innerHTML=data.msg;
											// if( oData_3 == 1 )
											// {
											// 	console.log('没积分2');
											// 	$(location).attr('href', '/Wcat/Users/integral_record');
											// 	return false;
											// }
											// else if( oData_3 == 2)
											// {
											// 	console.log('121');
											// 	$(location).attr('href', '/Wcat/Users/buy_month_card');
											// 	return false;
											// }
										}
										else{
											var oData=eval(data.data);
											if(oData.type == 2)
											{
												oAlertCont.innerHTML='月卡使用还剩余'+oData.surplus_number+'次';
											}else if( oData.type == 1)
											{
												var oData=eval(data.data);
												//money=money*10;
												oAlertCont.innerHTML='积分还剩:'+oData.integral+',需支付:'+money*10+'积分';
											}
											else if( oData.type == 3)
											{
												var oData=eval(data.data);
												oAlertCont.innerHTML='需支付'+money+'元';
											}
										}
										
									},
									error:function(xhr,type,errorThrown){
										//异常处理；
										console.log('失败发送');
									}
								});	
			  				}
							
						};
						
		  			}
	      		}
				getVals();
		  	})
      		oIcon_closeempty.addEventListener('tap',function(){
		  		mui('#popover').popover('hide');//弹出层关闭按钮
		  	});
		  	oIcon_closeempty2.addEventListener('tap',function(){
		  		mui('#popover2').popover('hide');//弹出层关闭按钮
		  	});
      		oMyBtn_sur.addEventListener('tap',function(){
      			console.log(checkId);
      			mui.ajax('http://www.bjyilongxiang.com/Wcat/Jssdk/order_payment',{
					data:{
						type:checkId,
						order_sn:order_sn,
						user_id:user_id,
						integral:money,
					},
					dataType:'json',//服务器返回json格式数据
					type:'post',//HTTP请求类型
					success:function(data){
						if(data.code == 201){
							mui.ajax('http://www.bjyilongxiang.com/Wcat/Wechat/payment',{
							data:{
								type:2,
								order_sn:order_sn,
								money:money,
								body:'微信支付',
								openId:openid,
							},
							dataType:'json',//服务器返回json格式数据
							type:'post',//HTTP请求类型
							success:function(data){
								if(data.code == 200){
						        	payment(data.data);
						        }else{
						        	mui('#popover2').popover('show');
									data1.innerHTML=data.msg;
						        }
							},
							error:function(xhr,type,errorThrown){
								//异常处理；
								console.log('失败发送-dingdan');
							}
						});	
						//购买积分
						}else if(data.code == 102){
							$(location).attr('href', '/Wcat/Users/integral_record');
						//购买月卡
						}else if(data.code == 101){
							$(location).attr('href', '/Wcat/Users/buy_month_card');
						}else if(data.code == 103){
							mui('#popover2').popover('show');
							data1.innerHTML=data.msg;
						}else{
							mui('#popover2').popover('show');
							data1.innerHTML="支付成功!";
							$(location).attr('href', '/Wcat/Charge/now_charge?order_sn='+order_sn);
						}
					},
					error:function(xhr,type,errorThrown){
						//异常处理；
						console.log('失败发送-dingdan');
					}
				});	
      		}); 
      	}
      	function payment(data){
			function jsApiCall()
		    {
		        WeixinJSBridge.invoke(
		            'getBrandWCPayRequest',
		            $.parseJSON(data),
		            function(res){
		                WeixinJSBridge.log(res.err_msg);
		                if(res.err_msg == "get_brand_wcpay_request:ok"){
		                	mui('#popover2').popover('show');
							data1.innerHTML="支付成功!";    
		                    $(location).attr('href', '/Wcat/Charge/now_charge?order_sn='+order_sn);  
		                }else if(res.err_msg == "get_brand_wcpay_request:cancel"){     
		                    mui('#popover2').popover('show');
							data1.innerHTML="用户取消支付";  
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
		}
    </script>