<?php if (!defined('THINK_PATH')) exit();?><table id="<?php echo html_id('datagrid');?>" data-menu="#<?php echo html_id('datagrid-menu');?>" data-toolbar="#<?php echo html_id('datagrid-toolbar');?>" data-title="<?php echo ($title); ?>" data-url="<?php echo U(CONTROLLER_NAME . '/' . ACTION_NAME, array('grid'=>'datagrid'));?>">
	<thead>
	<tr>
		<th data-options="field:'username',width:100,sortable:true">用户名</th>
		<th data-options="field:'controller',width:80,sortable:true">模块</th>
		<th data-options="field:'action',width:80,sortable:true">方法</th>
		<th data-options="field:'querystring',width:300,sortable:false">参数</th>
		<th data-options="field:'time',width:150,sortable:true">时间</th>
		<th data-options="field:'ip',width:100,sortable:true">IP</th>
	</tr>
	</thead>
</table>

<!--工具栏-->
<div id="<?php echo html_id('datagrid-toolbar');?>">
	<!--搜索-->
	<a class="easyui-linkbutton toolbar-search" data-data="[
		{name:'开始时间',field:'time.begin', editor:{type:'datetimebox',options:{editable:false}},group:'时间范围'},
		{name:'结束时间',field:'time.end', editor:{type:'datetimebox',options:{editable:false}},group:'时间范围'},
		{name:'模块',field:'controller', editor:'text',group:'其他'},
		{name:'方法',field:'action', editor:'text',group:'其他'},
		{name:'参数',field:'querystring', editor:'text',group:'其他'},
		{name:'IP',field:'ip', editor:'text',group:'其他'}
	]" data-close="true" data-group="true" data-width="400" data-height="310" iconCls="fa fa-search" plain="true">搜索</a>

	<!--操作项-->
	<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><a class="easyui-linkbutton toolbar-action" data-action="<?php echo ($li["a"]); ?>" iconCls="<?php echo ($li["icon"]); ?>" plain="true" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>"><?php echo ($li["name"]); ?></a><?php endforeach; endif; ?>
</div>

<!--右键菜单-->
<?php if(count($toolbars) > 0): ?><div id="<?php echo html_id('datagrid-menu');?>" class="easyui-menu">
		<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><div class="menu-action" data-action="<?php echo ($li["a"]); ?>" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>" iconCls="<?php echo ($li["icon"]); ?>"><?php echo ($li["name"]); ?></div><?php endforeach; endif; ?>
	</div><?php endif; ?>

<script type="text/javascript">
	require(['panel/operate'], function(module){
		module.init("#<?php echo html_id('datagrid');?>");
	});
</script>