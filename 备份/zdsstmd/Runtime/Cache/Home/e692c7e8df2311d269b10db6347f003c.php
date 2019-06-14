<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/sncss/css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="#">首页</a></li>
    <li><a href="#">表单</a></li>
    </ul>
    </div>
    
    <div class="formbody">
    
    <div class="formtitle"><span>基本信息</span></div>
      <form id="form1" name="form1" method="post" action="/Yshclbssb.php/Home/Index/usercl">
	  <input name="UE_account"  type="hidden" class="dfinput" value="<?php echo ($userdata['ue_account']); ?>" />
    <ul class="forminfo">
	<li><label>会员編号</label><input name="UE_account1" disabled="true " type="text" class="dfinput" value="<?php echo ($userdata['ue_account']); ?>" readonly=""/><i>不可修改</i></li>
	<li><label>推荐人</label><input name="UE_accName" type="text" class="dfinput" value="<?php echo ($userdata['ue_accname']); ?>"/></li>
	<li><label>姓名</label><input name="UE_theme"  type="text" class="dfinput" value="<?php echo ($userdata['ue_theme']); ?>"/></li>
	<li><label>是否激活</label><?php if($userdata['ue_check'] == 0): ?><cite><input name="UE_check" type="radio" value="1" />是&nbsp;&nbsp;&nbsp;&nbsp;<input name="UE_check" type="radio" value="0" checked="checked" />否</cite><?php else: ?><cite><input name="UE_check" type="radio" value="1" checked="checked" />
	是&nbsp;&nbsp;&nbsp;&nbsp;<input name="UE_check" type="radio" value="0" />
	否</cite><?php endif; ?></li>
    <li><label>是否经理</label><?php if($userdata['sfjl'] == 0): ?><cite><input name="UE_stop" type="radio" value="1" />是&nbsp;&nbsp;&nbsp;&nbsp;<input name="UE_stop" type="radio" value="0" checked="checked" />否</cite><?php else: ?><cite><input name="UE_stop" type="radio" value="1" checked="checked" />
	是&nbsp;&nbsp;&nbsp;&nbsp;<input name="UE_stop" type="radio" value="0" />
	否</cite><?php endif; ?></li>
    <li><label>账号状态</label>
	<?php if($userdata['ue_status'] == 0): ?><cite><input name="UE_status" type="radio" value="1" />
	封号&nbsp;&nbsp;&nbsp;&nbsp;
	<input name="UE_status" type="radio" value="0" checked="checked" />正常</cite>
	<?php else: ?><cite>
	<input name="UE_status" type="radio" value="1" checked="checked" />
	封号&nbsp;&nbsp;&nbsp;&nbsp;<input name="UE_status" type="radio" value="0" />
	正常</cite><?php endif; ?></li>
    <li><label>一級密码</label><input name="UE_password" type="text" class="dfinput" /><i>不修改请留空</i></li>
    <li><label>二級密码</label><input name="UE_secpwd" type="text" class="dfinput" /><i>不修改请留空</i></li>
    
    <li><label>手機</label><input name="UE_phone" type="text" class="dfinput" value="<?php echo (authcode_decode($userdata['ue_phone'])); ?>" readonly=""/><i></i></li>
	<li><label>支付宝</label><input name="zfb" type="text" class="dfinput" value="<?php echo (authcode_decode($userdata['zfb'])); ?>"/><i></i></li>    
    <li><label>银行账号</label><input name="yhzh" type="text" class="dfinput" value="<?php echo (authcode_decode($userdata['yhzh'])); ?>"/><i></i></li>
    
    <li><label>银行名称</label><input name="yhmc" type="text" class="dfinput" value="<?php echo (authcode_decode($userdata['yhmc'])); ?>"/><i></i></li>
	 <li><label>微信</label><input name="weixin" type="text" class="dfinput" value="<?php echo (authcode_decode($userdata['weixin'])); ?>"/><i></i></li>
	  <li><label>云支付</label><input name="yzf" type="text" class="dfinput" value="<?php echo (authcode_decode($userdata['yzf'])); ?>"/><i></i></li>
    <li><label>&nbsp;</label><input name="" type="submit" class="btn" value="确认保存"/></li>
    </ul>
      </form>
    
    </div>


</body>

</html>