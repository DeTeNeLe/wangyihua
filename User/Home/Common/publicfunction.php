<?php
function create_reg_code()
{
    return rand_string(2, 2) . rand_string(3, 3) . rand_string(2, 1) . rand_string(2, 3);
}
/**
+----------------------------------------------------------
* 产生随机字串， 可用来自动生成密码，验证码，表单令牌等
* 默认长度6位 字母和数字混合 支持中文
+----------------------------------------------------------
* @param string $len 长度
* @param string $type 字串类型
* 0 字母 1 数字 4 字母+数字 其它 混合
* @param string $addChars 额外字符
+----------------------------------------------------------
* @return string
+----------------------------------------------------------
*/
function rand_string($len = 6, $type = '', $addChars = '')
{
    $str = '';
    switch ($type) {
        case 0:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        case 1:
            $chars = str_repeat('0123456789', 3);
            break;
        case 2:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
            break;
        case 3:
            $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        case 4:
            $chars = 'abcdefghijkmnpqrstuvwxyz23456789' . $addChars;
            break;
        default:
            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
            break;
    }
    if ($len > 10) {
        //位数过长重复字符串一定次数
        $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
    }
    if ($type != 4) {
        $chars = str_shuffle($chars);
        $str = substr($chars, 0, $len);
    } else {
        // 中文随机字
        for ($i = 0; $i < $len; $i++) {
            $str .= msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
        }
    }
    return $str;
}
function createorderid($pre)
{
	usleep(1000000);
    return $pre . strtotime("now") . substr(uniqid(rand(1, 10000)), 7, 2);
    //return $pre . date('md').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}
//tgbz中的持仓总量【买入】
function get_tgbz_totaljb($mainid)
{
    return M('tgbz')->where('mainid=' . $mainid)->sum('jb');
}
//jsbz中的交割总量【买出】
function get_jsbz_totaljb($mainid)
{
    return M('jsbz')->where('mainid=' . $mainid)->sum('jb');
}
//判断买入订单的状态
function get_p_status($orderid)
{
    $tgbz = M('tgbz')->where("orderid='" . $orderid . "'")->find();
    if ($tgbz['zt'] == 0 && $tgbz['ppjb'] == 0) {
        return 0; //排队中
    }
    if ($tgbz['total'] == $tgbz['ppjb']) {
        return 2; //已完成
    }
    $tgbz_ppzjb = M('ppdd')->alias('ppdd')->join("left JOIN ot_tgbz tgbz on ppdd.p_id = tgbz.id where tgbz.user='" . $_SESSION['uname'] . "' and ppdd.zt = 0 and mainid =" . $tgbz['mainid'])->sum('tgbz.jb');
    if ($tgbz_ppzjb > 0 && $tgbz_ppzjb < $tgbz['total']) {
        return 3; //部分完成
    }
    if ($tgbz_ppzjb == $tgbz['total']) {
        return 5; //已全部匹配
    }
    $tgbz_ppcgjb = M('ppdd')->alias('ppdd')->join("left JOIN ot_tgbz tgbz on ppdd.p_id = tgbz.id where tgbz.user='" . $_SESSION['uname'] . "' and ppdd.zt = 2 and mainid =" . $tgbz['mainid'])->sum('tgbz.jb');
    if ($tgbz_ppcgjb > 0 && $tgbz_ppcgjb < $tgbz['total']) {
        return 4; //部分完成
    }
    if ($tgbz['ppjb'] > 0 && $tgbz['ppjb'] < $tgbz['total']) {
        return 3; //部分完成
    }
}

/**
 * 判断买入订单的状态
 * @param $mainid
 * @param int $isprepay  $isprepay为1表示预付款 ，为2表示尾款
 * @return int
 */
function get_p_status_pc($mainid ,$isprepay = 1)
{
    $tgbz = M('tgbz')->where("mainid='" . $mainid . "' and isprepay = $isprepay")->select();
    //预付款总金额
    $yfk_zje = 0;
    //预付款已付款完成的总额
    $ywc_yfk_zje = 0;
    foreach($tgbz as $item){
        $yfk_zje += $item['jb'];
        if($item['zt'] == 1 && $item['qr_zt'] == 1){
            $ywc_yfk_zje += $item['jb'];
        }
    }
    if( $ywc_yfk_zje == 0 ){
        return 0;//排队中
    }
    if( $yfk_zje == $ywc_yfk_zje ){ //预付款的总金额
        return 2;//已完成
    }
    if( $ywc_yfk_zje < $yfk_zje ){
        return 3;//部分完成
        //return 5; //已全部匹配
        //return 4; //部分完成
    }
}

//判断买出订单的状态
function get_g_status($orderid)
{

    $jsbz = M('jsbz')->where("orderid='" . $orderid . "'")->find();
    $map['mainid'] = $jsbz['mainid'];
    $all_jsbz_mainid_data = M('jsbz')->where($map)->select();
    //已近支付完成的
    $finished_num = 0;//已近支付完成次数
//    $ppnum = 0;  //已经匹配的次数
//    $zfnum = 0; //已经支付的次数
    foreach( $all_jsbz_mainid_data as $item )
    {
        if( $item['zt'] == 1 && $item['qr_zt'] == 1 )
        {
            $finished_num++;
        }
//        if(in_array($item['zt'],[0,1]))
//        {
//            $ppnum++;
//        }
//        if(in_array($item['zt'],[1]))
//        {
//            $zfnum++;
//        }
    }

    if( $finished_num > 0 && count($all_jsbz_mainid_data) == $finished_num )
    {
        return 2; //已完成
    }

    if( $finished_num > 0 && count($all_jsbz_mainid_data) > $finished_num )
    {
        return 3; //部分匹配
    }
    if( $finished_num == 0 )
    {
        return 0; //排队中
    }

    /*
     $jsbz = M('jsbz')->where("orderid='" . $orderid . "'")->find();
    if ($jsbz['zt'] == 0) {
        return 0; //排队中
    }
    if ($jsbz['total'] == $jsbz['ppjb']) {
        return 2; //已完成
    }
    $jsbz_ppzjb = M('ppdd')->alias('ppdd')->join("left JOIN ot_jsbz jsbz on ppdd.g_id = jsbz.id where jsbz.user='" . $_SESSION['uname'] . "' and ppdd.zt = 0 and jsbz.mainid =" . $jsbz['mainid'])->sum('jsbz.jb');
    if ($jsbz_ppzjb > 0 && $jsbz_ppzjb < $jsbz['total']) {
        return 3; //部分匹配
    }
    if ($jsbz_ppzjb == $jsbz['total']) {
        return 5; //已全部匹配
    }
    $jsbz_ppcgjb = M('ppdd')->alias('ppdd')->join("left JOIN ot_jsbz jsbz on ppdd.g_id = jsbz.id where jsbz.user='" . $_SESSION['uname'] . "' and ppdd.zt <> 0 and jsbz.mainid =" . $jsbz['mainid'])->sum('jsbz.jb');
    if ($jsbz_ppcgjb > 0 && $jsbz_ppcgjb < $jsbz['total']) {
        return 4; //部分完成
    }
    if ($jsbz['ppjb'] > 0 && $jsbz['ppjb'] < $jsbz['total']) {
        return 3; //部分匹配
    }
    */
}
//买入订单匹配
function get_pp_p_status($pporderid)
{
    $ppdd = M('ppdd')->where("pporderid='" . $pporderid . "'")->find();
    if ($ppdd['zt'] == 0) {
        return 0; //等待打款
    }
    if ($ppdd['zt'] == 1) {
        return 1; //等待对方确认
    }
    if ($ppdd['zt'] == 2) {
        return 2; //已完成
    }
}
////买出订单匹配
function get_pp_g_status($pporderid)
{
    $ppdd = M('ppdd')->where("pporderid='" . $pporderid . "'")->find();
    if ($ppdd['zt'] == 0) {
        return 0; //等待对方打款
    }
    if ($ppdd['zt'] == 1) {
        return 1; //等待您的确认
    }
    if ($ppdd['zt'] == 2) {
        return 2; //已完成
    }
}
function get_pro_num($money)
{
    return $money / 1000;
}
function get_userinfo($u_p, $field)
{
    $user = M('user')->field($field)->where("UE_account = '" . $u_p . "' or UE_phone = '" . $u_p . "'")->find();
    return $user ? $user[$field] : '-1';
}
function getpporderid_by_jj_r_id($r_id)
{
    $ppdd = M('ppdd')->where("id=" . $r_id)->find();
    return $ppdd['pporderid'];
}
function getporderid_by_jj_r_id($r_id)
{
    $ppdd = M('ppdd')->where("id=" . $r_id)->find();
    $tgbz = M('tgbz')->where("id=" . $ppdd['p_id'])->find();
    return $tgbz['orderid'];
}
function getpjb_by_jj_r_id($r_id)
{
    $ppdd = M('ppdd')->where("id=" . $r_id)->find();
    $tgbz = M('tgbz')->where("id=" . $ppdd['p_id'])->find();
    return $tgbz['jb'];
}
function getptotal_by_jj_r_id($r_id)
{
    $ppdd = M('ppdd')->where("id=" . $r_id)->find();
    $tgbz = M('tgbz')->where("id=" . $ppdd['p_id'])->find();
    $tgbz_total = M('tgbz')->where("qr_zt = 1 and mainid=" . $tgbz['mainid'])->sum('jb');
    return $tgbz_total;
}
function get_jj_type($type)
{
    switch ($type) {
        case "jlj":
            return "分享奖";
            break;
		case "tgbz":
            return "买入";
            break;
		case "jsbz":
            return "买出";
            break;
		case "xtzs":
            return "系统赠送";
            break;
		case "tiqian_lx":
            return "提前打款";
            break;
    }
}
function getUserPPSucCount()
{
    return M('ppdd')->where(array('p_user' => $_SESSION['uname'], 'zt' => 2))->count();
}
////获取用户买入的数量
function getUserTGBZCount()
{
    return M('tgbz')->where(array('user' => $_SESSION['uname']))->count();
}
//获取用户累计成交金额
function getUserTGBZJBSum($user)
{
    $map['zt'] = 1;
    $map['user'] = $user;
    return M('user_jj')->where($map)->sum('jb');
}
//获取用户上一笔订单金额
function getUserLastTGBZJB($user)
{
    $map['user'] = $user;
    $user_jj = M('user_jj')->where($map)->order('date desc')->limit(1)->select();
    if ($user_jj) {
        return getptotal_by_jj_r_id($user_jj[0]['r_id']);
    }
    return 0;
}
function auto_tgbz_yuyue()
{
}
//获取最低投资额度
function get_min()
{
    //可选的投资门槛【当前用户】
    $can_choice_jb_arr = can_choice_jb();
    //$can_choice_jb_arr中的第一条记录就是最低的投资门槛，最后一条就是最高的投资门槛
    $min = current($can_choice_jb_arr);
    return $min;

    /*
    $min = C("jj01s");
    //会员等级额度限制
    $jjaccountlevel = explode(',', C('jjaccountlevel'));
    $jibei_menkan = explode(',', C('jibei_menkan'));
    foreach ($jjaccountlevel as $key => $value) {
        if ($value == $usermm['levelname']) {
            $min = $min > $jibei_menkan[$key] ? $min : $jibei_menkan[$key];
            break;
        }
    }
    //排单金额不小于上一轮的百分比设置：
    $limit = get_tg_min_compare_last();
    $min = $min > $limit ? $min : $limit;
    return $min;
    */
}
//获取最高投资额度
function get_max()
{
    //可选的投资门槛【当前用户】
    $can_choice_jb_arr = can_choice_jb();
    //$can_choice_jb_arr中的第一条记录就是最低的投资门槛，最后一条就是最高的投资门槛
    $max = end($can_choice_jb_arr);
    return $max;
    /*
    $uab = getUserAddByCircle();
    return $uab == 0 ? C("jj01m") : C('jj01m') + $uab;
    */
}
//获取当前推荐人的烧伤金额基数
function get_shaoshang($accuser, $c_jb)
{
    $acc_last_jb = getUserLastTGBZJB($accuser);
    if ($acc_last_jb == 0) {
        return 0;
    }
    if ($acc_last_jb >= $c_jb) {
        return $c_jb;
    }
    return $acc_last_jb;
}

//预约挂单提交
function yuyue_tgbz($uname,$money)
{
	$user = M('user')->where(array(UE_account => $uname, UE_status => 0))->find();

	if(!$user)
		return;

//    if ($user['pdmnum'] < 1)
//	{
//		sendMail($user['ue_account'],'WNS邮件通知-预约挂单失败',C('pdm_name') . '余额不足!');
//		return false;
//    }

    //按照每排单多少扣除
//    if (C('paidanb_every') > 0 && C('paidanb_count') > 0)
//	{
//        $paidanb = ceil(ceil($money / C('paidanb_every')) * C('paidanb_count'));
//        if ($user['pdmnum'] < $paidanb)
//		{
//			sendMail($usermail,'WNS邮件通知-预约挂单失败',C('pdm_name') . '余额不足!');
//        } else
//		{
//		    /*
//            M('user')->where(array('UE_account' => $uname))->setDec('pdmnum', $paidanb);
//            $map['user'] = $uname;
//            $map['type'] = 'pd';
//            $map['info'] = '';
//            $map['yue'] = get_userinfo($uname, 'pdmnum');
//            $map['num'] = -$paidanb;
//            $map['date'] = date('Y-m-d H:i:s', time());
//            $paidan_log_newid = M('paidan_log')->add($map);
//		    */
//        }
//    }
    $data['zffs1'] = '1';
	$data['zffs2'] = '1';
	$data['zffs3'] = '1';
    $data['user'] = $user['ue_account'];
    $data['priority'] = $user['priority'];
    $data['user_nc'] = $user['ue_theme'];
    $data['user_tjr'] = $user['zcr'];
    $data['date'] = date('Y-m-d H:i:s', time());
    $data['zt'] = 0;
    $data['qr_zt'] = 0;
    $data['ppjb'] = 0;
    $data['isFast'] = 0;
	$data['isyuyue'] = 1;

	//是否使用预付款拆分功能
	$percent = C("prepaypercent");
	if($percent > 0)
	{
        $money_pre = $money * $percent / 100;
        $money_next = $money * (100 - $percent) / 100;

        //1.提交主订单
        $data['isprepay'] = 0;
        $data['jb'] = $money_next;
        $data['total'] = $money;
        $data['orderid'] = createorderid('P');
        $mainorderid = $data['orderid'];

        $newmainid = M('tgbz')->add($data);

        //1.提交预付款
        $data['isprepay'] = 1;
        $data['jb'] = $money_pre;
        $data['total'] = $money_pre;
        $data['orderid'] = createorderid('P');

        $newprepayid = M('tgbz')->add($data);
	}else
	{
        $data['jb'] = $money;
        $data['total'] = $money;
        $data['orderid'] = createorderid('P');
        $mainorderid = $data['orderid'];
        $newmainid = M('tgbz')->add($data);
	}
    if ($newmainid)
	{
        M('tgbz')->where(array('id' => $newmainid))->save(array('mainid' => $newmainid));
		if($newprepayid)
			M('tgbz')->where(array('id' => $newprepayid))->save(array('mainid' => $newmainid));
        /*
        if ($paidan_log_newid) {
            M('paidan_log')->where(array('id' => $paidan_log_newid))->save(array('info' => '排单消耗:' . $data['orderid']));
        }
        */
		//sendMail($usermail,'WNS邮件通知-预约挂单成功','预约挂单成功!');
		return true;
    } else {
		//sendMail($usermail,'WNS邮件通知-预约挂单失败','预约挂单失败');
		return false;
    }
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/25 0025
 * Time: 上午 11:49
 */
/**
 * 邮件发送函数
 */
function sendMail($to, $title, $content) {
    Vendor('PHPMailer.PHPMailerAutoload');
    $mail = new PHPMailer(); 
    $mail->IsSMTP(); 
    $mail->Host='smtp.exmail.qq.com'; //smtp服务器的名称
    $mail->SMTPAuth = TRUE; //启用smtp认证
    $mail->Username = 'smtp@qxhlsoft.com'; //你的邮箱名
    $mail->Password = 'Aa110110' ; //邮箱授权码（注意：这不是邮箱登录密码！）
    $mail->From = 'smtp@qxhlsoft.com'; //发件人地址（也就是你的邮箱地址）
    $mail->FromName = 'WNS'; //发件人姓名
    $mail->AddAddress($to,"尊敬的客户");
    $mail->WordWrap = 50; //设置每行字符长度
    $mail->IsHTML(true); // 是否HTML格式邮件
    $mail->CharSet='utf-8'; //设置邮件编码
    $mail->Subject =$title; //邮件主题
    $mail->Body = $content; //邮件内容
    $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
    return "info:" .($mail->Send());
}

//未转出订单数
function get_wzc_count()
{
	$map['zt'] = 0;
    $map['user'] = $_SESSION['uname'];
	$map['isprepay'] = 0;
    return M('user_jj')->where($map)->count();
}

//冻结期间订单数
function get_dj_count()
{
	$map['zt'] = 0;
    $map['user'] = $_SESSION['uname'];
    $list = M('user_jj')->where($map)->select();
	$count=0;
	foreach($list as $key => $value)
	{
		if(get_is_in_djq_bool($value))
			$count++;
	}

	return $count;
}

//20190521 start----------------
//判断冻结期间的订单数【新方法】只判断是否有成功付完预付款的订单
function new_get_dj_count()
{
    //判断是否存在上一个订单只是付完预付款的订单，并且时间在15天内有，预付款完成，且未付尾款的订单
    //$fiftyafter = time() + 15*24*3600;
    //$cur_date = date("Y-m-d H:i:s",time());
    //$fiftyago = time() - 15*24*3600;
    //$fiftyago_date = date("Y-m-d H:i:s",$fiftyago);
    $map = [];
    //$map['date'] = array('between',array($fiftyago_date,$cur_date));
    $map['isprepay'] = array('eq',1);//预付款支付完成的部分
    $map['zt'] = array('eq',1);//支付完成
    $map['qr_zt'] = array('eq',1);//确认支付完成
    $map['user'] = $_SESSION['uname'];
    $map['isreset'] = array('in',array(0,3));
    $tgbzModel = M('tgbz');
    $cur_tgbz_data = $tgbzModel->where($map)->group('mainid')->select();file_put_contents('testdata.txt',json_encode($cur_tgbz_data));
    $count = 0;
    foreach($cur_tgbz_data as $item)
    {
        //查询出付完预付款的主订单下的尾款的订单信息
        $cur_tgbz_data_by_mainid = $tgbzModel->where(array('mainid'=>$item['mainid'],'isprepay'=>0))->select();
        $tgbz_count = count($cur_tgbz_data_by_mainid);
        $cur_tgbz_count =0;
        foreach($cur_tgbz_data_by_mainid as $v){
            if( $v['zt']==1 && in_array($v['qr_zt'],[0,1]) ){
                $cur_tgbz_count++;
            }
        }
        if( $tgbz_count > 0 && $cur_tgbz_count == 0 ){
            $count++;
        }
    }
    return $count;
}
//20190521 end-------------

////订单是否在冻结期
function get_is_in_djq_bool($jj)
{
    if($jj['zt'] == 0)
	{
        $now_time = time();
        $jd_time = strtotime($jj['date_hk']) + C('jjdjdays') * 3600 * 24;//date_hk汇款时间，jjdjdays提现冻结天数。

        if($now_time > $jd_time)
		{  
           return false;
        }else{
		   return true;
        }
    }else
	{
		return false;
    }
}

//是预付款还是尾款
function get_prepayorfinal($p_id)
{
   return M('tgbz')->where(array('id' => $p_id))->find()['isprepay'] == 1 ? '预付款' : '尾款';
}


function fh($content,$append=false){
    if($append)
        file_put_contents('./test.txt', var_export($content,true),FILE_APPEND);
    else
        file_put_contents('./test.txt', var_export($content,true));
}

/**
 * 密码解密
 */
function authcode_decode($var)
{
  if(!checkStringIsBase64($var))
	  return $var;
  return authcode($var,'DECODE',C('base64_code_pwd'),0);
}
function checkStringIsBase64($var){
    $res1 = authcode($var,'DECODE',C('base64_code_pwd'),0);
	$res2 = authcode($res1,'ENCODE',C('base64_code_pwd'),0);
	$res3= authcode($res2,'DECODE',C('base64_code_pwd'),0);
	if($res1 == $res3 && $res1 != '' && $res3!= '')
		return true;
	return false;
}  
function authcode($string, $operation = "ENCODE", $key = "", $expiry = 0) {
	$ckey_length = 4;
	$key = md5($key ? $key : "default_key");
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = ($ckey_length ? ($operation == "DECODE" ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : "");
	$cryptkey = $keya . md5($keya . $keyc);
	$key_length = strlen($cryptkey);
	$string = ($operation == "DECODE" ? base64_decode(substr($string, $ckey_length)) : sprintf("%010d", $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string);
	$string_length = strlen($string);
	$result = "";
	$box = range(0, 255);
	$rndkey = array();

	for ($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for ($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for ($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ $box[($box[$a] + $box[$j]) % 256]);
	}

	if ($operation == "DECODE") {
		if (((substr($result, 0, 10) == 0) || (0 < (substr($result, 0, 10) - time()))) && (substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16))) {
			return substr($result, 26);
		}
		else {
			return "";
		}
	}
	else {
		return $keyc . str_replace("=", "", base64_encode($result));
	}
  }

/*--------------------------------
功能:     HTTP接口 发送短信
修改日期:   2011-03-04
说明:     http://api.sms.cn/mt/?uid=用户账号&pwd=MD5位32密码&mobile=号码&mobileids=号码编号&content=内容
状态:
    100 发送成功
    101 验证失败
    102 短信不足
    103 操作失败
    104 非法字符
    105 内容过多
    106 号码过多
    107 频率过快
    108 号码内容空
    109 账号冻结
    110 禁止频繁单条发送
    112 号码不正确
    120 系统升级
--------------------------------*/
//$type为1表示发送验证码，使用阿里短信发送，$type为0表示发送其他的短信信息，文字内容，使用美橙短信接口
function sendSMS($mobile,$content,$mobileids='',$http='http://api.sms.cn/sms/',$type = 0){
    $uid = C('sms_uid');
    $pwd = C('sms_pwd');
    //测试使用的号码 start--------------
    /*
    $examplePhone = ['13119710425','18198592045','13087802824','18697236967'];
    if(!in_array($mobile,$examplePhone)){
        $mobile = $examplePhone[floor(rand(0,4))];
    }*/

    if( $type == 1 ){
        //测试使用的号码 end--------------
        //阿里短信接口接入20190514 start--------------
        Vendor('aliyun_dysms_php_sdk.api_sdk.sms#class');
        $demos = new \sms();
        try{
            $response = $demos::sendSms($mobile,$content,$mobileids,'富怡');
        }catch(\Exception $e){
            $response = $e->getMessage();
        }
        file_put_contents('msgtt.txt',$response);
        return $response;
        //阿里短信接口接入20190514 end--------------
    }else{ //使用美橙短信接口
        try{
            $response = meichenSmsSet($mobile,$content);
        }catch (\Exception $e){
            $response = $e->getMessage();
        }
        return $response;
    }

    //return send($http,$uid,$pwd,$mobile,$content,$mobileids);
}

function insetSMSLog($user,$phone,$type,$content)
{
	$map['user'] = $user;
	$map['type']= $type;
    $map['content']= $content;
	$map['phone']= $phone;
	$map['date']= date('Y-m-d H:i:s', time());
	M('sms')->add($map);
}

function send($http,$uid,$pwd,$mobile,$content,$mobileids,$time='',$mid='')
{
    $data = array(
		'ac'=> 'send',
        'uid'=> $uid,                   //用户账号
        'pwd'=>$pwd,
        'mobile'=>$mobile,              //号码
        'content'=>$content,            //内容
        'mobileids'=>$mobileids,
        'time'=>$time,                  //定时发送
    );
    $re= postSMS($http,$data);          //POST方式提交
	file_put_contents('./sms.txt', var_export($mobile . ',' .$re,true),FILE_APPEND);
    return $re;
}

function postSMS($url,$data='')
{
    $port="";
    $post="";
    $row = parse_url($url);
    $host = $row['host'];
    $port = $row['port'] ? $row['port']:80;
    $file = $row['path'];
    while (list($k,$v) = each($data))
    {
        $post .= rawurlencode($k)."=".rawurlencode($v)."&"; //转URL标准码
    }
    $post = substr( $post , 0 , -1 );
    $len = strlen($post);
    $fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
    if (!$fp) {
        return "$errstr ($errno)\n";
    } else {
        $receive = '';
        $out = "POST $file HTTP/1.1\r\n";
        $out .= "Host: $host\r\n";
        $out .= "Content-type: application/x-www-form-urlencoded\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Content-Length: $len\r\n\r\n";
        $out .= $post;
        fwrite($fp, $out);
        while (!feof($fp)) {
            $receive .= fgets($fp, 128);
        }
        fclose($fp);
        $receive = explode("\r\n\r\n",$receive);
        unset($receive[0]);
        return implode("",$receive);
    }
}



function return_die_ajax($content,$ajax=true,$sf=0)
{
  if(!$ajax)
	  die("<script>alert('".$content."');history.back(-1);</script>");
  else
  {
	 header('Content-Type:application/json; charset=utf-8');
	 exit(json_encode(array('nr' => $content, 'sf' => $sf)));
  }
}

/** 
 *<span id="_xhe_cursor"></span> 通过IP获取对应城市信息(该功能基于淘宝第三方IP库接口) 
 * @param $ip IP地址,如果不填写，则为当前客户端IP 
 * @return  如果成功，则返回数组信息，否则返回false 
 */  
function getIpInfo($ip){  
    if(empty($ip)) $ip=get_client_ip(); 
    $url='http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;  
    $result = file_get_contents($url);  
    $result = json_decode($result,true);  
    if($result['code']!==0 || !is_array($result['data'])) return false;  
    return $result['data'];  
}

function checkSMSLimits($phone,$type)
{
    $today =  date('Y-m-d 00:00:00');
    $map['phone'] = $phone;
	$map['type'] = $type;
	$map['date'] = array('egt',$today);
	$sms = M('sms')->where($map)->order('date desc')->limit(3)->select();
	if(count($sms) >= 3)
		return true;
	else
		return false;
	return true;
}

function sendCheckCode($user ,$phone,$type)
{
     $rand = rand(100000, 900000);
     session('CHECK_CODE', $rand);
     session('PHONE_NUM', $phone);

	 $content =  "亲爱的会员，你的验证码是" . $rand . "【" . C('sms_sign') ."】";
     $info = sendSMS($phone,$content,'SMS_165380612','http://api.sms.cn/sms/',1);

	 insetSMSLog($user,$phone,$type,$rand);

     preg_match('/stat=([\d]{3})/', $info, $matches);
     if (is_array($matches) && $matches[1] == 100) 
	{
          session('check_status', 1);
     } else {
          session('check_status', 0);
     }
}
//阿里短信接口
function sendAliCheckCode($user ,$phone,$type)
{
    $rand = rand(100000, 900000);
    session('CHECK_CODE', $rand);
    session('PHONE_NUM', $phone);

    $content =  "亲爱的会员，你的验证码是" . $rand . "【" . C('sms_sign') ."】";
    $info = sendSMS($phone,$rand,'SMS_165380612','http://api.sms.cn/sms/',1);
file_put_contents('msg.txt',$info);
    insetSMSLog($user,$phone,$type,$rand);

    preg_match('/stat=([\d]{3})/', $info, $matches);
    if (is_array($matches) && $matches[1] == 100)
    {
        session('check_status', 1);
    } else {
        session('check_status', 0);
    }
}

function check_phone($code) {
   if (isset($_SESSION['adminuser'])) 
   {
       return true;
   }
   if ($code != session('CHECK_CODE'))
   {
      return false;
   }

   return true;
}

function check_vc_pay_status($from,$to,$type)
{
	$url='http://www.tokenview.com:8088/search/' . $from;  
    $result = file_get_contents($url);  
    $result = json_decode($result,true);  
    if($result['code']=='404')
		return '尚未获取到交易信息!'; 
	$txs = $result['data'][0]['txs'];
    $count_json = count($txs);

	$return_result = "";

    for ($i = 0; $i < $count_json; $i++)
    {
		if($type == "eth")
		{
			$item = $txs[$i];
			if(strtoupper($item['network']) == "ETH")
			{
				if(strtoupper($item['from']) == strtoupper($from))
				{
					if(strtoupper($item['to']) == strtoupper($to))
					{
						if($item['value'] >= '0.0005')
						{
							$return_result = $return_result . '[eth] 状态:成功,时间:' . date('Y-m-d h:i:s',$item['time']) . ',金额:' . $item['value'] . '</bt>';
						}
					}
				}
			}
		}
    }

	return $return_result == "" ? '尚未获取到交易信息' : $return_result;
}

//美橙短信20190516 start-------------
function meichenSmsSet($mobile,$content,$sign = '【富怡资本】'){
    //$uid=123;
    $encode='UTF-8';
    //用户名
    $username='gnyxdx';
    //32位MD5小写密码加密
    $password_md5='eca5571f3c07c3f886d79420837e5f93';
    //apikey秘钥（请登录 http://m.5c.com.cn 短信平台-->账号管理-->我的信息 中复制apikey）
    $apikey='3432d4f90cfb3f6d6b51e6db59e8d8b9';
    //手机号,只发一个号码：13800000001。发多个号码：13800000001,13800000002,...N 。使用半角逗号分隔。
    //$mobile=13087802824;
    //$mobile = 13119710425;
    //要发送的短信内容，特别注意：签名必须设置，网页验证码应用需要加添加【图形识别码】。
    //获取随机数字
    //$number=$this->random($uid);
    //$content='您好，您的验证码是：'.$number.'【富怡资本】';
    //$content='您的开仓订单已匹配成功，请根据匹配订单信息进行打款！【富怡资本】';
    //$content = $content.$sign;

    //  $content = iconv("GBK","UTF-8",$content);
    //执行URLencode编码 ，$content = urldecode($content);解码
    $contentUrlEncode = urlencode($content);
    $result = meichenSendSMS($username,$password_md5,$apikey,$mobile,$contentUrlEncode,$encode); //进行发送
    if(strpos($result,"success")>-1) {
        //提交成功

        //逻辑代码
    } else {
        //提交失败
        $result = meichenSendSMS($username,$password_md5,$apikey,$mobile,$contentUrlEncode,$encode);//失败在发送一次
        //逻辑代码
    }
    return $result; //输出result内容，查看返回值，成功为success，错误为error，（错误内容在上面有显示）
    //发送接口

}
function meichenSendSMS($username,$password_md5,$apikey,$mobile,$contentUrlEncode,$encode) {
//发送链接（用户名，密码，apikey，手机号，内容）
    $url = "http://m.5c.com.cn/api/send/index.php?"; //如连接超时，可能是您服务器不支持域名解析，请将下面连接中的：【m.5c.com.cn】修改为IP：【115.28.23.78】
    $data=array (
        'username'=>$username,
        'password_md5'=>$password_md5,
        'apikey'=>$apikey,
        'mobile'=>$mobile,
        'content'=>$contentUrlEncode,
        'encode'=>$encode,
    );
    $result = meichenCurlSMS($url,$data);
    return $result;
}

function meichenCurlSMS($url,$post_fields=array())
{

    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);//用PHP取回的URL地址（值将被作为字符串）
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//使用curl_setopt获取页面内容或提交数据，有时候希望返回的内容作为变量存储，而不是直接输出，这时候希望返回的内容作为变量
    curl_setopt($ch,CURLOPT_TIMEOUT,30);//30秒超时限制
    curl_setopt($ch,CURLOPT_HEADER,0);//将文件头输出直接可见。
    curl_setopt($ch,CURLOPT_POST,1);//设置这个选项为一个零非值，这个post是普通的application/x-www-from-urlencoded类型，多数被HTTP表调用。
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post_fields);//post操作的所有数据的字符串。 $data = curl_exec($ch);//抓取URL并把他传递给浏览器
    $data = curl_exec($ch);
    //var_dump($data);
    //exit;
    return $data;
    //$res = explode("\r\n\r\n",$data);//explode把他打散成为数组
    //return $res[2]; //然后在这里返回数组。
}


//获取随机数字
function meichenRandom($uid){
    $number=mt_rand(999,9999);
    //session('username'.$uid, $number);
    return $number;
}
//美橙短信20190516 end-------------


//excel表格导出
function exportExcel($expTitle,$expCellName,$expTableData){
    $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
    $fileName = $expTitle.date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
    $cellNum = count($expCellName);
    $dataNum = count($expTableData);

    vendor("PHPExcel_1_8.Classes.PHPExcel");

    $objPHPExcel = new \PHPExcel();
    $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

    $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
    for($i=0;$i<$cellNum;$i++){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
    }
    // Miscellaneous glyphs, UTF-8
    for($i=0;$i<$dataNum;$i++){
        for($j=0;$j<$cellNum;$j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
        }
    }
    ob_end_clean();
    header('pragma:public');
    header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
    header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

//20190515 start--------------------
//封号【冻结】账号，并扣除制定的通证积分【排单码】，
//如果排单码足够抵扣此次扣除的通证积分，就自动将用户状态解封【减排单码】
function fhkcpdm_or_jf( $user = '',$deduction_pdm = 0,$type = 'wgdfh',$info = '' )
{
    $cur_user_data = M('user')->where(array('UE_account' => $user))->find();
    if( $cur_user_data['pdmnum'] >= $deduction_pdm ) {
        //封号扣除10张通证【排单码】
        //M('user')->where(array(UE_account => $_SESSION['uname']))->setDec('pdmnum', 10);
        M('user')->where(array('UE_account' => $user))->setDec('pdmnum', $deduction_pdm);
        $map['user'] = $user;
        $map['type'] = $type;//未完成付款封号wwcfkfh
        $map['info'] = $info;
        $map['num'] = 0 - $deduction_pdm;
        $map['yue'] = get_userinfo($user, 'pdmnum');
        $map['date'] = date('Y-m-d H:i:s', time());
        M('paidan_log')->add($map);
//    if( $map['yue'] >= 0 ){
//        M('user')->where(array('UE_account' => $user))->save(array('UE_status'=>0));
//    }else{
        M('user')->where(array('UE_account' => $user))->save(array('UE_status' => 1));
//    }
    }else{
        $deduction_pdm = !empty($cur_user_data['pdmnum']) ? $cur_user_data['pdmnum'] : 0;
        M('user')->where(array('UE_account' => $user))->setDec('pdmnum', $deduction_pdm);
        $map['user'] = $user;
        $map['type'] = $type;//未完成付款封号wwcfkfh
        $map['info'] = $info;
        $map['num'] = 0 - $deduction_pdm;
        $map['yue'] = get_userinfo($user, 'pdmnum');
        $map['date'] = date('Y-m-d H:i:s', time());
        M('paidan_log')->add($map);
        M('user')->where(array('UE_account' => $user))->save(array('UE_status' => 1));
    }
}

//指定时间段内【后台指定】，如8小时内或者指定时间点未付款的订单【统一执行修改ts_zt状态为1的方法，自动投诉】【自动投诉后订单在交易大厅展示】
function zdsjwfk_ppdd_to_ts_zt1()
{
    //指定时间段内【后台指定】，如8小时内
    $base_time_date = 0;
    $jjdktime_set = C('jjdktime_set');
    if( $jjdktime_set ){
        $jjdktime = C('jjdktime');
        $base_time_date = date('Y-m-d H:i:s',(time() - 3600 * $jjdktime));
    }

    //指定时间点【后台指定】
    $jjdktime_at_set = C('jjdktime_at_set');
    if( $jjdktime_at_set ){
        $jjdktime_at = C('jjdktime_at');
        $base_time_date = date("Y-m-d",time()).' '.$jjdktime_at;
    }

    if( date("Y-m-d H:i:s",time()) < $base_time_date ){ //超过$base_time_date的时间再去执行以下的代码
        return true; //
    }

    if( !$base_time_date ){
        //$this->error('当前没有设置超时未付款的时间');
        return true;
    }

    //判断ppdd的时间是否超时【超时就讲ts_zt修改为1】
    $where['date'] = array('elt',$base_time_date);
    $where['zt'] = array('in',array(0));//未付款的订单
    $where['is_qgdt'] = array('eq',0);//未进入交易大厅的订单，当前订单是否进入抢购大厅 0否 1是【用户在指定时间内没有完成付款，而迁移到抢购大厅的】 2,表示已处理
    $wdk_ppdd_list = M('ppdd')->where($where)->select();

    //将ppdd订单对应的所有的tgbz表中的记录更新isreset字段【更新为1】
    //1.通过ppdd表中的p_id【对应tgbz表中的id】，然后查询出tgbz表中的主订单id即，mainid
    $ppdd_p_id_list = [];//所有匹配未付款的tgbz表的id
    $ppdd_g_id_list = [];//所有匹配未付款的jsbz表的id
    $ppdd_id_list = [];//所有匹配未付款的ppdd表的id
    foreach( $wdk_ppdd_list as $value ){
        $ppdd_p_id_list[] = $value['p_id'];
        $ppdd_g_id_list[] = $value['g_id'];
        $ppdd_id_list[] = $value['id'];
    }
    if( !empty( $ppdd_p_id_list ) ){
        $tgbzModel = M('tgbz');
        $cur_map['id'] = array('in',$ppdd_p_id_list);
        $cur_map['isreset'] = array('eq',0);
        $tgbz_mainid_data = $tgbzModel->field('mainid,user')->where($cur_map)->group('mainid')->select();//得到所有的主订单mainid的数据
        $tgbz_mainid_list = [];//所有主订单【mainid】的列表
        $tgbz_user_list = [];//所涉及的所有用户
        foreach( $tgbz_mainid_data as $tgbz_mainid )
        {
            $tgbz_mainid_list[] = $tgbz_mainid['mainid'];
            $tgbz_user_list[] = $tgbz_mainid['user'];
        }
        //到点未完成支付的订单，更新tgbz表中的isreset字段状态为1
        if( !empty( $tgbz_mainid_list ) ){
            //将$tgbz_mainid_list中所有主订单id【mainid】对应的tgbz订单的isreset更新为1【表示未打款失效】
            $cur_map_two['mainid'] = array('in',$tgbz_mainid_list);
            $cur_update = array(
                'isreset'=>1
            );
            M('tgbz')->where($cur_map_two)->setField($cur_update);//将tgbz订单的isreset更新为1.
        }

        //更新ppdd表的状态
        if(  !empty( $tgbz_mainid_list )  ){

            //1.将ppdd表匹配未付款的jsbz表记录的状态还原
            //$wdk_ppdd_list所有超时未付款的订单ppdd记录中【g_id是jsbz表汇总id】
            $jsbzWhere = [];
            $jsbzWhere['id'] = array('in',$ppdd_g_id_list);
            M('jsbz')->where($jsbzWhere)->save(array('zt' => 0, 'qr_zt' => 0));

            //2.将未付款的ppdd的记录删除
            //$ppdd_id_list所有匹配未付款的ppdd表的id
            $ppddWhere = [];
            $ppddWhere['id'] = array('in',$ppdd_id_list);
            M('ppdd')->where($ppddWhere)->delete();

            /*
            //查询出所有tgbz表中mainid对应的所有的id的列表
            $cur_map = [];
            $cur_map['mainid'] = array('in',$tgbz_mainid_list);
            $tgbz_id_data = M('tgbz')->field('id')->where($cur_map)->select();
            $tgbz_id_list = [];//主订单id【mainid】对应的所有的tgbz订单的id
            foreach( $tgbz_id_data as $v )
            {
                $tgbz_id_list[] = $v['id'];
            }
            //将到点未完成支付的匹配订单信息【ppdd表】,更新is_qgdt状态为1，更新ts_zt状态为1
            if( !empty( $tgbz_id_list ) )
            {
                $cur_map = [];
                $cur_map['p_id'] = array('in',$tgbz_id_list);
                $cur_update = [];
                $cur_update = array(
                    'is_qgdt' => 1,
                    'ts_zt' => 1,
                );
                M('ppdd')->where($cur_map)->setField($cur_update);
            }
            */
        }

        //执行封号【冻结】账号并扣除指定的通证积分【排单码】，如果排单码足够抵扣此次扣除的通证积分，就自动将用户状态解封【减排单码】[wwcfkfh未完成付款封号]
        if( isset( $tgbz_mainid_data ) && !empty($tgbz_mainid_data) ){
            foreach( $tgbz_mainid_data as $tgbz_mainid ){
                //执行封号【冻结】账号并扣除指定的通证积分【排单码】，如果排单码足够抵扣此次扣除的通证积分，就自动将用户状态解封【减排单码】[wwcfkfh未完成付款封号]
                fhkcpdm_or_jf($tgbz_mainid['user'],10,'wwcfkfh',$base_time_date.'前未付款单封号，扣除10个通证【排单码】,主订单id为'.$tgbz_mainid['mainid']);
            }
        }


    }

}
//20190515 end--------------------

//获得当前买入订单的开仓的金额
function get_kc_jb( $mainid )
{
    $cur_data = M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>1))->sum('jb');
    return $cur_data;
}

//获得当前买入订单的平仓的金额
function get_pc_jb( $mainid )
{
    //注意买入订单的总持仓量通过主id计算得来【tgbz.mainid】一样,然后求和
    $cur_data = M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>1))->sum('jb');
    $cur_data = !empty($cur_data) ? $cur_data : 0;
    $total = M('tgbz')->where('mainid=' . $mainid . ' and id = mainid')->find();//参考get_tgbz_totaljb()方法，计算tgbz中持仓总量的方法【买入】
    $res_total = !empty($total) ? $total['total'] : 0;
    return $res_total - $cur_data;//总的持仓总量 - 开仓的量 = 平仓的量
    //$cur_data = M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>0))->find();
    //return $cur_data['jb'];
}

