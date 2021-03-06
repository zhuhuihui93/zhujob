<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="80">角色名称：</td>
			<td><input class="easyui-validatebox" type="text" name="info[rolename]" value="<?php echo ($info["rolename"]); ?>" data-options="required:true,validType:['length[2,20]','remote[\'<?php echo U('User/public_checkRoleName', array('default'=>$info['rolename']));?>\',\'rolename\']']" style="width:220px" /></td>
		</tr>
		<tr>
			<td>角色描述：</td>
			<td><textarea class="easyui-validatebox" name="info[description]" data-options="validType:{length:[0,200]}" style="width:220px;height:60px;font-size:12px"><?php echo ($info["description"]); ?></textarea></td>
		</tr>
		<tr>
			<td>排序：</td>
			<td><input class="easyui-numberbox" type="text" name="info[listorder]" value="<?php echo ($info["listorder"]); ?>" data-options="min:0,precision:0" style="width:230px;height:24px" /></td>
		</tr>
		<tr>
			<td>是否启用：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[status]" style="width:230px;height:24px">
					<option value="1" <?php if(($info["status"]) == "1"): ?>selected<?php endif; ?>>启用</option>
					<option value="0" <?php if(($info["status"]) == "0"): ?>selected<?php endif; ?>>禁用</option>
				</select>
			</td>
		</tr>
	</table>
	<input name="info[roleid]" value="<?php echo ($info["roleid"]); ?>" type="hidden"/>
</form>