<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="90">上级菜单：</td>
			<td><input class="easyui-combotree" data-options="url:'<?php echo U('System/public_menuSelectTree');?>'" name="info[parentid]" value="<?php echo ((isset($_GET['parentid']) && ($_GET['parentid'] !== ""))?($_GET['parentid']):0); ?>" style="width:230px;height:24px" /></td>
		</tr>
		<tr>
			<td>菜单名称：</td>
			<td><input class="easyui-validatebox" data-options="required:true,validType:['length[2,20]']" name="info[name]" type="text" style="width:220px" /></td>
		</tr>
		<tr>
			<td>模块名：</td>
			<td><input class="easyui-validatebox" data-options="required:true,validType:['controller','length[0,20]']"  name="info[c]" type="text" style="width:220px" /></td>
		</tr>
		<tr>
			<td>方法名：</td>
			<td><input class="easyui-validatebox" data-options="required:true,validType:['action','length[0,20]']"  name="info[a]" type="text" style="width:220px" /></td>
		</tr>
		<tr>
			<td>附加参数：</td>
			<td><input class="easyui-validatebox" data-options="validType:['querystring','length[0,200]']"  name="info[data]" type="text" style="width:220px" /></td>
		</tr>
		<tr>
			<td>打开方式：<br/>(左侧菜单)</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[open]" style="height:24px;width:230px">
					<option value="ajax" selected>默认</option>
					<option value="iframe">iframe</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>工具栏显示：<br/>(四级菜单)</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[toolbar]" style="height:24px;width:230px">
					<option value="1">显示</option>
					<option value="0" selected>隐藏</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>排序：</td>
			<td><input class="easyui-numberbox" type="text" name="info[listorder]" data-options="min:0,precision:0" style="width:230px;height:24px" /></td>
		</tr>
		<tr>
			<td>图标：</td>
			<td><input type="text" style="width:220px"/></td>
		</tr>
		<tr>
			<td>是否显示菜单：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[display]" style="height:24px;width:230px">
					<option value="1">显示</option>
					<option value="0">隐藏</option>
				</select>
			</td>
		</tr>
	</table>
</form>