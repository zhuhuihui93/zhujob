<?php
namespace Admin\Controller;
// use Admin\Controller\CommonController;
use Think\Controller;

/**
 * 充电桩功率管理模块
 * @author zhh
 */
class PowerController extends Controller {
	//充电桩功率列表
	public function PowerList($id = 0, $page = 1, $rows = 10){
		if(IS_POST){
			$power_db = M('power_info');
			//搜索
			$where  = array();
			foreach ($search as $k=>$v){
				if(strlen($v) < 1) continue;
				switch ($k){
					case 'controller':
					case 'action':
					case 'querystring':
					case 'money':
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
			$order  = array('b.id'=>'desc');
			$roleid = user_info('roleid');
			if(intval($roleid) == 4){
				$where['b.adminid'] = user_info('userid');
			}
			$total  = $power_db->alias('b')->where($where)->count();
			if($id) $limit = null;
			$field="a.username,b.*";
			$join = "left join admin as a on a.userid = b.adminid";
			$list = $total ? $power_db->alias('b')->alias('b')->field($field)->join($join)->where($where)->order($order)->limit($limit)->select() : array();
			foreach($list as &$info){
				$info["create_time"] = date('Y-m-d H:i:s', $info["create_time"]);
				$info["edit_time"] = date('Y-m-d H:i:s', $info["edit_time"]);
				if($info["power"]==1){//1,待支付2，已支付3，完成
					$info["power"] = '<=200';
				}else if($info["power"]==2){
					$info["power"] = '>200<=500';
				}else if($info["power"]==3){
					$info["power"] = '>500 <=1000';
				}else{
					$info["power"] = '其他';
				}
				if($info["times"]==1){//1,待支付2，已支付3，完成
					$info["times"] = '1小时';
				}else if($info["times"]==2){
					$info["times"] = '2小时';
				}else if($info["times"]==3){
					$info["times"] = '4小时';
				}else if($info["times"]==4){
					$info["times"] = '8小时';
				}
			}

			$data = $id ? $list : array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else{
			$menu_db    = D('Menu');
			$menuid     = I('get.menuid');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$toolbars   = $menu_db->getToolBar($menuid);
			$this->assign('users',user_info());
			$this->assign('title', $currentpos);
			$this->assign('toolbars', $toolbars);
			$this->display('index');
		}
	}
	//新增功率
	public function PowerAdd(){
		if(IS_POST){
			$data = I('info');
			if(empty($data['adminid'])){
				$adminid = user_info('userid');
				$data['adminid'] = $adminid;
			}
			$data['create_time'] = time();
			$powers = M('power_info')->where(array('adminid'=>array('eq',$data['adminid'])))->select();
			foreach ($powers as $key => $value) {
				if($value['power'] == $data['power'] && $value['times'] == $data['times']){
					$this->error('此数据已存在，请选择其他的功率或时长！');
				}
			}
			$status = M('power_info')->add($data);
			$status ? $this->success('添加成功') : $this->error('添加失败');
		}else{
			$agent = M('admin')->where(array('roleid'=>array('eq',4)))->select();
			$this->assign('agent',$agent);
			$this->assign('users',user_info());
			$this->display('power_add');
		}
	}
	//修改功率
	public function PowerEdit(){
		if(IS_POST){
			$data = I('info');
			$where['id'] = I('power_id');
			if(empty($data['adminid'])){
				$adminid = user_info('userid');
				$data['adminid'] = $adminid;
			}
			$data['edit_time'] = time();
			$infos  = M('power_info')->where($where)->find();
			$powers = M('power_info')->where(array('adminid'=>array('eq',$data['adminid'])))->select();
			if($infos['power'] !== $data['power'] || $infos['times'] !== $data['times']){
				foreach ($powers as $key => $value) {
					if($value['power'] == $data['power'] && $value['times'] == $data['times']){
						$this->error('此数据已存在，请选择其他的功率或时长！');
					}
				}
			}
			$status = M('power_info')->where($where)->save($data);
			$status ? $this->success('修改成功') : $this->error('修改失败');
		}else{
			$map['id'] = I('id');
			$power = M('power_info')->where($map)->find();
			$agent = M('admin')->where(array('roleid'=>array('eq',4)))->select();
			$this->assign('agent',$agent);
			$this->assign('info',$power);
			$this->assign('users',user_info());
			$this->display('power_edit');
		}		
	}
	//删除功率
	public function PowerDel($ids = ''){
		if(IS_POST){
			$ids = explode(',', $ids);
			$res = M('power_info')->where(array('id'=>array('in', $ids)))->delete();
			$res ? $this->success('删除成功') : $this->error('删除失败');
		}
	}
}

?>