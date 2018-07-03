<?php if (!defined('THINK_PATH')) exit();?><form class="dialog-form-page">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="80">订单号：</td>
			<td><?php echo ($info['order_sn']); ?></td>
		</tr>
		<tr>
			<td width="80">设备名称：</td>
			<td><?php echo ($info['charging_name']); ?></td>
		</tr>
		<tr>
			<td width="80">端口号：</td>
			<td><?php echo ($info['number']); ?></td>
		</tr>
		<tr>
			<td width="80">下单人姓名：</td>
			<td><?php echo json_decode($info['user_name']);?></td>
		</tr>
		<tr>
			<td width="80">订单状态：</td>
			<?php if($info["order_status"] == '1'): ?><td>待支付</td>
			<?php elseif($info["order_status"] == '2'): ?>
				<td>已支付</td>
			<?php else: ?>
				<td>已完成</td><?php endif; ?>
		</tr>
		<tr>
			<td width="80">支付类型：</td>
			<?php if($info["pay_type"] == '1'): ?><td>月卡支付</td>
			<?php elseif($info["pay_type"] == '2'): ?>
				<td>积分支付</td>
			<?php else: ?>
				<td>微信支付</td><?php endif; ?>
		</tr>
		<tr>
			<?php if($info["pay_type"] == '1'): ?><td width="80">消费月卡：</td>
				<td>1次</td>
			<?php elseif($info["pay_type"] == '2'): ?>
				<td width="80">消费积分：</td>
				<td><?php echo ($info['integral']); ?></td>
			<?php else: ?>
				<td width="80">消费金额：</td>
				<td><?php echo ($info['money']); ?></td><?php endif; ?>
		</tr>
		<tr>
			<td width="80">充电时长：</td>
			<td><?php echo ($info['charge_time']); ?>小时</td>
		</tr>
		<tr>
			<td width="80">充电状态：</td>
			<?php if($info["charge_status"] == '1'): ?><td width="80">充电中</td>
			<?php elseif($info["charge_status"] == '2'): ?>
				<td width="80">已完成</td>
			<?php elseif($info["charge_status"] == '3'): ?>
				<td width="80">电池已充满</td>
			<?php elseif($info["charge_status"] == '4'): ?>
				<td width="80">空载(未连接电源)</td>
			<?php else: ?>
				<td width="80">过载(电池功率过大)</td><?php endif; ?>
		</tr>
		<tr>
			<td width="80">开始时间：</td>
			<td><?php echo date('Y-m-d H:i:s',$info['start_time']);?></td>
		</tr>
		<tr>
			<td width="80">结束时间：</td>
			<td><?php echo date('Y-m-d H:i:s',$info['end_time']);?></td>
		</tr>
	</table>
	<input name="info[typeid]" value="<?php echo ($info["typeid"]); ?>" type="hidden"/>
</form>