//当前买入订单已经完成的确认完成的金额
function get_yqr_tgbz_total_jb($mainid)
{
    //已确认的开仓金额【买入】
    $kc_yqr_total_jb = get_p_status_yfkjb_new($mainid,1);
    //已确认的平仓金额【买入】
    $pc_yqr_total_jb = get_p_status_yfkjb_new($mainid,0);
    return $kc_yqr_total_jb + $pc_yqr_total_jb;
}


//获取当前支付完成的买入订单【开仓，已支付完成】
function get_kc_yzf( $mainid )
{
    $cur_data = M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>1,'zt'=>1,'qr_zt'=>1))->find();
    if( !empty($cur_data) ){
        return $cur_data['jb'];
    }
    return 0;
}

//获取当前支付完成的买入订单【平仓，已支付完成】
function get_pc_yzf( $mainid )
{
    $cur_data = M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>0,'zt'=>1,'qr_zt'=>1))->find();
    if( !empty($cur_data) ){
        return $cur_data['jb'];
    }
    return 0;
}


/**
 * 判断买入订单的状态【开仓，预付款】
 * @param $orderid
 * @param int $isprepay 值为0表示尾款【平仓的金额】，若为1表示预付款【开仓的金额】
 * @return int
 */
function get_p_status_yfkjb($orderid,$isprepay = 0)
{
    $tgbz = M('tgbz')->where("orderid='" . $orderid . "'")->find();
    if ($tgbz['zt'] == 0 && $tgbz['ppjb'] == 0) {
        return 0; //排队中
    }
    if ($tgbz['total'] == $tgbz['ppjb']) {
        //return 2; //已完成
        //return $tgbz['total'];
        $tgbz_yfk_jb = M('tgbz')->where("orderid='" . $orderid . "' and isprepay = '" . $isprepay . "'")->sum('jb');
        return !empty($tgbz_yfk_jb) ? $tgbz_yfk_jb : 0;
    }
    $tgbz_ppzjb = M('ppdd')->alias('ppdd')->join("left JOIN ot_tgbz tgbz on ppdd.p_id = tgbz.id where tgbz.user='" . $_SESSION['uname'] . "' and ppdd.zt = 0 and mainid =" . $tgbz['mainid'])->sum('tgbz.jb');
    if ($tgbz_ppzjb > 0 && $tgbz_ppzjb < $tgbz['total']) {
        //return 3; //部分完成  //ppdd的zt为0表示等待打款【买入方，ppdd中p_id表示买入方的tgbz.id】
        $tgbz_yfk_jb = M('ppdd')->alias('ppdd')->join("left JOIN ot_tgbz tgbz on ppdd.p_id = tgbz.id where tgbz.user='" . $_SESSION['uname'] . "' and ppdd.zt = 2 and mainid =" . $tgbz['mainid'] . " and tgbz.isprepay = '" . $isprepay . "'")->sum('tgbz.jb');
        return !empty($tgbz_yfk_jb) ? $tgbz_yfk_jb : 0;
    }
    if ($tgbz_ppzjb == $tgbz['total']) {
        //return 5; //已全部匹配
        return 0;
    }
    $tgbz_ppcgjb = M('ppdd')->alias('ppdd')->join("left JOIN ot_tgbz tgbz on ppdd.p_id = tgbz.id where tgbz.user='" . $_SESSION['uname'] . "' and ppdd.zt = 2 and mainid =" . $tgbz['mainid'])->sum('tgbz.jb');
    if ($tgbz_ppcgjb > 0 && $tgbz_ppcgjb < $tgbz['total']) {
        //return 4; //部分完成
        $tgbz_yfk_jb = M('ppdd')->alias('ppdd')->join("left JOIN ot_tgbz tgbz on ppdd.p_id = tgbz.id where tgbz.user='" . $_SESSION['uname'] . "' and ppdd.zt = 2 and mainid =" . $tgbz['mainid'] . " and tgbz.isprepay = '" . $isprepay . "'")->sum('tgbz.jb');
        //dump($tgbz_yfk_jb);
        return !empty($tgbz_yfk_jb) ? $tgbz_yfk_jb : 0;
    }


    if ($tgbz['ppjb'] > 0 && $tgbz['ppjb'] < $tgbz['total']) {
        //return 3; //部分完成
        if( $tgbz['isprepay'] == $isprepay ){ //$isprepay 值为0表示尾款【平仓的金额】，若为1表示预付款【开仓的金额】
            return $tgbz['ppjb'];
        }
        return 0;
    }
}

