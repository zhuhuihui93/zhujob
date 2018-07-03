<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="90">所属角色：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[roleid]" style="width:230px;height:24px">
					<?php if(is_array($rolelist)): foreach($rolelist as $roleid=>$rolename): ?><option value="<?php echo ($roleid); ?>"><?php echo ($rolename); ?></option><?php endforeach; endif; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>用户名：</td>
			<td><input class="easyui-validatebox" type="text" name="info[username]" data-options="required:true,validType:['length[2,20]','remote[\'<?php echo U('User/public_checkName');?>\',\'name\']']" style="width:220px" /></td>
		</tr>
		<tr>
			<td>密码：</td>
			<td><input id="<?php echo html_id('form-password');?>" class="easyui-validatebox" type="password" name="info[password]" data-options="required:true,validType:{length:[6,20]}" style="width:220px" /></td>
		</tr>
		<tr>
			<td>确认密码：</td>
			<td><input class="easyui-validatebox" type="password" data-options="required:true,validType:'equals[\'#<?php echo html_id('form-password');?>\']'" style="width:220px" /></td>
		</tr>
		<tr>
			<td>真实姓名：</td>
			<td><input class="easyui-validatebox" type="text" name="info[realname]" data-options="required:true,validType:{length:[2,20]}" style="width:220px" /></td>
		</tr>
		<tr>
			<td>E-mail：</td>
			<td><input class="easyui-validatebox" type="text" name="info[email]" data-options="required:true,validType:['email','length[3,40]','remote[\'<?php echo U('User/public_checkEmail');?>\',\'email\']']" style="width:220px" /></td>
		</tr>
	</table>
</form>