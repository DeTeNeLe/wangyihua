<?php if (!defined('THINK_PATH')) exit();?><div class='buyincon'>
     <div>
       <input type='number' id='buyin_num' placeholder='金额' style='height:40px'><span class='wine'></span>X<span class='winnum'>0</span>
     </div>
    <div>
     金额必须是<?php echo ($jj01); ?>的整数倍<br>您当前的排单下限是 <span id='tigongxiaxian'><?php echo get_min();?></span> 元，上限是 <span  id='tigongshangxian'><?php echo get_max();?></span>元。
       <br>排单每<?php echo C('paidanb_every');?>元消耗<?php echo C('paidanb_count');?>个手续费，您当前拥有手续费数量：<?php echo ($pin_zs); ?>。 
    </div>
   </div>