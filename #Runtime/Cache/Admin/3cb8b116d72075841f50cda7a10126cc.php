<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="80">用户名：</td>
			<td><input type="text" value="<?php echo ($info["username"]); ?>" readonly disabled style="width: 220px"/></td>
		</tr>
		<tr>
			<td>旧密码：</td>
			<td><input class="easyui-validatebox" type="password"  name="old_password" data-options="required:true,validType:['length[6,20]','remote[\'<?php echo U('Index/public_checkPassword');?>\',\'password\']']" style="width:220px" /></td>
		</tr>
		<tr>
			<td>新密码：</td>
			<td><input id="<?php echo html_id('form-password');?>" class="easyui-validatebox" type="password" name="new_password" data-options="required:true,validType:{length:[6,20]}" style="width:220px" /></td>
		</tr>
		<tr>
			<td>重复新密码：</td>
			<td><input class="easyui-validatebox" type="password" data-options="required:true,validType:'equals[\'#<?php echo html_id('form-password');?>\']'" style="width:220px" /></td>
		</tr>
	</table>
</form>