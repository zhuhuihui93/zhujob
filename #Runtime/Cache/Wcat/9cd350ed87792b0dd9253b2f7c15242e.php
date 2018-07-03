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
		 <div class="mui-slider">
		    <div class="mui-slider-indicator mui-segmented-control mui-segmented-control-inverted my-monthly-card-tit">
		        <a href="#item1" class="mui-control-item myTabItem mui-active">购买月卡</a>
		        <a href="#item2" class="mui-control-item myTabItem">月卡列表</a>
		    </div>
	        <div class="mui-slider-group">
	            <div id="item1" class="mui-slider-item mui-control-content card-list mui-active">
	            	
	                <ul class="mui-table-view Buy-monthly-card">
	                    <li class="mui-table-view-cell mui-clearfix">
	                    	<p class="mui-pull-left">支付方式</p>
	                    	<span class="mui-pull-left">：</span>
	                    	<span class="mui-pull-right">微信支付</span>
	                    </li>
	                    <li class="mui-table-view-cell mui-clearfix">
	                    	<p class="mui-pull-left">类型</p>
	                    	<span class="mui-pull-left">：</span>
	                    	<span class="mui-pull-right">月卡（45元/月）</span>
	                    	<input type="hidden" name="openId" value="<?php echo ($list['openId']); ?>">
	                    </li>
	                </ul>
	            	<div class="Buy-monthly-cont">
	            		<h6>月卡使用规则</h6>
	            		<p>1.月卡可用30次，单次充电时长小于8小时，自购买之日起45天内有效。</p>
	            	</div>
	            	<div class="Buy-monthly-btn">
	            		<button class="mui-btn mui-btn-outlined" id="buy_month_card">购买</button>
	            	</div>
	            	</form>
	            </div>
	            <div id="item2" class="mui-slider-item mui-control-content my-card-list card-list">
	            	<?php if(!empty($list['money']) ): ?><ul class="mui-table-view">	                	
                		<?php if(is_array($list["month"])): $i = 0; $__LIST__ = $list["month"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i; if($list["is_expire"] == '1' ): ?><li class="mui-table-view-cell card-shixiao">
		                    	<h6>聚E充月卡</h6>
		                    	<p>有效期:<span><?php echo date('Y/m/d',$list['create_time']);?></span>至<span><?php echo date('Y/m/d',$list['end_time']);?></span></p>
		                    </li>
		                    <?php else: ?>
		                     <li class="mui-table-view-cell">
		                    	<h6>聚E充月卡</h6>
		                    	<p>有效期:<span><?php echo date('Y/m/d',$list['create_time']);?></span>至<span><?php echo date('Y/m/d',$list['end_time']);?></span></p>
		                    </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	                </ul>
	                <?php else: ?>
                    	<div class="mui-content">
							<div class="monthly-card-no"></div>
							<p class="monthly-card-no-txt">您还没有月卡哦，前去<a href="<?php echo U('Users/buy_month_card');?>">购买</a></p>
						</div><?php endif; ?>
	            </div>
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
<script type="text/javascript">
	mui.init();
	window.onload=function(){
		var data1 = document.getElementById('data1');
  		var oIcon_closeempty2=document.getElementById('icon-closeempty2');
		oIcon_closeempty2.addEventListener('tap',function(){
	  		mui('#popover2').popover('hide');//弹出层关闭按钮
	  	});
	}
	/* 月卡 */
	$('#buy_month_card').click(function(){
		var money = 45;
		var type = 3;
		var body = '购买月卡';
		var openId =  $("input[name='openId']").val();
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