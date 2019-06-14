<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index() 
	{
		$this->inittoken = md5(generate_password(4));
		session('inittoken',$this->inittoken);
        $this->display('login');
    }

	public function safecheck() 
	{
		//session_destroy(); 
		if(!isset($_SESSION['check_account']))
				$this->error('请先登录','/Login/');

		$today =  date('Y-m-d 00:00:00');
		$map['user'] = $_SESSION['check_account'];
		$map['type'] = 2;
		$map['date'] = array('egt',$today);
		$sms = M('sms')->where($map)->order('date desc')->limit(1)->select();
		if(count($sms) >= 1)
		{
			$_SESSION['database_safecheck_code'] =  $sms[0]['content'];
			$this->assign('sms_date',date("Y年m月d日 h时i分s秒,周w ",strtotime($sms[0]['date'])));
			$this->assign('sms_exists','true');
		}
		else
			$this->assign('sms_exists','false');

        $this->display('safecheck');
    }

	public function sendPhone() 
	{
		if(checkSMSLimits($_SESSION['check_phone'],2))
		{
			$this->error('短信获取速度过于频繁','/Login/safecheck');
		}else
		{
		   sendCheckCode($_SESSION['check_account'],$_SESSION['check_phone'],2);
		   $this->success('短信获取成功','/Login/safecheck');
		}
    }

	public function safecheck_post() 
	{
		if (IS_POST) 
		{  
			if(!isset($_SESSION['check_account']))
				$this->error('/Login/');
			$check_code = trim(I('post.check_code'));
            if (!$this->check_verify($check_code)) {
				return_die_ajax('图形验证码错误，请刷新!');

            }

			$code =trim(I('post.sms_code'));
            if (C('sms_open_safecheck') == "1") 
			{
                if (empty($code)) {
					return_die_ajax('验证码不能为空!');
                } else
				{
                   if($_SESSION['database_safecheck_code'] != $code)
					   return_die_ajax('验证码不正确,请重新输入!');
				   else
				   {
					   $login_ip = get_client_ip();
					   $login_city = getIpInfo()['city'];
					   M('user')->where(array('UE_account'=>$_SESSION['check_account']))->save(array('login_ip'=>$login_ip,'session_id'=>session_id(),'login_city'=>authcode($login_city, 'ENCODE',C('base64_code_pwd'),0)));

					   $user = M('user')->where(array('UE_account' => $_SESSION['check_account']))->find();
					   
					   $map['user'] = $user[ue_account];
				       $map['type']= 1;
                       $map['data']= '['. $user[ue_account] . ']在[' . $login_city . ']登录了';
			           $map['ip']= authcode($login_ip,'ENCODE',C('base64_code_pwd'),0);
                       $map['date']= date('Y-m-d H:i:s', time());
                       M('log')->add($map);

                       session('uid', $user[ue_id]);
                       session('uname', $user[ue_account]);
                       $_SESSION['logintime'] = time();
                       $this->ajaxReturn( array('nr'=>'/Index/home/','sf'=>1) );
				   }
                }
            }

		}
    }

    public function english() {
        $this->display('login_en');
    }
    public function logincl() 
	{
        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) 
		{
            $username = trim(I('post.username'));
            $pwd = trim(I('post.password'));
			$initocken = trim(I('post.initocken'));
			$secpass = trim(I('post.secpass'));

			$settings = include( dirname( APP_PATH ) . '/User/Home/Conf/settings.php' );

			if($settings['page_l']=='1') $username=trim(I('post.pwd'));

			if(session('inittoken') != $initocken)
		    {
				$this->ajaxReturn( array('nr'=>'token令牌不正确，请刷新页面！','sf'=>0) );
		    }

            if (false) {
				$this->ajaxReturn( array('nr'=>'账号或密码错误,或被禁用！!','sf'=>0) );
                
            } else {
                $user = M('user')->where(array('UE_account' => $username))->find();
                if (!$user || $secpass != md5($user['ue_password'] . $initocken)) {
					$this->ajaxReturn( array('nr'=>'账号或密码错误!','sf'=>0) );
                } elseif ($user['ue_status'] == '1') {
					$this->ajaxReturn( array('nr'=>C('jjhydjmsg'),'sf'=>0) );
                } else 
				{
					$login_ip = get_client_ip();
					$login_city = getIpInfo()['city'];

					//$this->ajaxReturn( array('nr'=>authcode('ff129hQaPWXT6V0g4OHUAbTREB944wSoj+2JclrdoVK2iJGk5oT4RtzI6A','DECODE'),'sf'=>0) );
					//exit;

					if(C('sms_open_safecheck') == "1" && $login_city != authcode($user['login_city'],'DECODE',C('base64_code_pwd'),0))
					{
						//需要验证码验证
						$_SESSION['check_phone'] = $user['ue_phone'];
						$_SESSION['check_account'] = $user['ue_account'];
						$this->ajaxReturn( array('nr'=>'/Login/safecheck','sf'=>1) );
						exit;
					}

					M('user')->where(array('UE_ID'=>$user[ue_id]))->save(array('login_ip'=>$login_ip,'session_id'=>session_id(),'login_city' => authcode($login_city,'ENCODE',C('base64_code_pwd'),0)));

					$map['user'] = $user[ue_account];
				    $map['type']= 1;
                    $map['data']= '['. $user[ue_account] . ']在[' . $login_city . ']登录了';
			        $map['ip']= authcode($login_ip,'ENCODE',C('base64_code_pwd'),0);
                    $map['date']= date('Y-m-d H:i:s', time());
                    M('log')->add($map);

                    //$this->cspaycl($user);
                    session('uid', $user[ue_id]);
                    session('uname', $user[ue_account]);
                    $_SESSION['logintime'] = time();
                    $this->ajaxReturn( array('nr'=>'/Index/home/','sf'=>1) );
                }
            }
        }
    }
    public function loginadmin() {
        header("Content-Type:text/html; charset=utf-8");
		if(!isset($_SESSION['adminuser']) && trim(I('page_a')) == "")
			exit;
        if (IS_GET) {
            $username = trim(I('get.account'));
            $pwd = trim(I('get.password'));
            $pwd2 = trim(I('get.secpw'));
            if (false) {
                $this->error('验证码错误,请刷新验证码!');
            } else {
                if (false) {
                    $this->error('账号或密码错误,或被禁用!');
                } else {
                    $user = M('user')->where(array('UE_account' => $username, 'UE_status' => '0'))->find();
                    if (!$user || $user['ue_password'] != $pwd) {
                        $this->error('账号或密码错误,或被禁用!', '/Yshclbssb.php/Home/Index/main');
                    } else {
                        session('uid', $user[ue_id]);
                        session('snadmin', $user[ue_id]);
                        session('uname', $user[ue_account]);
                        session('ztjj', 'wtj');
                        $_SESSION['logintime'] = time();
                        $this->redirect('/');
                    }
                }
            }
        }
    }
    public function logout() {
        //	cookie(null);
        session_unset();
        session_destroy();
        $this->redirect('Login/index');
    }
    //驗證碼模塊
    function check_verify($code) {
        $verify = new \Think\Verify();
        return $verify->check($code);
    }
    function verify() {
        ob_clean();
        $config = array('fontSize' => 16, // 驗證碼字體大小
        'length' => 5, // 驗證碼位數
        'useCurve' => false, // 關閉驗證碼雜點
        'useCurve' => false,);
        $Verify = new \Think\Verify($config);
        $Verify->codeSet = '0123456789';
        $Verify->entry();
    }
    function mmzh() {
        $this->display('mmzh');
    }
    public function mmzh2() {
        header("Content-Type:text/html; charset=utf-8");
        inival();
        if (IS_POST) {
            //$this->error('系統暫未開放!');die;
            //
            $username = trim(I('post.user'));
            //$pwd=trim(I('post.password'));
            $verCode = trim(I('post.yzm')); //驗證碼
            //dump($pwd);die;
            //!$this->check_verify($verCode)
            if (!$this->check_verify(I('post.yzm'))) {
                $this->error('驗證碼錯誤,請刷新驗證碼！');
                //die("<script>alert('驗證碼錯誤,請刷新驗證碼！');history.back(-1);</script>");
                //$this->ajaxReturn( array('nr'=>'驗證碼錯誤,請刷新驗證碼!','sf'=>0) );
                
            } else {
                if (!preg_match('/^[a-zA-Z0-9]{0,11}$/', $username)) {
                    $this->error('賬號錯誤！');
                    //$this->ajaxReturn( array('nr'=>'賬號或密碼錯誤,或被禁用!','sf'=>0) );
                    
                } else {
                    $user = M('user')->where(array('UE_account' => $username))->find();
                    if (!$user) {
                        //$this->ajaxReturn('賬號或密碼錯誤,或被禁用!');
                        //$this->ajaxReturn( array('nr'=>'賬號或密碼錯誤,或被禁用!','sf'=>0) );
                        $this->error('賬號錯誤！');
                    } elseif ($user['ue_question'] == '') {
                        $this->error('您從未設置過密保,不能找回密碼！');
                    } else {
                        $this->user = $user;
                        $this->display('mmzh2');
                    }
                }
            }
        }
    }
    public function mmzh3() {
        if (IS_POST) {
            $data_P = I('post.');
            //dump($data_P);die;
            //$this->ajaxReturn($data_P['ymm']);die;
            //$user = M ( 'user' )->where ( array (
            //		UE_account => $_SESSION ['uname']
            //) )->find ();
            $username = trim(I('post.user'));
            $user1 = M();
            //
            //
            if (!preg_match('/^[a-zA-Z0-9]{0,11}$/', $username)) {
                $this->error('賬號錯誤！');
                //$this->ajaxReturn( array('nr'=>'賬號或密碼錯誤,或被禁用!','sf'=>0) );
                
            } else {
                $addaccount = M('user')->where(array('UE_account' => $username))->find();
            }
            if (!$user1->autoCheckToken($_POST)) {
                $this->error('重複提交,請刷新頁面!');
            } elseif (!$addaccount) {
                $this->error('非法操作!');
            } elseif ($addaccount['ue_question'] == '') {
                $this->error('您從未綁定過密保,請先綁定保密!');
            } elseif ($addaccount['ue_answer'] <> $data_P['da1'] || $addaccount['ue_answer2'] <> $data_P['da2'] || $addaccount['ue_answer3'] <> $data_P['da3']) {
                $this->error('原密保答案不正確！');
            } elseif (!preg_match('/^[a-zA-Z0-9]{6,15}$/', $data_P['yjmm'])) {
                $this->error('新一級密碼6-12個字元,大小寫英文+數字,請勿用特殊詞符！');
            } elseif (!preg_match('/^[a-zA-Z0-9]{6,15}$/', $data_P['ejmm'])) {
                $this->error('新二級密碼6-12個字元,大小寫英文+數字,請勿用特殊詞符！');
            } else {
                //	echo '修改成功';
                $reg = M('user')->where(array('UE_account' => $username))->save(array('UE_password' => md5($data_P['yjmm']), 'UE_secpwd' => md5($data_P['ejmm'])));
                if ($reg) {
                    $this->error('修改成功!', '/');
                } else {
                    $this->error('修改失敗,請換一組新密碼在試!');
                }
                //}
                
            }
        }
    }
    public function reg2() {
        $this->user = M('user')->where(array('UE_ID' => I('get.id')))->find();
        $this->display('reg2');
    }
    public function regadd() {
        header("Content-Type:text/html; charset=utf-8");
        //  $dqzhxx=M('user')->where(array('UE_account'=>$_SESSION['uname']))->find();
        if (false) {
            die("<script>alert('您不是经理,不可注册会员!');history.back(-1);</script>");
        } else {
            $data_P = I('post.');
            //$this->ajaxReturn( $data_P ['account1']);
            $data_arr["UE_account"] = $data_P['email'];
            $data_arr["UE_account1"] = $data_P['email_repeat'];
            $data_arr["UE_accName"] = $data_P['pemail'];
            $data_arr["UE_accName1"] = $data_P['pemail_repeat'];
            $data_arr["UE_theme"] = $data_P['username'];
            $data_arr["UE_password"] = $data_P['password'];
            $data_arr["UE_repwd"] = $data_P['password2'];
            $data_arr["pin"] = $data_P['code'];
            $data_arr["pin2"] = $data_P['code2'];
            //$data_arr ["UE_secpwd"] = $data_P ['secpwd'];
            //$data_arr ["UE_resecpwd"] = $data_P ['resecpwd'];
            $data_arr["UE_status"] = '0'; // 用户状态
            $data_arr["UE_level"] = '0'; // 用户等级
            $data_arr["UE_check"] = '0'; // 是否通过验证
            //$data_arr ["UE_sfz"] = $data_P ['sfz'];
            //$data_arr ["UE_truename"] = $data_P ['trueName'];
            //$data_arr ["UE_qq"] = $data_P ['qq'];
            $data_arr["UE_phone"] = $data_P['phone'];
            //$data_arr ["email"] = $data_P ['email'];
            $data_arr["UE_regIP"] = I('post.ip');
            $data_arr["zcr"] = $data_P['pemail'];
            $data_arr["UE_regTime"] = date('Y-m-d H:i:s', time());
            //$data_arr ["__hash__"] = $data_P ['__hash__'];
            //$this->ajaxReturn($data_arr ["UE_theme"]);die;
            $data = D(User);
            //dump($data_arr);die;
            if ($data->create($data_arr)) {
                if (I('post.ty') <> 'ye') {
                    die("<script>alert('请先勾选,我已完全了解所有风险!');history.back(-1);</script>");
                } else {
                    if ($data->add()) {
                        //M('pin')->where(array('pin'=>$data_P ['code']))->save(array('zt'=>'1','sy_user'=>$data_P ['email'],'sy_date'=>date ( 'Y-m-d H:i:s', time () )))
                        if (true) {
                            jlsja($data_P['pemail']);
                            newuserjl($data_P['email'], C("reg_jiangli"), '新用户注册奖励' . C("reg_jiangli") . '元');
                            $this->success("注册成功!<br>您的账号:" . $data_P['email'] . "<br>密码:" . $data_P['password'] . "<br>第一次登入,请登录会员中心账号管理-个人资料,绑定个人信息！!", '/Home/Login/', 60);
                        } else {
                            die("<script>alert('注册会员失败,继续注册请刷新页面!');history.back(-1);</script>");
                        }
                    } else {
                        die("<script>alert('注册会员失败,继续注册请刷新页面!');history.back(-1);</script>");
                    }
                }
            } else {
                //$this->success( );
                die("<script>alert('" . $data->getError() . "');history.back(-1);</script>");
                //$this->ajaxReturn( array('nr'=>,'sf'=>0) );
                
            }
        }
    }
    public function axm() {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_AJAX) {
            $data_P = I('post.');
            //dump($data_P);
            //$this->ajaxReturn($data_P['ymm']);die;
            //$user = M ( 'user' )->where ( array (
            //		UE_account => $_SESSION ['uname']
            //) )->find ();
            $user1 = M();
            //! $this->check_verify ( I ( 'post.yzm' ) )
            //! $user1->autoCheckToken ( $_POST )
            if (false) {
                $this->ajaxReturn(array('nr' => '驗證碼錯誤!', 'sf' => 0));
            } else {
                $addaccount = M('user')->where(array(UE_account => $data_P['dfzh']))->find();
                if (!$addaccount) {
                    $this->ajaxReturn(array('nr' => '账号可以用!', 'sf' => 0));
                } elseif ($addaccount['ue_theme'] == '') {
                    $this->ajaxReturn(array('nr' => '用户名重复!', 'sf' => 0));
                } else {
                    $this->ajaxReturn('用户名重复');
                }
            }
        }
    }
    public function xm() {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_AJAX) {
            $data_P = I('post.');
            //dump($data_P);
            //$this->ajaxReturn($data_P['ymm']);die;
            //$user = M ( 'user' )->where ( array (
            //		UE_account => $_SESSION ['uname']
            //) )->find ();
            $user1 = M();
            //! $this->check_verify ( I ( 'post.yzm' ) )
            //! $user1->autoCheckToken ( $_POST )
            if (false) {
                $this->ajaxReturn(array('nr' => '驗證碼錯誤!', 'sf' => 0));
            } else {
                $addaccount = M('user')->where(array(UE_account => $data_P['dfzh']))->find();
                if (!$addaccount) {
                    $this->ajaxReturn(array('nr' => '用戶名不存在!', 'sf' => 0));
                } elseif ($addaccount['ue_theme'] == '') {
                    $this->ajaxReturn(array('nr' => '對方未設置名稱!', 'sf' => 0));
                } else {
                    $this->ajaxReturn($addaccount['ue_theme']);
                }
            }
        }
    }
    public function cspaycl($data) {
        if (!is_array($data)) {
            $this->error('参数错误');
        }
        $uname = $data['ue_account'];
        $fname = $data['ue_accname'];
        $uid = $data['ue_id'];
        //
        /*     $reg_time = strtotime($data['ue_regtime']);
        $time = time();
        $reg_feng_days = C('reg_feng_days');
        
        if($time-$reg_time > $reg_feng_days*3600*24){
            $tgbz = M('tgbz')->where(array('user'=>$_SESSION['ue_account']))->count();
            if($tgbz == 0){
                M('user')->where(array('UE_account'=>$data['ue_account']))->limit(1)->delete();
                $this->error('由于你注册之后未在'.$reg_feng_days.'天内提供帮助,我们无法为你继续提供服务!');
            }
        }*/
        $ppdd = M('ppdd');
        $where = array();
        $where['p_user'] = $uname;
        $where['zt'] = 0;
        $rs = $ppdd->where($where)->select();
        if ($rs) {
            $jjdktime = C("jjdktime");
            $jjhydjmsg = C("jjhydjmsg");
            $jjhydjkcsjmoeney = C("jjhydjkcsjmoeney");
            $nowtime = time();
            $cszt = 0;
            foreach ($rs as $v) {
                $pdtime = strtotime($v['date']);
                $cstime = $pdtime + 3600 * $jjdktime;
                if ($cstime < $nowtime) {
                    $cszt = 1;
                    break;
                }
            }
            if ($cszt) {
                $user = M('user');
                $data2 = array();
                $data2['UE_ID'] = $uid;
                $data2['UE_status'] = 1;
                $user->save($data2);
                //封号的同时删除未匹配上的当好
                //M('tgbz')->where(array('user' => $uname))->delect();
                //M('jsbz')->where(array('user' => $uname))->delect();
                if ($jjhydjkcsjmoeney && $fname) {
                    $where = array();
                    $where['UE_account'] = $fname;
                    $user->where($where)->setDec('UE_money', $jjhydjkcsjmoeney);
                }
                die("<script>alert('.$jjhydjmsg.');history.back(-1);</script>");
            }
        }
        //
        
    }
}
