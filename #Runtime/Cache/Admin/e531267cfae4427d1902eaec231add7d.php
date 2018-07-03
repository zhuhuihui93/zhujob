<?php if (!defined('THINK_PATH')) exit();?><!--datagrid列表-->
<table id="<?php echo html_id('datagrid');?>" data-menu="#<?php echo html_id('datagrid-menu');?>" data-toolbar="#<?php echo html_id('datagrid-toolbar');?>" data-title="<?php echo ($title); ?>" data-url="<?php echo U(CONTROLLER_NAME . '/' . ACTION_NAME, array('grid'=>'datagrid'));?>">
	<thead>
		<tr>
			<th data-options="field:'user_name',width:100,sortable:true">用户名</th>
			<th data-options="field:'cdcs',width:100,sortable:true">充电次数</th>
			<th data-options="field:'integral',width:250,sortable:true">用户积分</th>
			<th data-options="field:'pp',width:250,sortable:true">充电桩入口</th>
			<th data-options="field:'creation_time',width:100,sortable:true">关注时间</th>
			<th data-options="field:'subscribe',width:100,sortable:true">关注状态</th>
			<th data-options="field:'edit_time',width:100,sortable:true">更新时间</th>
			<th data-options="field:'remark',width:100,sortable:true">备注</th>
		</tr>
	</thead>
</table>

<!--工具栏-->
<div id="<?php echo html_id('datagrid-toolbar');?>">
	<!--搜索-->
	<a class="easyui-linkbutton toolbar-search" data-data="[
		{name:'用户名',field:'user_name', editor:'text',group:'其他'}
	]" data-close="true" data-group="true" data-width="400" data-height="260" iconCls="fa fa-search" plain="true">搜索</a>

	<!--操作项-->
	<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><a class="easyui-linkbutton toolbar-action" data-action="<?php echo ($li["a"]); ?>" iconCls="<?php echo ($li["icon"]); ?>" plain="true" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>"><?php echo ($li["name"]); ?></a><?php endforeach; endif; ?>
</div>

<!--右键菜单-->
<?php if(count($toolbars) > 0): ?><div id="<?php echo html_id('datagrid-menu');?>" class="easyui-menu">
		<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><div class="menu-action" data-action="<?php echo ($li["a"]); ?>" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>" iconCls="<?php echo ($li["icon"]); ?>"><?php echo ($li["name"]); ?></div><?php endforeach; endif; ?>
	</div><?php endif; ?>

<script type="text/javascript">
	require(['cduser/cduser'], function(module){

		module.init("#<?php echo html_id('datagrid');?>");
	});
</script>