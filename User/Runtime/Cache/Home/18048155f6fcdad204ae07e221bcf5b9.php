<?php if (!defined('THINK_PATH')) exit();?><span style="font-size:1.5em;margin-left:10px;vertical-align:middle">是否开启自动预约排单：</span>
<div class="switch ">
	<input type="checkbox" class="newyuyueswitch" data-on-text="已开启" data-off-text="已关闭" <?php if($userData['isyuyue'] == 1): ?>checked=""<?php endif; ?>/>
</div>
<div class="help tips">
	<!--开启后每隔指定天数系统会自动为您排单，请确保账户中的手续费充足。-->开启后连续指定天数排单
</div>
<div class="col-lg-10 newyuyue_list" style="margin: 30px auto;font-size: 1.4em;<?php if( $userData['isyuyue'] == 0 ): ?>display:none<?php endif; ?>">
	<div class="form-group">
		<select class="form-control" name="yy_days" id="yy_days" style="width: 38%;display: inline-block;" <?php if($userData['isyuyue'] == 1 ): ?>disabled<?php endif; ?> >
			<?php $__FOR_START_1349543085__=1;$__FOR_END_1349543085__=$schedule_max_day;for($i=$__FOR_START_1349543085__;$i < $__FOR_END_1349543085__;$i+=1){ ?><option value="<?php echo ($i); ?>" <?php if($userData['yuyuezhouqi'] == $i): ?>selected<?php endif; ?> >连续<?php echo ($i); ?>天</option><?php } ?>
		</select>
		<!--<input type="text" class="form-control" id="yy_money" placeholder="预约金额" style="width: 50%;display: inline-block;" value="20000" 
		<?php if($userData['isyuyue'] == 1 ): ?>disabled<?php endif; ?> />-->
		<select  name="yy_money" id="yy_money" <?php if($userData['isyuyue'] == 1 ): ?>disabled<?php endif; ?> >
			<?php if(is_array($can_choice_jb_arr)): $i = 0; $__LIST__ = $can_choice_jb_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$can_choice_jb): $mod = ($i % 2 );++$i;?><option value="<?php echo ($can_choice_jb); ?>"  ><?php echo ($can_choice_jb); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		</select>
		<!--<input type="text" class="form-control" id="yuyue_cur_time" placeholder="预约开始时间,如：2019-05-19 10:10:00" style="width: 70%;display: inline-block;" value="" 
		<?php if($userData['isyuyue'] == 1 ): ?>disabled<?php endif; ?> />-->
		<br>
		<br>
		<?php if($userData['isyuyue'] == 1 ): ?><span style="color:red;">预约开始的时间</span>
			<br>
			<?php if(!empty($userData["yuyue_cur_time"])): ?><input type="text"  disabled value="<?php echo (date('Y-m-d',strtotime($userData['yuyue_cur_time']))); ?>" ><?php endif; endif; ?>
		<?php if($userData['isyuyue'] == 0 ): ?><span style="color:red;">请在下方选择预约开始的时间</span>
			<br>
			<input type="date" class="form-control" id="yuyue_cur_time" placeholder="预约开始时间,如：2019-05-19 10:10:00" style="width: 70%;display: inline-block;" value=""  /><?php endif; ?>

		<input type="button" class="form-control btn btn-primary yy_submit" value="提交" style="width: 20%;display: inline-block;" <?php if($userData['isyuyue'] == 1 ): ?>disabled<?php endif; ?>/>
		<table class="table">
			<tbody>
				<tr>
					<th>预约日期</th>
					<th>预约金额</th>
					<th>状态</th>
				</tr>
				<?php echo ($yuyue_table); ?>
			</tbody>
		</table>
	</div>
</div>
<style>
	div.switch {
		display: inline-block;
		height: 40px;
		margin: 20px 0;
	}
	
	.help {
		margin: 10px;
		font-size: 1.1em;
	}
