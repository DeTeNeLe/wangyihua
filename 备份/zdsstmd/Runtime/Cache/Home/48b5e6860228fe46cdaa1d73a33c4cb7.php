<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/sncss/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/sncss/js/jquery.js"></script>
<script type="text/javascript" src="/sncss/date/laydate.js"></script>

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
	 
   <input name="user" type="text" class="dfinput" id="user" value="<?php echo ($user); ?>" />
   <input name="cz" type="hidden" class="dfinput" id="cz" value="<?php echo ($cz); ?>" />
   <input placeholder="请输入开始日期" class="laydate-icon" value="<?php echo ($_SESSION['start']); ?>" name="start" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
   <input placeholder="请输入结束日期" class="laydate-icon" value="<?php echo ($_SESSION['end']); ?>" name="end" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
   <input name="" type="submit" class="btn" value="确认搜索"/>
   搜索出的日期累计总额：<?php echo ($s_sum); ?> &nbsp;&nbsp;
      </form></td>
    <td align="right">
	  <a href="/Yshclbssb.php/Home/Index/ppdd_list/cz/-1/" style="padding:2px 5px;<?php if($cz == -1): ?>background:#00CCFF<?php endif; ?>">所有</a>
      <a href="/Yshclbssb.php/Home/Index/ppdd_list/cz/0/"  style="padding:2px 5px;<?php if($cz == 0): ?>background:#00CCFF<?php endif; ?>">交易中</a>
      <a href="/Yshclbssb.php/Home/Index/ppdd_list/cz/1/"  style="padding:2px 5px;<?php if($cz == 1): ?>background:#00CCFF<?php endif; ?>">交易成功</a>
     </td>
  </tr>
  <tr>
    <td>请输入订单ID或用户名 </td>
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
		<th>时间</th>
		<th>操作</th>
    	</tr>
        </thead>
        <tbody>
		
		<?php if(is_array($list)): foreach($list as $key=>$v): ?><tr style="<?php if(in_array(($v["g_user"]), explode(',',"13169973076"))): ?>display:none;<?php endif; ?>">
		 <td>R<?php echo ($v["id"]); ?></td>
		 <td>P<?php echo ($v["p_id"]); ?></td>
		  <td>
		 G<?php echo ($v["g_id"]); ?>		  </td>
		   <td><?php echo ($v["p_user"]); ?> [<a class='btn-details' href='<?php echo ($v["p_user"]); ?>'>详情</a>]</td>
		    <td><?php echo ($v["g_user"]); ?> [<a class='btn-details' href='<?php echo ($v["g_user"]); ?>'>详情</a>]</td>
        
        <td><?php echo ($v["jb"]); ?>		</td>
        <td><?php if($v["zt"] == 0): ?>待付款<?php endif; ?>
											<?php if($v["zt"] == 1): ?>已付款<?php endif; ?>
											<?php if($v["zt"] == 2): ?>交易成功<?php endif; ?></td>
        <td><?php echo ($v["date"]); ?></td>
        <td><a onClick="javascript:if(!confirm('删除此订单会连同相关提供和接受订单一起删除,确认要删除吗？'))  return  false; " href="/Yshclbssb.php/Home/Index/ppdd_list_del/id/<?php echo ($v["id"]); ?>" >删除</a></td>
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

                        <div align="right"><?php echo ($page); ?>
                        </div>
   </div>
    
	 <div align="left">

会员信息：<span id="user_info"></span></div>

</div>
    
    <div class="tip">
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
    
    </div>
    
    
    
    
    </div>
    
    <script type="text/javascript">
	$('.tablelist tbody tr:odd').addClass('odd');
	 $('.btn-details').click(function () {
	$.post("/Yshclbssb.php/Home/Index/get_userinfo_from_uname.html", { uname: $(this).attr('href') }, function(msg) { $('#user_info').html(msg); });
    return false;
  });
	</script>

</body>

</html>