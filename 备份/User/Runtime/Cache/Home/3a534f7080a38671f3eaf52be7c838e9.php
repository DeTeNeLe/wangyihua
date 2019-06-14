<?php if (!defined('THINK_PATH')) exit();?><!-- header start -->
   <!DOCTYPE html>
<html lang="zh-cmn-Hans">
 <head> 
  <meta charset="UTF-8" /> 
  <title>跳转中...</title> 
  <meta name="viewport" content="width=device-width, initial-scale=.8, user-scalable=yes" /> 
  <meta name="renderer" content="webkit" /> 
  <meta http-equiv="Cache-Control" content="public" /> 
  <link rel="shortcut icon" href="/assets/wns/img/favicon.ico" /> 
  <link rel="stylesheet" href="/assets/wns/css/layui.css" /> 
  <link rel="stylesheet" href="/assets/wns/css/backend.css" /> 
  <link rel="stylesheet" href="/assets/wns/css/bootstrap.min.css" /> 
  <link rel="stylesheet" href="/assets/wns/css/skins/skin-blue.css" /> 
  <script src="/assets/wns/js/jquery.min.js"></script> 
  <script src="/assets/wns/js/jquery.slimscroll.min.js"></script> 
  <script src="/assets/wns/js/adminlte.js"></script> 
  <script src="/assets/wns/js/bootstrap.min.js"></script> 
  <script src="/assets/wns/js/layui.all.js"></script> 
  <script src="/assets/js/jquery.countdown.js"></script> 
  <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. --> 
  <!--[if lt IE 9]>
  <script src="/assets/wns/js/html5shiv.js"></script>
  <script src="/assets/wns/js/respond.min.js"></script>
<![endif]--> 
<style>
	div.switch{display: inline-block;height: 40px;margin:20px 0;}
	.help{margin:10px;font-size: 1.1em;}
