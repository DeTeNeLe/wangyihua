<?php

namespace Home\Controller;

use Think\Controller;
use \Think\Page;

class InfoController extends CommonController
{
    // 首頁
    public function index()
    {
        $userData = M('user')->where(array('UE_ID' => $_SESSION ['uid']))->find();
        $this->userData = $userData;

		$accuserData = M('user')->where(array('UE_account' => $userData['ue_accname']))->find();
        $this->accuserData = $accuserData;

        $ip = M('drrz')->where(array('user' => $_SESSION ['uname'], 'leixin' => 0))->order('id DESC')->limit(2)->select();

        $this->bcip = $ip[0];
        $this->scip = $ip[1];
        $this->grsz = true;

		$this->assign('pinfo_active','active');

        $this->display('grsz');
    }

    public function xgmm()
    {
        $userData = M('user')->where(array(
            'UE_ID' => $_SESSION ['uid']
        ))->find();
        $this->userData = $userData;
        $this->display('xgmm');
    }

    public function xgmme()
    {
        $userData = M('user')->where(array(
            'UE_ID' => $_SESSION ['uid']
        ))->find();
        $this->userData = $userData;
        $this->display('xgmme');
    }

    public function bdmb()
    {
        $userData = M('user')->where(array(
            'UE_ID' => $_SESSION ['uid']
        ))->find();
        $this->userData = $userData;
        if ($userData['ue_question'] == '') {
            $this->display('bdmb');
        } else {
            $this->display('xgmb');
        }
    }

    public function xgmb()
    {
        $userData = M('user')->where(array(
            'UE_ID' => $_SESSION ['uid']
        ))->find();
        $this->userData = $userData;
        $this->display('xgmb');
    }

    public function ejmm()
    {
        $this->display('ejmm');
    }

    public function ejmmcl()
    {
        if (IS_POST) {
            $data_P = I('post.');
            $addaccount = M('user')->where(array(UE_account => $_SESSION ['uname']))->find();
            
            if ($addaccount['ue_secpwd'] <> md5($data_P['ejmmqr'])) {
                $this->error('二級密碼不正確!');
            } else {
                $_SESSION['ejmmyz'] = $addaccount['ue_secpwd'];
                $this->success('驗證成功', $_SESSION['url']);
            }
        }
    }

    public function xgzlcl()
    {
        if (IS_POST) {
            if(C('sms_open_mod') == "1")
				$this->check_phone_ajax();
            $data_P = I('post.');
            $tgbztj = M('ppdd')->where(array('p_user' => $_SESSION['uname'], 'zt' => '2'))->sum('jb');

            if ($tgbztj > 0) {
				$this->ajaxReturn(array('nr' => '提供帮助成功后不可修改个人信息！', 'sf' => 0));
            } else {
                $userxx = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();
                
				$data_up['weixin'] = authcode($data_P['wechat'],'ENCODE',C('base64_code_pwd'),0);
				$data_up['zfb'] = authcode($data_P['alipay'],'ENCODE',C('base64_code_pwd'),0);
				$data_up['yhmc'] = authcode($data_P['yhmc'],'ENCODE',C('base64_code_pwd'),0);
				$data_up['remark'] = $data_P['remark'];
				$data_up['yzf'] = authcode($data_P['yzf'],'ENCODE',C('base64_code_pwd'),0);
				$data_up['yhzh'] = authcode($data_P['yhzh'],'ENCODE',C('base64_code_pwd'),0);
				$data_up['yhckr'] = authcode($data_P['yhckr'],'ENCODE',C('base64_code_pwd'),0);
				$data_up['yhzhxx'] = authcode($data_P['yhzhxx'],'ENCODE',C('base64_code_pwd'),0);
				$reg = M('user')->where(array('UE_account' => $_SESSION['uname']))->save($data_up);
				if ($reg) {
					$this->ajaxReturn(array('nr' => '修改成功！', 'sf' => 1));
				} else {
					$this->ajaxReturn(array('nr' => '修改失败！', 'sf' => 0));
				}
            }
        }
    }


