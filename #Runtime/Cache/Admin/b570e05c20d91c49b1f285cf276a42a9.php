<?php if (!defined('THINK_PATH')) exit();?><!--datagrid列表-->
<table id="<?php echo html_id('datagrid');?>" data-menu="#<?php echo html_id('datagrid-menu');?>" data-toolbar="#<?php echo html_id('datagrid-toolbar');?>" data-title="<?php echo ($title); ?>" data-url="<?php echo U(CONTROLLER_NAME . '/' . ACTION_NAME, array('grid'=>'datagrid'));?>">
	<thead>
		<tr>
			<th data-options="field:'charging_name',width:100,sortable:true">设备名称</th>
			<th data-options="field:'agent_name',width:100,sortable:true">代理商</th>
			<th data-options="field:'charge_pattern',width:250,sortable:true">收费模式</th>
			<th data-options="field:'status',width:250,sortable:true">状态</th>
			<th data-options="field:'socket_number',width:100,sortable:true">端口数</th>
			<th data-options="field:'charging_number',width:100,sortable:true">充电次数</th>
			<th data-options="field:'address',width:300,sortable:true">区域</th>
			<th data-options="field:'charging_code',width:100,sortable:true">设备编码</th>
		</tr>
	</thead>
</table>

<!--工具栏-->
<div id="<?php echo html_id('datagrid-toolbar');?>">
	<!--搜索-->
	<a class="easyui-linkbutton toolbar-search" data-data="[
		{name:'设备名称',field:'user_name', editor:'text',group:'其他'}
	]" data-close="true" data-group="true" data-width="400" data-height="260" iconCls="fa fa-search" plain="true">搜索</a>

	<!--操作项-->
	<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><a class="easyui-linkbutton toolbar-action" data-action="<?php echo ($li["a"]); ?>" iconCls="<?php echo ($li["icon"]); ?>" plain="true" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>"><?php echo ($li["name"]); ?></a><?php endforeach; endif; ?>
</div>

<!--右键菜单-->
<?php if(count($toolbars) > 0): ?><div id="<?php echo html_id('datagrid-menu');?>" class="easyui-menu">
		<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><div class="menu-action" data-action="<?php echo ($li["a"]); ?>" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>" iconCls="<?php echo ($li["icon"]); ?>"><?php echo ($li["name"]); ?></div><?php endforeach; endif; ?>
	</div><?php endif; ?>

<script type="text/javascript">
	require(['charge/charge'], function(module){
		module.init("#<?php echo html_id('datagrid');?>");
	});
</script>