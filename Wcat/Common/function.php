<?php

	/** 
	 * 微信支付 
	 * @param  string   $openId     openid 
	 * @param  string   $goods      商品名称 
	 * @param  string   $attach     附加参数,我们可以选择传递一个参数,比如订单ID 
	 * @param  string   $order_sn   订单号 
	 * @param  string   $total_fee  金额 
	 */  
	function wxpay($openId,$body,$order_sn,$total_fee,$attach){ 
	    require_once APP_ROOT."/Wcat/wxpay/lib/WxPay.Api.php";  
	    require_once APP_ROOT."/Wcat/wxpay/payment/WxPay.JsApiPay.php";  
	    require_once APP_ROOT.'/Wcat/wxpay/payment/log.php'; 	    
	    //初始化日志  
	    $logHandler= new \CLogFileHandler(APP_ROOT."/Wcat/wxpay/logs/".date('Y-m-d').'.log');  
	    $log = \Log::Init($logHandler, 15);  
	    $tools = new \JsApiPay();  

	    if(empty($openId)) 
	    	$openId = $tools->GetOpenid();  	      
	    $input = new \WxPayUnifiedOrder();
		$input->SetBody($body);
		$input->SetAttach($attach);
		$input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
		$input->SetTotal_fee($total_fee);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag($body);
		$input->SetNotify_url("http://".$_SERVER['HTTP_HOST']."/Wcat/Wechat/actionNotifyurl");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = \WxPayApi::unifiedOrder($input);
		$jsApiParameters = $tools->GetJsApiParameters($order); 	      
	    $jsApiParameters = $tools->GetJsApiParameters($order);  
	    return $jsApiParameters;  
	}  

?>