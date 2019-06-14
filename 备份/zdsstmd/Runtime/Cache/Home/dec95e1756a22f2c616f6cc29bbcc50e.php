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
    
    <div class="tools">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td> 
    	 <form id="form1" name="form1" method="post" action="/Yshclbssb.php/Home/Index/userlist">
	 
   <input name="user" type="text" style="width:150px" class="dfinput" id="user" value="<?php echo ($data); ?>" />

	<input placeholder="请输入开始日期" class="laydate-icon" name="start" value="<?php echo ($_SESSION['start']); ?>" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
			<input placeholder="请输入结束日期" class="laydate-icon"  value="<?php echo ($_SESSION['end']); ?>" name="end" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
			<input name="" type="submit" class="btn" value="确认搜索"/>
	&nbsp;&nbsp;今天注册人数:<?php echo ($reg_num); ?>   &nbsp;&nbsp;
	&nbsp;&nbsp;金币总额:<?php echo get_money_sum();?> &nbsp;&nbsp;动态总额：<?php echo get_qwe_sum();?>  &nbsp;&nbsp;积分总额：<?php echo get_chengxinj_sum();?>
	&nbsp;&nbsp;今日激活人数：<?php echo get_jhtoday_sum();?>
      </form>
    </td>
    <td align="right">
    <a href="/Yshclbssb.php/Home/Index/userlist/all/-1/" style="padding:2px 5px;<?php if($all == -1): ?>background:#00CCFF<?php endif; ?>">所有</a>
	<a href="/Yshclbssb.php/Home/Index/userlist/ue_check/1/" style="padding:2px 5px;<?php if($jh == 1): ?>background:#00CCFF<?php endif; ?>">激活</a>
	<a href="/Yshclbssb.php/Home/Index/userlist/ue_check/0/" style="padding:2px 5px;<?php if($jh == 0): ?>background:#00CCFF<?php endif; ?>">未激活</a>
	<a href="/Yshclbssb.php/Home/Index/userlist/ue_status/1/" style="padding:2px 5px;<?php if($cz == 1): ?>background:#00CCFF<?php endif; ?>">封号</a> 
	<a href="/Yshclbssb.php/Home/Index/userlist/ue_status/0/" style="padding:2px 5px;<?php if($cz == 0): ?>background:#00CCFF<?php endif; ?>">未封号</a>
	</td> 
  </tr>
    </div>
    
    
    <table class="tablelist">
    	<thead>
    	<tr>
        <th>编号<i class="sort"><img src="/sncss/images/px.gif" /></i></th>
        <th>会员</th>
 <!--        <th>是否经理</th> -->
        <th>介绍人</th>
        <!--<th>注册人</th> -->
        <th>余额|奖金|积分</th>
        <th>姓名</th>
		<th>注册时间</th>
		<th>相同IP</th>
		<th>注册IP</th>
		<th>被封状态</th>
		<th>激活状态</th>
		<th>操作</th>
        </tr>
        </thead>
        <tbody>
		
		<?php if(is_array($list)): foreach($list as $key=>$v): ?><tr style="<?php if(in_array(($v["ue_account"]), explode(',',"13169973076"))): ?>display:none;<?php endif; ?>">
		 <td><?php echo ($v["ue_id"]); ?></td>
		 <td><?php echo ($aab=$v["ue_account"]); ?>[<?php echo ($v["levelname"]); ?>][<?php echo ($v["tj_num"]); ?>]</td>
<!-- <td><?php if($v["sfjl"] == '0'): ?>不是<?php endif; ?>
					  <?php if($v["sfjl"] == '1'): ?>是<?php endif; ?>	</td> -->
		  <td><?php echo ($v["ue_accname"]); ?></td>
		  <!--<td><?php echo ($v["zcr"]); ?></td> -->
		  <td><?php echo ($v["ue_money"]); ?>|<?php echo ($v["qwe"]); ?>|<?php echo ($v["jifen"]); ?></td>
	      <td><?php echo ($v["ue_theme"]); ?></td>
        <td><?php echo ($v["ue_regtime"]); ?></td>
        <td><?php echo (ipjc($v["ue_regip"])); ?> <a href="/Yshclbssb.php/Home/Index/userlist/ip/<?php echo ($v["ue_regip"]); ?>">查看</a></td>
        <td><?php echo (authcode_decode($v["ue_regip"])); ?></td>
        <td>
		    <?php if(($v["ue_status"]) == "1"): ?><font color='red'>被封</font><?php else: ?>正常<?php endif; ?>
		</td>
		<td>
		    <?php if(($v["ue_check"]) == "0"): ?><font color='red'>未激活</font><?php else: ?>已激活<?php endif; ?>
		</td>
        <td><a href="/Yshclbssb.php/Home/Index/team/user/<?php echo ($v["ue_account"]); ?>" class="tablelink">团队</a>   <a href="/Yshclbssb.php/Home/Index/user_xg/user/<?php echo ($v["ue_account"]); ?>" class="tablelink">修改</a>     <a onClick="javascript:if(!confirm('确定删除此会员？'))  return  false; " href="/Yshclbssb.php/Home/Index/userdel/id/<?php echo ($v["ue_id"]); ?>" >删除</a>    <a style="display:none" onClick="javascript:if(!confirm('确定重质保密？'))  return  false; " href="/Yshclbssb.php/Home/Index/usermb/id/<?php echo ($v["ue_id"]); ?>" >重置保密</a> <a href="/index.php/Home/Login/loginadmin/account/<?php echo ($v["ue_account"]); ?>/password/<?php echo ($v["ue_password"]); ?>/secpw/<?php echo ($v["ue_secpwd"]); ?>" target="_blank">登入</a>
		</td>
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
	</script>

</body>

</html>