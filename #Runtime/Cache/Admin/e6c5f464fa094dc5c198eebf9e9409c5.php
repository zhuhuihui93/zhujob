<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="80">用户名：</td>
			<td><input type="text" value="<?php echo ($info["username"]); ?>" readonly disabled style="width: 220px"/></td>
		</tr>
		<tr>
			<td>最后登录时间</td>
			<td><input type="text" value="<?php if(($info["lastlogintime"]) > "0"): echo (date('Y-m-d H:i:s',$info["lastlogintime"])); else: ?>-<?php endif; ?>" readonly disabled style="width: 220px"/></td>
		</tr>
		<tr>
			<td>最后登录IP</td>
			<td><input type="text" value="<?php echo ((isset($info["lastloginip"]) && ($info["lastloginip"] !== ""))?($info["lastloginip"]):'-'); ?>" readonly disabled style="width: 220px"/></td>
		</tr>
		<tr>
			<td>真实姓名</td>
			<td><input class="easyui-validatebox" type="text" name="info[realname]" value="<?php echo ($info["realname"]); ?>" data-options="required:true,validType:{length:[2,20]}" style="width:220px" /></td>
		</tr>
		<tr>
			<td>E-mail：</td>
			<td><input class="easyui-validatebox" type="text" name="info[email]" value="<?php echo ($info["email"]); ?>" data-options="required:true,validType:['email','length[3,40]','remote[\'<?php echo U('Index/public_checkEmail', array('default'=>$info['email']));?>\',\'email\']']" style="width:220px" /></td>
		</tr>
	</table>
</form>