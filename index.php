<?php
//sleep(rand(3,10));

//exit;
//header('Location: /updating.html');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false

define('APP_DEBUG',true);

// 定义应用目录 

define('APP_PATH','./User/');

if(strstr(dirname(__FILE__),"\\"))
	define('_ABS_ROOT_',str_replace("\\","/",dirname(__FILE__))."/");
else
	define('_ABS_ROOT_',dirname(__FILE__)."/");
define('_ABS_APP_ROOT_',_ABS_ROOT_.'User/');
define('APP_NAME', 'User');

require_once "./User/Home/Common/publicfunction.php";

// 引入ThinkPHP入口文件

require './ThinkPHP/ThinkPHP.php';



// 亲^_^ 后面不需要任何代码了 就是如此简单