/**
 *
 * 判断买入订单的状态【开仓，预付款（尾款的已支付完成）的金额】
 * @param $mainid
 * @param int $isprepay  $isprepay为1表示预付款 ，为2表示尾款
 * @return int
 */
function get_p_status_yfkjb_new($mainid ,$isprepay = 1 ){

    $tgbz = M('tgbz')->where("mainid='" . $mainid . "' and isprepay = $isprepay")->select();
    //预付款总金额
    $yfk_zje = 0;
    //预付款已付款完成的总额
    $ywc_yfk_zje = 0;
    foreach($tgbz as $item){
        $yfk_zje += $item['total'];
        if($item['zt'] == 1 && $item['qr_zt'] == 1){
            $ppdd_data = M('ppdd')->where(array('p_id'=>$item['id']))->find();
            if( $ppdd_data['zt'] == 2 ){
                $ywc_yfk_zje += $item['jb'];
            }
        }
    }

    return $ywc_yfk_zje;
}
//判断买出订单的状态
function get_g_status_jb($orderid)
{
    $jsbz = M('jsbz')->where("orderid='" . $orderid . "'")->find();
    if ($jsbz['zt'] == 0) {
        return 0; //排队中
    }
    if ($jsbz['total'] == $jsbz['ppjb']) {
        return 2; //已完成
    }
    $jsbz_ppzjb = M('ppdd')->alias('ppdd')->join("left JOIN ot_jsbz jsbz on ppdd.g_id = jsbz.id where jsbz.user='" . $_SESSION['uname'] . "' and ppdd.zt = 0 and jsbz.mainid =" . $jsbz['mainid'])->sum('jsbz.jb');
    if ($jsbz_ppzjb > 0 && $jsbz_ppzjb < $jsbz['total']) {
        return 3; //部分匹配  //ppdd的zt为0表示等待打款【买出方，ppdd中g_id表示买出方的jsbz.id】
    }
    if ($jsbz_ppzjb == $jsbz['total']) {
        return 5; //已全部匹配
    }
    $jsbz_ppcgjb = M('ppdd')->alias('ppdd')->join("left JOIN ot_jsbz jsbz on ppdd.g_id = jsbz.id where jsbz.user='" . $_SESSION['uname'] . "' and ppdd.zt <> 0 and jsbz.mainid =" . $jsbz['mainid'])->sum('jsbz.jb');
    if ($jsbz_ppcgjb > 0 && $jsbz_ppcgjb < $jsbz['total']) {
        return 4; //部分完成
    }
    if ($jsbz['ppjb'] > 0 && $jsbz['ppjb'] < $jsbz['total']) {
        return 3; //部分匹配
    }
}
//买入订单匹配
function get_pp_p_status_jb($pporderid)
{
    $ppdd = M('ppdd')->where("pporderid='" . $pporderid . "'")->find();
    if ($ppdd['zt'] == 0) {
        return 0; //等待打款
    }
    if ($ppdd['zt'] == 1) {
        return 1; //等待对方确认
    }
    if ($ppdd['zt'] == 2) {
        return 2; //已完成
    }
}
////买出订单匹配
function get_pp_g_status_jb($pporderid)
{
    $ppdd = M('ppdd')->where("pporderid='" . $pporderid . "'")->find();
    if ($ppdd['zt'] == 0) {
        return 0; //等待对方打款
    }
    if ($ppdd['zt'] == 1) {
        return 1; //等待您的确认
    }
    if ($ppdd['zt'] == 2) {
        return 2; //已完成
    }
}
//获得当前买入订单的利息[配对打款后的利息]
function get_tgbz_lx( $tgbz_id = 0 )
{
    //先找出预付款的tgbz记录
    $tgbz_data = M('tgbz')->where(array('id'=>$tgbz_id))->find();
    $yfk_tgbz_data = M('tgbz')->where(array('mainid'=>$tgbz_data['mainid'],'isprepay'=>1))->order('id asc')->find();
    //判断当前的主订单对应下的所有预付款订单是否都已完成支付
    $yfk_all_tgbz_data = M('tgbz')->where(array('mainid'=>$tgbz_data['mainid'],'isprepay'=>1))->select();
    $yfk_all_pay_finish_num = 0;//已经支付完成的预付款的记录【本次主订单下的记录】
    foreach($yfk_all_tgbz_data as $item)
    {
        if( $item['zt'] == 1 && $item['qr_zt'] == 1 )
        {
            $yfk_all_pay_finish_num++;
        }
    }
    if( count($yfk_all_tgbz_data) ==0 || $yfk_all_pay_finish_num < count($yfk_all_tgbz_data) ){ //判断本次主订单下的所有预付款订单是否支付完成
        return 0;
    }

    $user_jj_data = M('user_jj')->where(array('p_id' => $yfk_tgbz_data['id']))->find();
    if(empty($user_jj_data)){ //没有支付记录
        return 0;
    }
    $ppdd = M("ppdd")->where(array("p_id" => $yfk_tgbz_data['id']))->find();
    //获得当前订单第一次打款的时间 ,得到 如2019-05-12 12:00:31
    $dakuan_time = $ppdd['date_hk'];//打款后第二天开始释放红利
    //打款后第二天开始的时间戳
    $start_time = strtotime(date("Y-m-d",strtotime($dakuan_time)).' 00:00:00') + 24*3600;

    //打款后分红天数
    $jjfhdays = C('jjfhdays');
    //到目前为止开始的天数
    if( time() > $start_time ){
        $day_num = ceil((time()-$start_time)/(24 * 3600));
    }else{
        $day_num = 0;
    }
    //配对打款后利息
    $lixi2 = C('lixi2')/100;

    //当前订单的总金额
    $cur_total = get_tgbz_totaljb($yfk_tgbz_data['mainid']);

    if( $jjfhdays > $day_num ){ //判断支付成功后第二天开始，到现在的天数是否超过设置的天数【如15天】，没超过的情况
        return $cur_total * $lixi2 * $day_num;
    }
    //判断支付成功后第二天开始，到现在的天数是否超过设置的天数【如15天】，超过的情况
    return $cur_total * $lixi2 * $jjfhdays;


    //$ppdd = M("ppdd")->where(array("p_id" => $yfk_tgbz_data['id']))->find();
    //$user_jj_data = M('user_jj')->where(array('r_id' => $ppdd['id']))->find();
    //return user_jj_paidui_lx($user_jj_data['id']);//使用user_jj的id获取买入订单的利息

}

