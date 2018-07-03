<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<?php if($users["roleid"] != '4' ): ?><tr>
			<td width="80">代理商：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[adminid]" style="width:230px;height:24px">
					<?php if(is_array($agent)): $i = 0; $__LIST__ = $agent;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$agent): $mod = ($i % 2 );++$i; if($info['adminid'] == $agent['userid']) $check = 'selected="selected"'; else $check = ''; ?>	
						<option <?php echo $check; ?> value="<?php echo ($agent['userid']); ?>"><?php echo ($agent['username']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</td>
		</tr><?php endif; ?>
		<tr>
			<td width="80">功率：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[power]" style="width:230px;height:24px">
					<option value="1" <?php if(($info["power"]) == "1"): ?>selected<?php endif; ?>><=200</option>
					<option value="2" <?php if(($info["power"]) == "2"): ?>selected<?php endif; ?>>>200 <=500</option>
					<option value="3" <?php if(($info["power"]) == "3"): ?>selected<?php endif; ?>>>500 <=1000</option>
					<option value="4" <?php if(($info["power"]) == "4"): ?>selected<?php endif; ?>>其他</option>
				</select>
			</td>
		</tr>
		<tr>
			<td width="80">时长：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[times]" style="width:230px;height:24px">
					<option value="1" <?php if(($info["times"]) == "1"): ?>selected<?php endif; ?>>1小时</option>
					<option value="2" <?php if(($info["times"]) == "2"): ?>selected<?php endif; ?>>2小时</option>
					<option value="3" <?php if(($info["times"]) == "3"): ?>selected<?php endif; ?>>4小时</option>
					<option value="4" <?php if(($info["times"]) == "4"): ?>selected<?php endif; ?>>8小时</option>
				</select>
		    </td>
		</tr>
		<tr>
			<td width="80">价格：</td>
			<td><input class="easyui-validatebox" type="text" name="info[money]" value="<?php echo ($info['money']); ?>" style="width:220px" /></td>
		</tr>
		<input type="hidden" name="power_id" value="<?php echo ($info['id']); ?>">
	</table>
</form>