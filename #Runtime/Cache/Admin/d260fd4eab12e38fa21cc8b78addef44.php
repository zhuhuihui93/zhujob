<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td>名称：</td>
			<td><input class="easyui-validatebox" data-options="required:true,validType:{length:[0,20]}" type="text" name="info[name]" style="width:220px" /></td>
		</tr>
		<tr>
			<td>描述：</td>
			<td><textarea class="easyui-validatebox" data-options="validType:{length:[0,200]}" name="info[description]" style="width:220px;height:60px;font-size:12px"></textarea></td>
		</tr>
		<tr>
			<td>排序：</td>
			<td><input class="easyui-numberbox" type="text" name="info[listorder]" data-options="min:0,precision:0" style="width:230px;height:24px" /></td>
		</tr>
		<tr>
			<td>是否启用：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[status]" style="width:230px;height:24px">
					<option value="1">启用</option>
					<option value="0">禁用</option>
				</select>
			</td>
		</tr>
	</table>
</form>