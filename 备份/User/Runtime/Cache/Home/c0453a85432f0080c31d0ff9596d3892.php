<?php if (!defined('THINK_PATH')) exit();?>  <div class="viewcon" style="display: block;">
   <?php if($ppdd_count == 0): ?><div>
      该申请单还未匹配
   <div><?php endif; ?>
   <?php if($ppdd_count != 0): ?><div>
    已匹配金额：<?php echo ($jsbz_ppjb); ?>
   </div>
   <?php if(is_array($ppdd_list)): foreach($ppdd_list as $key=>$pp): ?><div class="l_item lin status_pp_g_<?php echo (get_pp_g_status($pp["pporderid"])); ?> open">
    <span class="orderid">订单号<?php echo ($pp["pporderid"]); ?></span>
    <span> (匹配买入订单<?php echo ($pp['tgbz_data']['orderid']); ?>))</span>
    <div>
     <div class="bj status_pp_g_<?php echo (get_pp_g_status($pp["pporderid"])); ?>"></div>
     <div>
      <span class="bold"><?php echo ($pp['tgbz_user']['ue_theme']); ?>&gt; <span class="cash"><?php echo ($pp["jb"]); ?></span> &gt; 您 </span>
      <span>订单创建日期:<?php echo ($pp["date"]); ?></span>
     </div>
    </div>
   </div>
   <div class="viewcon lout" style="display: none;">
    <div>
     <span class="left">打款人：</span>
     <span><?php echo ($pp['tgbz_user']['ue_theme']); ?>(<?php echo (replace_xing($pp['tgbz_user']['ue_phone'])); ?>)</span>
    </div>
    <div>
     <span class="left">打款人领导人：</span>
     <span><?php echo ($pp['tgbz_accname']['ue_theme']); ?>(<?php echo (replace_xing($pp['tgbz_accname']['ue_phone'])); ?>)</span>
    </div>
    <div class="card">
     <div>
      <span class="left">银行：</span>
      <span><?php echo (authcode_decode($pp['jsbz_user']['yhmc'])); ?></span>
     </div>
     <div>
      <span class="left">卡号：</span>
      <span class="clickbank"><?php echo (authcode_decode($pp['jsbz_user']['yhzh'])); ?></span>
     </div>
     <div>
      <span class="left">支行：</span>
      <span><?php echo (authcode_decode($pp['jsbz_user']['yhzhxx'])); ?></span>
     </div>
     <div>
      <span class="left">持有人：</span>
      <span><?php echo (authcode_decode($pp['jsbz_user']['yhckr'])); ?></span>
     </div>
     <div>
      <span class="left">支付宝：</span>
      <span><?php echo (authcode_decode($pp['jsbz_user']['zfb'])); ?></span>
     </div>
	 <div>
      <span class="left">微信：</span>
      <span><?php echo (authcode_decode($pp['jsbz_user']['weixin'])); ?></span>
     </div>
     <div>
      <span class="left">备注：</span>
      <span></span>
     </div>
    </div>
    <div>
     <span class="left">收款人：</span>
     <span><?php echo ($pp['jsbz_user']['ue_theme']); ?>(<?php echo (replace_xing($pp['jsbz_user']['ue_phone'])); ?>)</span>
    </div>
    <div>
     <span class="left">收款人领导人：</span>
     <span><?php echo ($pp['jsbz_accname']['ue_theme']); ?>(<?php echo (replace_xing($pp['jsbz_accname']['ue_phone'])); ?>)</span>
    </div>
    <div>
     <span>打款时间：<?php echo ($pp["date"]); ?></span>
    </div>
    <div>
    <?php if($pp["pic"] != '0'): ?><a class="btn btn-success viewimg" data="<?php echo ($pp["pic"]); ?>">查看打款凭证</a><?php endif; ?>
    </div>
   </div><?php endforeach; endif; endif; ?>
  </div>