<?php  
class JSSDK {  
  private $appId;  
  private $appSecret;  
  
  public function __construct($appId, $appSecret) {  
    $this->appId = $appId;  
    $this->appSecret = $appSecret;  
  }  
  
  public function getSignPackage() {  
    $jsapiTicket = $this->getJsApiTicket();  
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";  
    $timestamp = time();  
    $nonceStr = $this->createNonceStr();  
  
    // 这里参数的顺序要按照 key 值 ASCII 码升序排序  
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&tamp=$timestamp&url=$url";  
    $signature = sha1($string);  
  
    $signPackage = array(  
      "appId"     => $this->appId,  
      "nonceStr"  => $nonceStr,  
      "timestamp" => $timestamp,  
      "url"       => $url,  
      "signature" => $signature,  
      "rawString" => $string  
    );  
    return $signPackage;   
  }  
  
  private function createNonceStr($length = 16) {  
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
    $str = "";  
    for ($i = 0; $i < $length; $i++) {  
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);  
    }  
    return $str;  
  }  
  
  private function getJsApiTicket() { 
    if(!empty($_SESSION['ticket']) && empty($_SESSION['expire_time'] < time())){
        $ticket['ticket'] = $_SESSION['ticket'];
    }else{
        $accessToken = $this->getAccessToken();
        $url1 = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken"; 
        $ticket = $this->getJson($url1);
        $_SESSION['ticket'] = $ticket['ticket'];
        $_SESSION['expire_time'] = time() + 7000;
    } 
    return $ticket['ticket'];  
  }  
  
  private function getAccessToken()  
  { 
        if (!empty($_SESSION['access_token']) && $_SESSION['alive']>time()) {
            $result['access_token']=$_SESSION['access_token'];  
        }else{
            $Tokenurl='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appId.'&secret='.$this->appSecret;         
            $result = $this->getJson($Tokenurl);   
            $_SESSION['access_token'] = $result['access_token'];  
            $_SESSION['alive'] = time()+7000;  
        }  
        return $result['access_token'];    
  }  
  
    private function getJson($url){
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