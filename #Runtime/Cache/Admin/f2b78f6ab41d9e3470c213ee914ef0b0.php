<?php if (!defined('THINK_PATH')) exit();?><!--datagrid列表-->
<table id="<?php echo html_id('datagrid');?>" data-menu="#<?php echo html_id('datagrid-menu');?>" data-toolbar="#<?php echo html_id('datagrid-toolbar');?>" data-title="<?php echo ($title); ?>" data-url="<?php echo U(CONTROLLER_NAME . '/' . ACTION_NAME, array('grid'=>'datagrid'));?>">
	<thead>
		<tr>
			<th data-options="field:'user_name',width:100,sortable:true">用户名</th>
			<th data-options="field:'integral',width:100,sortable:true">用户积分</th>
			<th data-options="field:'creation_time',width:250,sortable:true">关注时间</th>
			<th data-options="field:'charge_count',width:250,sortable:true">充电次数</th>
			<th data-options="field:'charge_name',width:100,sortable:true">入口充电桩</th>
		</tr>
	</thead>
</table>

<!--工具栏-->
<div id="<?php echo html_id('datagrid-toolbar');?>">
	<!--搜索-->
	<!--操作项-->
	<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><a class="easyui-linkbutton toolbar-action" data-action="<?php echo ($li["a"]); ?>" iconCls="<?php echo ($li["icon"]); ?>" plain="true" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>"><?php echo ($li["name"]); ?></a><?php endforeach; endif; ?>
	<div>
		<a class="easyui-linkbutton di1 toolbar-action iconCls="fa fa-search" plain="true""><span class="sp1">用户统计总数(人)</span></br><span class="sp2"><?php echo ($users_count); ?></span></br><span class="sp3">用户统计总数</span></a>
	</div>
	

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