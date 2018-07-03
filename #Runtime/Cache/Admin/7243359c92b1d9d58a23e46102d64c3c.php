<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="90">所属角色：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[roleid]" style="width:230px;height:24px">
					<?php if(is_array($rolelist)): foreach($rolelist as $roleid=>$rolename): ?><option value="<?php echo ($roleid); ?>" <?php if(($info["roleid"]) == $roleid): ?>selected<?php endif; ?>><?php echo ($rolename); ?></option><?php endforeach; endif; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>用户名：</td>
			<td><input type="text" value="<?php echo ($info["username"]); ?>" readonly style="width:220px" /></td>
		</tr>
		<tr>
			<td>真实姓名：</td>
			<td><input class="easyui-validatebox" type="text" name="info[realname]" value="<?php echo ($info["realname"]); ?>" data-options="required:true,validType:{length:[2,20]}" style="width:220px" /></td>
		</tr>
		<tr>
			<td>E-mail：</td>
			<td><input class="easyui-validatebox" type="text" name="info[email]" value="<?php echo ($info["email"]); ?>" data-options="required:true,validType:['email','length[3,40]','remote[\'<?php echo U('User/public_checkEmail', array('default'=>$info['email']));?>\',\'email\']']" style="width:220px" /></td>
		</tr>
	</table>
	<input name="info[userid]" value="<?php echo ($info["userid"]); ?>" type="hidden"/>
</form>