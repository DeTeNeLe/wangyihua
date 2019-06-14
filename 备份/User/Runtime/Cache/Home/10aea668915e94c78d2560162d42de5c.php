<?php if (!defined('THINK_PATH')) exit();?>﻿
<!--  -->
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head><meta name="keywords" content="LX世纪商城,互助源码,理想家园,预付款,抢单互助,源码下载">
<meta name="description" content="定制开发：LX世纪商城,互助源码,理想家园,预付款,抢单互助,源码下载,站长源码交易网是国内优秀的源码交易网站，一个交易安全有保障的网站源码交易平台，提供各种网站源码，微信源码，棋牌源码等各种源码交易，安全快捷的站长源码交易、出售、求购、交流分享平台">
<meta charset="UTF-8">
<title>LX世纪商城,互助源码,理想家园,预付款,抢单互助,源码下载,源码销售认准qq2994682708</title>
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
</head>
<body class="hold-transition skin-blue sidebar-mini fixed" id="tabs">

<div class="container">
    <div style="text-align:center;padding-top:50px">
	  &nbsp;
	</div>
    <div class="login-wrapper">
        <div class="login-screen">
            <div class="well">
                <div class="login-form">
                    <h2>欢迎登录 <?php echo C('webname_full');?></h2>
                    <p id="profile-name" class="profile-name-card"></p>
                    
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
                        <input type="hidden" name="initocken" id="initocken" value="<?php echo ($inittoken); ?>">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-lg btn-block">登 录</button>
                        </div>
                    </div>
                </div>
            </div>
            <p class="copyright"></p>
        </div>
    </div>
</div>
<script src="http://www.gongjuji.net/Content/files/jquery.md5.js"></script>
<script>
	(function(){
		document.title="登录-<?php echo C('webname_full');?>";
		$(document).on('click','[type=submit]',function(){
			var username = $('#pd-form-username').val();
			var password = $('#pd-form-password').val();
			var initocken = $('#initocken').val();
			var secpass = $.md5($.md5(password)+initocken);
			$.ajax({
				type:'post',
				url:'/Home/Login/logincl.html',
				data:'username='+username+'&initocken='+initocken+'&secpass='+secpass,
				timeout:8000,
				success:function(data){
					if (data.sf==1) {
						location.href=data.nr;
					}else{
                        alert(data.nr);
                    }
				},
				complete:function(XMLHttprequest){
					if (XMLHttprequest.statusText=='timeout') {
						alert('连接超时，请更换网络环境或稍后再试！');
					}
				}
			});
		})
	})();
</script>
<style type="text/css">
    body {
        background:url('/assets/wns/img/userloginbg.jpg');
        background-size:cover;
    }
    h2{
        text-align: center;color: white;text-shadow: 0 1px 2px rgba(0,0,0,.4);font-weight: bold;letter-spacing:2px !important;
    }
    .container{
        padding: 5px;
    }
    @media (min-width: 768px){
        .container{
            width: 400px;
        }
        .login-screen .well{padding: 10px 15px !important;}
    }
    .login-panel{margin-top:150px;}
    .login-screen {
        padding:0;
        margin:30px auto 0 auto;

    }
    .login-screen .well {
        border-radius: 3px;
        -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background: rgba(255,255,255, 0.1);
        padding: 5px;
    }
    .login-screen .copyright {
        text-align: center;
    }
    @media(max-width:767px) {
        .login-screen {
            padding:0 ;
        }
    }

    #login-form {
        margin-top:20px;
    }
    #login-form .input-group {
        margin-bottom:20px;
    }
    #login-form .input-group input , #login-form button{
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