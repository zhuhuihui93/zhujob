<?php
/*
 * 公共接口
 * By xiang.fo 2017.11.28
 */
namespace Wcat\Controller;
use Think\Controller;
class PublicController extends Controller {

	public function _initialize () {
		if(!session('user_info')){
			$this->redirect('Index/index');
		}   
		$user_info = session('user_info');
		$this->assign('user_info',$user_info);
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
	function sms_Code($phone_mobile,$scene){   
		$smscode = rand(100000,999999);
		if(!preg_match("/^1[34578]{1}\d{9}$/",$phone_mobile)){  
            return array('status'=>'fail','code'=>'103','msg'=>'验证码发送失败');
	        exit();
        }  
        //发送短信文案
        $data  = "【聚e充】您的验证码为".$smscode."，请您不要告诉他人！";
        $content = iconv("UTF-8","GB2312",$data);
		$post_data = array();
        $post_data['username'] = "15011590268";
        $post_data['password'] = MD5("b3k7p9".time());
        $post_data['password'] = 'b3k7p9';
        $post_data['mobiles'] = $phone_mobile;
        $post_data['content'] = $content;
		$url='http://api.sms1086.com/api/verifycode.aspx?'; 
		$post_data=http_build_query($post_data);
        $result=file_get_contents($url.$post_data);
        parse_str($result,$pieces);
        if($pieces['result']==0){
	        $postdata = array('status'=>'success','code'=>'200','smscode'=>$smscode,'msg'=>'验证码发送成功');
	        $logdata['status'] = 1;
        }else{
        	$logdata['status'] = 0;
        	$postdata = array('status'=>'fail','code'=>'103','msg'=>'验证码发送失败');
        } 
		$logdata['phone'] = $phone_mobile;
		$logdata['session_id'] = session_id();
		$logdata['add_time'] = time();
		$logdata['effective_time'] = time()+60*60;
		$logdata['code'] = $smscode;
		if(intval($scene)) $logdata['scene'] = $scene;
		$logdata['msg'] = $data;
		if($pieces['description']){
			$logdata['error_msg']= iconv("GB2312","UTF-8",$pieces['description']);
		}
		M()->db(3,"DB_CONFIG3")->table('sms_log')->data($logdata)->add();
		return $postdata;            
	}
	/*
	 * 发送短信
	 * By hui.fo 2018.3.27
	 * request  : http://192.168.1.157/www/Api/Public/phonecode
	 * 参数说明
	 * phone    : 手机号    [必选]
	 */
	function phonecode(){
		$phone_mobile = I('phone');
		$scene = I('scene');
		if(!preg_match("/^1[34578]{1}\d{9}$/",$phone_mobile)){  
            $this->appReturn('fail',103,'手机号格式不正确');
        }
        $codeReturn = $this->sms_Code($phone_mobile,$scene);
        echo $this->ajaxReturn($codeReturn,'json');     
	}
	public function http_post_json($url, $jsonStr){
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Content-Length: ' . strlen($jsonStr)));
	    $response = curl_exec($ch);
	    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    return array($httpCode, $response);
	}

	/*
	 *   u_lat 用户纬度
	 *   u_lon 用户经度
	 *   list  sql语句
	 */
	public function range($u_lat,$u_lon,$list){
		if(!empty($u_lat) && !empty($u_lon)){
			foreach ($list as $row) {
				$row['km'] = $this->nearby_distance($u_lat, $u_lon, $row['latitude'], $row['longitude']);
				$row['km'] = round($row['km'], 1);
				$res[] = $row;
			}
			if (!empty($res)) {
				/*
				foreach ($res as $user) {
					$ages[] = $user['km'];
				}
				array_multisort($ages, SORT_ASC, $res);
				*/
				return $res;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	//计算经纬度两点之间的距离
	public function nearby_distance($lat1, $lon1, $lat2, $lon2) {
        $EARTH_RADIUS = 6378.137;
        $radLat1 = $this->rad($lat1);
        $radLat2 = $this->rad($lat2);
        $a = $radLat1 - $radLat2;
        $b = $this->rad($lon1) - $this->rad($lon2);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s1 = $s * $EARTH_RADIUS;
        $s2 = round($s1 * 10000) / 10000;
        return $s2;
        // print_r(floor($s2*100)/100);
    }
    private function rad($d) {
        return $d * 3.1415926535898 / 180.0;
    }
    //获取地址经纬度坐标
    function getCoordinate($city,$province,$area,$address){
        $json=file_get_contents("http://api.map.baidu.com/geocoder?address=".trim($area).trim($address)."&output=json&key=96980ac7cf166499cbbcc946687fb414&city=".trim($city)."");
        $infolist=json_decode($json);
        $array=array('errorno'=>'1');
        if(isset($infolist->result->location) && !empty($infolist->result->location)){
            $array=array(
            'lng'=>$infolist->result->location->lng,
            'lat'=>$infolist->result->location->lat,
            'errorno'=>'0'
            );
        }
        return $array;
    } 

    function addkey(&$val,$key, $param)
	{
	    $val[$param['key']] = $param['val'];
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