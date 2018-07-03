<?php

namespace Wcat\Controller;
use Think\Controller;
 
class JssdkController extends Controller{
    protected $opid    = "201805242301064940";  
    protected $opidkey = "8C210C1AEA8DEE08"; 
    //硬件回调地址
    public function makeSign(){
        $postObj = file_get_contents('php://input');
        // file_put_contents('log2.txt',$postObj,FILE_APPEND);
        $result = array();
        foreach (explode('&', $postObj) as $val){
            list($a,$b) = explode('=', $val);
            $result[$a] = $b;
        }
        //获取功率
        if($result['action'] == "numinfo"){
            $info = json_decode($result['data']);
            $number = $info->number;
            $code = $info->opennum;
            $values = $info->values;
            $power['power'] = $values->power;
            $charge_id = M('charge')->field('id')->where(array('charging_code'=>array('eq',$number)))->find();
            $arr['charge_id'] = $charge_id['id'];
            $arr['number'] = $code;
            M('charge_code')->where($arr)->save($power);
        }
        //上线
        if($result['action'] == "online"){
            $info = json_decode($result['data']);
            $number = $info->number;
            $where['charging_code'] = $number;
            $map['status'] = 1;
            $charge = M('charge')->where($where)->save($map);
        }
        //下线
        if($result['action'] == "unline"){
            $info = json_decode($result['data']);
            $number = $info->number;
            $where['charging_code'] = $number;
            $map['status'] = 0;
            $map['drop_time'] = time();
            $charge = M('charge')->where($where)->save($map);
            if($charge){
                $chargeId = M('charge')->where(array('charging_code'=>array('eq',$number)))->find();
                $res['order_status'] = 2;
                $res['charge_id'] = $chargeId['id'];
                $orderInfo = M('orders')->where($res)->select();
                foreach ($orderInfo as $key => $val) {
                    $arrs['order_status'] = 3;
                    $arrs['end_time'] = time();
                    $arrs['charge_status'] = 5; //故障断电
                    $saOrders = M('orders')->where(array('order_sn'=>array('eq',$val['order_sn'])))->save($arrs);
                    if($saOrders){
                        $code_status['charge_status'] = 0;
                        $code_status['power'] = 0;
                        $code1['id'] = $val['code_id'];
                        M('charge_code')->where($code1)->save($code_status);
                    }
                }
            }
        }
        //关闭
        if($result['action'] == "close"){
            $info = json_decode($result['data']);
            $number = $info->number;
            $opennum = $info->opennum;
            $charge1 = M('charge')->field('id,charging_name')->where(array('charging_code'=>array('eq',$number)))->find();
            $map['charge_id'] = $charge1['id'];
            $map['number'] = $opennum;
            $code2 = M('charge_code')->field('id')->where($map)->find();
            $map1['order_status'] = 2;
            $map1['charge_status'] = 1;
            $map1['charge_id'] = $charge1['id'];
            $map1['code_id'] = $code2['id'];
            $field = 'order_sn,charge_time,user_id,pay_type,money,integral,start_time,code_id';
            $orders = M('orders')->field($field)->where($map1)->find();
            if(!empty($orders)){
                $msg = $info->msg;
                if($msg == "充满"){
                    $data['charge_status'] = 3;
                    $msgs = "此次充电已充满";
                }elseif($msg == "空载"){
                    $data['charge_status'] = 4;
                    $msgs = "电源连接有误，请检查！";
                }elseif($msg == "过载"){
                    $data['charge_status'] = 5;
                    $msgs = "您的电池功率过载，暂不支持充电！";
                }
                if(!empty($data['charge_status'])){
                    $data['end_time'] = time();
                    $data['order_status'] = 3;
                    $where['order_sn'] = $orders['order_sn'];
                    $order_info = M('orders')->where($where)->save($data);
                    if($order_info){
                        $status['charge_status'] = 0;
                        $status['power'] = 0;
                        $where1['id'] = $orders['code_id'];
                        M('charge_code')->where($where1)->save($status);
                        $wxmsg = D('Wxmsg');
                        $data1=array(
                            'first'=>array('value'=>urlencode("本次充电失败。"),'color'=>'#EE5C42'),
                            'keyword1'=>array('value'=>urlencode($charge1['charging_name'])),
                            'keyword2'=>array('value'=>urlencode(date('Y-m-d H:i:s',time()))),
                            'keyword3'=>array('value'=>urlencode($msgs)),
                            'remark'=>array('value'=>urlencode('对于此次的充电失败，我们非常抱歉，请重试或者联系客服。')),
                        );
                        $usId1['id'] = $orders['user_id'];
                        $sOpid1 = M('users')->field('openid')->where($usId1)->find();
                        $wxmsgs1 = $wxmsg->doSend($sOpid1['openid'],'86asK-Fw-n1DhFqxPH8_HgL_lSYqHxX66M5qzROVjWI','',$data1);
                        $read['read'] = 1;
                        M('orders')->where(array('order_sn'=>array('eq',$value['order_sn'])))->save($read);
                        echo "";
                    }
                }
            }
        }

    }
    public function choose_time(){
        require_once APP_ROOT."/Wcat/wxpay/lib/WxPay.Api.php";
        require_once APP_ROOT."/Wcat/wxpay/payment/WxPay.JsApiPay.php";
        $tools = new \JsApiPay();
        $charge['openid'] = $tools->GetOpenid();
        $charge_id['id']   = I('charge_id');
        $charge['code_id'] = I('code_id');
        $openid['openid']  = $charge['openid'];
        $charge['users']  = M('users')->where($charge)->find();
        $wechat_config = C('wechat_config');
        $appid  = $wechat_config['appid']; 
        $secret = $wechat_config['secret'];   
        $token = $this->AcessToken($appid,$secret);
        $get_user_info_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$charge['openid'].'&lang=zh_CN';
        $userinfo = $this->getJson($get_user_info_url);
        if(!empty($charge['users'])){
            $data['user_name'] = json_encode($userinfo['nickname']);
            $data['sex']       = $userinfo['sex'];
            $data['province']  = $userinfo['province'];
            $data['city']      = $userinfo['city'];
            $data['country']   = $userinfo['country'];
            $data['user_img']  = $userinfo['headimgurl'];
            $data['subscribe'] = 1;
            $data['edit_time'] = time();
            $data['user_type'] = 1;
            M('users')->where(array('openid'=>array('eq',$userinfo['openid'])))->save($data);
            $users = M('users')->where(array('openid'=>array('eq',$userinfo['openid'])))->find();
            session('user_info',$users);
        }else{
            $data['openid']    = $userinfo['openid'];
            $data['user_name'] = json_encode($userinfo['nickname']);
            $data['sex']       = $userinfo['sex'];
            $data['province']  = $userinfo['province'];
            $data['city']      = $userinfo['city'];
            $data['country']   = $userinfo['country'];
            $data['user_img']  = $userinfo['headimgurl'];
            $data['subscribe'] = 1;
            $data['creation_time'] = time();
            $data['user_type'] = 1;
            M('users')->add($data);
            $users = M('users')->where(array('openid'=>array('eq',$userinfo['openid'])))->find();
            session('user_info',$users);  
        }
        $charge['charge'] = M('charge')->field('id,charging_name,is_free')->where($charge_id)->find();
        $map['id'] = $charge['code_id'];
        $charge['code']  = M('charge_code')->field('power,number')->where($map)->find();
        $ids['c.id'] = $charge_id['id'];
        $field = "p.power,p.times,p.money";
        $join = "LEFT join charge as c on p.adminid = c.admin_id";
        $power_info = M('power_info')->alias('p')->field($field)->join($join)->where($ids)->select();
        if(empty($power_info)){
            $power = 0;
        }
        foreach ($power_info as $key => $value) {
            if($value['power'] == 1 && $value['times'] == 1){
                $power['time1'] = $value['money'];
            }
            if($value['power'] == 1 && $value['times'] == 2){
                $power['time2'] = $value['money'];
            }
            if($value['power'] == 1 && $value['times'] == 3){
                $power['time3'] = $value['money'];
            }
            if($value['power'] == 1 && $value['times'] == 4){
                $power['time4'] = $value['money'];
            }
            if($value['power'] == 2 && $value['times'] == 1){
                $power['time5'] = $value['money'];
            }
            if($value['power'] == 2 && $value['times'] == 2){
                $power['time6'] = $value['money'];
            }
            if($value['power'] == 2 && $value['times'] == 3){
                $power['time7'] = $value['money'];
            }
            if($value['power'] == 2 && $value['times'] == 4){
                $power['time8'] = $value['money'];
            }
            if($value['power'] == 3 && $value['times'] == 1){
                $power['time9'] = $value['money'];
            }
            if($value['power'] == 3 && $value['times'] == 2){
                $power['time10'] = $value['money'];
            }
            if($value['power'] == 3 && $value['times'] == 3){
                $power['time11'] = $value['money'];
            }
            if($value['power'] == 3 && $value['times'] == 4){
                $power['time12'] = $value['money'];
            }
        } 
        $this->assign('power',$power);
        $this->assign('charge',$charge);
        $this->assign('name','扫码充电');
        $this->display(T('Charge/Scavenging-charging'));
    }
    public function vip_choose_time(){
        require_once APP_ROOT."/Wcat/wxpay/lib/WxPay.Api.php";
        require_once APP_ROOT."/Wcat/wxpay/payment/WxPay.JsApiPay.php";
        $tools = new \JsApiPay();
        $charge['openid'] = $tools->GetOpenid();
        $charge_id['id']   = I('charge_id');
        $charge['code_id'] = I('code_id');
        $openid['openid']  = $charge['openid'];
        $charge['users']  = M('users')->where($charge)->find();
        $wechat_config = C('wechat_config');
        $appid  = $wechat_config['appid']; 
        $secret = $wechat_config['secret'];   
        $token = $this->AcessToken($appid,$secret);
        $get_user_info_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$charge['openid'].'&lang=zh_CN';
        $userinfo = $this->getJson($get_user_info_url);
        if(!empty($charge['users'])){
            $data['user_name'] = json_encode($userinfo['nickname']);
            $data['sex']       = $userinfo['sex'];
            $data['province']  = $userinfo['province'];
            $data['city']      = $userinfo['city'];
            $data['country']   = $userinfo['country'];
            $data['user_img']  = $userinfo['headimgurl'];
            $data['subscribe'] = 1;
            $data['edit_time'] = time();
            $data['user_type'] = 1;
            M('users')->where(array('openid'=>array('eq',$userinfo['openid'])))->save($data);
            $users = M('users')->where(array('openid'=>array('eq',$userinfo['openid'])))->find();
            session('user_info',$users);
        }else{
            $data['openid']    = $userinfo['openid'];
            $data['user_name'] = json_encode($userinfo['nickname']);
            $data['sex']       = $userinfo['sex'];
            $data['province']  = $userinfo['province'];
            $data['city']      = $userinfo['city'];
            $data['country']   = $userinfo['country'];
            $data['user_img']  = $userinfo['headimgurl'];
            $data['subscribe'] = 1;
            $data['creation_time'] = time();
            $data['user_type'] = 1;
            M('users')->add($data);
            $users = M('users')->where(array('openid'=>array('eq',$userinfo['openid'])))->find();
            session('user_info',$users);  
        }
        $charge['charge'] = M('charge')->field('id,charging_name,is_free')->where($charge_id)->find();
        $map['id'] = $charge['code_id'];
        $charge['code']  = M('charge_code')->field('power,number')->where($map)->find();
        $this->assign('charge',$charge);
        $this->assign('name','扫码充电');
        $this->display(T('Charge/saveTimes'));
    }
    //获取用户支付类型的信息
    public function users_account()
    {
        $keyword = I('type');
        $user_id['id'] = $_SESSION['user_info']['id'];
        //积分
        if(!empty(intval($keyword) == 1)){          
            $users = M('users')->field('integral')->where($user_id)->find();
            if(intval($users['integral']) > 0){
                $users['type'] = $keyword;
                $this->appReturn('success',200,'返回成功',$users);
            }else{
                $data = $keyword;
                $this->appReturn('fail',103,'积分不足，请充值哦！',$data);
            }
        }elseif(!empty(intval($keyword) == 2)){
            $where['user_id'] = $_SESSION['user_info']['id'];
            $month = M('month_card')->field('charge_number,surplus_number,is_expire')->where($where)->find();
            if(empty($month)) $this->appReturn('fail',103,'您还没有月卡，请购买！');
            if(intval($month['is_expire']) == 0){
                if(intval($month['surplus_number']) > 0) {
                    $month['type'] = $keyword;
                    $this->appReturn('success',200,'返回成功',$month);
                }else{
                    $data = $keyword;
                    $this->appReturn('fail',103,'月卡次数不足够支付！',$data);
                }
            }else{
                $data = $keyword;
                $this->appReturn('fail',103,'月卡已过期，去购买！',$data);
            }
        }elseif(!empty(intval($keyword) == 3)){
            $openId['type'] = $keyword;
            $this->appReturn('success',200,'返回成功',$openId);
        }else{
            $this->appReturn('fail',103,'请至少传一个支付方式！');
        }
    }
    //订单支付
    public function order_payment(){
        $order_sn['order_sn'] = I('order_sn');
        $user_id = I('user_id');
        $type = I('type');
        if(!$order_sn) $this->appReturn('fail',103,'订单号不能为空！');
        $order_info = M('orders')->field('charge_id,code_id,order_status')->where($order_sn)->find();
        if(intval($order_info['order_status']) == 2)
            $this->appReturn('fail',103,'该订单已支付完成！');
        $charges = M('charge')->field('charging_code')->where(array('id'=>array('eq',$order_info['charge_id'])))->find();
        $code_id['id'] = $order_info['code_id'];
        $code_infos = M('charge_code')->field('number')->where($code_id)->find();
        //积分
        if(intval($type) == 1){
            $model =M('charge_code');
            $model->startTrans();
            $integral = I('integral')*10;
            if(!$integral) $this->appReturn('fail',103,'支付积分不能为空！');
            $where['id'] = $user_id;
            $users = M('users')->field('integral')->where($where)->find();
            if($users['integral'] < $integral){
                $this->appReturn('fail',102,'您账户积分不足支付此充电时长！');
            }elseif(intval($users['integral']) == 0){
                $this->appReturn('fail',102,'积分不足，请充值哦！');
            }else{
                $data['pay_type'] = 2;
                $data['order_status'] = 2;
                $data['integral'] = $integral;
                $data['pay_time'] = time();
                $data['charge_status'] = 1;
                $orders = M('orders')->where($order_sn)->save($data);
                if($orders){
                    $map['integral'] = $users['integral']-$integral;
                    $arr['charge_status'] = 1;
                    M('users')->where($where)->save($map);
                    $model->where($code_id)->save($arr);
                    $model->commit();
                    $charge = D('charge')->open_charge($charges['charging_code'],$code_infos['number'],$order_sn['order_sn']);
                    $this->appReturn('success',200,'订单支付成功！');
                }else{
                    $model->rollback();
                    $this->appReturn('fail',103,'订单提交失败！');
                }
            }
        }elseif(intval($type) == 2){
            $model = M('charge_code');
            $model->startTrans();
            $map1['user_id'] = $user_id;
            $month = M('month_card')->field('is_expire,surplus_number')->where($map1)->find();
            if(empty($month)) $this->appReturn('fail',101,'您还没有月卡，请购买月卡！');
            if(intval($month['is_expire'] == 1)){
                $this->appReturn('fail',101,'您的月卡已过期，请购买新月卡！');
            }else{
                $data['pay_type'] = 1;
                $data['order_status'] = 2;
                $data['pay_time'] = time();
                $data['charge_status'] = 1;
                $orders = M('orders')->where($order_sn)->save($data);
                if($orders){
                    $card['surplus_number'] = $month['surplus_number']-1;
                    $arr['charge_status'] = 1;
                    M('month_card')->where($map1)->save($card);
                    $model->where($code_id)->save($arr);
                    $model->commit();
                    $charge = D('charge')->open_charge($charges['charging_code'],$code_infos['number'],$order_sn['order_sn']);
                    $this->appReturn('success',200,'订单支付成功！');
                }else{
                    $model->rollback();
                    $this->appReturn('fail',103,'订单提交失败！');
                }
            }
        }elseif(intval($type) == 3){
            $this->appReturn('success',201,'微信支付');
        }else{
            $this->appReturn('fail',103,'请至少传一个支付方式！');
        }
    }
        
