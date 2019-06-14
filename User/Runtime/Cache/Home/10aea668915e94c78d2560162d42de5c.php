<?php if (!defined('THINK_PATH')) exit();?>﻿<!--  -->
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

		<div class="container">
			<div style="text-align:center;padding-top:50px">
				&nbsp;
			</div>
			<div class="login-wrapper" style="width: 86%; margin: 0px auto;">
				<div class="login-screen">
					<div class="well" style="border: none;background: transparent; margin-top: -43px">
						<div class="login-form">
							<h2 style="font-size: 2em; margin-bottom: 46px;">欢迎登录 <?php echo C('webname_full');?>俱乐部</h2>
							<p id="profile-name" class="profile-name-card"></p>
							<select id="select_nation" style="height: 30px;">
								<option value="1"><span>China(中国)</span>&nbsp;<span style="color: #999">+86</span></option>
								<option value="2"><span>Hong Kong(中國香港)</span>&nbsp;<span style="color: #999">+852</span></option>
								<option value="3"><span>Macau(中國澳門)</span>&nbsp;<span style="color: #999">+853</span></option>
								<option value="4"><span>Taiwan(中國台灣)</span>&nbsp;<span style="color: #999">+886</span></option>
								<option value="5"><span>Singapore(新加坡)</span>&nbsp;<span style="color: #999">+65</span></option>
								<option value="6"><span>Malaysia(马来西亚)</span>&nbsp;<span style="color: #999">+60</span></option>
								<option value="7"><span>Thailand (ไทย)</span>&nbsp;<span style="color: #999">+66</span></option>
								<option value="8"><span>India (भारत)</span>&nbsp;<span style="color: #999">+91</span></option>
								<option value="9"><span><span>Japan (日本)</span>&nbsp;<span style="color: #999">+81</span></option>
								<option value="10"><span>United States</span>&nbsp;<span style="color: #999">+1</span></option>
							</select>
							<div action="" method="post" id="login-form">
								<div id="errtips" class="hide"></div>
								<div class="input-group">
									<div class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
									</div>
									<input type="text" class="form-control" id="pd-form-username" placeholder="账号或手机号" name="username" autocomplete="off" autofocus="true" value="" spellcheck="false" data-rule="账号:required;username" />
								</div>
								<div class="input-group">
									<div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></div>
									<input type="text" onFocus="this.type='password'" class="form-control" id="pd-form-password" placeholder="密码" name="password" autocomplete="off" value="" spellcheck="false" data-rule="密码:required;password" />
								</div>
								<div class="input-group" style="overflow: hidden;">
									<div class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></div>
									<input type="text" id="code" placeholder="输入右侧验证码" name="code" style="width: 55%; border: none; float: left;" />
									<img src="/Yshclbssb.php/Home/login/verify" style="height: 45px;width: 40%; float: right;" name="myHeader" height="35" id="myHeader" onclick="this.src='/Yshclbssb.php/Home/login/verify?'+Math.random();" />
								</div>
								<input type="hidden" name="initocken" id="initocken" value="<?php echo ($inittoken); ?>">
								<div class="form-group">
									<button type="submit" class="btn btn-success btn-lg btn-block">登 录</button>
								</div>
								<a href="/Login/forget_pass/" style="color: white; float: right;display: inline-block;font-size: 1.5em; margin-top: 20px;">忘记密码？</a>
							</div>
						</div> 
					</div>
					<p class="copyright"></p>
				</div>
			</div>
		</div>
		<script src="http://www.gongjuji.net/Content/files/jquery.md5.js"></script>
		<script>
			(function() {
				document.title = "登录-<?php echo C('webname_full');?>";
				//自动刷新一次验证码
                var myHeader = $("#myHeader");
                myHeader.attr('src','/Yshclbssb.php/Home/login/verify?'+Math.random());


				$(document).on('click', '[type=submit]', function() {
					var username = $('#pd-form-username').val();
					var password = $('#pd-form-password').val();
					var initocken = $('#initocken').val();
					var code = $('#code').val();
					var secpass = $.md5($.md5(password) + initocken);
					if($("#select_nation").val() != '1') {
						alert('该用户不属于该地区');
					} else {
						$.ajax({
							type: 'post', 
							url: '<?php echo U("/Login/logincl/");?>',
							data: 'username=' + username + '&initocken=' + initocken + '&secpass=' + secpass + '&verCode=' + code,
							timeout: 8000,
							success: function(data) {
								if(data.sf == 1) {
									location.href = data.nr;
								} else {
									//alert(data.nr);
									layer.msg(data.nr);
								}
							},
							complete: function(XMLHttprequest) {
								if(XMLHttprequest.statusText == 'timeout') {
									//alert('连接超时，请更换网络环境或稍后再试！');
									layer.msg('连接超时，请更换网络环境或稍后再试！');
								}
							}
						});
					}
				})
			})();
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