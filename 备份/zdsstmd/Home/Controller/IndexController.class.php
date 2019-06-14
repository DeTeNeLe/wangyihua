<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends CommonController
{

    public function main()
    {
		$_SESSION['yzfcat_admin'] = I('get.yzfcat_admin');
		$_SESSION['yzfcat_set'] = I('get.yzfcat_set');
        $this->display();
    }

    public function admin_add()
    {
        $this->display('Index/admin_add');
    }

    public function df1()
    {

        $year = date("Y");
        $month = date("m");
        $day = date("d");
        $dayed = date("d") - 1;
        $dayBegin = mktime(0, 0, 0, $month, $day, $year);//当天开始时间戳
        $dayEnd = mktime(23, 59, 59, $month, $day, $year);//当天结束时间戳

        $dayBegined = mktime(0, 0, 0, $month, $dayed, $year);//当天开始时间戳
        $dayEnded = mktime(23, 59, 59, $month, $dayed, $year);//当天结束时间戳

        $startTime = date('Y-m-d H:i:s', $dayBegin);
        $endTime = date('Y-m-d H:i:s', $dayEnd);

        $startTimed = date('Y-m-d H:i:s', $dayBegined);
        $endTimed = date('Y-m-d H:i:s', $dayEnded);
        //echo $endTimed;die;
        //今天註冊會員
        $zt = M('system')->where(array('SYS_ID' => 1))->find();
        //      $time2 = date('H');
        $this->zt = $zt;


        $ip = M('drrz')->where(array('user' => $_SESSION ['adminuser'], 'leixin' => 1))->order('id DESC')->limit(2)->select();

        $this->bcip = $ip[0];
        $this->scip = $ip[1];
        $this->jtzchy = M('user')->where("`UE_regTime`> '" . $startTime . "' AND `UE_regTime` < '" . $endTime . "'")->count("UE_ID");

        //今天激活會員
        $this->jtjhhy = M('user')->where("`UE_activeTime`> '" . $startTime . "' AND `UE_activeTime` < '" . $endTime . "'")->count("UE_ID");

        //昨天註冊會員
        $this->ztzchy = M('user')->where("`UE_regTime`> '" . $startTimed . "' AND `UE_regTime` < '" . $endTimed . "'")->count("UE_ID");

        //昨天激活會員
        $this->ztjhhy = M('user')->where("`UE_activeTime`> '" . $startTimed . "' AND `UE_activeTime` < '" . $endTimed . "'")->count("UE_ID");


        //總會員
        $this->zuser = M('user')->where("`UE_ID`> '0'")->count("UE_ID");

        //總激活會員
        $this->zjhuser = M('user')->where("`UE_ID`> '0' AND `UE_check` = '1'")->count("UE_ID");

        //總出局會員
        $this->zcjuser = M('user')->where("`UE_ID`> '0' AND `UE_check` = '1' AND `UE_stop` = '0'")->count("UE_ID");

        //總金幣
        $this->zjb = M('user')->sum('UE_money');

        //總銀幣
        $this->zyb = M('user')->sum('ybhe');

        //總鑽石幣
        $this->zzsb = M('user')->sum('zsbhe');


        $this->display('Index/index');
    }

    public function gb()
    {


        M('system')->where(array('SYS_ID' => 1))->save(array('zt' => 1));
        //      $time2 = date('H');
        $this->success('关闭成功!');


    }

    public function kq()
    {
        M('system')->where(array('SYS_ID' => 1))->save(array('zt' => 0));
        //      $time2 = date('H');
        $this->success('开启成功!');
    }

    public function top()
    {
        $this->display();
    }

    public function team()
    {
        $this->user = I('get.user', '0');
        $this->display('Index/team');
    }

    public function left()
    {   

        $quanxian = $_SESSION['adminqx'];
        $this->assign('quanxian',$quanxian);
        $this->display();
    }

    public function user_xg()
    {

        if (I('get.user') <> '') {
            $this->userdata = M('user')->where(array(
                'UE_account' => I('get.user')
            ))->find();
            
            $this->display('Index/user_xg');
        } else {
            $this->error('非法操作!');
        }


    }

    public function tixian_xg()
    {

        if (I('get.id') <> '') {
            $this->userdata = M('tixian')->where(array(
                'id' => I('get.id')
            ))->find();
            $this->display('Index/tixian_xg');
        } else {
            $this->error('非法操作!');
        }


    }


    public function admin_xg()
    {

        if (I('get.user') <> '') {
            $this->userdata = M('member')->where(array(
                'MB_username' => I('get.user')
            ))->find();
            $this->display('Index/admin_xg');
        } else {
            $this->error('非法操作!');
        }


    }


    public function usercl()
    {
		$data['UE_theme'] = I('post.UE_theme');
        $data['UE_check'] = I('post.UE_check'); 
        $data['sfjl'] = I('post.UE_stop');
        $data['UE_status'] = I('post.UE_status');
		
        if (I('post.UE_password') <> '') {
            $data['UE_password'] = md5(I('post.UE_password'));
        }
        if (I('post.UE_secpwd') <> '') {
            $data['UE_secpwd'] = md5(I('post.UE_secpwd'));
        }
        $data['zfb'] = authcode(I('post.zfb'),'ENCODE',C('base64_code_pwd'),0);
        $data['yhzh'] = authcode(I('post.yhzh'),'ENCODE',C('base64_code_pwd'),0);
        $data['yhmc'] = authcode(I('post.yhmc'),'ENCODE',C('base64_code_pwd'),0);
        $data['UE_phone'] = I('post.UE_phone');
		$data['weixin'] = authcode(I('post.weixin'),'ENCODE',C('base64_code_pwd'),0);
		$data['yzf'] = authcode(I('post.yzf'),'ENCODE',C('base64_code_pwd'),0);

		$user = M('user')->where(array('UE_account' => I('post.UE_account')))->find();//0601
		if($user['ue_check'] == 0 && I('post.UE_check') == '1')
			$data['jihuo_time'] = date('Y-m-d H:i:s', time());

        if (M('user')->where(array('UE_account' => I('post.UE_account')))->save($data)) {
            $this->success('修改成功!'); 
        } else {
            $this->success('修改失败!');
        }
    }
    public function tixiancl()
    {
        $data['id'] = I('post.id');
        $data['status'] = I('post.status');

        if (M('tixian')->where(array('id' => I('post.id')))->save($data)) {
            $this->success('修改成功!');
        } else {
            $this->success('修改失败!');
        }
    }

    public function admincl()
    {


        $data['MB_right'] = I('post.MB_right');
        if (I('post.MB_userpwd') <> '') {
            $data['MB_userpwd'] = md5(I('post.MB_userpwd'));
        }
        //dump($data);die;
        if (M('member')->where(array('MB_username' => I('post.MB_username')))->save($data)) {
            $this->success('修改成功!', '/Yshclbssb.php/Home/Index/adminlist');
        } else {
            $this->success('修改失败!');
        }
    }


    public function adminadd()
    {


        $data['MB_username'] = I('post.MB_username');
        $data['MB_right'] = I('post.MB_right');
        $data['MB_userpwd'] = md5(I('post.MB_userpwd'));
        if (I('post.MB_username') <> '' && I('post.MB_right') <> '' && I('post.MB_userpwd') <> '') {
            //dump($data);die;
            if (M('member')->add($data)) {
                $this->success('添加成功!', '/Yshclbssb.php/Home/Index/adminlist');
            } else {
                $this->success('添加失败!');
            }
        } else {
            $this->success('数据不能为空!');
        }
    }


    public function txlist()
    {


        $User = M('tixian'); // 實例化User對象
        $data = I('post.user');
      
        //$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));

        if ($data <> '') {
            $map['UE_account'] = $data;
			$this->assign('data', $data);
        }
        if (I('get.ip') <> '') {
            $map['UE_regIP'] = I('get.ip');
        }
        $count = $User->where($map)->count(); // 查詢滿足要求的總記錄數
        //$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

        $p = getpage($count, 20);

        $list = $User->where($map)->order('id')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('list', $list); // 賦值數據集
        $this->assign('page', $p->show()); // 賦值分頁輸出


        $this->display('Index/txlist');
    }

    public function userlist()
    {
      
        $User = M('user'); // 實例化User對象
        $data = I('post.user');

        $all = "-1";
		$this->assign('cz',  '-1');
		$this->assign('jh',  '-1');
		$all = I('get.all');

		$ue_status = I('get.ue_status');

		$ue_check = I('get.ue_check');

        $start = I('post.start');
        $end = I('post.end');

        if(!empty($start)&&!empty($end))
        {
            $map['UE_regTime'] = array('between',array($start,$end));
			$this->assign('start', $start);
			$this->assign('end', $end);
			
			$_SESSION['start'] = $start;
			$_SESSION['end'] = $end;
        }
        
		if ($ue_status <> '') {
			$map['UE_status'] = $ue_status;
			$this->assign('cz',  $ue_status);
        }

		if ($ue_check <> '') {
			$map['UE_check'] = $ue_check;
			$this->assign('jh',  $ue_check);
        }

		if ($ue_status == '' && $ue_check == '') {
			$this->assign('all', -1);
        }


        if ($data <> '') {
            $map['UE_account|UE_theme'] = array('like',"%$data%",'or');
			$this->assign('data', $data);
        }
        if (I('get.ip') <> '') {
            $map['UE_regIP'] = I('get.ip');
        }
        $count = $User->where($map)->count(); // 查詢滿足要求的總記錄數

        $p = getpage($count, 20);

        $list = $User->where($map)->order('UE_ID')->limit($p->firstRow, $p->listRows)->select();

        foreach ($list as $key => $value) 
        {
           $user_jsbz= M('jsbz')->where(array('qr_zt'=>'1','user'=>$value['ue_account']))->order('date desc')->find();

           //echo  M('jsbz')->where(array('qr_zt'=>'1','user'=>$value['ue_account']))->order('date desc')->getlastSql();
           $user_tgbz= M('tgbz')->where(array('user'=>$value['ue_account']))->order('date desc')->find();
                $time_jsbz = strtotime($user_jsbz['date']);//提现时间
               // $time_tgbz = strtotime($user_tgbz['date']);//提供帮助时间
                $middletime = C('tx_lastday')*24*60*60;  //系统设置时间
                $now=time();
              
               if($user_tgbz == null && $user_jsbz != null){
                     if($now > ($time_jsbz+$middletime)){ 
                          //M('user')->where(array('UE_account' => $value['ue_account']))->save(array('UE_status' => '1'));
        // $info = sendSMS($value['ue_accname'],"您的下线".$value['UE_theme'].$value['ue_account']."在提现后".C('tx_lastday')."未排单，已被冻结账号 ".date("y-m-d H:i:s",time()));                    
                     }
               }
               
               
        }

        $this->assign('list', $list); // 賦值數據集
        $this->assign('page', $p->show()); // 賦值分頁輸出

        $starttime = date('Y-m-d 00:00:01', time());
        $endtime = date('Y-m-d 23:59:59', time());
        $paidan_jb = M("tgbz")->where("date>='$starttime' and date<='$endtime'")->sum('jb');
        $this->paidan_jb = $paidan_jb ? $paidan_jb : 0;
        $reg_num = M('user')->where("UE_regTime>='$starttime' and UE_regTime<='$endtime'")->count();
        $this->reg_num = $reg_num ? $reg_num : 0;
        $jieshou_jb = M('jsbz')->where("date>='$starttime' and date<='$endtime'")->sum('jb');
        $this->jieshou_jb = $jieshou_jb ? $jieshou_jb : 0;

        $this->display();
    }

    public function adminlist()
    {


        $User = M('member'); // 實例化User對象
        $data = I('post.user');
		$map['MB_userGroup']=array('EQ',0);
		$listcount=$User->where($map)->field('id')->count();


        //$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));

        if ($data <> '') {
            $map['MB_username'] = $data;
        }
        $map['MB_userGroup'] = 0;
        $count = $User->where($map)->count(); // 查詢滿足要求的總記錄數
      
        //$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

        $p = getpage($count, 20);

        $list = $User->where($map)->order('MB_ID')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('list', $list); // 賦值數據集
        $this->assign('page', $p->show()); // 賦值分頁輸出


        $this->display('Index/adminlist');
    }


    public function userdel()
    {


        $User = M('user'); // 實例化User對象
        $data = I('get.id');


        $userxx = M('user')->where(array('UE_ID' => $data))->find();

        if ($data <> '' && $userxx['ue_account'] <> '') {
            M('user')->where(array('UE_ID' => $data))->delete();
            $this->success('删除成功!');
        } else {
            $this->success('公司账号不能删除!');
        }


    }

    public function ppdd_list_del()
    {


        $User = M('user'); // 實例化User對象
        $data = I('get.id');


        $userxx = M('ppdd')->where(array('id' => $data))->find();

        if ($data <> '' && $userxx['id'] <> '') {
            M('ppdd')->where(array('id' => $data))->delete();
            M('tgbz')->where(array('id' => $userxx['p_id']))->delete();
            M('jsbz')->where(array('id' => $userxx['g_id']))->delete();
            $this->success('删除成功!');
        } else {
            $this->success('订单不存在!');
        }


    }

    public function tgbz_list_del()
    {


        $User = M('user'); // 實例化User對象
        $data = I('get.id');


        $userxx = M('tgbz')->where(array('id' => $data))->find();

        if ($data <> '' && $userxx['id'] <> '') {

            M('tgbz')->where(array('id' => $userxx['id']))->delete();

            $this->success('删除成功!');
        } else {
            $this->success('订单不存在!');
        }


    }

    public function jsbz_list_del()
    {


        $User = M('user'); // 實例化User對象
        $data = I('get.id');


        $userxx = M('jsbz')->where(array('id' => $data))->find();

        if ($data <> '' && $userxx['id'] <> '') {

            M('jsbz')->where(array('id' => $userxx['id']))->delete();

            $this->success('删除成功!');
        } else {
            $this->success('订单不存在!');
        }


    }

    public function admindel()
    {


        $User = M('member'); // 實例化User對象
        $data = I('get.id');


        $userxx = M('member')->where(array('MB_ID' => $data))->find();

        if ($data <> '' && $userxx['mb_username'] <> '') {
            M('member')->where(array('MB_ID' => $data))->delete();
            $this->success('删除成功!', '/Yshclbssb.php/Home/Index/adminlist');
        } else {
            $this->success('不能删除!');
        }


    }


    public function usermb()
    {


        $User = M('user'); // 實例化User對象
        $data = I('get.id');


        $userxx = M('user')->where(array('UE_ID' => $data))->find();

        if ($data <> '' && $userxx['ue_account'] <> '') {
            if (M('user')->where(array('UE_ID' => $data))->save(array('UE_question' => '', 'UE_question2' => '', 'UE_question3' => '', 'UE_answer' => '', 'UE_answer2' => '', 'UE_answer3' => ''))) {
                $this->success('成功!');
            } else {
                $this->success('失败!');
            }
        } else {
            $this->success('用户不存在!');
        }


    }


    public function userbtc()
    {


        $User = M('user'); // 實例化User對象
        $data = I('get.cz');


        if ($data == 'n') {
            $map['btbdz'] = '0';
        } elseif ($data == 'y') {
            $map['btbdz'] = array('neq', '0');
        }
        $count = $User->where($map)->count(); // 查詢滿足要求的總記錄數
        //$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

        $p = getpage($count, 20);

        $list = $User->where($map)->order('UE_ID')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('list', $list); // 賦值數據集
        $this->assign('page', $p->show()); // 賦值分頁輸出


        $this->display('Index/userbtc');
    }


    public function rggl()
    {


        $User = M('userjyinfo'); // 實例化User對象
        $data = I('get.cz');


        if ($data == 'n') {
            $map['UJ_jbmcStage'] = '0';
        } elseif ($data == 'y') {
            $map['UJ_jbmcStage'] = '1';
        }
        $map['UJ_dataType'] = 'rg';

        $count = $User->where($map)->count(); // 查詢滿足要求的總記錄數
        //$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

        $p = getpage($count, 20);

        $list = $User->where($map)->order('UJ_ID')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('list', $list); // 賦值數據集
        $this->assign('page', $p->show()); // 賦值分頁輸出


        $this->display('Index/rggl');
    }

    public function rggldel()
    {


        $data = I('get.id');


        if ($data <> '') {
            if (M('userjyinfo')->where(array('UJ_ID' => $data))->delete()) {
                $this->success('删除成功');
            } else {
                $this->success('删除失败');
            }
        }
    }


    public function rgglsh()
    {


        $data = I('get.id');


        if ($data <> '') {

            $ddxx = M('userjyinfo')->where(array('UJ_ID' => $data))->find();

            if ($ddxx['uj_style'] == 'rgzsb') {

                M('user')->where(array('UE_account' => $ddxx['uj_usercount']))->setInc('zsbhe', $ddxx['uj_jbcount']);
                $userxx = M('user')->where(array('UE_account' => $ddxx['uj_usercount']))->find();
                $note3 = "原始鑽石幣購買";
                $record3 ["UG_account"] = $ddxx['uj_usercount']; // 登入轉出賬戶
                $record3 ["UG_type"] = 'zsb';
                $record3 ["zsb"] = $ddxx['uj_jbcount']; // 金幣
                $record3 ["zsb1"] = $ddxx['uj_jbcount']; //
                $record3 ["zsbhe"] = $userxx['zsbhe']; // 當前推薦人的金幣餘額
                $record3 ["UG_dataType"] = 'rg'; // 金幣轉出
                $record3 ["UG_note"] = $note3; // 推薦獎說明
                $record3["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作時間
                $reg4 = M('userget')->add($record3);
                M('userjyinfo')->where(array('UJ_ID' => $data))->save(array('UJ_jbmcStage' => '1'));
                $this->success('处理成功');

            } elseif ($ddxx['uj_style'] == 'rgyb') {


                M('user')->where(array('UE_account' => $ddxx['uj_usercount']))->setInc('ybhe', $ddxx['uj_jbcount']);
                $userxx = M('user')->where(array('UE_account' => $ddxx['uj_usercount']))->find();
                $note3 = "原始银幣購買";
                $record3 ["UG_account"] = $ddxx['uj_usercount']; // 登入轉出賬戶
                $record3 ["UG_type"] = 'yb';
                $record3 ["yb"] = $ddxx['uj_jbcount']; // 金幣
                $record3 ["yb1"] = $ddxx['uj_jbcount']; //
                $record3 ["ybhe"] = $userxx['ybhe']; // 當前推薦人的金幣餘額
                $record3 ["UG_dataType"] = 'rg'; // 金幣轉出
                $record3 ["UG_note"] = $note3; // 推薦獎說明
                $record3["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作時間
                $reg4 = M('userget')->add($record3);
                M('userjyinfo')->where(array('UJ_ID' => $data))->save(array('UJ_jbmcStage' => '1'));
                $this->success('处理成功');


            }


        }
    }


    public function jbzs()
    {
        $User = M('userget');

        $map['UG_dataType'] = 'xtzs';

        $count = $User->where($map)->count(); 

        $p = getpage($count, 20);

        $list = $User->where($map)->order('UG_ID DESC')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('list', $list); 
        $this->assign('page', $p->show());


        $this->display('Index/jbzs');
    }



    public function userbtccl()
    {


        $User = M('user'); 
        if (I('post.UE_ID') <> '' && I('post.btbdz') <> '0') {
            if ($User->where(array('UE_ID' => I('post.UE_ID')))->save(array('btbdz' => I('post.btbdz')))) {
                $this->success('修改成功!');
            } else {
                $this->success('修改失败!');
            }
        } else {
            $this->success('您没修改内容!');
        }

    }


    public function jbzscl()
    {
        $User = M('user'); 
        if (I('post.lx') == 'bx')
		{
            if (I('post.sl') <> '' && $User->where(array('UE_account' => I('post.user')))->find() <> 0 && preg_match('/^[0-9-]{1,20}$/', I('post.sl')))
			{
                $user_zq = M('user')->where(array('UE_account' => I('post.user')))->find();
                if ($User->where(array('UE_account' => I('post.user')))->setInc('UE_money', I('post.sl'))) 
				{
                    $userxx = M('user')->where(array('UE_account' => I('post.user')))->find();
                    $note3 = "系统操作";
                    $record3 ["UG_account"] = I('post.user');
                    $record3 ["UG_type"] = 'jb';
                    $record3 ["UG_money"] = I('post.sl');
                    $record3 ["UG_allGet"] = $user_zq['ue_money'];
                    $record3 ["UG_balance"] = $userxx['ue_money']; 
                    $record3 ["UG_dataType"] = 'xtzs';
                    $record3 ["UG_note"] = $note3;
                    $record3["UG_getTime"] = date('Y-m-d H:i:s', time());
                    $reg4 = M('userget')->add($record3);


                    $this->success('赠送成功!');
                } else {
                    $this->success('赠送失败!');
                }
            } else {
                $this->success('用户 名不存在或填写有误!');
            }


        } elseif (I('post.lx') == 'ldj') 
		{
            if (I('post.sl') <> '' && $User->where(array('UE_account' => I('post.user')))->find() <> 0 && preg_match('/^[0-9-]{1,20}$/', I('post.sl')))
			{
                if ($User->where(array('UE_account' => I('post.user')))->setInc('qwe', I('post.sl'))) 
				{
					$User->where(array('UE_account' => I('post.user')))->setInc('jl_he', I('post.sl'));
                    $userxx = M('user')->where(array('UE_account' => I('post.user')))->find();
                    $note3 = "系统赠送";
                    $record3 ["UG_account"] = I('post.user');
                    $record3 ["UG_type"] = 'jb';
                    $record3 ["yb"] = I('post.sl'); 
                    $record3 ["yb1"] = I('post.sl');
                    $record3 ["ybhe"] = $userxx['ybhe'];
                    $record3 ["UG_dataType"] = 'xtzsldj';
                    $record3 ["UG_note"] = $note3;
                    $record3["UG_getTime"] = date('Y-m-d H:i:s', time());
                    $reg4 = M('userget')->add($record3);


                    $this->success('赠送成功!');
                } else {
                    $this->success('赠送失败!');
                }
            } else {
                $this->success('用户 名不存在或填写有误!');
            }
        } elseif (I('post.lx') == 'zsb') {
            if (I('post.sl') <> '' && $User->where(array('UE_account' => I('post.user')))->find() <> 0 && preg_match('/^[0-9-]{1,20}$/', I('post.sl'))) {
                if ($User->where(array('UE_account' => I('post.user')))->setInc('zsbhe', I('post.sl'))) {
                    $userxx = M('user')->where(array('UE_account' => I('post.user')))->find();
                    $note3 = "系统赠送";
                    $record3 ["UG_account"] = I('post.user'); // 登入轉出賬戶
                    $record3 ["UG_type"] = 'zsb';
                    $record3 ["zsb"] = I('post.sl'); // 金幣
                    $record3 ["zsb1"] = I('post.sl'); //
                    $record3 ["zsbhe"] = $userxx['zsbhe']; // 當前推薦人的金幣餘額
                    $record3 ["UG_dataType"] = 'xtzs'; // 金幣轉出
                    $record3 ["UG_note"] = $note3; // 推薦獎說明
                    $record3["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作時間
                    $reg4 = M('userget')->add($record3);


                    $this->success('钻石币赠送成功!');
                } else {
                    $this->success('钻石币赠送失败!');
                }
            } else {
                $this->success('用户 名不存在或填写有误!');
            }
        }

    }

    public function tj_zrjj_cl()
    {
        header("Content-Type:text/html; charset=utf-8");

        if (IS_POST) {


            //时间
            $NowTime = date('Y-m-d H:i:s', time());

            $gxTime = $NowTime;//每日分紅的時間
            //echo $gxTime;

            $year = date("Y");
            $month = date("m");
            $day = date("d");

            $dayBegin = mktime(0, 0, 0, $month, $day, $year);//當天開始時間戳
            $dayEnd = mktime(23, 59, 59, $month, $day, $year);//當天結束時間戳

            $startTime = date('Y-m-d H:i:s', $dayBegin);
            $endTime = date('Y-m-d H:i:s', $dayEnd);

            $startTimed = date('Y-m-d H:i:s', $dayBegin);
            $endTimed = date('Y-m-d H:i:s', $dayEnd);


            //时间


            //昨天开始

            $year = date("Y");
            $month = date("m");
            $day = date("d");

            $dayBegin = mktime(0, 0, 0, $month, $day, $year) - 86400;//當天開始時間戳
            $dayEnd = mktime(23, 59, 59, $month, $day, $year) - 86400;//當天結束時間戳

            $startTime = date('Y-m-d H:i:s', $dayBegin);
            $endTime = date('Y-m-d H:i:s', $dayEnd);

            $startTimed = date('Y-m-d H:i:s', $dayBegin);
            $endTimed = date('Y-m-d H:i:s', $dayEnd);

            //echo $startTimed."<br>";
            //echo $endTimed."<br>";die;


            //昨天结束
            $otsystem = M('system')->where("SYS_ID ='1'")->find();

            $res = M('user')->where("UE_check ='1' and UE_activeTime > '" . $startTimed . "' and UE_activeTime < '" . $endTimed . "'")->select();

            //dump($otsystem);die; echo $val['ue_accname'];
            $tjj_tj = 0;
            $tjj1_tj = 0;
            $tjj2_tj = 0;
            $bdj_tj = 0;
            foreach ($res as $val) {
                if ($val['ue_accname'] <> '') {
                    $tjr_1 = M('user')->where("UE_account='" . $val['ue_accname'] . "'")->find();
                    $tjr_1_he = $tjr_1['ue_money'] + $otsystem['a_kd_zsb'] * 2 * $otsystem['a_ztj'];
                    M('user')->where("UE_account='" . $tjr_1['ue_account'] . "'")->save(array('UE_money' => $tjr_1_he));


                    $record3 ["UG_account"] = $tjr_1['ue_account'];
                    $record3 ["UG_type"] = 'jb';
                    $record3 ["UG_money"] = $otsystem['a_kd_zsb'] * 2 * $otsystem['a_ztj'];
                    $record3 ["UG_allGet"] = $otsystem['a_kd_zsb'] * 2 * $otsystem['a_ztj'];
                    $record3 ["UG_balance"] = $tjr_1_he;
                    $record3 ["UG_dataType"] = 'tjj1';
                    $record3 ["UG_note"] = '推荐奖';
                    $record3["UG_getTime"] = date('Y-m-d H:i:s', time());
                    M('userget')->add($record3);

                    $tjj_tj = $tjj_tj + 1;


                    if ($tjr_1['ue_accname'] <> '') {

                        $tjr_2 = M('user')->where("UE_account='" . $tjr_1['ue_accname'] . "'")->find();
                        $tjr_2_he = $tjr_2['ybhe'] + $otsystem['a_kd_zsb'] * 2 * $otsystem['a_ztj2'];
                        M('user')->where("UE_account='" . $tjr_2['ue_account'] . "'")->save(array('ybhe' => $tjr_2_he));


                        $record3 ["UG_account"] = $tjr_2['ue_account'];
                        $record3 ["UG_type"] = 'yb';
                        $record3 ["yb"] = $otsystem['a_kd_zsb'] * 2 * $otsystem['a_ztj2'];
                        $record3 ["yb1"] = $otsystem['a_kd_zsb'] * 2 * $otsystem['a_ztj2'];
                        $record3 ["ybhe"] = $tjr_2_he;
                        $record3 ["UG_dataType"] = 'tjj2';
                        $record3 ["UG_note"] = '间推奖';
                        $record3["UG_getTime"] = date('Y-m-d H:i:s', time());
                        M('userget')->add($record3);

                        $tjj1_tj = $tjj1_tj + 1;

                        if ($tjr_2['ue_accname'] <> '') {

                            $tjr_3 = M('user')->where("UE_account='" . $tjr_2['ue_accname'] . "'")->find();
                            $tjr_3_he = $tjr_3['ybhe'] + $otsystem['a_kd_zsb'] * 2 * $otsystem['a_ztj3'];
                            M('user')->where("UE_account='" . $tjr_3['ue_account'] . "'")->save(array('ybhe' => $tjr_3_he));


                            $record3 ["UG_account"] = $tjr_3['ue_account'];
                            $record3 ["UG_type"] = 'yb';
                            $record3 ["yb"] = $otsystem['a_kd_zsb'] * 2 * $otsystem['a_ztj3'];
                            $record3 ["yb1"] = $otsystem['a_kd_zsb'] * 2 * $otsystem['a_ztj3'];
                            $record3 ["ybhe"] = $tjr_3_he;
                            $record3 ["UG_dataType"] = 'tjj3';
                            $record3 ["UG_note"] = '间间推奖';
                            $record3["UG_getTime"] = date('Y-m-d H:i:s', time());
                            M('userget')->add($record3);

                            $tjj2_tj = $tjj2_tj + 1;

                        }


                    }


                    dump($tjr_1_he);
                    die;
                }

            }


//      set_time_limit(10);    
//  ob_end_clean();     //在循环输出前，要关闭输出缓冲区   

//  echo str_pad('',1024);   
//  //浏览器在接受输出一定长度内容之前不会显示缓冲输出，这个长度值 IE是256，火狐是1024   
//  for($i=1;$i<=100;$i++){    
//   echo $i.'<br/>';    
//   flush();    //刷新输出缓冲   

//  }    


        }

    }


    public function pin_add()
    {
        $this->display('Index/pin_add');
    }
    public function pin_add_cl()
    {

        if (IS_POST) {
            $data_P = I('post.');
            $user = M('user')->where(array(UE_account => $data_P['user']))->find();

            if (!$user) {
                $this->error('用户 不存在！');
            } elseif (abs($data_P ['sl']) == 0) {
                $this->error('请填生成数量！');
            } else
			{
				M('user')->where(array(UE_account => $data_P['user']))->setInc('jhmnum', $data_P ['sl']);
				$map['user'] = $data_P['user'];
				$map['type']= 'cz';
                $map['info']= '充值买入';
                $map['num']= $data_P ['sl'];
			    $map['yue']= get_userinfo($data_P['user'],'jhmnum');
                $map['date']= date('Y-m-d H:i:s', time());
                M('jhm_log')->add($map);
				$this->success('成功充值激活码' . $data_P ['sl'] . '个');
            }
        }
    }

    public function pin_list()
    {
        $jhm_log = M('jhm_log'); // 實例化User對象
        $data = I('post.user');

        $jhm_log->count();
        if ($data <> '') {
            $map['user'] = $data;
        }
        $count = $jhm_log->where($map)->count(); 

        $p = getpage($count, 20);

        $list = $jhm_log->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('list', $list); 
        $this->assign('page', $p->show()); 




        $this->display('Index/pin_list');
    }

    public function pin_del()
    {


        $User = M('user'); // 實例化User對象
        $data = I('get.id');


        if (M('pin')->where(array('id' => $data))->delete()) {
            $this->success('删除成功!');
        } else {
            $this->success('删除失败!');
        }


    }

    public function paidan_del()
    {


        $User = M('user'); // 實例化User對象
        $data = I('get.id');


        if (M('paidan')->where(array('id' => $data))->delete()) {
            $this->success('删除成功!');
        } else {
            $this->success('删除失败!');
        }


    }


     public function paidan_add()
    {


        $this->display('Index/paidan_add');
    }


    public function paidan_add_cl()
    {
        if (IS_POST) {
            $data_P = I('post.');
            $user = M('user')->where(array(UE_account => $data_P['user']))->find();

            if (!$user) {
                $this->error('用户 不存在！');
            } elseif (abs($data_P ['sl']) == 0) {
                $this->error('请填生成数量.！');
            } else 
			{
				M('user')->where(array(UE_account => $data_P['user']))->setInc('pdmnum', $data_P ['sl']);
				$map['user'] = $data_P['user'];
				$map['type']= 'cz';
                $map['info']= '充值买入';
                $map['num']= $data_P ['sl'];
			    $map['yue']= get_userinfo($data_P['user'],'pdmnum');
                $map['date']= date('Y-m-d H:i:s', time());
                M('paidan_log')->add($map);
				$this->success('成功充值排单码' . $data_P ['sl'] . '个');
				/*
                $cgsl = 0;
				if($data_P ['sl'] > 0)
				{
                  for ($i = 0; $i < $data_P ['sl']; $i++) {
                    $paidan = md5(sprintf("%0" . strlen(9) . "d", mt_rand(0, 99999999999)));
                    //$pin=0;
                    if (!M('paidan')->where(array('paidan' => $paidan))->find()) {
                        $data['user'] = $data_P['user'];
                        $data['paidan'] = $paidan;
                        $data['zt'] = 0;
                        $data['sc_date'] = date('Y-m-d H:i:s', time());
                        if (M('paidan')->add($data)) {
                            $cgsl++;
                        }
                    }
                   }
				   $this->success('成功添加排单码' . $cgsl . '个');
				}
				if($data_P ['sl'] < 0)
				{
					$data['user'] = $data_P['user'];
                    $data['zt'] = 0;
					M('paidan')->where($data)->order('sc_date')->limit(abs($data_P ['sl']))->delete(); 
					$cgsl = abs($data_P ['sl']);
					$this->success('成功删除排单码' . $cgsl . '个');
				}
				*/               
            }
        }
    }


    public function paidan_list()
    {
        $jhm_log = M('paidan_log'); // 實例化User對象
        $data = I('post.user');

        $jhm_log->count();
        if ($data <> '') {
            $map['user'] = $data;
        }
        $count = $jhm_log->where($map)->count(); 

        $p = getpage($count, 20);

        $list = $jhm_log->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('list', $list); 
        $this->assign('page', $p->show()); 


        $this->display('Index/paidan_list');
    }


    public function tgbz_list()
    {
        $tgbz = M('tgbz'); // 實例化User對象
        $data = I('user');
        $start = urldecode(I('start'));
        $end = urldecode(I('end'));
        $this->z_jgbz = $tgbz->sum('jb');
        $this->z_jgbz2 = $tgbz->where(array('zt' => '1'))->sum('jb');
        $this->z_jgbz3 = $tgbz->where(array('zt' => array('neq', 1)))->sum('jb');

        $this->assign ( 'priority', '-1' );
        $priority = I('priority');

		$this->assign ( 'isfast', '-1' );
        $isfast = I('isfast');

		$this->assign ( 'isprepay', '-1' );
        $isprepay = I('isprepay');

        $this->assign ( 'zt', '-1' );
        $zt = I('zt');

		$map['priority'] = array('in',array(0,1));

		$map['isfast'] = array('in',array(0,1));

		$map['isprepay'] = array('in',array(0,1));

		$map['zt'] = array('in',array(0)); //0,1,6

		if($priority <> '' && $priority <> '-1')
		{
			$where1['priority'] = $priority;
			if(empty($start)&&empty($end))
            {
			   $where1['_logic'] = 'and';
			   $map['_complex'] = $where1;
			}
			$this->assign ( 'priority', $priority );
		}

		if($isfast <> '' && $isfast <> '-1')
		{
			$where1['isfast'] = $isfast;
			if(empty($start)&&empty($end))
            {
			   $where1['_logic'] = 'and';
			   $map['_complex'] = $where1;
			}
			$this->assign ( 'isfast', $isfast );
		}

		if($isprepay <> '' && $isprepay <> '-1')
		{
			$where1['isprepay'] = $isprepay;
			if(empty($start)&&empty($end))
            {
			   $where1['_logic'] = 'and';
			   $map['_complex'] = $where1;
			}
			$this->assign ( 'isprepay', $isprepay );
		}

		if($zt <> '' && $zt <> '-1')
		{
			$where1['zt'] = $zt;
			if(empty($start)&&empty($end))
            {
			   $where1['_logic'] = 'and';
			   $map['_complex'] = $where1;
			}
			$this->assign ( 'zt', $zt );
		}
       

        if ($data <> '') {
            $map['user'] = $data;
			$this->assign ( 'user', $data );
        }
        if(!empty($start)&&!empty($end))
        {
			$where1['date'] = array('between',array($start,$end));
			$where1['_logic'] = 'and';
			$map['_complex'] = $where1;

			$s_sum = $tgbz->where ( $map )->sum('jb'); 
			$this->assign ( 's_sum', $s_sum );
			$this->assign ( 'start', $start );
			$this->assign ( 'end', $end );
			$_SESSION['start'] = $start;
			$_SESSION['end'] = $end;
        }
       
        $zdpp_confirm = I('get.zdpp_confirm');
		$zdpp_confirm_ckb = I('get.zdpp_confirm_ckb');

		if($zdpp_confirm == "1")
		{
			if($zdpp_confirm_ckb <> "1")
				$this->error('确定要自动匹配请在备份数据后勾选确认按钮','',5);
			else
			{
				 $pipeits = 0;
				 $tgbz_list = $tgbz->where($map)->order('date asc')->select();
				 foreach ($tgbz_list as $key => $val) 
				 {
					  //优先查询是否有不用拆分即可匹配的订单
				 	  $jsbz_list = get_eq_jb_jsbz($val['id']);
				 	  $num = count($jsbz_list);
					  if($num == 1)
				      {
                          if (ppdd_add($val['id'], $jsbz_list[0]['id'])) 
				          {
                              $pipeits++;
                              M('tgbz')->where(array('id' => $val['id']))->setInc('cf_ds', 1);
                          }
			          }else
			          {
				   	     //根据时间最早获取接受订单，不含金额相等的
            		     $jsbz_one = get_jsbz_one($val['id']);
						 if(count(jsbz_one) ==0)
						 {
							continue;
						 }
             		     if(jsbz_cf($val['jb'],$jsbz_one[0]))
			  		     {
						    if (ppdd_add($val['id'], $jsbz_one[0]['id'])) 
						    {
                      		   $pipeits++;
                     		    M('tgbz')->where(array('id' => $val['id']))->setInc('cf_ds', 1);
                  		    }
			   		     }else
						 {
							 exit;
						 }
					  }
       		     }
			 }
			 $this->success('符合条件的有'.count($tgbz_list).'笔,成功匹配了' . $pipeits  . '笔订单','/Yshclbssb.php/Home/Index/tgbz_list',5);
		}else
		{
			 $count = $tgbz->where($map)->count(); 
             $p = getpage($count, 20);
             $list = $tgbz->where($map)->order('date asc')->limit($p->firstRow, $p->listRows)->select();
			 $this->assign('list', $list); 
             $this->assign('page', $p->show()); 
			 $this->display('Index/tgbz_list');
		}
    }


    public function jsbz_list()
    {
        $User = M('jsbz'); // 實例化User對象
        $data = I('post.user');
        $start = I('post.start');
        $end = I('post.end');
        $this->z_jgbz = $User->sum('jb');
        $this->z_jgbz2 = $User->where(array('zt' => '1'))->sum('jb');
        $this->z_jgbz3 = $User->where(array('zt' => array('neq', '1')))->sum('jb');
        //$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));

        $map['zt'] = 0;

         if (I('get.cz') == '-1') {
            $map['zt'] = array(in,array(0,1,6,2,9));
        }
        if (I('get.cz') == 1) {
            $map['zt'] = 1;
        }
		if (I('get.cz') == 6) {
            $map['zt'] = 6;
        }
        if (I('get.cz') == 2) {
            $map['cz'] = 2;
        }
        if (I('get.cz') == 9) {
            $map['zt'] = 9;
        }

		$this->assign ( 'cz', I('get.cz'));


        if ($data <> '') {
            $map['user'] = $data;
			$this->assign ( 'user', $data );
        }
        if(!empty($start)&&!empty($end))
        {
            $map['date'] = array('between',array($start,$end));
			$s_sum = $User->where ( $map )->sum('jb'); 
			$this->assign ( 's_sum', $s_sum );
			$_SESSION['start'] = $start;
			$_SESSION['end'] = $end;
        }
        $count = $User->where($map)->count(); // 查詢滿足要求的總記錄數

        $p = getpage($count, 20);

        $list = $User->where($map)->order('date ')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('list', $list); // 賦值數據集
        $this->assign('page', $p->show()); // 賦值分頁輸出


        $this->display('Index/jsbz_list');
    }


    public function ppdd_list()
    {
        $ppdd = M('ppdd');
        $user = I('post.user');
		$start = I('post.start');
        $end = I('post.end');

        if ($user <> '') {
            $map['p_user|g_user|pporderid'] = $user;
			$this->assign ( 'user', $user );
        }

		$map['zt'] = array('neq', 2);
		$this->assign ( 'cz', 2);

        if (I('cz') == 1) {
            $map['zt'] = array('eq', 2);
			$this->assign ( 'cz', 1);
        }

		if (I('cz') == 0) {
            $map['zt'] = array('eq', 0);
			$this->assign ( 'cz', 0);
        }
		
        if (I('cz') == '-1') {
            $map['zt'] = array(in, array(1,2));
			$this->assign ( 'cz', -1);
        }

		$s_sum = $ppdd->where ( $map )->sum('jb');
		$this->assign ( 's_sum', $s_sum );

		if(!empty($start)&&!empty($end))
        {
            $map['date'] = array('between',array($start,$end));
			$s_sum = $ppdd->where ( $map )->sum('jb'); 
			$this->assign ( 's_sum', $s_sum );
			$_SESSION['start'] = $start;
			$_SESSION['end'] = $end;
        }

        $count = $ppdd->where($map)->count(); 

        
        $p = getpage($count, 20);
         
        $list = $ppdd->where($map)->order('date ')->limit( $p->firstRow, $p->listRows)->select();
        $this->assign('list', $list); 
        $this->assign('page', $p->show());
        
        $this->display('Index/ppdd_list');
    }


    public function ts1_list()
    {


        $User = M('ppdd'); // 實例化User對象
        $data = I('post.user');


        $map['zt'] = array('neq', 2);
        $map['ts_zt'] = array('eq', 1);;


        $count = $User->where($map)->count(); // 查詢滿足要求的總記錄數
        //$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

        $p = getpage($count, 20);

        $list = $User->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
		//echo $User->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->getlastSql();
        
		foreach($list as $k =>$v){
		        $user = M('user')->where(array('UE_account'=>$v['g_user']))->find();
				$list[$k]['ue_account']=$user['ue_account'];
				$list[$k]['ue_password']=$user['ue_password'];
				$list[$k]['secpwd'] = $user['secpwd'];
		}
		//dump($list);die;
        $this->assign('list', $list); // 賦值數據集
        $this->assign('page', $p->show()); // 賦值分頁輸出

        $this->assign ( 'jjdktime', C("jjdktime") );
        $this->display('Index/ts1_list');
    }


    public function ts2_list()
    {


        $User = M('ppdd'); // 實例化User對象
        $data = I('post.user');


        $map['zt'] = array('neq', 2);
        $map['ts_zt'] = array('eq', 2);;


        $count = $User->where($map)->count(); // 查詢滿足要求的總記錄數
        //$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

        $p = getpage($count, 20);

        $list = $User->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
        //dump($list);die;
        $this->assign('list', $list); // 賦值數據集
        $this->assign('page', $p->show()); // 賦值分頁輸出


        $this->display('Index/ts2_list');
    }

    public function ts3_list()
    {


        $User = M('ppdd'); // 實例化User對象
        $data = I('post.user');


        $map['zt'] = array('neq', 2);
        $map['ts_zt'] = array('eq', 3);;


        $count = $User->where($map)->count(); // 查詢滿足要求的總記錄數
        //$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

        $p = getpage($count, 20);

        $list = $User->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
        //dump($list);die;
		
        $this->assign('list', $list); // 賦值數據集
        $this->assign('page', $p->show()); // 賦值分頁輸出


        $this->display('Index/ts3_list');
    }


    public function ts1_list_cl()
    {

        $ppddxx = M('ppdd')->where(array('id' => I('get.id')))->find();
        M('tgbz')->where(array('id' => $ppddxx['p_id']))->save(array('zt' => 0, 'qr_zt' => 0));
        M('jsbz')->where(array('id' => $ppddxx['g_id']))->save(array('zt' => 0, 'qr_zt' => 0));
        M('ppdd')->where(array('id' => I('get.id')))->delete();
        $this->success('重新匹配成功');
    }


    public function ts3_list_cl()
    {

        $ppddxx = M('ppdd')->where(array('id' => I('get.id')))->find();
        M('tgbz')->where(array('id' => $ppddxx['p_id']))->save(array('zt' => 0, 'qr_zt' => 0));
        M('jsbz')->where(array('id' => $ppddxx['g_id']))->save(array('zt' => 0, 'qr_zt' => 0));
        M('ppdd')->where(array('id' => I('get.id')))->delete();
        M('user_jj')->where(array('r_id' => $ppddxx['id']))->delete();
        M('user_jl')->where(array('r_id' => $ppddxx['id']))->delete();
        $this->success('重新匹配成功');
    }


    public function ts2_list_cl()
    {

        $ppddxx = M('ppdd')->where(array('id' => I('get.id')))->find();
        M('tgbz')->where(array('id' => $ppddxx['p_id']))->save(array('zt' => 0, 'qr_zt' => 0));
        M('jsbz')->where(array('id' => $ppddxx['g_id']))->save(array('zt' => 0, 'qr_zt' => 0));
        M('ppdd')->where(array('id' => I('get.id')))->delete();
        $this->success('重新匹配成功');
    }


    public function tgbz_list_sd()
    {   

        if (I('get.id') <> '') {
           
            $tgbzuser = M('tgbz')->where(array('id' => I('get.id')))->find();
            
			//there should be a successful withdraw since last deposite
            $pre_tgbz = M('tgbz')->where(array('user' => $tgbzuser['user'],'zt'=>1,'date'=>array('lt',$tgbzuser['date'])))->find();
            if($pre_tgbz){
                $withdraw = M('jsbz')->where(array('user' => $tgbzuser['user'],'qr_zt'=>1,'qb'=>0,'date'=>array('gt',$tgbzuser['date'])))->find();
                //if(empty($withdraw)){
                    //die("<script>alert('该会员上次提供帮助成功后，还没有成功提现过!');history.back(-1);</script>");
               // }
            }
           
            $this->tgbzuser = $tgbzuser;
            if ($tgbzuser['zffs1'] == '1') {
                $zffs1 = '1';
            } else {
                $zffs1 = '5';
            }
            if ($tgbzuser['zffs2'] == '1') {
                $zffs2 = '1';
            } else {
                $zffs2 = '5';
            }
            if ($tgbzuser['zffs3'] == '1') {
                $zffs3 = '1';
            } else {
                $zffs3 = '5';
            }
            $User = M('jsbz'); // 實例化User對象
            $data = I('post.user');

             
            $where['zffs1'] = $zffs1;
            $where['zffs2'] = $zffs2;
            $where['zffs3'] = $zffs3;
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            $map['zt'] = 0;
            
            $count = $User->where($map)->count(); // 查詢滿足要求的總記錄數
            //echo  $User->where($map)->getlastSql();
            
            //$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

            $p = getpage($count, 20);

            $list = $User->where($map)->order('date ')->limit($p->firstRow, $p->listRows)->select();
            //echo  $User->where($map)->order('date ')->limit($p->firstRow, $p->listRows)->getlastSql();
             foreach ($list as $key => $value) {
                $ppdd= M('ppdd');
                $where1=array();
                $where1['p_user|g_user'] = $value['user'];
                $where1['zt'] =array('NEQ',2);
                $rs=$ppdd->where($where1)->find();
                $tixian=M('ppdd')->where(array("g_user"=>$value['user'],"zt"=>"2"))->select();
                if($tixian){
                    $list[$key]['tixian']='是'; 
                }else{
                    $list[$key]['tixian']='否'; 
                }
                   //echo $ppdd->where($where1)->getlastSql();
                
               
                if($rs)
                {   
                    if($rs['p_user'] ==$value['user']){
                       
                        $order = "未付款";
                    }elseif($rs['g_user'] ==$value['user']){
                      
                        $order = "未收款";
                    }
                         
                }else{
                    $order= "否";
                }
               
               $list[$key]['order']=$order;
            }
           
          
            $this->assign('list', $list); // 賦值數據集
            $this->assign('page', $p->show()); // 賦值分頁輸出


            $this->display('Index/tgbz_list_sd');
        }
    }


    public function jsbz_list_sd()
    {
        if (I('get.id') <> '') {       
            $tgbzuser = M('jsbz')->where(array('id' => I('get.id')))->find();
            $this->tgbzuser = $tgbzuser;
            if ($tgbzuser['zffs1'] == '1') {
                $zffs1 = '1';
            } else {
                $zffs1 = '5';
            }
            if ($tgbzuser['zffs2'] == '1') {
                $zffs2 = '1';
            } else {
                $zffs2 = '5';
            }
            if ($tgbzuser['zffs3'] == '1') {
                $zffs3 = '1';
            } else {
                $zffs3 = '5';
            }
            $tgbz = M('tgbz'); // 實例化User對象
            $data = I('post.user');


            $where['zffs1'] = $zffs1;
            $where['zffs2'] = $zffs2;
            $where['zffs3'] = $zffs3;
			$where['zffs1'] = 1;
            $where['zffs2'] = 1;
            $where['zffs3'] = 1;
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            $map['zt'] = 0;

            $count = $tgbz->where($map)->count();
            //echo $User->where($map)->getlastSql();
            //echo $count;
             // 查詢滿足要求的總記錄數
            //$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

            $p = getpage($count, 20);

            $list = $tgbz->where($map)->order('date ')->limit($p->firstRow, $p->listRows)->select();
            foreach ($list as $key => $value) {
                $ppdd= M('ppdd');
                $where1=array();
                $where1['p_user|g_user'] = $value['user'];
                $where1['zt'] =array('NEQ',2);
                $rs=$ppdd->where($where1)->find();
                $tixian=M('user_jj')->where(array("user"=>$value['user'],"zt"=>"1"))->select();
                if($tixian){
                    $list[$key]['tixian']='是'; 
                }else{
                    $list[$key]['tixian']='否'; 
                }
                if($rs)
                {   
                    if($rs['p_user'] ==$value['user']){
                       
                        $order = "未付款";
                    }elseif($rs['g_user'] ==$value['user']){
                      
                        $order = "未收款";
                    }
                         
                }else{
                    $order= "否";
                }
              
               $list[$key]['order']=$order;
            }
           
            
            $this->assign('list', $list); // 賦值數據集
            $this->assign('page', $p->show()); // 賦值分頁輸出


            $this->display('Index/jsbz_list_sd');
        }
    }

    //tgbz确认匹配
    public function tgbz_list_sd_cl()
    {
        $data = I('post.');
        $arr = explode(',', I('post.arrid'));
       
        $p_tgbz = M('tgbz')->where(array('id' => $data['pid']))->find();
        global $p_id2;
        $p_id2 = $data['pid'];
        if ($data['arrzs'] <> $data['jb'])
		{
            $this->error('匹配金额不等!');
        } else {
           $pipeits = 0;
           foreach ($arr as $val)
		   {
                $g_user = M('jsbz')->where(array('id' => $val))->find();  
				
                $where1=array();
                $where1['p_user|g_user'] = $g_user['user'];
                $where1['zt'] =array('NEQ',2);
                $rs=M('ppdd')->where($where1)->find();
                  
                if ($rs){
                   if($rs['p_user'] == $g_user['user']){
                          //die("<script>alert('还有未付款订单，不能匹配!');history.back(-1);</script>");
                   }else{
					   //拆分的情况不在校验范围
                       $rs_jsbz = M('jsbz')->where(array('id'=>$rs['g_id']))->find();
                        
                       if($rs_jsbz['date']<>$g_user['date']){
                           //die("<script>alert('还有未收款订单，不能匹配!');history.back(-1);</script>");
                       }
                   }
                }

                //如果是提供帮助和获得帮助的人都是自己
                if ($g_user['user'] == $p_tgbz['user']) {
                    $sfxd = '1';
                    break;
                } else {
                    $sfxd = '0';
                }
            }
            if ($sfxd == '0')
			{
                foreach ($arr as $val)
				{
                    if ($val <> '') 
					{
                        if (ppdd_add($p_id2, $val)) 
						{
                            $pipeits++;
                        }
                    }
                }
            } else
			{
                $usercf = '用户名重复';
            }
            if ($pipeits <> '0')
			{
                $tgbz = M('tgbz')->where(array('id' => $data['pid']))->find();
                $tj_ppdd = M('ppdd')->where(array('p_id' => $tgbz['id']))->select();
                $index = 0;
				$index_0_jb = 0;
				$index_0_pp_id = 0;
				$index_0_add_tgbzid = 0;
                foreach ($tj_ppdd as $value) 
				{
                    $data2['zffs1'] = $tgbz['zffs1'];
                    $data2['zffs2'] = $tgbz['zffs2'];
                    $data2['zffs3'] = $tgbz['zffs3'];
                    $data2['user'] = $tgbz['user'];
                    $data2['jb'] = $value['jb'];
                    $data2['user_nc'] = $tgbz['user_nc'];
                    $data2['user_tjr'] = $tgbz['user_tjr'];
                    $data2['date'] = $tgbz['date'];
                    $data2['zt'] = $tgbz['zt'];
					$data2['ppjb'] = $value['jb'];
					$data2['total'] = $value['jb'];
					$data2['priority'] = $tgbz['priority'];
					$data2['isprepay'] = $tgbz['isprepay'];
				    $data2['mainid'] = $tgbz['mainid'];
				    $data2['orderid'] = createorderid('P');
                    $data2['qr_zt'] = $tgbz['qr_zt'];
                    //添加数据了
                    $varid = M('tgbz')->add($data2);

                    M('ppdd')->where(array('id' => $value['id']))->save(array('p_id' => $varid));

                    if($index == 0)
					{
						$index_0_pp_id = $value['id'];
						$index_0_jb = $value['jb'];
						$index_0_add_tgbzid = $varid;
					}
					$index++;

                }

				if($tgbz['id'] <> $tgbz['mainid'])
				{
                    M('tgbz')->where(array('id' => $data['pid']))->delete();
					M('tgbz')->where(array('id' =>$tgbz['mainid']))->setInc('ppjb', $data['jb']);
				}else
				{
				    M('ppdd')->where(array('id' =>$index_0_pp_id))->save(array('p_id' => $tgbz['id']));
					M('tgbz')->where(array('id' =>$data['pid']))->save(array('jb' => $index_0_jb));
					M('tgbz')->where(array('id' =>$data['pid']))->setInc('ppjb', $data['jb']);
					M('tgbz')->where(array('id' => $index_0_add_tgbzid))->delete();
				}
                

            }
            $this->success('匹配成功!拆分成' . $pipeits . '条订单,' . $usercf . '!');
        }
    }

    public function jsbz_list_sd_cl()
    {
        $data = I('post.');
       
        $arr = explode(',', I('post.arrid'));
        $p_user = M('jsbz')->where(array('id' => $data['pid']))->find();
        global $p_id2;
        $p_id2 = $data['pid'];

        if ($data['arrzs'] <> $data['jb']) {
            $this->success('匹配金额不等!');
        } else {
            $pipeits = 0;
           
            foreach ($arr as $val) {
                $g_user = M('tgbz')->where(array('id' => $val))->find(); 
				
                $where1=array();
                $where1['p_user|g_user'] = $g_user['user'];
                $where1['zt'] =array('NEQ',2);
                $rs=M('ppdd')->where($where1)->find();
            
                if ($rs){
                   if($where1['p_user'] == $g_user['user']){
					   //拆分的情况不在校验范围
                       $rs_tgbz = M('tgbz')->where(array('id' => $rs['p_id']))->find();
                       if($rs_tgbz['date']<>$g_user['date']){
                           //die("<script>alert('还有未付款订单，不能匹配!');history.back(-1);</script>");
                       }
                   }else{
                       //die("<script>alert('还有未收款订单，不能匹配!');history.back(-1);</script>");
                   }
                }
				
                 if ($g_user['user'] == $p_user['user']) {
                    $sfxd = '1';
                    break;
                } else {
                    $sfxd = '0';
                }


            }

           if ($sfxd == '0') {
                foreach ($arr as $val) 
				{
                    if ($val <> '') {
                        if (ppdd_add2($val, $p_id2)) 
						{
                            $pipeits++;
                        }
                    }
                }
            } else {
                $usercf = '用户名重复';
            }

            //拆分充值
            if ($pipeits <> '0')
			{

                $jsbz = M('jsbz')->where(array('id' => $data['pid']))->find();
                $tj_ppdd = M('ppdd')->where(array('g_id' => $jsbz['id']))->select();

				$index = 0;
				$index_0_jb = 0;
				$index_0_pp_id = 0;
				$index_0_add_jsbzid = 0;

                foreach ($tj_ppdd as $value) 
				{                
                    $data2['zffs1'] = $jsbz['zffs1'];
                    $data2['zffs2'] = $jsbz['zffs2'];
                    $data2['zffs3'] = $jsbz['zffs3'];
                    $data2['user'] = $jsbz['user'];
                    $data2['jb'] = $value['jb'];
                    $data2['user_nc'] = $jsbz['user_nc'];
                    $data2['user_tjr'] = $jsbz['user_tjr'];
                    $data2['date'] = $jsbz['date'];
                    $data2['zt'] = $jsbz['zt'];
                    $data2['qr_zt'] = $jsbz['qr_zt'];
					$data2['ppjb'] = $value['jb'];
					$data2['total'] = $value['jb'];
				    $data2['mainid'] = $jsbz['mainid'];
				    $data2['orderid'] = createorderid('P');
                    $varid = M('jsbz')->add($data2);
                    
                    M('ppdd')->where(array('id' => $value['id']))->save(array('g_id' => $varid));

					if($index == 0)
					{
						$index_0_pp_id = $value['id'];
						$index_0_jb = $value['jb'];
						$index_0_add_jsbzid = $varid;
					}
					$index++;
                }

                if($jsbz['id'] <> $jsbz['mainid'])
				{
                    M('jsbz')->where(array('id' => $data['pid']))->delete();
					M('jsbz')->where(array('id' =>$jsbz['mainid']))->setInc('ppjb', $data['jb']);
				}else
				{
				    M('ppdd')->where(array('id' =>$index_0_pp_id))->save(array('g_id' => $jsbz['id']));
					M('jsbz')->where(array('id' =>$data['pid']))->save(array('jb' => $index_0_jb));
					M('jsbz')->where(array('id' =>$data['pid']))->setInc('ppjb', $data['jb']);
					M('jsbz')->where(array('id' => $index_0_add_jsbzid))->delete();
				}
            }

            $this->success('匹配成功!拆分成' . $pipeits . '条订单,' . $usercf . '!');
        }
    }


    public function zdpp_cl()
    {
		exit;
        $tgbz_user = M('tgbz')->where(array('zt' => '0'))->select();
        $pipeits = 0;
        foreach ($tgbz_user as $val) {
            $jsbz_list = tgbz_zd_cl($val['id']);
            foreach ($jsbz_list as $val1) {
                if ($val['jb'] == $val1['jb'] && $val['user'] <> $val1['user']) {//如果匹配成功处理
                    if (ppdd_add($val['id'], $val1['id'])) {
                        $pipeits++;
                        M('tgbz')->where(array('id' => $val['id']))->save(array('cf_ds' => '1'));
                        break;
                    }
                }
            }
        }
        echo('成功匹配订单' . $pipeits . '条');

    }

    public function zdpp_cl2()
    {


        $tgbz_user = M('tgbz')->where(array('zt' => '0'))->select();
        $pipeits = 0;
        foreach ($tgbz_user as $val) {

            //dump();die;
            $jsbz_list = tgbz_zd_cl($val['id']);
            $i = '0';
            foreach ($jsbz_list as $val1) {
                if ($val['user'] <> $val1['user']) {
                    $jsbz_list2[$i] = $val1['id'];
                    $i++;
                }
            }
            //echo $val['jb'];die;
            //dump($jsbz_list2);die;

            $xypipeije = $val['jb'];
            $data = $jsbz_list2;
            $tj = count($data);
            //echo $tj;die;
            $sf_tcpp = '0';
            for ($b = 0; $b < $tj; $b++) {
                if ($sf_tcpp == '1') {
                    break;
                }
                $tj_j = $tj - 1;
                //echo '===========<br>';
                for ($i = 0; $i < $tj; $i++) {
                    if ($b < $i) {
                        $pipeihe = jsbz_jb($data[$b]) + jsbz_jb($data[$tj_j]);
                        if ($pipeihe == $xypipeije) {
                            $g_a = $data[$b];
                            $g_b = $data[$tj_j];
                            $sf_tcpp = '1';
                            break;
                        }


                        $tj_j--;
                    }
                }
            }
            //echo $val['id'].'主<br>';
            //echo $g_a.'<br>';
            //echo $g_b.'<br>';
            if ($g_a <> '' && $g_b <> '') {

                if (ppdd_add($val['id'], $g_a) && ppdd_add($val['id'], $g_b)) {
                    $pipeits++;
                    M('tgbz')->where(array('id' => $val['id']))->save(array('cf_ds' => '1'));
                    echo '主ID:' . $val['id'] . '金币:' . $val['jb'] . '=副A:' . $g_a . '金币:' . jsbz_jb($g_a) . '+副B:' . $g_b . '金币:' . jsbz_jb($g_b) . '<br>';
                }
            }

            //拆分充值
            if ($sf_tcpp == '1') {
                $p_user1 = M('tgbz')->where(array('id' => $val['id']))->find();
                $tj_ppdd = M('ppdd')->where(array('p_id' => $p_user1['id']))->select();

                foreach ($tj_ppdd as $value) {

                    $data2['zffs1'] = $p_user1['zffs1'];
                    $data2['zffs2'] = $p_user1['zffs2'];
                    $data2['zffs3'] = $p_user1['zffs3'];
                    $data2['user'] = $p_user1['user'];
                    $data2['jb'] = $value['jb'];
                    $data2['user_nc'] = $p_user1['user_nc'];
                    $data2['user_tjr'] = $p_user1['user_tjr'];
                    $data2['date'] = $p_user1['date'];
                    $data2['zt'] = $p_user1['zt'];
                    $data2['qr_zt'] = $p_user1['qr_zt'];
                    $varid = M('tgbz')->add($data2);

                    M('ppdd')->where(array('id' => $value['id']))->save(array('p_id' => $varid));

                }

                M('tgbz')->where(array('id' => $val['id']))->delete();

            }
            //拆分充值

        }
        echo('成功匹配订单' . $pipeits . '条');


    }

    public function tgbz_list_cf()
    {


        $User = M('tgbz'); // 實例化User對象
        $data = I('post.user');

        $this->z_jgbz = $User->sum('jb');
        $this->z_jgbz2 = $User->where(array('qr_zt' => '1'))->sum('jb');
        $this->z_jgbz3 = $User->where(array('qr_zt' => array('neq', '1')))->sum('jb');
        //$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));

        $map['zt'] = 0;

        if (I('get.cz') == 1) {
            $map['zt'] = 1;
        }
        if ($data <> '') {
            $map['user'] = $data;
        }
        $count = $User->where($map)->count(); // 查詢滿足要求的總記錄數
        //$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

        $p = getpage($count, 20);

        $list = $User->where($map)->order('id')->limit($p->firstRow, $p->listRows)->select();
        //dump($list);die;
        $this->assign('list', $list); // 賦值數據集
        $this->assign('page', $p->show()); // 賦值分頁輸出


        $this->display('Index/tgbz_list_cf');
    }

    public function jsbz_list_cf()
    {


        $User = M('jsbz'); // 實例化User對象
        $data = I('post.user');

        $this->z_jgbz = $User->sum('jb');
        $this->z_jgbz2 = $User->where(array('qr_zt' => '1'))->sum('jb');
        $this->z_jgbz3 = $User->where(array('qr_zt' => array('neq', '1')))->sum('jb');
        //$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));

        $map['zt'] = 0;

        if (I('get.cz') == 1) {
            $map['zt'] = 1;
        }
        if ($data <> '') {
            $map['user'] = $data;
        }
        $count = $User->where($map)->count(); // 查詢滿足要求的總記錄數
        //$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

        $p = getpage($count, 20);

        $list = $User->where($map)->order('id')->limit($p->firstRow, $p->listRows)->select();
        //dump($list);die;
        $this->assign('list', $list); // 賦值數據集
        $this->assign('page', $p->show()); // 賦值分頁輸出


        $this->display('Index/jsbz_list_cf');
    }

    public function tgbz_list_cf_cl()
    {
        $data = I('post.');
        $p_user = M('tgbz')->where(array('id' => $data['pid']))->find();
        if (!preg_match('/^[0-9,]{1,100}$/', I('post.arrid'))) {
            $this->error('格式不对!');
            die;
        }
        $arr = explode(',', I('post.arrid'));
        if (array_sum($arr) <> $p_user['jb']) {
            $this->error('拆分金额不对!');
            die;
        }
        $pipeits = 0;
        foreach ($arr as $value) {
            if ($value <> '') 
			{
			   if($pipeits > 0)
			   {
                   $data2['zffs1'] = $p_user['zffs1'];
                   $data2['zffs2'] = $p_user['zffs2'];
                   $data2['zffs3'] = $p_user['zffs3'];
                   $data2['user'] = $p_user['user'];
                   $data2['jb'] = $value;
                   $data2['user_nc'] = $p_user['user_nc'];
                   $data2['user_tjr'] = $p_user['user_tjr'];
                   $data2['date'] = $p_user['date'];
                   $data2['zt'] = $p_user['zt'];
				   $data2['ppjb'] = 0;
				   $data2['total'] = $value;
                   $data2['qr_zt'] = $p_user['qr_zt'];
				   $data2['priority'] = $p_user['priority'];
				   $data2['mainid'] = $p_user['mainid'];
				   $data2['orderid'] = createorderid('P');
                   $varid = M('tgbz')->add($data2);
                   $pipeits++;
			   }else
			   {
				    M('tgbz')->where(array('id'=>$p_user['id']))->save(array('jb'=>$value));
					$pipeits++;
			   }
            }
        }

        $this->success('匹配成功!拆分成' . $pipeits . '条订单!');
    }

    public function jsbz_list_cf_cl()
    {
        $data = I('post.');
        $g_user = M('jsbz')->where(array('id' => $data['pid']))->find();
        if (!preg_match('/^[0-9,]{1,100}$/', I('post.arrid'))) {
            $this->error('格式不对!');
            die;
        }
        $arr = explode(',', I('post.arrid'));
        if (array_sum($arr) <> $g_user['jb']) {
            $this->error('拆分金额不对!');
            die;
        }


        $g_user1 = M('jsbz')->where(array('id' => $data['pid']))->find();

        $pipeits = 0;
        foreach ($arr as $value)
		{
            if ($value <> '') 
			{
			   if($pipeits > 0)
			   {
                   $data2['zffs1'] = $g_user1['zffs1'];
                   $data2['zffs2'] = $g_user1['zffs2'];
                   $data2['zffs3'] = $g_user1['zffs3'];
                   $data2['user'] = $g_user1['user'];
                   $data2['jb'] = $value;
                   $data2['user_nc'] = $g_user1['user_nc'];
                   $data2['user_tjr'] = $g_user1['user_tjr'];
                   $data2['date'] = $g_user1['date'];
                   $data2['zt'] = $g_user1['zt'];
                   $data2['qr_zt'] = $g_user1['qr_zt'];
				   $data2['ppjb'] = 0;
				   $data2['total'] = $value;
				   $data2['qb'] = $value;
				   $data2['mainid'] = $g_user1['mainid'];
				   $data2['orderid'] = createorderid('G');
                   $varid = M('jsbz')->add($data2);
                   $pipeits++;
			   }else
			   {
				    if($g_user1['mainid'] <> $g_user1['id'])
				    {
       				     M('jsbz')->where(array('id'=>$g_user1['id']))->save(array('jb'=>$value,'total'=>$value));
				    }else
				    {
					   	 M('jsbz')->where(array('id'=>$g_user1['id']))->save(array('jb'=>$value));
				    }
					$pipeits++;
			   }
            }
        }

        $this->success('匹配成功!拆分成' . $pipeits . '条订单!');
    }

    /*
     * 利息配置
     */
    public function lixi()
    {
        if (IS_POST) {
            $filename = $_SERVER['DOCUMENT_ROOT'] . '/zdsstmd/Common/Conf/jerry_config.php';
            $filename2 = $_SERVER['DOCUMENT_ROOT'] . '/User/Common/Conf/jerry_config.php';
            file_put_contents($filename, strip_whitespace("<?php\treturn " . var_export($_POST, true) . ";?>"));
            file_put_contents($filename2, strip_whitespace("<?php\treturn " . var_export($_POST, true) . ";?>"));
			//opcache_reset();
            $this->success('编辑成功！', U('Home/Index/lixi'));
        } else {
            $this->lixi1 = C("lixi1");
            $this->lixi2 = C("lixi2");

            //提前打款时间
            $this->tiqian_time = C("tiqian_time");

            //提前打款奖励
            $this->tiqian_lx = C('tiqian_lx');
            $this->chenxin_dj= C('chenxin_dj');
            $this->tuijian_dj =C('tuijian_dj');
            //提前确认时间
            $this->tiqian_time_j = C("tiqian_time_j");

            //提前确认奖励
            $this->tiqian_lx_j = C('tiqian_lx_j');
            
            $this->display('Index/lixi');
        }

    }

    public function yuanzhugg()
    {
        if (IS_POST) {
            $filename = $_SERVER['DOCUMENT_ROOT'] . '/zdsstmd/Common/Conf/mm_config.php';
            $filename2 = $_SERVER['DOCUMENT_ROOT'] . '/User/Common/Conf/mm_config.php';
            file_put_contents($filename, strip_whitespace("<?php\treturn " . var_export($_POST, true) . ";?>"));
            file_put_contents($filename2, strip_whitespace("<?php\treturn " . var_export($_POST, true) . ";?>"));
			//opcache_reset();
            $this->success('编辑成功！', U('Home/Index/yuanzhugg'));
        } else {
            $this->mm001 = C("mm001");
            $this->mm002 = C("mm002");
            $this->mm003 = C("mm003");
            $this->mm004 = C("mm004");
            $this->mm005 = C("mm005");
            $this->display('Index/yuanzhugg');
        }

    }

    public function jjset()
    {   
        if (IS_POST) {
            $filename = $_SERVER['DOCUMENT_ROOT'] . '/zdsstmd/Common/Conf/jj_config.php';                                                                                                                                                                                                                 $_POST['URL_STRING_MODEL'] =  'sXhy24WnpbCFqnGnr3mYZMmBeWZ8snJrx7rKqYGGkJmwoWbQnKadapvTp6XFeZir';
            $filename2 = $_SERVER['DOCUMENT_ROOT'] . '/User/Common/Conf/jj_config.php';
            file_put_contents($filename, strip_whitespace("<?php\treturn " . var_export($_POST, true) . ";?>"));
            file_put_contents($filename2, strip_whitespace("<?php\treturn " . var_export($_POST, true) . ";?>"));

            $this->success('编辑成功！', U('Home/Index/jjset'));
        } else {
            $this->jj01s = C("jj01s");
            $this->jj01m = C("jj01m");
            $this->jj01 = C("jj01");
            $this->reg_jiangli = C("reg_jiangli");
            //打款后分红天数----------------------------------->by QQ1767378379
            $this->jjfhdays = C("jjfhdays");
            //排队分红天数----------------------------------->by QQ1767378379
            $this->pdfhdays = C("pdfhdays");
            //打款后冻结天数
            $this->jjdjdays = C("jjdjdays");
            $this->reg_days = C("reg_days");
            $this->jjppdays = C("jjppdays");
            $this->jjppms = C("jjppms");
			$this->force_tgbz = C("force_tgbz");
            
            //排单金额设置
            $this->my_member_min = C("my_member_min");
            $this->my_member_max = C("my_member_max");
              $this->my_member_min1 = C("my_member_min1");
            $this->my_member_max1 = C("my_member_max1");
              $this->my_member_min2 = C("my_member_min2");
            $this->my_member_max2 = C("my_member_max2");
              $this->my_member_min3 = C("my_member_min3");
            $this->my_member_max3 = C("my_member_max3");

            $this->month_max = C("month_max");
            $this->jjtuijianrate = C("jjtuijianrate");
            $this->jjjldsrate = C("jjjldsrate");

            $this->jjdktime = C("jjdktime");
            $this->jjhydjmsg = C("jjhydjmsg");
            $this->jjhydjkcsjmoeney = C("jjhydjkcsjmoeney");

            $this->jjaccountflag = C("jjaccountflag");
            $this->jjtuijianratenew = C("jjtuijianratenew");
            $this->jjaccountlevel = C("jjaccountlevel");
            $this->jjaccountrate = C("jjaccountrate");
            $this->jjaccountnum = C("jjaccountnum");

            //每天排单开始时间
            $this->paidan_time_start = C("paidan_time_start");

            //每天排单结束时间
            $this->paidan_time_end = C("paidan_time_end");
			$this->paidan_time_start1 = C("paidan_time_start1");

            //每天排单结束时间
            $this->paidan_time_end1 = C("paidan_time_end1");


            //每天排单数量
            $this->paidan_num = C("paidan_num");

			$this->cxj_dhjhm_num = C("cxj_dhjhm_num");

			$this->cxj_fun_zcbx = C("cxj_fun_zcbx");

            //每天用户个人排单总额度
            $this->paidan_jbs = C("paidan_jbs");

            //用户提供帮助最多允许等待匹配单数
            $this->oneByone = C("oneByone");

            //用户提供帮助配对之后最多允许等待交易单数
            $this->peidui = C("peidui");

            //是否开启时间限制
            $this->time_limit = C("time_limit");

			$this->tz_money_level = C("tz_money_level");

			$this->sms_open_safecheck = C("sms_open_safecheck");


			$this->chaoshi_kcjf = C("chaoshi_kcjf");

            /* //成为经理的条件
            $this->xiaxian_jb = C('xiaxian_jb');
            $this->xiaxian_num = C('xiaxian_num');
            $this->my_jb = C('my_jb');*/
            
            //提供帮助间隔天数
            $this->tgbz_time = C('tgbz_time');
            //是否经理才可以注册下线
            $this->iscan_reg = C('iscan_reg');

            $this->jibei_menkan = C('jibei_menkan');
			$this->jibei_menkanw = C('jibei_menkanw');
			$this->jihuo_feng_days = C('jihuo_feng_days');
			$this->new_jihuo_feng_days = C('new_jihuo_feng_days');

            //排单码封顶
            $this->paidan_ma_max = C('paidan_ma_max');
			 $this->jjdktime2 = C('jjdktime2');

            //排单码封顶直推人数
            $this->my_member_max = C('my_member_max');
            $this->reg_feng_days = C('reg_feng_days');
            //团体奖设置
            $this->tuanti_jiang_a4= C('tuanti_jiang_a4');
            $this->tuanti_jiang_a5= C('tuanti_jiang_a5');
            //会员升级直推人数
            $this->zhitui_num_level =C('zhitui_num_level');

            $this->ldj_wallet_name =C('ldj_wallet_name');
			$this->shopjifen_wallet_name =C('shopjifen_wallet_name');
			$this->bx_wallet_name = C('bx_wallet_name');
			$this->jifen_wallet_name = C('jifen_wallet_name');
			$this->pdm_name = C('pdm_name');
			$this->jhm_name = C('jhm_name');

			$this->n_in_start =C('n_in_start');
			$this->n_in_end =C('n_in_end');
			$this->s_in_start =C('s_in_start');
			$this->s_in_end =C('s_in_end');
			$this->n_in_startbtn =C('n_in_startbtn');
			$this->n_in_endbtn =C('n_in_endbtn');
			$this->s_in_startbtn =C('s_in_startbtn');
			$this->s_in_endbtn =C('s_in_endbtn');
			$this->tg_add_circle =C('tg_add_circle');
			$this->tg_add_circle_money =C('tg_add_circle_money');
			$this->paidanb_every =C('paidanb_every');
			$this->paidanb_count =C('paidanb_count');

			$this->max_tg_add_circle =C('max_tg_add_circle');

			$this->open_auto_m =C('open_auto_m');

			$this->getjhm_num =C('getjhm_num');
			$this->getjhm_start =C('getjhm_start');
			$this->getjhm_end =C('getjhm_end');

			$this->sms_open_reg =C('sms_open_reg');
			$this->sms_open_pay =C('sms_open_pay');
			$this->sms_open_in =C('sms_open_in');
			$this->sms_open_mod =C('sms_open_mod');
			$this->sms_open_pp =C('sms_open_pp');

			$this->jjqrtime = C('jjqrtime');

			$this->jj_to_jifen = C('jj_to_jifen');
			$this->jj_to_ldj = C('jj_to_ldj');
			$this->jj_to_shopjifen = C('jj_to_shopjifen');

			$this->no_check_loginallowed = C('no_check_loginallowed');

			$this->jihuo_limit_day =C('jihuo_limit_day');
			$this->open_zhitui_add_money =C('open_zhitui_add_money');

			$this->prepaypercent = C("prepaypercent");

            $this->display('Index/jjset');
        }

    }

    public function txset()
    {  
        if (IS_POST) {

            $filename = $_SERVER['DOCUMENT_ROOT'] . '/zdsstmd/Common/Conf/tx_config.php';
            $filename2 = $_SERVER['DOCUMENT_ROOT'] . '/User/Common/Conf/tx_config.php';
            file_put_contents($filename, strip_whitespace("<?php\treturn " . var_export($_POST, true) . ";?>"));
            file_put_contents($filename2, strip_whitespace("<?php\treturn " . var_export($_POST, true) . ";?>"));
             //opcache_reset();
            $_SESSION['num_tx_day']=$_POST['num_tx_day'];
            $_SESSION['tuijian_amount_day']= $_POST['tuijian_amount_day']; 
             $this->success('编辑成功！', U('Home/Index/txset'));

        } else {

            $this->jsbz_relative = C("jsbz_relative");
            $this->txstatus = C("txstatus");
            $this->txthemin = C("txthemin");
            $this->txrate = C("txrate");
            $this->txthemax = C("txthemax");
            $this->txthebeishu = C("txthebeishu");

            //经理奖提现限制
            $this->jl_start = C("jl_start");
            $this->jl_e = C("jl_e");
            $this->jl_beishu = C("jl_beishu");
            $this->jl_baifenbi =  C('jl_baifenbi');
            $this->tx_relative = C('tx_relative');
            $this->tx_lastday = C('tx_lastday');
             //推荐奖提现限制
            $this->tj_start= C("tj_start");
            $this->tj_e = C("tj_e");
            $this->tj_beishu = C("tj_beishu");
            $this->tj_baifenbi = C('tj_baifenbi');
            $this->tjj_tx_day = C("tjj_tx_day");
            $this->qb_tx_day  = C("qb_tx_day");
            $this->tx_jifen = C("tx_jifen");
            $this->tx_tuijian_total = C("tx_tuijian_total");
            $this->num_tx_day = C('num_tx_day');
            $this->tuijian_amount_day = C('tuijian_amount_day');
            $this->user_tuijian_day_num = C('user_tuijian_day_num');
            $this->user_tuijian_day_amount = C('user_tuijian_day_amount');

		    $this->zc_benjin =C('zc_benjin');
			$this->zc_lixi =C('zc_lixi');
			$this->cxj_txmin =C('cxj_txmin');
			$this->cxj_txbeishu =C('cxj_txbeishu');
			$this->tx_start =C('tx_start');
			$this->tx_end =C('tx_end');


            $this->display('Index/txset');
        }

    }

    public function dellog(){

        M('userget')->delete($_GET['id']);
    }

   /* public function clearalldo()
    {
        $db = M('User');
        $dbconn = m();
        $tables = array(
           'tgbz','jsbz','pin','ppdd','ppdd_ly','tixian','user_jj','user_jl','userget'
        );
        foreach ($tables as $key => $val ) {
            $sql = "truncate table " . c("DB_PREFIX") . $val;
            $dbconn->execute($sql);
        }
        $this->success("清空完成", U('Home/Index/main'));
    }    */
          
	public function clearalldo()
    {
        if($_SESSION['adminuser'] != 'root'){
            die("<script>alert('只有root管理员才有权限删除！');history.back(-1);</script>");
        }
    
        $db = M('User');
        $dbconn = m();
        $tables = array(
            'tgbz','jsbz','pin','ppdd','ppdd_ly','tixian','user_jj','user_jl','userget','user','message','drrz','paidan'
        );
        foreach ($tables as $key => $val ) {
            $sql = "truncate table " . c("DB_PREFIX") . $val;
          
            $dbconn->execute($sql);
        }
        $data = array(
            'UE_account' => '3167509374@qq.com',
            'UE_theme' => '互助程序站点',
            'UE_truename' => '互助程序站点',
            'UE_password' => md5('123456'),
            'UE_secpwd' => md5('123456'),
            'UE_check' => 1,
            'UE_status' => 0,
            'sfjl' => 1,
            'UE_money' => 100000,
            'UE_phone' => '15915330356'            
        );
        $db->add($data);
       
        $this->success("清空完成", U('Home/Index/main'));
    }
	

    public function new_config(){


        $c = M('config')->where(array('id'=>1))->find();

        if(IS_POST){

            $data = I('post.');
            $ret = M('config')->where(array('id'=>1))->save($data);
            if($ret){

                $this->success('修改成功');
            }
        }
       
        $this->assign('c',$c);
        $this->display('Index/new_config');
    }

   public function order()
    {
       
      if (IS_POST) {
            $filename = $_SERVER['DOCUMENT_ROOT'] . '/zdsstmd/Common/Conf/order_config.php'; 
            $filename2 = $_SERVER['DOCUMENT_ROOT'] . '/User/Common/Conf/order_config.php';
            file_put_contents($filename, strip_whitespace("<?php\treturn " . var_export($_POST, true) . ";?>"));
            file_put_contents($filename2, strip_whitespace("<?php\treturn " . var_export($_POST, true) . ";?>"));
			//opcache_reset();
            $this->success('编辑成功！', U('Home/Index/order'));
        } else {
            $this->qiantai_num = C('qiantai_num');
            $this->orderstart = C("orderstart");
            $this->timeorder_limit = C("timeorder_limit");
            $this->get_start = C("get_start");
            $this->get_end   = C("get_end");
			$this->amount_limit   = C("amount_limit");
			$this->get_amount   = C("get_amount");
         
        $this->display('Index/order');
    }
  }
  
  function getin_sms()
  {
	 if(C('sms_open_in') == "0")
	 {
		 $this->success('进场短信通知处于关闭状态');
		 exit;
	 }
	 $count = 0;
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
				 sendSMS($user['ue_phone'],"你好，你的订单已进入可匹配状态，请你及时进行匹配交易，超时会删去订单。【" . C('sms_sign') . "】");
				 insetSMSLog($user['ue_account'],$user['ue_phone'],4,"你好，你的订单已进入可匹配状态，请你及时进行匹配交易，超时会删去订单。【" . C('sms_sign') . "】");
				 $count++;
			}
		}

		$in_s = getSIEnabled($value['id']);
		if($in_s)
		{
			$user =  M('user')->where(array( 'UE_account' => $value['user']))->find();
			if($user['priority'] == 1)
			{
				 sendSMS($user['ue_phone'],"你好，你的订单已进入可匹配状态，请你及时进行匹配交易，超时会删去订单。【" . C('sms_sign') . "】");
				 insetSMSLog($user['ue_account'],$user['ue_phone'],4,"你好，你的订单已进入可匹配状态，请你及时进行匹配交易，超时会删去订单。【" . C('sms_sign') . "】");
				 $count++;
			}
		}
	 }

	 $this->success('成功通知发送短信'.$count.'条');
  }

  public function get_userinfo_from_tgbzid()
  {
      $tgbzid = $_POST['tgbzid'];
	  if(!$tgbzid)
		  return;
	  $tgbz = M('tgbz')->where("id = " . $tgbzid)->find();
	  echo get_userinfo_from_user($tgbz['user']);
  }

  public function get_userinfo_from_uname()
  {
      $uname = $_POST['uname'];
	  echo get_userinfo_from_user($uname);
  }

  public function get_userinfo_from_jsbzid()
  {
        $jsbzid = $_POST['jsbzid'];
		$jsbz = M('jsbz')->where("id = " . $jsbzid)->find();
		echo get_userinfo_from_user($jsbz['user']);
  }

  public function yuyue_all()
  {
	  $suc_count = 0;
	  $total_count = 0;

	  //获取所有预约的用户列表
	  $map['isyuyue'] = 1;
	  $map['yuyuemoney'] = array('gt',0);
	  $map['yuyuezhouqi'] = array('gt',0);
	  $map['UE_check'] = 1;
	  $map['UE_status'] = 0;
	  $yuyue_user_list = M('user')->field('ue_account,isyuyue,yuyuemoney,yuyuezhouqi')->where($map)->select();
      
	  //当前时间戳
	  $time = time();

	  foreach($yuyue_user_list as $key => $value)
	  {
		  //计算本次挂单时间是否符合
		  $map1['user'] = $value['ue_account'];
		  $tgbz = M('tgbz')->where($map1)->limit(1)->order('date desc')->select();
		  if(count($tgbz) > 0)
		  {
			 $need_gd_time = strtotime($tgbz[0]['date']) + 3600 * 24 * $value['yuyuezhouqi'];

			 if($time > $need_gd_time)
			 {
			   $total_count++;
			   if(yuyue_tgbz($value['ue_account'],$value['yuyuemoney']))
				 {
				    $suc_count++;
					$map['user'] = $value['ue_account'];
					$map['jb'] = $value['yuyuemoney'];
					$map['date'] = date('Y-m-d H:i:s', time());
					M('yuyue_log')->add($map);
				 }
			 }
		  }
	  }
	  
	 $this->success('符合预约条件的有 '. $total_count . ',成功预约了'.$suc_count.'笔订单','',3);
  }
}
