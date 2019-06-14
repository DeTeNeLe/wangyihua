<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html lang="zh-cmn-Hans">
 <head> 
  <meta charset="UTF-8" /> 
  <title>注册</title> 
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" /> 
  <meta name="renderer" content="webkit" /> 
  <link rel="stylesheet" href="/assets/wns/css/layui.css" /> 
  <link rel="stylesheet" href="/assets/wns/css/bootstrap.min.css" /> 
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
      <div class="form-control" style="font-size:.6em;text-align:center"><input style="width:280px;padding:0 5px;" value="<?php echo ($tgurl); ?>"><input id="tglj" type="button" value="复制推广链接"></div>
   <div class="maincon"> 
    <ul> 
     <li class="title">注册成为<?php echo C('webname_full');?>商城会员</li> 
	 <li> <label class="control-label">基本信息(必填)：</label>
	 <li> <input type="text" placeholder="邀请人" class="form-control invitor" value="邀请人:<?php echo ($accname); ?>(<?php echo (replace_xing($phone)); ?>,<?php echo (replace_xing($account)); ?>)" disabled="disabled" /> </li> 
     <li> <input type="text" id="nickname" class="form-control" placeholder="姓名(昵称)" /> </li> 
     <li> <input type="text" id="username" class="form-control" placeholder="邮箱(账号)" /> </li> 
     <li> <input type="text" id="pwd" class="form-control" placeholder="密码" onfocus="this.type='password';" /> </li> 
     <li> <input type="text" id="pwdrepeat" class="form-control" placeholder="确认密码" onfocus="this.type='password';" /> </li> 
	 <li> <input type="text" id="mobile" class="form-control" placeholder="手机号" value="<?php echo ($_SESSION['PHONE_NUM']); ?>"/> </li>
	 <?php if(C('sms_open_reg') == '1'): ?><li> <input type="text" id="phone_check" class="form-control" placeholder="输入手机验证码" value="<?php echo ($_SESSION['CHECK_CODE']); ?>" /> 
	 <input id="btn-sendsms" type="button" class="btn btn-primary submit form-control" value="获取手机验证码" style="background-color: #628cb7;"/></li><?php endif; ?>
     <br /> 
     <li> <label for="bankname" class="control-label">绑定银行卡信息(选填)：</label> <select class="form-control" id="bankname"> <option value="请选择银行">请选择银行</option> <option value="工商银行">工商银行</option> <option value="建设银行">建设银行</option> <option value="中国银行">中国银行</option> <option value="交通银行">交通银行</option> <option value="农业银行">农业银行</option> <option value="招商银行">招商银行</option> <option value="邮政储蓄银行">邮政储蓄银行</option> <option value="光大银行">光大银行</option> <option value="民生银行">民生银行</option> <option value="平安银行">平安银行</option> <option value="浦发银行">浦发银行</option> <option value="中信银行">中信银行</option> <option value="兴业银行">兴业银行</option> <option value="华夏银行">华夏银行</option> <option value="广发银行">广发银行</option> <option value="北京银行">北京银行</option> <option value="其他请备注">其他请备注</option> </select> </li> 
     <li> <input type="text" placeholder="卡号" class="form-control bankaccount" value="" /> </li> 
     <li> <input type="text" placeholder="支行名称" class="form-control bankaddr" value="" /> </li> 
     <li> <input type="text" placeholder="持卡人" class="form-control bankowner" value="" /> </li> 
     <li> <input type="text" placeholder="支付宝" class="form-control alipay" value="" /> </li> 
	 <li> <input type="text" placeholder="微信" class="form-control weixin" value="" /> </li> 
     <li> <input type="text" placeholder="收款备注" class="form-control bankmark" value="" /> </li> 
     <hr /> 
     <li> <input type="checkbox" value="false" /><span>我已读过<a href="#">警告</a>,并完全了解风险的存在并愿意承担可能的风险。</span> </li> 
     <li> <input id="submit" type="button" class="btn btn-primary submit form-control" value="提交注册" /> </li> 
    </ul> 
    <script>
							(function(){
								var $_GET = (function(){
									var u = window.document.location.href.toString().split("?");
									if(typeof(u[1]) == "string"){
										u = u[1].split("&");
										var g = {};
										for(var i in u){
											var j = u[i].split("=");
											g[j[0]] = j[1];
										}
										return g;
									} else {
										return {};
									}
								})();
								$(document).on('change','#username',function(){
									var o = $(this);
									var pattern = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
								    if (!pattern.test(o.val())) {
								        layer.msg('请输入正确的邮箱地址。');
								        o.focus();
								    }
								})
								var wait = 60;
								$(document).on('click','#btn-sendsms',function(){
									var o = $(this);
									var ini = wait;
									var username = $('#username').val();
									var mobile = $('#mobile').val();
									var reg = /^0?1\d{10}$/;
									if (!reg.test(mobile)){
										layer.msg('手机号错误！');
										return;
									}
									if (username == ""){
										layer.msg('请先填写账号！');
										return;
									}
									var p = setInterval(function(){
										if (wait==ini) {
											o.attr('disabled','disabled');
											$.ajax({
												type:'post',
												url:'/Reg/sendPhone',
												data:'mobile='+mobile + '&username='+username,
												timeout:7000,
												async:true,
												success:function(data){
													if (data.sf==1) {
														layer.tips('短信发送成功', '.btn-sendsms');
													}else{
														layer.alert('错误信息:'+data.nr,{title:'短信发送失败'})
													}
												},
												complete:function(XMLHttprequest){
													if (XMLHttprequest.statusTest=='timeout') {
														layer.msg('连接超时,请更换网络环境或稍后再试!');
													}
												}
											});
										}
										o.val(wait+'s后可再次发送...');
										wait--;
										if (wait==0) {
											o.removeAttr('disabled').val('发送短信');
											wait=ini;
											clearInterval(p);
										}
									},1000);
								})
								$(document).on('click','#submit',function(){
									//$(this).attr('disabled','disabled');
									var o = $(this);
									var nickname = $('#nickname').val();
									var username = $('#username').val();
									var mobile = $('#mobile').val();
									var mobilecode = $('.mobile-code').val();
									var pwd = $('#pwd').val();
									var pwdrepeat = $('#pwdrepeat').val();
									var regcode = "<?php echo ($regcode); ?>";
									regcode = regcode.replace(/#/,'');
									var bankname = $('#bankname').val();
									var bankaccount = $('.bankaccount').val();
									var bankowner = $('.bankowner').val();
									var bankaddr = $('.bankaddr').val();
									var alipay = $('.alipay').val();
									var weixin = $('.weixin').val();
									var bankmark = $('.bankmark').val();
									var phone_check = $('#phone_check').val();
									

									if(!$('input:checkbox').is(':checked'))
									{
										layer.msg('请确认你已阅读警告并勾选确认！');
										return;
									}
									if (nickname=='') {
										layer.msg('请输入昵称');
										return;
									}
									var regmail = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
									if (!regmail.test(username)){
										layer.msg('请输入邮箱');
										return;
									}
									if (mobile=='') {
										layer.msg('请输入手机号');
										return;
									}
									var reg = /^0?1\d{10}$/;
									if (!reg.test(mobile)){
										layer.msg('手机号错误！');
										return;
									}
									// if (mobilecode=='') {
									// 	layer.msg('请输入验证码');
									// 	return;
									// }
									if (pwd=='') {
										layer.msg('请输入密码');
										return;
									}
									if (pwdrepeat != pwd) {
										layer.msg('两次输入密码不一致！');
										return;
									}
									if (alipay!='' && bankaccount=='')
									{
										layer.msg('仅填写支付宝的银行信息无效，请补齐银行卡号');
										return;
									}
									if (bankmark!='' && bankaccount=='') {
										layer.msg('请正确填写银行卡信息');
										return;
									}
									if (bankaccount!='' && bankowner=='') {
										layer.msg('请完善银行卡信息');
										return;
									}
									if (bankname=='请选择银行') {
										layer.msg('请选择银行');
										return;
									}
									var str = 'ty=ye&pwdrepeat='+pwdrepeat+'&nickname='+nickname+'&username='+username+'&mobile='+mobile+'&mobilecode='+mobilecode+'&pwd='+pwd+'&regcode='+regcode+'&phone_check='+phone_check;
									if (bankaccount!='') {
										str+='&bankname='+bankname+'&bankaccount='+bankaccount+'&bankowner='+bankowner+'&bankaddr='+bankaddr+'&alipay='+alipay+'&bankmark='+bankmark+'&weixin='+weixin;
									}
									console.log(str);
									o.attr('disabled','disabled');
									$.ajax({
										type:'post',
										url:'/Reg/regadd/',
										data:str,
										timeout:7000,
										success:function(data){
											if (data.sf==1) {
												layer.msg('注册成功,正在为您跳转至登录页面');
												setTimeout(function(){location.href='/'},1000);
											}else{
												layer.alert('错误信息:'+data.nr,{title:'注册失败'});
											}
											o.removeAttr('disabled');
										},
										complete:function(XMLHttprequest){
											if (XMLHttprequest.statusTest=='timeout') {
												layer.msg('连接超时,请更换网络环境或稍后再试!');
											}
										}
									});
								})
							})();
						</script> 
   </div> 
  </div>   
 </body>
</html>