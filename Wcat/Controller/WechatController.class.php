<?php
/*
* 微信支付接口
* request		: www.bjyilongxiang.com/Wcat/Wechat
* 参数说明
* order_sn  : 订单编号[必选];
* body      : 商品支付描述[可选]
*/
namespace Wcat\Controller;
use Think\Controller;
class WechatController extends Controller{
	/*s
     * body :支付描述 
     * type :支付场景 1，积分充值 ,2，余额支付，3，购买月卡
     * amount : 支付金额
     * order_sn : 订单号
     */ 
	public function payment(){ 
		require_once APP_ROOT."/Wcat/wxpay/lib/WxPay.Config.php";
	    $order_sn = I('order_sn');  
	    $money    = I('money');  
	    $type     = I('type');  
	    $body     = I('body');  
	    $openId   = I('openId');
	    if(!$type) $this->appReturn('fail',103,'支付场景不能为空！');
	    if(intval($type) == 2){
    		if(!$order_sn){
    			$this->appReturn('fail',103,'订单号不能为空！');
    		}else{
    			$order['order_sn'] = $order_sn;
    			$orders = M('orders')->where($order)->find();
    			if(empty($orders))$this->appReturn('fail',103,'订单号不能为空！');
    			if(intval($orders['order_status']) == 2)$this->appReturn('fail',103,'该订单已支付！');
    		}
    	}elseif(intval($type) == 3){
    		$map['user_id'] = $_SESSION['user_info']['id'];
    		$map['is_expire'] = 0;
    		$month = M('month_card')->field('is_expire')->where($map)->select();
    		if(!empty($month)){
    			$this->appReturn('fail',103,'您还有月卡没有失效哦~');
    		}else{
    			$order_sn = \WxPayConfig::MCHID.date("YmdHis");
    		}
    	}else{
    		$order_sn = \WxPayConfig::MCHID.date("YmdHis");
    	}
	    $jsApiParameters = $this->wxpayment($openId,$body,$order_sn,$money,$type);
	    $this->appReturn('success',200,'返回成功！',$jsApiParameters);
	}  

