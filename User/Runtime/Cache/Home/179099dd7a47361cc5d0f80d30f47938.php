<?php if (!defined('THINK_PATH')) exit();?>﻿

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
<meta charset="UTF-8">
<title>注册</title>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
<meta name="renderer" content="webkit">
<link rel="stylesheet" href="/assets/wns/css/layui.css">
<link rel="stylesheet" href="/assets/wns/css/bootstrap.min.css">
<script src="/assets/wns/js/jquery.min.js"></script>
<script src="/assets/wns/js/bootstrap.min.js"></script>
<script src="/assets/wns/js/layui.all.js"></script>

<!--[if lt IE 9]>
	  <script src="/assets/wns/js/html5shiv.js"></script>
	  <script src="/assets/wns/js/respond.min.js"></script>
	<![endif]-->
<style>
		html,body{margin: 0;padding: 0;width: 100%;height: 100%;}
		body{background: url(/assets/wns/img/newbg.jpg)center center no-repeat; background-size:cover;position: absolute;width: 100%;height: 100%;background-attachment: fixed;}
		.page{width: 40%;position: relative; margin:0 auto;min-height: 100%;background: rgba(255,255,255,.1);}
		.maincon{margin: 0 auto;width: 100%;background: rgba(255,255,255,.8);font-size: 2em;min-height: 400px;padding: 10px;vertical-align: middle;position: absolute;top: 120px;margin-bottom: 30px;}
		.error{text-align: center;color: red;line-height: 400px}
		.maincon .title{text-align: center;padding: 15px 0}
		.maincon input{font-size: 1em;height: 40px;line-height: 40px;}
		.maincon .btn-sendsms , .maincon .mobile-code{width: 50%;display: inline-block;margin: 0;float: left;	padding: 0 5px;}
		.maincon .btn-sendsms{font-size: .85em;}
		.maincon .submit{padding: 0;}
		.maincon .invitor{font-size: .7em}
		.maincon input[type=checkbox]{width: 30px;height: 30px;vertical-align: bottom}
		.maincon input[type=checkbox] + span{font-size: .8em;}
		@media screen and (max-width: 767px) {
			.page{width: 94%}
		}
	</style>
</head>
<body class="body">
<div class="page">
<div class="maincon">
<p class='error'>注册链接错误</p> </div>
</div>
</body>
</html>