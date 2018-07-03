<?php
namespace Wcat\Model;
use Think\Model;

class WxmsgModel extends Model{
	protected $appid;
	protected $secrect;

	function  __construct()
	{
		$wechat_config = C('wechat_config');
        $appid  = $wechat_config['appid']; 
        $secret = $wechat_config['secret']; 
		$this->appid = $appid;
		$this->secrect = $secret;
	}

	/**
	 * 发送post请求
	 * @param string $url
	 * @param string $param
	 * @return bool|mixed
	 */

	function request_post($url = '', $param = '')
	{
		if (empty($url) || empty($param)) {
			return false;
		}
		$postUrl = $url;
		$curlPost = $param;
		$ch = curl_init(); //初始化curl
		curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
		curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		$data = curl_exec($ch); //运行curl
		curl_close($ch);
		return $data;
	}

	/**
	 * 发送get请求
	 * @param string $url
	 * @return bool|mixed
	 */

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


	/**
	 * 发送自定义的模板消息
	 * @param $touser
	 * @param $template_id
	 * @param $url
	 * @param $data
	 * @param string $topcolor
	 * @return bool
	 */

	public function doSend($touser, $template_id, $url, $data, $topcolor = '#7B68EE')
	{
		$template = array(
		'touser' => $touser,
		'template_id' => $template_id,
		'url' => $url,
		'topcolor' => $topcolor,
		'data' => $data
		);
		$json_template = json_encode($template); 
		$token = $this->AcessToken($this->appid,$this->secrect);
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $token;
		$dataRes = $this->request_post($url, urldecode($json_template));
		var_dump($dataRes);
		if ($dataRes['errcode'] == 0) {
			return true;
		} else {
			return false;
		}		
	}
	/**
	 * @param $appid
	 * @param $appsecret
	 * @return mixed
	 * 获取token
	 */

	public	function AcessToken($appid,$secret)  
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
}
?>