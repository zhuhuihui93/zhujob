<?php if (!defined('THINK_PATH')) exit();?><!--datagrid列表-->
<table id="<?php echo html_id('datagrid');?>" data-menu="#<?php echo html_id('datagrid-menu');?>" data-toolbar="#<?php echo html_id('datagrid-toolbar');?>" data-title="<?php echo ($title); ?>" data-url="<?php echo U(CONTROLLER_NAME . '/' . ACTION_NAME, array('grid'=>'datagrid'));?>">
	<thead>
		<tr>
			<th data-options="field:'title',width:150,sortable:false">名称</th>
			<th data-options="field:'name',width:100,sortable:false">标识</th>
			<th data-options="field:'description',width:350,sortable:false">描述</th>
			<th data-options="field:'status_text',width:100,sortable:false">状态</th>
			<th data-options="field:'author',width:100,sortable:false">作者</th>
			<th data-options="field:'version',width:100,sortable:false">版本</th>
		</tr>
	</thead>
</table>

<!--工具栏-->
<?php if(count($toolbars) > 0): ?><div id="<?php echo html_id('datagrid-toolbar');?>">
		<!--操作项-->
		<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><a class="easyui-linkbutton toolbar-action" data-action="<?php echo ($li["a"]); ?>" iconCls="<?php echo ($li["icon"]); ?>" plain="true" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>"><?php echo ($li["name"]); ?></a><?php endforeach; endif; ?>
	</div><?php endif; ?>

<!--右键菜单-->
<?php if(count($toolbars) > 0): ?><div id="<?php echo html_id('datagrid-menu');?>" class="easyui-menu">
		<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><div class="menu-action" data-action="<?php echo ($li["a"]); ?>" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>" iconCls="<?php echo ($li["icon"]); ?>"><?php echo ($li["name"]); ?></div><?php endforeach; endif; ?>
	</div><?php endif; ?>

<script type="text/javascript">
	require(['extend/addon'], function(module){
		module.init("#<?php echo html_id('datagrid');?>");
	});
</script>