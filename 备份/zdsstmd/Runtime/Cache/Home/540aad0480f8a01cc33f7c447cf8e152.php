<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/sncss/css/style.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="/sncss/js/jquery.js"></script>

<script type="text/javascript">
$(function(){	
	//导航切换
	$(".menuson li").click(function(){
		$(".menuson li.active").removeClass("active")
		$(this).addClass("active");
	});
	
	$('.title').click(function(){
		var $ul = $(this).next('ul');
		$('dd').find('ul').slideUp();
		if($ul.is(':visible')){
			$(this).next('ul').slideUp();
		}else{
			$(this).next('ul').slideDown();
		}
	});
})	
</script>


</head>

<body style="background:#f0f9fd;">
	<div class="lefttop"><span></span>功能栏</div>
    
    <dl class="leftmenu">
    <?php if($quanxian == 1): ?><dd>
    <div class="title">
    <span><img src="/sncss/images/leftico01.png" /></span>会员管理
    </div>
    	<ul class="menuson">
        <li><cite></cite><a href="/Yshclbssb.php/Home/Index/userlist" target="rightFrame">所有會員</a><i></i></li>
        <li><cite></cite><a href="/Yshclbssb.php/Home/Index/jbzs" target="rightFrame">金币赠送</a><i></i></li>
        <li><cite></cite><a href="/Yshclbssb.php/Home/Index/team" target="rightFrame">会员关系网</a><i></i></li>
		<li><cite></cite><a href="/Yshclbssb.php/Home/Index/txlist" target="rightFrame">提现管理</a><i></i></li>
        </ul>    
    </dd>   
    
    <dd><div class="title"><span><img src="/sncss/images/leftico03.png" /></span>文章管理</div>
    <ul class="menuson">

		<li><cite></cite><a href="/Yshclbssb.php/Home/Shop/zsbyg_list" target="rightFrame">新闻公告</a><i></i></li>
		<li><cite></cite><a href="/Yshclbssb.php/Home/Shop/zsbyg_list_xg" target="rightFrame">添加内容</a><i></i></li>
		<li><cite></cite><a href="/Yshclbssb.php/Home/Index/yuanzhugg" target="rightFrame">援助公告</a><i></i></li>
    </ul>    
    </dd>  
    
	
	 <dd><div class="title"><span><img src="/sncss/images/leftico03.png" /></span>留言管理</div>
    <ul class="menuson">

		<li><cite></cite><a href="/Yshclbssb.php/Home/Shop/ly_list/type/0/" target="rightFrame">未处理留言</a><i></i></li>
		<li><cite></cite><a href="/Yshclbssb.php/Home/Shop/ly_list/type/1/" target="rightFrame">已处理留言</a><i></i></li>
    </ul>    
    </dd>  
    
  <dd><div class="title"><span><img src="/sncss/images/leftico04.png" /></span>激活码管理</div>
    <ul class="menuson">
        <li><cite></cite><a href="/Yshclbssb.php/Home/Index/pin_add" target="rightFrame">生成激活码</a><i></i></li>
		<li><cite></cite><a href="/Yshclbssb.php/Home/Index/pin_list" target="rightFrame">PIN管理</a><i></i></li>
    </ul>
    
    </dd> 

      <dd><div class="title"><span><img src="/sncss/images/leftico04.png" /></span>排单码管理</div>
    <ul class="menuson">
        <li><cite></cite><a href="/Yshclbssb.php/Home/Index/paidan_add" target="rightFrame">生成排单码</a><i></i></li>
		<li><cite></cite><a href="/Yshclbssb.php/Home/Index/paidan_list" target="rightFrame">排单码使用</a><i></i></li>
    </ul>
    
    </dd>
	  <dd >
	  <div class="title"><span><img src="/sncss/images/leftico04.png" /></span>奖金管理</div>
		<ul class="menuson">
			<li><cite></cite><a href="/Yshclbssb.php/Home/Index/jjset" target="rightFrame">奖金设定</a><i></i></li>
			<li><cite></cite><a href="/Yshclbssb.php/Home/Index/txset" target="rightFrame">提现设置</a><i></i></li>
			<li><cite></cite><a href="/Yshclbssb.php/Home/Index/lixi" target="rightFrame">利息配置</a><i></i></li>
		</ul>
    
    </dd><?php endif; ?>
	
	
	  <dd><div class="title"><span><img src="/sncss/images/leftico04.png" /></span>匹配系统</div>
		<ul class="menuson">
			<li><cite></cite><a href="/Yshclbssb.php/Home/Index/tgbz_list" target="rightFrame">提供帮助</a><i></i></li>
			<li><cite></cite><a href="/Yshclbssb.php/Home/Index/jsbz_list" target="rightFrame">接受帮助</a><i></i></li>
			<li><cite></cite><a href="/Yshclbssb.php/Home/Index/ppdd_list" target="rightFrame">交易中的订单</a><i></i></li>
			<li><cite></cite><a href="/Yshclbssb.php/Home/Index/ppdd_list/cz/1/" target="rightFrame">成功交易订单</a><i></i></li>
			<li><cite></cite><a href="/Yshclbssb.php/Home/Index/ts1_list" target="rightFrame">到期未打款</a><i></i></li>
			<li><cite></cite><a href="/Yshclbssb.php/Home/Index/ts2_list" target="rightFrame">未收到款</a><i></i></li>
			<li><cite></cite><a href="/Yshclbssb.php/Home/Index/ts3_list" target="rightFrame">到期未确认</a><i></i></li>
				 <li><cite></cite><a href="/Yshclbssb.php/Home/Index/tgbz_list_cf" target="rightFrame">提供拆分</a><i></i></li>
			<li><cite></cite><a href="/Yshclbssb.php/Home/Index/jsbz_list_cf" target="rightFrame">接受拆分</a><i></i></li>
			<!-- <li><cite></cite><a  onclick="javascript:if(!confirm('确认要清理数据表吗？'))  return  false; "  href="/Yshclbssb.php/Home/Index/clearalldo" target="rightFrame">清理数据表</a><i></i></li> 
			<li><cite></cite><a href="/Yshclbssb.php/Home/Baksql/backall" target="rightFrame">备份数据库</a><i></i></li> -->
			<!--<li><cite></cite><a href="/admin.php/Home/Index/pin_list" target="rightFrame">PIN管理</a><i></i></li>-->
			<!--<li><cite></cite><a href="/admin.php/Home/Shop/zsbyg_list" target="rightFrame">钻石币云购管理</a><i></i></li>-->
		</ul>
    </dd>
       <?php if($quanxian == 1): ?><dd ><div class="title"><span><img src="/sncss/images/leftico04.png" /></span>最新设置</div>
            <ul class="menuson">
                <li><cite></cite><a href="/Yshclbssb.php/Home/Index/new_config" target="rightFrame">系统设置</a><i></i></li>
                <li><cite></cite><a href="/Yshclbssb.php/Home/Index/order" target="rightFrame">预约抢单设置</a><i></i></li>
            </ul>
        </dd><?php endif; ?>
    
    </dl>
    
</body>
</html>