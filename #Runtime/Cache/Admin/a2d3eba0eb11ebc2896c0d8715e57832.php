<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="80">上级栏目：</td>
			<td><input class="easyui-combotree" data-options="url:'<?php echo U('Category/public_categorySelect');?>'" type="text" name="info[parentid]" value="<?php echo ($info["parentid"]); ?>" style="width:230px;height:24px" /></td>
		</tr>
		<tr>
			<td>栏目名称：</td>
			<td><input class="easyui-validatebox" data-options="required:true,validType:{length:[0,20]}" type="text" name="info[catname]" value="<?php echo ($info["catname"]); ?>" style="width:220px" /></td>
		</tr>
		<tr>
			<td>栏目类型：</td>
			<td>
				<select name="info[type]" class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" style="width:230px;height:24px">
					<?php if(is_array($typeList)): foreach($typeList as $key=>$type): if(is_array($type['son'])): ?><optgroup label="<?php echo ($type["name"]); ?>">
								<?php if(is_array($type["son"])): foreach($type["son"] as $key2=>$type2): ?><option value="<?php echo ($key2); ?>" <?php if(($info["type"]) == $key2): ?>selected<?php endif; ?>><?php echo ($type2["name"]); ?></option><?php endforeach; endif; ?>
							</optgroup>
						<?php else: ?>
							<option value="<?php echo ($key); ?>" <?php if(($info["type"]) == $key): ?>selected<?php endif; ?>><?php echo ($type["name"]); ?></option><?php endif; endforeach; endif; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>栏目描述：</td>
			<td><textarea class="easyui-validatebox" data-options="validType:{length:[0,200]}" name="info[description]" style="width:220px;height:60px;font-size:12px"><?php echo ($info["description"]); ?></textarea></td>
		</tr>
		<tr>
			<td>排序：</td>
			<td><input class="easyui-numberbox" type="text" name="info[listorder]" value="<?php echo ($info["listorder"]); ?>" data-options="min:0,precision:0" style="width:230px;height:24px" /></td>
		</tr>
		<tr>
			<td>图标：</td>
			<td><input type="text" name="info[icon]" value="<?php echo ($info["icon"]); ?>" style="width:220px"/></td>
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
	<input name="info[catid]" value="<?php echo ($info["catid"]); ?>" type="hidden"/>
</form>