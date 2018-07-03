<?php if (!defined('THINK_PATH')) exit();?><table id="<?php echo html_id('treegrid');?>" data-menu="#<?php echo html_id('treegrid-menu');?>" data-toolbar="#<?php echo html_id('treegrid-toolbar');?>" data-title="<?php echo ($title); ?>" data-url="<?php echo U(CONTROLLER_NAME . '/' . ACTION_NAME, array('grid'=>'treegrid'));?>">
	<thead>
		<tr>
			<th data-options="field:'catid',width:30">栏目ID</th>
			<th data-options="field:'catname',width:150">栏目名称</th>
			<th data-options="field:'type',width:50">类型</th>
			<th data-options="field:'description',width:100">描述</th>
			<th data-options="field:'level',width:30">栏目级别</th>
			<th data-options="field:'listorder',width:30">排序</th>
			<th data-options="field:'status',width:30">状态</th>
		</tr>
	</thead>
</table>

<!--工具栏-->
<div id="<?php echo html_id('treegrid-toolbar');?>">
	<a class="easyui-linkbutton toolbar-handle" data-handle="collapseAll" iconCls="fa fa-folder-o" plain="true">收起</a>
	<a class="easyui-linkbutton toolbar-handle" data-handle="expandAll" iconCls="fa fa-folder-open-o" plain="true">展开</a>

	<!--操作项-->
	<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><a class="easyui-linkbutton toolbar-action" data-action="<?php echo ($li["a"]); ?>" iconCls="<?php echo ($li["icon"]); ?>" plain="true" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>"><?php echo ($li["name"]); ?></a><?php endforeach; endif; ?>
</div>

<!--右键菜单-->
<div id="<?php echo html_id('treegrid-menu');?>" class="easyui-menu">
	<div class="menu-handle" data-handle="collapse" iconCls="fa fa-folder-o">收起</div>
	<div class="menu-handle" data-handle="expand" iconCls="fa fa-folder-open-o">展开</div>

	<?php if(count($toolbars) > 0): ?><div class="menu-sep"></div>
		<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><div class="menu-action" data-action="<?php echo ($li["a"]); ?>" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>" iconCls="<?php echo ($li["icon"]); ?>"><?php echo ($li["name"]); ?></div><?php endforeach; endif; endif; ?>
</div>

<script type="text/javascript">
	require(['category/category'], function(module){
		module.init("#<?php echo html_id('treegrid');?>");
	});
</script>