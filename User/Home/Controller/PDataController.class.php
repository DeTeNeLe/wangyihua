<?php



namespace Home\Controller;



use Think\Controller;



class PDataController extends CommonController {

	// 首頁

	public function index() {

		$suser = I ( 'post.user' );

		if($suser==''){
			$map['UE_account']=array('neq','admin@qq.com');
		}else{
			$map['UE_account']=$suser;
		}
		//////////////////----------
		$User = M ( 'user' ); 

		$count = $User->where ( $map )->count (); 


		$p = getpage($count,10);

		$list = $User->where ( $map )->order ( 'UE_ID DESC' )->limit ( $p->firstRow, $p->listRows )->select ();
		foreach ($list as $key => $value) {
            $list[$key]['ue_accname'] = str_replace(substr($list[$key]['ue_accname'],3,4),'****',$list[$key]['ue_accname']);
			$list[$key]['ue_account'] = str_replace(substr($list[$key]['ue_account'],3,4),'****',$list[$key]['ue_account']);
			$list[$key]['zcr'] = str_replace(substr($list[$key]['zcr'],3,4),'****',$list[$key]['zcr']);
		}

		$this->assign ( 'list', $list );

		$this->assign ( 'page', $p->show() );
		
		$userData = M ( 'user' )->where ( array ('UE_ID' => $_SESSION ['uid'] ) )->find ();

		$this->userData = $userData;

		$this->pdata = true;

		$this->display ( 'user' );

	}


	public function tgbzlist() {

		//////////////////----------
		$tgbz = M ( 'tgbz' ); 

		$start = I('post.start');
        $end = I('post.end');

        $map["qr_zt"] = array('eq',0);
		if(!empty($start)&&!empty($end))
        {
            $map['date'] = array('between',array($start." 00:00:00",$end." 23:59:59"));
			$this->assign ( 'start', $start );
			$this->assign ( 'end', $end );
			$s_sum = $tgbz->where ( $map )->sum('jb'); 
			$this->assign ( 's_sum', $s_sum );
        }

		$count = $tgbz->where ( $map )->count (); 

		$p = getpage($count,10);

		$list = $tgbz->where ( $map )->order ( 'date DESC' )->limit ( $p->firstRow, $p->listRows )->select ();

		$this->assign ( 'list', $list );

		$this->assign ( 'page', $p->show() );
		
		$userData = M ( 'user' )->where ( array ('UE_ID' => $_SESSION ['uid'] ) )->find ();

		$this->userData = $userData;

		$this->pdata = true;

		$this->display ( 'tgbzlist' );

	}

	public function jsbzlist() {

        $tgbzid = I('get.tgbzid');
		$_SESSION['user_zdpp_tgbzid'] = $tgbzid;

        if($tgbzid)
		{
		   $priority = getUserInEnabled();
		   if($priority == 0)
			  $this->error('抱歉，当前不在进场时间范围');
		}

		//////////////////----------left(md5(`id`),6)
		$jsbz = M ( 'jsbz' ); 

		$search = I('post.search');
		$start = I('post.start');
        $end = I('post.end');

        $map["zt"] = array('eq',0);

		if(!empty($search))
        {
			$where1['_logic'] = 'or';
            $where1['user'] = $search;
			$where1['left(md5(`id`),6)'] = $search;
			$map['_complex'] = $where1;
			$this->assign ( 'search', $search );
        }

		if(!empty($start)&&!empty($end))
        {
			$where2['_logic'] = 'or';
            $where2['date'] = array('between',array($start." 00:00:00",$end." 23:59:59"));
			$map['_complex'] = $where2;
			$this->assign ( 'start', $start );
			$this->assign ( 'end', $end );
        }

        if($where2 != "" || $where1 != "")
		{
		  $s_sum = $jsbz->where ( $map )->sum('jb'); 
		  $this->assign ( 's_sum', $s_sum );
		}

		$count = $jsbz->where ( $map )->count (); 

		$p = getpage($count,10);

		$list = $jsbz->where ( $map )->order ( 'date asc' )->limit ( $p->firstRow, $p->listRows )->select ();

		$this->assign ( 'list', $list );

		$this->assign ( 'page', $p->show() );
		
		$userData = M ( 'user' )->where ( array ('UE_ID' => $_SESSION ['uid'] ) )->find ();

		$this->userData = $userData;

		$this->pdata = true;

		$this->display ( 'jsbzlist' );

	}
}