    //下订单
    public function orders(){
        $power = I('power');
        $code_id['id']  = I('code_id');
        $is_free = I('is_free');
        if(intval($power >= 1000)) $this->appReturn('fail',103,'由于您的功率过载，暂不支持此设备充电！');
        if(intval($power <= 1)) $this->appReturn('fail',103,'暂未获取到您的功率，请重新扫码！');
        $charge_code = M('charge_code')->field('charge_status,number')->where($code_id)->find();
        if(intval($charge_code['charge_status']) == 1) 
            $this->appReturn('fail',103,'此充电口正在充电中！');
        $orders['user_id'] = $_SESSION['user_info']['id'];
        $orders['charge_id']  = I('charge_id');
        $orders['code_id'] = $code_id['id'];
        $orders['power']   = I('power');
        $orders['charge_time'] = I('charge_time');
        $orders['order_sn'] = (time()-strtotime('2016-01-01')).randomVerificationCode(3,1);
        $orders['user_name'] = $_SESSION['user_info']['user_name'];
        $orders['phone'] = $_SESSION['user_info']['phone'];
        $orders['create_time'] = time();
        if(intval($is_free) == 1){
            $orders['pay_type'] = 4;
            $orders['order_status'] = 2;  
            $orders['charge_status'] = 1;
            $add_order = M('orders')->add($orders);
            if($add_order){
                $whers['id'] = $orders['charge_id'];
                $ch_ids = M('charge')->field('charging_code')->where($whers)->find();
                $order_sn = M('orders')->field('order_sn')->order('id desc')->limit(1)->find();
                $charge = D('charge')->open_charge($ch_ids['charging_code'],$charge_code['number'],$order_sn['order_sn']);
                $this->appReturn('success',200,'订单支付成功！',$order_sn);
            }
        }else{
            $orders['order_status'] = 1;            
            M('orders')->add($orders);
            $order_sn = M('orders')->field('order_sn')->order('id desc')->limit(1)->find();
            $order_sn['user_id'] = $_SESSION['user_info']['id'];
            $this->appReturn('success',200,'返回成功！',$order_sn);
        }
    }
    //返回价格
    public function getPower(){
        $maps['c.id'] = I('charge_id');
        $powers = intval(I('powers'));
        $times = intval(I('times'));
        if($times == 4){
            $times = 3;
        }elseif($times == 8){
            $times = 4;
        }
        $maps['p.times'] = $times;
        if($powers <= 200){
            $maps['p.power'] = 1;
            if($times == 1){
                $money['money'] = 0.5;
            }elseif($times == 2){
                $money['money'] = 1;
            }elseif($times == 3){
                $money['money'] = 1.5;
            }elseif($times == 4){
                $money['money'] = 1.8;
            }
        }elseif($powers > 200 && $powers <= 500){
            $maps['p.power'] = 2;
            if($times == 1){
                $money['money'] = 0.8;
            }elseif($times == 2){
                $money['money'] = 1.5;
            }elseif($times == 3){
                $money['money'] = 2.4;
            }elseif($times == 4){
                $money['money'] = 3;
            }
        }elseif($powers > 500 && $powers <= 1000){
            $maps['p.power'] = 3;
            if($times == 1){
                $money['money'] = 1.5;
            }elseif($times == 2){
                $money['money'] = 3;
            }elseif($times == 3){
                $money['money'] = 4;
            }elseif($times == 4){
                $money['money'] = 10;
            }
        }
        $join = "LEFT JOIN charge as c on p.adminid = c.admin_id";
        $charges = M('power_info')->alias('p')->field("p.money")->join($join)->where($maps)->find();
        if(empty($charges)){
            $this->appReturn('success',200,'返回成功！',$money);
        }else{
            $this->appReturn('success',200,'返回成功！',$charges);
        }
    }
    //更新订单||订单已支付
    public function orders_status(){  
        $orders = M('orders')->select();   
        $timeout['order_status'] = 2;
        $orders1 = M('orders')->where($timeout)->select();  
        if(!empty($orders1)){
            foreach ($orders1 as $key => $value) {
                $end_time = intval($value['start_time']+($value['charge_time']*60*60));
                //更新订单
                if(time() >= $end_time){
                    $vals['id'] = $value['charge_id'];
                    $charges2 = M('charge')->where($vals)->find();
                    $vals1['id'] = $value['code_id'];
                    $codes = M('charge_code')->where($vals1)->find();
                    $close = D('charge')->close_charge($charges2['charging_code'],$codes['number'],2,$value['order_sn']);
                    $logs['order_sn'] = $value['order_sn'];
                    $logs['order_status'] = 3;
                    $logs['status'] = 1;
                    $logs['create_time'] = time();
                    $logs['remark'] = '更新订单状态';
                    M('orders_log')->add($logs); 
                //订单已支付给用户推送消息   
                }else{
                    if(intval($value['read']) == 0){
                        if(empty($value['integral'])){
                            $mun = $value['money'].'元';
                        }else{
                            $mun = $value['integral'].'积分';
                        }
                        $wxmsg = D('Wxmsg');
                        $data2=array(
                            'first'=>array('value'=>urlencode("您好！您的订单支付成功。"),'color'=>'#00CD00'),
                            'keyword1'=>array('value'=>urlencode($value['order_sn'])),
                            'keyword2'=>array('value'=>urlencode(date('Y-m-d H:i:s',$value['start_time']))),
                            'keyword3'=>array('value'=>urlencode($mun)),
                            'remark'=>array('value'=>urlencode('如有问题，请联系我们。')),
                        );
                        $usIds['id'] = $value['user_id'];
                        $sOpids = M('users')->field('openid')->where($usIds)->find();
                        $wxmsgs2 = $wxmsg->doSend($sOpids['openid'],'aR5Fdif1KfW-TfYKYEG7NG4GhMG8QZ0KEaqsXTexsQo','',$data2);
                        $read['read'] = 1;
                        M('orders')->where(array('order_sn'=>array('eq',$value['order_sn'])))->save($read);
                        echo "";
                    }
                }
            }
        }
    }
    public function index(){
        var_dump(111);
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
    /**
     * 将数据格式化成树形结构
     */
    function getTree($tree, $rootId = 0) 
    {  
        $return = array();  
        foreach($tree as $leaf) 
        {  
            if($leaf['parent_id'] == $rootId) 
            {  
                foreach($tree as $subleaf) 
                {  
                    if($subleaf['parent_id'] == $leaf['id']) 
                    {  
                        $leaf['children'] = $this->getTree($tree, $leaf['id']);  
                        break;  
                    }  
                }  
                $return[] = $leaf;  
            }  
        }  
        return $return;  
    }
    function AcessToken($appid,$secret)  
    { 
        if (!empty($_SESSION['access_token']) && $_SESSION['alive']>time()) {
            $result['access_token']=$_SESSION['access_token'];  
        }else{
            $Tokenurl='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;         
            $result = $this->getJson($Tokenurl);   
            $_SESSION['access_token'] = $result['access_token'];  
            $_SESSION['alive'] = time()+7000;  
        }  
        return $result['access_token'];  
    } 
    function getJson($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }
}
?>
