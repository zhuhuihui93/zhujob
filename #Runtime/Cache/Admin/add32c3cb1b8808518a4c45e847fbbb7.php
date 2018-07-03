<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="80">事件名称：</td>
			<td><input class="easyui-validatebox" type="text" name="event" value="<?php echo ($info["event"]); ?>" style="width:220px" /></td>
		</tr>
		<tr>
			<td>是否启用：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="delmark" style="width:230px;height:24px">
					<option value="1" <?php if(($info["delmark"]) == "1"): ?>selected<?php endif; ?>>启用</option>
					<option value="0" <?php if(($info["delmark"]) == "0"): ?>selected<?php endif; ?>>禁用</option>
				</select>
			</td>
		</tr>
	</table>
	<input name="id" value="<?php echo ($info["id"]); ?>" type="hidden"/>
</form>