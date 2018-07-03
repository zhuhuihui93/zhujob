<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td>设备编码：</td>
			<td>
				<input class="easyui-validatebox" data-options="required:true,validType:{length:[8,8]}" type="text" name="info[charging_code]"
				 style="width:220px" />
			</td>
		</tr>
		<tr>
			<td>设备名称：</td>
			<td>
				<input class="easyui-validatebox" data-options="required:true,validType:{length:[0,20]}" type="text" name="info[charging_name]"
				 style="width:220px" />
			</td>
		</tr>
		<tr>
			<td>代理商：</td>
			<td>
				<!-- <input class="easyui-validatebox" data-options="required:true,validType:{length:[0,20]}" type="text" name="info[agent_name]"
				 style="width:220px" /> -->
				 <select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[admin_id]" style="width:230px;height:24px">
				 	<?php if(is_array($agent)): $i = 0; $__LIST__ = $agent;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$agent): $mod = ($i % 2 );++$i;?><option value="<?php echo ($agent['userid']); ?>"><?php echo ($agent['username']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>收费模式：</td>
			<td>
				<select class="easyui-combobox" data-options="editable:false,panelHeight:'auto'" name="info[is_free]" style="width:230px;height:24px">
					<option value="0">需支付</option>
					<option value="1">免支付</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>插座个数：</td>
			<td>
				<input class="easyui-validatebox" data-options="required:true,validType:{length:[0,20]}" type="text" name="info[socket_number]"
				 style="width:220px" />
			</td>
		</tr>

		<tr>
			<td>区域：</td>
			<td>
				<input class="easyui-validatebox" data-options="required:true,validType:{length:[0,20]}" type="text" name="info[area]" style="width:220px"
				/>
			</td>
		</tr>
		<tr>
			<td>地址：</td>
			<td>
				<textarea class="easyui-validatebox" data-options="validType:{length:[0,200]}" name="info[address]" style="width:220px;height:60px;font-size:12px"></textarea>
			</td>
		</tr>
		<tr>
			<td>经纬度：</td>
			<td>
				<input class="easyui-validatebox" data-options="required:true,validType:{length:[0,20]}" type="text" name="info[latitude]" style="width:220px"
				/></br>
				<input class="easyui-validatebox" data-options="required:true,validType:{length:[0,20]}" type="text" name="info[longitude]" style="width:220px"
				/></br>
				<input  class="easyui-button" data-href="<?php echo U('Charge/a2lAjax');?>" id="ajaxAds2l" type="button" value="获取"/>
			</td>
		</tr>
	</table>
</form>