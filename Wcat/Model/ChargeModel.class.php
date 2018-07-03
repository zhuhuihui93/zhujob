<?php

namespace Wcat\Model;
use Think\Model;

class ChargeModel extends Model{

    protected $opid    = "201805242301064940";  
    protected $opidkey = "8C210C1AEA8DEE08";  

    //开启充电口
    public function open_charge($chargeCode,$codeId,$order_sn){
    	$opidkey = $this->opidkey;
    	$opid = $this->opid;
        $url = "http://open.mocele.com/api/action/open";
        $timestamp = (string)time();
        $string = $this->createNonceStr();
        $sign = strtoupper(MD5($opid.$chargeCode.$codeId.$timestamp.$string.$opidkey));
        $data = array(  
            "opid"        => $opid,  
            "number"      => $chargeCode,  
            "opennum"     => intval($codeId),  
            "nonce_str"   => $string,  
            "timestamp"   => $timestamp,  
            "sign"        => $sign  
        );
        $data1['data'] = json_encode($data);
        $info = json_decode($this->api_notice_increment($url,$data1),true);
        if($info['msg'] == "success" && $info['msgid'] == 0000){
            if(!empty($order_sn)){
                $arr['charge_status'] = 1;
                $arr['start_time'] = time();
                $map['order_sn'] = $order_sn;
                M('orders')->where($map)->save($arr);
                return true;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
    //关闭充电口
    public function close_charge($chargeCode,$codeId,$status,$order_sn){
        $opidkey = $this->opidkey;
        $opid = $this->opid;
        $url = "http://open.mocele.com/api/action/close";
        $timestamp = (string)time();
        $string = $this->createNonceStr();
        $sign = strtoupper(MD5($opid.$chargeCode.$codeId.$timestamp.$string.$opidkey));
        $data = array(  
            "opid"        => $opid,  
            "number"      => $chargeCode,  
            "opennum"     => intval($codeId),  
            "nonce_str"   => $string,  
            "timestamp"   => $timestamp,  
            "sign"        => $sign  
        );
        $data1['data'] = json_encode($data);
        $info = json_decode($this->api_notice_increment($url,$data1),true);
        if($info['msg'] == "success" && $info['msgid'] == 0000){
            if(!empty($order_sn)){
                $arr['charge_status'] = $status;
                $arr['order_status'] = 3;
                $arr['end_time'] = time();
                $map['order_sn'] = $order_sn;
                $order_info = M('orders')->where($map)->save($arr);
                $orders = M('orders')->where(array('order_sn'=>array('eq',$order_sn)))->find();
                if($order_info){
                    $maps['charge_status'] = 0;
                    $maps['power'] = 0;
                    $where['id'] = $orders['code_id'];
                    M('charge_code')->where($where)->save($maps);
                    if(empty($orders['integral'])){
                        $mun = $orders['money'].'元';
                    }else{
                        $mun = $orders['integral'].'积分';
                    }
                    $wxmsg = D('Wxmsg');
                    $data5=array(
                        'first'=>array('value'=>urlencode("本次充电完成。"),'color'=>'#00CD00'),
                        'keyword1'=>array('value'=>urlencode($order_sn)),
                        'keyword2'=>array('value'=>urlencode($chargeCode)),
                        'keyword3'=>array('value'=>urlencode($orders['charge_time'].'小时')),
                        'keyword4'=>array('value'=>urlencode($mun)),
                        'keyword5'=>array('value'=>urlencode(date('Y-m-d H:i:s',$orders['end_time']))),
                        'remark'=>array('value'=>urlencode('感谢您的使用！')),
                    );
                    $usId1['id'] = $orders['user_id'];
                    $sOpids = M('users')->field('openid')->where($usId1)->find();
                    $wxmsgs5 = $wxmsg->doSend($sOpids['openid'],'c5xBzHgQc_mJiTfA7bucui8cgyWPzQMAABAmytgElow','',$data5);
                    $read['read'] = 1;
                    M('orders')->where(array('order_sn'=>array('eq',$value['order_sn'])))->save($read);
                    echo "";
                }
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
    //获取功率
    public function getPowerInfo($chargeId,$codeId){
        $opidkey = $this->opidkey;
        $opid = $this->opid;
        $url = "http://open.mocele.com/api/action/numinfo";
        $timestamp = (string)time();
        $string = $this->createNonceStr();
        $sign = strtoupper(MD5($opid.$chargeId.$codeId.$timestamp.$string.$opidkey));
        $data = array(  
            "opid"        => $opid,  
            "number"      => $chargeId,  
            "opennum"     => intval($codeId),  
            "nonce_str"   => $string,  
            "timestamp"   => $timestamp,  
            "sign"        => $sign  
        );
        $data1['data'] = json_encode($data);
        $info = json_decode($this->api_notice_increment($url,$data1),true);
        if($info['msg'] == "success" && $info['msgid'] == 0000){
            return true;
        }else{
            return false;
        }
    }

    public function createNonceStr($length = 16) {  
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
        $str = "";  
        for ($i = 0; $i < $length; $i++) {  
          $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);  
        }  
        return $str;  
    } 
    function api_notice_increment($url, $data){
        $ch = curl_init();
        $header = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return $ch;
        }else{ 
            return $tmpInfo;
        }
        curl_close( $ch ) ;
    }
}
?>