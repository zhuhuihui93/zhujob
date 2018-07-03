<?php

namespace Wcat\Controller;
use Think\Controller;
define("TOKEN", "bjxtlkj201805081");
define("APPID", "wx57939dd3f5293448");
define("APPSECRET", "90967ce98d888d0a42818c5d884c036f");
header('content-type:text');
//微信修改基本配置
class WcatController extends Controller {
    private $opid    = "201805242301064940";  
    private $opidkey = "8C210C1AEA8DEE08"; 
    public function index()
    {
        if(!isset($_GET['echostr'])){
            $this->responseMsg();
        }else{
            $this->valid();
        }
    }
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce     = $_GET["nonce"];
        $token  = "bjxtlkj201805081";
        $tmpArr = array(
            $token,
            $timestamp,
            $nonce
        );
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
    //执行接收器方法 
    public function responseMsg()
    { 
        $postStr = file_get_contents('php://input'); 
        file_put_contents('log2.txt',$postStr,FILE_APPEND);
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data = json_decode(json_encode($postObj),true);
        $logs['from_user_name'] = $data['FromUserName'];
        $logs['create_time'] = $data['CreateTime'];
        // $logs['event'] = $data['Event'];
        $wechat_log = M('wechat_log')->field('from_user_name,create_time')->where($logs)->find();
        if(!empty($wechat_log)){
            echo " "; 
            if (!empty($postObj)){              
                $RX_TYPE = $postObj->MsgType;      
                switch($RX_TYPE){ 
                    case "event":
                        $result = $this->receiveEvent($postObj); 
                        break; 
                } 
                echo $result;             
            }else{ 
                echo ""; 
                exit; 
            } 
        }else{
            $users['from_user_name'] = $data['FromUserName'];
            $users['create_time'] = $data['CreateTime'];
            // $users['event'] = $data['Event'];
            M('wechat_log')->add($users);
            if (!empty($postObj)){              
                $RX_TYPE = $postObj->MsgType;      
                switch($RX_TYPE){ 
                    case "event":
                        $result = $this->receiveEvent($postObj); 
                        break; 
                } 
                echo $result;             
            }else{ 
                echo ""; 
                exit; 
            } 
        }
    } 
    private function receiveEvent($object)
    {
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                $resultStr = "欢迎您的关注。使用步骤：1，连接电源 2，扫码预约 3，选择时长并支付";
                $contentStr = $this->responseText($object, $resultStr); 
                $data = json_decode(json_encode($object),true);
                $openId['openid'] = $data['FromUserName'];
                $map['subscribe'] = 1;
                $users = M('users')->where($openId)->find();
                if(!empty($users)){
                    M('users')->where($openId)->save($map);
                }
                break;
            case "unsubscribe":
                $contentStr = "";
                $data = json_decode(json_encode($object),true);
                $openId['openid'] = $data['FromUserName'];
                $map['subscribe'] = 0;
                M('users')->where($openId)->save($map);
                break;
            case "CLICK":
                $contentStr =  $this->receiveClick($object);    
                break;
            case "LOCATION":
                $contentStr = $this->receiveLocation($object);
                break;
            // case "scancode_push":
            //     $contentStr = $this->receiveScancode_push($object);
            //     break;
            case "SCAN":
                $contentStr = $this->receiveScancode_push($object);
                break;
            default:
                $contentStr = "receive a new event: ".$object->Event;
                break;
        }
        return $contentStr;
    } 
    //关注时回复微信
    private function responseText($object, $content, $flag=0)  
    {  
        $textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content><FuncFlag>%d</FuncFlag></xml>";  
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);  
        return $resultStr;  
    }  
    //点击事件时回复微信
    private function transmitText($object,$content)
    { 
        $textTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content></xml>"; 
        $result = sprintf($textTpl, $object->FromUserName,$object->ToUserName, time(),$content); 
        return $result; 
    }
    private function receiveClick($object) 
    {        
        switch ($object->EventKey) { 
            case "about_us": $contentStr = "   北京鑫天朗科技有限公司主营社区智能技术产品开发与应用配套，本着“聚科技，助民生的经营理念，致力于成为新型智能社区配套服务领域的领导者。
    鑫天朗推出的“聚e充”共享型社区电动自行车充电站项目，意在解决社区住户电动自行车充电难、停放难的问题，同时解决物业在住户家用电动车乱停放，乱接线等方面的管理困局，规避安全隐患，为人们选择绿色出行交通工具创造条件。
    “聚e充” 采取智能后台管理体系，结合高效的充电设备，过载保护、高温保护、防爆保护、漏电保护等应有尽有。可以做到一机多用，节能环保，安全可靠，方便快捷。鑫天朗，为蓝天，为明天。"; 
                break; 
            case "manage": $contentStr = "哈哈哈"; 
                break; 
            default: $contentStr = "暂无数据"; 
                break; 
        } 
        $resultStr = $this->transmitText($object, $contentStr); 
        return $resultStr;
    } 
    //位置消息
    private function receiveLocation($object)
    {
        $data = json_decode(json_encode($object),true);
        $lng = $data['Longitude'];
        $lat = $data['Latitude'];
        $info = "http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x=$lng&y=$lat";
        $url = $this->getJson($info);
        $result['lng'] = base64_decode($url['x']);
        $result['lat'] = base64_decode($url['y']);
        $openId['openid'] = $data['FromUserName'];
        $map['latitude']  = $result['lat'];
        $map['longitude'] = $result['lng'];
        $users = M('users')->where($openId)->find();
        if(!empty($users)) {
            M('users')->where($openId)->save($map);
        }else{
            $arr['openid'] = $data['FromUserName'];
            $arr['latitude'] = $result['lat'];
            $arr['longitude'] = $result['lng'];
            M('users')->add($arr);
        }
        return 'SUCCESS';
    }
    //扫一扫
    protected function receiveScancode_push($object){
        // file_put_contents('log.txt',$object,FILE_APPEND);
        $data = json_decode(json_encode($object),true);
        switch ($object->EventKey) { 
            case "rselfmenu": 
                $contentStr = $this->Charge_code($data);
                break;
            default: 
                $contentStr = $this->Charge_code($data); 
                break; 
        }
        $resultStr = $this->transmitText($object, $contentStr); 
        return $resultStr;
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
    function Charge_code($data){
        $openId['openid'] = $data['FromUserName'];
        $users = M('users')->where($openId)->find();
        if(!empty($users)){
            if(empty($data['ScanCodeInfo']['ScanResult'])){
                $info['scene_id'] = $data['EventKey'];
            }else{
                $info['url'] = $data['ScanCodeInfo']['ScanResult'];
            }
            $user_id['user_id'] = $users['id'];  
            $orders = M('orders')->field('charge_status')->where($user_id)->select();
            if(intval($orders['charge_status'] == 1)){
                $result = "正在充电中"; 
            }else{       
                $two_code = M('two_code')->field('scene_id')->where($info)->find();
                if(!empty($two_code)){
                    $map['code'] = $two_code['scene_id'];
                    $charge = M('charge_code')->field('id,charge_id,status,charge_status,power,number')->where($map)->find();
                    $map1['id'] = $charge['charge_id'];
                    $charge_info = M('charge')->field('status,charging_code,is_free')->where($map1)->find();
                    if(intval($charge['charge_status']) == 1){
                        $result = "预约编号".$charge['id']."充电口失败，端口正处于【正在充电】状态中";
                    }else{
                        $charge1 = D('charge')->open_charge($charge_info['charging_code'],$charge['number']);
                        sleep(3); //暂停3秒再执行
                        if($charge1){                                    
                            $close = D('charge')->getPowerInfo($charge_info['charging_code'],$charge['number']);
                            if($close){
                                $res['status'] = 1;
                                M('charge')->where($map1)->save($res);
                                $clos=D('charge')->close_charge($charge_info['charging_code'],$charge['number']);
                                //vip
                                if(intval($charge_info['is_free'] == 1)){
                                    $result = "预约编号".$charge['id']."充电口成功".'，<a href="http://www.bjyilongxiang.com/Wcat/Jssdk/vip_choose_time?charge_id='.$charge['charge_id'].'&code_id='.$charge['id'].'">请点击此处打开</a>';
                                //no vip
                                }else{
                                    $result = "预约编号".$charge['id']."充电口成功".'，<a href="http://www.bjyilongxiang.com/Wcat/Jssdk/choose_time?charge_id='.$charge['charge_id'].'&code_id='.$charge['id'].'">请点击此处打开</a>';
                                }
                            }
                        }else{
                            $res['status'] = 0;
                            M('charge')->where($map1)->save($res);
                            $result = "此充电桩掉线中"; 
                        }
                    }
                }else{
                    $result = "此二维码暂无数据"; 
                } 
            }                  
        }
        return $result;
    }    
}

?>