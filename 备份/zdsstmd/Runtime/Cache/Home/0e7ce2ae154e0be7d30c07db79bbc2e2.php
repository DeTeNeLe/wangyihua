<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/sncss/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://cdn.static.runoob.com/libs/jquery/1.10.2/jquery.min.js"></script>
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
   <td> 
      <form id="form1" name="form1" method="get" action="/Yshclbssb.php/Home/Index/tgbz_list">	 
       <input name="user" type="input" class="dfinput" id="user" style="width:150px" value="<?php echo ($user); ?>"/>
       <input type="radio" name="isfast" value="-1"  <?php if($isfast == '-1'): ?>checked="checked"<?php endif; ?>/>所有
       <input type="radio" name="isfast" value="0" <?php if($isfast == 0): ?>checked="checked"<?php endif; ?>/>不加速
       <input type="radio" name="isfast" value="1" <?php if($isfast == 1): ?>checked="checked"<?php endif; ?> />加速&nbsp;&nbsp;|
       <input type="radio" name="isprepay" value="-1"  <?php if($isprepay == '-1'): ?>checked="checked"<?php endif; ?>/>所有
       <input type="radio" name="isprepay" value="0" <?php if($isprepay == 0): ?>checked="checked"<?php endif; ?>/>尾款
       <input type="radio" name="isprepay" value="1" <?php if($isprepay == 1): ?>checked="checked"<?php endif; ?> />预付款&nbsp;&nbsp;|
       <input type="radio" name="priority" value="-1"  <?php if($priority == '-1'): ?>checked="checked"<?php endif; ?>/>所有
       <input type="radio" name="priority" value="0" <?php if($priority == 0): ?>checked="checked"<?php endif; ?>/>普通
       <input type="radio" name="priority" value="1" <?php if($priority == 1): ?>checked="checked"<?php endif; ?> />高级&nbsp;&nbsp;|
       <input type="radio" name="zt" value="-1"  <?php if($zt == '-1'): ?>checked="checked"<?php endif; ?>/>所有状态
       <input type="radio" name="zt" value="0" <?php if($zt == 6): ?>checked="checked"<?php endif; ?>/>预约中
       <input type="radio" name="zt" value="0" <?php if($zt == 0): ?>checked="checked"<?php endif; ?>/>未匹配
       <input type="radio" name="zt" value="0" <?php if($zt == 1): ?>checked="checked"<?php endif; ?>/>已匹配&nbsp;&nbsp;|
       <input placeholder="请输入开始日期" class="laydate-icon" value="<?php echo ($_SESSION['start']); ?>" name="start" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
       <input placeholder="请输入结束日期" class="laydate-icon" value="<?php echo ($_SESSION['end']); ?>" name="end" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
       <input name="" type="submit" class="btn" value="确认搜索"/>
	   搜索出的日期累计总额：<?php echo ($s_sum); ?> &nbsp;&nbsp;今日排单总额：<?php echo getTodayPDSum();?>&nbsp;&nbsp;未匹配总额：<?php echo getNoPPPDSum();?>
	   <input name="" type="submit" class="btn" value="自动匹配" onClick="javascript:form1.action='/Yshclbssb.php/Home/Index/tgbz_list/zdpp_confirm/1';"/>
	   <input type="checkbox" name="zdpp_confirm_ckb" value="1">自动匹配查询结果的所有订单，请确保数据备份！
   </form>
   </td>
    <td align="right">	
	<!--<a onClick="javascript:if(!confirm('1-1自动匹配前请先备份备据,未备份请点取消,点确定自动匹配所有订单!'))  return  false; "  href="/Yshclbssb.php/Home/Index/zdpp_cl" style="padding:2px 5px;">所有订单自动匹配</a> </td>-->
  </tr>
  <tr>
    <td>总充值:<?php echo ($z_jgbz); ?> 交易成功:<?php echo ($z_jgbz2); ?> 交易中:<?php echo ($z_jgbz3); ?> </td>
    <td align="right">&nbsp;</td>
  </tr>