//获得当前订单第一次打款的时间 ,得到 如2019-05-12 12:00:31
function get_first_dakuan_time( $tgbz_id = 0 )
{
    //先找出预付款的tgbz记录
    $tgbz_data = M('tgbz')->where(array('id'=>$tgbz_id))->find();
    $yfk_tgbz_data = M('tgbz')->where(array('mainid'=>$tgbz_data['mainid'],'isprepay'=>1))->find();
    $user_jj_data = M('user_jj')->where(array('p_id' => $yfk_tgbz_data['id']))->find();
    if(empty($user_jj_data)){ //没有支付记录
        return 0;
    }
    $ppdd = M("ppdd")->where(array("p_id" => $yfk_tgbz_data['id']))->find();
    $dakuan_time = $ppdd['date_hk'];//打款后第二天开始释放红利
    return $dakuan_time;
}

//获取交割订单的交割金额
function get_jsbz_jg_jb($mainid)
{

}

//判断买出订单的订单金额交割金额
function get_g_status_totaljb($orderid)
{
    $jsbz = M('jsbz')->where("orderid='" . $orderid . "'")->find();
    $map['mainid'] = $jsbz['mainid'];
    $all_jsbz_mainid_data = M('jsbz')->where($map)->select();
    //已近支付完成的
    $finished_num = 0;//已近支付完成次数
    $finished_jb = 0;//已经完成的交割积分
    foreach( $all_jsbz_mainid_data as $item )
    {
        if( $item['zt'] == 1 && $item['qr_zt'] == 1 )
        {
            $finished_num++;
            $finished_jb +=$item['jb'];
        }
    }

    return $finished_jb;

    /*
    $jsbz = M('jsbz')->where("orderid='" . $orderid . "'")->find();
    if ($jsbz['zt'] == 0) {
        return 0; //排队中
    }
    if ($jsbz['total'] == $jsbz['ppjb']) {
        //return 2; //已完成
        return $jsbz['total'];
    }
    $jsbz_ppzjb = M('ppdd')->alias('ppdd')->join("left JOIN ot_jsbz jsbz on ppdd.g_id = jsbz.id where jsbz.user='" . $_SESSION['uname'] . "' and ppdd.zt = 0 and jsbz.mainid =" . $jsbz['mainid'])->sum('jsbz.jb');
    if ($jsbz_ppzjb > 0 && $jsbz_ppzjb < $jsbz['total']) {
        //return 3; //部分匹配
        return 0;
    }
    if ($jsbz_ppzjb == $jsbz['total']) {
        //return 5; //已全部匹配
        return 0;
    }
    $jsbz_ppcgjb = M('ppdd')->alias('ppdd')->join("left JOIN ot_jsbz jsbz on ppdd.g_id = jsbz.id where jsbz.user='" . $_SESSION['uname'] . "' and ppdd.zt <> 0 and jsbz.mainid =" . $jsbz['mainid'])->sum('jsbz.jb');
    if ($jsbz_ppcgjb > 0 && $jsbz_ppcgjb < $jsbz['total']) {
        //return 4; //部分完成
        $jsbz_ppcgjb_totaljb = M('ppdd')->alias('ppdd')->join("left JOIN ot_jsbz jsbz on ppdd.g_id = jsbz.id where jsbz.user='" . $_SESSION['uname'] . "' and ppdd.zt <> 0 and jsbz.mainid =" . $jsbz['mainid'] ." and qr_zt = '1'")->sum('jsbz.jb');
        return $jsbz_ppcgjb_totaljb;
    }
    if ($jsbz['ppjb'] > 0 && $jsbz['ppjb'] < $jsbz['total']) {
        //return 3; //部分匹配
        return 0;
    }
    */
}

