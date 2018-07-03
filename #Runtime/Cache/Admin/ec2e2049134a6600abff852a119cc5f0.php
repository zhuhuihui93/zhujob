<?php if (!defined('THINK_PATH')) exit();?><ul class="easyui-tree <?php echo html_id('tree', 'CLASS');?>" data-options='animate:true,lines:true,data:<?php echo json_encode($data);?>'></ul>

<script type="text/javascript">
	require(['index/left'], function(module){
		module.init("<?php echo html_id('tree', '.CLASS');?>");
	});
</script>