</table>

    
    </div>
    
    
    <table class="tablelist">
    	<thead>
    	<tr>
        <th>全选<input type="checkbox" name="all">编号<i class="sort"><img src="/sncss/images/px.gif" /></i></th>
        <th>提供会员</th>
        <th>提供金额</th>
        <th>状态</th>
        <th>确认状态</th>
		<th>订单类型</th>
        <th>提供昵称</th>
		<th>提供时间</th>
		<th>提供操作</th>
        </tr>
        </thead>
        <tbody id="tb">
		
		<?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
		 <td><input type="checkbox" name="id" value="<?php echo ($v["jb"]); ?>" onclick="userCheck(this)"><?php echo ($v["id"]); ?> <!--<a onClick="javascript:if(!confirm('确认要删除吗？'))  return  false; " href="/Yshclbssb.php/Home/Index/tgbz_list_del/id/<?php echo ($v["id"]); ?>" >删除</a>--></td>
		 <td><?php echo ($v["user"]); ?> [<a class='btn-details' href='<?php echo ($v["user"]); ?>'>详情</a>]</td>
		   <td><?php echo ($v["jb"]); ?></td>
		    <td><?php if($v["zt"] == 0): ?>等待中<?php endif; ?>
											<?php if($v["zt"] == 1): ?>匹配成功<?php endif; if($v["zt"] == 6): ?>预约中<?php endif; ?></td>
        
        <td>
		
		<?php if($v["qr_zt"] == 0): ?>未确认<?php endif; ?>
											<?php if($v["qr_zt"] == 1): ?>已确认<?php endif; ?>
		</td>
		<td>
		<?php if($v["isfast"] == 1): ?><font color="red">加速</font><?php endif; ?>
		<?php if($v["isfast"] == 0): ?>不加速<?php endif; ?> |
		<?php if($v["isprepay"] == 1): ?><font color="red">预付款</font><?php endif; ?>
		<?php if($v["isprepay"] == 0): ?>尾款<?php endif; ?> |
		<?php if($v["priority"] == 0): ?>普通<?php endif; ?>
		<?php if($v["priority"] == 1): ?><font color="red">高级</font><?php endif; ?>
		</td>
        <td><?php echo ($v["user_nc"]); ?></td>
        <td><?php echo ($v["date"]); ?></td>
       
        <td><a href="/Yshclbssb.php/Home/Index/tgbz_list_sd/id/<?php echo ($v["id"]); ?>/user/<?php echo ($v["user"]); ?>">手动匹配</a></td>
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

 <div align="left">选中金额统计:<span id="check_sum"></span></div>
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
	var check_sum = 0;
	$('#check_sum').html(check_sum);
	$('.tablelist tbody tr:odd').addClass('odd');
	$(function () {
    //全选,设置chheckbox name='all' tbody id=tb
    $("input[name=all]").click(function () {
        if (this.checked) {
			$("#tb :checkbox").each(function(){ 
              check_sum+=Number($(this).val());
            }) 
			
		    $('#check_sum').html(check_sum);
            $("#tb :checkbox").prop("checked", true);
        } else {
			check_sum=0;
		    $('#check_sum').html(check_sum);
            $("#tb :checkbox").prop("checked", false);
        }
    });
});
//单选 设置name=id
function userCheck(ths) {
    if (ths.checked == false) {
		check_sum-=Number(ths.value);
		$('#check_sum').html(check_sum);
        $("input[name=all]:checkbox").prop('checked', false);
    }
    else {
		check_sum+=Number(ths.value);
		$('#check_sum').html(check_sum);
        var count = $("input[name='id']:checkbox:checked").length;
        if (count == $("input[name='id']:checkbox").length) {
            $("input[name=all]:checkbox").prop("checked", true);
        }
    }
}

   $('.btn-details').click(function () {
	$.post("/Yshclbssb.php/Home/Index/get_userinfo_from_uname.html", { uname: $(this).attr('href') }, function(msg) { $('#user_info').html(msg); });
    return false;
  });
	</script>

</body>

</html>