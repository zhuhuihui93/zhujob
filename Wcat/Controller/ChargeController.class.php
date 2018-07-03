<?php
namespace Wcat\Controller;
use Think\Controller;
//充电桩
class ChargeController extends PublicController {

	public function my_charge()
	{
		$user_info = $this->user_info;
		if(!$user_info['id']){
            $this->redirect('Index/index');
        }  
        $users['user_id'] = $user_info['id'];
        $field = "id,charge_id";
        $orders = M('orders')->field($field)->where($users)->group('charge_id')->select();
        $arr = array();
        foreach ($orders as $key => $value) {
        	$arr[] = $value['charge_id'];
        }
        if(!empty($arr)){
        	$where['id'] = array('in',$arr);
        }else{
        	$where['id'] = '';
        }
        $field1 = "id,charging_name,longitude,latitude";
        $charge = M('charge')->field($field1)->where($where)->select();
        $map['id'] = $user_info['id'];
        $infos =  M('users')->field('latitude,longitude')->where($map)->find();
        if($infos['latitude'] && $infos['longitude']){
			$result = $this->range($infos['latitude'],$infos['longitude'],$charge);
		}else{
			$result = $charge;
		}
		$this->assign('charge',$result);
		$this->assign('name','常用电桩');
		$this->display(T('Charge/Frequently-used-ChargingStation'));
	}
	public function record_list()
	{
		$user_info = $this->user_info;
		if(!$user_info['id']){
            $this->redirect('Index/index');
        }
		$year = I('year');
		$info = explode("-", $year);
		if(!empty($year)){
			$time = $this->getShiJianChuo($info[0],$info[1]);
	        $start_time = intval($time['begin']);
	        $end_time   = intval($time['end']);
		}else{
			$time = $this->getShiJianChuo(date('Y'),date('m'));
	        $start_time = intval($time['begin']);
	        $end_time   = intval($time['end']);
		}
		$map['user_id'] = $user_info['id'];
		$field = "id,recharge_type,recharge_money,total_money,integral,total_integral,create_time,record_status";
		$data = M('recharge')->field($field)->where($map)->group('id desc')->select();
		foreach ($data as $key => $value) {
			$create_time = intval($value['create_time']);
			if($start_time <= $create_time && $end_time >= $create_time){
				$result[] = $value;
			}
		}
		$this->assign('list',$result);
		$this->assign('name','充值记录');
		$this->display(T('Charge/Recharge-record'));
	}
	public function charge_list()
	{
		$user_info = $this->user_info;
		if(!$user_info['id']){
            $this->redirect('Index/index');
        }
        $year = I('year');
		$info = explode("-", $year);
		if(!empty($year)){
			$time = $this->getShiJianChuo($info[0],$info[1]);
	        $start_time = intval($time['begin']);
	        $end_time   = intval($time['end']);
		}else{
			$time = $this->getShiJianChuo(date('Y'),date('m'));
	        $start_time = intval($time['begin']);
	        $end_time   = intval($time['end']);
		}
        $map['o.user_id'] = $user_info['id'];
        $field = "o.id,o.order_sn,o.order_status,o.power,o.money,o.pay_type,o.create_time,c.charging_name,o.integral";
        $join = "left join charge as c on o.charge_id = c.id";
        $orders = M('orders')->alias('o')->field($field)->join($join)->where($map)->order('id desc')->select();
  //       foreach ($orders as $key => $value) {
		// 	$create_time = intval($value['create_time']);
		// 	if($start_time <= $create_time && $end_time >= $create_time){
		// 		$data[] = $value;
		// 	}
		// }
		$this->assign('list',$orders);
		$this->assign('name','充电记录');
		$this->display(T('Charge/Charge-record'));
	}
	public function contact_us()
	{
		$this->assign('name','联系我们');
		$this->display(T('Charge/Contact-us'));
	}
	//正在充电列表
	public function now_charge_list(){
		$user_info = $this->user_info;
		if(!$user_info['id']){
            $this->redirect('Index/index');
        }
		$users['o.user_id'] = $user_info['id'];
		$users['order_status'] = 2;
		$orders = M('orders')->field('o.*,c.address,a.number')->alias('o')->join('LEFT JOIN charge as c on o.charge_id = c.id LEFT JOIN charge_code as a on o.code_id = a.id')->where($users)->order('o.id desc')->select();
		if(!empty($orders)){
			foreach ($orders as $key => $value) {
				$times = $value['start_time'] + ($value['charge_time']*60*60);
				$timediff = $this->timediff(time(),$times);
				$orders[$key]['time'] = $timediff['hour'].':'.$timediff['min'].':'.$timediff['sec'];
			}
		}else{
			$orders = '';
		}		
		$this->assign('list',$orders);
		$this->assign('name','正在充电列表');
		$this->display(T('Charge/zhengzaichongdian'));
	}	
	//正在充电
	public function now_charge(){
		$user_info = $this->user_info;
		if(!$user_info['id']){
            $this->redirect('Index/index');
        }
		$users['user_id'] = $user_info['id'];
        // $users['charge_status'] = 1;
        $users['order_sn'] = I('order_sn');
		$orders = M('orders')->field('order_sn,start_time,charge_time,charge_status')->where($users)->find();
		if(!empty($orders)){
			$this->assign('orders',$orders);
			$this->assign('name','正在充电');
			$this->display(T('Charge/now_charge'));
		}else{
			$this->assign('name','正在充电');
			$this->display(T('Charge/null_now_charge'));
		}
	}
	//正在充电请求接口
	public function now_charge_json(){
		$user_info = $this->user_info;
		if(!$user_info['id']){
            $this->redirect('Index/index');
        }
		$users['user_id'] = $user_info['id'];
        // $users['charge_status'] = 1;
        $users['order_sn'] = I('order_sn');
		$orders = M('orders')->field('code_id,order_sn,start_time,charge_time,charge_status')->where($users)->find();
		$times = $orders['start_time'] + ($orders['charge_time']*60*60);
		$data['end_time'] = $times - time();
		$data['order_sn'] = $orders['order_sn'];
		$data['charge_status'] = $orders['charge_status'];
		$data['charge_time'] = $orders['charge_time'];
		if(!empty($data)){
			$this->appReturn('success',200,'成功！',$data);
		}else{
			$this->appReturn('fail',102,'暂无数据');
		}
	}
	//关闭充电口
	public function close_charge(){
		$order_sn['order_sn'] = I('order_sn');
		$status = I('status');
		if(!$order_sn['order_sn']) $this->appReturn('fail',103,'订单号不能为空！');
		$orders = M('orders')->field('charge_id,code_id')->where($order_sn)->find();
		$map['id'] = $orders['charge_id'];
		$charges = M('charge')->field('charging_code')->where($map)->find();
		$map1['id'] = $orders['code_id'];
		$codes = M('charge_code')->field('number')->where($map1)->find();
		$close = D('charge')->close_charge($charges['charging_code'],$codes['number'],$status,$order_sn['order_sn']);
		if($close){
			$this->appReturn('success',200,'成功！');
		}
	}
	//获取月份的开始时间和结束时间
    function getShiJianChuo($nian=0,$yue=0){
        if(empty($nian) || empty($yue)){
            $now = time();
            $nian = date("Y",$now);
            $yue =  date("m",$now);
        }
        $time['begin'] = mktime(0,0,0,$yue,1,$nian);
        $time['end'] = mktime(23,59,59,($yue+1),0,$nian);
        return $time;
    }
    //计算两个时间差
    function timediff($begin_time,$end_time)
	{
	    if($begin_time < $end_time){
	        $starttime = $begin_time;
	        $endtime = $end_time;
	    }else{
	        $starttime = $end_time;
	        $endtime = $begin_time;
	    }
	    //计算天数
	    $timediff = $endtime-$starttime;
	    $days = intval($timediff/86400);
	    //计算小时数
	    $remain = $timediff%86400;
	    $hours = intval($remain/3600);
	    //计算分钟数
	    $remain = $remain%3600;
	    $mins = intval($remain/60);
	    //计算秒数
	    $secs = $remain%60;
	    $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
	    return $res;
	}
}

?>