<?php if (!defined('THINK_PATH')) exit();?><!--datagrid列表-->
<table id="<?php echo html_id('datagrid');?>" data-menu="#<?php echo html_id('datagrid-menu');?>" data-toolbar="#<?php echo html_id('datagrid-toolbar');?>" data-title="<?php echo ($title); ?>" data-url="<?php echo U(CONTROLLER_NAME . '/' . ACTION_NAME, array('grid'=>'datagrid'));?>">
	<thead>
		<tr>
			<th data-options="field:'id',width:100,sortable:true">ID</th>
			<th data-options="field:'code',width:200,sortable:true">模板编号</th>
			<th data-options="field:'subject',width:300,sortable:true">邮件主题</th>
			<th data-options="field:'addtime',width:200,sortable:true">添加时间</th>
			<th data-options="field:'edittime',width:200,sortable:true">修改时间</th>
		</tr>
	</thead>
</table>

<!--工具栏-->
<div id="<?php echo html_id('datagrid-toolbar');?>">
	<!--搜索-->
	<a class="easyui-linkbutton toolbar-search" data-data="[
		{name:'开始时间',field:'addtime.begin', editor:{type:'datetimebox',options:{editable:false}},group:'添加时间'},
		{name:'结束时间',field:'addtime.end', editor:{type:'datetimebox',options:{editable:false}},group:'添加时间'},
		{name:'开始时间',field:'edittime.begin', editor:{type:'datetimebox',options:{editable:false}},group:'修改时间'},
		{name:'结束时间',field:'edittime.end', editor:{type:'datetimebox',options:{editable:false}},group:'修改时间'},
		{name:'ID',field:'id', editor:'numberbox',group:'其他'},
		{name:'模板编号',field:'code', editor:'text',group:'其他'},
		{name:'邮件主题',field:'subject', editor:'text',group:'其他'}
	]" data-close="true" data-group="true" data-width="400" data-height="360" iconCls="fa fa-search" plain="true">搜索</a>

	<!--操作项-->
	<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><a class="easyui-linkbutton toolbar-action" data-action="<?php echo ($li["a"]); ?>" iconCls="<?php echo ($li["icon"]); ?>" plain="true" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>"><?php echo ($li["name"]); ?></a><?php endforeach; endif; ?>
</div>

<!--右键菜单-->
<?php if(count($toolbars) > 0): ?><div id="<?php echo html_id('datagrid-menu');?>" class="easyui-menu">
		<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><div class="menu-action" data-action="<?php echo ($li["a"]); ?>" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>" iconCls="<?php echo ($li["icon"]); ?>"><?php echo ($li["name"]); ?></div><?php endforeach; endif; ?>
	</div><?php endif; ?>

<script type="text/javascript">
	require(['system/email'], function(module){
		module.init("#<?php echo html_id('datagrid');?>");
	});
</script>