<?php
return array(
	'FILE_UPLOAD_TYPE'      => 'Local',      //上传驱动
	'AUTOLOAD_NAMESPACE'    => array('Addons'=> SITE_DIR.'/Addons'),
	'LOAD_EXT_CONFIG'       => 'file',
		/* URL设置 */
	'MODULE_ALLOW_LIST'     => array('Home', 'Wcat','Admin', 'Install'),
	'DEFAULT_MODULE'        => 'Wcat',       // 默认模块
	'URL_CASE_INSENSITIVE'  => false,         // 默认false 表示URL区分大小写 true则表示不区分大小写
	'URL_MODEL'             => 2,            // URL模式
		'DEBUG_MODE'         => false,
		'URL_HTML_SUFFIX'=>'.html',
		//是否在页面中显示调试信息
		'SHOW_PAGE_TRACE' => false,
		'DB_TYPE' => 'mysql',
		'DB_HOST' => '47.92.130.200',
		'DB_NAME' => 'charge',
		'DB_USER' => 'root',
		'DB_PWD'  => 'Xiangshihotel123',
		'DB_PORT' => '3306', // 端口
		'DB_PREFIX' => '',
		'DB_CHARSET'      => 'utf8',
		'DB_HOST1' => '127.0.0.1',
		'DB_PWD1'  => 'root',
	    //缓存配置
		//'DATA_CACHE_TYPE'     => 'Memcached',
		//MD5时的密钥
		'MD5_KEY'             => 'fdDFA(3-GRT49#ER;FDP23278dl32@@@12__dke22',
		'DEFAULT_FILTER'      => 'trim,htmlspecialchars',
		'IMAGE_CONFIG' => array(
			'htmlPath' => '/Public/Uploads/', 
			'rootPath' => 'Public/Uploads/',
			'maxSize'  => 3145728,
			'exts' => array('jpg', 'gif', 'png', 'jpeg')
		),
	    'wechat_config' => array(
	    	'appid'	     => 'wx57939dd3f5293448',
			'secret'     => '90967ce98d888d0a42818c5d884c036f'
		),
		/**
		 * 百度地图api配置 
		 * ret_coordtype 可选数值 添加后返回国测局经纬度坐标或百度米制坐标 gcj02ll（国测局坐标）、bd09mc（百度墨卡托坐标）
		 * 详情：http://lbsyun.baidu.com/index.php?title=webapi/guide/webservice-geocoding
		*/
		'baidu_lbsyun'=>array(
			'ak'=>'SHodWgIobBdFAviOq7uyuGc5LUhSeeoO',
			'ret_coordtype'=>'gcj02ll',

		),
		/* 模板标签设置 */
	'TMPL_L_DELIM'          => '{',         // 模板引擎普通标签开始标记
		'TMPL_R_DELIM'          => '}',         // 模板引擎普通标签结束标记
		/* 模板解析设置 */
	'TMPL_PARSE_STRING'     => array(
		'./Public/upload/'  => SCRIPT_DIR . '/Public/upload/',
		'__PUBLIC__'        => SCRIPT_DIR . '/Public',
		'__STATIC__'        => SCRIPT_DIR . '/Public/static',
		'__VERSION__'       => date('YmdHi'),
	),
	/* 邮箱配置 */
	'EMAIL_CONFIG'          => array(
		'smtp'     => 'smtp.qq.com',
		'port'     => 25,
		'from'     => '531381545@qq.com',
		'user'     => '531381545@qq.com',
		'password' => '',
		'report'   => '644601935@qq.com', //报警接收邮箱
	),

	/* 水印配置 */
	'IMAGE_WATER_CONFIG'    => array(
		'status'   => 0,         //状态
		'type'     => 0,         //模式 1为图片 0为文字
		'text'     => 'EASYTP',  //水印文字
		'image'    => './Public/static/img/logo.png',  //水印图片
		'position' => 9,         //九宫格位置
		'x'        => -5,        //x轴偏移
		'y'        => -5,        //y轴偏移
		'size'     => 30,        //水印文字大小
		'color'    => '#305697', //水印文字颜色
	),

	/* 接口设置 */
	'API_SIGN'              => '04B29480233F4DEF5C875875B6BDC3B1', //接口签名
);