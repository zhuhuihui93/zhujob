<?php if (!defined('THINK_PATH')) exit();?><!--datagrid列表-->
<table id="<?php echo html_id('datagrid');?>" data-menu="#<?php echo html_id('datagrid-menu');?>" data-toolbar="#<?php echo html_id('datagrid-toolbar');?>" data-title="<?php echo ($title); ?>" data-url="<?php echo U(CONTROLLER_NAME . '/' . ACTION_NAME, array('grid'=>'datagrid'));?>">
	<thead>
		<tr>
			<th data-options="field:'memberid',width:100,sortable:true">ID</th>
			<th data-options="field:'head',width:200,sortable:true">头像</th>
			<th data-options="field:'username',width:200,sortable:true">会员名</th>
			<th data-options="field:'nick',width:200,sortable:true">昵称</th>
			<th data-options="field:'gender',width:200,sortable:true">性别</th>
			<th data-options="field:'mobile',width:200,sortable:true">手机</th>
			<th data-options="field:'constellation',width:200,sortable:true">星座</th>
			<th data-options="field:'typeid',width:200,sortable:true">会员类型</th>
			<th data-options="field:'status',width:200,sortable:true">状态</th>
			<th data-options="field:'lastlogintime',width:200,sortable:true">最后登录时间</th>
			<th data-options="field:'lastloginip',width:200,sortable:true">最后登录IP</th>
			<th data-options="field:'regtime',width:200,sortable:true">注册时间</th>
		</tr>
	</thead>
</table>

<!--工具栏-->
<div id="<?php echo html_id('datagrid-toolbar');?>">
	<!--搜索-->
	<a class="easyui-linkbutton toolbar-search" data-data="[
		{name:'开始时间',field:'lastlogintime.begin', editor:{type:'datetimebox',options:{editable:false}},group:'登录时间'},
		{name:'结束时间',field:'lastlogintime.end', editor:{type:'datetimebox',options:{editable:false}},group:'登录时间'},
		{name:'开始时间',field:'regtime.begin', editor:{type:'datetimebox',options:{editable:false}},group:'注册时间'},
		{name:'结束时间',field:'regtime.end', editor:{type:'datetimebox',options:{editable:false}},group:'注册时间'},
		{name:'会员ID',field:'memberid', editor:'numberbox',group:'其他'},
		{name:'会员名',field:'username', editor:'text',group:'其他'},
		{name:'昵称',field:'nick', editor:'text',group:'其他'},
		{name:'手机',field:'mobile', editor:'text',group:'其他'},
		{name:'性别',field:'gender', editor:{type:'combobox',options:{editable:false,panelHeight:'auto',data:<?php echo (str_replace('"',"'",json_encode($dict["gender"]))); ?>}},group:'其他'},
		{name:'星座',field:'constellation', editor:{type:'combobox',options:{editable:false,panelHeight:'auto',data:<?php echo (str_replace('"',"'",json_encode($dict["constellation"]))); ?>}},group:'其他'},
		{name:'会员类型',field:'typeid', editor:{type:'combobox',options:{editable:true,data:<?php echo (str_replace('"',"'",json_encode($combobox))); ?>}},group:'其他'}
	]" data-close="true" data-group="true" data-scrollbar="true" data-width="400" data-height="360" iconCls="fa fa-search" plain="true">搜索</a>

	<!--操作项-->
	<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><a class="easyui-linkbutton toolbar-action" data-action="<?php echo ($li["a"]); ?>" iconCls="<?php echo ($li["icon"]); ?>" plain="true" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>"><?php echo ($li["name"]); ?></a><?php endforeach; endif; ?>
</div>

<!--右键菜单-->
<?php if(count($toolbars) > 0): ?><div id="<?php echo html_id('datagrid-menu');?>" class="easyui-menu">
		<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><div class="menu-action" data-action="<?php echo ($li["a"]); ?>" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>" iconCls="<?php echo ($li["icon"]); ?>"><?php echo ($li["name"]); ?></div><?php endforeach; endif; ?>
	</div><?php endif; ?>

<script type="text/javascript">
	require(['member/user'], function(module){
		module.init("#<?php echo html_id('datagrid');?>");
	});
</script>