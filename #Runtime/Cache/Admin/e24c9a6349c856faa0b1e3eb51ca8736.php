<?php if (!defined('THINK_PATH')) exit();?><!--datagrid列表-->
<style>
#ercodes{display: block;}
div.ercode{ float: left;}
div.ercode img {width: 160px; height: 160px; cursor: pointer;}
</style>
<div id="ercodes">
	<?php if(is_array($ercodes)): foreach($ercodes as $key=>$er): ?><div class="ercode"><img data-id="<?php echo ($er['scene_id']); ?>" data-url="<?php echo ($er["tiket"]); ?>" data-href="<?php echo U('Charge/syncercode');?>" src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=<?php echo ($er["tiket"]); ?>"/>
		<p><font>设备:<?php echo ($er["charging_code"]); ?></font></br>
			<font>端口:<?php echo ($er["number"]); ?></font>
			<font>参数:<?php echo ($er["scene_id"]); ?></font></p>
		</div><?php endforeach; endif; ?>
</div>