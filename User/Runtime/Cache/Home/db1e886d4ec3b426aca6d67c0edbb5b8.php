<?php if (!defined('THINK_PATH')) exit();?> <?php if(is_array($list)): foreach($list as $key=>$v): ?>用户:<?php echo ($v["user_nc"]); ?> 时间:<?php echo ($v["date"]); ?><br>
    内容:<span style="color:red"><?php echo ($v["nr"]); ?></span> <hr><?php endforeach; endif; ?>
 <p>你可以与这位参与者消息联系。</p> 
  <hr /> 
  <div class="col-lg-12" id="msg"> 
  </div>
  <div class="col-lg-12"> 
   <form action="/Home/Index/home_ddxx_ly_cl/" method="post"> 
    <input name="id" value="<?php echo ($ppddxx["id"]); ?>" id="id" type="hidden" /> 
    <textarea rows="5" style="overflow: auto;resize: none;" cols="60" name="mesg" id="mesg"> </textarea> 
    <br /> 
    <br /> 
    <input type="submit" class="btn btn-info" value="提交" /> 
   </form> 
  </div>