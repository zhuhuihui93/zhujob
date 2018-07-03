<?php if (!defined('THINK_PATH')) exit(); if(IS_AJAX) exit((isset($message) ? $message : $error)); ?>
<!DOCTYPE html>
<html>
<head lang="zh-CN">
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<meta name="robots" content="none">
	<meta http-equiv="Pragma" content="no-cache">
	<meta name="Author" Content="YITI">
	<title><?php echo strip_tags(C('SYSTEM_NAME'));?></title>
	<link rel="shortcut icon" href="/Public/static/favicon.ico?201806110536" />
	<link rel="stylesheet" type="text/css" href="/Public/static/js/semantic/semantic.min.css?201806110536">
	<script src="/Public/static/js/jquery.min.js?201806110536"></script>
	<script src="/Public/static/js/semantic/semantic.min.js?201806110536"></script>
	<style type="text/css">
		body {
			background-color: #EEEEEE;
		}
		body > .grid {
			height: 100%;
		}
		.column {
			max-width: 450px;
		}
	</style>
	<script>
		var href = '<?php echo($jumpUrl); ?>';
		$(document).ready(function(){
			$('.message').on('click', function(){
				location.href = href;
			});
			var wait = document.getElementById('wait');
			var interval = setInterval(function(){
				var time = --wait.innerHTML;
				if(time <= 0) {
					location.href = href;
					clearInterval(interval);
				};
			}, 1000);
		});
	</script>
</head>
<body>

<div class="ui middle aligned center aligned grid">
	<div class="column left aligned">
		<div class="ui icon message">
			<i class="notched circle loading icon"></i>
			<div class="content">
				<div class="header"><?php echo (isset($message) ? $message : $error) ?></div>
				<p>页面自动跳转，等待时间： <b id="wait"><?php echo($waitSecond); ?></b></p>
			</div>
		</div>
	</div>
</div>

</body>

</html>