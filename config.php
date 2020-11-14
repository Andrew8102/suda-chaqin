<?php
//数据库连接配置文件
define('DB_HOST','localhost');
define('DB_USER','填写用户');
define('DB_PASS', '这里填写密码');
define('DB_NAME', '数据库名称');
//定义后可以直接引用
//项目配置文件
return [
	//数据库连接信息
	'DB_CONNECT' => [
		'host' => 'localhost',	  //服务器地址
		'user' => '这里填写用户',		  //用户名
		'pass' => '这里填写密码',		  //密码
		'dbname' => '数据库名称', //默认数据库
		'port' => '3306',		  //端口
	],
	'DB_CHARSET' =>	'utf8mb4',		//字符集
];


?>