    public function xgyjmmcl()
    {
        if (IS_POST) {
            $data_P = I('post.');
			if($data_P['xmm'] == "" || $data_P['xmm'] == null)
				$this->ajaxReturn(array('nr' => '不能为空!', 'sf' => 0));
            if (!preg_match('/^[a-zA-Z0-9]{1,15}$/', $data_P ['xmm']))
		    {
				$this->ajaxReturn(array('nr' => '新密碼6-12個字元,大小寫英文+數字,請勿用特殊詞符！', 'sf' => 0));
            } elseif ($data_P['xmm'] <> $data_P['xmmqr']) {
				$this->ajaxReturn(array('nr' => '新密碼兩次輸入不一致!', 'sf' => 0));
            } elseif ($data_P['ymm'] == $data_P['xmm']) {
				$this->ajaxReturn(array('nr' => '原密碼和新密碼不能相同!', 'sf' => 0));
            } else {
                $addaccount = M('user')->where(array(UE_account => $_SESSION ['uname']))->find();
                if ($addaccount['ue_password'] <> md5($data_P['ymm'])) {
					$this->ajaxReturn(array('nr' => '原密碼不正確', 'sf' => 0));
                }else {
                    $reg = M('user')->where(array(
                        'UE_ID' => $_SESSION ['uid']
                    ))->save(array('UE_password' => md5($data_P['xmm'])));
                    if ($reg) {
						$this->ajaxReturn(array('nr' => '修改成功!', 'sf' => 0));
                    } else {
						$this->ajaxReturn(array('nr' => '修改失敗!', 'sf' => 0));
                    }
                }
            }
        }
    }
    public function secpwd(){
       
        $user = $user = M ( 'user' )->where ( array (
                    UE_account => $_SESSION ['uname'],UE_check=>1
            ) )->find ();
        $password = $user['ue_secpwd'];
      
    if(isset($_POST["pwd"])){ 
        if(md5($_POST["pwd"]) == $password){ 
           //setcookie("isview",$_POST["pwd"],time()+3600);
            $_GET['isview']='1';
            $this->redirect('index/jsbzcl',$_GET);
        }else{
        $p = (empty($_POST["pwd"])) ? "需要密码才能查看，请输入密码。" : "<div style=\"color:#F00;\">密码不正确，请重新输入。</div>";
        } 
      }else{
        
         $p = "请输入密码查看，获取密码可联系推荐人。";
      }
        $this->assign('p',$p);
        $this->display('info/secpwd');
    }
    public function secpwd2(){
       
        $user = $user = M ( 'user' )->where ( array (
                    UE_account => $_SESSION ['uname'],UE_check=>1
            ) )->find ();
        $password = $user['ue_secpwd'];
      
    if(isset($_POST["pwd"])){ 
        if(md5($_POST["pwd"]) == $password){ 
           //setcookie("isview",$_POST["pwd"],time()+3600);
            $_GET['isview']='1';
            $this->redirect('index/jsbzcl2',$_GET);
        }else{
        $p = (empty($_POST["pwd"])) ? "需要密码才能查看，请输入密码。" : "<div style=\"color:#F00;\">密码不正确，请重新输入。</div>";
        } 
      }else{
         $p = "请输入密码查看，获取密码可联系推荐人。";
      }
        $this->assign('p',$p);
        $this->display('info/secpwd');
    }
    
    public function xgejmmcl()
    {

        if (IS_POST) {
            $data_P = I('post.');
            if (!preg_match('/^[a-zA-Z0-9]{1,15}$/', $data_P ['xejmm'])) {
                //$this->ajaxReturn ( array ('nr' => '新二级密碼6-12個字元,大小寫英文+數字,請勿用特殊詞符！','sf' => 0 ) );
                die("<script>alert('新二级密碼6-12個字元,大小寫英文+數字,請勿用特殊詞符！');history.back(-1);</script>");
            } elseif ($data_P['xejmm'] <> $data_P['xejmmqr']) {
                //$this->ajaxReturn ( array ('nr' => '新二级密碼兩次輸入不一致!','sf' => 0 ) );
                die("<script>alert('新二级密碼兩次輸入不一致！');history.back(-1);</script>");
            } elseif ($data_P['yejmm'] == $data_P['xejmm']) {
                //$this->ajaxReturn ( array ('nr' => '原二级密碼和新密碼不能相同!','sf' => 0 ) );
                die("<script>alert('原二级密碼和新密碼不能相同！');history.back(-1);</script>");
            } else {
                $addaccount = M('user')->where(array(UE_account => $_SESSION ['uname']))->find();

                if ($addaccount['ue_secpwd'] <> md5($data_P['yejmm'])) {
                    //$this->ajaxReturn ( array ('nr' => '原二级密碼不正確!','sf' => 0 ) );
                    die("<script>alert('原二级密碼不正確！');history.back(-1);</script>");
                } else {

                    $reg = M('user')->where(array(
                        'UE_ID' => $_SESSION ['uid']
                    ))->save(array('UE_secpwd' => md5($data_P['xejmm'])));


                    if ($reg) {
                        //$this->ajaxReturn ( array ('nr' => '修改成功!','sf' => 0 ));
                        die("<script>alert('修改成功!');history.back(-1);</script>");
                    } else {
                        //$this->ajaxReturn ( array ('nr' => '修改失敗!','sf' => 0 ) );
                        die("<script>alert('修改失敗！');history.back(-1);</script>");
                    }
                }
            }
        }
    }