//平台的当前的开仓量【明日的开仓量】
function get_cur_opening_quantity()
{
    //判断是否是当天晚上的20：00之后
    if( time() > strtotime(date("Y-m-d").' 20:00:00') ){
        return add_cur_opening_quantity();
    }else{
        $data = M('transaction_volume_log')->order('time desc')->select();
        if( !empty($data) ){
            return $data[0]['total_transaction_volume'];
        }
        return 0;
    }
}

//添加平台明日的开仓量
function add_cur_opening_quantity()
{
    //用户的本息账户总金额，与动态收益的总金额
    $ue_money_total = M('user')->sum('UE_money');
    //用户的动态收益金
    $qwe_total = M('user')->sum('qwe');

    $map['time'] = array('gt',date("Y-m-d").' 20:00:00');
    $data = M('transaction_volume_log')->where($map)->find();
    if(empty($data)){
        M('transaction_volume_log')->add(array('total_ue_money'=>$ue_money_total,'total_qwe'=>$qwe_total,'total_transaction_volume'=>($ue_money_total+$qwe_total),'time'=>date("Y-m-d H:i:s")));
    }
    return $ue_money_total + $qwe_total;
}


/**
 * 获取用户直推用户数
 */
function getSubUserNum($UE_accName)
{
    $sub_num = M('user')->where(array('UE_accName' => $UE_accName))->count(); // 直推人数

    return $sub_num;
}

