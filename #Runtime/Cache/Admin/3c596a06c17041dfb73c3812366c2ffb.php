<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="zh-CN">
	<meta charset="UTF-8">
	<meta name="robots" content="none">
<meta http-equiv="Pragma" content="no-cache">
<meta name="Author" Content="YITI">
<title><?php echo strip_tags(C('SYSTEM_NAME'));?></title>
<link rel="shortcut icon" href="<?php echo SCRIPT_DIR;?>/favicon.ico" />
<link rel="stylesheet" href="/Public/static/js/easyui/themes/gray/easyui.css?201807020944">
<link rel="stylesheet" href="/Public/static/css/font-awesome.css?201807020944">
<link rel="stylesheet" href="/Public/static/css/admin.css?201807020944">
<link rel="stylesheet" href="/Public/static/js/venobox/venobox.css?201807020944">
<link rel="stylesheet" href="/Public/static/js/croppic/croppic.css?201807020944">
</head>
<body class="easyui-layout" fit="true">
	<div id="body-lock-screen-loading">
		<table width="100%" height="100%" align="center">
			<tr>
				<td><i class="fa fa-spinner fa-spin" style="font-size: 36px"></i></td>
			</tr>
		</table>
	</div>

	<div id="index-index-layout-north" data-options="region:'north',split:false,border:false">
		<div class="toolbar">
			<div class="logo"><?php echo C('SYSTEM_NAME');?></div>
			<div class="items">
				<a class="easyui-linkbutton easytp-window-open" data-options="plain:true,iconCls:'fa fa-globe'" data-href="http://www.bjyilongxiang.com/admin/index">访问首页</a>
				<a class="easyui-splitbutton" data-options="menu:'#<?php echo html_id('layout-north-toolbar-user');?>',iconCls:'fa fa-user'"><?php echo ($userInfo["username"]); ?></a>
				<a class="easyui-splitbutton" data-options="menu:'#<?php echo html_id('layout-north-toolbar-help');?>',iconCls:'fa fa-question-circle'">帮助中心</a>

				<div id="<?php echo html_id('layout-north-toolbar-user');?>">
					<div iconCls="fa fa-group"><?php echo ($userInfo["rolename"]); ?></div>
					<div class="menu-sep"></div>
					<div class="easytp-dialog-form" iconCls="fa fa-edit" data-href="<?php echo U('Index/public_userInfo');?>" data-width="400" data-height="280">个人信息</div>
					<div class="easytp-dialog-form" iconCls="fa fa-key" data-href="<?php echo U('Index/public_userPwd');?>" data-width="400" data-height="250">修改密码</div>
					<div class="menu-sep"></div>
					<div class="easytp-window-location-confirm" iconCls="fa fa-sign-out"  data-msg="确定要退出系统吗？" data-href="<?php echo U('Index/logout');?>">退出登录</div>
				</div>

				<div id="<?php echo html_id('layout-north-toolbar-help');?>">
					<div class="easytp-dialog-page" iconCls="fa fa-trash-o" data-href="<?php echo U('Index/public_clearCatche');?>" data-width="350" data-height="200">清除缓存</div>
					<div class="easytp-dialog-page" iconCls="fa fa-bar-chart" data-href="<?php echo U('Index/public_sysInfo');?>" data-width="600" data-height="400">系统信息</div>
					<div class="easytp-dialog-page" iconCls="fa fa-send-o" data-href="<?php echo U('Index/public_feedback');?>" data-width="400" data-height="300">留言反馈</div>
					<div class="menu-sep"></div>
					<div class="easytp-layer" iconCls="fa fa-book" data-type="iframe" data-href="http://www.jeasytp.com/doc.html">开发文档</div>
					<div class="easytp-layer" iconCls="fa fa-globe" data-type="iframe" data-href="http://www.jeasytp.com">官方网站</div>
					<div class="menu-sep"></div>
					<div class="fullscreen-button" iconCls="fa fa-expand">全屏模式</div>
					<div class="easytp-dialog-page" iconCls="fa fa-info-circle" data-href="<?php echo U('Index/public_about');?>" data-width="400" data-height="300">关于系统</div>
				</div>
			</div>
		</div>
		<div class="navbar">
			<ul>
				<?php if(is_array($menuList)): foreach($menuList as $key=>$menu): ?><li><a class="easytp-navbar-button" data-href="<?php echo U('Index/public_left', array('menuid'=>$menu['id']));?>" data-icon="<?php echo ($menu["icon"]); ?>"><?php echo ($menu["name"]); ?></a></li><?php endforeach; endif; ?>
			</ul>
		</div>
	</div>

	<div id="index-index-layout-west" data-options="region:'west',split:true,title:'Loading...'">
		<div id="index-index-layout-west-accordion" class="easyui-accordion" data-options="fit:true,border:false"></div>
	</div>
	
	<div id="index-index-layout-center" data-options="region:'center'">
		<div id="index-index-layout-center-tabs" class="easyui-tabs" data-options="tabPosition:'bottom',fit:true,border:false,plain:false">
			<div title="欢迎页面" href="<?php echo U('Index/public_welcome');?>" data-options="cache:false,iconCls:'fa fa-home'"></div>
		</div>
	</div>

	<div id="index-index-layout-south" data-options="region:'south',split:false">
		<div>&copy; <?php echo date('2013 - Y');?> Powered by <a href="http://bjyilongxiang.com" target="_blank">bjyilongxiang</a></div>
	</div>

	<!-- 公共部分 -->
	<div id="globel-dialog" class="easyui-dialog word-wrap" data-options="closed:true,title:'Loading...'" style="line-height:1.5"></div>
	<div id="globel-upload" style="display:block;margin:0;padding:0;width:0;height:0;overflow:hidden;"></div>
	<div id="globel-croppic" style="width:400px;height:300px;position:relative;display:none"></div>
	<img id="globel-qrcode" class="easytp-layer" data-border="20" data-bgcolor="#ffffff" style="display:none" />

	<!-- require.js -->
