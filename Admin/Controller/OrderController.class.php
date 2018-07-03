<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 订单管理
 * @author YITI
 */
class OrderController extends CommonController {
	/**
	 * 订单列表
	 */
	public function orderlist($search = array(), $page = 1, $rows = 10, $sort = 'creation_time', $order = 'desc'){
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
				$where['charge_id'] = array('in',$map);
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
			
				
				// $info["cd_time"] = $info["end_time"] -$info["start_time"];
				$info["cd_time"] = $info['charge_time'];
				$info["start_time"] = date('Y-m-d H:i:s', $info["start_time"]);
				$info["end_time"] = date('Y-m-d H:i:s', $info["end_time"]);
				$info["user_name"] = json_decode($info['user_name']);
				// $info["pp"] = "x:".$info["latitude"]."</br>Y:".$info["longitude"];
					
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
					$info["pay_type"] = '月卡';
					break;
					case 2:
					$info["money"] = $info["integral"].'分';
					$info["pay_type"] = '积分';
					break;
					case 3:
					$info["money"] = $info["money"].'元';
					$info["pay_type"] = '时充';
					break;
					case 4:
					$info["money"] = '免支付';
					$info["pay_type"] = '免支付';
					break;
					default:
					$info["money"] = '-';
					$info["pay_type"] = '-';
				}
			}

			$data = $id ? $list : array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else{
			$menu_db    = D('Menu');
			$menuid     = I('get.menuid');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$toolbars   = $menu_db->getToolBar($menuid);
			$this->assign('title', $currentpos);
			$this->assign('toolbars', $toolbars);
			$this->display();
		}
	}
	/**
	 * 退款订单列表
	 */
	public function reorderlist($search = array(), $page = 1, $rows = 10, $sort = 'creation_time', $order = 'desc'){
		if(IS_POST){
			
			$reorder_db = M('refund_orders');
			$users = M('users')->select();
			$orders = M('orders')->select();
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
			$total  = $reorder_db->where($where)->count();
			if($id) $limit = null;
			$list = $total ? $reorder_db->where($where)->order($order)->limit($limit)->select() : array();
			foreach($list as &$info){
				foreach($users as &$u){
					if($u["id"]==$info["user_id"]){
						$info["user_name"] = $u["user_name"];
						break;
					}
				}
				foreach($orders as &$o){
					if($o["id"]==$info["order_id"]){
						$info["pay_type"] = $o["pay_type"];
						$info["order_sn"] = $o["order_sn"];
						break;
					}
				}
				 $info["user_name"]=unicode_decode1($info["user_name"]);
				 $info["create_time"] = date('Y-m-d H:i:s', $info["create_time"]);
				// $info["edit_time"] = date('Y-m-d H:i:s', $info["edit_time"]);
				// $info["pp"] = "x:".$info["latitude"]."</br>Y:".$info["longitude"];
			}

			$data = $id ? $list : array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else{
			$menu_db    = D('Menu');
			$menuid     = I('get.menuid');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$toolbars   = $menu_db->getToolBar($menuid);
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