    //回调
    public function notifyurl()
    {   
        $xml = file_get_contents('php://input');  
	    $data = json_decode(json_encode(simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA)),true);    
	    $data_sign = $data['sign'];    
	    unset($data['sign']);  
	    $sign = $this->makeSign($data);   
	    if (($sign===$data_sign) && ($data['return_code']=='SUCCESS') && ($data['result_code']=='SUCCESS')) {
	    	$type      = $data['attach']; 
	        $order_sn  = $data['out_trade_no'];  
	        $openid    = $data['openid'];     
	        $total_fee = $data['total_fee']*0.01;
	        $user_info = M('users')->where(array('openid'=>array('eq',$openid)))->find();  
	    	if(intval($type) == 1){
	    		if(intval($total_fee) >= 100 && intval($total_fee) < 200){
	    			$integral = $total_fee*10+80;
	    		}elseif(intval($total_fee) >= 200){
	    			$integral = $total_fee*10+200;
	    		}else{
	    			$integral = $total_fee*10;
	    		}
				$info['user_id'] = $user_info['id'];
	        	$info['integral'] = $integral;
	        	$info['total_integral'] = $user_info['integral']+$integral;
	        	$info['recharge_type'] = 2;
	        	$info['record_status'] = 1;
	        	$info['pay_status'] = 1;
	        	$info['create_time'] = time();
	        	$recharge =  M('recharge')->add($info); 
	        	if($recharge){
	        		$users['integral'] = $info['total_integral'];
	        		M('users')->where(array('openid'=>array('eq',$openid)))->save($users);
	        		$wxmsg = D('Wxmsg');
			        $data2=array(
			            'first'=>array('value'=>urlencode("您的积分账户变更如下"),'color'=>'#00CD00'),
			            'FieldName'=>array('value'=>urlencode('变更时间')),
			            'Account'=>array('value'=>urlencode(date('Y-m-d H:i:s',time()))),
			            'change'=>array('value'=>urlencode('增加')),
			            'CreditChange'=>array('value'=>urlencode($integral)),
			            'CreditTotal'=>array('value'=>urlencode($info['total_integral'])),
			            'Remark'=>array('value'=>urlencode('欢迎您的使用，1元=10积分。')),
			        );
			        $wxmsgs2 = $wxmsg->doSend($user_info['openid'],'l48nzhiFe8wLX2OD0VnX_s-wul0R7ZEyjXwhRAekONA','',$data2);
			        echo "";
	        	}    	
	        }elseif(intval($type) == 2){
	        	$data    =    [
	                'order_status' => 2,
	                'pay_code'     => $order_sn,
	                'pay_time'     => time(),
	                'pay_type'     => 3,
	                'money'        => $total_fee
	            ];   
	            $map['order_sn'] = $order_sn;
	            $orders = M('orders')->where($map)->save($data);
	            if($orders){
	            	$order_info = M('orders')->field('charge_id,code_id')->where($map)->find();
	            	$code_id['id'] = $order_info['code_id'];
	            	$arr['charge_status'] = 1;
	            	$charge_code = M('charge_code')->where($code_id)->save($arr);
	            	if($charge_code){
	            		$map2['id'] = $order_info['charge_id'];
		            	$chargeInfo = M('charge')->field('charging_code')->where($map2)->find();
		            	$codeInfo = M('charge_code')->field('number')->where($code_id)->find();
		            	$charge = D('charge')->open_charge($chargeInfo['charging_code'],$codeInfo['number'],$order_sn);
	            	}
	            }
	        }elseif(intval($type) == 3){
	        	$end_time = strtotime("+1 months", time());
	        	$info['user_id'] = $user_info['id'];
	        	$info['charge_number'] = 30;
	        	$info['surplus_number'] = 30;
	        	$info['month_card_money'] = $total_fee;
	        	$info['create_time'] = time();
	        	$info['end_time'] = $end_time;
	        	M('month_card')->add($info);    
	        } 	         
	    }
	    echo exit('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>');    	 
    }
    /** 
	* 生成签名 
	* @return 签名，本函数不覆盖sign成员变量 
	*/  
	protected function makeSign($data){   
	    require_once APP_ROOT."/Wcat/wxpay/lib/WxPay.Api.php";  
	    $key = \WxPayConfig::KEY;    
	    $data=array_filter($data);    
	    ksort($data);  
	    $string_a=http_build_query($data);  
	    $string_a=urldecode($string_a);   
	    $string_sign_temp=$string_a."&key=".$key;  
	    $sign = md5($string_sign_temp);    
	    $result=strtoupper($sign);  
	    return $result;  
	} 
	function wxpayment($openId,$body,$order_sn,$total_fee,$attach){
		require_once APP_ROOT."/Wcat/wxpay/lib/WxPay.Api.php";
		require_once APP_ROOT."/Wcat/wxpay/payment/WxPay.JsApiPay.php";
		require_once APP_ROOT.'/Wcat/wxpay/payment/log.php';
		$logHandler= new \CLogFileHandler("../logs/".date('Y-m-d').'.log');
		$log = \Log::Init($logHandler, 15); 
		$tools = new \JsApiPay();
		// $openId = $tools->GetOpenid();
		$input = new \WxPayUnifiedOrder();
		$input->SetBody($body);
		$input->SetAttach($attach);
		$input->SetOut_trade_no($order_sn);
		$input->SetTotal_fee($total_fee*100);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag($body);
		$input->SetNotify_url("http://".$_SERVER['HTTP_HOST']."/Wcat/Wechat/notifyurl");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$order = \WxPayApi::unifiedOrder($input);
		$jsApiParameters = $tools->GetJsApiParameters($order);
		return $jsApiParameters;
	}
	function appReturn($status,$code,$msg,$result,$pageCode){
	    if(!$status) $status = "fail";
        if(!$code)   $code   = "400";
        if(!$msg)    $msg    = "失败";
        if(empty($result))  $result=array();
        if(empty($pageCode))  $pageCode=array();
        $data = array(
            'status' => $status,
            'code'   => $code,
            'msg'    => $msg,
            'data'   => $result,
            'pageCode'   => $pageCode,
            'operateTime' => time()
        );
	    header('Content-Type:application/json; charset=utf-8');
	    $callback=I('callback');
	    if($callback || $_REQUEST['deviceType']==3){
	    	exit($callback.'('.json_encode($data).')');  
	    }
	    exit(json_encode($data));
	} 
}

?>