    public function bdmbadd()
    {

        if (IS_AJAX) {
            $data_P = I('post.');
            //dump($data_P);die;
            //$this->ajaxReturn($data_P['ymm']);die;
            //$user = M ( 'user' )->where ( array (
            //		UE_account => $_SESSION ['uname']
            //) )->find ();

            $user1 = M();
            //! $this->check_verify ( I ( 'post.yzm' ) )
            //! $user1->autoCheckToken ( $_POST )
            if (!$this->check_verify(I('post.yzm'))) {

                $this->ajaxReturn(array('nr' => '驗證碼錯誤!', 'sf' => 0));
            } elseif ($data_P['wt1'] == '0' || $data_P['wt2'] == '0' || $data_P['wt3'] == '0') {
                $this->ajaxReturn(array('nr' => '請選擇問題!', 'sf' => 0));
            } elseif ($data_P['wt1'] == $data_P['wt2'] || $data_P['wt1'] == $data_P['wt3'] || $data_P['wt2'] == $data_P['wt3']) {
                $this->ajaxReturn(array('nr' => '密保問題不能相同!', 'sf' => 0));
            } elseif (strlen($data_P['wt1']) > 60 || strlen($data_P['wt2']) > 60 || strlen($data_P['wt3']) > 60) {
                $this->ajaxReturn(array('nr' => '問題格式不對!', 'sf' => 0));
            } elseif (strlen($data_P['da1']) > 20 || strlen($data_P['da2']) > 20 || strlen($data_P['da3']) > 20) {
                $this->ajaxReturn(array('nr' => '答案1-10個字！', 'sf' => 0));
            } elseif (strlen($data_P['da1']) < 1 || strlen($data_P['da2']) < 1 || strlen($data_P['da3']) < 1) {
                $this->ajaxReturn(array('nr' => '答案1-10個字！', 'sf' => 0));
            } elseif (!$user1->autoCheckToken($_POST)) {
                $this->ajaxReturn(array('nr' => '新勿重複提交,請刷新頁面!', 'sf' => 0));
            } else {
                $addaccount = M('user')->where(array(UE_account => $_SESSION ['uname']))->find();

                if ($addaccount['ue_question'] <> '') {
                    $this->ajaxReturn(array('nr' => '您已經設置過密保!', 'sf' => 0));
                    //}elseif(false){
                    //	$this->ajaxReturn ( array ('nr' => '新勿重複提交,請刷新頁面!','sf' => 0 ) );
                } else {


                    $data_up['UE_question'] = $data_P['wt1'];
                    $data_up['UE_question2'] = $data_P['wt2'];
                    $data_up['UE_question3'] = $data_P['wt3'];
                    $data_up['UE_answer'] = $data_P['da1'];
                    $data_up['UE_answer2'] = $data_P ['da2'];
                    $data_up['UE_answer3'] = $data_P ['da3'];


                    $reg = M('user')->where(array(
                        'UE_ID' => $_SESSION ['uid']
                    ))->save($data_up);


                    if ($reg) {
                        $this->ajaxReturn(array('nr' => '綁定成功!', 'sf' => 0));
                    } else {
                        $this->ajaxReturn(array('nr' => '綁定失敗!', 'sf' => 0));
                    }
                }
            }
        }
    }

    public function xgmbadd()
    {

        if (IS_AJAX) {
            $data_P = I('post.');
            //dump($data_P);die;
            //$this->ajaxReturn($data_P['ymm']);die;
            //$user = M ( 'user' )->where ( array (
            //		UE_account => $_SESSION ['uname']
            //) )->find ();

            $user1 = M();
            //! $this->check_verify ( I ( 'post.yzm' ) )
            //! $user1->autoCheckToken ( $_POST )
            $addaccount = M('user')->where(array(UE_account => $_SESSION ['uname']))->find();
            if (!$this->check_verify(I('post.yzm'))) {
                $this->ajaxReturn(array('nr' => '驗證碼錯誤!', 'sf' => 0));
            } elseif ($addaccount['ue_question'] == '') {
                $this->ajaxReturn(array('nr' => '您從未綁定過密保,請先綁定保密!', 'sf' => 0));
            } elseif ($addaccount['ue_answer'] <> $data_P['yda1'] || $addaccount['ue_answer2'] <> $data_P['yda2'] || $addaccount['ue_answer3'] <> $data_P['yda3']) {
                $this->ajaxReturn(array('nr' => '原密保答案不正確!', 'sf' => 0));
            } elseif ($data_P['wt1'] == '0' || $data_P['wt2'] == '0' || $data_P['wt3'] == '0') {
                $this->ajaxReturn(array('nr' => '請選擇新保密問題!', 'sf' => 0));
            } elseif ($data_P['wt1'] == $data_P['wt2'] || $data_P['wt1'] == $data_P['wt3'] || $data_P['wt2'] == $data_P['wt3']) {
                $this->ajaxReturn(array('nr' => '新保密問題不能相同!', 'sf' => 0));
            } elseif (strlen($data_P['wt1']) > 60 || strlen($data_P['wt2']) > 60 || strlen($data_P['wt3']) > 60) {
                $this->ajaxReturn(array('nr' => '新保密問題格式不對!', 'sf' => 0));
            } elseif (strlen($data_P['da1']) > 20 || strlen($data_P['da2']) > 20 || strlen($data_P['da3']) > 20) {
                $this->ajaxReturn(array('nr' => '新保密答案1-10個字！', 'sf' => 0));
            } elseif (strlen($data_P['da1']) < 1 || strlen($data_P['da2']) < 1 || strlen($data_P['da3']) < 1) {
                $this->ajaxReturn(array('nr' => '新保密答案1-10個字！', 'sf' => 0));
            } elseif (false) {
                $this->ajaxReturn(array('nr' => '新勿重複提交,請刷新頁面!', 'sf' => 0));
            } else {


                //if ($addaccount['ue_question']<>'') {
                //	$this->ajaxReturn ( array ('nr' => '您已經設置過密保!','sf' => 0 ) );
                //}elseif(false){
                //	$this->ajaxReturn ( array ('nr' => '新勿重複提交,請刷新頁面!','sf' => 0 ) );
                //} else {


                $data_up['UE_question'] = $data_P['wt1'];
                $data_up['UE_question2'] = $data_P['wt2'];
                $data_up['UE_question3'] = $data_P['wt3'];
                $data_up['UE_answer'] = $data_P['da1'];
                $data_up['UE_answer2'] = $data_P ['da2'];
                $data_up['UE_answer3'] = $data_P ['da3'];


                $reg = M('user')->where(array(
                    'UE_ID' => $_SESSION ['uid']
                ))->save($data_up);


                if ($reg) {
                    $this->ajaxReturn(array('nr' => '修改成功!', 'sf' => 0));
                } else {
                    $this->ajaxReturn(array('nr' => '修改失敗!', 'sf' => 0));
                }
                //}
            }
        }
    }