</style>
<link rel="stylesheet" href="/assets/wns/css/bootstrap.min.css" />
<link rel="stylesheet" href="/assets/wns/css/bootstrap-switch.min.css" />
<script src="/assets/wns/js/jquery.min.js"></script>
<script src="/assets/wns/js/bootstrap-switch.min.js"></script>
<script src="/assets/wns/js/layui.all.js"></script>
<script>
	(function() {
		$("#yy_days").find("option[value = '" + "<?php echo ($userData['yuyuezhouqi']); ?>" + "']").attr("selected", "selected");
		$("#yy_money").val("<?php echo ($userData['yuyuemoney']); ?>");
		$('.newyuyueswitch').bootstrapSwitch();
		$('.newyuyueswitch').on('switchChange.bootstrapSwitch', function(event, state) {
			var o = $(this);
			if(state) {
				$('.newyuyue_list').show();
				layer.tips('预约开启成功', o);
				//				$.ajax({
				//					type: 'post',
				//					url: '/Home/Index/home_post',
				//					data: 'act=updateyystaus&do=1',
				//					success: function(data) {
				//						if(data.sf == 1) {
				//							$('.newyuyue_list').show();
				//							layer.tips('预约开启成功', o);
				//						} else {
				//							//$('.newyuyueswitch').bootstrapSwitch('toggleState');
				//							layer.tips(data.nr, o);
				//							setTimeout(function() {
				//								location.reload()
				//							}, 2000);
				//						}
				//					}
				//				});
			} else {
				$('.newyuyue_list').hide();
				layer.tips('预约已关闭', o);
				//				$.ajax({
				//					type: 'post',
				//					url: '/Home/Index/home_post',
				//					data: 'act=updateyystaus&do=0',
				//					success: function(data) {
				//						if(data.sf == 1) {
				//							$('.newyuyue_list').hide();
				//							layer.tips('预约已关闭', o);
				//						} else {
				//							$('.newyuyueswitch').bootstrapSwitch('toggleState');
				//							layer.tips(data.nr, o);
				//						}
				//					}
				//				});
			}
		});

		$(document).on('click', '.yy_submit', function() {
			var o = $(this);
			var yy_days = $('#yy_days').val();
			var yy_money = $('#yy_money').val();
			var yuyue_cur_time = $('#yuyue_cur_time').val();
			$.ajax({
				type: 'post',
				url: '/Home/Index/home_post',
				data: 'act=updateyyset&d=' + yy_days + '&m=' + yy_money + '&state=1' + '&t=' + yuyue_cur_time,
				success: function(data) {
					layer.tips(data.nr, o);
					if(data.sf == 1) {
						setTimeout(function() {
							location.reload();
						}, 1000)
					}
				}
			});
		})

		// $(document).on('click','.newyuyueswitch',function(){
		// 	console.log($('.newyuyueswitch').bootstrapSwitch('state'));
		// 	if ($(this).is(':checked')) {
		// 		//on
		// 		$('.newyuyue_list').show();
		// 	}else{
		// 		$('.newyuyue_list').hide();
		// 	}
		// })
		// $(document).on('click','.yy_submit',function(){
		// 	var yy_days = $('#yy_days').val();
		// 	var yy_money = $('#yy_money').val();
		// 	$.ajax({
		// 		type:'post',
		// 		url:'?',
		// 		data:'act=addyuyue&yydays='+yy_days+'&yymoney='+yy_money,
		// 		timeout:7000,
		// 		success:function(data){
		// 			if (data==1) {
		// 				layer.msg('添加成功');
		// 				setTimeout(function(){location.reload()},1000)
		// 			}else{
		// 				layer.msg(data)
		// 			}
		// 		},
		// 		complete:function(XMLHttprequest){
		// 			if (XMLHttprequest.statusText=='timeout') {
		// 				layer.msg('连接超时,请更换网络环境或稍后再试!');
		// 			}
		// 		}
		// 	});
		// }).on('click','.del_yypd',function(){
		// 	var id = $(this).attr('data');
		// 	layer.confirm('确定要删除这个预约排单请求吗？',{title:'预约排单'},function(index,layero){
		// 		$.ajax({
		// 			type:'post',
		// 			url:'?',
		// 			data:'act=delyuyue&id='+id,
		// 			timeout:7000,
		// 			success:function(data){
		// 				if (data==1) {
		// 					layer.msg('删除成功！');
		// 					layer.close(index);
		// 				}else{
		// 					layer.msg(data);
		// 				}
		// 			},
		// 			complete:function(XMLHttprequest){
		// 				if (XMLHttprequest.statusText=='timeout') {
		// 					layer.msg('连接超时,请更换网络环境或稍后再试!');
		// 					layer.close(index);
		// 				}
		// 			}
		// 		});
		// 	});
		// })

	})();
</script>