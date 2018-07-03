<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="90">用户分类：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[typeid]" style="width:230px;height:24px">
					<?php if(is_array($typelist)): foreach($typelist as $typeid=>$typename): ?><option value="<?php echo ($typeid); ?>" <?php if(($info["typeid"]) == $typeid): ?>selected<?php endif; ?>><?php echo ($typename); ?></option><?php endforeach; endif; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>用户名：</td>
			<td><input class="easyui-validatebox"  type="text" value="<?php echo ($info["username"]); ?>" readonly style="width:220px" /></td>
		</tr>
		<tr>
			<td>手机：</td>
			<td><input class="easyui-validatebox" data-options="required:true,validType:['number', 'length[11,11]','remote[\'<?php echo U('Member/public_checkMobile', array('default'=>$info['mobile']));?>\',\'mobile\']']" type="text" name="info[mobile]" value="<?php echo ($info["mobile"]); ?>" style="width:220px" /></td>
		</tr>
		<tr>
			<td>性别：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[gender]" style="width:230px;height:24px">
					<?php if(is_array($dict["gender"])): foreach($dict["gender"] as $val=>$text): ?><option value="<?php echo ($val); ?>" <?php if(($info["gender"]) == $val): ?>selected<?php endif; ?>><?php echo ($text); ?></option><?php endforeach; endif; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>昵称：</td>
			<td><input class="easyui-validatebox" data-options="required:true,validType:['length[2,50]','remote[\'<?php echo U('Member/public_checkNick', array('default'=>$info['nick']));?>\',\'nick\']']" type="text" name="info[nick]" value="<?php echo ($info["nick"]); ?>" style="width:220px" /></td>
		</tr>
		<tr>
			<td>星座：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[constellation]" style="width:230px;height:24px">
					<?php if(is_array($dict["constellation"])): foreach($dict["constellation"] as $val=>$text): ?><option value="<?php echo ($val); ?>" <?php if(($info["constellation"]) == $val): ?>selected<?php endif; ?>><?php echo ($text); ?></option><?php endforeach; endif; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>备注：</td>
			<td><textarea class="easyui-validatebox" data-options="validType:{length:[0,500]}" name="info[remark]" style="width:220px;height:60px;font-size:12px"><?php echo ($info["remark"]); ?></textarea></td>
		</tr>
	</table>
	<input name="info[memberid]" value="<?php echo ($info["memberid"]); ?>" type="hidden"/>
</form>