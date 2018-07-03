<?php if (!defined('THINK_PATH')) exit();?><style>
	.di1,.di2,.di3,.di4{ color: #ffffff; margin: 5px 15px;}
	span.sp2 {font-size: 20px;}
	.di1{background-color:#f46593;}
	.di2{background-color:#0dc4a8;}
	.di3{background-color:#efb730;}
	.di4{background-color:#08a85e;}
</style>
<!--datagrid列表-->
<table id="<?php echo html_id('datagrid');?>" data-menu="#<?php echo html_id('datagrid-menu');?>" data-toolbar="#<?php echo html_id('datagrid-toolbar');?>" data-title="<?php echo ($title); ?>" data-url="<?php echo U(CONTROLLER_NAME . '/' . ACTION_NAME, array('grid'=>'datagrid'));?>">
	<thead>
		<tr>
			<th data-options="field:'charge_name',width:100,sortable:true">设备</th>
			<th data-options="field:'order_status',width:100,sortable:true">支付状态</th>
			<th data-options="field:'money',width:250,sortable:true">消费金额</th>
			<th data-options="field:'charge_time',width:250,sortable:true">充电时长</th>
			<th data-options="field:'power',width:100,sortable:true">电量</th>
			<th data-options="field:'create_time',width:100,sortable:true">订单时间</th>
			<th data-options="field:'edit_time',width:100,sortable:true">操作</th>
		</tr>
	</thead>
</table>

<!--工具栏-->
<div id="<?php echo html_id('datagrid-toolbar');?>">
	<!--搜索-->
	<!--操作项-->
	<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><a class="easyui-linkbutton toolbar-action" data-action="<?php echo ($li["a"]); ?>" iconCls="<?php echo ($li["icon"]); ?>" plain="true" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>"><?php echo ($li["name"]); ?></a><?php endforeach; endif; ?>
	<div>
		<a class="easyui-linkbutton di1 toolbar-action iconCls="fa fa-search" plain="true""><span class="sp1">用户总数(位)</span></br><span class="sp2"><?php echo ($users_count); ?></span></br><span class="sp3">点此查看用户统计</span></a>
		<a class="easyui-linkbutton di2 toolbar-action iconCls="fa fa-search" plain="true""><span class="sp1">订单数(次)</span></br><span class="sp2"><?php echo ($orders_count); ?></span></br><span class="sp3">点此查看订单统计</span></a>
		<a class="easyui-linkbutton di3 toolbar-action iconCls="fa fa-search" plain="true""><span class="sp1">消费金额(元)</span></br><span class="sp2"><?php echo ($money_svg); ?></span></br><span class="sp3">点此查看收益统计</span></a>
		<a class="easyui-linkbutton di4 toolbar-action iconCls="fa fa-search" plain="true""><span class="sp1">用电电量(瓦/时)</span></br><span class="sp2"><?php echo ($power_svg); ?></span></br><span class="sp3">点此查看收益统计</span></a>
	
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