<?php if (!defined('THINK_PATH')) exit();?><!--datagrid列表-->
<table id="<?php echo html_id('datagrid');?>" data-menu="#<?php echo html_id('datagrid-menu');?>" data-toolbar="#<?php echo html_id('datagrid-toolbar');?>" data-title="<?php echo ($title); ?>" data-url="<?php echo U(CONTROLLER_NAME . '/' . ACTION_NAME, array('grid'=>'datagrid'));?>">
	<thead>
		<tr>
			<th data-options="field:'charge_name',width:3,sortable:true">设备名称</th>
			<th data-options="field:'sum_jf',width:10,sortable:true">积分累计</th>
			<th data-options="field:'count_jf',width:10,sortable:true">积分充电次数</th>
			<th data-options="field:'bi_jf',width:10,sortable:true">积分订单均价</th>
			<th data-options="field:'sum_mo',width:10,sortable:true">现金累计</th>
			<th data-options="field:'count_mo',width:10,sortable:true">现金充电次数</th>
			<th data-options="field:'count_mc',width:10,sortable:true">月卡充电次数</th>
			<th data-options="field:'bi_all',width:10,sortable:true">总收益比</th>
			<th data-options="field:'sum_power',width:10,sortable:true">总电量</th>
			<th data-options="field:'svg_power',width:10,sortable:true">平均用电量</th>
		</tr>
	</thead>
</table>

<!--工具栏-->
<div id="<?php echo html_id('datagrid-toolbar');?>">
	<!--搜索-->
	<!--操作项-->
	<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><a class="easyui-linkbutton toolbar-action" data-action="<?php echo ($li["a"]); ?>" iconCls="<?php echo ($li["icon"]); ?>" plain="true" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>"><?php echo ($li["name"]); ?></a><?php endforeach; endif; ?>
	<div>
		<a class="easyui-linkbutton di1 toolbar-action iconCls="fa fa-search" plain="true""><span class="sp1">现金订单金额(元)</span></br><span class="sp2"><?php echo ($money_sum); ?></span></br><span class="sp3">现金订单<?php echo ($money_count); ?>个</span></a>
		<a class="easyui-linkbutton di2 toolbar-action iconCls="fa fa-search" plain="true""><span class="sp1">积分订单分数(分)</span></br><span class="sp2"><?php echo ($integral_sum); ?></span></br><span class="sp3">积分订单<?php echo ($integral_count); ?>个</span></a>
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