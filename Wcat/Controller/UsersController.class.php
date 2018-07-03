<?php
namespace Wcat\Controller;
use Think\Controller;
//用户中心
class UsersController extends PublicController {

	public function users(){
		$user_info = $this->user_info;
        if(!$user_info['id']){
            $this->redirect('Index/index');
        }
        $where['id'] = $user_info['id'];
        $data = M('users')->where($where)->find();
        session('user_info',$data);
        $data['user_name'] = json_decode($data['user_name']);
        $users_id['id'] = $user_info['id'];
        $users = M('users')->field('latitude,longitude')->where($users_id)->find();
        $field = "id,charging_name,latitude,longitude,address";
        $charge = M('charge')->field($field)->select();
        if($users['latitude'] && $users['longitude']){
            $result = $this->range($users['latitude'],$users['longitude'],$charge);
        }else{
            $result = $charge;
        }
        //附近充电桩
        foreach ($result as $key => $val) {    
            if(!empty($val['km'])){
                if($val['km'] <= 8){
                    $wheres['charge_id'] = $val['id'];
                    $wheres['charge_status'] = 0;
                    $charge_code = M('charge_code')->where($wheres)->count();
                    $val['number'] = $charge_code;
                    $arr[] = $val;
                }
            }                
        }
        //删除超时未支付的订单
        $order['user_id'] = $user_info['id'];
        $order['order_status'] = 1;
        $orders = M('orders')->field('order_sn,create_time')->where($order)->select();
        if(!empty($orders)){
            foreach ($orders as $key => $value) {
                $times = intval($value['create_time']);
                $out_time = $times+15*60;
                if($out_time <= time()){
                    $map['order_sn'] = $value['order_sn'];
                    M('orders')->where($map)->delete();
                }
            }
        }
        $this->assign('info',$arr);
        $this->assign('users',$data); 
        $this->assign('name','个人中心');
        $this->display(T('Users/index'));
	}
	public function users_info()
	{
		$user_info = $this->user_info;
		if(!$user_info['id']){
            $this->redirect('Index/index');
        } 
        $user_info['user_name'] = json_decode($user_info['user_name']);
        $this->assign('users',$user_info);
        $this->assign('name','个人资料'); 
        $this->display(T('Users/personal-data'));
	}
	public function binding_phone()
	{   
		$user_info = $this->user_info;
		if(!$user_info['id']){
            $this->redirect('Index/index');
        }
        if(IS_POST){
			$user_id['id'] = $user_info['id'];
	        $mphone['phone'] = I('phone');
	        $verifyCode = I('sms_code');
	        $pre_phone = preg_match("/^1\d{10}$/", $mphone['phone']);
	        if (!$pre_phone) $this->appReturn('fail',103,'填写手机号不正确！');
	        $phone = M('users')->where($mphone)->find();
	        $sms_log = M('sms_log')->where($mphone)->order('id desc')->find();
	        if($sms_log['code']!=$verifyCode) $this->appReturn('fail',103,'输入验证码不正确！');
       		if($sms_log['effective_time'] < time())  $this->appReturn('fail',103,'验证码已过期，请重新获取！');
	        if(!empty($phone)) {
	        	$this->appReturn('fail',103,'该手机号已存在！');
	        }else{
	        	M('users')->where($user_id)->save($mphone);
	        	$this->appReturn('success',200,'修改成功！');
	        }
        }else{
        	$this->assign('name','绑定手机');
			$this->display(T('Users/Binding-cell-phone-number'));
        }  
	}
	
	public function getLocation(){
		$wechat_config = C('wechat_config');
        $appid  = $wechat_config['appid']; 
        $secret = $wechat_config['secret'];  
	    $jssdk = new \Think\Location($appid, $secret);
	    $signPackage = $jssdk->GetSignPackage();
	    $this->assign('signPackage',$signPackage);
		$this->display(T('Users/getLocation'));
	}
	public function month_card()
	{ 
		require_once APP_ROOT."/Wcat/wxpay/lib/WxPay.Api.php";
		require_once APP_ROOT."/Wcat/wxpay/payment/WxPay.JsApiPay.php";
		$tools = new \JsApiPay();
		if(!empty($_SESSION['user_info']['openid'])){
            $month['openId'] = $_SESSION['user_info']['openid'];
        }else{
            $month['openId'] = $tools->GetOpenid();
        }
		$users['openid'] = $month['openId'];
		$user_info = M('users')->where($users)->find();
		session('user_info',$user_info);
        $map['user_id'] = $user_info['id'];
        $map['delmark'] = 0;
        $field = "id,user_id,create_time,end_time,is_expire,surplus_number";
        $month['month'] = M('month_card')->field($field)->where($map)->order('id desc')->select();
        $this->assign('list' ,$month);
		$this->assign('name','我的月卡');
		$this->display(T('Users/month'));
	}
	public function buy_month_card()
    {
        require_once APP_ROOT."/Wcat/wxpay/lib/WxPay.Api.php";
        require_once APP_ROOT."/Wcat/wxpay/payment/WxPay.JsApiPay.php";
        $tools = new \JsApiPay();
        if(!empty($_SESSION['user_info']['openid'])){
            $month['openId'] = $_SESSION['user_info']['openid'];
        }else{
            $month['openId'] = $tools->GetOpenid();
        }
        $users['openid'] = $month['openId'];
        $user_info = M('users')->where($users)->find();
        session('user_info',$user_info);
        $map['user_id'] = $user_info['id'];
        $map['delmark'] = 0;
        $field = "id,user_id,create_time,end_time,is_expire,surplus_number";
        $month['month'] = M('month_card')->field($field)->where($map)->order('id desc')->select();
        $this->assign('list' ,$month);
        $this->assign('name','购买月卡');
        $this->display(T('Users/Monthly-card'));
    }
    public function integral_record()
    {
        require_once APP_ROOT."/Wcat/wxpay/lib/WxPay.Api.php";
        require_once APP_ROOT."/Wcat/wxpay/payment/WxPay.JsApiPay.php";
        $tools = new \JsApiPay();
        if(!empty($_SESSION['user_info']['openid'])){
            $month['openId'] = $_SESSION['user_info']['openid'];
        }else{
            $month['openId'] = $tools->GetOpenid();
        }
        $users['openid'] = $month['openId'];
        $user_info = M('users')->where($users)->find();
        session('user_info',$user_info);
        $users['integral'] = $user_info['integral'];
        $this->assign('users',$users);
        $this->assign('name','积分充值');
        $this->display(T('Charge/integral-recharge'));
    }
}

?>