<?php
/**
 * 上传目录列表
 * @param string $path 目录名
 * @param string $exts 获取后缀名
 * @return array
 */
function file_list_upload($path, $exts = '', &$res = array()){
	$list = file_dir($path);
	foreach($list as $info){
		if($info['type'] == 'dir') continue;

		if ($exts && !preg_match("/({$exts})$/i", $info['ext'])) continue;

		$info['url'] = str_replace(UPLOAD_PATH, '', $path) . $info['path'];
		array_push($res, $info);
	}
	return $res;
}

/**
 * 用户信息管理
 * @param string $name
 * @param string $value
 * @return mix
 */
function user_info($name = '', $value = ''){
	$time = 3600 * 24;
	$key  = cookie('identity');
	if(!$key){
		$key = uuid();
		cookie('identity', $key, array('httponly'=>true));
	}

	$info = S($key);
	//清除数据
	if($name === null){
		S($key, null);
		return null;
	}

	if(empty($value)){
		if(empty($name)) return $info;
		if(isset($info[$name])) return $info[$name];
	}else{
		if(empty($name)){
			$info = $value;

			if(isset($info['password'])) unset($info['password']);
			if(isset($info['encrypt'])) unset($info['encrypt']);
		}else{
			$info[$name] = $value;
		}
		S($key, $info, $time);
	}
	return null;
}

/**
 * 生成HTML中的id
 * @param string|array $subfix 后缀
 * @param string       $prefix 前缀
 * @return string
 */
function html_id($subfix = '', $prefix = 'ID'){
	$items = array(MODULE_NAME, CONTROLLER_NAME, ACTION_NAME, http_build_query($_REQUEST));
	if($subfix){
		if(is_string($subfix)) array_push($items, $subfix);
		if(is_array($subfix)) $items = array_merge($items, $subfix);
	}

	$str  = substr(md5(implode('-', $items)), 8, 16);
	$code = array($prefix);  //id必须已字母开头
	$code = array_merge($code, str_split($str, 4));

	return strtoupper(implode('-', $code));
}

/**
 * 验证是否为datetime格式
 * @param string $str
 * @return bool
 */
function check_datetime($str = ''){
	if(!preg_match("/^\d{4}(-\d{2}){2} \d{2}(\:\d{2}){2}$/", $str)){
		return false;
	}
	return true;
}

/**
 * 验证是否为date格式
 * @param string $str
 * @return bool
 */
function check_date($str = ''){
	if(!preg_match("/^\d{4}(-\d{2}){2}$/", $str)){
		return false;
	}
	return true;
}

/**
 * 菜单图标
 * @param int $level
 * @param string|null $icon
 * @return string;
 */
function menu_icon($level, $icon = null){
	if($icon) return $icon;

	switch($level){
		case 1:
			$icon = 'fa fa-home';
			break;

		case 2:
			$icon = 'fa fa-inbox';
			break;

		case 3:
			$icon = 'fa fa-puzzle-piece';
			break;

		case 4:
			$icon = 'fa fa-file-o';
			break;

		default:
			$icon = 'fa fa-puzzle-piece';
	}

	return $icon;
}

/**
 * $str 原始中文字符串
 * $encoding 原始字符串的编码，默认GBK
 * $prefix 编码后的前缀，默认"&#"
 * $postfix 编码后的后缀，默认";"
 */
function unicode_encode($str, $encoding = 'GBK', $prefix = '&#', $postfix = ';') {
    $str = iconv($encoding, 'UCS-2', $str);
    $arrstr = str_split($str, 2);
    $unistr = '';
    for($i = 0, $len = count($arrstr); $i < $len; $i++) {
        $dec = hexdec(bin2hex($arrstr[$i]));
        $unistr .= $prefix . $dec . $postfix;
    } 
    return $unistr;
} 
 
/**
 * $str Unicode编码后的字符串
 * $decoding 原始字符串的编码，默认GBK
 * $prefix 编码字符串的前缀，默认"&#"
 * $postfix 编码字符串的后缀，默认";"
 */
