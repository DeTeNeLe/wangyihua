<?php if (!defined('THINK_PATH')) exit();?><div class="viewcon <?php if($is_p == 1): ?>lout<?php endif; if($is_p == 0): ?>lin<?php endif; ?>" style="display: none;">
	<div <?php if($is_p != 1): ?>style="display:none;"<?php endif; ?>>
	<div>
		<span class="left">收款人：</span>
		<!--<span><?php echo ($jsbz_user['ue_theme']); ?>(<?php echo (replace_xing($jsbz_user['ue_phone'])); ?>)</span>-->
		<span>
	             <?php echo ($jsbz_user['ue_theme']); ?>
			<!--(<?php echo ($jsbz_user['ue_phone']); ?>)  -->
	         </span>
	</div>
	<div>
		<span class="left">收款人电话：</span>
		<!--<span><?php echo ($jsbz_user['ue_theme']); ?>(<?php echo (replace_xing($jsbz_user['ue_phone'])); ?>)</span>-->
		<span>
	             <?php echo ($jsbz_user['ue_phone']); ?>
	         </span>
	</div>
	<div>
		<span class="left">收款银行：</span>
		<span><?php echo (authcode_decode($jsbz_user['yhmc'])); ?></span>
	</div>
	<div>
		<span class="left">收款卡号：</span>
		<span class="clickbank"><?php echo (authcode_decode($jsbz_user['yhzh'])); ?></span>
	</div>
	<div>
		<span class="left">支行信息：</span>
		<span><?php echo (authcode_decode($jsbz_user['yhzhxx'])); ?></span>
	</div>
	<div>
		<span class="left copyclass">支付宝：</span>
		<span id="zfb"><?php echo (authcode_decode($jsbz_user['zfb'])); ?></span>
	</div>
	<!--<div>-->
		<!--<span class="left">微信：</span>-->
		<!--<span><?php echo (authcode_decode($jsbz_user['weixin'])); ?></span>-->
	<!--</div>-->
	<div>
		<span class="left">备注：</span>
		<span><?php echo ($pp['jsbz_user']['remark']); ?></span>
	</div>
</div>

<div <?php if($is_p != 0): ?>style="display:none;"<?php endif; ?>>
<div>
	<span class="left">打款人：</span>
	<!--<span><?php echo ($tgbz_user['ue_theme']); ?>(<?php echo (replace_xing($tgbz_user['ue_phone'])); ?>)</span>-->
	<span><?php echo ($tgbz_user['ue_theme']); ?></span>
</div>
<div>
	<span class="left">打款人手机号：</span>
	<!--<span><?php echo ($tgbz_user['ue_theme']); ?>(<?php echo (replace_xing($tgbz_user['ue_phone'])); ?>)</span>-->
	<span><?php echo ($tgbz_user['ue_phone']); ?></span>
</div>
<div>
	<span class="left">打款人银行：</span>
	<span><?php echo (authcode_decode($tgbz_user['yhmc'])); ?></span>
</div>
<div>
	<span class="left">打款人卡号：</span>
	<span class="clickbank"><?php echo (authcode_decode($tgbz_user['yhzh'])); ?></span>
</div>
<div>
	<span class="left">打款人支行信息：</span>
	<span><?php echo (authcode_decode($tgbz_user['yhzhxx'])); ?></span>
</div>
<div>
	<span class="left">打款人微信：</span>
	<span><?php echo (authcode_decode($tgbz_user['weixin'])); ?></span>
</div>
<div>
	<span class="left">打款人支付宝：</span>
	<span><?php echo (authcode_decode($tgbz_user['zfb'])); ?></span>
</div>
<!--<div>
    <span class="left">打款人领导人：</span>
    <span><?php echo ($tgbz_accname['ue_theme']); ?>(<?php echo (replace_xing($tgbz_accname['ue_phone'])); ?>)</span>
    <span><?php echo ($tgbz_accname['ue_theme']); ?>(<?php echo ($tgbz_accname['ue_phone']); ?>)</span>
</div>

<div>
    <span class="left">持有人：</span>
    <span><?php echo (authcode_decode($jsbz_user['yhckr'])); ?></span>
</div>-->

<!--<div>
    <span class="left">收款人领导人：</span>
    <span><?php echo ($jsbz_accname['ue_theme']); ?>(<?php echo (replace_xing($jsbz_accname['ue_phone'])); ?>)</span>
    <span><?php echo ($jsbz_accname['ue_theme']); ?>(<?php echo ($jsbz_accname['ue_phone']); ?>)</span>
</div>-->
<div>
	<span>匹配时间：<?php echo ($ppdd["date"]); ?></span>
</div>
<div>
	<span>打款时间：<?php echo ($ppdd["date_hk"]); ?></span>
</div>
</div>
<div>
	<?php if($ppdd["pic"] != '0'): ?><a class="btn btn-success viewimg" data="<?php echo ($ppdd["pic"]); ?>">查看打款凭证</a><?php endif; ?>
	<a class="btn btn-success sendmessage" target="#" data="<?php echo ($ppdd["id"]); ?>">留言</a>
	<?php if($ppdd["zt"] == '0' and $is_p == 1): if($ppdd["ts_zt"] == '1'): ?><a class="btn btn-success tousued" target="#">已被投诉</a>
			<?php else: ?>
			<a class="btn btn-success confirmpay" target="#" data="<?php echo ($ppdd["id"]); ?>">确认已付款</a><?php endif; endif; ?>
	<?php if($ppdd["zt"] == '1' and $is_p == 1): if($ppdd["ts_zt"] == '2'): ?><a class="btn btn-success tousued" target="#">已被投诉</a>
			<?php else: ?>
			<a class="btn btn-success confirmpay" target="#" data="<?php echo ($ppdd["id"]); ?>"><?php echo C('jjqrtime');?>小时未确认投诉</a><?php endif; endif; ?>

	<?php if($ppdd["zt"] == '0' and $is_p == 0): ?><a class="btn btn-success wdktousu" target="#" data="<?php echo ($ppdd["g_id"]); ?>"><?php echo C('jjdktime');?>小时未打款投诉</a><?php endif; ?>
	<?php if($ppdd["zt"] == '1' and $is_p == 0): if($ppdd["ts_zt"] == '3'): ?><a class="btn btn-success tousued" target="#">未确认收款被投诉</a>
			<?php else: ?>
			<a class="btn btn-success confirmget" target="#" data="<?php echo ($ppdd["id"]); ?>">确认收款/投诉</a><?php endif; endif; ?>

	<!--<a class="btn btn-success payfromethcheck" target="#" data="<?php echo ($ppdd["id"]); ?>">ETH打款检测</a>-->
</div>
</div>

</div>
<input type="hidden" />

</div>
<style>
	.lout div {
		/*height:20px;*/
	}

	.viewcon {
		line-height: 1.7em;
	}

	.lout div .left {
		font-size: 16px;
	}
</style>