<?php if (!defined('THINK_PATH')) exit();?><!--datagrid列表-->
<table id="<?php echo html_id('datagrid');?>" data-menu="#<?php echo html_id('datagrid-menu');?>" data-toolbar="#<?php echo html_id('datagrid-toolbar');?>" data-title="<?php echo ($title); ?>" data-url="<?php echo U(CONTROLLER_NAME . '/' . ACTION_NAME, array('grid'=>'datagrid'));?>">
	<thead>
		<tr>
			<th data-options="field:'userid',width:100,sortable:true">ID</th>
			<th data-options="field:'username',width:200,sortable:true">用户名</th>
			<th data-options="field:'realname',width:200,sortable:true">真实姓名</th>
			<th data-options="field:'email',width:200,sortable:true">邮箱</th>
			<th data-options="field:'roleid',width:200,sortable:true">角色</th>
			<th data-options="field:'lastlogintime',width:200,sortable:true">最后登录时间</th>
			<th data-options="field:'lastloginip',width:200,sortable:true">最后登录IP</th>
		</tr>
	</thead>
</table>

<!--工具栏-->
<div id="<?php echo html_id('datagrid-toolbar');?>">
	<!--搜索-->
	<a class="easyui-linkbutton toolbar-search" data-data="[
		{name:'开始时间',field:'lastlogintime.begin', editor:{type:'datetimebox',options:{editable:false}},group:'登录时间'},
		{name:'结束时间',field:'lastlogintime.end', editor:{type:'datetimebox',options:{editable:false}},group:'登录时间'},
		{name:'用户ID',field:'userid', editor:'numberbox',group:'其他'},
		{name:'用户名',field:'username', editor:'text',group:'其他'},
		{name:'真实姓名',field:'realname', editor:'text',group:'其他'},
		{name:'邮箱',field:'email', editor:'text',group:'其他'},
		{name:'角色',field:'roleid', editor:{type:'combobox',options:{editable:true,data:<?php echo (str_replace('"',"'",json_encode($combobox))); ?>}},group:'其他'},
		{name:'IP',field:'lastloginip', editor:'text',group:'其他'}
	]" data-close="true" data-group="true" data-width="400" data-height="360" iconCls="fa fa-search" plain="true">搜索</a>

	<!--操作项-->
	<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><a class="easyui-linkbutton toolbar-action" data-action="<?php echo ($li["a"]); ?>" iconCls="<?php echo ($li["icon"]); ?>" plain="true" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>"><?php echo ($li["name"]); ?></a><?php endforeach; endif; ?>
</div>

<!--右键菜单-->
<?php if(count($toolbars) > 0): ?><div id="<?php echo html_id('datagrid-menu');?>" class="easyui-menu">
		<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><div class="menu-action" data-action="<?php echo ($li["a"]); ?>" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>" iconCls="<?php echo ($li["icon"]); ?>"><?php echo ($li["name"]); ?></div><?php endforeach; endif; ?>
	</div><?php endif; ?>

<script type="text/javascript">
	require(['user/user'], function(module){
		module.init("#<?php echo html_id('datagrid');?>");
	});
</script>