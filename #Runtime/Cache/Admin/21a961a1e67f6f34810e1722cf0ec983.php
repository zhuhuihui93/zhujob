<?php if (!defined('THINK_PATH')) exit();?><div id="<?php echo html_id('panel');?>" class="easyui-panel" data-options="fit:true,title:'后台首页',border:false">
	<div class="easyui-portal">
		<div style="width:50%">
			<div title="近期登录" collapsible="true" style="padding:8px;line-height:1.8">
				<?php if(is_array($loginList)): foreach($loginList as $key=>$log): ?>[<?php echo ($log["time"]); ?>] 登录IP：<?php echo ($log["ip"]); ?><br/><?php endforeach; endif; ?>
			</div>

			<!-- <div title="新版特性" collapsible="true" style="padding:8px;line-height:1.8">
				1.支持菜单图标自定义，同时增加一些人性化功能<br/>
				2.javascript模块化处理，大部分代码可直接复制使用<br/>
				3.采用事件监听方式，以更少的代码实现更强大的功能<br/>
				4.无需担心菜单名称、html属性id以及js函数名重复问题<br/>
				5.自动判断处理图片方式(优先级 imagick > gmagick > gd)<br/>
			</div> -->

		</div>

		<div style="width:50%">
			<div title="安全提示" collapsible="true" style="padding:8px;line-height:1.8;">
				<?php if(is_writeable(SITE_DIR . DS . 'Libs')): ?>建议设置Libs目录权限为<?php if(IS_WIN): ?>只读<?php else: ?>755<?php endif; ?>  <br /><?php endif; ?>
				<?php if(APP_DEBUG): ?>网站上线后建议关闭DEBUG调试模式 <br /><?php endif; ?>
				<?php if(!C('SAVE_LOG_OPEN')): ?>建议开启后台日志记录功能<br /><?php endif; ?>
				<?php if(!C('LOGIN_ONLY_ONE')): ?>建议开启单设备登录功能<br /><?php endif; ?>
			</div>

			<!-- <div title="系统说明" collapsible="true" style="padding:8px;line-height:1.8">
				本系统采用 <span class="easytp-layer" data-type="iframe" data-bgcolor="#eeeeee" data-width="600" data-height="550" title="GPL协议" href="http://www.gnu.org/licenses/gpl.txt" style="cursor: pointer;text-decoration: underline;">GPL协议</span><br/>
				当前版本：v<?php echo C('SYSTEM_VERSION');?> （仅支持HTML5浏览器）<br />
				采用 ThinkPHP <?php echo (THINK_VERSION); ?> + jQuery EasyUI 1.4.4 开发<br/>
				捐赠作者：<span class="easytp-qrcode" data-text="h1ttps://d.alipay.com/i/index.htm?b=RECEIVE_AC&u=VkKvpKeKfqKKa2PAZSkYKk/hwW66gBzWhXd3ffsiEq8=" data-title="使用手机扫一扫支付" data-size="240" style="cursor: pointer;text-decoration: underline;">支付宝扫码支付</span>
			</div> -->

		</div>

	</div>
</div>

<script type="text/javascript">
	require(['index/welcome'], function(module){
		module.init("#<?php echo html_id('panel');?>");
	});
</script>