/**
 * 获取用户所有下级用户数
 */
function getAllSubUserNum($UE_accName, $field = 'UE_ID,UE_account,UE_accName')
{
    $count = 0; // 总人数

    $user_list = M('user')->where(array('UE_accName' => $UE_accName))->field($field)->select(); // 下级数据

    if ($user_list) {
        foreach ($user_list as $key => $value) {
            $_data = getAllSubUserNum($value['ue_account'], $field);
            if ($_data) {
                $count += $_data;
            }
            $count++;
        }
        return $count;
    }
}

/**
 * 通证奖励计算
 */
function tz_profit($UE_accName, $all_profit)
{
    $profit[1]['reward'] = explode('-', C('pass_reward_1'));
    $profit[1]['discount'] = C('pass_discount_1');
    $profit[2]['reward'] = explode('-', C('pass_reward_2'));
    $profit[2]['discount'] = C('pass_discount_2');
    $profit[3]['reward'] = explode('-', C('pass_reward_3'));
    $profit[3]['discount'] = C('pass_discount_3');
    $profit[4]['reward'] = explode('-', C('pass_reward_4'));
    $profit[4]['discount'] = C('pass_discount_4');
    $profit[5]['reward'] = explode('-', C('pass_reward_5'));
    $profit[5]['discount'] = C('pass_discount_5');
    $profit[6]['reward'] = explode('-', C('pass_reward_6'));
    $profit[6]['discount'] = C('pass_discount_6');
    $profit[7]['reward'] = explode('-', C('pass_reward_7'));
    $profit[7]['discount'] = C('pass_discount_7');
    $profit[8]['reward'] = explode('-', C('pass_reward_8'));
    $profit[8]['discount'] = C('pass_discount_8');
    $profit[9]['reward'] = explode('-', C('pass_reward_9'));
    $profit[9]['discount'] = C('pass_discount_9');
    $profit[10]['reward'] = C('pass_reward_10');
    $profit[10]['discount'] = C('pass_discount_10');

    $data = M('user')->where(array('UE_account' => $UE_accName))->field('UE_ID,UE_account,UE_accName')->find();
    if ($data) {
        $sub_num = getSubUserNum($UE_accName); // 直推人数
        if ($sub_num >= 3) { // 直推大于等于3人才计算
            $num = getAllSubUserNum($UE_accName); // 统计团队人数
            // 计算享受折扣
            foreach($profit as $_key => $_v)
            {
                if (!isset($all_profit[$_v['discount']])) { // 不存在折扣
                    if (is_array($_v['reward'])) {
                        if ($_v['reward'][0] <= $num && $_v['reward'][1] >= $num) {
                            $all_profit[$_v['discount']] = $UE_accName;
                        }
                    } else if ($_v['reward'] <= $num) {
                        $all_profit[$_v['discount']] = $UE_accName;
                    }
                }
            }
        }
    }

    // 判断是否有上级
    if ($data['ue_accname'] != 'base64@qq.com' && !empty($data['ue_accname'])) {
        $all_profit = tz_profit($data['ue_accname'], $all_profit);
    }

    return $all_profit;
}

//获取收益
function getTeamProfit($ue_id)
{
    $data = M('user')->where(array('UE_ID' => $ue_id))->field('UE_ID,UE_account,UE_accName')->find();

    // 判断是否有上级
    $all_profit = array();
    if ($data['ue_accname'] != 'base64@qq.com' && !empty($data['ue_accname'])) {
        $all_profit = tz_profit($data['ue_accname']);
    }

    return $all_profit;
    //dump($all_profit);die;
}


/**
 *
 * 在扣除通证积分【排单码】时即可计算通证级差奖励
 * @param int $ue_id 当前交易的用户的id
 * @param int $pdnum 当前变动的排单码
 */
