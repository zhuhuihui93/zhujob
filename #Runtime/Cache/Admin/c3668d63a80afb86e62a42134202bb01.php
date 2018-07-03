<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<?php if($users["roleid"] != '4' ): ?><tr>
			<td width="80">代理商：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[adminid]" style="width:230px;height:24px">
					<?php if(is_array($agent)): $i = 0; $__LIST__ = $agent;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$agent): $mod = ($i % 2 );++$i;?><option value="<?php echo ($agent['userid']); ?>"><?php echo ($agent['username']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</td>
		</tr><?php endif; ?>
		<tr>
			<td width="80">功率：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[power]" style="width:230px;height:24px">
					<option value="1"><=200</option>
					<option value="2">>200 <=500</option>
					<option value="3">>500 <=1000</option>
					<option value="4">其他</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="80">时长：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[times]" style="width:230px;height:24px">
					<option value="1">1小时</option>
					<option value="2">2小时</option>
					<option value="3">4小时</option>
					<option value="4">8小时</option>
				</select>
		    </td>
		</tr>
		<tr>
			<td width="80">价格：</td>
			<td><input class="easyui-validatebox" type="text" name="info[money]" style="width:220px" /></td>
		</tr>
	</table>
</form>