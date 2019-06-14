<?php if (!defined('THINK_PATH')) exit();?>  <div class="help tips">
   系统自动检测打款是否成功，到账会有延迟，请耐心等待。
  </div>
  <div class="col-lg-10 newyuyue_list" style="margin: 30px auto;font-size: 1.4em;"> 
   <div class="form-group">
    <table class="table"> 
     <tbody>
      <tr>
       <th>打款人账户信息</th>
      </tr> 
      <tr>
	    <th>[eth]&nbsp;<?php echo ($p_user["eth_addr"]); ?></th>
	  </tr>
	  <tr>
       <th>收款人账户信息</th>
      </tr> 
      <tr>
	    <th>[eth]&nbsp;<?php echo ($g_user["eth_addr"]); ?></th>
	  </tr>
	  <tr>
       <th>支付结果</th>
      </tr> 
      <tr>
	    <th><span><?php echo ($payresult); ?></span></th>
	  </tr>
     </tbody>
    </table> 
   </div> 
  </div> 
  <style>
	div.switch{display: inline-block;height: 40px;margin:20px 0;}
	.help{margin:10px;font-size: 1.1em;}
</style> 
  <link rel="stylesheet" href="/assets/wns/css/bootstrap.min.css" /> 
  <link rel="stylesheet" href="/assets/wns/css/bootstrap-switch.min.css" /> 
  <script src="/assets/wns/js/jquery.min.js"></script> 
  <script src="/assets/wns/js/bootstrap-switch.min.js"></script> 
  <script src="/assets/wns/js/layui.all.js"></script>