    public function cwmx()
    {

//jerry
        $tgbz = M("tgbz");
        $result = $tgbz->where(array("user" => $_SESSION['uname'], "zt" => 0))->order('id DESC')->select();
        $this->v_list = $result;
        //jerry

        //////////////////----------
        $User = M('user_jj'); // 實例化User對象

        $map1['user'] = $_SESSION['uname'];
        $count1 = $User->where($map1)->count(); // 查詢滿足要求的總記錄數
        //$page = new \\Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

        $p1 = getpage($count1, 10);

        $list1 = $User->where($map1)->order('id DESC')->limit($p1->firstRow, $p1->listRows)->select();
        $this->assign('list1', $list1); // 賦值數據集
        $this->assign('page1', $p1->show()); // 賦值分頁輸出
        /////////////////----------------

        //////////////////----------
        $User = M('user_jl'); // 實例化User對象

        $map2['user'] = $_SESSION['uname'];
        $count2 = $User->where($map2)->count(); // 查詢滿足要求的總記錄數
        //$page = new \\Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

        $p2 = getpage($count2, 10);

        $list2 = $User->where($map2)->order('id DESC')->limit($p2->firstRow, $p2->listRows)->select();
        $this->assign('list2', $list2); // 賦值數據集
        $this->assign('page2', $p2->show()); // 賦值分頁輸出
        /////////////////----------------


        //////////////////----------
        $User = M('userget'); // 實例化User對象

        $map['UG_account'] = $_SESSION['uname'];
        $count = $User->where($map)->count(); // 查詢滿足要求的總記錄數
        //$page = new \\Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

        $p = getpage($count, 10);

        $list = $User->where($map)->order('UG_ID DESC')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('list', $list); // 賦值數據集
        $this->assign('page', $p->show()); // 賦值分頁輸出
        /////////////////----------------



        //////////////////----------
       /* $User = M('tixian'); // 實例化User對象

        $map['UG_account'] = $_SESSION['uname'];
        $count = $User->where($map)->count(); // 查詢滿足要求的總記錄數
        //$page = new \\Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

        $p = getpage($count, 10);

        $txlist = $User->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
		//echo "<pre>";print_r($txlist);exit;
        $this->assign('txlist', $txlist); // 賦值數據集
        $this->assign('txpage', $p->show()); // 賦值分頁輸出*/
        /////////////////----------------


        $userdata = M('user')->where(array(
            UE_account => $_SESSION ['uname']
        ))->find();

        $this->userdata = $userdata;


        $fenhong1 = M('userget')->where(array('UG_account' => $_SESSION ['uname'], 'UG_dataType' => 'tjj'))->sum('UG_money');

        if ($fenhong1 == '') {
            $fenhong1 = '0';
        }
        //$fenhong2='600';
        $this->fenhong1 = $fenhong1;


        $fenhong2 = M('userget')->where(array('UG_account' => $_SESSION ['uname'], 'UG_dataType' => 'jlj'))->sum('UG_money');

        if ($fenhong2 == '') {
            $fenhong2 = '0';
        }
        //$fenhong2='600';
        $this->fenhong2 = $fenhong2;

		$this->txstatus = C("txstatus");
		$this->txthemin = C("txthemin");
		$this->txrate = C("txrate");
		$this->txthemax = C("txthemax");
		$this->txthebeishu = C("txthebeishu");

        //经理奖提现限制
        $this->jl_start = C("jl_start");
        $this->jl_e = C("jl_e");
        $this->jl_beishu = C("jl_beishu");

        //推荐奖提现限制
        $this->tj_start= C("tj_start");
        $this->tj_e = C("tj_e");
        $this->tj_beishu = C("tj_beishu");

        $this->display('cwmx');
    }

