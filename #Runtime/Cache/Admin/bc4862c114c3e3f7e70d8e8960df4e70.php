<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">

		<tr>
			<td>用户积分：</td>
			<td><input class="easyui-validatebox" data-options="required:true,validType:{length:[0,20]}" type="text" name="info[integral]" value="<?php echo ($info["integral"]); ?>" style="width:220px" /></td>
		</tr>
	</table>
	<input name="info[id]" value="<?php echo ($info["id"]); ?>" type="hidden"/>
</form>