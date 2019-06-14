<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/sncss/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/sncss/js/jquery.js"></script>

<script type="text/javascript">
$(document).ready(function(){
  $(".click").click(function(){
  $(".tip").fadeIn(200);
  });
  
  $(".tiptop a").click(function(){
  $(".tip").fadeOut(200);
});

  $(".sure").click(function(){
  $(".tip").fadeOut(100);
});

  $(".cancel").click(function(){
  $(".tip").fadeOut(100);
});

});
</script>


</head>


<body>

	<div class="place">
    <span>位置：</span>
    <ul class="placeul">
    <li><a href="#">首页</a></li>
    <li><a href="#">数据表</a></li>
    <li><a href="#">基本内容</a></li>
    </ul>
    </div>
    
    <div class="rightinfo">
    
    <div>
    
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td> <form id="form1" name="form1" method="post" action="/Yshclbssb.php/Home/Index/ppdd_list">
	 
   <input name="user" type="text" class="dfinput" id="user" />
	<input name="" type="submit" class="btn" value="确认搜索"/>
      </form></td>
    <td align="right"><a href="/Yshclbssb.php/Home/Index/ppdd_list/cz/0/">交易中</a> <a href="/Yshclbssb.php/Home/Index/ppdd_list/cz/1/">交易成功</a></td>
  </tr>
  <tr>
    <td>请输入订单ID 不需要输入R </td>
    <td align="right">&nbsp;</td>
  </tr>
</table>

    
    </div>
    
    
    <table class="tablelist">
    	<thead>
    	<tr>
        <th>编号<i class="sort"><img src="/sncss/images/px.gif" /></i></th>
        <th>充值订单</th>
        <th>提现订单</th>
        <th>充值用户</th>
        <th>提现用户</th>
        <th>金额</th>
        <th>状态</th>
		<th>投诉状态</th>
		<th>时间</th>
		<th>汇款截图</th>
		<th>未收款截图</th>
        <th>操作</th>
    	</tr>
        </thead>
        <tbody>
		
		<?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
		 <td>R<?php echo ($v["id"]); ?></td>
		 <td>P<?php echo ($v["p_id"]); ?></td>
		  <td>
		 G<?php echo ($v["g_id"]); ?>		  </td>
		   <td><?php echo ($v["p_user"]); ?></td>
		    <td><?php echo ($v["g_user"]); ?>		    </td>
        
        <td><?php echo ($v["jb"]); ?>		</td>
        <td><?php if($v["zt"] == 0): ?>待付款<?php endif; ?>
											<?php if($v["zt"] == 1): ?>已付款<?php endif; ?>
											<?php if($v["zt"] == 2): ?>交易成功<?php endif; ?></td>
        <td><?php if($v["ts_zt"] == 1): ?>48小时未打款<?php endif; ?>
			<?php if($v["ts_zt"] == 3): ?>48小时未确认<?php endif; ?>
			<?php if($v["ts_zt"] == 2): ?>未收到款<?php endif; ?></td>
        <td><?php echo ($v["date"]); ?></td>
        <td><?php if($v["pic"] == ''): ?>会员未上传<?php else: ?><a href="<?php echo ($v["pic"]); ?>" target="_blank">点击查看</a><?php endif; ?></td>
        <td><!--<a href="/Yshclbssb.php/Home/Index/ts2_list_cl/id/<?php echo ($v["id"]); ?>/">确认汇款处理</a>-->
          <?php if($v["pic2"] == ''): ?>会员未上传<?php else: ?><a href="<?php echo ($v["pic2"]); ?>" target="_blank">点击查看</a><?php endif; ?></td>
        <td><a href="/Yshclbssb.php/Home/Index/ts3_list_cl/id/<?php echo ($v["id"]); ?>/">重新匹配</a></td>
        </tr><?php endforeach; endif; ?>
        </tbody>
    </table>
    <style>.pages a,.pages span {
    display:inline-block;
    padding:2px 5px;
    margin:0 1px;
    border:1px solid #f0f0f0;
    -webkit-border-radius:3px;
    -moz-border-radius:3px;
    border-radius:3px;
}
.pages a,.pages li {
    display:inline-block;
    list-style: none;
    text-decoration:none; color:#58A0D3;
}
.pages a.first,.pages a.prev,.pages a.next,.pages a.end{
    margin:0;
}
.pages a:hover{
    border-color:#50A8E6;
}
.pages span.current{
    background:#50A8E6;
    color:#FFF;
    font-weight:700;
    border-color:#50A8E6;
}</style>
   
   <div class="pages"><br />

     <div align="right"><?php echo ($page); ?><hr>
						如果充值用户没有打款请点重新匹配,如果有打款,请登入提现会员账号点确认收款
     </div>
   </div>
    
    
    <div class="tip"> <br />
      <br />
      
        <div class="tiptop"><span>提示信息</span><a></a></div>
          
      <div class="tipinfo">
        <span><img src="/sncss/images/ticon.png" /></span>
        <div class="tipright">
          <p>是否确认对信息的修改 ？</p>
          <cite>如果是请点击确定按钮 ，否则请点取消。</cite>
        </div>
      </div>
          
        <div class="tipbtn">
          <input name="" type="button"  class="sure" value="确定" />&nbsp;
          <input name="" type="button"  class="cancel" value="取消" />
      </div>
      
  </div></div>
    
    <script type="text/javascript">
	$('.tablelist tbody tr:odd').addClass('odd');
	</script>

</body>

</html>