function calculation_reward_pdnum( $ue_id = 0 ,$pdnum = 0 )
{
    //return true;//关闭该功能【20190603】
    //计算公式
    $reward_currency_rate = C('reward_currency_rate');//通证积分与人民币的比例
    //$profit = $pdnum * ( $reward_currency_rate - $reward_currency_rate * 0.95 );

    $user_list = getTeamProfit($ue_id);
    if(is_array($user_list))file_put_contents('profit.txt',json_encode($user_list));
    foreach( $user_list as $rate => $user){
        //获取当前排单码产生的奖励
        $cur_profit = $pdnum * ( $reward_currency_rate - $reward_currency_rate * ( $rate / 10 ) );

        if ($cur_profit > 0) {
            //给对应的用户的本息账户增加该奖励
            $map['UE_account'] = $user;
            //初始数据
            $user_data = M('user')->where($map)->find();
            //增加用户的本息账户金额
            M('user')->where($map)->setInc('UE_money', $cur_profit);
            //变动后的用户本息账户数据
            $user_data_after = M('user')->where($map)->find();
            //记录日志
            $note3 = "通证积分级差奖励折扣为".$rate.'折，得'.$cur_profit.'奖励';
            $record3["UG_account"] = $user; // 登入转出账户
            $record3["UG_type"] = 'jb';
            $record3["UG_allGet"] = $user_data['ue_money']; // 本息账户 ，变动前
            $record3["UG_money"] = '+' . $cur_profit; //得到的奖励【由通证积分变动产生】,变动金额
            $record3["UG_balance"] = $user_data_after['ue_money']; // 本息账户 ，变动后
            $record3["UG_dataType"] = 'tzjl'; // 通证奖励
            $record3["UG_note"] = $note3; // 推荐奖说明
            $record3["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作时间
            $reg4 = M('userget')->add($record3);
        }
    }
}

/**
 * 获取当前用户的可选金额【可选的投资门槛】
 */
function can_choice_jb()
{
    //当前所有用户的级别
    $jjaccountlevel_arr = explode(',',C('jjaccountlevel'));
    //当天所有级别的投资门槛
    $jibei_menkan_arr = explode(',',C('jibei_menkan'));

    //获取当前用户的级别
    $userData = M('user')->where(array('UE_account'=>$_SESSION['uname']))->find();

    //可选则的金额
    $choice_jb_arr = [];
    $jibei_menkan_key = 0;
    //1.用户级别所对应的投资门槛【买入门槛】
    foreach($jjaccountlevel_arr as $key => $jjaccountlevel)
    {
        if( $jjaccountlevel == $userData['levelname'] ){
            $jibei_menkan_key = $key;
            $choice_jb_arr[] = $jibei_menkan_arr[$key];
            break;
        }
    }

    $cur_level_finish_tgbz_status = cur_level_finish_tgbz_status();//当前用户级别对应下【是否】有完成支付的订单

    //下一级别的投资门槛
    if( $cur_level_finish_tgbz_status == 1 ) //确认当前用户级别对应下【有完成】支付的订单
    {
        if( isset( $jibei_menkan_arr[ $jibei_menkan_key + 1 ] ) ){  //如果存在下一级别就记录一下
            $choice_jb_arr[] = $jibei_menkan_arr[ $jibei_menkan_key + 1 ]; //记录下一级别门槛
        }

    }

    return $choice_jb_arr;
}


/**
 * 判断当前用户是否有完成当前级别下的订单
 * @return int 1表示有 0表示没有
 */
function cur_level_finish_tgbz_status()
{
    //当前所有用户的级别
    $jjaccountlevel_arr = explode(',',C('jjaccountlevel'));
    //当天所有级别的投资门槛
    $jibei_menkan_arr = explode(',',C('jibei_menkan'));

    //获取当前用户的级别
    $userData = M('user')->where(array('UE_account'=>$_SESSION['uname']))->find();

    //可选则的金额
    $choice_jb_arr = [];
    //$jibei_menkan_key = 0;
    //1.用户级别所对应的投资门槛【买入门槛】
    foreach($jjaccountlevel_arr as $key => $jjaccountlevel)
    {
        if( $jjaccountlevel == $userData['levelname'] ){
            //$jibei_menkan_key = $key;
            $choice_jb_arr[] = $jibei_menkan_arr[$key];
            break;
        }
    }

    //买入订单中是否存在当前会员级别对应的订单
    $map['user'] = $_SESSION['uname'];
    //当前级别下如有完成的订单即可获得下一级别的投资额的机会
    $tgbz_list = M('tgbz')->where($map)->group('mainid')->select();
    foreach( $tgbz_list as $tgbz ){
        $last_tgbz = M('tgbz')->where(array('mainid'=>$tgbz['mainid']))->select();
        //判断是否支付完成
        $counts = 0;//当前主订单下对应完成订单的数量
        $cur_tgbz_total_jb = 0;//当前订单的总额
        foreach( $last_tgbz as $item)
        {
            if( $item['zt'] == 1 && $item['qr_zt'] == 1 ){ //支付完成，确认完成的
                $counts++;
            }
            $cur_tgbz_total_jb += $item['jb'];//当前订单的总金额
        }
        //如果当前级别的投资金额与用户完成的订单中的任意订单匹配成功，即表示当前级别下的订单有完成记录，则用户有选择下一级别的机会
        if( $cur_tgbz_total_jb > 0 && $counts > 0 && count($last_tgbz) == $counts && $choice_jb_arr[0] == $cur_tgbz_total_jb ){ //判断当前支付完成的订单金额与当前级别的投资门槛是否匹配,当前级别的投资额$choice_jb_arr[0]
            return 1; //有完成的订单
        }
    }
    return 0; //没有完成的订单
}


/**
 * 当前用户user已经平仓的次数
 * @param $user 用户的账号
 * @return int 用户完成的订单次数
 */
function get_user_finish_num($user)
{
    $map['user'] = $user;
    //当前级别下如有完成的订单即可获得下一级别的投资额的机会
    $tgbz_list = M('tgbz')->where($map)->group('mainid')->select();

    $finished_num = 0; //订单完成的次数【平仓完成】
    foreach( $tgbz_list as $tgbz ){
        $last_tgbz = M('tgbz')->where(array('mainid'=>$tgbz['mainid']))->select();
        //判断是否支付完成
        $counts = 0;//当前主订单下对应完成订单的数量
        $cur_tgbz_total_jb = 0;//当前订单的总额
        foreach( $last_tgbz as $item)
        {
            if( $item['zt'] == 1 && $item['qr_zt'] == 1 ){ //支付完成，确认完成的
                $counts++;
            }
            $cur_tgbz_total_jb += $item['jb'];
        }
        //如果当前级别的投资金额与用户完成的订单中的任意订单匹配成功，即表示当前级别下的订单有完成记录，则用户有选择下一级别的机会
        if( $counts > 0 && count($last_tgbz) == $counts ){ //判断当前支付完成的订单金额与当前级别的投资门槛是否匹配,当前级别的投资额$choice_jb_arr[0]
            //return 1; //有完成的订单
            $finished_num++;
        }
    }
    return $finished_num; //完成订单的次数
}


/**
 * 平仓完成后将将本金和利息计入奖励账户中【20190523确认】
 * 注意确认收款的一方是收到钱的一方，订单完结后由收款方确认后，触发提现操作【提现操作，提现给创建订单的用户】
 */
function get_tgbz_tx_cl( $user_jj_id = 0 )
{
    if ( $user_jj_id > 0 )
    {
        $uname = $_SESSION['uname'];
        $starttime = date('Y-m-d 00:00:01', time());
        $endtime = date('Y-m-d 23:59:59', time());
        //$count1 = M("userget")->where("UG_getTime>='$starttime' and UG_getTime<='$endtime' and UG_account='$uname' and UG_dataType='tgbz'")->count();
        //if ($count1 == 50) {
            //die("<script>alert('提现失败，每天只允许提现五次！');history.back(-1);</script>");
        //} else {
//            $starttime = date('Y-m-1 00:00:01', time());
//            $endtime = date('Y-m-31 23:59:59', time());
//            $count2 = M("userget")->where("UG_getTime>='$starttime' and UG_getTime<='$endtime' and UG_account='$uname' and UG_dataType='tgbz'")->count();
//            if ($count2 >= 60) {
//                //die("<script>alert('提现失败，每月只允许提现60次！');history.back(-1);</script>");
//            } else {
                $varid = $user_jj_id;
                $proall = M('user_jj')->where(array('id' => $varid))->find();

                $ppdd_list = M('ppdd')->where(array('id'=>$proall['r_id']))->find();

                //买入的订单
                $tgbz = M('tgbz')->where(array('id'=>$ppdd_list['p_id']))->find();
        file_put_contents('userjjtest.txt',json_encode($proall).'||||'.json_encode($ppdd_list).'||||'.json_encode($ppdd_list)."|||");

                if(!$proall)return ['code'=>0,'msg'=>'订单不存在'];
                    //die("<script>alert('订单不存在!');history.back(-1);</script>");
                if($proall['zt'] == '1' && $tgbz['dj'] == 0)
                {
                    return ['code'=>0,'msg'=>'提现订单已完成,请勿多次提交!'];
                    //die("<script>alert('订单已完成,请勿多次提交!');history.back(-1);</script>");
                }
                if($proall['isprepay'] == 1)
                {
                    return ['code'=>0,'msg'=>'提交异常!'];
                    //die("<script>alert('提交异常!');history.back(-1);</script>");
                }
                if($ppdd_list['zt'] <> 2)
                {
                    return ['code'=>0,'msg'=>'订单未确认，请等待确认后再提款!'];
                    //die("<script>alert('订单未确认，请等待确认后再提款!');history.back(-1);</script>");
                }
                //冻结天数 -------------------
                if(C('jjdjdays')>0)
                {
                    $now_day = time();
                    $dakuan_day = strtotime($ppdd_list['date_hk']);
                    $jdtime = $dakuan_day + C('jjdjdays')*3600*24;
                    if($now_day < $jdtime){
                        return ['code'=>0,'msg'=>'打款完后要等"'.C('jjdjdays').'"天才可以提现哦！'];
                        //die("<script>alert('打款完后要等".C('jjdjdays')."天才可以提现哦！');history.back(-1);</script>");
                    }
                }

                /*
                if(!check_all_chaifen_tx_enabled($tgbz['mainid']) )
                    die("<script>alert('您有拆分的订单，请完全交易成功后再提现');history.back(-1);</script>");
                */

                if (isset($_SESSION['havepost']))return ['code'=>0,'msg'=>'正在处理,请勿多次提交！'];
                    //die("<script>alert('正在处理,请勿多次提交！');history.back(-1);</script>");
                else
                    $_SESSION['havepost'] = "y";

                $result = M('user_jj')->where(array('main_p_id' => $proall['main_p_id']))->save(array('zt' => '1'));

                if(!$result)
                    return ['code'=>0,'msg'=>'提现出错'];
                    //$this->error("提现出错",'',2);

                //注意确认收款的一方是收到钱的一方，订单完结后由收款方确认后，触发提现操作【提现操作，提现给创建订单的用户】
                $lx_he = user_jj_zong_lx($varid) + $proall['total']  + $tgbz['qd'];
        file_put_contents('10xxx.txt',$lx_he.'||||'.$result.'||||');
                $note3 = "买入本金加利息";
                $user_zq = M('user')->where(array('UE_account' => $ppdd_list['p_user']))->find();
                //M('user')->where(array('UE_ID' => $_SESSION['uid']))->setInc('UE_money', $lx_he);
                M('user')->where(array('UE_account' => $ppdd_list['p_user']))->setInc('qwe', $lx_he);//提现金额和利息计入到奖励账户中
                M('tgbz')->where(array('id' => $ppdd_list['p_id']))->save(array('dj'=>0,'had_zc'=>$lx_he,'jdstate'=>1));


                $user_xz = M('user')->where(array('UE_account' => $ppdd_list['p_user']))->find();

                $record3["UG_account"] = $ppdd_list['p_user'];
                $record3["UG_type"] = 'jb';
                $record3["UG_allGet"] = $user_zq['qwe'];
                $record3["UG_money"] = '+' . $lx_he; //
                $record3["UG_balance"] = $user_xz['qwe'];
                $record3["UG_dataType"] = 'tgbz';
                $record3["UG_note"] = $note3;
                $record3["UG_getTime"] = date('Y-m-d H:i:s', time());
                $record3["varid"] = $varid;
                $reg4 = M('userget')->add($record3);//计入到奖励账户中,记录表都是userget表

                unset($_SESSION['havepost']);
                return ['code'=>1,'msg'=>'提现成功'];

//            }
        //}
    }
}

/**
 * 推荐人已经获得当前会员的推荐奖励次数
 * @param $recommend 推荐人的账户名称
 * @param $user 买入【开仓】的账户
 * @return int 推荐人从买入者【开仓】会员出获得的推荐奖励
 */
function get_recommender_num( $recommend , $user )
{
    file_put_contents('recomend113.txt',$recommend.'---'.$user.'---|');
    //先获取买入人$user的tgbz订单列表
    $map['user'] = $user;
    $tgbz_list = M('tgbz')->field('id')->where($map)->select();
    $tgbz_id_arr = [];
    foreach( $tgbz_list as $tgbz )
    {
        $tgbz_id_arr[] = $tgbz['id'];
    }
    file_put_contents('recomend11.txt',json_encode($tgbz_id_arr));
    //userget表中的varid字段对应tgbz表中id
    if( !empty($tgbz_id_arr) ){
        $map1['UG_account'] = array('eq',$recommend);//获利的推荐人的账号
        $map1['UG_dataType'] = array('eq','jlj');
        $map1['varid'] = array('in',$tgbz_id_arr);
        $usergetData = M('userget')->where($map1)->group('varid')->select();
        return count($usergetData);
    }
    return 0;
}

/**
 *
 * 当前是否是开仓状态
 * @return int  0表示在正常开仓  1表示不在开仓时间内
 */
function get_pd_status()
{
    //开仓开始时间
    $paidan_time_start = C('paidan_time_start');
    $paidan_time_start_date = date("Y-m-d",time()).' '.$paidan_time_start.':00:00';
    $paidan_time_start_time = strtotime($paidan_time_start_date);
    //开仓截至时间
    $paidan_time_end = C('paidan_time_end');
    $paidan_time_end_date = date('Y-m-d',time()).' '.$paidan_time_end.':00:00';
    $paidan_time_end_time = strtotime($paidan_time_end_date);

    $nowtime = time();

    $switch = 0;//正常开仓
    if( $nowtime < $paidan_time_start_time || $nowtime > $paidan_time_end_time ){
        $switch = 1;//不在开仓时间内
    }
    return $switch;
}

/**
 * 强制转整
 */
function num_to_int($float=0.00)
{
    return (int)$float;
}

/**
 *
 * 判断当前是否已经达到今日最大的开仓量
 * @param int $money 当前用户开仓的金额
 * @return int 0表示今日开仓总量已经是最大值，不能再买入了。 1表示还可买入。
 */
function get_today_kczl_status( $money = 0 )
{

    $starttime = date('Y-m-d 00:00:01', time());
    $endtime = date('Y-m-d 23:59:59', time());


    $last_starttime = strtotime($starttime) - 24 * 3600;
    $last_endtime = strtotime($endtime) - 24 * 3600;
    $last_starttime_date = date('Y-m-d H:i:s',$last_starttime);
    $last_endtime_date = date('Y-m-d H:i:s',$last_endtime);


    //预期的交易总量
    $transaction_volume_log = M('transaction_volume_log')->where("time>='$last_starttime_date' and time<='$last_endtime_date' ")->find();
    $paidan_jbs = C('paidan_jbs');

    if( $transaction_volume_log['total_transaction_volume'] <= $paidan_jbs ) {

        if ($paidan_jbs > 0) {
            $sum = M("tgbz")->where("date>='$starttime' and date<='$endtime' ")->sum('jb');
            if (($sum + $money) > $paidan_jbs) {
                //$this->ajaxReturn(array('nr' => '今日排单额度已满，记得明日抢早排单哦!', 'sf' => 0));
                return 0;
            }
            return 1;
        }
        return 0;
    }else{
        if ($paidan_jbs > 0) {
            $sum = M("tgbz")->where("date>='$starttime' and date<='$endtime' ")->sum('jb');
            if (($sum + $money) > $transaction_volume_log['total_transaction_volume'] ) {
                //$this->ajaxReturn(array('nr' => '今日排单额度已满，记得明日抢早排单哦!', 'sf' => 0));
                return 0;
            }
            return 1;
        }
        return 0;
    }




}


/**
 *
 * 判断当前用户是否存在预付款【开仓】支付完成的订单【平仓订单未支付完成】的订单
 * @return int 0表示不存在未支付的订单【不存在支付完成的预付款的订单】 1表示【只存在支付完成的预付款的订单】
 */
function get_exists_yfkzfwc()
{
    $tgbzModel = M('tgbz');
    //除去抢购大厅的订单
    $cur_map = [];
    $cur_map['user'] = $_SESSION['uname'];
    $cur_map['isreset'] = array('in',array(0,3));
    //array('user'=>$_SESSION['uname'],'isreset'=>array('in',array(0,3)))
    $tgbzData = $tgbzModel->where($cur_map)->group('mainid')->select();

    foreach( $tgbzData as $item )
    {
        //开仓订单【预付款】
        $cur_yfk_tgbz_data = $tgbzModel->where(array('mainid'=>$item['mainid'],'isprepay'=>1))->select();
        $cur_yfk_tgbz_zt_num = 0;
        foreach( $cur_yfk_tgbz_data as $v ){
            if( $v['zt'] == 1 && $v['qr_zt'] == 1 ){
                $cur_yfk_tgbz_zt_num++;
            }
        }
        //平仓订单【尾款】
        $cur_wk_tgbz_data = $tgbzModel->where(array('mainid'=>$item['mainid'],'isprepay'=>0))->select();
        $cur_wk_tgbz_zt_num = 0;
        foreach( $cur_wk_tgbz_data as $v1 )
        {
            if( $v1['zt'] == 1 && $v1['qr_zt'] == 1 )
            {
                $cur_wk_tgbz_zt_num++;
            }
        }

        if( count($cur_yfk_tgbz_data) > 0 && count($cur_yfk_tgbz_data) == $cur_yfk_tgbz_zt_num && count($cur_wk_tgbz_data) > $cur_wk_tgbz_zt_num  ){
            return 1;
        }

    }

    return 0;

}


/**
 * 判断当前登录的会员是否是超级管理员
 */
function is_admin_user()
{
    if( $_SESSION['adminuser'] == 'admin' )
    {
        return 1;
    }
    return 0;
}

/**
 * 判断开仓是否匹配成功且未完成的订单【有匹配的订单订单时】
 * @return int 0表示有未完成的订单  1表示订单都已完成,没有匹配的未完成的订单
 */
function is_yfk_unfinished_status()
{
    $map['isprepay'] = 1;
    $map['isreset'] = array('in',array(0,3));
    $map['user'] = $_SESSION['uname'];
    $all_yfk_tgbz_id_list = M('tgbz')->field('id')->where($map)->select();

    //查询出所有的匹配记录，tgbz表对应的
    $all_yfk_tgbz_ids = [];
    foreach($all_yfk_tgbz_id_list as $value)
    {
        $all_yfk_tgbz_ids[] = $value['id'];
    }

    if(empty($all_yfk_tgbz_ids))
    {
        return 1;
    }
    //所有的匹配记录
    $map1['is_qgdt'] = 0;
    $map1['p_id'] = array('in',$all_yfk_tgbz_ids);
    $all_ppdd_list = M('ppdd')->where($map1)->select();
    if(empty($all_ppdd_list))
    {
        return 1;
    }

    $finished_num = 0;
    foreach( $all_ppdd_list as $item)
    {
        if( $item['zt'] == 2 )
        {
            $finished_num++;
        }
    }
    if( $finished_num > 0 && count($all_ppdd_list) == $finished_num )
    {
        return 1;
    }
    return 0;
}

/**
 * 判断平仓是否有匹配成功且未完成的订单
 * @return int 0表示有未完成的订单  1表示订单都已完成
 */
function is_wk_unfinished_status()
{
    $map['isprepay'] = 0;
    $map['isreset'] = array('in',array(0,3));
    $map['user'] = $_SESSION['uname'];
    $all_yfk_tgbz_id_list = M('tgbz')->field('id')->where($map)->select();

    //查询出所有的匹配记录，tgbz表对应的
    $all_yfk_tgbz_ids = [];
    foreach($all_yfk_tgbz_id_list as $value)
    {
        $all_yfk_tgbz_ids[] = $value['id'];
    }

    if(empty($all_yfk_tgbz_ids))
    {
        return 1;
    }
    //所有的匹配记录
    $map1['is_qgdt'] = 0;
    $map1['p_id'] = array('in',$all_yfk_tgbz_ids);
    $all_ppdd_list = M('ppdd')->where($map1)->select();
    if(empty($all_ppdd_list))
    {
        return 1;
    }

    $finished_num = 0;
    foreach( $all_ppdd_list as $item)
    {
        if( $item['zt'] == 2 )
        {
            $finished_num++;
        }
    }
    if( $finished_num > 0 && count($all_ppdd_list) == $finished_num )
    {
        return 1;
    }
    return 0;
}

/**
 * 判断交割订单是否有匹配成功且未完成的订单
 */
function is_jg_unfinished_status(){
    $map['isreset'] = 0;
    $map['user'] = $_SESSION['uname'];
    $all_yfk_tgbz_id_list = M('jsbz')->field('id')->where($map)->select();

    //查询出所有的匹配记录，tgbz表对应的
    $all_yfk_tgbz_ids = [];
    foreach($all_yfk_tgbz_id_list as $value)
    {
        $all_yfk_tgbz_ids[] = $value['id'];
    }

    if(empty($all_yfk_tgbz_ids))
    {
        return 1;
    }
    //所有的匹配记录
    $map1['is_qgdt'] = 0;
    $map1['g_id'] = array('in',$all_yfk_tgbz_ids);
    $all_ppdd_list = M('ppdd')->where($map1)->select();
    if(empty($all_ppdd_list))
    {
        return 1;
    }

    $finished_num = 0;
    foreach( $all_ppdd_list as $item)
    {
        if( $item['zt'] == 2 )
        {
            $finished_num++;
        }
    }
    if( $finished_num > 0 && count($all_ppdd_list) == $finished_num )
    {
        return 1;
    }
    return 0;
}

/**
 * 检查会员的最早注册开仓的一个订单是否已经完成打款
 * @param string $user  打款人的账号
 * @return int 为0表示未完成，为1表示已完成
 */
function checkUserFirstTgbzStatus( $user = '' )
{
    $tgbzModel = M('tgbz');
    $map = [];
    $map['user'] = $user;
    $firstMainid = $tgbzModel->field('mainid')->where($map)->order('date asc')->find();
    if(empty($firstMainid))
    {
        return 0;
    }
    //查询出该主订单下对应的用户的预付款订单
    $map2 = [];
    $map2['mainid'] = $firstMainid['mainid'];
    $map2['isprepay'] = 1;
    $first_yfk_data = $tgbzModel->where($map2)->select();
    $first_yfk_finished_num = 0;//第一次预付款完成的次数
    foreach( $first_yfk_data as $key => $v )
    {
        if( $v['zt'] == 1 && $v['qr_zt'] == 1 )
        {
            $first_yfk_finished_num++;
        }
    }

    if( $first_yfk_finished_num > 0 && count( $first_yfk_data ) == $first_yfk_finished_num )
    {
        return 1;//第一单的预付款打款已完成
    }

    return 0;

}

/**
 * 删除自动排单产生的异常订单【tgbz表记录】
 * 只在后台执行自动排单后的时候调用该方法
 */
function del_abnormal_tgbz()
{
    //查询出所有异常的tgbz订单
    $map = [];
    $map['mainid'] = 0;
    $map['zt'] = 0;
    $map['qr_zt'] = 0;
    $map['isyuyue'] = 1;
    $map['isreset'] = 0;
    $all_abnormal_tgbz = M('tgbz')->where($map)->select();
    $all_abnormal_tgbz_ids = [];//所有异常的tgbz订单的id
    foreach( $all_abnormal_tgbz as $item )
    {
        $all_abnormal_tgbz_ids[] = $item['id'];
    }

    if( empty($all_abnormal_tgbz_ids) ){
        return true;
    }

    //判断这些异常订单是否有匹配订单的记录【ppdd表中是否有记录】
    $map2 = [];
    $map2['p_id'] = array('in',$all_abnormal_tgbz_ids);
    $all_ppdd = M('ppdd')->where($map2)->select();
    foreach( $all_ppdd as $value )
    {
        //unset( $all_abnormal_tgbz_ids[$value['p_id']] );//除去有匹配记录的
        $key = array_search($value['p_id'],$all_abnormal_tgbz_ids);
        unset($all_abnormal_tgbz_ids[$key]);//除去有匹配记录的
    }

    //删除异常的tgbz订单
    $map3 = [];
    $map3['id'] = array('in',$all_abnormal_tgbz_ids);
    M('tgbz')->where($map3)->delete();
}

/**
 *
 * 当前预约排单的会员的预约记录【本次预约周期内还没有执行自动排单的记录的剩余次数】
 * @return int   若为-1表示用户没有预约或者没有该用户的信息，若为0或者大于0表示本次预约周期内还剩余的预约天数
 */
function cur_balance_yuyue_zhouqi()
{
    $map = [];
    $map['UE_account'] = $_SESSION['uname'];
    $userInfo = M('user')->where($map)->find();
    if( empty($userInfo) ){
        return -1;
    }
    if( $userInfo['isyuyue'] == 0 ){
        return -1;
    }

    //获得本次预约周期内已经执行预约的记录
    $finished_num = 0;
    $where = [];
    $where['user'] = $_SESSION['uname'];
    $where['yuyue_cur_time'] = $userInfo['yuyue_cur_time'];
    $finished_num = M('yuyue_log')->where($where)->count();
    $finished_num = !empty( $finished_num ) ? $finished_num : 0;

    //本次预约剩余的预约天数
    $balance_yuyue_day = $userInfo['yuyuezhouqi'] - $finished_num;

    return $balance_yuyue_day;

}

/**
 * 当前用户在本次的预约周期内已经预约成功的最后一条记录的时间
 */
function get_cur_yuyue_zhouqi_last_tgbz()
{
    $map = [];
    $map['UE_account'] = $_SESSION['uname'];
    $userInfo = M('user')->where($map)->find();
    if( empty($userInfo) ){
        return -1;
    }

    //预约的开始时间
    $yuyue_start = $userInfo['yuyue_cur_time'];

    $yuyue_start_time = strtotime($yuyue_start) + $userInfo['yuyuezhouqi']*3600*24;

    //预约的结束时间
    $yuyue_end = date("Y-m-d",$yuyue_start_time).' 23:59:59';
    $where = [];
    $where['user'] = $_SESSION['uname'];
    $where['date'] = array('between',array($yuyue_start,$yuyue_end));
    $where['isyuyue'] = 1;
    $where['_string'] = 'mainid = id';
    $last_tgbz = M('tgbz')->where($where)->order('date desc')->find();
    return $last_tgbz;
}


?>