    //rwhistory 转出
    public function tgbz_tx_cl()
    {
        if (I('get.id') <> '') 
		{
            $uname = $_SESSION['uname'];
            $starttime = date('Y-m-d 00:00:01', time());
            $endtime = date('Y-m-d 23:59:59', time());
            $count1 = M("userget")->where("UG_getTime>='$starttime' and UG_getTime<='$endtime' and UG_account='$uname' and UG_dataType='tgbz'")->count();
            if ($count1 == 50) {
                die("<script>alert('提现失败，每天只允许提现五次！');history.back(-1);</script>");
            } else {
                $starttime = date('Y-m-1 00:00:01', time());
                $endtime = date('Y-m-31 23:59:59', time());
                $count2 = M("userget")->where("UG_getTime>='$starttime' and UG_getTime<='$endtime' and UG_account='$uname' and UG_dataType='tgbz'")->count();
                if ($count2 >= 60) {
                    die("<script>alert('提现失败，每月只允许提现60次！');history.back(-1);</script>");
                } else {
                    $varid = I('get.id');
                    $proall = M('user_jj')->where(array('id' => $varid))->find();
					
                    $ppdd_list = M('ppdd')->where(array('id'=>$proall['r_id']))->find();

					//提供帮助的订单
					$tgbz = M('tgbz')->where(array('id'=>$ppdd_list['p_id']))->find();

					if(!$proall)
						die("<script>alert('订单不存在!');history.back(-1);</script>");
					if($proall['zt'] == '1' && $tgbz['dj'] == 0)
					{
						die("<script>alert('订单已完成,请勿多次提交!');history.back(-1);</script>");
					}
					if($proall['isprepay'] == 1)
					{
						die("<script>alert('提交异常!');history.back(-1);</script>");
					}
					if($ppdd_list['zt'] <> 2)
					{
						die("<script>alert('订单未确认，请等待确认后再提款!');history.back(-1);</script>");
					}
					//冻结天数 -------------------
					if(C('jjdjdays')>0)
					{
						$now_day = time();
						$dakuan_day = strtotime($ppdd_list['date_hk']);
						$jdtime = $dakuan_day + C('jjdjdays')*3600*24;
						if($now_day < $jdtime){
							 die("<script>alert('打款完后要等".C('jjdjdays')."天才可以提现哦！');history.back(-1);</script>");
						}
					}

                    /*
					if(!check_all_chaifen_tx_enabled($tgbz['mainid']) )
						die("<script>alert('您有拆分的订单，请完全交易成功后再提现');history.back(-1);</script>");
					*/

					if (isset($_SESSION['havepost']))
						die("<script>alert('正在处理,请勿多次提交！');history.back(-1);</script>");
					else
						$_SESSION['havepost'] = "y";

					$result = M('user_jj')->where(array('main_p_id' => $proall['main_p_id']))->save(array('zt' => '1'));

					if(!$result)
						$this->error("提现出错" . $lx_he,'',2);


					$lx_he = user_jj_zong_lx($varid) + $proall['total']  + $tgbz['qd'];
					$note3 = "提供帮助本金加利息";
					$user_zq = M('user')->where(array('UE_ID' => $_SESSION['uid']))->find();
					M('user')->where(array('UE_ID' => $_SESSION['uid']))->setInc('UE_money', $lx_he);
					M('tgbz')->where(array('id' => $ppdd_list['p_id']))->save(array('dj'=>0,'had_zc'=>$lx_he,'jdstate'=>1));


					$user_xz = M('user')->where(array('UE_ID' => $_SESSION['uid']))->find();

					$record3["UG_account"] = $_SESSION['uname']; 
					$record3["UG_type"] = 'jb';
					$record3["UG_allGet"] = $user_zq['ue_money']; 
					$record3["UG_money"] = '+' . $lx_he; //
					$record3["UG_balance"] = $user_xz['ue_money'];
					$record3["UG_dataType"] = 'tgbz'; 
					$record3["UG_note"] = $note3; 
					$record3["UG_getTime"] = date('Y-m-d H:i:s', time()); 
					$record3["varid"] = $varid;
					$reg4 = M('userget')->add($record3);
					
					unset($_SESSION['havepost']);
					$this->success("提现转出成功" . $lx_he,'',2);
                }
            }
        }
    }

    public function jhm()
    {
        $map['user'] = $_SESSION['uname'];
        $jhm_count = M('jhm_log')->where($map)->count();
        $p_jhm = getpage($jhm_count, 10);
        $jhm_list = M('jhm_log')->where($map)->limit($p_jhm->firstRow.','.$p_jhm->listRows)->order('date desc')->select();

        $this->assign('jhm_list',$jhm_list);
        $this->assign('jhm_page',$p_jhm->show());
		$this->assign('jhm_count',$jhm_count);

		$this->assign('jhm_active','active');

        $this->display('jhm');
    }



    public function paidan()
    {
		$map['user'] = $_SESSION['uname'];
        $paidan_count = M('paidan_log')->where($map)->count();
        $p_paidan = getpage($paidan_count, 10);
        $paidan_list = M('paidan_log')->where($map)->limit($p_paidan->firstRow.','.$p_paidan->listRows)->order('date desc')->select();

        $this->assign('paidan_list',$paidan_list);
        $this->assign('paidan_page',$p_paidan->show());
		$this->assign('paidan_count',$paidan_count);

		$this->assign('pdm_active','active');

        $this->display('paidan');
    }


    public function aab()
    {


        $arr = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
        $p = 0;
        $tj = count($arr);

        //$tj1=$tj;
        //$bba=array_slice($arr,0,1);
        //dump($bba);
        //die;
        //0,4


        for ($m = 0; $m < $tj; $m++) {


            for ($p = 2; $p + $m < $tj; $p++) {
                if ($tj - $m < $p) {
                    break;
                }//1,4  5
                $bba = array_slice($arr, $m, 2);

                //echo $arr[$p].'</br>';
                $bba[] = $arr[$p + $m];

                foreach ($bba as $var)
                    echo $var . '+';

                //dump($bba);
                echo '=' . array_sum($bba) . '<br/>';
                //$bba=array();
            }
            //$tj1--;
            //$a=
            //$tj2=$tj1-1;
            //echo '------------<br>';


        }


        //die;


        for ($m = 0; $m < $tj; $m++) {


            for ($p = 2; $p <= $tj; $p++) {
                if ($tj - $m < $p) {
                    break;
                }//1,4  5
                $bba = array_slice($arr, $m, $p);
                // dump($bba);
                foreach ($bba as $var)
                    echo $var . '+';

                echo '=' . array_sum($bba) . '<br/>';
                //$bba=array();
            }
            //$tj1--;
            //$a=
            //$tj2=$tj1-1;
            //echo '------------<br>';


        }


        die;


        sort($arr); //保证初始数组是有序的
        $last = count($arr) - 1; //$arr尾部元素下标
        $x = $last;
        $count = 1; //组合个数统计
        echo implode(',', $arr), "\n"; //输出第一种组合
        echo "<br/>";
        while (true) {
            $y = $x--; //相邻的两个元素
            if ($arr[$x] < $arr[$y]) { //如果前一个元素的值小于后一个元素的值
                $z = $last;
                while ($arr[$x] > $arr[$z]) { //从尾部开始，找到第一个大于 $x 元素的值
                    $z--;
                }
                /* 交换 $x 和 $z 元素的值 */
                list($arr[$x], $arr[$z]) = array($arr[$z], $arr[$x]);
                /* 将 $y 之后的元素全部逆向排列 */
                for ($i = $last; $i > $y; $i--, $y++) {
                    list($arr[$i], $arr[$y]) = array($arr[$y], $arr[$i]);
                }
                echo implode(',', $arr), "\n"; //输出组合
                echo "<br/>";
                $x = $last;
                $count++;
            }
            if ($x == 0) { //全部组合完毕
                break;
            }
        }
        echo '组合个数： ', $count, "\n";
        //输出结果为：3628800个


        die;


        $xypipeije = 16;
        $data = array(1, 2, 3, 4, 5, 6, 7, 8);
        $tj = count($data);
        $sf_tcpp = '0';

        for ($m = 0; $m < $tj; $m++) {

            for ($p = 0; $p < $tj - $m; $p++) {
                $data1[$m][$p] = $data[$m];

            }
        }
        $adsfdsaf = $data1[0];
        dump($adsfdsaf);
        die;

        for ($v = 0; $v < $tj; $v++) {

            for ($c = 0; $c < $tj; $c++) {
                echo $data[$v] + $data[$c + 1] . '<br>';

            }
        }

        die;


        for ($b = 0; $b < $tj; $b++) {


            if ($sf_tcpp == '1') {
                break;
            }
            $tj_j = $tj - 1;
            echo '===========<br>';
            for ($i = 0; $i < $tj; $i++) {
                if ($b < $i) {
                    $pipeihe = $data[$b] + $data[$tj_j];
                    if ($pipeihe == $xypipeije) {
                        echo $data[$b] . '+' . $data[$tj_j] . '<br>';
                        $sf_tcpp = '1';
                        break;
                    }


                    $tj_j--;
                }
            }
        }


    }


