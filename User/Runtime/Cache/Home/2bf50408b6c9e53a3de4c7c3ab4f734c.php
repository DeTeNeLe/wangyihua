<?php if (!defined('THINK_PATH')) exit();?><!--  -->
<!DOCTYPE html>
<html lang="zh-cmn-Hans">

	<head>
		<meta name="keywords" content="富怡,互助源码,理想家园,预付款,抢单互助,源码下载">
		<meta name="description" content="定制开发：富怡,互助源码,理想家园,预付款,抢单互助,源码下载,站长源码交易网是国内优秀的源码交易网站，一个交易安全有保障的网站源码交易平台，提供各种网站源码，微信源码，棋牌源码等各种源码交易，安全快捷的站长源码交易、出售、求购、交流分享平台">
		<meta charset="UTF-8">
		<title>富怡</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
		<meta name="renderer" content="webkit">
		<link rel="shortcut icon" href="/assets/wns/img/favicon.ico" />
		<link rel="stylesheet" href="/assets/wns/css/layui.css">
		<link rel="stylesheet" href="/assets/wns/css/backend.css">
		<link rel="stylesheet" href="/assets/wns/css/bootstrap.min.css">
		<link rel="stylesheet" href="/assets/wns/css/skins/skin-blue.css">

		<script src="/assets/wns/js/jquery.min.js"></script>
		<script src="/assets/wns/js/jquery.slimscroll.min.js"></script>
		<script src="/assets/wns/js/bootstrap.min.js"></script>
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
		<!--[if lt IE 9]>
  <script src="/assets/wns/js/html5shiv.js"></script>
  <script src="/assets/wns/js/respond.min.js"></script>
<![endif]-->
		<script src="/assets/wns/js/layui.all.js"></script>
	</head>

	<body class="hold-transition skin-blue sidebar-mini fixed" id="tabs" style="background-image: url('/assets/img/login_bg.png');">
		<span class="btn btn-primary" style="display: inline-block;font-size: 1.4em;margin:20px;" onclick="goback()">
	   	<i class="fa fa-chevron-circle-left"></i>返回
	    <script>
			function goback(){
				window.history.back();
			}
			(function(){
				$(document).on('click','#tglj',function(){
					var o = $(this).prev('input');
					o.select();
					document.execCommand("copy",false,null);
					layer.tips('已复制至剪贴板',o);
				})
			})();
		</script>
	</span>
		<div class="container">
			<div style="text-align:center;padding-top:50px">
				&nbsp;
			</div>
			<div class="login-wrapper" style="    width: 86%; margin: 0px auto;">
				<div class="login-screen">
					<div class="well" style="border: none;background: transparent;">
						<div class="login-form">
							<p id="profile-name" class="profile-name-card"></p>
							<form action="/Login/amend_pass" method="post">
								<div action="" method="post" id="login-form">
									<div id="errtips" class="hide"></div>
									<div class="input-group">
										<div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
										</div>
										<input type="text" class="form-control" id="username" placeholder="请输入账号" name="username" autocomplete="off" autofocus="true" value="" spellcheck="false" data-rule="账号:required;username" />
									</div>
									<div class="input-group">
										<div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
										</div>
										<input type="text" class="form-control" id="mobile" placeholder="请输入手机号" name="mobile" autocomplete="off" autofocus="true" value="" spellcheck="false" data-rule="账号:required;username" />
									</div>
									<!--<div class="input-group" style="overflow: hidden;">
									<div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></div>
									<input type="text" id="code" placeholder="输入验证码" name="code" style="width: 55%; border: none; float: left;" />
									<input type="button" id="code" value="获取验证码" name="code" style="width: 40%;height: 45px; float: right;" />
								</div>-->
									<div class="input-group" style="overflow: hidden;">
										<input type="text" id="phone_check" class="form-control" style="width: 60%; float: left;" placeholder="输入手机验证码" value="<?php echo ($_SESSION['CHECK_CODE']); ?>" />
										<input id="btn-sendsms" type="button" class="btn btn-primary submit form-control" value="获取手机验证码" style="float: right; background-color: #3B8CFF;    font-size: 1.2em;
    height: 3.21em; width: 40%;" />
									</div>
									<input type="hidden" name="initocken" id="initocken" value="<?php echo ($inittoken); ?>">
									<div class="form-group">
										<!--<a href="/Login/amend_pass/" type="submit" class="btn btn-success btn-lg btn-block">下一步</a>-->
										<button type="submit" class="btn btn-success btn-lg btn-block">下一步</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<p class="copyright"></p>
				</div>
			</div>
		</div>
		<script src="http://www.gongjuji.net/Content/files/jquery.md5.js"></script>
		<script>
			var wait = 60;
			$(document).on('click', '#btn-sendsms', function() {
				var o = $(this);
				var ini = wait;
				var username = $('#username').val();
				var mobile = $('#mobile').val();
				var reg = /^0?1\d{10}$/;
				if(!reg.test(mobile)) {
					layer.msg('手机号错误！');
					return;
				}
				if(username == "") {
					layer.msg('请先填写账号！');
					return;
				}
				var p = setInterval(function() {
					if(wait == ini) {
						o.attr('disabled', 'disabled');
						$.ajax({
							type: 'post',
							url: '/Login/sendCodeToPhone',
							data: 'mobile=' + mobile + '&username=' + username,
							timeout: 7000,
							async: true,
							success: function(data) {
								if(data.sf == 1) {
									layer.tips('短信发送成功', '.btn-sendsms');
								} else {
									layer.alert('错误信息:' + data.nr, {
										title: '短信发送失败'
									})
								}
							},
							complete: function(XMLHttprequest) {
								if(XMLHttprequest.statusTest == 'timeout') {
									layer.msg('连接超时,请更换网络环境或稍后再试!');
								}
							}
						});
					}
					o.val(wait + 's后可再次发送...');
					wait--;
					if(wait == 0) {
						o.removeAttr('disabled').val('发送短信');
						wait = ini;
						clearInterval(p);
					}
				}, 1000);
			})
		</script>
		<style type="text/css">
			body {
				background: url('/assets/wns/img/userloginbg.jpg');
				background-size: cover;
			}
			
			h2 {
				text-align: center;
				color: white;
				text-shadow: 0 1px 2px rgba(0, 0, 0, .4);
				font-weight: bold;
				letter-spacing: 2px !important;
			}
			
			.container {
				padding: 5px;
			}
			
			@media (min-width: 768px) {
				.container {
					width: 400px;
				}
				.login-screen .well {
					padding: 10px 15px !important;
				}
			}
			
			.login-panel {
				margin-top: 150px;
			}
			
			.login-screen {
				padding: 0;
				margin: 30px auto 0 auto;
			}
			
			.login-screen .well {
				border-radius: 3px;
				-webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
				background: rgba(255, 255, 255, 0.1);
				padding: 5px;
			}
			
			.login-screen .copyright {
				text-align: center;
			}
			
			@media(max-width:767px) {
				.login-screen {
					padding: 0;
				}
			}
			
			#login-form {
				margin-top: 20px;
			}
			
			#login-form .input-group {
				margin-bottom: 20px;
			}
			
			#login-form .input-group input,
			#login-form button {
				font-size: 1.5em;
				line-height: 2.5em;
				height: 2.5em;
				padding: 0 5px;
			}
		</style>

		<!--
<script src="http://qxhlsoft.oss-cn-hangzhou.aliyuncs.com/WebTools/floatservice/xfgw/service.js"></script>
-->

	</body>

</html>