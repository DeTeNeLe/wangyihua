<?php if (!defined('THINK_PATH')) exit();?> <div class="sellcon">
   <div style="display:none">
    银行卡：
    <br />
    <select name="bankcard" id="bankcard">
      <option name="bankcard" value="18364">农业银行(62284517748315)</option>
  </select>
  <br />
   </div>
     <style>
         #selltype{
             height:30px;
         }
         #sell_num{
             height:30px;
         }
     </style>
   <div>
    申请交割：
    <br />
    <select name="selltype" id="selltype"><option name="selltype" value="0">通证奖金</option><option name="selltype" value="1">交割积分</option></select>
    <input type="hidden" class="benxi_cash" value="<?php echo ($benxi_cash); ?>" />
    <input type="hidden" class="jiangli_cash" value="<?php echo ($jiangli_cash); ?>" />
    <br />
    <br />
    <input type="number" style="width:250px;" placeholder="总积分<?php echo ($benxi_cash); ?>" id="sell_num" />
    <br />
    <br />
    <input type='password' tyle="width:250px;" name="secpwd" id='secpwd' placeholder="二级密码" />
   </div>
   <div id="js_tips">
   </div>
  </div>