    public function pintopin(){
        //导航激活
        $this->pin_zs = M('pin')->where(array('user' => $_SESSION['uname'], 'zt' => 0))->count();
        $this->manage = true;
        $this->display();
    }


    public function paitopai(){
        //导航激活
        $this->pin_zs = M('paidan')->where(array('user' => $_SESSION['uname'], 'zt' => 0))->count();
        $this->manage = true;
        $this->display();
    }


    public function gdhistory(){
        //激活导航
        $this->detail = true;
        $this->gdlist = M('jsbz')->where(array('user'=>$_SESSION['uname']))->select();
        $this->display();
    }

    public function pdhistory(){
        //激活导航
        $this->detail = true;
        $this->pdlist = M('tgbz')->where(array('user'=>$_SESSION['uname']))->select();
        $this->display();
    }

   //钱包 
   public function wallet()
   {
	   $map_tg['zt'] =  array('in','0');
	   $jjshowall=1;
	   $this->assign('jjshowall', $jjshowall);
       $tgbz = M("tgbz");
	   $map_tg['user'] = $_SESSION['uname'];
	   $map_tg['_string'] = "mainid = id";
       $result = $tgbz->where($map_tg)->order('id DESC')->select();
	   $tg_count = $tgbz->where($map_tg)->count(); 
       $this->tgbz_list = $result;
	   $this->assign('tg_count', $tg_count); 

       $user_jj = M('user_jj');
	   
	   $map_jj['zt'] =   array(in, array('0'));

	   if(I('get.jjshowall') == '1')
	   {
		   $this->assign('jjshowall', 0);
		   $map_jj['zt'] =  array(in, array('0','1'));
	   }

       $map_jj['user'] = $_SESSION['uname'];
	   $map_jj['isprepay'] = 0;
	   $map_jj['_string'] = "p_id = main_p_id";
       $jj_count = $user_jj->where($map_jj)->count(); 
       $p1 = getpage($jj_count, 10);

       $list1 = $user_jj->where($map_jj)->order('id DESC')->limit($p1->firstRow, $p1->listRows)->select();

       $this->assign('jj_list', $list1); 
       $this->assign('jj_page', $p1->show()); 
	   $this->assign('jj_count', $jj_count); 

	   $this->assign('jj_tg_count_sum', $jj_count + $tg_count); 

	   $userget = M('userget');
       $map_bonus['UG_account'] = $_SESSION['uname'];
       $arr=array('tuanjijain','tuijian_jl','zhuan','tqjl','tjj','jlj','tgbz','jsbz','xtzs','tiqian_lx');
       $map_bonus['UG_dataType'] = array("in",$arr);
       $bonus_count = $userget->where($map_bonus)->count(); 
       $p = getpage($bonus_count, 10);
       $bonus_list = $userget->where($map_bonus)->order('UG_ID DESC')->limit($p->firstRow, $p->listRows)->select();
       $get_paidan= $this->tuijian_total;
       $tuijian_dongjie=($get_paidan)*(C('tuijian_dj')/100);
       $this->assign('tuijian_max', $tuijian_dongjie);
       $tuijian_allow=($this->tuijian_total)-$tuijian_dongjie?($this->tuijian_total)-$tuijian_dongjie:0;
       $this->assign('tuijian_allow',$tuijian_allow);
       $this->assign('bonus_list', $bonus_list);
       $this->assign('bonus_page', $p->show()); 
	   $this->assign('bonus_count', $bonus_count);
	
	   $this->assign('wallet_active','active');

       $this->display();
   }