<script type="text/javascript" src="/Public/static/js/require.js?201807020944"></script>
<script type="text/javascript">
	window.UEDITOR_HOME_URL   = '/Public/static/js/ueditor/';
	window.UEDITOR_SERVER_URL = '<?php echo U('Upload/ueditor');?>';
	require.config({
		baseUrl: "/Public/static/module/admin/",
		urlArgs: '201807020944',
		waitSeconds: 0,
		paths: {
			'jquery':                '../../js/jquery.min',
			'jquery.easyui':         '../../js/easyui/jquery.easyui.min',
			'jquery.easyui.portal':  '../../js/easyui/plugins/jquery.portal',
			'jquery.easyui.lang':    '../../js/easyui/locale/easyui-lang-zh_CN',
			'jquery.easyui.app':     '../../js/jquery.easyui.app',
			'jquery.venobox':        '../../js/venobox/venobox.min',
			'jquery.croppic':        '../../js/croppic/croppic',
			'editor':                '../../js/ueditor/ueditor.require',
			'editor.zeroclipboard':  '../../js/ueditor/third-party/zeroclipboard/ZeroClipboard.min'
		},
		shim: {
			'jquery.venobox':      {deps: ['jquery']},
			'jquery.croppic':      {deps: ['jquery']},
			'jquery.easyui':       {deps: ['jquery']},
			'jquery.easyui.lang':  {deps: ['jquery', 'jquery.easyui']},
			'jquery.easyui.portal':{deps: ['jquery', 'jquery.easyui', 'jquery.easyui.lang']},
			'jquery.easyui.app': {
				deps: ['jquery', 'jquery.easyui', 'jquery.easyui.portal', 'jquery.easyui.lang', 'jquery.venobox', 'jquery.croppic'],
				exports: '$'
			},
			'editor':              {deps: ['../../js/ueditor/ueditor.config', 'editor.zeroclipboard']}
		}
	});
</script>
	<script type="text/javascript">
		require(['index/index'], function(module){
			module.init("<?php echo U('Index/public_sessionLife');?>");
		});
	</script>
</body>
</html>