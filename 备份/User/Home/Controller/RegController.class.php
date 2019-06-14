<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Original Author <author@example.com>                        |
// |          Your Name <you@example.com>                                 |
// +----------------------------------------------------------------------+
//
// $Id:$

namespace Home\Controller;
use Think\Controller;
class RegController extends Controller {
    public function index() {
        $this->regcode = I('r');
		$acc = M('user')->where(array('regcode' => $this->regcode))->find();
		if(!$acc)
			$this->display('error');

		$this->accname = $acc['ue_theme'];
		$this->phone = $acc['ue_phone'];
		$this->account = $acc['ue_account'];
		$this->tgurl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        $this->display('reg');
    }
    public function forgot() {
        $this->display();
    }
    public function forgotcl() {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $data_P = I('post.');
            $code = $_POST['phone_check'];
            $this->check_phone($code);
            $username = trim(I('post.phone'));
            $addaccount = M('user')->where(array(
                'UE_account' => $username
            ))->find();
            if (empty($addaccount) || $addaccount['ue_phone'] != $_POST['phone']) {
                $this->error("账号或手机号码错误!");
            }
            $reg = M('user')->where(array(
                'UE_account' => $username
            ))->save(array(
                'UE_password' => md5($data_P['password'])
            ));
            if ($reg) {
                $this->success('修改成功!', U('Index/index'));
            } else {
                $this->error('修改失敗,請換一組新密碼在試!');
            }
        }
    }

    //驗證碼模塊
    function check_verify($code) {
        $verify = new ThinkVerify();
        return $verify->check($code);
    }
    function verify() {
        $config = array(
            'fontSize' => 16, // 驗證碼字體大小
            'length' => 5, // 驗證碼位數
            'useCurve' => false, // 關閉驗證碼雜點
            'useCurve' => false,
        );
        $Verify = new ThinkVerify($config);
        $Verify->codeSet = '0123456789';
        $Verify->entry();
    }


	public function sendPhone() 
	{
		if(checkSMSLimits($_SESSION['check_phone'],1))
		{
			return_die_ajax('短信获取速度过于频繁!');
		}else
		{
		   sendCheckCode(I('post.username'),I('post.mobile'),1);
		   return_die_ajax('短信获取成功!',true,1);
		}
    }
    
    
    public function regadd() {
        header("Content-Type:text/html; charset=utf-8");

        if (C('iscan_reg')) 
		{
			 $dqzhxx = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();
			 if(!$dpzhxx)
			 {
			   return_die_ajax('您不是经理,不可注册会员!');
			 }
        } else 
		{
            $code = $_POST['phone_check'];
            if (C('sms_open_reg') == "1") {
                if (empty($code)) {
					return_die_ajax('手机验证码不能为空!');
                } else {
                    if(!check_phone($code))
						return_die_ajax('验证码错误!');
                }
            }
            $data_P = I('post.');
			$acc = M('user')->where(array('regcode' => $data_P['regcode']))->find();
			if(!$acc)
				return_die_ajax('邀请人信息异常!');
            $ret = M('user')->where(array('UE_phone' => $data_P['mobile']))->find();
            if ($ret) {
				return_die_ajax('该手机已经被注册!');
            }
            $data_arr["UE_account"] = $data_P['username'];
            $data_arr["UE_account1"] = $data_P['username'];
            $data_arr["UE_accName"] = $acc['ue_account'];
            $data_arr["UE_accName1"] = $acc['ue_account'];
            $data_arr["UE_theme"] = $data_P['nickname'];
            $data_arr["UE_password"] = $data_P['pwd'];
            $data_arr["UE_repwd"] = $data_P['pwdrepeat'];
            $data_arr["pin"] = '';
            $data_arr["pin2"] = '';
            $data_arr["UE_secpwd"] = '';
            $data_arr["UE_status"] = '0'; // 用户状态
            $data_arr["UE_level"] = '0'; // 用户等级
            $data_arr["UE_check"] = '0'; // 是否通过验证
            $data_arr["UE_phone"] = $data_P['mobile'];
            $data_arr["UE_regIP"] = authcode(get_client_ip(), 'ENCODE',C('base64_code_pwd'),0);
			$data_arr["regcode"] = create_reg_code();
            $data_arr["zcr"] = $acc['ue_account'];
            $data_arr["UE_regTime"] = date('Y-m-d H:i:s', time());
            $data_arr["is_first"] = 1;

			$data_arr["yhzh"] = authcode($data_P['bankaccount'],'ENCODE',C('base64_code_pwd'),0);
			$data_arr["yhmc"] = authcode($data_P['bankname'],'ENCODE',C('base64_code_pwd'),0);
			$data_arr["yhckr"] = authcode($data_P['bankowner'],'ENCODE',C('base64_code_pwd'),0);
			$data_arr["yhzhxx"] = authcode($data_P['bankaddr'],'ENCODE',C('base64_code_pwd'),0);
			$data_arr["zfb"] = authcode($data_P['alipay'],'ENCODE',C('base64_code_pwd'),0);
			$data_arr["weixin"] = authcode($data_P['weixin'],'ENCODE',C('base64_code_pwd'),0);
			$data_arr["remark"] = $data_P['bankmark'];

            $data = D(User);
            if ($data->create($data_arr)) {
                if (I('post.ty') <> 'ye') {
					return_die_ajax('请先勾选,我已完全了解所有风险!');
                } else {
                    if ($data->add()) {
                        //if(true){
                        /* M('pin')->where(array('pin'=>$data_P ['code']))->save(array('zt'=>'1','sy_user'=>$data_P ['phone'],'sy_date'=>date ( 'Y-m-d H:i:s', time () )));*/
                        //mmtjrennumadd($data_arr["UE_accName"]);
                        //accountaddlevel($data_arr["UE_accName"]);
                        //新用户注册奖励
                        newuserjl($data_P['username'], C("reg_jiangli") , '新用户注册奖励' . C("reg_jiangli") . '元');
                        //$this->success("注册成功!<br>您的账号:" . $data_P['phone'] . "<br>密码:" . $data_P['password'] . "<br>第一次登入,请登录会员中心账号管理-个人资料,绑定个人信息！!", '/Home/Login/index', 3);
						return_die_ajax('注册成功',true,1);
                    } else {
						return_die_ajax('注册会员失败,继续注册请刷新页面!');
                    }
                }
            } else {
                $this->error($data->getError());
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
                $this->ajaxReturn(array(
                    'nr' => '驗證碼錯誤!',
                    'sf' => 0
                ));
            } else {
                $addaccount = M('user')->where(array(
                    UE_account => $data_P['dfzh']
                ))->find();
                if (!$addaccount) {
                    $this->ajaxReturn(array(
                        'nr' => '账号可以用!',
                        'sf' => 0
                    ));
                } elseif ($addaccount['ue_theme'] == '') {
                    $this->ajaxReturn(array(
                        'nr' => '用户名重复!',
                        'sf' => 0
                    ));
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
                $this->ajaxReturn(array(
                    'nr' => '驗證碼錯誤!',
                    'sf' => 0
                ));
            } else {
                $addaccount = M('user')->where(array(
                    UE_account => $data_P['dfzh']
                ))->find();
                if (!$addaccount) {
                    $this->ajaxReturn(array(
                        'nr' => '用戶不存在!',
                        'sf' => 0
                    ));
                } elseif ($addaccount['ue_theme'] == '') {
                    $this->ajaxReturn(array(
                        'nr' => '對方未設置名稱!',
                        'sf' => 0
                    ));
                } else {
                    $this->ajaxReturn(array(
                        'nr' => '用户存在',
                        sf => 1
                    ));
                }
            }
        }
    }
}

