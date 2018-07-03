<?php

namespace Wcat\Controller;
use Think\Controller;
header("Content-Type: text/html; charset=utf-8");
//微信授权用户信息
class IndexController extends Controller {
    public function index()
    {
        $wechat_config = C('wechat_config');
        $appid = $wechat_config['appid'];
        $redirect_uri = urlencode ('http://www.bjyilongxiang.com/Wcat/Index/getcode' );
        $url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        header("Location:".$url);
    }

    public function getcode(){
        if(!empty($_SESSION['user_info'])){
            $this->redirect('Users/users');
        }else{
            $wechat_config = C('wechat_config');
            $appid  = $wechat_config['appid']; 
            $secret = $wechat_config['secret'];   
            $code = $_GET["code"];
            $token = $this->AcessToken($appid,$secret);
            $oauth2Url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
            $oauth2 = $this->getJson($oauth2Url);
            $access_token = $token;
            $openid = $oauth2['openid'];
            $get_user_info_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
            $userinfo = $this->getJson($get_user_info_url);  
            $userId = M('users')->where(array('openid'=>array('eq',$userinfo['openid'])))->find();
            if(empty($userId)){
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
            }else{
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
            }
            $this->redirect('Users/users');
        }
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