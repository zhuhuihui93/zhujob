<?php if (!defined('THINK_PATH')) exit();?><!--datagrid列表-->
<table id="<?php echo html_id('datagrid');?>" data-menu="#<?php echo html_id('datagrid-menu');?>" data-toolbar="#<?php echo html_id('datagrid-toolbar');?>" data-title="<?php echo ($title); ?>" data-url="<?php echo U(CONTROLLER_NAME . '/' . ACTION_NAME, array('grid'=>'datagrid'));?>">
	<thead>
		<tr>
			<th data-options="field:'title',width:100,sortable:true">广告标题</th>
			<th data-options="field:'event_id',width:100,sortable:true">事件类型</th>
			<th data-options="field:'cat_id',width:250,sortable:true">广告分类</th>
			<th data-options="field:'is_show',width:250,sortable:true">是否显示</th>
			<th data-options="field:'sort',width:100,sortable:true">排序</th>
			<th data-options="field:'valid_time',width:100,sortable:true">有效时间</th>
			<th data-options="field:'times',width:250,sortable:true">播放时间段</th>
			
		</tr>
	</thead>
</table>

<!--工具栏-->
<div id="<?php echo html_id('datagrid-toolbar');?>">
	<!--搜索-->
	<a class="easyui-linkbutton toolbar-search" data-data="[
		{name:'广告标题',field:'user_name', editor:'text',group:'其他'}
	]" data-close="true" data-group="true" data-width="400" data-height="260" iconCls="fa fa-search" plain="true">搜索</a>

	<!--操作项-->
	<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><a class="easyui-linkbutton toolbar-action" data-action="<?php echo ($li["a"]); ?>" iconCls="<?php echo ($li["icon"]); ?>" plain="true" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>"><?php echo ($li["name"]); ?></a><?php endforeach; endif; ?>
</div>

<!--右键菜单-->
<?php if(count($toolbars) > 0): ?><div id="<?php echo html_id('datagrid-menu');?>" class="easyui-menu">
		<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><div class="menu-action" data-action="<?php echo ($li["a"]); ?>" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>" iconCls="<?php echo ($li["icon"]); ?>"><?php echo ($li["name"]); ?></div><?php endforeach; endif; ?>
	</div><?php endif; ?>

<script type="text/javascript">
	require(['ad/cduser'], function(module){
		module.init("#<?php echo html_id('datagrid');?>");
	});
</script>