      public function shopjifen()
    {

	    $map['user'] = $_SESSION['uname'];
        $shopjifen_count = M('shopjifen_log')->where($map)->count();
        $p_shopjifen = getpage($shopjifen_count, 10);
        $shopjifen_list = M('shopjifen_log')->where($map)->limit($p_shopjifen->firstRow.','.$p_shopjifen->listRows)->order('date desc')->select();

        $this->assign('shopjifen_list',$shopjifen_list);
        $this->assign('shopjifen_page',$p_shopjifen->show());
		$this->assign('shopjifen_count',$shopjifen_count);

        $this->assign('shopjifen_active','active');

        $this->display();
    }

   public function jifen()
    {

	    $map['user'] = $_SESSION['uname'];
        $jifen_count = M('jifen_log')->where($map)->count();
        $p_jifen = getpage($jifen_count, 10);
        $jifen_list = M('jifen_log')->where($map)->limit($p_jifen->firstRow.','.$p_jifen->listRows)->order('date desc')->select();

        $this->assign('jifen_list',$jifen_list);
        $this->assign('jifen_page',$p_jifen->show());
		$this->assign('jifen_count',$jifen_count);

        $User = M('userget'); // 實例化User對象
        $map['UG_account'] = $_SESSION['uname'];
        $arr = array('tiqian_lx', 'zhuanzhang');

        $map['UG_dataType'] = array("in", $arr);
        $count = $User->where($map)->count();
        //$page = new \\Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

        $p = getpage($count, 10);

        $list = $User->where($map)->order('UG_ID DESC')->limit($p->firstRow, $p->listRows)->select();

        $listone = M('tgbz')->where(array('user' => $_SESSION['uname'], 'zt' => '1'))->order('id desc')->find();

        $chencin_dongjie = 0;
        if (!empty($listone) && $listone['in_time']) {
            $chencin_dongjie = $listone['jb'] * C('chenxin_dj') / 100;
        }

        $this->assign('chenxin_max', $chencin_dongjie);

        $chenxin_allow = (($this->chenxin_total) - $chencin_dongjie)>0 ? (($this->chenxin_total) - $chencin_dongjie) : 0;
        $this->assign('chenxin_allow', $chenxin_allow);
        $this->assign('list', $list); // 賦值數據集
        $this->assign('page', $p->show());
        

        $this->assign('jifen_active','active');

        $this->display();
    }


	
   //积分钱包
   public function jifen1(){
     $User = M('userget'); // 實例化User對象

        $map['UG_account'] = $_SESSION['uname'];
        //$map['UG_type'] = array('neq','pd');
     $arr=array('jifen_jl');

        $map['UG_dataType'] = array("in",$arr);
        $count = $User->where($map)->count(); 
        $p = getpage($count, 10);

        $list = $User->where($map)->order('UG_ID DESC')->limit($p->firstRow, $p->listRows)->select();
        //$listone=M('tgbz')->where(array('user'=>$_SESSION['uname'],'zt'=>'1'))->order('date desc')->find();
       
        $get_paidan= $this->tuijian_total;
        
        $tuijian_dongjie=($get_paidan)*(C('tuijian_dj')/100);
        
          $this->assign('tuijian_max', $tuijian_dongjie);
          
          $tuijian_allow=($this->tuijian_total)-$tuijian_dongjie?($this->tuijian_total)-$tuijian_dongjie:0;
          $this->assign('tuijian_allow',$tuijian_allow);
        $this->assign('list', $list); // 賦值數據集
        $this->assign('page', $p->show()); // 賦值分頁輸出
    //激活导航
    $this->detail = true;
    $this->display();
   }

   
   public function sendPhone_info()
   {
	   $phone = $_POST['phone'];
	   if(!checkSMSLimits($phone,5))
	   {
			sendCheckCode($_SESSION['uanme'],$phone,5);
	   }
    }

    
    public function check_phone(){ 
        $phone = $_POST['phone_check'];
        if(empty($phone )){
            die("<script>alert('验证码不能为空!');history.back(-1);</script>");
        }
        elseif($phone  !=session('CHECK_CODE')){
            die("<script>alert('验证码错误!');history.back(-1);</script>");
		}   
    }

	 public function check_phone_ajax(){ 
        $phone = $_POST['phone_check'];
        if(empty($phone )){
			$this->ajaxReturn(array('nr' => '验证码不能为空', 'sf' => 0));
        }
        elseif($phone  !=session('CHECK_CODE')){
			$this->ajaxReturn(array('nr' => '验证码错误', 'sf' => 0));
		}   
    }