function unicode_decode($unistr, $encoding = 'GBK', $prefix = '&#', $postfix = ';') {
    $arruni = explode($prefix, $unistr);
    $unistr = '';
    for($i = 1, $len = count($arruni); $i < $len; $i++) {
        if (strlen($postfix) > 0) {
            $arruni[$i] = substr($arruni[$i], 0, strlen($arruni[$i]) - strlen($postfix));
        } 
        $temp = intval($arruni[$i]);
        $unistr .= ($temp < 256) ? chr(0) . chr($temp) : chr($temp / 256) . chr($temp % 256);
    } 
    return iconv('UCS-2', $encoding, $unistr);
}

function unicode_test()
{
	# code...
	//GBK字符串测试
$str = '<b>哈哈</b>';
echo $str.'<br />';
 
$unistr = unicode_encode($str);
echo $unistr.'<br />'; // &#60;&#98;&#62;&#21704;&#21704;&#60;&#47;&#98;&#62;
 
$str2 = unicode_decode($unistr);
echo $str2.'<br />'; //<b>哈哈</b>
 
//UTF-8字符串测试
$utf8_str = iconv('GBK', 'UTF-8', $str);
echo $utf8_str.'<br />'; // <b>鍝堝搱</b> 注：UTF在GBK下显示的乱码！可切换浏览器的编码测试
 
$utf8_unistr = unicode_encode($utf8_str, 'UTF-8');
echo $utf8_unistr.'<br />'; // &#60;&#98;&#62;&#21704;&#21704;&#60;&#47;&#98;&#62;
 
$utf8_str2 = unicode_decode($utf8_unistr, 'UTF-8');
echo $utf8_str2.'<br />'; // <b>鍝堝搱</b>
 
//其它后缀、前缀测试
$prefix_unistr = unicode_encode($str, 'GBK', "\\u", '');
echo $prefix_unistr.'<br />'; // \u60\u98\u62\u21704\u21704\u60\u47\u98\u62
 
$profix_unistr2 = unicode_decode($prefix_unistr, 'GBK', "\\u", '');
echo $profix_unistr2.'<br />'; //<b>哈哈</b>
}

//将内容进行UNICODE编码，编码后的内容格式：\u56fe\u7247 （原始：图片）  
function unicode_encode1($name)  
{  
    $name = iconv('UTF-8', 'UCS-2BE', $name);  
    $len = strlen($name);  
    $str = '';  
    for ($i = 0; $i < $len - 1; $i = $i + 2)  
    {  
        $c = $name[$i];  
        $c2 = $name[$i + 1];  
        if (ord($c) > 0)  
        {    // 两个字节的文字  
            $str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);  
        }  
        else  
        {  
            $str .= $c2;  
        }  
    }  
    return $str;  
}  
  
// 将UNICODE编码后的内容进行解码，编码后的内容格式：\u56fe\u7247 （原始：图片）  
function unicode_decode1($name)  
{  
    // 转换编码，将Unicode编码转换成可以浏览的utf-8编码  
    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';  
    preg_match_all($pattern, $name, $matches);  
    if (!empty($matches))  
    {  
        $name = '';  
        for ($j = 0; $j < count($matches[0]); $j++)  
        {  
            $str = $matches[0][$j];  
            if (strpos($str, '\\u') === 0)  
            {  
                $code = base_convert(substr($str, 2, 2), 16, 10);  
                $code2 = base_convert(substr($str, 4), 16, 10);  
                $c = chr($code).chr($code2);  
				//$c = iconv('UCS-2', 'UTF-8', $c);  //UCS-2BE
				$c = iconv('UCS-2BE', 'UTF-8', $c);  //UCS-2BE
                $name .= $c;  
            }  
            else  
            {  
                $name .= $str;  
            }  
        }  
    }  
    return $name;  
}

 //封装公共的CURL函数
 function curl($url){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$data = curl_exec($curl);  
	curl_close($curl);
	return $data;
}
// 调用百度地图api获取地址的经纬度

