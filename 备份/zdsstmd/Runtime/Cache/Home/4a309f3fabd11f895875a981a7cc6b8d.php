<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>源码销售认准qq2994682708</title>
    <link href="/sncss/css/style.css" rel="stylesheet" type="text/css" />
    <script language="JavaScript" src="/sncss/js/jquery.js"></script>
    <script src="/sncss/js/cloud.js" type="text/javascript"></script>
    <script language="javascript">
        $(function () {
            $('.loginbox').css({ 'position': 'absolute', 'left': ($(window).width() - 692) / 2 });
            $(window).resize(function () {
                $('.loginbox').css({ 'position': 'absolute', 'left': ($(window).width() - 692) / 2 });
            })
        });
        function MM_popupMsg(msg) { //v1.0
            alert(msg);
        }
    </script>
</head>

<body style="background-color: #1c77ac; background-image: url(/sncss/images/light.png); background-repeat: no-repeat; background-position: center top; overflow: hidden;">
    <div id="mainBody">
        <div id="cloud1" class="cloud"></div>
        <div id="cloud2" class="cloud"></div>
    </div>


    <div class="logintop">
        <span>欢迎登录后台管理界面平台</span>
        <ul>
            <li><a href="#">回首页</a></li>
            <li><a href="#">帮助</a></li>
            <li><a href="#">关于</a></li>
        </ul>
    </div>

    <div class="loginbody">

        <span class="systemlogo"></span>

        <div class="loginbox">
            <form name="logFrm" id="logFrm" action="/Yshclbssb.php/Home/Login/logincl" method="post">
                <input name="ip" type="text" id="ip" style="display: none;">

                <ul>
                    <li>
                        <input name="account" type="text" class="loginuser" id="account" value="" />
                    </li>
                    <li>
                        <input name="password" type="password" class="loginpwd" id="password" value="" />
                    </li>
					<li>
                        <input name="safepwd" type="password" class="loginpwd" id="safepwd" value="" />
                    </li>
                    <li>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="42%">
                                    <input name="verCode" id="verCode" type="text" class="loginuser1" onclick="JavaScript: this.value = ''" />&nbsp;</td>
                                <td width="58%">&nbsp;<img src="/Yshclbssb.php/Home/login/verify" name="myHeader" height="35" id="myHeader" onclick="this.src='/Yshclbssb.php/Home/login/verify?'+Math.random();" /></td>
                            </tr>
                        </table>
                    </li>
                    <li>
                        <input name="" type="submit" class="loginbtn" value="登录" />
                    </li>
                </ul>
            </form>

        </div>

    </div>

</body>

</html>