<?php if (!defined('THINK_PATH')) exit(); ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>回答问题文档</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<style type="text/css">
		
		div{width:778px;margin:0 auto;background:#fff;text-align:left;}
</style>
<script language="javascript">
	$(function(){
    $('.error').css({'position':'absolute','left':($(window).width()-490)/2});
	$(window).resize(function(){  
    $('.error').css({'position':'absolute','left':($(window).width()-490)/2});
    })  
});  
</script> 
</head>


<body style="background:#edf6fa;" algin="center">
	<script>alert('新人温馨提示：由于您是第一次提供帮助，需要先回答一下几个问题。');</script>
	<div >
	<form action="<?php echo U('index/question');?>" id="form" name="form" method="post" enctype="multipart/form-data" >
		<table>
			<tr>
				<td>1、你是否了解<?php echo C('app_name');?>并且自愿参与共享平台？</td>
			</tr>
			<tr>
			   <td><input name="question_1_A" type="checkbox" value="A" />我不了解</td>
			</tr>
			<tr>
			   <td><input name="question_1_B" type="checkbox" value="B" />我已全部了解</td>
			</tr>
			
			<tr>
				<td>2、你是否同意对方任意使用支付平台向你支付并能及时确认吗？</td>
			</tr>
			<tr>
			   <td><input name="question_2_A" type="checkbox" value="A" />同意</td>
			</tr>
			<tr>
			   <td><input name="question_2_B" type="checkbox" value="B" /> 不同意</td>
			</tr>
			
			<tr>
				<td>3、如果因为本人原因导致的各种处罚你愿意接受吗？</td>
			</tr>
			<tr>
			   <td><input name="question_3_A" type="checkbox" value="A" />我愿意</td>
			</tr>
			<tr>
			   <td><input name="question_3_B" type="checkbox" value="B" />我不愿意</td>
			</tr>
			<td>4、你能保证自已永远做个正能量诚信会员吗?不投机不诋毁</td>
			</tr>
			<tr>
			   <td><input name="question_4_A" type="checkbox" value="A" />我能保证</td>
			</tr>
			<tr>
			   <td><input name="question_4_B" type="checkbox" value="B" />我不能保证</td>
			</tr>
			<td>5、如果不是发起人导致的人为损失你愿意承担一切经济损失吗？</td>
			</tr>
			<tr>
			   <td><input name="question_5_A" type="checkbox" value="A" />愿意</td>
			</tr>
			<tr>
			   <td><input name="question_5_B" type="checkbox" value="B" />不愿意</td>
			</tr>
			<tr >
				<td style="text-align: center"><input type="reset" value="重置" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="提交" /></td>
				
			</tr>
		</table>
        
          
         
     </form>
     
	</div>
 
</body>

</html>