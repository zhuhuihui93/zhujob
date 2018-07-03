<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 充电桩
 * @author YITI
 */
class ChargeController extends CommonController {
	/**
	 * 充电桩管理
	 */
	public function index($search = array(), $page = 1, $rows = 10, $sort = 'creation_time', $order = 'desc'){
		if(IS_POST){
			$charge_db = M('charge');
		
			//搜索
			$where  = array();
			foreach ($search as $k=>$v){
				if(strlen($v) < 1) continue;
				switch ($k){
					case 'controller':
					case 'action':
					case 'querystring':
					case 'charging_name':
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
			$order  = array('create_time'=>'desc');
			$roleid = user_info('roleid');
			if(intval($roleid) == 4){
				$where['admin_id'] = user_info('userid');
			}
			$total  = $charge_db->where($where)->count();
			if($id) $limit = null;
			$list = $total ? $charge_db->where($where)->order($order)->limit($limit)->select() : array();
			foreach($list as &$info){
				// $info["user_name"]=unicode_decode1($info["user_name"]);
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
	 * 充电桩管理-添加
	 */
	public function ChargeAdd(){
		$charge_db = M('charge');

		if(IS_POST){
			set_time_limit(0);
			$data = I('post.info');
			$ChargeCode = M('charge_code');
			$TwoCode = M('two_code');
			//检查设备编码
			$charging_code = $data['charging_code'];
			//验证sn号唯一
			$where['charging_code'] = array('EQ',$charging_code);
			$codeid=$charge_db->where($where)->getField('id');
			if($codeid){
				$this->error('修改失败,设备编码冲突.['.$codeid.']');
			}
			//补齐字段
			$charge_id = $charge_db->add($data);
			//生成二维码
			$num = $data['socket_number'];//充电口个数
			//$secne_id = $num;//时间戳+循环序号
			$secne_id = M('two_code')->order('scene_id desc')->field('scene_id')->find();
			$secne_id =$secne_id['scene_id'];
			$secne_id = intval($secne_id);
			// 批量添加数据
			for ($i=1; $i<=$num ; $i++) { 
				$ercode = createewm($secne_id+$i);
				
				$url=$ercode['url'];
				$ccList[] = array('charge_id'=>$charge_id,'port'=>80,'number'=>$i,'code'=>$ercode['scene_id'],'two_code'=>$url,'create_time'=>time());
				$tcList[] = $ercode; 
			}
			
			$res_chargecode = $ChargeCode->addAll($ccList);
			$res_twocode = $TwoCode->addAll($tcList);
			
			//var_dump($res_chargecode);
			//var_dump($res_twocode);
			//保存充电桩二维码表
			if($charge_id){//添加成功
				//调用方法，生成二维码连接
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}
			
		}else{
			$roleid['id'] = user_info('roleid');
			$map['roleid'] = 4;
			if(intval($roleid) == 4){
				$map['userid'] = user_info('userid');
				$agent = M('admin')->field('userid,username')->where($map)->find();
			}else{
				$agent = M('admin')->field('userid,username')->where($map)->select();
			}
			$this->assign('agent',$agent);
			$this->assign('info', $info);
			$this->display('charge_add');
		}
	}
	/**
	 * 充电桩管理-编辑
	 */
	public function ChargeEdit(){
		$charge_db = M('charge');
		if(IS_POST){
			$data = I('post.info');
			$charging_code = $data['charging_code'];
			$id = $data['id'];
			//验证sn号唯一
			$where['charging_code'] = array('EQ',$charging_code);
	
			$codeid=$charge_db->where($where)->getField('id');
			if($codeid==$id){
				$status = $charge_db->save($data);
				$status ? $this->success('修改成功') : $this->error('修改失败');
			}else{
				$this->error('修改失败,设备编码冲突.['.$codeid.']');
			}
			
			
		}else{
			$id  = I('get.id');
			$info = $charge_db->where(array('id'=>$id))->find();
			$roleid['id'] = user_info('roleid');
			$map['roleid'] = 4;
			if(intval($roleid) == 4){
				$map['userid'] = user_info('userid');
				$agent = M('admin')->field('userid,username')->where($map)->find();
			}else{
				$agent = M('admin')->field('userid,username')->where($map)->select();
			}
			$this->assign('agent',$agent);
			$this->assign('info', $info);
			$this->display('charge_edit');
		}
	}
	/**
	 * 充电桩管理-删除
	 */
	public function ChargeDelete(){
		
		if(IS_POST){
			$charge_db = M('charge');
			$ChargeCode = M('charge_code');
			$TwoCode = M('two_code');
			$id   = I('post.ids');
			//删除二维码表
			$urls = $ChargeCode->where(array('charge_id in ('.$id.')'))->getField('two_code',true);
			if($urls){
				$where['url']=array('in',$urls);
				$twocodes = $TwoCode->where($where)->delete();
			}
			//$TwoCode->where();
			//删除充电口
			$urls = $ChargeCode->where(array('charge_id in ('.$id.')'))->delete();
			//删除充电桩
			$result = $charge_db->where(array('id in ('.$id.')'))->delete();
			
			$result ? $this->success('删除成功') : $this->error('没有数据或已删除过了，请稍后再试');
		}
	}
	/**
	 * 时充收费管理
	 */
	public function paytype($search = array(), $page = 1, $rows = 10, $sort = 'creation_time', $order = 'desc'){
		if(IS_POST){
			$charge_db = M('charge_power');
		
			//搜索
			$where  = array();
			foreach ($search as $k=>$v){
				if(strlen($v) < 1) continue;
				switch ($k){
					case 'controller':
					case 'action':
					case 'querystring':
					case 'charging_name':
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
			$total  = $charge_db->where($where)->count();
			if($id) $limit = null;
			$list = $total ? $charge_db->where($where)->order($order)->limit($limit)->select() : array();
			foreach($list as &$info){
				// $info["user_name"]=unicode_decode1($info["user_name"]);
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
	 * 充电桩管理-二维码列表
	 */
	public function chargeercode($search = array(), $page = 1, $rows = 10, $sort = 'creation_time', $order = 'desc'){
			$id   = I('get.id');
			$ccode_db = M('charge_code');
			$ercodes = $ccode_db
			->join('two_code ON charge_code.code = two_code.scene_id','LEFT')
			->join('charge ON charge_code.charge_id = charge.id','LEFT')
			->where(array('charge_code.charge_id'=>$id))
			//->fetchSql(true)
			->select();
			//var_dump($ercodes);
			$this->assign('ercodes', $ercodes);
			$this->display('ercode');
		
	}
	public function syncercode(){
		if (IS_AJAX && IS_POST)
		{
		//获取二维码获取
		//保存 charge_code
		//保存 two_code
		$ChargeCode = M('charge_code');
		$TwoCode = M('two_code');
		$id   = I('post.id');
		$fid   = I('post.fid');
		$ercode = createewm($id);
		
		$url=$ercode['url'];
		$tiket = $ercode['tiket'];
		$cc = array('two_code'=>$url);
		$tc = array('url'=>$url,'tiket'=>$tiket); 
		if(strlen($tiket)>10){
		//生成特定二维码并保存数据库和文件系统(公共目录下ercode，按照设备号+id+充电口.jpg)
		$filename = $fid.'.jpg';
		$imgurl = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$tiket;
		$img = getImage($imgurl,APP_ROOT.'Public/Ercode',$filename);//保存远程图片到指定目录
		//error=0 	save_path  file_name
		/**
		 * IMAGE_WATER_NORTHWEST =   1 ; //左上角水印
		 * IMAGE_WATER_NORTH     =   2 ; //上居中水印
		 * IMAGE_WATER_NORTHEAST =   3 ; //右上角水印
		 * IMAGE_WATER_WEST      =   4 ; //左居中水印
		 * IMAGE_WATER_CENTER    =   5 ; //居中水印
		 * IMAGE_WATER_EAST      =   6 ; //右居中水印
		 * IMAGE_WATER_SOUTHWEST =   7 ; //左下角水印
		 * IMAGE_WATER_SOUTH     =   8 ; //下居中水印
		 * IMAGE_WATER_SOUTHEAST =   9 ; //右下角水印
		 */
		if($img['error']==0){//原始图片保存完成
			$font = APP_ROOT.'Public/font/simhei.ttf';
			$logo = APP_ROOT.'Public/logo/logo.png';
			$file = $img['save_path'];
			$new_file = APP_ROOT.'Public/Ercodes/'.$filename;
			//图片加图片+文字水印
			$image = new \Think\Image(); 
			// 在图片右下角添加水印文字 ThinkPHP 并保存为new.jpg
			$res = $image->open($file)
				  ->water($logo,\Think\Image::IMAGE_WATER_CENTER,90)
				  ->text($fid,$font,18,'#FF0000',\Think\Image::IMAGE_WATER_CENTER)
				  ->save($new_file); 
			//处理后图片保存另外制定目录
		}else{
			$data = array('msg'=>'图片保存异常','status'=>1);
		}
		
		
		

		//更新充电口
		
			$c = $ChargeCode->where('code='.$id)->save($cc);
			$c1 = $TwoCode->where('scene_id='.$id)->save($tc);
			$data = array('msg'=>'', 'data'=>'/Public/Ercodes/'.$fid.'.jpg?'.time(),'status'=>0);
		}else{
			$data = array('msg'=>'['.$tiket.']'.$ercode['remark'],'status'=>1);
		}
		//更新二维码
		 
		$this->ajaxReturn($data);
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
	/**
	 * 充电装-地址转坐标
	 */
	public function a2lAjax()
	{
		# ajax返会地址转经纬度
		//针对后台ajax请求特殊处理
		if(!IS_AJAX) send_http_status(404);
		if (IS_AJAX && IS_POST){
			$ads = I('post.ads');
			$city = I('post.city');
			$data = address2location($ads,$city);

			// $status ? $this->success('修改成功') : $this->error('修改失败');
			// $data = array('info'=>'请求地址不存在或已经删除', 'status'=>0, 'total'=>0, 'rows'=>array());
			$this->ajaxReturn($data);
		}else{
			$this->display('Common:500');
		}
	}
	/**
	 * 充电装-二维码打包下载
	 */
	public function ercodeDownload()
	{
		# ajax返会地址转经纬度
		//针对后台ajax请求特殊处理
		if(!IS_AJAX) send_http_status(404);
		if (IS_AJAX && IS_POST){
			$ads = I('post.ads');
			$city = I('post.city');
			$data = address2location($ads,$city);

			// $status ? $this->success('修改成功') : $this->error('修改失败');
			// $data = array('info'=>'请求地址不存在或已经删除', 'status'=>0, 'total'=>0, 'rows'=>array());
			$this->ajaxReturn($data);
		}else{
			$this->display('Common:502');
		}
	}
}