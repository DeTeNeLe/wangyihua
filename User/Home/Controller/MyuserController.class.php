<?php



namespace Home\Controller;



use Think\Controller;



class MyuserController extends CommonController {

	// 首頁

	public function index() {

		$suser = I ( 'post.user' );
		if($suser==''){
			$map['_string']="UE_account = '" . $_SESSION['uname'] . "' or UE_accName = '" . $_SESSION['uname'] ."'";
		}else{

			$map['UE_account']=$suser;

		}
		/*
		//////////////////----------
		$User = M ( 'user' ); // 實例化User對象

		$count = $User->where ( $map )->count (); // 查詢滿足要求的總記錄數

		$p = getpage($count,I ( 'get.pagesize' ) == "" ? 10 : I ( 'get.pagesize' ));

		$list = $User->where ( $map )->order ( 'UE_ID asc' )->limit ( $p->firstRow, $p->listRows )->select ();

		$this->assign ( 'list', $list ); // 賦值數據集

		$this->assign ( 'page', $p->show() ); // 賦值分頁輸出
		*/

        $User = M ( 'user' ); // 實例化User對象
        //当前会员下的直推和间接推荐的会员
        //一级会员
        $fristList = $User->where(array('UE_accName'=>$_SESSION['uname']))->select();
        $this->assign ( 'fristList', $fristList ); // 直推会员
        //二级会员
        $secondList = [];
        foreach( $fristList as $user ){
            $cur_list = $User->where(array('UE_accName'=>$user['ue_account']))->select();
            if( !empty($cur_list) ) {
                foreach( $cur_list as $key => $cur_user ){
                    $cur_user['parent_phone'] = $user['ue_phone'];
                    $cur_list[$key] = $cur_user;
                }

                $secondList = array_merge_recursive($secondList, $cur_list);
            }
        }

        //$fristList = array_merge_recursive($fristList,$secondList);//dump($fristList);
        //$this->assign ( 'list', $fristList ); // 賦值數據集
        //目前只保留简介推荐的会员
        $this->assign ( 'list', $secondList ); // 賦值數據集

		
		$userData = M ( 'user' )->where ( array ('UE_ID' => $_SESSION ['uid'] ) )->find ();

		$this->userData = $userData;

		$this->jiazu = true;


		$this->assign('team_active','active');

		//所有下级的人数
        $all_user_num = getAllSubUserNum($_SESSION['uname']);
        $all_user_num = !empty($all_user_num) ? $all_user_num : 0;
        $this->assign('all_user_num',$all_user_num);

		$this->display ( 'wdzh' );

	}
	

