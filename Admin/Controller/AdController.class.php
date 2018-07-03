<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 广告管理
 * @author YITI
 */
class AdController extends CommonController {
	/**
	 * 广告列表管理
	 */
	public function index($search = array(), $page = 1, $rows = 10, $sort = 'creation_time', $order = 'desc'){
		if(IS_POST){
			$level = C('CATEGORY_LEVEL');
			$category_db = M('advert');
			
			//搜索
			$where  = array();
			foreach ($search as $k=>$v){
				if(strlen($v) < 1) continue;
				switch ($k){
					case 'controller':
					case 'action':
					case 'querystring':
					case 'titile':
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
			$order  = array('sort'=>'desc');
			$total  = $category_db->where($where)->count();
			if($id) $limit = null;
			$list = $total ? $category_db->where($where)->order($order)->limit($limit)->select() : array();
			
			foreach($list as &$info){
				
				$info["valid_time"] = date('Y-m-d H:i:s', $info["valid_time"]);
				$info["start_time"] = date('Y-m-d H:i:s', $info["start_time"]);
				$info["end_time"] = date('Y-m-d H:i:s', $info["end_time"]);
				$info["times"] = $info["start_time"]."~".$info["end_time"];
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
	 * 广告分类管理
	 */
	public function category($search = array(), $page = 1, $rows = 10, $sort = 'creation_time', $order = 'desc'){
		if(IS_POST){
			
			$category_db = M('advert_category');
		
			//搜索
			$where  = array();
			foreach ($search as $k=>$v){
				if(strlen($v) < 1) continue;
				switch ($k){
					case 'controller':
					case 'action':
					case 'querystring':
					case 'cat_name':
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
			foreach($list as &$info){
				$info["create_time"] = date('Y-m-d H:i:s', $info["create_time"]);
				$info["edit_time"] = date('Y-m-d H:i:s', $info["edit_time"]);
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
	 * 广告绑定管理
	 */
	public function binding($search = array(), $page = 1, $rows = 10, $sort = 'creation_time', $order = 'desc'){
		if(IS_POST){
			
			$category_db = M('advert_event');
			
			//搜索
			$where  = array();
			foreach ($search as $k=>$v){
				if(strlen($v) < 1) continue;
				switch ($k){
					case 'controller':
					case 'action':
					case 'querystring':
					case 'event':
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
			foreach($list as &$info){
				
				$info["create_time"] = date('Y-m-d H:i:s', $info["create_time"]);
				$info["edit_time"] = date('Y-m-d H:i:s', $info["edit_time"]);
				
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