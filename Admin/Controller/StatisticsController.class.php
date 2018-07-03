<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 统计管理
 * @author YITI
 */
class StatisticsController extends CommonController {
	/**
	 * 统计首页
	 */
	public function index($search = array(), $page = 1, $rows = 10, $sort = 'creation_time', $order = 'desc'){
		if(IS_POST){
			$level = C('CATEGORY_LEVEL');
			$category_db = M('orders');
			$where  = array();
			//搜索
			foreach ($search as $k=>$v){
				if(strlen($v) < 1) continue;
				switch ($k){
					case 'controller':
					case 'action':
					case 'querystring':
					case 'user_name':
						$vs = unicode_encode1($v);
						$where[] = "`{$k}` like '%{$vs}%'";
						break;
					case 'time.begin':
						if(!check_datetime($v)){
							unset($search[$k]);
							continue;
						}
						$where[] = "`time` >= '{$v}'";
						break;
					case 'time.end':
						if(!check_datetime($v)){
							unset($search[$k]);
							continue;
						}
						$where[] = "`time` <= '{$v}'";
						break;
				}
			}
			$where = implode(' and ', $where);
			$limit  = ($page - 1) * $rows . "," . $rows;
			$order  = array('create_time'=>'desc');
			$roleid = user_info('roleid');
			if(intval($roleid) == 4){
				$maps['admin_id'] = user_info('userid');
				$charge = M('charge')->field('id')->where($maps)->select();
				foreach ($charge as $key => $value) {
					$map[] = $value['id'];
				}
				if(empty($map)){
					$where['charge_id'] = 0;
				}else{
					$where['charge_id'] = array('in',$map);
				}
			}
			$total  = $category_db->where($where)->count();
			if($id) $limit = null;
			$list = $total ? $category_db->where($where)->order($order)->limit($limit)->select() : array();
			$charge_db = M('charge');
			$charges = $charge_db->select();
			foreach($list as &$info){
				foreach($charges as &$charge){
					if($charge["id"]==$info["charge_id"]){
						$info["charge_name"] = $charge["charging_name"];
						break;
					}
				}
			
				
				$info["create_time"] = date('Y-m-d H:i:s', $info["create_time"]);
				
				$info["start_time"] = date('Y-m-d H:i:s', $info["start_time"]);
				$info["end_time"] = date('Y-m-d H:i:s', $info["end_time"]);
				$info["end_time"] = date('Y-m-d H:i:s', $info["end_time"]);
				
					$info['power'] = $info['power']* $info['charge_time'];
				
				
				if($info["order_status"]==1){//1,待支付2，已支付3，完成
					$info["order_status"] = '待支付';
				}else if($info["order_status"]==2){
					$info["order_status"] = '已支付';
				}else if($info["order_status"]==3){
					$info["order_status"] = '完成';
				}
				 //支付类型 1，月卡2，积分，3，时充，4，免支付
				switch ($info["pay_type"])
				{
					case 1:
					$info["money"] = '月卡';
					break;
					case 2:
					$info["money"] = $info["integral"].'分';
					break;
					case 3:
					$info["money"] = $info["money"].'元';
					break;
					case 4:
					$info["money"] = '免支付';
					break;
					default:
					$info["money"] = '-';
				}
				
				// $info["pp"] = "x:".$info["latitude"]."</br>Y:".$info["longitude"];
			}

			$data = $id ? $list : array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else{
			$roleid = user_info('roleid');
			if(intval($roleid) == 4){
				$maps['admin_id'] = user_info('userid');
				$charge = M('charge')->field('id')->where($maps)->select();
				foreach ($charge as $key => $value) {
					$map[] = $value['id'];
				}
				if(empty($map)){
					$where['charge_id'] = 0;
				}else{
					$where['charge_id'] = array('in',$map);
				}
			}
			$users_count = M('users')->count();
			$orders_count = M('orders')->where($where)->count();
			$money_svg = M('orders')->where($where)->sum('money');
			$power_svg = M('orders')->where($where)->sum('power*charge_time');
			

			$menu_db    = D('Menu');
			$menuid     = I('get.menuid');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$toolbars   = $menu_db->getToolBar($menuid);
			$this->assign('users_count', $users_count);
			$this->assign('orders_count', $orders_count);
			$this->assign('money_svg', $money_svg);
			$this->assign('power_svg', $power_svg);

			$this->assign('title', $currentpos);
			$this->assign('toolbars', $toolbars);
			$this->display();
		}
	}
		/**
	 * 订单统计
	 */
	public function orders($search = array(), $page = 1, $rows = 10, $sort = 'creation_time', $order = 'desc'){
		if(IS_POST){
			$level = C('CATEGORY_LEVEL');
			$category_db = M('orders');
			$where  = array();
			//搜索
			foreach ($search as $k=>$v){
				if(strlen($v) < 1) continue;
				switch ($k){
					case 'controller':
					case 'action':
					case 'querystring':
					case 'user_name':
						$vs = unicode_encode1($v);
						$where[] = "`{$k}` like '%{$vs}%'";
						break;
					case 'time.begin':
						if(!check_datetime($v)){
							unset($search[$k]);
							continue;
						}
						$where[] = "`time` >= '{$v}'";
						break;
					case 'time.end':
						if(!check_datetime($v)){
							unset($search[$k]);
							continue;
						}
						$where[] = "`time` <= '{$v}'";
						break;
				}
			}
			$where = implode(' and ', $where);
			$limit  = ($page - 1) * $rows . "," . $rows;
			$order  = array('create_time'=>'desc');
			$roleid = user_info('roleid');
			if(intval($roleid) == 4){
				$maps['admin_id'] = user_info('userid');
				$charge = M('charge')->field('id')->where($maps)->select();
				foreach ($charge as $key => $value) {
					$map[] = $value['id'];
				}
				if(empty($map)){
					$where['charge_id'] = 0;
				}else{
					$where['charge_id'] = array('in',$map);
				}
			}
			$total  = $category_db->where($where)->count();
			if($id) $limit = null;
			$list = $total ? $category_db->where($where)->order($order)->limit($limit)->select() : array();
			$charge_db = M('charge');
			$charges = $charge_db->select();
			foreach($list as &$info){
				foreach($charges as &$charge){
					if($charge["id"]==$info["charge_id"]){
						$info["charge_name"] = $charge["charging_name"];
						break;
					}
				}
			
				
				$info["create_time"] = date('Y-m-d H:i:s', $info["create_time"]);
				$info["cd_time"] = $info["end_time"] -$info["start_time"];
				$info["start_time"] = date('Y-m-d H:i:s', $info["start_time"]);
				$info["end_time"] = date('Y-m-d H:i:s', $info["end_time"]);
				// $info["pp"] = "x:".$info["latitude"]."</br>Y:".$info["longitude"];
				$info['power'] = $info['power']* $info['charge_time'];
				
				if($info["order_status"]==1){//1,待支付2，已支付3，完成
					$info["order_status"] = '待支付';
				}else if($info["order_status"]==2){
					$info["order_status"] = '已支付';
				}else if($info["order_status"]==3){
					$info["order_status"] = '完成';
				}
				 //支付类型 1，月卡2，积分，3，时充，4，免支付
				switch ($info["pay_type"])
				{
					case 1:
					$info["money"] = '月卡';
					break;
					case 2:
					$info["money"] = $info["integral"].'分';
					break;
					case 3:
					$info["money"] = $info["money"].'元';
					break;
					case 4:
					$info["money"] = '免支付';
					break;
					default:
					$info["money"] = '-';
				}
			}

			$data = $id ? $list : array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else{
			$roleid = user_info('roleid');
			if(intval($roleid) == 4){
				$maps['admin_id'] = user_info('userid');
				$charge = M('charge')->field('id')->where($maps)->select();
				foreach ($charge as $key => $value) {
					$map[] = $value['id'];
				}
				if(empty($map)){
					$where['charge_id'] = 0;
				}else{
					$where['charge_id'] = array('in',$map);
				}
			}
			$users_count = M('users')->count();
			$orders_count = M('orders')->where($where)->count();
			$money_svg = M('orders')->where($where)->sum('money');
			$power_svg = M('orders')->where($where)->sum('power');

			$menu_db    = D('Menu');
			$menuid     = I('get.menuid');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$toolbars   = $menu_db->getToolBar($menuid);
			$this->assign('users_count', $users_count);
			$this->assign('orders_count', $orders_count);
			$this->assign('money_svg', $money_svg);
			$this->assign('power_svg', $power_svg);

			$this->assign('title', $currentpos);
			$this->assign('toolbars', $toolbars);
			$this->display();
		}
	}
		/**
	 * 收益统计
	 */
	public function gains($search = array(), $page = 1, $rows = 10, $sort = 'creation_time', $order = 'desc'){
		if(IS_POST){
			
			$category_db = M('charge');
			//搜索
			$where  = array();
			foreach ($search as $k=>$v){
				if(strlen($v) < 1) continue;
				switch ($k){
					case 'controller':
					case 'action':
					case 'querystring':
					case 'user_name':
						$vs = unicode_encode1($v);
						$where[] = "`{$k}` like '%{$vs}%'";
						break;
					case 'time.begin':
						if(!check_datetime($v)){
							unset($search[$k]);
							continue;
						}
						$where[] = "`time` >= '{$v}'";
						break;
					case 'time.end':
						if(!check_datetime($v)){
							unset($search[$k]);
							continue;
						}
						$where[] = "`time` <= '{$v}'";
						break;
				}
			}
			$where = implode(' and ', $where);
			$limit  = ($page - 1) * $rows . "," . $rows;
			$order  = array('charge.id'=>'desc');
			$roleid = user_info('roleid');
			if(intval($roleid) == 4){
				$maps['admin_id'] = user_info('userid');
				$charge = M('charge')->field('id')->where($maps)->select();
				foreach ($charge as $key => $value) {
					$map[] = $value['id'];
				}
				if(empty($map)){
					$where['charge_id'] = 0;
				}else{
					$where['charge_id'] = array('in',$map);
				}
			}
			$total  = $category_db->where($where)->count();
			if($id) $limit = null;
			$tlist = $category_db->field('charge.charging_name as charge_name,charge.id as id,Sum(orders.integral) as sum_jf,Sum(orders.money) as sum_mo,Sum(orders.power) sum_power ,Sum(orders.electricity) as sumele,Count(orders.id) as gs')->join('LEFT JOIN orders ON charge.id = orders.charge_id')->where($where)->order($order)->group('charge.id')->select();
			$list = $total ?  $tlist: array();
			$dict = dict('type', 'Category');
			$order_db = M('orders');
			
			foreach($list as &$info){
				
				$info["create_time"] = date('Y-m-d H:i:s', $info["create_time"]);
				
				
				if($info["gs"]>0){
					
					$info["count_jf"]=$order_db->where(array('charge_id'=>$info["id"],'pay_type'=>2))->count();
					$info["bi_jf"]=round($info["sum_jf"]/$info["count_jf"]).'分';
					$info["sum_jf"]=$info["sum_jf"].'分';

					$info["count_mo"]=$order_db->where(array('charge_id'=>$info["id"],'pay_type'=>3))->count();
					$info["sum_mo"]=$info["sum_mo"].'元';

					$info["count_mc"]=$order_db->where(array('charge_id'=>$info["id"],'pay_type'=>1))->count();
					$info["bi_all"]='-';
					$info["sum_power"];
					$info["svg_power"] =round($info["sum_power"]/$info["gs"],2);
				}else{
					$info["sum_jf"]='-';
					$info["count_jf"]='-';
					$info["bi_jf"]='-';
					$info["sum_mo"]='-';
					$info["count_mo"]='-';
					$info["count_mc"]='-';
					$info["bi_all"]='-';
					$info["sum_power"]='-';
					$info["svg_power"]='-';
				}
				
			
			}

			$data = $id ? $list : array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else{
			$menu_db    = D('Menu');
			$orders_db    = M('orders');
			
			$menuid     = I('get.menuid');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$toolbars   = $menu_db->getToolBar($menuid);
			$roleid = user_info('roleid');
			if(intval($roleid) == 4){
				$maps['admin_id'] = user_info('userid');
				$charge = M('charge')->field('id')->where($maps)->select();
				foreach ($charge as $key => $value) {
					$map[] = $value['id'];
				}
				if(empty($map)){
					$where['charge_id'] = 0;
				}else{
					$where['charge_id'] = array('in',$map);
				}
			}
			$integral_sum = $orders_db->field('Sum(integral) as a')->where(array('pay_type'=>2))->where($where)->select()[0]['a'];
			$money_sum = $orders_db->field('Sum(money) as a')->where(array('pay_type'=>3))->where($where)->select()[0]['a'];
			$money_count = $orders_db->where(array('pay_type'=>3))->where($where)->count();
			$integral_count = $orders_db->where(array('pay_type'=>2))->where($where)->count();
			$this->assign('integral_sum', $integral_sum);
			$this->assign('money_sum', $money_sum);
			$this->assign('money_count', $money_count);
			$this->assign('integral_count', $integral_count);
			$this->assign('title', $currentpos);
			$this->assign('toolbars', $toolbars);
			$this->display();
		}
	}
		/**
	 * 月卡统计
	 */
	public function monthcard($search = array(), $page = 1, $rows = 10, $sort = 'creation_time', $order = 'desc'){
		if(IS_POST){
			$level = C('CATEGORY_LEVEL');
			$category_db = M('month_card');
			$users_db    = M('users');
			//搜索
			$where  = array();
			foreach ($search as $k=>$v){
				if(strlen($v) < 1) continue;
				switch ($k){
					case 'controller':
					case 'action':
					case 'querystring':
					case 'user_name':
						$vs = unicode_encode1($v);
						$where[] = "`{$k}` like '%{$vs}%'";
						break;
					case 'time.begin':
						if(!check_datetime($v)){
							unset($search[$k]);
							continue;
						}
						$where[] = "`time` >= '{$v}'";
						break;
					case 'time.end':
						if(!check_datetime($v)){
							unset($search[$k]);
							continue;
						}
						$where[] = "`time` <= '{$v}'";
						break;
				}
			}
			$where = implode(' and ', $where);
			$limit  = ($page - 1) * $rows . "," . $rows;
			$order  = array('create_time'=>'desc');
			$total  = $category_db->where($where)->count();
			if($id) $limit = null;
			$list = $total ? $category_db->where($where)->order($order)->limit($limit)->select() : array();
			$dict = dict('type', 'Category');
			foreach($list as &$info){
				$info["user_name"] = $users_db->where('id='.$info["user_id"])->find()['user_name'];
				$info["user_name"]=unicode_decode1($info["user_name"]);
				if(empty($info["user_name"])){
					$info["user_name"] = $info["user_id"];
				}
				$info["create_time"] = date('Y-m-d H:i:s', $info["create_time"]);
			}

			$data = $id ? $list : array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else{
			$menu_db    = D('Menu');
			$mcard_db    = M('month_card');
			$users_db    = M('users');
			$menuid     = I('get.menuid');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$toolbars   = $menu_db->getToolBar($menuid);
			$MC_count = $mcard_db->count();
			$MC_isexpire = $mcard_db->sum('is_expire');
			$MC_chargecs = $mcard_db->sum('charge_number');
			$MC_moneys = $mcard_db->sum('month_card_money');
			$this->assign('MC_count', $MC_count);
			$this->assign('MC_isexpire', $MC_isexpire);
			$this->assign('MC_chargecs', $MC_chargecs);
			$this->assign('MC_moneys', $MC_moneys);
			$this->assign('title', $currentpos);
			$this->assign('toolbars', $toolbars);
			$this->display();
		}
	}
	/**
	 * 用户统计
	 */
	public function users($search = array(), $page = 1, $rows = 10, $sort = 'creation_time', $order = 'desc'){
		if(IS_POST){
			$level = C('CATEGORY_LEVEL');
			$category_db = M('users');
			$orders_db = M('orders');
			$where  = array('user_type'=>"1");
			//搜索
			$where  = array('user_type'=>"1");
			foreach ($search as $k=>$v){
				if(strlen($v) < 1) continue;
				switch ($k){
					case 'controller':
					case 'action':
					case 'querystring':
					case 'user_name':
						$vs = unicode_encode1($v);
						$where[] = "`{$k}` like '%{$vs}%'";
						break;
					case 'time.begin':
						if(!check_datetime($v)){
							unset($search[$k]);
							continue;
						}
						$where[] = "`time` >= '{$v}'";
						break;
					case 'time.end':
						if(!check_datetime($v)){
							unset($search[$k]);
							continue;
						}
						$where[] = "`time` <= '{$v}'";
						break;
				}
			}
			$where = implode(' and ', $where);
			$limit  = ($page - 1) * $rows . "," . $rows;
			$order  = array('creation_time'=>'desc');
			$total  = $category_db->where($where)->count();
			if($id) $limit = null;
			$list = $total ? $category_db->where($where)->order($order)->limit($limit)->select() : array();
			
			$Model = new \Think\Model();
			foreach($list as &$info){
				
				$info["user_name"]=unicode_decode1($info["user_name"]);
				$info["charge_count"]=$orders_db->where('user_id='.$info['id'])->count();
				$info["charge_name"]=$Model->query("select charging_name from __CHARGE__ where id in (select charge_id from __ORDERS__ where user_id=".$info['id'].")")[0]['charging_name'];
				$info["creation_time"] = date('Y-m-d H:i:s', $info["creation_time"]);
				$info["edit_time"] = date('Y-m-d H:i:s', $info["edit_time"]);
				$info["integral"] = round($info["integral"],0).'分';
				$info["pp"] = "x:".$info["latitude"]."</br>Y:".$info["longitude"];
			}

			$data = $id ? $list : array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else{
			$menu_db    = D('Menu');
			$menuid     = I('get.menuid');
			$users_count = M('users')->count();
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$toolbars   = $menu_db->getToolBar($menuid);
			$this->assign('users_count', $users_count);
			$this->assign('title', $currentpos);
			$this->assign('toolbars', $toolbars);
			$this->display();
		}
	}
	/**
	 * 充值统计
	 */
	public function recharge($search = array(), $page = 1, $rows = 10, $sort = 'creation_time', $order = 'desc'){
		if(IS_POST){
			$level = C('CATEGORY_LEVEL');
			$category_db = M('recharge');
			//搜索
			$where  = array();
			foreach ($search as $k=>$v){
				if(strlen($v) < 1) continue;
				switch ($k){
					case 'controller':
					case 'action':
					case 'querystring':
					case 'user_name':
						$vs = unicode_encode1($v);
						$where[] = "`{$k}` like '%{$vs}%'";
						break;
					case 'time.begin':
						if(!check_datetime($v)){
							unset($search[$k]);
							continue;
						}
						$where[] = "`time` >= '{$v}'";
						break;
					case 'time.end':
						if(!check_datetime($v)){
							unset($search[$k]);
							continue;
						}
						$where[] = "`time` <= '{$v}'";
						break;
				}
			}
			$where = implode(' and ', $where);
			$limit  = ($page - 1) * $rows . "," . $rows;
			$order  = array('create_time'=>'desc');
			$total  = $category_db->where($where)->count();
			if($id) $limit = null;
			$list = $total ? $category_db->where($where)->order($order)->limit($limit)->select() : array();
			$dict = dict('type', 'Category');
			foreach($list as &$info){
				
				$info["user_name"] =  M('users')->where('id='.$info['user_id'])->select()[0]['user_name'];
				$info["user_name"]=unicode_decode1($info["user_name"]);
				if(empty($info["user_name"])){
					$info["user_name"] = $info["user_id"];
				}
				if($info["recharge_type"]==1){
					$info["recharge_money"] = $info["recharge_money"].'元';
				}else if($info["recharge_type"]==2){
					$info["recharge_money"] = $info["integral"].'分';
				}
				$info["create_time"] = date('Y-m-d H:i:s', $info["create_time"]);
			}

			$data = $id ? $list : array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else{
			$users_count = M('users')->count();
			$integral_sum = M('recharge')->sum('total_integral');
			

			$menu_db    = D('Menu');
			$menuid     = I('get.menuid');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$toolbars   = $menu_db->getToolBar($menuid);
			$this->assign('users_count', $users_count);
			$this->assign('integral_sum', $integral_sum);

			$this->assign('title', $currentpos);
			$this->assign('toolbars', $toolbars);
			$this->display();
		}
	}
	/**
	 * 删除一个月前数据
	 */
	public function cduserEdit(){
		$users_db = D('users');
		if(IS_POST){
			$data = I('post.info');
			$status = $users_db->save($data);
			$status ? $this->success('修改成功') : $this->error('修改失败');
		}else{
			$id   = I('get.id');
			$info = $users_db->where(array('id'=>$id))->find();
			$this->assign('info', $info);
			$this->display('cduser_edit');
		}
	}
	/**
	 * 删除一个月前数据
	 */
	public function loginDelete(){
		if(IS_POST){
			$userid       = user_info('userid');
			$admin_log_db = M('admin_log');
			$date         = date('Y-m-d', strtotime('last month'));
			$where        = "`type` = 'login' AND `userid` = {$userid} AND left(`time`, 10) <= '{$date}'";
			$result       = $admin_log_db->where($where)->delete();
			$result ? $this->success('删除成功') : $this->error('没有数据或已删除过了，请稍后再试');
		}
	}

	/**
	 * 操作日志
	 */
	public function operate($search = array(), $page = 1, $rows = 10, $sort = 'time', $order = 'desc'){
		$userid = user_info('userid');

		//搜索
		$where = array("`userid` = {$userid}");
		foreach ($search as $k=>$v){
			if(strlen($v) < 1) continue;
			switch ($k){
				case 'controller':
				case 'action':
				case 'querystring':
				case 'ip':
					$where[] = "`{$k}` like '%{$v}%'";
					break;
				case 'time.begin':
					if(!check_datetime($v)){
						unset($search[$k]);
						continue;
					}
					$where[] = "`time` >= '{$v}'";
					break;
				case 'time.end':
					if(!check_datetime($v)){
						unset($search[$k]);
						continue;
					}
					$where[] = "`time` <= '{$v}'";
					break;
			}
		}
		$where = implode(' and ', $where);

		$this->datagrid(array(
			'db'    => M('log'),
			'where' => $where,
			'page'  => $page,
			'rows'  => $rows,
			'sort'  => $sort,
			'order' => $order,
		));
	}

	/**
	 * 删除一个月前数据
	 */
	public function operateDelete(){
		if(IS_POST){
			$userid = user_info('userid');
			$log_db = M('log');
			$date   = date('Y-m-d', strtotime('last month'));
			$where  = "`userid` = {$userid} AND left(`time`, 10) <= '{$date}'";
			$result = $log_db->where($where)->delete();
			$result ? $this->success('删除成功') : $this->error('没有数据或已删除过了，请稍后再试');
		}
	}
}