function address2location($address,$city=""){
	$baidu_lbsyun = C('baidu_lbsyun');
		
	$ak  = $baidu_lbsyun['ak']; 
	$ret_coordtype = $baidu_lbsyun['ret_coordtype'];
	$url = 'http://api.map.baidu.com/geocoder/v2/?ret_coordtype='.$ret_coordtype.'&address='.$address.'&city='.$city.'&output=json&ak='.$ak; //GET请求
	$json=file_get_contents($url);
	$return = json_decode($json);
	return $return;
}
/**
 * 生成二维码
 * @ $num 格式
 * @ type 类型
 */
function createewm($secne_id,$type="QR_LIMIT_SCENE"){
	
		$wechat_config = C('wechat_config');
	
		$appid  = $wechat_config['appid']; 
		$secret = $wechat_config['secret'];
		//$access_token =  $wechat_config['token'];
		$access_token = AcessToken($appid,$secret);
		$json_url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$access_token;             
		$action_name = $type; //生成类型 QR_SCENE临时 QR_LIMIT_SCENE永久 
		$arr = 0;
		
			$curl_data='';
			
			
			if($action_name=='QR_SCENE'){
				$curl_data='{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$secne_id.'}}}';
				$expire_seconds = 604800;
			}                 
			if($action_name=='QR_LIMIT_SCENE'){
				$curl_data='{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$secne_id.'}}}';
				$expire_seconds = 0;
			}                
			$json_info=json_decode(api_notice_increment($json_url,$curl_data),true);
			
			if($json_info['errcode']){ 
				$data['tiket']=$json_info['errcode'];
				$data['url']=time().''.$secne_id;
				$data['scene_id']=$secne_id;
				$data['expire_seconds']='0';
				$data['action_name']=$action_name;
				$data['remark']=$json_info['errmsg'];
				$data['create_time']=time();
				return $data;
			}else{
				$ticket = urlencode($json_info['ticket']);  
				$data['tiket']=$ticket;
				$data['url']=$json_info['url'];
				$data['scene_id']=$secne_id;
				$data['expire_seconds']=$expire_seconds;
				$data['action_name']=$action_name;
				$data['remark']='';
				$data['create_time']=time();
				return $data;
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
function AcessToken($appid,$secret)  
    { 
        if (!empty($_SESSION['access_token']) && $_SESSION['alive']>time()) {
            $result['access_token']=$_SESSION['access_token'];  
        }else{
            $Tokenurl='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;         
            $result = getJson($Tokenurl);   
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
function getImage($url,$save_dir='',$filename='',$type=0){
	if(trim($url)==''){
		return array('file_name'=>'','save_path'=>'','error'=>1);
	}
	if(trim($save_dir)==''){
		$save_dir='./';
	}
	if(trim($filename)==''){//保存文件名
		$ext=strrchr($url,'.');
	if($ext!='.gif'&&$ext!='.jpg'&&$ext!='.png'&&$ext!='.jpeg'){
		return array('file_name'=>'','save_path'=>'','error'=>3);
	}
		$filename=time().rand(0,10000).$ext;
	}
	if(0!==strrpos($save_dir,'/')){
		$save_dir.='/';
	}
	//创建保存目录
	if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
		return array('file_name'=>'','save_path'=>'','error'=>5);
	}
	//获取远程文件所采用的方法
	if($type){
		$ch=curl_init();
		$timeout=5;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$img=curl_exec($ch);
		curl_close($ch);
	}else{
		ob_start();
		readfile($url);
		$img=ob_get_contents();
		ob_end_clean();
	}
	//$size=strlen($img);
	//文件大小
	$fp2=@fopen($save_dir.$filename,'a');
	fwrite($fp2,$img);
	fclose($fp2);
	unset($img,$url);
	return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
}