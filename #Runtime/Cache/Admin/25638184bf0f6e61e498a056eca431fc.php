<?php if (!defined('THINK_PATH')) exit();?><!--datagrid列表-->
<table id="<?php echo html_id('datagrid');?>" data-menu="#<?php echo html_id('datagrid-menu');?>" data-toolbar="#<?php echo html_id('datagrid-toolbar');?>" data-title="<?php echo ($title); ?>" data-url="<?php echo U(CONTROLLER_NAME . '/' . ACTION_NAME, array('grid'=>'datagrid'));?>">
	<thead>
		<tr>
			<th data-options="field:'typeid',width:100,sortable:true">ID</th>
			<th data-options="field:'typename',width:200,sortable:true">类型名称</th>
			<th data-options="field:'description',width:350,sortable:true">类型描述</th>
			<th data-options="field:'listorder',width:100,sortable:true">排序</th>
			<th data-options="field:'status',width:100,sortable:true">状态</th>
		</tr>
	</thead>
</table>

<!--工具栏-->
<div id="<?php echo html_id('datagrid-toolbar');?>">
	<!--搜索-->
	<a class="easyui-linkbutton toolbar-search" data-data="[
		{name:'类型ID',field:'typeid', editor:'numberbox',group:'其他'},
		{name:'类型名称',field:'typename', editor:'text',group:'其他'},
		{name:'状态',field:'status', editor:{type:'combobox',options:{editable:false,panelHeight:'auto',data:[{value:1,text:'启用'},{value:0,text:'禁用'}]}},group:'其他'}
	]" data-close="true" data-group="false" data-width="400" data-height="200" iconCls="fa fa-search" plain="true">搜索</a>

	<!--操作项-->
	<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><a class="easyui-linkbutton toolbar-action" data-action="<?php echo ($li["a"]); ?>" iconCls="<?php echo ($li["icon"]); ?>" plain="true" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>"><?php echo ($li["name"]); ?></a><?php endforeach; endif; ?>
</div>

<!--右键菜单-->
<?php if(count($toolbars) > 0): ?><div id="<?php echo html_id('datagrid-menu');?>" class="easyui-menu">
		<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><div class="menu-action" data-action="<?php echo ($li["a"]); ?>" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>" iconCls="<?php echo ($li["icon"]); ?>"><?php echo ($li["name"]); ?></div><?php endforeach; endif; ?>
	</div><?php endif; ?>

<script type="text/javascript">
	require(['member/type'], function(module){
		module.init("#<?php echo html_id('datagrid');?>");
	});
</script>