</style>
 <style>
 .b-page {
    background: #fff;
    box-shadow: 0px 1px 2px 0px #E2E2E2;
}
.page {
    width: 100%;
    padding: 0px 0px 0 px 20px;
    text-align: right;
    overflow: hidden;
}
.page .first,
.page .prev,
.page .current,
.page .num,
.page .current,
.page .next,
.page .end {
    padding: 8px 16px;
    margin: 0px 5px;
    display: inline-block;
    color: #008CBA;
    border: 1px solid #F2F2F2;
    border-radius: 5px;
}
.page .first:hover,
.page .prev:hover,
.page .current:hover,
.page .num:hover,
.page .current:hover,
.page .next:hover,
.page .end:hover {
    text-decoration: none;
    background: #F8F5F5;
}
.page .current {
    background-color: #008CBA;
    color: #FFF;
    border-radius: 5px;
    border: 1px solid #008CBA;
}
.page .current:hover {
    text-decoration: none;
    background: #008CBA;
}
.page .not-allowed {
    cursor: not-allowed;
}
.rows
{
  color:white;
}
 </style>
 </head> 
 <body class="hold-transition skin-blue sidebar-mini fixed" id="tabs"> 
  <div class="wrapper"> 
   <header id="header" class="main-header">
    <a class="logo" style="text-decoration: none"> <span class="logo-mini"><?php echo C('webname_full');?></span> <span class="logo-lg"><b><?php echo C('webname_full');?></b></span> </a> 
    <nav class="navbar navbar-static-top"> 
     <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" style="width: auto;text-decoration: none;font-size: 2em;padding: 8px;background: rgba(0,0,0,.2);z-index: 999"> <span class="sr-only">菜单</span><span>菜单</span> </a> 
     <div id="nav" class="pull-left"> 
      <ul class="nav nav-tabs nav-addtabs disable-top-badge" role="tablist"> 
      </ul> 
     </div> 
     <div class="navbar-custom-menu"> 
      <ul class="nav navbar-nav"> 
       <li> <a style="font-size:1.2em;padding-left: 0;padding-right: 0;cursor: pointer;" class="kefuwechat" data="源码销售认准qq2994682708">源码销售qq2994682708专属客服:<span style="display: inline-block;border:2px solid red">小玲&nbsp;&nbsp;&nbsp;</span></a> &nbsp;&nbsp;&nbsp;</li> 
       <li> <a href="/Home/Login/logout.html" class="btn btn-danger" style="height: 50px;border: none;font-size: 1.4em"><i class="fa fa-sign-out"></i> <span>退出</span></a> </li> 
      </ul> 
     </div> 
    </nav> 
   </header> 
   <aside class="main-sidebar" style="background: #000;border-right: 1px solid rgba(255,255,255,.1);font-size: 1.8em;"> 
    <section class="sidebar"> 
     <ul class="sidebar-menu"> 
      <li class="<?php echo ($home_active); ?>">
	    <a href="/?o" url="/?o" py="kb" pinyin="shouye">
	    <i class="fa fa-dashboard"></i> <span>首页</span> 
	    <span class="pull-right-container"></span>
		</a>
	  </li>
	  <li class="<?php echo ($getjhm_active); ?> " <?php if($userData['ue_check'] != 0): ?>style='display:none'<?php endif; ?>>
	    <a href="/Home/Index/getjhm.html" url="/Home/Index/getjhm.html" py="xw" pinyin="tuandui"><i class="fa fa-users"></i> <span>抢激活码</span> <span class="pull-right-container"> </span></a> 
	  </li>
      <li class="<?php echo ($team_active); ?>">
	    <a href="/Home/Myuser/index.html" url="/Home/Myuser/index.html" py="xw" pinyin="tuandui"><i class="fa fa-users"></i> <span>团队</span> <span class="pull-right-container"> </span></a> 
	  </li> 
      <li class="<?php echo ($pinfo_active); ?>">
	    <a href="/Home/Info/index.html" url="/Home/Info/index.html" py="xw" pinyin="gerenshezhi"><i class="fa fa-cogs"></i> <span>个人设置</span> <span class="pull-right-container"> </span></a> 
	  </li> 
      <li class="<?php echo ($wallet_active); ?>">
	    <a href="/Info/wallet" url="/Info/wallet" py="xw" pinyin="qianbao"><i class="fa fa-rmb"></i> <span>钱包</span> <span class="pull-right-container"> </span></a> 
	  </li>
	  <li class="<?php echo ($pdm_active); ?>">
	    <a href="/Info/paidan" url="/Info/paidan" py="xw" pinyin="qianbao"><i class="fa fa-rmb"></i> <span><?php echo C('pdm_name');?></span> <span class="pull-right-container"> </span></a> 
	  </li>
	  <li class="<?php echo ($jhm_active); ?>">
	    <a href="/Info/jhm" url="/Info/jhm" py="xw" pinyin="qianbao"><i class="fa fa-rmb"></i> <span><?php echo C('jhm_name');?></span> <span class="pull-right-container"> </span></a> 
	  </li>
      <li  class="<?php echo ($jifen_active); ?>">
	    <a href="/Info/jifen" url="/Info/jifen" py="jf" pinyin="jifen"><i class="fa fa-money"></i> <span><?php echo C('jifen_wallet_name');?></span> <span class="pull-right-container"> </span></a>
	  </li> 
      <li>
	    <a href="<?php echo ($tgurl); ?>" url="<?php echo ($tgurl); ?>" py="xw" pinyin="zhucexinhuiyuan"><i class="fa fa-user-plus"></i> <span>注册新会员</span> <span class="pull-right-container"> </span></a> </li> 
      <li  class="<?php echo ($shopjifen_active); ?>">
	    <a href="/Info/shopjifen" url="/Info/shopjifen" url=""><i class="fa fa-shopping-cart"></i> <span><?php echo C('shopjifen_wallet_name');?></span> <span class="pull-right-container"> </span></a> 
	  </li> 
      <li>
	    <a href="javascript:alert('模块开发中');" url=""><i class="fa fa-balance-scale"></i> <span>积分交易系统</span> <span class="pull-right-container"> </span></a>
	  </li> 
     </ul> 
    </section> 
   </aside> 

   <script>
    var layer = layui.layer;
	(function(){
		$(document).on('click','.sidebar-menu li',function(){
			$('.sidebar-menu li').removeClass('active');
			$(this).addClass('active');
		})

        //全屏事件
        $(document).on('click', "[data-toggle='fullscreen']", function () {
            var doc = document.documentElement;
            if ($(document.body).hasClass("full-screen")) {
                $(document.body).removeClass("full-screen");
                document.exitFullscreen ? document.exitFullscreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitExitFullscreen && document.webkitExitFullscreen();
            } else {
                $(document.body).addClass("full-screen");
                doc.requestFullscreen ? doc.requestFullscreen() : doc.mozRequestFullScreen ? doc.mozRequestFullScreen() : doc.webkitRequestFullscreen ? doc.webkitRequestFullscreen() : doc.msRequestFullscreen && doc.msRequestFullscreen();
            }
        });
        var sss = location.href.split(location.host+'/')[1];
        if ($.inArray(sss,['team','setting','wallet','jifen','deal'])>-1) {
            $('.sidebar-menu li').removeClass('active');
            $(document).find('[href=\'/'+sss+'\']').parents('li').addClass('active');
        }
        $(document).on('click','.kefuwechat',function(){
            var wechat = $(this).attr('data');
            layer.open({
                type:1,
                content:'<div style=\'padding:30px;font-size:1.2em;text-align:center\'>源码销售认准qq2994682708 <span style=\'display:inline-block;border:1px solid #555 ;padding:2px 4px\'>'+wechat+'</span><br><br><span style=\'font-size:.85em;color:#777\'>请仔细核对qq号，防止骗子!</span></div>',
                title:'注意'
            })
        })
	})();