	public function fhjl() {

	

		$User = M ( 'userget' ); // 實例化User對象

	//	$suser = I ( 'post.user', '', '/^[a-zA-Z0-9]{6,12}$/' );

	

		$map ['UG_account'] = $_SESSION ['uname'];

		$map ['UG_dataType'] = 'fuhuojl';

	

		//dump($map ['UE_accName']);die;

	

		//	$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));

	

		// 		if (! empty ( $date1 ) && ! empty ( $date2 )) {

		// 			$map ['UG_getTime'] = array (

		// 					array (

		// 							'gt',

		// 							$date1

		// 					),

		// 					array (

		// 							'lt',

		// 							$date2

		// 					),

		// 					'and'

		// 			);

		// 		}

	

		//$map  = array('tjj','kdj','glj');

		//	$map['UE_Faccount']  = $_SESSION ['uname']

		//$ljtc1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>array('IN',$map)))->sum('UG_money');

	

		$count = $User->where ($map )->count (); // 查詢滿足要求的總記錄數

		$page = new \Think\Page ( $count, 20 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

	

		// $page->lastSuffix=false;

		$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>條記錄    第<b>%NOW_PAGE%</b>頁/共<b>%TOTAL_PAGE%</b>頁</li>' );

		$page->setConfig ( 'prev', '上一頁' );

		$page->setConfig ( 'next', '下一頁' );

		$page->setConfig ( 'last', '末頁' );

		$page->setConfig ( 'first', '首頁' );

		$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );

		;

	

		$show = $page->show (); // 分頁顯示輸出

		// 進行分頁數據查詢 注意limit方法的參數要使用Page類的屬性

		$list = $User->where ($map)->order ( 'UG_ID DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();

		//	dump($list);die;

		$this->assign ( 'list', $list ); // 賦值數據集

		$this->assign ( 'page', $show ); // 賦值分頁輸出

	

	

		$userData = M ( 'user' )->where ( array (

				'UE_ID' => $_SESSION ['uid']

		) )->find ();

		$this->userData = $userData;

		$this->display ( 'fhjl' );

	}

	

	

	

	public function yjhhy() {

	

		$User = M ( 'user' ); // 實例化User對象

		$suser = I ( 'post.user', '', '/^[a-zA-Z0-9]{6,12}$/' );

	

		$map ['UE_accName'] = $_SESSION ['uname'];

		$map ['zcr'] = $_SESSION ['uname'];

		$map ['UE_theme'] = $_SESSION ['uname'];

		$map ['_logic'] = 'OR';

	

		//dump($map ['UE_accName']);die;

	

		//	$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));

	

		// 		if (! empty ( $date1 ) && ! empty ( $date2 )) {

		// 			$map ['UG_getTime'] = array (

		// 					array (

		// 							'gt',

		// 							$date1

		// 					),

		// 					array (

		// 							'lt',

		// 							$date2

		// 					),

		// 					'and'

		// 			);

		// 		}

	

		//$map  = array('tjj','kdj','glj');

		//	$map['UE_Faccount']  = $_SESSION ['uname']

		//$ljtc1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>array('IN',$map)))->sum('UG_money');

	

		$count = $User->where (array(array($map ),array('UE_check'=>1)))->count (); // 查詢滿足要求的總記錄數

		$page = new \Think\Page ( $count, 20 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

	

		// $page->lastSuffix=false;

		$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>條記錄    第<b>%NOW_PAGE%</b>頁/共<b>%TOTAL_PAGE%</b>頁</li>' );

		$page->setConfig ( 'prev', '上一頁' );

		$page->setConfig ( 'next', '下一頁' );

		$page->setConfig ( 'last', '末頁' );

		$page->setConfig ( 'first', '首頁' );

		$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );

		;

	

		$show = $page->show (); // 分頁顯示輸出

		// 進行分頁數據查詢 注意limit方法的參數要使用Page類的屬性

		$list = $User->where ( array(array($map ),array('UE_check'=>1)))->order ( 'UE_ID DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();

		//	dump($list);die;

		$this->assign ( 'list', $list ); // 賦值數據集

		$this->assign ( 'page', $show ); // 賦值分頁輸出

	

	

		$userData = M ( 'user' )->where ( array (

				'UE_ID' => $_SESSION ['uid']

		) )->find ();

		$this->userData = $userData;

		$this->display ( 'yjhhy' );

	}

	

	

	public function xzzh() {

	

		//////////////////----------

		$User = M ( 'tgbz' ); // 實例化User對象

		

		$map['user_tjr']=$_SESSION['uname'];

		$count = $User->where ( $map )->count (); // 查詢滿足要求的總記錄數

		//$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

		

		$p = getpage($count,10);

		

		$list = $User->where ( $map )->order ( 'id DESC' )->limit ( $p->firstRow, $p->listRows )->select ();

		$this->assign ( 'list', $list ); // 賦值數據集

		$this->assign ( 'page', $p->show() ); // 賦值分頁輸出

		/////////////////----------------

		

		

		

		//////////////////----------

		$User = M ( 'jsbz' ); // 實例化User對象

		

		$map1['user_tjr']=$_SESSION['uname'];

		$count1 = $User->where ( $map1 )->count (); // 查詢滿足要求的總記錄數

		//$page = new \Think\Page ( $count, 3 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

		

		$p1 = getpage($count1,100);

		

		$list1 = $User->where ( $map1 )->order ( 'id DESC' )->limit ( $p1->firstRow, $p1->listRows )->select ();

		$this->assign ( 'list1', $list1 ); // 賦值數據集

		$this->assign ( 'page1', $p1->show() ); // 賦值分頁輸出

		/////////////////----------------

		

		

		

		

		$this->display ( 'xzzh' );

	}

	public function zhdel() {

		if (!preg_match ( '/^[0-9]{1,10}$/', I ('get.id') )) {

			$this->success('非法操作,將凍結賬號!');

		}else{

			$userinfo = M ( 'user' )->where ( array ('UE_ID' => I ('get.id'),'UE_check'=>0) )->find ();

			//dump(I ('get.id'));

			//dump($userinfo['ue_accname']);die;

			if ($userinfo['ue_accname']<>$_SESSION ['uname']&&$userinfo['ue_theme']<>$_SESSION ['uname']&&$userinfo['zcr']<>$_SESSION ['uname']) {

				$this->success('非法操作,將凍結賬號!');

			}else{

				$reg = M ( 'user' )->where(array ('UE_ID' => I ('get.id')))->delete();

				if ($reg) {

					$this->success('刪除成功!');

				}else {

					$this->success('刪除失敗!');

				}

			}

		}

	}

	

	

	

	public function jihuo() {

			$otconfig = M ( 'system' )->where ( array (

				'SYS_ID' => 1

		) )->find ();

			

			$data_P = I ( 'get.' );

			//當前賬號信息

			$user = M ( 'user' )->where ( array ('UE_account' => $_SESSION ['uname'] ) )->find ();

			$user1 = M ();

			// dump(I ( 'post.yzm' ));die;

			// 

			//dump($user ['zsbhe']);die;

			if (! preg_match ( '/^[a-zA-Z0-9]{6,12}$/', $data_P ['wjbhname'] )) {

				$this->success ( '玩家用戶名格式不對!');

			

			}  elseif ($user ['zsbhe'] < $otconfig['a_kd_zsb']) {

				$this->success ( '餘額不足!');

				

			} else {

				//要激活賬號信息

				$wjbhname = M ( 'user' )->where ( array ('UE_account' => $data_P ['wjbhname'] ) )->find ();

				//報單中心信息

				//$bdzxname = M ( 'user' )->where ( array ('UE_account' => $data_P ['bdzxname'] ) )->find ();

				//報單中心許可權

				//$bdzx_rs = M ( 'user' )->where ( array ('UE_accName' => $data_P ['bdzxname'],'UE_Faccount'=>'0','UE_check'=>'1','UE_stop'=>'1' ) )->count("UE_ID");

				//dump($bdzx_rs)

				//echo ($bdzx_rs);die;

				if (! $wjbhname) {

					$this->success ( '需激活用戶不存在或已激活!');

				} elseif ($wjbhname ['ue_check'] == 1) {

					$this->success ( '用戶名已經激活過了!');

					

				} else {

					//寫入數據開始

					$date_dq = date ( 'Y-m-d H:i:s', time () );

					$reg10 = M('user')->where(array ('UE_account' => $_SESSION ['uname'] ) )->setDec('zsbhe',$otconfig['a_kd_zsb']);

					 M('user')->where(array ('UE_account' => $data_P ['wjbhname'] ) )->setDec('UE_money',50);

					$user = M ('user' )->where ( array ('UE_account' => $_SESSION ['uname'] ) )->find ();

					$note1="為新用戶".$wjbhname ['ue_account']."激活成功";

					$record1["UG_account"]	= $_SESSION ['uname'];

					$record1["UG_type"]  	= 'zsb';

					$record1["zsb"] 	= $otconfig['a_kd_zsb']-$otconfig['a_kd_zsb']*2; //金幣

					//$record1["UG_allGet"]	= '1500'; //推薦獎金總的

					$record1["zsbhe"]	= $user['zsbhe']; //當前推薦人的金幣餘額

					$record1["UG_dataType"]	= 'tjfy'; //當前開單人的金幣餘額

					$record1["UG_note"]		= $note1; //推薦獎說明

					$record1["UG_getTime"]		= $date_dq; //操作時間

					$reg1 = M ( 'userget' )->add ( $record1 );

					

					

					

					

					$note2="網路維護費";

					$record2["UG_account"]	= $data_P ['wjbhname'];

					$record2["UG_type"]  	= 'jb';

					$record2["UG_money"] 	= 50-100; //金幣

					//$record1["UG_allGet"]	= '1500'; //推薦獎金總的

					$record2["UG_balance"]	= 50-100; //當前推薦人的金幣餘額

					$record2["UG_dataType"]	= 'whf'; //當前開單人的金幣餘額

					$record2["UG_note"]		= $note1; //推薦獎說明

					$record2["UG_getTime"]		= $date_dq; //操作時間

					M ( 'userget' )->add ( $record2 );

					

				

					$reg14=M('user')->where(array('UE_account'=>$data_P ['wjbhname']))->save(array('UE_check'=>'1','UE_activeTime'=>$date_dq,'jihuouser'=>$_SESSION ['uname']));

					

					if($reg10 && $reg1 && $reg14){

					$this->success( '激活成功!' );

					}else{

						$this->success ( '激活失敗!');

					}

				}

			

		}

	}

	

	

	public function jihuo2() {

		$otconfig = M ( 'system' )->where ( array (

				'SYS_ID' => 1

		) )->find ();

			

		$data_P = I ( 'get.' );

		//當前賬號信息

		$user = M ( 'user' )->where ( array ('UE_account' => $_SESSION ['uname'] ) )->find ();

		$user1 = M ();

		// dump(I ( 'post.yzm' ));die;

		//

		//dump($user ['zsbhe']);die;

		if (! preg_match ( '/^[a-zA-Z0-9]{6,12}$/', $data_P ['wjbhname'] )) {

		$this->success ( '玩家用戶名格式不對!');

		

		}  elseif ($user ['ue_money'] < 1000) {

				$this->success ( '金币餘額不足!');

	

			} else {

		//要激活賬號信息

		$wjbhname = M ( 'user' )->where ( array ('UE_account' => $data_P ['wjbhname'] ) )->find ();

				//報單中心信息

				//$bdzxname = M ( 'user' )->where ( array ('UE_account' => $data_P ['bdzxname'] ) )->find ();

				//報單中心許可權

				//$bdzx_rs = M ( 'user' )->where ( array ('UE_accName' => $data_P ['bdzxname'],'UE_Faccount'=>'0','UE_check'=>'1','UE_stop'=>'1' ) )->count("UE_ID");

				//dump($bdzx_rs)

				//echo ($bdzx_rs);die;

				if (! $wjbhname) {

				$this->success ( '需激活用戶不存在或已激活!');

				} elseif ($wjbhname ['ue_check'] == 1) {

				$this->success ( '用戶名已經激活過了!');

						

				} else {

					//寫入數據開始

				$date_dq = date ( 'Y-m-d H:i:s', time () );

				$reg10 = M('user')->where(array ('UE_account' => $_SESSION ['uname'] ) )->setDec('UE_money',1000);

				M('user')->where(array ('UE_account' => $data_P ['wjbhname'] ) )->setDec('UE_money',50);

					$user = M ('user' )->where ( array ('UE_account' => $_SESSION ['uname'] ) )->find ();

						$note1="為新用戶".$wjbhname ['ue_account']."激活成功";

								$record1["UG_account"]	= $_SESSION ['uname'];

					$record1["UG_type"]  	= 'jb';

				$record1["UG_money"] 	= "-1000"; //金幣

						//$record1["UG_allGet"]	= '1500'; //推薦獎金總的

				$record1["UG_balance"]	= $user['ue_money']; //當前推薦人的金幣餘額

					$record1["UG_dataType"]	= 'tjfy'; //當前開單人的金幣餘額

						$record1["UG_note"]		= $note1; //推薦獎說明

				$record1["UG_getTime"]		= $date_dq; //操作時間

				$reg1 = M ( 'userget' )->add ( $record1 );

					

					

					

			

				$note2="網路維護費";

				$record2["UG_account"]	= $data_P ['wjbhname'];

				$record2["UG_type"]  	= 'jb';

				$record2["UG_money"] 	= 50-100; //金幣

				//$record1["UG_allGet"]	= '1500'; //推薦獎金總的

				$record2["UG_balance"]	= 50-100; //當前推薦人的金幣餘額

				$record2["UG_dataType"]	= 'whf'; //當前開單人的金幣餘額

				$record2["UG_note"]		= $note1; //推薦獎說明

						$record2["UG_getTime"]		= $date_dq; //操作時間

						M ( 'userget' )->add ( $record2 );

							

	

						$reg14=M('user')->where(array('UE_account'=>$data_P ['wjbhname']))->save(array('UE_check'=>'1','UE_activeTime'=>$date_dq,'jihuouser'=>$_SESSION ['uname']));

									

					if($reg10 && $reg1 && $reg14){

						$this->success( '激活成功!' );

				}else{

				$this->success ( '激活失敗!');

				}

				}

		

				}

				}

	

	public function xzczmm() {

		

		$userData = M ( 'user' )->where ( array (

				'UE_ID' => $_SESSION ['uid']

		) )->find ();

		$this->xzzhname = I('get.name');

		$this->display ( 'xzczmm' );

	}

	public function ygsc() {

	

		$userData = M ( 'user' )->where ( array (

				'UE_ID' => $_SESSION ['uid']

		) )->find ();

		$this->xzzhname = I('get.name');

		$this->display ( 'ygsc' );

	}

	public function xzczmmcl() {

	

		if (IS_AJAX) {

			$data_P = I ( 'post.' );

			

			//dump($data_P);die;

			//$this->ajaxReturn($data_P['ymm']);die;

			//$user = M ( 'user' )->where ( array (

			//		UE_account => $_SESSION ['uname']

			//) )->find ();

				

			$user1 = M ();

			//! $this->check_verify ( I ( 'post.yzm' ) )

			//! $user1->autoCheckToken ( $_POST )

			if (! $this->check_verify ( I ( 'post.yzm' ) )) {

					

				$this->ajaxReturn ( array ('nr' => '驗證碼錯誤!','sf' => 0 ) );

			}elseif (!preg_match ( '/^[a-zA-Z0-9]{6,12}$/', $data_P ['username'] )) {

				$this->ajaxReturn ( array ('nr' => '用戶名格式不對！','sf' => 0 ) );

			}elseif (!preg_match ( '/^[a-zA-Z0-9]{6,15}$/', $data_P ['yjmm'] )) {

				$this->ajaxReturn ( array ('nr' => '新密碼6-12個字元,大小寫英文+數字,請勿用特殊詞符！','sf' => 0 ) );

			}elseif (!preg_match ( '/^[a-zA-Z0-9]{6,15}$/', $data_P ['yjmmqr'] )) {

				$this->ajaxReturn ( array ('nr' => '新密碼6-12個字元,大小寫英文+數字,請勿用特殊詞符！','sf' => 0 ) );

			}elseif (!preg_match ( '/^[a-zA-Z0-9]{6,15}$/', $data_P ['ejmm'] )) {

				$this->ajaxReturn ( array ('nr' => '新二級密碼6-12個字元,大小寫英文+數字,請勿用特殊詞符！','sf' => 0 ) );

			}elseif (!preg_match ( '/^[a-zA-Z0-9]{6,15}$/', $data_P ['ejmmqr'] )) {

				$this->ajaxReturn ( array ('nr' => '新二級密碼6-12個字元,大小寫英文+數字,請勿用特殊詞符！','sf' => 0 ) );

			}elseif ($data_P ['yjmm']<>$data_P ['yjmmqr']) {

				$this->ajaxReturn ( array ('nr' => '新密碼兩次輸入不一致!','sf' => 0 ) );

			}elseif ($data_P ['ejmm']<>$data_P ['ejmmqr']) {

				$this->ajaxReturn ( array ('nr' => '新二級密碼兩次輸入不一致!','sf' => 0 ) );

			} else {

				$addaccount = M ( 'user' )->where ( array ('UE_account' => $data_P ['username']) )->find ();

	

				if ($addaccount['ue_faccount']<>$_SESSION['uname']) {

					$this->ajaxReturn ( array ('nr' => '非法操作,將凍結賬號!','sf' => 0 ) );

				}elseif(! $user1->autoCheckToken ( $_POST )){

					$this->ajaxReturn ( array ('nr' => '新勿重複提交,請刷新頁面!','sf' => 0 ) );

				} else {

					//dump($data_P ['username']);die;

 					$reg = M ( 'user' )->where (array ('UE_account' => $data_P ['username']))->save (array('UE_password'=>md5($data_P['yjmm']),'UE_secpwd'=> md5($data_P['ejmm'])));

	

	

	

 					if ($reg) {

 						$this->ajaxReturn ( '修改成功!' );

 					} else {

 						$this->ajaxReturn ( '修改失敗!' );

 					}

				}

			}

		}

	}

	public function lxwm() {

	

		$User = M ( 'message' ); // 實例化User對象

		//$suser = I ( 'post.user', '', '/^[a-zA-Z0-9]{6,12}$/' );

		

			$map ['MA_userName'] = $_SESSION ['uname'];







		//dump($map ['UE_accName']);die;

	

		//	$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));

	

		// 		if (! empty ( $date1 ) && ! empty ( $date2 )) {

		// 			$map ['UG_getTime'] = array (

		// 					array (

		// 							'gt',

		// 							$date1

		// 					),

		// 					array (

		// 							'lt',

		// 							$date2

		// 					),

		// 					'and'

		// 			);

		// 		}

	

		//$map  = array('tjj','kdj','glj');

		//	$map['UE_Faccount']  = $_SESSION ['uname']

		//$ljtc1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>array('IN',$map)))->sum('UG_money');

	

		$count = $User->where ( $map )->count (); // 查詢滿足要求的總記錄數

		//dump($var)

		$page = new \Think\Page ( $count, 12 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

	

		// $page->lastSuffix=false;

		$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>條記錄    第<b>%NOW_PAGE%</b>頁/共<b>%TOTAL_PAGE%</b>頁</li>' );

		$page->setConfig ( 'prev', '上一頁' );

		$page->setConfig ( 'next', '下一頁' );

		$page->setConfig ( 'last', '末頁' );

		$page->setConfig ( 'first', '首頁' );

		$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );

		;

	

		$show = $page->show (); // 分頁顯示輸出

		// 進行分頁數據查詢 注意limit方法的參數要使用Page類的屬性

		$list = $User->where ( $map )->order ( 'MA_ID DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();

		//dump($list);die;

		$this->assign ( 'list', $list ); // 賦值數據集

		$this->assign ( 'page', $show ); // 賦值分頁輸出

	

	

		$userData = M ( 'user' )->where ( array (

				'UE_ID' => $_SESSION ['uid']

		) )->find ();

		$this->userData = $userData;

		$this->display ( 'lxwm' );

	}

	

	public function lxwmcl() {

	

		if (IS_POST) {

			$data_P = I ( 'post.' );

			//dump($data_P);die;

			//$this->ajaxReturn($data_P['ymm']);die;

			//$user = M ( 'user' )->where ( array (

			//		UE_account => $_SESSION ['uname']

			//) )->find ();

	

			$user1 = M ();

			//! $this->check_verify ( I ( 'post.yzm' ) )

			//! $user1->autoCheckToken ( $_POST )

			if (strlen($data_P['lybt']) > 190 || strlen($data_P['lybt']) < 1) {

				die("<script>alert('留言标题不能为空！');history.back(-1);</script>");

				

			} elseif ( strlen($data_P['lynr']) < 1) {

				die("<script>alert('留言內容不能为空！');history.back(-1);</script>");

				

			}else {

				

					 

				$record['MA_type']		= 'message';

				$record['MA_userName']	= $_SESSION['uname'];

				$record['pic']	= $data_P['face180'];

				$record['MA_otherInfo']	= $data_P['otlylx'];

				$record['MA_theme']		= $data_P['lybt'];

				$record['MA_note']		= $data_P['lynr'];

				$record['MA_time']		= date ( 'Y-m-d H:i:s', time () );;

						

				$reg = M ( 'message' )->add ( $record );

					

	

					 

	

					if ($reg) {

						die("<script>alert('留言成功！');history.back(-1);</script>");

						

					} else {

						die("<script>alert('留言失败！');history.back(-1);</script>");

						

					}

				

			}

		}

	}

	public function lxwmdel() {

		if (!preg_match ( '/^[0-9]{1,10}$/', I ('get.id') )) {

			$this->success('非法操作,將凍結賬號!');

		}else{

			$userinfo = M ( 'message' )->where ( array ('MA_ID' => I ('get.id')) )->find ();

			//dump(I ('get.id'));

			//dump($userinfo['ue_accname']);die;

			if ($userinfo['ma_username']<>$_SESSION ['uname']) {

				$this->success('非法操作,將凍結賬號!');

			}else{

				$reg = M ( 'message' )->where(array ('MA_ID' => I ('get.id')))->delete();

				if ($reg) {

					$this->success('刪除成功!');

				}else {

					$this->success('刪除失敗!');

				}

			}

		}

	}

	public function lxwmx() {

		

		if (!preg_match ( '/^[0-9]{1,10}$/', I ('get.id') )) {

			$this->success('非法操作,將凍結賬號!');

		}else{

			$id = I ( 'get.id' );

		$data = M ( 'message' )->where ( array (

				'MA_ID' => $id,

				'MA_userName'=>$_SESSION['uname']

		) )->find ();

		//dump($data);die;

		$this->data = $data;

		$this->display ( 'lxwmx' );

		}

	}

	public function yjsb() {



		$User = M ( 'user' ); // 實例化User對象

		//$suser = I ( 'post.user', '', '/^[a-zA-Z0-9]{6,12}$/' );

		//if($suser==''){

			$map ['zbzh'] = $_SESSION ['uname'];

			$map ['zbqx'] = '1';

			$map ['UE_money'] = array('egt','100');

		//}else{

			//$map ['UE_accName'] = $suser;

		//}

		//dump($map ['UE_accName']);die;

		

		//	$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));

		

		// 		if (! empty ( $date1 ) && ! empty ( $date2 )) {

		// 			$map ['UG_getTime'] = array (

		// 					array (

		// 							'gt',

		// 							$date1

		// 					),

		// 					array (

		// 							'lt',

		// 							$date2

		// 					),

		// 					'and'

		// 			);

		// 		}

		

		//$map  = array('tjj','kdj','glj');

		//	$map['UE_Faccount']  = $_SESSION ['uname']

		//$ljtc1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>array('IN',$map)))->sum('UG_money');

		

		$count = $User->where ( $map )->count (); // 查詢滿足要求的總記錄數

		$page = new \Think\Page ( $count, 12 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

		

		// $page->lastSuffix=false;

		$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>條記錄    第<b>%NOW_PAGE%</b>頁/共<b>%TOTAL_PAGE%</b>頁</li>' );

		$page->setConfig ( 'prev', '上一頁' );

		$page->setConfig ( 'next', '下一頁' );

		$page->setConfig ( 'last', '末頁' );

		$page->setConfig ( 'first', '首頁' );

		$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );

		;

		

		$show = $page->show (); // 分頁顯示輸出

		// 進行分頁數據查詢 注意limit方法的參數要使用Page類的屬性

		$list = $User->where ( $map )->order ( 'UE_ID DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();

		$this->assign ( 'list', $list ); // 賦值數據集

		$this->assign ( 'page', $show ); // 賦值分頁輸出

		

		

		$userData = M ( 'user' )->where ( array (

				'UE_ID' => $_SESSION ['uid']

		) )->find ();

		$this->userData = $userData;



			$this->display ( 'yjsb' );

		

	}

	

	public function yjzbcl() {

	

 		if (I ('post.id')<>1 ) {

 			$this->success('非法操作,將凍結賬號!');

 		}else {

 			

 			$user = M('user');

 			$map ['zbzh'] = $_SESSION ['uname'];

 			$map ['zbqx'] = '1';

 			$map ['UE_money'] = array('egt','100');

 			

 			$sbname=$user->where($map)->getField('ue_account',true);

 			if(count($sbname)==0){

 				$this->success( '目前沒有可代收幣賬號!' );

 			}else{

 			$xlhaaa = 0 ;

 			$moneyhe = 0 ;

 			foreach($sbname as $val){

 				$xlhaaa = $xlhaaa + 1 ;

 				$zbzqye=$user->where(array('UE_account'=>$val))->getField('ue_money');

 				$dqzhye=$user->where(array('UE_account'=>$_SESSION['uname']))->getField('ue_money');

 				$jiabi = floor($zbzqye/100)*100;

 				$moneyhe += $jiabi;

 				

 				$reg6 = $user->where(array ('UE_account' => $val ) )->setDec('UE_money',$jiabi);

 				$reg1 = $user->where(array ('UE_account' => $_SESSION['uname'] ) )->setInc('UE_money',$jiabi);

 				

 				$date_dq = date ( 'Y-m-d H:i:s', time () );

 				$record1 ["UJ_usercount"] = $val; // 登入的賬戶

 				$record1 ["UJ_payaccount"] = $val; // 轉出賬戶

 				$record1 ["UJ_addaccount"] = $_SESSION['uname']; // 轉入賬戶

 				$record1 ["UJ_JBcount"] = $jiabi; // 轉出轉入數量

 				$record1 ["UJ_note"] = '一鍵轉幣'; // 備註

 				$record1 ["UJ_style"] = '轉出'; // 類型

 				$record1 ["UJ_balance"] = $zbzqye-$jiabi; // 餘額

 				$record1 ["UJ_dataType"] = 'zs'; // 轉賬類型

 				$record1 ["UJ_time"] = date ( 'Y-m-d H:i:s', time () ); // 轉賬類型

 				$reg2 = M ( 'userjyinfo' )->add ( $record1 );

 					

 				$record2 ["UJ_usercount"] = $_SESSION['uname']; // 登入的賬戶

 				$record2 ["UJ_payaccount"] = $val; // 轉出賬戶

 				$record2 ["UJ_addaccount"] = $_SESSION['uname']; // 轉入賬戶

 				$record2 ["UJ_JBcount"] = $jiabi; // 轉出轉入數量

 				$record2 ["UJ_note"] = '一鍵轉幣'; // 備註

 				$record2 ["UJ_style"] = '轉入'; // 類型

 				$record2 ["UJ_balance"] = $dqzhye+$jiabi; // 餘額

 				$record2 ["UJ_dataType"] = 'zs'; // 轉賬類型

 				$record2 ["UJ_time"] = date ( 'Y-m-d H:i:s', time () ); // 轉賬類型

 				$reg3 = M ( 'userjyinfo' )->add ( $record2 );

 					

 				$note3 = "玩家" . $val . "一鍵轉幣" . $jiabi . "個，轉入成功";

 				$record3 ["UG_account"] = $_SESSION['uname']; // 登入轉出賬戶

 				$record3 ["UG_type"] = '轉入';

 				$record3 ["UG_money"] = $jiabi; // 金幣

 				$record3 ["UG_allGet"] = $jiabi; //

 				$record3 ["UG_balance"] = $dqzhye+$jiabi; // 當前推薦人的金幣餘額

 				$record3 ["UG_dataType"] = 'jbzr'; // 金幣轉出

 				$record3 ["UG_note"] = $note3; // 推薦獎說明

 				$record3["UG_getTime"]		= $date_dq; //操作時間

 				$reg4 = M ( 'userget' )->add ( $record3 );

 					

 				$note4 = "給玩家" . $_SESSION['uname'] . "轉賬金幣" . $jiabi . "個，提交成功";

 				$record4 ["UG_account"] = $val; // 登入轉出賬戶

 				$record4 ["UG_type"] = '轉出';

 				$record4 ["UG_money"] = $jiabi; // 金幣

 				$record4 ["UG_allGet"] = $jiabi; //

 				$record4 ["UG_balance"] = $zbzqye-$jiabi; // 當前推薦人的金幣餘額

 				$record4 ["UG_dataType"] = 'jbzc'; // 金幣轉出

 				$record4 ["UG_note"] = $note4; // 推薦獎說明

 				$record4["UG_getTime"]		= $date_dq; //操作時間

 				$reg5 = M ( 'userget' )->add ( $record4 );

 				

 			}

 			if ($reg6 && $reg1 && $reg2 && $reg3 && $reg4 && $reg5) {

 				$this->success( "恭喜一鍵轉幣成功!共轉出".$xlhaaa."個賬號,金額總數為".$moneyhe."個金幣",'',10);

 			} else {

 				$this->success( '轉賬失敗!' );

 			}

 			

 			

 			}

 			

 			

 			

 			

 			

 			

// 			$id = I ( 'get.id' );

// 			$data = M ( 'message' )->where ( array (

// 					'MA_ID' => $id,

// 					'MA_userName'=>$_SESSION['uname']

// 			) )->find ();

// 			//dump($data);die;

// 			$this->data = $data;

// 			$this->display ( 'lxwmx' );

         //   $this->success('一鍵收幣成功');

 		}

	}

	public function fh() {

	

		$userData = M ( 'user' )->where ( array (

				'UE_ID' => $_SESSION ['uid']

		) )->find ();

		$otconfig = M ( 'system' )->where ( array (

				'SYS_ID' => 1

		) )->find ();

		

		$this->userData = $userData;

		$this->otconfig = $otconfig;

		$this->display ( 'fh' );

	}

	

	public function fhcl() {

		$otconfig = M ( 'system' )->where ( array (

				'SYS_ID' => 1

		) )->find ();

			

		//$data_P = I ( 'post.' );

		//當前賬號信息

		$user = M ( 'user' )->where ( array ('UE_account' => $_SESSION ['uname'] ) )->find ();

		$user1 = M ();

		// dump(I ( 'post.yzm' ));die;

		//

		//dump($user ['zsbhe']);die;

		if ($user ['zsbhe'] < $otconfig['a_fuhuo']) {

				$this->success ( '餘額不足!');

	

			} else {

		//要激活賬號信息

		//$wjbhname = M ( 'user' )->where ( array ('UE_account' => $data_P ['wjbhname'] ) )->find ();

				//報單中心信息

				//$bdzxname = M ( 'user' )->where ( array ('UE_account' => $data_P ['bdzxname'] ) )->find ();

				//報單中心許可權

				//$bdzx_rs = M ( 'user' )->where ( array ('UE_accName' => $data_P ['bdzxname'],'UE_Faccount'=>'0','UE_check'=>'1','UE_stop'=>'1' ) )->count("UE_ID");

				//dump($bdzx_rs)

				//echo ($bdzx_rs);die;

				if ($user ['ue_stop'] <> 0) {

				$this->success ( '您的賬號未出局,不需要復活!');

						

				} else {

					//寫入數據開始

				$date_dq = date ( 'Y-m-d H:i:s', time () );

				$reg10 = M('user')->where(array ('UE_account' => $_SESSION ['uname'] ) )->setDec('zsbhe',$otconfig['a_fuhuo']);

				$user = M ('user' )->where ( array ('UE_account' => $_SESSION ['uname'] ) )->find ();

				$note1="賬號復活成功";

						$record1["UG_account"]	= $_SESSION ['uname'];

						$record1["UG_type"]  	= 'zsb';

						$record1["zsb"] 	= $otconfig['a_fuhuo']-$otconfig['a_fuhuo']*2; //金幣

						//$record1["UG_allGet"]	= '1500'; //推薦獎金總的

						$record1["zsbhe"]	= $user['zsbhe']; //當前推薦人的金幣餘額

								$record1["UG_dataType"]	= 'tjfy'; //當前開單人的金幣餘額

					$record1["UG_note"]		= $note1; //推薦獎說明

						$record1["UG_getTime"]		= $date_dq; //操作時間

						$reg1 = M ( 'userget' )->add ( $record1 );

						

						

						$note1="復活記錄";

						$record1["UG_account"]	= $_SESSION ['uname'];

						$record1["UG_type"]  	= '復活記錄';

						$record1["zsb"] 	= $otconfig['a_fuhuo']; //金幣

						//$record1["UG_allGet"]	= '1500'; //推薦獎金總的

						$record1["zsbhe"]	= $user['zsbhe']; //當前推薦人的金幣餘額

						$record1["UG_dataType"]	= 'fuhuojl'; //當前開單人的金幣餘額

						$record1["UG_note"]		= $note1; //推薦獎說明

						$record1["UG_getTime"]		= $date_dq; //操作時間

						$reg1 = M ( 'userget' )->add ( $record1 );

							

	

									$reg14=M('user')->where(array('UE_account'=>$_SESSION ['uname']))->save(array('UE_stop'=>'1'));

										

									if($reg10 && $reg1 && $reg14){

									$this->success( '復活成功!',U('/Home/Myuser/fhjl'));

									}else{

						$this->success ( '復活失敗!');

									}

									}

										

									}

	}

	

	

	

	//認購

	

	public function rg() {

	

		

		$otconfig = M ( 'system' )->where ( array (

				'SYS_ID' => 1

		) )->find ();

		$this->otconfig =$otconfig;

	

		$User = M ( 'userjyinfo' ); // 實例化User對象

		

	$map ['UJ_usercount'] = $_SESSION ['uname'];

	$map ['UJ_dataType'] = 'rg';

	

	

	

	

	$count = $User->where ( $map )->count (); // 查詢滿足要求的總記錄數

	//dump($var)

	$page = new \Think\Page ( $count, 12 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

	

	// $page->lastSuffix=false;

	$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>條記錄    第<b>%NOW_PAGE%</b>頁/共<b>%TOTAL_PAGE%</b>頁</li>' );

	$page->setConfig ( 'prev', '上一頁' );

	$page->setConfig ( 'next', '下一頁' );

	$page->setConfig ( 'last', '末頁' );

	$page->setConfig ( 'first', '首頁' );

	$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );

	;

	

	$show = $page->show (); // 分頁顯示輸出

	// 進行分頁數據查詢 注意limit方法的參數要使用Page類的屬性

	$list = $User->where ( $map )->order ( 'UJ_ID DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();

	

	//dump($list);die;

	

	$this->assign ( 'list', $list ); // 賦值數據集

	$this->assign ( 'page', $show ); // 賦值分頁輸出

	

	

		$userData = M ( 'user' )->where ( array (

				'UE_ID' => $_SESSION ['uid']

		) )->find ();

		$this->userData = $userData;

		$this->display ( 'rg' );

	}

	

	public function rgcl() {

	

		if (IS_AJAX) {

			$data_P = I ( 'post.' );

			//dump($data_P);die;

			//$this->ajaxReturn($data_P['ymm']);die;

			$user = M ( 'user' )->where ( array (

					UE_account => $_SESSION ['uname']

			) )->find ();

			$otconfig = M ( 'system' )->where ( array (

					'SYS_ID' => 1

			) )->find ();

			$user1 = M ();

			

			if( $data_P['leixin']=='zsb'){

			//! $this->check_verify ( I ( 'post.yzm' ) )

			//! $user1->autoCheckToken ( $_POST )

			//dump($data_P['lxfs']);die;

			//$zkbsl = $data_P ['mcsl'] +$data_P ['mcsl'] *$otconfig['a_sxf'];

			if ($user['btbdz']=='0') {

				$this->ajaxReturn ( array ('nr' => '您暫無認購許可權!','sf' => 0 ) );

			}elseif (! preg_match ( '/^[0-9.]{3,10}$/', $data_P ['mrsl'] )) {

				$this->ajaxReturn ( array ('nr' => '認購鑽石幣金額必須是大於500,並且是500倍數!','sf' => 0 ) );

			}elseif ($data_P ['mrsl'] % 500 > 0 ||$data_P ['mrsl']=='') {

				$this->ajaxReturn ( array ('nr' => '認購鑽石幣金額必須是大於500,並且是500倍數!','sf' => 0 ) );

			} else {

	            $btbsl=$data_P ['mrsl']*$otconfig['a_zsbhuilv'];

	          //  dump($btbsl);die;

				//$this->ajaxReturn ( '認購鑽石幣成功!' );

				$record["UJ_usercount"]	    = $_SESSION ['uname'];//登入的賬戶

				$record["UJ_JBcount"] 	    = $data_P ['mrsl'];	//賣出的數量

				$record["UJ_style"]	        = 'rgzsb';      //類型

				$record["UJ_balance"]	    = $btbsl;      //餘額

				$record["UJ_note"]         = $user['btbdz'];

				$record["UJ_dataType"]      = 'rg';

				$record["UJ_jbmcStage"]      = '0';

				$record ["UJ_time"] = date ( 'Y-m-d H:i:s', time () );

				$reg = M ( 'userjyinfo' )->add ( $record );

	            

			if ($reg) {

						$this->ajaxReturn ( array ('nr' => '認購成功!','sf' => 0 ) );

					} else {

						$this->ajaxReturn ( array ('nr' => '認購失敗!','sf' => 0 ) );

					}

	

			}

		}else{

			if ($user['btbdz']=='') {

				$this->ajaxReturn ( array ('nr' => '您暫無認購許可權!','sf' => 0 ) );

			}elseif (! preg_match ( '/^[0-9.]{3,10}$/', $data_P ['mrsl'] )) {

				$this->ajaxReturn ( array ('nr' => '銀幣認購金額必須是大於100,並且是100倍數!','sf' => 0 ) );

			}elseif ($data_P ['mrsl'] % 100 > 0 ||$data_P ['mrsl']=='') {

				$this->ajaxReturn ( array ('nr' => '銀幣金額必須是大於100,並且是100倍數!','sf' => 0 ) );

			} else {

				$btbsl=$data_P ['mrsl']*$otconfig['a_ybhuilv'];

				//  dump($btbsl);die;

				//$this->ajaxReturn ( '認購鑽石幣成功!' );

				$record["UJ_usercount"]	    = $_SESSION ['uname'];//登入的賬戶

				$record["UJ_JBcount"] 	    = $data_P ['mrsl'];	//賣出的數量

				$record["UJ_style"]	        = 'rgyb';      //類型

				$record["UJ_balance"]	    = $btbsl;      //餘額

				$record["UJ_note"]         = $user['btbdz'];

				$record["UJ_dataType"]      = 'rg';

				$record["UJ_jbmcStage"]      = '0';

				$record ["UJ_time"] = date ( 'Y-m-d H:i:s', time () );

				$reg = M ( 'userjyinfo' )->add ( $record );

				 

				if ($reg) {

					$this->ajaxReturn ( array ('nr' => '認購成功!','sf' => 0 ) );

				} else {

					$this->ajaxReturn ( array ('nr' => '認購失敗!','sf' => 0 ) );

				}

			

			}

		}

			

		}

	}

	public function team() {

		$this->display("team");

	}

	

	

	public function xm() {

	

		if (IS_AJAX) {

			$data_P = I ( 'post.' );

			//dump($data_P);

			//$this->ajaxReturn($data_P['ymm']);die;

			//$user = M ( 'user' )->where ( array (

			//		UE_account => $_SESSION ['uname']

			//) )->find ();

				

			$user1 = M ();

			//! $this->check_verify ( I ( 'post.yzm' ) )

			//! $user1->autoCheckToken ( $_POST )

			if (false) {

					

				$this->ajaxReturn ( array ('nr' => '驗證碼錯誤!','sf' => 0 ) );

			} else {

				$addaccount = M ( 'user' )->where ( array (UE_account => $data_P ['dfzh']) )->find ();

	

				if (!$addaccount) {

					$this->ajaxReturn ( array ('nr' => '用戶名不存在!','sf' => 0 ) );

				}elseif($addaccount['ue_theme']==''){

					$this->ajaxReturn ( array ('nr' => '對方未設置名稱!','sf' => 0 ) );

				} else {

	

					$this->ajaxReturn (array ('nr' => '用户存在!','sf' => 1 ));

				}

			}

		}

	}


	public function getMessages(){

		$User = M ( 'message' ); // 實例化User對象

		//$suser = I ( 'post.user', '', '/^[a-zA-Z0-9]{6,12}$/' );

		

			$map ['MA_userName'] = $_SESSION ['uname'];

			$map['zt'] = 1;





		

	

		$count = $User->where ( $map )->count (); // 查詢滿足要求的總記錄數

		//dump($var)

		$page = new \Think\Page ( $count, 12 ); // 實例化分頁類 傳入總記錄數和每頁顯示的記錄數(25)

	

		// $page->lastSuffix=false;

		$page->setConfig ( 'header', '<li class="rows">共<b>%TOTAL_ROW%</b>條記錄    第<b>%NOW_PAGE%</b>頁/共<b>%TOTAL_PAGE%</b>頁</li>' );

		$page->setConfig ( 'prev', '上一頁' );

		$page->setConfig ( 'next', '下一頁' );

		$page->setConfig ( 'last', '末頁' );

		$page->setConfig ( 'first', '首頁' );

		$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );

		;

	

		$show = $page->show (); // 分頁顯示輸出

		// 進行分頁數據查詢 注意limit方法的參數要使用Page類的屬性

		$list = $User->where ( $map )->order ( 'MA_ID DESC' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();

		//dump($list);die;
	

		$this->assign ( 'list', $list ); // 賦值數據集

		$this->assign ( 'page', $show ); // 賦值分頁輸出

	

	

		$userData = M ( 'user' )->where ( array (

				'UE_ID' => $_SESSION ['uid']

		) )->find ();



		$this->display();
	}

	

	

	

	

}