    //诚信奖转账
    public function chenxin_zz(){

        if(IS_POST){

			$num = I('post.num');
			
			if ($num < C("cxj_txmin")) {
                die("<script>alert('转出金额" . C("cxj_txmin") . "起并且是" . C("cxj_txbeishu") . "的倍数！');history.back(-1);</script>");
            } elseif ($data_P['get_amount'] % C("txthebeishu") > 0) {
                die("<script>alert('转出金额" . C("cxj_txmin") . "起并且是" . C("cxj_txbeishu") . "的倍数！');history.back(-1);</script>");
            }
			 
			$chencin_dongjie = 0;
			$listone = M('tgbz')->where(array('user' => $_SESSION['uname'], 'zt' => '1'))->order('id desc')->find();
			if (!empty($listone) && $listone['in_time']) {
				$chencin_dongjie = $listone['jb'] * C('chenxin_dj') / 100;
			}
			$chenxin_allow =($this->chenxin_total) - $chencin_dongjie;
			
			if($num > $chenxin_allow){
				die("<script>alert('超出诚信奖可转余额！');history.back(-1);</script>");
			}

			if (!preg_match('/^[0-9.]{1,10}$/', $num)) {
				$this->error('请填写正确的数量！');
			}else {
				$accname_xz = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();
				$result1=M('user')->where(array('UE_account' =>$_SESSION['uname']))->setDec('jifen', $num);
				$result2=M('user')->where(array('UE_account' =>$_SESSION['uname']))->setInc('UE_money', $num);
				
				//在userget表里添加一条数据
				$record3 ["UG_account"] = $_SESSION['uname']; // 登入轉出賬戶
				$record3 ["UG_type"] = 'jb';
				$record3 ["UG_allGet"] = $accname_xz['jifen']; // 金幣
				$record3 ["UG_money"] = '-' . $num; //
				$record3 ["yb"] = 0; //
				$record3 ["UG_balance"] = ($accname_xz['jifen'] - $num); // 當前推薦人的金幣餘額
				$record3 ["UG_dataType"] = 'zhuanzhang'; // 金幣轉出
				$record3 ["UG_note"] = '诚信奖转静态钱包'; // 推薦獎說明
				$record3["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作時間
				$reg4 = M('userget')->add($record3);
				
				if($result1 && $result2){
					 
					$this->success('成功转到静态钱包'.$num);
				}
			}
        }
        else{
            die("<script>alert('参数错误！');history.back(-1);</script>");
        }
    }

	public function coinchange()
	{
	   $this->display ( 'coinchange' );
	}
    
    //激活码转账
    public function jhm_zz(){
        if(IS_POST){

			$num = I('post.num');
			$to_user = I('post.to_user');
			$to_user= str_replace(' ', '', $to_user);
			$alluser=get_last_all_user2($_SESSION['uname']);
				if(!in_array(trim($to_user),$alluser)){
					 die("<script>alert('转入用户不是您的下级，不能转让！');history.back(-1);</script>");
				}
			$user = M('user')->where(array(
				'UE_account' => $to_user
			))->find();

			$count = M('pin')->where(array('user'=>$_SESSION['uname'],'zt'=>0))->count();

			if($num>$count){
				die("<script>alert('激活码数量不足！');history.back(-1);</script>");
			}

			if (!$user) {
				die("<script>alert('用户不存在！');history.back(-1);</script>");
			} elseif (!preg_match('/^[0-9.]{1,10}$/', $num)) {
				$this->error('请填写正确的数量！');
			} else {
				$cgsl = 0;

				$my_pin = M('pin')->where(array('user'=>$_SESSION['uname'],'zt'=>0))->select();

				for($i=0;$i<$num;$i++){

					M('pin')->where(array('id'=>$my_pin[$i]['id']))->setField('user',$to_user);
					$map['user'] = $_SESSION['uname'];
					$map['to_user']= $to_user;
					$map['pin']= $my_pin[$i]['pin'];
					$map['date']= date('Y-m-d H:i:s', time());
					M('pin_log')->add($map);
					$cgsl++;
				}

				$this->success('成功转给'. $cgsl . '个激活码');
			}
        }
        else{

            die("<script>alert('参数错误！');history.back(-1);</script>");
        }
    }


    //paidan转账
    public function paidan_zz(){


        if(IS_POST){

            $num = I('post.num');
            $to_user = I('post.to_user');
            $to_user= str_replace(' ', '', $to_user);
            $alluser=get_last_all_user2($_SESSION['uname']);
            //var_dump($alluser);die;

            if(!in_array(trim($to_user),$alluser)){
                 die("<script>alert('转入用户不是您的下级，不能转让！');history.back(-1);</script>");
            }
            $user = M('user')->where(array(
                'UE_account' => $to_user
            ))->find();

            $count = M('paidan')->where(array('user'=>$_SESSION['uname'],'zt'=>0))->count();

            if($num>$count){
                die("<script>alert('排单币数量不足！');history.back(-1);</script>");
            }
            if (!$user) {
                die("<script>alert('用户不存在！');history.back(-1);</script>");
            } elseif (!preg_match('/^[0-9.]{1,10}$/', $num)) {
                $this->error('请填写正确的数量！');
            } else {
                $cgsl = 0;

                $my_pin = M('paidan')->where(array('user'=>$_SESSION['uname'],'zt'=>0))->select();

                for($i=0;$i<$num;$i++){ 

                    M('paidan')->where(array('id'=>$my_pin[$i]['id']))->setField('user',$to_user);
                    $map['user'] = $_SESSION['uname'];
                    $map['to_user']= $to_user;
                    $map['paidan']= $my_pin[$i]['paidan'];
                    $map['date']= date('Y-m-d H:i:s', time());
                    M('paidan_log')->add($map);
                    $cgsl++;
                }
                $this->success('成功转给'. $cgsl . '个排单币');
            }
        }
        else{

            die("<script>alert('参数错误！');history.back(-1);</script>");
        }
    }

	public function get_userinfo_from_tgbzid()
	{
        $tgbzid = $_POST['tgbzid'];
		$tgbz = M('tgbz')->where("id = " . $tgbzid)->find();
		echo get_userinfo_from_user($tgbz['user']);
	}

	public function get_userinfo_from_jsbzid()
	{
        $jsbzid = $_POST['jsbzid'];
		$jsbz = M('jsbz')->where("id = " . $jsbzid)->find();
		echo get_userinfo_from_user($jsbz['user']);
	}

    public function get_username_by_username()
	{
        $username = $_POST['username'];
		echo get_ue_theme_from_user($username);
	}

}
