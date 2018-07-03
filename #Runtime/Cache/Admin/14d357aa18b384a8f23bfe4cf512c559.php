<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="90">用户分类：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[typeid]" style="width:230px;height:24px">
					<?php if(is_array($typelist)): foreach($typelist as $typeid=>$typename): ?><option value="<?php echo ($typeid); ?>"><?php echo ($typename); ?></option><?php endforeach; endif; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>用户名：</td>
			<td><input class="easyui-validatebox" type="text" name="info[username]" value="默认由系统自动分配" readonly style="width:220px" /></td>
		</tr>
		<tr>
			<td>密码：</td>
			<td><input id="<?php echo html_id('form-password');?>" class="easyui-validatebox" data-options="required:true,validType:{length:[6,20]}" type="password" name="info[password]" style="width:220px" /></td>
		</tr>
		<tr>
			<td>确认密码：</td>
			<td><input class="easyui-validatebox" data-options="required:true,validType:'equals[\'#<?php echo html_id('form-password');?>\']'" type="password" style="width:220px" /></td>
		</tr>
		<tr>
			<td>手机：</td>
			<td><input class="easyui-validatebox" data-options="required:true,validType:['number', 'length[11,11]','remote[\'<?php echo U('Member/public_checkMobile');?>\',\'mobile\']']" type="text" name="info[mobile]" style="width:220px" /></td>
		</tr>
		<tr>
			<td>性别：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[gender]" style="width:230px;height:24px">
					<?php if(is_array($dict["gender"])): foreach($dict["gender"] as $val=>$text): ?><option value="<?php echo ($val); ?>"><?php echo ($text); ?></option><?php endforeach; endif; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>昵称：</td>
			<td><input class="easyui-validatebox" data-options="required:true,validType:{length: [2,30]}" type="text" name="info[nick]" style="width:220px" /></td>
		</tr>
		<tr>
			<td>星座：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[constellation]" style="width:230px;height:24px">
					<?php if(is_array($dict["constellation"])): foreach($dict["constellation"] as $val=>$text): ?><option value="<?php echo ($val); ?>"><?php echo ($text); ?></option><?php endforeach; endif; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>备注：</td>
			<td><textarea class="easyui-validatebox" data-options="validType:{length:[0,500]}" name="info[remark]" style="width:220px;height:60px;font-size:12px"></textarea></td>
		</tr>
	</table>
</form>