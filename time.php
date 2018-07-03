<?php 
ignore_user_abort();//关闭浏览器仍然执行
set_time_limit(0);//让程序一直执行下去
$interval = 60;//每隔一定时间运行
$url="http://".$_SERVER['HTTP_HOST']."/wwwroot/Wcat/Jssdk/orders_status";
do{
    sleep($interval);
	file_get_contents($url);
}while(true);
?>