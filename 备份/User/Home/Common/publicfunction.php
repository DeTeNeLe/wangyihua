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
function get_tgbz_totaljb($mainid)
{
    return M('tgbz')->where('mainid=' . $mainid)->sum('jb');
}
function get_jsbz_totaljb($mainid)
{
    return M('jsbz')->where('mainid=' . $mainid)->sum('jb');
}
function get_p_status($orderid)
{
    $tgbz = M('tgbz')->where("orderid='" . $orderid . "'")->find();
    if ($tgbz['zt'] == 0 && $tgbz['ppjb'] == 0) {
        return 0;
    }
    if ($tgbz['total'] == $tgbz['ppjb']) {
        return 2;
    }
    $tgbz_ppzjb = M('ppdd')->alias('ppdd')->join("left JOIN ot_tgbz tgbz on ppdd.p_id = tgbz.id where tgbz.user='" . $_SESSION['uname'] . "' and ppdd.zt = 0 and mainid =" . $tgbz['mainid'])->sum('tgbz.jb');
    if ($tgbz_ppzjb > 0 && $tgbz_ppzjb < $tgbz['total']) {
        return 3;
    }
    if ($tgbz_ppzjb == $tgbz['total']) {
        return 5;
    }
    $tgbz_ppcgjb = M('ppdd')->alias('ppdd')->join("left JOIN ot_tgbz tgbz on ppdd.p_id = tgbz.id where tgbz.user='" . $_SESSION['uname'] . "' and ppdd.zt = 2 and mainid =" . $tgbz['mainid'])->sum('tgbz.jb');
    if ($tgbz_ppcgjb > 0 && $tgbz_ppcgjb < $tgbz['total']) {
        return 4;
    }
    if ($tgbz['ppjb'] > 0 && $tgbz['ppjb'] < $tgbz['total']) {
        return 3;
    }
}
function get_g_status($orderid)
{
    $jsbz = M('jsbz')->where("orderid='" . $orderid . "'")->find();
    if ($jsbz['zt'] == 0) {
        return 0;
    }
    if ($jsbz['total'] == $jsbz['ppjb']) {
        return 2;
    }
    $jsbz_ppzjb = M('ppdd')->alias('ppdd')->join("left JOIN ot_jsbz jsbz on ppdd.g_id = jsbz.id where jsbz.user='" . $_SESSION['uname'] . "' and ppdd.zt = 0 and jsbz.mainid =" . $jsbz['mainid'])->sum('jsbz.jb');
    if ($jsbz_ppzjb > 0 && $jsbz_ppzjb < $jsbz['total']) {
        return 3;
    }
    if ($jsbz_ppzjb == $jsbz['total']) {
        return 5;
    }
    $jsbz_ppcgjb = M('ppdd')->alias('ppdd')->join("left JOIN ot_jsbz jsbz on ppdd.g_id = jsbz.id where jsbz.user='" . $_SESSION['uname'] . "' and ppdd.zt <> 0 and jsbz.mainid =" . $jsbz['mainid'])->sum('jsbz.jb');
    if ($jsbz_ppcgjb > 0 && $jsbz_ppcgjb < $jsbz['total']) {
        return 4;
    }
    if ($jsbz['ppjb'] > 0 && $jsbz['ppjb'] < $jsbz['total']) {
        return 3;
    }
}
function get_pp_p_status($pporderid)
{
    $ppdd = M('ppdd')->where("pporderid='" . $pporderid . "'")->find();
    if ($ppdd['zt'] == 0) {
        return 0;
    }
    if ($ppdd['zt'] == 1) {
        return 1;
    }
    if ($ppdd['zt'] == 2) {
        return 2;
    }
}
function get_pp_g_status($pporderid)
{
    $ppdd = M('ppdd')->where("pporderid='" . $pporderid . "'")->find();
    if ($ppdd['zt'] == 0) {
        return 0;
    }
    if ($ppdd['zt'] == 1) {
        return 1;
    }
    if ($ppdd['zt'] == 2) {
        return 2;
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
            return "提供帮助";
            break;
		case "jsbz":
            return "接受帮助";
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
////获取用户提供帮助的数量
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
}
//获取最高投资额度
function get_max()
{
    $uab = getUserAddByCircle();
    return $uab == 0 ? C("jj01m") : C('jj01m') + $uab;
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

    if ($user['pdmnum'] < 1) 
	{
		sendMail($user['ue_account'],'WNS邮件通知-预约挂单失败',C('pdm_name') . '余额不足!');
		return false;
    }

    //按照每排单多少扣除
    if (C('paidanb_every') > 0 && C('paidanb_count') > 0)
	{
        $paidanb = ceil(ceil($money / C('paidanb_every')) * C('paidanb_count'));
        if ($user['pdmnum'] < $paidanb)
		{
			sendMail($usermail,'WNS邮件通知-预约挂单失败',C('pdm_name') . '余额不足!');
        } else 
		{
            M('user')->where(array('UE_account' => $uname))->setDec('pdmnum', $paidanb);
            $map['user'] = $uname;
            $map['type'] = 'pd';
            $map['info'] = '';
            $map['yue'] = get_userinfo($uname, 'pdmnum');
            $map['num'] = -$paidanb;
            $map['date'] = date('Y-m-d H:i:s', time());
            $paidan_log_newid = M('paidan_log')->add($map);
        }
    }
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
        $money_pre = $data_P ['amount'] * $percent / 100;
        $money_next = $data_P ['amount'] * (100 - $percent) / 100;

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

        if ($paidan_log_newid) {
            M('paidan_log')->where(array('id' => $paidan_log_newid))->save(array('info' => '排单消耗:' . $data['orderid']));
        }
		sendMail($usermail,'WNS邮件通知-预约挂单成功','预约挂单成功!');
		return true;
    } else {
		sendMail($usermail,'WNS邮件通知-预约挂单失败','预约挂单失败');
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

////订单是否在冻结期
function get_is_in_djq_bool($jj)
{
    if($jj['zt'] == 0)
	{
        $now_time = time();
        $jd_time = strtotime($jj['date_hk']) + C('jjdjdays') * 3600 * 24;

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

function sendSMS($mobile,$content,$mobileids='',$http='http://api.sms.cn/sms/'){
    $uid = C('sms_uid');
    $pwd = C('sms_pwd');
	 
    return send($http,$uid,$pwd,$mobile,$content,$mobileids);
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
     $info = sendSMS($phone,$content);

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
?> <?php 