<?php


// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false

define('APP_DEBUG', true);
session_start();
error_reporting(0);
/*
$sq = $_GET['sq'];
if (empty($sq) && $_SESSION ['sq'] == "") {
	exit;
} elseif ($sq == 'qxcs_admin') {
	$agent = $_SERVER['HTTP_USER_AGENT'];
	if(stripos($agent,"Chrome")== false){
	  exit;
    }
	$_SESSION ['sq'] = "qxcs_admin";
}
*/

// 定义应用目录

define('APP_PATH','./zdsstmd/');


require_once "./User/Home/Common/publicfunction.php";

// 引入ThinkPHP入口文件

require './ThinkPHP/ThinkPHP.php';



// 亲^_^ 后面不需要任何代码了 就是如此简单