</script> 
   <style>
    div.maincon{width: 100%;text-align: center;margin: 20px auto;}
    .contitle{text-align: left;background: rgba(255,255,255,.8);padding: 0.5em 1em;border-bottom: 3px solid #3c8dbc;font-size: 1.2em;margin-top: 10px;font-weight: bold}
    .content-wrapper{background: url('/assets/wns/img/newbg.jpg') 50% 50% repeat #000;background-size: contain;}
    table.table td , table.table th{text-align: center;}
    .table tr:nth-child(odd){background: rgba(0,0,0,.03);}
    .table tr:hover{background: rgba(255,255,255,.4);}
    .skin-blue .main-header .navbar{}
    .skin-blue .main-header .logo{}
    @media (max-width: 767px) {
        .skin-blue .main-header .navbar{background-size: cover;}
    }
</style>  
<!-- header end -->
<style>
    div.maincon{width: 94%;text-align: center;margin: 20px auto;}
    .contitle{text-align: left;background: rgba(255,255,255,.8);padding: 0.5em 1em;border-bottom: 3px solid #3c8dbc;font-size: 1.2em;margin-top: 10px;font-weight: bold}
    .content-wrapper{background: url('assets/img/newbg.jpg') 50% 50% repeat #000;background-size: contain;}
    table.table td , table.table th{text-align: center;}
    .table tr:nth-child(odd){background: rgba(0,0,0,.03);}
    .table tr:hover{background: rgba(255,255,255,.4);}
    .skin-blue .main-header .navbar{}
    .skin-blue .main-header .logo{}
    @media (max-width: 767px) {
        .skin-blue .main-header .navbar{background-size: cover;}
    }
</style>
<div class="content-wrapper tab-content tab-addtabs">
	<div role="tabpanel" class="tab-pane active" style="overflow: auto">
		<div class="maincon">
			<div id="settingpanel">
				<div class="contitle active">
					基本信息
				</div>
				<div>
					<table class="table">
						<tr>
							<td>昵称/姓名</td>
							<td><?php echo ($userData['ue_theme']); ?></td>
						</tr>
						<tr>
							<td>账号</td>
							<td><?php echo ($userData['ue_account']); ?> [<?php echo ($userData['levelname']); ?>]</td>
						</tr>
						<tr>
							<td>状态</td>
							<td><?php if($userData['ue_check'] == 0 ): ?>未激活<?php else: ?>激活(<?php echo ($userData['jihuo_time']); ?>)<?php endif; ?></td>
						</tr>
						<tr>
							<td>手机号</td>
							<td class="user_mobile"><?php echo ($userData['ue_phone']); ?></td>
						</tr>
						<tr>
							<td>注册日期</td>
							<td><?php echo ($userData['ue_regtime']); ?></td>
						</tr>
						<tr>
							<td>微信</td>
							<td><input type="text" disabled="disabled" class="form-control" name="wechat" value="<?php echo (authcode_decode($userData['weixin'])); ?>" /></td>
						</tr>
						<tr>
							<td>支付宝</td>
							<td><input type="text" disabled="disabled" class="form-control" name="alipay" value="<?php echo (authcode_decode($userData['zfb'])); ?>" /></td>
						</tr>
						<tr>
							<td>持卡人</td>
							<td><input type="text" disabled="disabled" class="form-control" name="yhckr" value="<?php echo (authcode_decode($userData['yhckr'])); ?>" /></td>
						</tr>
						<tr>
							<td>银行名称</td>
							<td><input type="text" disabled="disabled" class="form-control" name="yhmc" value="<?php echo (authcode_decode($userData['yhmc'])); ?>" /></td>
						</tr>
						<tr>
							<td>银行账户号码</td>
							<td><input type="text" disabled="disabled" class="form-control" name="yhzh" value="<?php echo (authcode_decode($userData['yhzh'])); ?>" /></td>
						</tr>
						<tr>
							<td>支行</td>
							<td><input type="text" disabled="disabled" class="form-control" name="yhzhxx" value="<?php echo (authcode_decode($userData['yhzhxx'])); ?>" /></td>
						</tr>
						<tr>
							<td>收款备注</td>
							<td><input type="text" disabled="disabled" class="form-control" name="remark" value="<?php echo ($userData['remark']); ?>" /></td>
						</tr>
						<tr <?php if(C('sms_open_mod')=="0") echo "style='display:none'"; ?>>
							<td>
							     <input type="button" class="form-control col-2 btn-primary" onclick="time(this);" value="发送手机短信" style="display: inline-block;">
							</td>
							<td>
							   <input type="text" disabled="disabled" class="form-control" name="phone_check" value="<?php echo session('CHECK_CODE');?>" />
							</td>
						</tr>
					</table>
					<a class="btn btn-primary mf_edit">编辑基本信息</a><a class="btn btn-primary mf_save">保存</a>
				</div>
				<div class="contitle">
					修改密码
				</div>
				<div>
					<div style="width: 80%;margin: auto;" >
						<div style="margin: 20px 0;display:none">
							通过验证手机短信更改登录密码，如果您的手机号无法接收短信请联系客服。
						</div>
						<div style="display:none">
							<input type="button" class="form-control col-2 btn-primary" value="发送手机短信" style="width: 49%;display: inline-block;" /><input type="text" class="form-control smscode" placeholder="短信验证码" style="width: 49%;display: inline-block;" />
						</div>
						<div style="margin: 10px 0">
							<span style="display: inline-block;width: 49%">输入原密码：</span><input type="password" class="form-control ymm" placeholder="原密码" style="width: 49%;display: inline-block;" />
						</div>
						<div style="margin: 10px 0">
							<span style="display: inline-block;width: 49%">输入新的密码：</span><input type="password" class="form-control xmm" placeholder="新的密码" style="width: 49%;display: inline-block;" />
						</div>
						<div style="margin: 10px 0">
							<span style="display: inline-block;width: 49%">确认新密码：</span><input type="password" class="form-control xmmqr" placeholder="确认新密码" style="width: 49%;display: inline-block;" />
						</div>
						<div>
							<a class="btn btn-primary mf_savepwd">提交</a>
						</div>
					</div>
				</div>
				<div class="contitle" style="display:none">
					账号托管
				</div>
				<div style="display:none">
					<div>
						<span>托管开关</span> <input class="toggle" type="checkbox" id="tuoguan"
						<?php if($userData['tuoguan'] == 1 ): ?>checked='checked'<?php else: endif; ?> > 
						<i style="font-size:0.7em">
							*开启后你的直接领导人将可以帮助你进行操作。
						</i>
					</div>
				</div>	
				<div class="contitle">
					领导人信息
				</div>
				<div>
					 <table class="table">
						<tr>
							<td>昵称</td>
							<td><?php echo ($accuserData['ue_theme']); ?></td>
						</tr>
						<tr>
							<td>账号</td>
							<td><?php echo ($accuserData['ue_account']); ?></td>
						</tr>
						<tr>
							<td>手机号</td>
							<td><?php echo ($accuserData['ue_phone']); ?></td>
						</tr>
						<tr>
							<td>微信</td>
							<td><?php echo ($accuserData['weixin']); ?></td>
						</tr>
					</table>
				</div>

				<div class="contitle" style="display:none">
					银行卡
				</div>
				<div>
					<div class='card'><div><span class='left'>银行：</span><span>农业银行</span></div><div><span class='left'>卡号：</span><span>62284810</span></div><div><span class='left'>支行：</span><span>东台市支行</span></div><div><span class='left'>持有人：</span><span>陶丽</span></div><div><span class='left'>支付宝：</span><span>13779588</span></div><div><span class='left'>备注：</span><span></span></div></div>					<span class="help-block">为了保障会员账户安全，银行卡添加后会员不可编辑或删除。如需更改银行卡请联系客服操作。</span>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	#settingpanel{background: rgba(255,255,255,.9);padding: 10px 5px;}
	.contitle {cursor: pointer;}
	.contitle + div{margin-bottom: 20px;}
	.contitle + div{display: none;}
	.contitle.active + div{display: block;}
	input[name='wechat'] , input[name='alipay'], input[name='yhmc'], input[name='yhzh'], input[name='remark'], input[name='yhckr'], input[name='yhzhxx']{width: 20em;margin:auto;}
	input[name='phone_check']{width: 20em;margin:auto;}
	a.mf_save{display: none;}
	input.sendsms[disabled]{background: #2c3e50;}

	.card{padding: 10px 0;margin: 5px 0; background: rgba(255,255,255,.8);border-radius: 8px;box-shadow: 0 2px 2px rgba(0,0,0,.5);font-family: monospace;font-size: 1.2em;text-align: left;}
	.card div{padding: 0 10px;margin: 3px 0;}
	.card div:nth-child(2){background: #000;color: #fff;padding: 5px 10px;font-size: 1.2em}
</style>
<script>

    function isMobile(str) {
        var myreg = /^([0]?)(((13[0-9]{1})|(14[0-9]{1})|(15[0-9]{1})|(16[0-9]{1})|(17[0-9]{1})|(18[0-9]{1})|(19[0-9]{1}))+\d{8})$/;
        return myreg.test(str);
    }

    function isEmail(str) {
        var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
        return reg.test(str);
    }

    var wait = 60;

    function time(o) {       
        if ("<?php echo ($userData['ue_phone']); ?>"== "") {
            layer.msg("请填写手机号");
            return false;
        }       

        if (!isMobile("<?php echo ($userData['ue_phone']); ?>")) {
            layer.msg("手机格式不正确");
            return false;
        }

        $.post("<?php echo U('Info/sendPhone_info');?>", { phone: "<?php echo ($userData['ue_phone']); ?>" }, function(msg) { layer.msg("验证码已经发送，注意查收"); });

        okssss(o);
    }
    var wait = 60;
    function okssss(o) {
        if (wait == 0) {
            $(o).removeAttr("disabled");
            $(o).val("免费获取验证码");
            wait = 120;
        } else {
            $(o).attr("disabled", true);
            $(o).val("重新发送(" + wait + ")");
            wait--;
            setTimeout(function() {
                okssss(o);
            },
            1000);
        }
    }

	(function(){
		document.title='设置';
		$(document).on('click','.mf_edit',function(){
			$(this).hide();
			$('[name=wechat]').removeAttr('disabled');
			$('[name=alipay]').removeAttr('disabled');
			$('[name=yhmc]').removeAttr('disabled');
			$('[name=yhzh]').removeAttr('disabled');
			$('[name=yhzhxx]').removeAttr('disabled');
			$('[name=yhckr]').removeAttr('disabled');
			$('[name=remark]').removeAttr('disabled');
			$('[name=phone_check]').removeAttr('disabled');
			$('.mf_save').show();
		})
		//保存基础信息
		$(document).on('click','.mf_save',function(){
			var wechat = $('[name=wechat]').val();
			var alipay = $('[name=alipay]').val();
			var yhmc = $('[name=yhmc]').val();
			var yhzh = $('[name=yhzh]').val();
			var remark = $('[name=remark]').val();
			var yhzhxx = $('[name=yhzhxx]').val();
			var yhckr = $('[name=yhckr]').val();
			var phone_check = $('[name=phone_check]').val();
			$.ajax({
				type:'post',
				url:'/Home/Info/xgzlcl',
				data:'wechat='+wechat+'&alipay='+alipay+'&yhmc='+yhmc+'&yhzh='+yhzh+'&yhzhxx='+yhzhxx+'&phone_check='+phone_check+'&yhckr='+yhckr+'&remark='+remark,
				timeout:7000,
				success:function(data){
					if (data.sf==1) {
						layer.msg('操作成功');
					}else{
						layer.msg(data.nr);
					}
				},
				compplete:function(XMLHttprequest){
					if (XMLHttprequest.statusText=='timeout') {
						layer.msg('连接超时,请更换网络环境或稍后再试!')
					}
				}

			});
		})
		//编辑密码
		$(document).on('click','.mf_editpwd',function(){
			var o = $(this);
			o.hide();
			o.next('div').show();
			var mobile = $('.user_mobile').text();
			console.log(mobile);
			if(!(/^1[34578]\d{9}$/.test(mobile))){
				layer.msg('您的手机号有误，无法发送短信，请联系客服修改手机号！');
			}
		})		

		$(document).on('click','#sendsms1',function(){
			var o = $(this);
			var ini = wait;
			var p = setInterval(function(){
				if (wait==ini) {
					o.attr('disabled','disabled');
					$.ajax({
						type:'post',
						url:'?',
						data:'act=sendsms&t=2',
						timeout:7000,
						async:true,
						success:function(data){
							if (data=='短信已发送') {
								layer.tips('短信发送成功', '#sendsms1');
							}else{
								layer.alert('错误信息:'+data,{title:'短信发送失败'})
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

		//保存密码
		$(document).on('click','.mf_savepwd',function(){
			var ymm = $('.ymm').val();
			var xmm = $('.xmm').val();
			var xmmqr = $('.xmmqr').val();
			$.ajax({
				type:'post',
				url:'/Home/Info/xgyjmmcl',
				data:'ymm='+ymm+'&xmm='+xmm+'&xmmqr='+xmmqr,
				timeout:7000,
				async:true,
				success:function(data){
					if (data.sf==1) {
						layer.msg('密码修改成功，请重新登录');
						setTimeout(function(){location.href='/'},1000)
					}else{
						layer.alert('错误信息:'+data.nr,{title:'密码修改失败'})
					}
				},
				complete:function(XMLHttprequest){
					if (XMLHttprequest.statusTest=='timeout') {
						layer.msg('连接超时,请更换网络环境或稍后再试!');
					}
				}
			});
		})
		$(document).on('click','#tuoguan',function(){
			var o = $(this);
			var tuoguan = o.prop('checked') ? true : false;
			if (tuoguan) {
				var doo = 1;
			}else{
				var doo = 0;
			}
			$.ajax({
				type:'post',
				url:'/Home/Index/home_post',
				data:'act=tuoguan&tuoguan='+doo,
				timeout:7000,
				success:function(data){
					if (data.sf==1) {
						layer.msg(data.nr);
					}else{
						layer.msg(data.nr);
					}
				},compplete:function(XMLHttprequest){
					if (XMLHttprequest.statusText=='timeout') {
						layer.msg('连接超时,请更换网络环境或稍后再试!')
					}
				}
			})
		});
		$(document).on('click','.contitle',function(){
			var o = $(this);
			o.next('div').slideToggle(200);
		})
		$(document).on('click','.add_bankcard',function(){
			layer.open({
				title:'添加银行卡',
			 	type: 1, 
				content: "<fieldset class='addnewcard'><div class='form-group'><label for='bank' class='col-lg-2 control-label'>银行：</label><div class='col-lg-10 '><select class='form-control' id='bank'><option value='工商银行'>工商银行</option><option value='建设银行'>建设银行</option><option value='中国银行'>中国银行</option><option value='交通银行'>交通银行</option><option value='农业银行'>农业银行</option><option value='招商银行'>招商银行</option><option value='徽商银行'>招商银行</option><option value='邮政储蓄银行'>邮政储蓄银行</option><option value='光大银行'>光大银行</option><option value='民生银行'>民生银行</option><option value='平安银行'>平安银行</option><option value='浦发银行'>浦发银行</option><option value='中信银行'>中信银行</option><option value='兴业银行'>兴业银行</option><option value='华夏银行'>华夏银行</option><option value='广发银行'>广发银行</option><option value='北京银行'>北京银行</option><option value='其他请备注'>其他请备注</option></select></div></div><div class='form-group'><label for='account' class='col-lg-2 control-label'>卡号：</label><div class='col-lg-10 '><input type='text' class='form-control' id='account' placeholder='卡号'></div></div><div class='form-group'><label for='owner' class='col-lg-2 control-label'>持卡人：</label><div class='col-lg-10 '><input type='text' class='form-control' id='owner' placeholder='持卡人'></div></div><div class='form-group'><label for='addr' class='col-lg-2 control-label'>分行：</label><div class='col-lg-10 '><input type='text' class='form-control' id='addr' placeholder='分行'></div></div><div class='form-group'><label for='alipay' class='col-lg-2 control-label'>支付宝：</label><div class='col-lg-10 '><input type='text' class='form-control' id='alipay' placeholder='支付宝'></div></div><div class='form-group'><label for='beizhu' class='col-lg-2 control-label'>备注</label><div class='col-lg-10 '><textarea class='form-control' rows='3' id='beizhu'></textarea></div></div><div class='form-group'><label for='smscode' class='col-lg-2 control-label'>验证码</label><div class='col-lg-10 '><input type='button' class='form-control btn btn-primary' id='sendsms1' value='发送短信' style='display:inline-block;width:50%'><input type='text' class='form-control' id='smscode' placeholder='短信验证码' style='display:inline-block;width:50%'></div></div></fieldset>",
				area: ['500px', '70%'],
				offset:'5%',
				btn: ['保存', '取消'],
				yes: function(index, layero){
				    var bank = $('#bank').val();
				    var account = $('#account').val();
				    var owner = $('#owner').val();
				    var addr = $('#addr').val();
				    var alipay = $('#alipay').val();
				    var beizhu = $('#beizhu').val();
				    var smscode = $('#smscode').val();
				    $.ajax({
				    	type:'post',
				    	url:'?',
				    	data:'act=addbankcard&bank='+bank+'&account='+account+'&owner='+owner+'&addr='+addr+'&alipay='+alipay+'&beizhu='+beizhu+'&smscode='+smscode,
				    	timeout:7000,
				    	success:function(data){
				    		if (data==1) {
				    			layer.msg('添加成功');
				    			layer.close(index);
				    		}else{
				    			layer.msg(data)
				    		}
				    	},
				    	complete:function(XMLHttprequest){
				    		if (XMLHttprequest.statusText=='timeout') {
				    			layer.msg('连接超时,请更换网络环境或稍后再试!');
				    		}
				    	}
				    });
				},
				btn2: function(index, layero){
					console.log('222')
				}
			}); 
		})
	})();
</script>


<!-- footer start --> 
  </div>
 </body>
</html> 
<!-- footer end -->