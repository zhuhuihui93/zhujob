<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 故障管理
 * Fault
 * @author YITI
 */
class FaultController extends CommonController {
	/**
	 * 登录日志
	 */
	public function index($search = array(), $page = 1, $rows = 10, $sort = 'add_time', $order = 'desc'){
		if(IS_POST){
			$level = C('CATEGORY_LEVEL');
			$category_db = M('fault_log');
			//搜索
			$where  = array('status'=>"1");
			foreach ($search as $k=>$v){
				if(strlen($v) < 1) continue;
				switch ($k){
					case 'controller':
					case 'action':
					case 'querystring':
					case 'name':
						//$vs = unicode_encode1($v);
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
			$limit  = ($page - 1) * $rows . "," . $rows;
			$order  = array('add_time'=>'desc');
			$total  = $category_db->where($where)->count();
			if($id) $limit = null;
			$list = $total ? $category_db->where($where)->order($order)->limit($limit)->select() : array();
			
			foreach($list as &$info){
				
				$info["fault_time"] = date('Y-m-d H:i:s', $info["fault_time"]);
				$info["fault_sure_time"] = date('Y-m-d H:i:s', $info["fault_sure_time"]);
			
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