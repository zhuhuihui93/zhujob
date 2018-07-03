<?php
namespace Admin\Controller;
use Think\Controller;
//二维码
class ErController extends Controller {

    public function createewm(){
        if(IS_GET){
			$wechat_config = C('wechat_config');
		
            $appid  = $wechat_config['appid']; 
            $secret = $wechat_config['secret'];
			//$access_token = $this->AcessToken($appid,$secret);
			$access_token = 'bjxtlkj201805081';
			var_dump($access_token);
            $json_url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;             
            $action_name = I('action_name'); //生成类型 QR_SCENE临时 QR_LIMIT_SCENE永久
			$create_num  = I('create_num');  //需要生成的个数    
			
            $now_secne_id = M('two_code')->field('scene_id')->order('id desc')->find();
            $start_secne_id = intval($now_secne_id)+1;
            $end_secne_id = intval($now_secne_id)+intval($create_num);
            $arr = 0;
            for($secne_id=$start_secne_id;$secne_id<=$end_secne_id;$secne_id++){
                $curl_data='';
                if($action_name=='QR_SCENE'){
                    $curl_data='{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$secne_id.'}}}';
                }                 
                if($action_name=='QR_LIMIT_SCENE'){
                    $curl_data='{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$secne_id.'}}}';
                }                
                $json_info=json_decode($this->api_notice_increment($json_url,$curl_data),true);
				$ticket = urlencode($json_info['ticket']); 
				var_dump($json_info);
                if($json_info['errcode']!=40013){ 
                    $data[$arr]['tiket']=$ticket;
                    $data[$arr]['url']=$json_info['url'];
                    $data[$arr]['scene_id']=$secne_id;
                    $data[$arr]['expire_seconds']=$json_info['expire_seconds'];
                    $data[$arr]['action_name']=$action_name;
                    $data[$arr]['remark']='';
                    $data[$arr]['create_time']=time();
                    $arr++;
                }else{
                    $this->appReturn('fail',103,'操作失败');
                }
            }
            if(count($data)>0){
            	foreach ($data as $key => $value) {
           		//	$res= M('two_code')->add($value);
	               // if($res){
	                    $this->appReturn('success',200,'添加成功');
	              //  }else{
	                  //  $this->appReturn('fail',103,'操作失败');
	              //  }
            	}               
            }
        }else{
			$this->appReturn('success',200,'url is ok');
		}
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