<?php if (!defined('THINK_PATH')) exit();?><!--datagrid列表-->
<table id="<?php echo html_id('propertygrid');?>" data-toolbar="#<?php echo html_id('propertygrid-toolbar');?>" data-title="<?php echo ($title); ?>" data-url="<?php echo U(CONTROLLER_NAME . '/' . ACTION_NAME, array('grid'=>'propertygrid'));?>"></table>

<!--工具栏-->
<div id="<?php echo html_id('propertygrid-toolbar');?>">
	<!--操作项-->
	<?php if(is_array($toolbars)): foreach($toolbars as $key=>$li): ?><a class="easyui-linkbutton toolbar-action" data-action="<?php echo ($li["a"]); ?>" iconCls="<?php echo ($li["icon"]); ?>" plain="true" data-href="<?php echo U($li['c'] . '/' . $li['a'] . '?' . $li['data']);?>"><?php echo ($li["name"]); ?></a><?php endforeach; endif; ?>

	<a class="easyui-linkbutton toolbar-handle" data-handle="refresh" iconCls="fa fa-refresh" plain="true">刷新</a>
</div>

<script type="text/javascript">
	require(['system/setting'], function(module){
		module.init("#<?php echo html_id('propertygrid');?>");
	});
</script>