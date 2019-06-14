<?php if (!defined('THINK_PATH')) exit();?><div class='buyincon'>
     <div>
       <!--<input type='number' id='buyin_num' placeholder='金额' style='height:40px'>-->
         开仓积分：<select name="buyin_num" id="buyin_num" style="width:100%;">
             <?php if(is_array($can_choice_jb_arr)): $i = 0; $__LIST__ = $can_choice_jb_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$jb): $mod = ($i % 2 );++$i;?><option value="<?php echo ($jb); ?>"><?php echo ($jb); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
         </select>
     </div>

    <div>
       <!--<input type='number' id='buyin_num' placeholder='金额' style='height:40px'>-->
         二级密码：<input type='password' name="secpwd" id='secpwd' style='height:40px;width:100%;' />
     </div>

    <div>
     <!--金额必须是<?php echo ($jj01); ?>的整数倍<br>您当前的排单下限是 <span id='tigongxiaxian'><?php echo get_min();?></span> 元，上限是 <span  id='tigongshangxian'><?php echo get_max();?></span>元。-->
       <!--<br>排单每<?php echo C('paidanb_every');?>元消耗<?php echo C('paidanb_count');?>个通证积分，您当前拥有通证积分数量：<?php echo ($pin_zs); ?>。-->
        <!--<span id="cur_choice_buyin_num"></span>元-->
        消耗 <span id="cur_buyin_num" ></span> 个通证
    </div>
    <input type="hidden" id="paidanb_every" value="<?php echo C('paidanb_every');?>">
    <input type="hidden" id="paidanb_count" value="<?php echo C('paidanb_count');?>">
   </div>
<script>
    $(function(){
        var paidanb_every =  $('#paidanb_every').val();
        var paidanb_count =  $('#paidanb_count').val();
        var buyin_num = $('#buyin_num').val();

        //初始化
        var cur_buyin_num = buyin_num * ( paidanb_count / paidanb_every );
        //$("#cur_choice_buyin_num").html(buyin_num);
        $("#cur_buyin_num").html(cur_buyin_num);

        $("#buyin_num").on('change',function(){
            buyin_num = $("#buyin_num").val();
            var cur_buyin_num = buyin_num * ( paidanb_count / paidanb_every );
            //$("#cur_choice_buyin_num").html(buyin_num);
            $("#cur_buyin_num").html(cur_buyin_num);
        })
    })
</script>