<?php if (!defined('THINK_PATH')) exit();?> <div class="sellcon">
   <div style="display:none">
    银行卡：
    <br />
    <select name="bankcard" id="bankcard">
	    <option name="bankcard" value="18364">农业银行(62284517748315)</option>
	</select>
	<br />
   </div>
   <div>
    提现账户：
    <br />
    <select name="selltype" id="selltype"><option name="selltype" value="0">本息账户</option><option name="selltype" value="1">奖励账户</option></select>
    <input type="hidden" class="benxi_cash" value="<?php echo ($benxi_cash); ?>" />
    <input type="hidden" class="jiangli_cash" value="<?php echo ($jiangli_cash); ?>" />
    <br />
    <br />
    <input type="number" style="width:250px;" placeholder="最多可提现金额<?php echo ($benxi_cash); ?>元" id="sell_num" />
   </div>
   <div id="js_tips">
   </div>
  </div>