<?php
namespace Home\Controller;
use Think\Controller;
use Think\Think;

class IndexController extends CommonController {

	private $lockTableFile = NULL;
    // 首页
    public function index()
	{

        $this->redirect('/Home/Index/home');
    }

	// qkl
    public function qkl()
	{
        $this->display();
    }

	public  function home_post()
	{
		if (IS_POST) {
			$act = I('post.act');
			if($act == "buyincon")
			{
				 //直推人数
				 $this->zhitui_num = M('user')->where(array('UE_accName' => $_SESSION['uname'], "UE_check" => "1"))->count();
				 $this->jingli_all = M('user_jl')->where(array('user' => $_SESSION['uname']))->sum('jb');
				 $this->tuijian_all = M('userget')->where(array('user' => $_SESSION['uname'], 'UE_dataType' => 'tjj'))->sum('yb');
				 $this->touzi_all = M('tgbz')->where(array('user' => $_SESSION['uname']))->sum('jb');
				 $this->all_all = $this->touzi_all;
				 $zhitui_num = $this->zhitui_num;
				 //echo $zhitui_num;
				 if(C('open_zhitui_add_money') == "1")
				 {
				    if ($zhitui_num == '0') {
              	      $my_member_min = C('my_member_min');
              	      $my_member_max = C('my_member_max');
				    } elseif ($zhitui_num == '1') {
              	      $my_member_min = C('my_member_min1');
              	      $my_member_max = C('my_member_max1');
				    } elseif ($zhitui_num == '2') {
             	       $my_member_min = C('my_member_min2');
              	      $my_member_max = C('my_member_max2');
				    } elseif ($zhitui_num == '3') {
              	      $my_member_min = C('my_member_min3');
              	      $my_member_max = C('my_member_max3');
				    } else {
              	      $my_member_min = C('jj01s');
				   	  $my_member_max = C('jj01m');
				    }
				 }else
				 {
				      $my_member_min = C('jj01s');
				      $my_member_max = C('jj01m');
				 }
				 //每完成n轮,额度提升M元(1000的倍数)
				 $my_member_max += getUserAddByCircle();
				 $limit_amount = get_tg_min_compare_last();
				 if($my_member_min < $limit_amount)
					 $my_member_min =  $limit_amount;
				 $this->assign('my_member_min', $my_member_min);
				 $this->assign('my_member_max', $my_member_max);


				 //可选的投资门槛
                $can_choice_jb_arr = can_choice_jb();
                $this->assign('can_choice_jb_arr',$can_choice_jb_arr);

				 //pin值
                 $this->pin_zs = get_userinfo($_SESSION['uname'],'pdmnum');

				 $this->display("act/buyincon");
			}elseif($act == "selloutcon")
			{
			    //交割条件
                //1.红利积分150 【满足得到红利积分，例如1000的15%即150】
                //2.该笔订单（如1000元）的订单完成交易（完成打款）
                //3.交割当前（1150元）必须再开仓20%，且打款成功


				 //获取诚信奖钱包总额;
				 $data['UG_account'] = $_SESSION['uname'];
				 $chenxin_total = M('user')->where(array('UE_account' => $_SESSION['uname']))->sum('jifen');
				 $benxi_cash = M('user')->where(array('UE_account' => $_SESSION['uname']))->sum('ue_money');
				 $jiangli_cash = M('user')->where(array('UE_account' => $_SESSION['uname']))->sum('qwe');
				 $this->assign('benxi_cash', (int)$benxi_cash);
				 $this->assign('jiangli_cash', (int)$jiangli_cash);

				 $this->display("act/selloutcon");
			}elseif($act == "fangzhuang")
			{
				 $map['fangzhuang'] = I('post.do');
				 //防撞单
                 $tgbz= M('tgbz');
                 $where=array();
                 $where['user'] = $_SESSION ['uname'];
                 $where['qr_zt'] =  array('eq',0);
                 $count = $tgbz->where($where)->count();
				 $fangzhuang = get_userinfo($_SESSION['uname'],'fangzhuang');
                 if ($map['fangzhuang'] == '1' && $count >=2)
                 {
			        $this->ajaxReturn(array('nr' => "存在多笔订单，无法防撞", 'sf' => 0));
                 }
                 $savetg = M('user')->where(array('UE_ID' => $_SESSION['uid']))->save($map);
				 if($savetg)
					 $this->ajaxReturn(array('nr' => $map['fangzhuang'] == 1 ? '已设置防撞' : '已取消防撞', 'sf' => 1));
				 else
					 $this->ajaxReturn(array('nr' => '设置防撞失败', 'sf' => 0));
			}elseif($act == "updateyystaus")
			{
				$map['isyuyue'] = I('post.do');
				if($map['isyuyue']  == 1 && getUserTGBZCount() == 0)
					$this->ajaxReturn(array('nr' => '启用预约必须有过挂单', 'sf' => 0));
                $savetg = M('user')->where(array('UE_ID' => $_SESSION['uid']))->save($map);
				if($savetg)
					 $this->ajaxReturn(array('nr' => $map['isyuyue'] == 1 ? '预约开启成功' : '预约已关闭', 'sf' => 1));
				else
					 $this->ajaxReturn(array('nr' => '设置预约失败', 'sf' => 0));
			}elseif($act == "updateyyset")
			{
                 $state = I('post.state');
                 if($state != 1){
                     $this->ajaxReturn(array('nr' => "你还没有开启排单状态", 'sf' => 0));
                 }
				 $map['yuyuezhouqi'] = I('post.d');
				 $map['yuyuemoney'] = I('post.m');
                 $cur_yuyuezhouqi = $map['yuyuezhouqi'];
                 $cur_yuyuemoney = $map['yuyuemoney'];

				 $tg_min = get_min();
				 $tg_max = get_max();

				 if ($map['yuyuemoney'] < $tg_min || $map['yuyuemoney'] > $tg_max || $map['yuyuemoney'] % C("jj01") > 0)
				 {
				     $this->ajaxReturn(array('nr' => "预约金额" . $tg_min . "-" . $tg_max . ",并且是" . C("jj01") . "的倍数！", 'sf' => 0));
                 } elseif ($map['yuyuemoney'] % C("jj01") > 0) {
				    $this->ajaxReturn(array('nr' => "预约金额" . $tg_min . "-" . $tg_max . ",并且是" . C("jj01") . "的倍数！", 'sf' => 0));
                 }

                 //判断当前用户是否有开仓完成且支付完成开仓订单，未支付完成平仓订单的记录
                ///dump(get_exists_yfkzfwc());die;
                if( get_exists_yfkzfwc() == 0 )
                {
                    $this->ajaxReturn(array('nr' => "还没有开仓的订单", 'sf' => 0));
                }


                /*
                $cur_user_data = M('user')->where(array('UE_ID' => $_SESSION['uid']))->find();
                if( $cur_user_data['isyuyue'] == 1 ){
                   $this->error('您已设置预约，咱不能操作');
                }
                */

                //设置自动预约时扣除相应时长的通证码 start----------------------
                $yuyue_cur_time = I('post.t');
                if(!preg_match('/^\d{4}[-]\d{2}[-]\d{2}$/',$yuyue_cur_time)){
                    $this->ajaxReturn(array('nr' => "您填写的预约时间格式有误，请按照".date('Y-m-d H:i:s',time()).'形式填写', 'sf' => 0));
                }
                $paidan_time_start = C('paidan_time_start');//后台设置的开仓时间
                $paidan_time_end = C('paidan_time_end');//后台设置的开仓时间的截至时间
                $yuyue_cur_time = $yuyue_cur_time." ".$paidan_time_start.':00:00';//设置的预约时间

                //预约的时间必须要大于今天
                $next_day_time = time() + 24*3600;
                $next_day_start = date('Y-m-d',$next_day_time).' 00:00:00';
                if( $yuyue_cur_time < $next_day_start ){
                    $this->ajaxReturn(array('nr' => '预约时间需要大于今天才可以', 'sf' => 0));
                }

                //qiantai_num今天预约的最大人数
                $qiantai_num = C('qiantai_num');
                //通过预约扣除通证的记录获取
                $paidanModel = M('paidan_log');
                $paidanWhere= [];
                $paidanWhere['type'] = 'yypd';
                $today_start_date = date('Y-m-d',time()).' 00:00:00';
                $today_end_date = date('Y-m-d',time()).' 23:59:59';
                $paidanWhere['date'] = array('between',array($today_start_date,$today_end_date));
                $paidan_count = $paidanModel->where($paidanWhere)->count();
                $paidan_all_user = $paidanModel->field('user')->where($paidanWhere)->select();//今天预约的所有用户
                $paidan_count++;
                if( $paidan_count > $qiantai_num )
                {
                    $this->ajaxReturn(array('nr' => "今天预约的人数已达最大值，不能再预约了", 'sf' => 0));
                }

                //get_amount今天预约的总额的最大值【例如：10个人，没人预约1000，都预约了15天，那就是15000的总额】
                $get_amount = C('get_amount');
                $paidan_all_user_arr = [];//所有今天预约的用户账号列表
                foreach( $paidan_all_user as $item )
                {
                    $paidan_all_user_arr[] = $item['user'];
                }

                if( !empty($paidan_all_user_arr) )
                {
                    $total_today_yy_jb = 0;//今天预约的总额
                    $yy_where = [];
                    $yy_where['UE_account'] = array('in',$paidan_all_user_arr);
                    $all_yy_user_info = M('user')->where($yy_where)->select();
                    foreach( $all_yy_user_info as $yy_user_info )
                    {
                        $total_today_yy_jb += $yy_user_info['yuyuemoney'] * $yy_user_info['yuyuezhouqi'];
                    }
                    $total_today_yy_jb += $cur_yuyuezhouqi * $cur_yuyuemoney;
                    if( $total_today_yy_jb > $get_amount ){
                        $this->ajaxReturn(array('nr' => "今天预约的金额已达最大值，不能再预约了", 'sf' => 0));
                    }
                }

                $user_data = M('user')->where(array('UE_ID' => $_SESSION['uid']))->find();
                //设置成功就扣除通证码设置时长的通证码
                $jhm_pass_rate = C('jhm_pass_rate');//排单码【通证积分】的比例
                $dec_all_tz = $map['yuyuezhouqi'] * $map['yuyuemoney'] * $jhm_pass_rate;
                if( $user_data['pdmnum'] < $dec_all_tz ){
                    $this->ajaxReturn(array('nr' => "通证积分不足，请充值或者调整时长再试", 'sf' => 0));
                }
                if( $user_data['isyuyue'] == 1 ){
                    $this->ajaxReturn(array('nr' => "您已经预约过，请不要再预约了", 'sf' => 0));
                }

                //防撞功能
                $tgbz= M('tgbz');
                $where=array();
                $where['user'] = $_SESSION ['uname'];
                $where['qr_zt'] =  array('eq',0);
                $where['isprepay'] = 1;//预付款完成否，防撞
                $where['isreset'] =  array('in',array(0,3));
                $rs=$tgbz->where($where)->find();
                if ($user_data['fangzhuang'] == 1 && $rs )
                {
                    $this->ajaxReturn(array('nr' => "已启用防撞单功能,您还有未完成的订单未处理，不能继续申请", 'sf' => 0));
                }

                //扣除排单码
                M('user')->where(array('UE_ID' => $_SESSION['uid']))->setDec('pdmnum',$dec_all_tz);

                //计算通证积分变动后后的级差奖励
                calculation_reward_pdnum($_SESSION['uid'],$dec_all_tz);

                $cur_update_arr = array(
                    //'yuyue_cur_time'=>date("Y-m-d H:i:s"),
                    'yuyue_cur_time'=>$yuyue_cur_time,//预约的起始时间
                    'isyuyue'=>1,
                );
                M('user')->where(array('UE_ID' => $_SESSION['uid']))->save($cur_update_arr);

                //增加扣除激活码的日志记录
                $map1['user'] = $_SESSION['uname'];
                $map1['type']= 'yypd';
                $map1['info']= '设置自动预约'.$map['yuyuezhouqi'].'天，扣除'.$dec_all_tz.'通证';
                $map1['num']= -$dec_all_tz;
                $map1['yue']= get_userinfo($_SESSION['uname'],'pdmnum');
                $map1['date']= date('Y-m-d H:i:s', time());
                M('paidan_log')->add($map1);
                //设置自动预约时扣除相应时长的通证码 end----------------------

                 $savetg = M('user')->where(array('UE_ID' => $_SESSION['uid']))->save($map);
				 if($savetg)
					 $this->ajaxReturn(array('nr' => '预约设置成功', 'sf' => 1));
				 else
					 $this->ajaxReturn(array('nr' => '预约设置成功', 'sf' => 0));
			}elseif($act == "tuoguan")
			{
				 $map['tuoguan'] = I('post.tuoguan');
                 $savetg = M('user')->where(array('UE_ID' => $_SESSION['uid']))->save($map);
				 if($savetg)
					 $this->ajaxReturn(array('nr' => $map['tuoguan'] == 1 ? '成功托管' : '已取消托管', 'sf' => 1));
				 else
					 $this->ajaxReturn(array('nr' => '托管失败', 'sf' => 0));
			}elseif($act == "viewin")
			{
				$orderid = I('post.orderid');

				$tgbz = M('tgbz');

				$map_main_tgbz['user'] = $_SESSION['uname'];
		        $map_main_tgbz['orderid'] = $orderid;

				$main_tgbz = $tgbz->where($map_main_tgbz)->find();
				$ppdd_list = M('ppdd')
					->alias('ppdd')
					->join("LEFT JOIN ot_tgbz tgbz on ppdd.p_id = tgbz.id where tgbz.user='"
					. $_SESSION['uname']
					. "' and mainid ="
					. $main_tgbz['mainid'])
					->select();
				$tgbz_ppjb = M('ppdd')
					->alias('ppdd')
					->join("LEFT JOIN ot_tgbz tgbz on ppdd.p_id = tgbz.id where tgbz.user='"
					. $_SESSION['uname']
					. "' and mainid ="
					. $main_tgbz['mainid'])
					->sum('tgbz.jb');
				$this->assign('ppdd_count', count($ppdd_list));
				$this->assign('tgbz_ppjb', $tgbz_ppjb);
				$ppdd_list = getPDinfo($ppdd_list);
				$this->assign('ppdd_list', $ppdd_list);
				$this->display("act/viewin");
			}elseif($act == "viewout")
			{
				$orderid = I('post.orderid');

				$jsbz = M('jsbz');

				$map_main_jsbz['user'] = $_SESSION['uname'];
		        $map_main_jsbz['orderid'] = $orderid;

				$main_jsbz = $jsbz->where($map_main_jsbz)->find();
				$ppdd_list = M('ppdd')
					->alias('ppdd')
					->join("LEFT JOIN ot_jsbz jsbz on ppdd.g_id = jsbz.id where jsbz.user='"
					. $_SESSION['uname']
					. "' and mainid ="
					. $main_jsbz['mainid'])
					->select();
				$jsbz_ppjb = M('ppdd')
					->alias('ppdd')
					->join("LEFT JOIN ot_jsbz jsbz on ppdd.g_id = jsbz.id where jsbz.user='"
					. $_SESSION['uname']
					. "' and mainid ="
					. $main_jsbz['mainid'])
					->sum('jsbz.jb');
				$this->assign('ppdd_count', count($ppdd_list));
				$this->assign('jsbz_ppjb', $jsbz_ppjb);
				$ppdd_list = getPDinfo($ppdd_list);
				$this->assign('ppdd_list', $ppdd_list);
				$this->display("act/viewout");
			}elseif($act == "viewinfo")
			{
				$pporderid = I('post.orderid');

		        $map['pporderid'] = $pporderid;

				$ppdd = M('ppdd')->where($map)->find();

				$tgbz_db = M('tgbz');
				$jsbz_db = M('jsbz');
				$user_db = M('user');

				$tgbz_data = $tgbz_db->where(array('id'=>$ppdd['p_id']))->find();
                $jsbz_data = $jsbz_db->where(array('id'=>$ppdd['g_id']))->find();
                $jsbz_user = $user_db->where(array('UE_account'=>$jsbz_data['user']))->field(array('UE_account','UE_ID','yhzhxx','UE_phone','yzf','weixin','UE_theme','zfb','yhmc','yhckr','yhzh','UE_accName'))->find();
			    $tgbz_user = $user_db->where(array('UE_account'=>$tgbz_data['user']))->field(array('UE_account','UE_ID','yhzhxx','UE_phone','yzf','weixin','UE_theme','zfb','yhmc','yhckr','yhzh','UE_accName'))->find();
                $jsbz_accname=  $user_db->where(array('UE_account'=>$jsbz_user['ue_accname']))->field('UE_theme,UE_phone')->find();
			    $tgbz_accname=  $user_db->where(array('UE_account'=>$tgbz_user['ue_accname']))->field('UE_theme,UE_phone')->find();

                if($ppdd['p_user'] == $_SESSION['uname'])
					$this->assign('is_p', 1);
				else
					$this->assign('is_p', 0);
				$this->assign('ppdd', $ppdd);
				$this->assign('tgbz_data', $tgbz_data);
				$this->assign('jsbz_data', $jsbz_data);
				$this->assign('jsbz_user', $jsbz_user);
				$this->assign('tgbz_user', $tgbz_user);
				$this->assign('jsbz_accname', $jsbz_accname);
				$this->assign('tgbz_accname', $tgbz_accname);
				$this->display("act/viewinfo");
			}elseif($act == "get_userinfo")
			{
				$u_p = I('post.u_p');
				$field = I('post.field');
				$result = get_userinfo($u_p,$field);
				if($result == '-1')
					return_die_ajax('没有查询到该会员');
				else
					return_die_ajax($result,true,1);
			}elseif($act == "jihuo")
			{
				$uname = I('post.uname');
				if(C('jihuo_limit_day') > 0)
		        {
		           $getTodayJHMUsedCount = getTodayJHMUsedCount();
		           if($getTodayJHMUsedCount >=  C('jihuo_limit_day'))
			       {
					  return_die_ajax('当日激活人数已满，请明天再来激活！');
			       }
		        }

                //用户
                $jhuser = M('user')->where(array('UE_account'=>$uname))->find();

                if(!$jhuser)
				{
				   return_die_ajax('要激活的用户不存在!');
                }

				if($jhuser['ue_check'] == 1)
				{
				   return_die_ajax('用户已是激活状态！');
                }

                $jhmnum = get_userinfo($_SESSION['uname'],'jhmnum');
                if($jhmnum < 1)
				{
				  return_die_ajax('你还没有激活码，请购买激活码后激活！');
                }

                $result = M('user')->where(array('UE_account' =>$_SESSION['uname']))->setDec('jhmnum', 1);
				$result = M('user')->where(array('UE_account' =>$uname))->save(array('UE_check'=>1));
				$result = M('user')->where(array('UE_account' =>$uname))->save(array('jihuo_time'=>date('Y-m-d H:i:s', time())));
				$now = date('Y-m-d H:i:s', time());
				M('user')->execute("update ot_user set next_tgbz_time = date_add('". $now ."', interval " . C('jihuo_feng_days') ." day)  where UE_account = '" . $uname. "'");
                if($result)
				{
				    $map['user'] = $_SESSION['uname'];
				    $map['type']= 'jh';
                    $map['info']= '激活用户' . $uname;
                    $map['num']= -1;
			        $map['yue']= $jhmnum - 1;
                    $map['date']= date('Y-m-d H:i:s', time());
                    M('jhm_log')->add($map);

                    //新用户注册激活成功后，自动生成一个买入【开仓】订单20190513 start----------------
                    $new_jh_user_data = M('user')->where(array('UE_account' =>$uname))->find();
                    $this->new_user_create_first_tgbz($new_jh_user_data,1000);
                    //新用户注册激活成功后，自动生成一个买入【开仓】订单20190513 start----------------

				    return_die_ajax('激活成功！',true,1);
               }else
			   {
				 return_die_ajax('激活失败！');
               }
			}elseif($act == "paidan_zc")
			{
				$to_user = I('post.to_user');
				$num = I('post.num');
				$to_user= str_replace(' ', '', $to_user);

				$secpwd = I('post.secpwd');
				if (!isset($secpwd)) {
	        		return_die_ajax("请输入二级密码!");
	            }

	            $user = M('user')->where(array(UE_account => $_SESSION['uname']))->find();

	            if (md5($secpwd) != $user['ue_secpwd']) {
	        		return_die_ajax("二级密码不正确!");
	            }

				/*
                $alluser=get_last_all_user2($_SESSION['uname']);
                if(!in_array(trim($to_user),$alluser))
				{
					return_die_ajax('转入用户不是您的下级，不能转让！');
                }
				*/

				$zc_user_m = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();

                $to_user_m = M('user')->where("UE_account = '" . $to_user . "' or UE_phone = '" .$to_user ."'")->find();

				if($zc_user_m['ue_account'] == $to_user_m['ue_account'])
				{
					return_die_ajax('不能转账给自己!');
				}

                //判断转账是否满足上下级的关系20190512 start-------
                $map['_string']="UE_account = '" . $to_user . "' or UE_phone = '" . $to_user ."'";
                $to_user_data = M('user')->where($map)->find();
                if ( empty( $to_user_data ) ) {
                    return_die_ajax("转入的用户不存在!");
                }
                $to_user = $to_user_data['ue_account'];
                //向下级转
                $transfer_accounts_down_set = C('transfer_accounts_down_set');//得到向下级的转账规则设置，如果为1则需满足向下的转账规则才行
                if( $transfer_accounts_down_set ) { //向下级的转账规则开启
                    //判断转账是否满足下级的关系
                    $all_child_user = get_last_all_user2($_SESSION['uname']);
                    if (!in_array(trim($to_user), $all_child_user)) {
                        return_die_ajax('转入用户不是您的下级，不能转让！');
                    }
                }

                //向上级转
                $transfer_accounts_up_set = C('transfer_accounts_up_set');//得到向上级的转账规则设置，如果为1则需满足向上的转账规则才行
                if( $transfer_accounts_up_set ){ //向上级的转账规则开启
                    //判断转账是否满足上级的关系
                    $all_parent_user = get_parent_all_user($_SESSION['uname']);
                    if(!in_array(trim($to_user),$all_parent_user))
                    {
                        return_die_ajax('转入用户不是您的上级，不能转让！');
                    }
                }
                if( !$transfer_accounts_down_set && !$transfer_accounts_up_set ){
                    return_die_ajax('请开启一个向上级或者向下级转让的规则！');
                }
                //判断转账是否满足上下级的关系20190512 end-------

				if($num>$zc_user_m['pdmnum'])
				{
				   return_die_ajax(C('pdm_name') . '数量不足！');
                }
                if (!$to_user_m)
				{
				   return_die_ajax('用户不存在！');
                }
				elseif (!preg_match('/^[0-9.]{1,10}$/', $num))
				{
                   return_die_ajax('请填写正确的数量！');
                } else
				{
					M('user')->where("UE_account = '" . $to_user . "' or UE_phone = '" .$to_user ."'")->setInc('pdmnum', $num);
					M('user')->where(array('UE_account' =>$_SESSION['uname']))->setDec('pdmnum', $num);

                    $map['user'] = $_SESSION['uname'];
					$map['type']= 'zc';
                    $map['info']= '转出到用户' . $to_user;
                    $map['num']= -$num;
					$map['yue']= get_userinfo($_SESSION['uname'],'pdmnum');
                    $map['date']= date('Y-m-d H:i:s', time());
                    M('paidan_log')->add($map);

					$map2['user'] = $to_user;
					$map2['type']= 'zr';
                    $map2['info']= '由' . $_SESSION['uname'] . '转入';
                    $map2['num']= $num;
					$map2['yue']= get_userinfo($to_user,'pdmnum');
                    $map2['date']= date('Y-m-d H:i:s', time());
                    M('paidan_log')->add($map2);

					return_die_ajax('转出成功!',true,1);
			    }

				return_die_ajax('参数错误!');
			}elseif($act == "jhm_zc")
			{
				$to_user = I('post.to_user');
				$num = I('post.num');
				$to_user= str_replace(' ', '', $to_user);

				$secpwd = I('post.secpwd');
				if (!isset($secpwd)) {
	        		return_die_ajax("请输入二级密码!");
	            }

	            $user = M('user')->where(array(UE_account => $_SESSION['uname']))->find();

	            if (md5($secpwd) != $user['ue_secpwd']) {
	        		return_die_ajax("二级密码不正确!");
	            }


				//判断转账是否满足上下级的关系20190512 start-------
                $map['_string']="UE_account = '" . $to_user . "' or UE_phone = '" . $to_user ."'";
	            $to_user_data = M('user')->where($map)->find();
                if ( empty( $to_user_data ) ) {
                    return_die_ajax("转入的用户不存在!");
                }
                $to_user = $to_user_data['ue_account'];
                //向下级转
                $transfer_accounts_down_set = C('transfer_accounts_down_set');//得到向下级的转账规则设置，如果为1则需满足向下的转账规则才行
				if( $transfer_accounts_down_set ) { //向下级的转账规则开启
                    //判断转账是否满足下级的关系
                    $all_child_user = get_last_all_user2($_SESSION['uname']);
                    if (!in_array(trim($to_user), $all_child_user)) {
                        return_die_ajax('转入用户不是您的下级，不能转让！');
                    }
                }

                //向上级转
                $transfer_accounts_up_set = C('transfer_accounts_up_set');//得到向上级的转账规则设置，如果为1则需满足向上的转账规则才行
                if( $transfer_accounts_up_set ){ //向上级的转账规则开启
                    //判断转账是否满足上级的关系
                    $all_parent_user = get_parent_all_user($_SESSION['uname']);
                    if(!in_array(trim($to_user),$all_parent_user))
                    {
                        return_die_ajax('转入用户不是您的上级，不能转让！');
                    }
                }
                if( !$transfer_accounts_down_set && !$transfer_accounts_up_set ){
                    return_die_ajax('请开启一个向上级或者向下级转让的规则！');
                }
                //判断转账是否满足上下级的关系20190512 end-------

				/*
                $alluser=get_last_all_user2($_SESSION['uname']);
                if(!in_array(trim($to_user),$alluser))
				{
					return_die_ajax('转入用户不是您的下级，不能转让！');
                }
				*/

				$zc_user_m = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();

                $to_user_m = M('user')->where("UE_account = '" . $to_user . "' or UE_phone = '" .$to_user ."'")->find();

				if($zc_user_m['ue_account'] == $to_user_m['ue_account'])
				{
					return_die_ajax('不能转账给自己!');
				}

				if($num>$zc_user_m['jhmnum'])
				{
				   return_die_ajax(C('jhm_name') .'数量不足！');
                }
                if (!$to_user_m)
				{
				   return_die_ajax('用户不存在！');
                }
				elseif (!preg_match('/^[0-9.]{1,10}$/', $num))
				{
                   return_die_ajax('请填写正确的数量！');
                } else
				{
					M('user')->where("UE_account = '" . $to_user . "' or UE_phone = '" .$to_user ."'")->setInc('jhmnum', $num);
					M('user')->where(array('UE_account' =>$_SESSION['uname']))->setDec('jhmnum', $num);

                    $map['user'] = $_SESSION['uname'];
					$map['type']= 'zc';
                    $map['info']= '转出到用户' . $to_user;
                    $map['num']= -$num;
					$map['yue']= get_userinfo($_SESSION['uname'],'jhmnum');
                    $map['date']= date('Y-m-d H:i:s', time());
                    M('jhm_log')->add($map);

					$map2['user'] = $to_user;
					$map2['type']= 'zr';
                    $map2['info']= '由' . $_SESSION['uname'] . '转入';
                    $map2['num']= $num;
					$map2['yue']= get_userinfo($to_user,'jhmnum');
                    $map2['date']= date('Y-m-d H:i:s', time());
                    M('jhm_log')->add($map2);

					return_die_ajax('转出成功!',true,1);
			    }

				return_die_ajax('参数错误!');
			}elseif($act == "jifen_dhjhm")
			{
				$to_user = I('post.to_user');
				$num = I('post.num');
				$to_user= str_replace(' ', '', $to_user);

				if(C('cxj_dhjhm_num') == 0)
					return_die_ajax('系统暂时关闭兑换功能!');

				if($_SESSION['uname'] != $to_user)
					return_die_ajax('目前只能给自己兑换!');

				$user = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();

                $to_user_m = M('user')->where("UE_account = '" . $to_user . "' or UE_phone = '" .$to_user ."'")->find();

				if($num * C('cxj_dhjhm_num') > $user['jifen'])
				{
				   return_die_ajax(C('jifen_wallet_name') . '数量不足！');
                }

				elseif (!preg_match('/^[0-9.]{1,10}$/', $num))
				{
                   return_die_ajax('请填写正确的数量！');
                } else
				{
					M('user')->where(array('UE_account' =>$to_user))->setDec('jifen', $num * C('cxj_dhjhm_num'));

					M('user')->where(array('UE_account' =>$to_user))->setInc('jhmnum', $num);

                    $map['user'] = $to_user;
					$map['type']= 'dhjhm';
                    $map['info']= '兑换'.$num.'个'.C('jhm_name');
                    $map['num']= -($num * C('cxj_dhjhm_num'));
					$map['yue']= get_userinfo($to_user,'jifen');
                    $map['date']= date('Y-m-d H:i:s', time());
                    M('jifen_log')->add($map);

					$map2['user'] = $to_user;
					$map2['type']= 'jfdh';
                    $map2['info']= $map['info']= '使用了'.$num * C('cxj_dhjhm_num') . C('jifen_wallet_name') .'兑换'.$num.'个'.C('jhm_name');
                    $map2['num']= $num;
					$map2['yue']= get_userinfo($to_user,'jhmnum');
                    $map2['date']= date('Y-m-d H:i:s', time());
                    M('jhm_log')->add($map2);

					return_die_ajax('转出成功!',true,1);
			    }

				return_die_ajax('参数错误!');
			}elseif($act == "jifen_dhpdm")
            {  //当前诚信积分兑换通证奖励【有原来的诚信积分兑换激活码修改而来】
                $to_user = I('post.to_user');
                $num = I('post.num');
                $to_user= str_replace(' ', '', $to_user);

                if(C('cxj_dhjhm_num') == 0)
                    return_die_ajax('系统暂时关闭兑换功能!');


                if($_SESSION['uname'] != $to_user)
                    return_die_ajax('目前只能给自己兑换!');

                $user = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();

                $to_user_m = M('user')->where("UE_account = '" . $to_user . "' or UE_phone = '" .$to_user ."'")->find();

                if($num * C('cxj_dhjhm_num') > $user['jifen'])
                {
                    return_die_ajax(C('jifen_wallet_name') . '数量不足！');
                }

                elseif (!preg_match('/^[0-9.]{1,10}$/', $num))
                {
                    return_die_ajax('请填写正确的数量！');
                } else
                {
                    M('user')->where(array('UE_account' =>$to_user))->setDec('jifen', $num * C('cxj_dhjhm_num'));

                    M('user')->where(array('UE_account' =>$to_user))->setInc('pdmnum', $num);

                    $map['user'] = $to_user;
                    $map['type']= 'dhpdm';
                    $map['info']= '兑换'.$num.'个通证';
                    $map['num']= -($num * C('cxj_dhjhm_num'));
                    $map['yue']= get_userinfo($to_user,'jifen');
                    $map['date']= date('Y-m-d H:i:s', time());
                    M('jifen_log')->add($map);

                    $map2['user'] = $to_user;
                    $map2['type']= 'jfdh';
                    $map2['info']= $map['info']= '使用了'.$num * C('cxj_dhjhm_num') . C('jifen_wallet_name') .'兑换'.$num.'个通证';
                    $map2['num']= $num;
                    $map2['yue']= get_userinfo($to_user,'pdmnum');
                    $map2['date']= date('Y-m-d H:i:s', time());
                    //M('jhm_log')->add($map2);
                    M('paidan_log')->add($map2);

                    return_die_ajax('转出成功!',true,1);
                }

                return_die_ajax('参数错误!');
            }
		}

		if(IS_GET)
		{
			$act = I('get.act');

			if($act == "yuyue")
			{
				$yuyuemoney = get_userinfo($_SESSION['uname'],'yuyuemoney');
				$yuyuezhouqi = get_userinfo($_SESSION['uname'],'yuyuezhouqi');

				$yuyue_table = "";

				$time = time();

				$map['user'] = $_SESSION['uname'];
				$map['isyuyue']= 1;
				$tgbz = M('tgbz')->where($map)->limit(1)->order('date desc')->select();
				if(count($tgbz) > 0)
				{
                    $map1['mainid'] = $tgbz[0]['mainid'];
                    $cur_tgbz_jb = M('tgbz')->where($map1)->sum('jb');//当前开仓订单的总额

                    //当前用户在本次预约周期内的最近一次的一条记录
                    $last_cur_tgbz = get_cur_yuyue_zhouqi_last_tgbz();
                    $isyuyue = get_userinfo($_SESSION['uname'],'isyuyue');
                    if( !empty($last_cur_tgbz) && $isyuyue == 1 ) {
                        $yuyue_table = $yuyue_table .
                            "<tr style='color:Red'>
				          <td>" . date('Y-m-d', strtotime($last_cur_tgbz['date'])) . "</td>
				          <td>" . $last_cur_tgbz['total'] . "</td>
				          <td>已成功挂单</td>
				        </tr> ";
                    }

                    /*
					$yuyue_table = $yuyue_table .
					   "<tr style='color:Red'>
				          <td>".date('Y-m-d',strtotime($tgbz[0]['date']))."</td>
				          <td>".$cur_tgbz_jb."</td>
				          <td>已成功挂单</td>
				        </tr> ";
                    */
				}
                $map1['user'] = $_SESSION['uname'];
                $tgbz = M('tgbz')->where($map1)->limit(1)->order('date desc')->select();
                $cur_info_data = M('user')->where(array('UE_account'=>$_SESSION['uname']))->find();
                if( count($tgbz) > 0 && $tgbz[0]['date'] > $cur_info_data['yuyue_cur_time']){
                    $time = strtotime($tgbz[0]['date']);
                }else{
                    //得到当前符合的挂单时间
                    $paidan_time_start = C('paidan_time_start');//如8  【8点开始】
                    $paidan_time_start_date = date('Y-m-d',time()).' '.$paidan_time_start.'00:00';
                    $paidan_time_end = C('paidan_time_end'); //如18 【18点开始】
                    $paidan_time_end_date = date('Y-m-d',time()).' '.$paidan_time_end.'00:00';

                    $yuyue_cur_time_H = date("H",strtotime($cur_info_data['yuyue_cur_time']));
                    if( $yuyue_cur_time_H >= $paidan_time_start && $yuyue_cur_time_H <= $paidan_time_end ){
                        $time = strtotime($cur_info_data['yuyue_cur_time']);
                    }else{
                        $time = strtotime($cur_info_data['yuyue_cur_time']) + 24*3600;
                    }
                    $today_paidan_time_start = strtotime( $paidan_time_start_date );
                    $today_paidan_time_end = strtotime( $paidan_time_end_date );
                }
                if( $cur_info_data['isyuyue'] == 1 ) {

                    $cur_last_yuyue_time_start = strtotime($cur_info_data['yuyue_cur_time']);//在本周期内上一条预约的时间，如果有就赋值时间，没有就设置为预约的起始时间yuyue_cur_time；
                    //当前用户在本次预约周期内的最近一次的一条记录
                    $last_cur_tgbz_two = get_cur_yuyue_zhouqi_last_tgbz();
                    if( !empty($last_cur_tgbz_two) )
                    {
                        $cur_last_yuyue_time_start = strtotime($last_cur_tgbz_two['date']);
                    }
                    $base_time = time();
                    if( $cur_last_yuyue_time_start > $base_time ){
                        $base_time = $cur_last_yuyue_time_start;
                    }

                    //本次预约周期内剩余的排单次数
                    $cur_balance_yuyue_zhouqi = cur_balance_yuyue_zhouqi();
                    if( $cur_balance_yuyue_zhouqi > 0 ){
                        for ($x = 0; $x < $cur_balance_yuyue_zhouqi; $x++) {
                            $base_time_date = date('Y-m-d',($base_time));
                            $today_date = date("Y-m-d",time());
                            if( $base_time_date == date('Y-m-d',$cur_last_yuyue_time_start) && !empty($last_cur_tgbz_two) && $today_date == $base_time_date ) {
                                $base_time = $base_time + 3600 * 24;
                            }
                            if (($base_time + 3600 * 24 * $x) >= $cur_last_yuyue_time_start) {

                                    $yuyue_table = $yuyue_table .
                                        "<tr>
                                      <td>" . date('Y-m-d', ($base_time + 3600 * 24 * $x)) . "</td>
                                      <td>" . $yuyuemoney . "</td>
                                      <td>等待执行;</td>
                                    </tr> ";
                            }
                        }
                    }

                    /*
                    for ($x = 0; $x < $yuyuezhouqi; $x++) {
                        if (($time + 3600 * 24 * $x) > $today_paidan_time_start) {
                            $yuyue_table = $yuyue_table .
                                "<tr>
				          <td>" . date('Y-m-d', ($time + 3600 * 24 * $x)) . "</td>
				          <td>" . $yuyuemoney . "</td>
				          <td>等待执行;</td>
				        </tr> ";
                        }
                    }
                    */
                }

                /*
				$map1['user'] = $_SESSION['uname'];
				$tgbz = M('tgbz')->where($map1)->limit(1)->order('date desc')->select();
				if(count($tgbz) > 0)
				{
					$time = strtotime($tgbz[0]['date']);
				}
				for ($x=1; $x<=6; $x++) 
				{
				   $yuyue_table = $yuyue_table . 
					   "<tr>
				          <td>" . date('Y-m-d',($time + 3600 * 24 * $yuyuezhouqi * $x)) ."</td>
				          <td>". $yuyuemoney ."</td>
				          <td>等待执行</td>
				        </tr> ";
				}
                */

                //排单的最大天数
                $schedule_max_day = C('schedule_max_day');
                $schedule_max_day = $schedule_max_day + 1;
                $this->assign('schedule_max_day',$schedule_max_day);

                //获取当前用户可预约的金额
                $can_choice_jb_arr =  can_choice_jb();
                $this->assign('can_choice_jb_arr',$can_choice_jb_arr);

				$this->assign('yuyue_table', $yuyue_table);
				$this->display("act/yuyue");
			}else if($act == "viewimg")
			{
				$pic = I('get.pic');
				$this->assign('pic', $pic);
				$this->display("act/viewimg");
			}else if($act == "payfromethcheck")
			{
				$ppid = I('get.ppid');
				$map1['id'] = $ppid;
				$ppdd = M('ppdd')->where($map1)->find();
				$map2['UE_account'] = $ppdd['p_user'];
				$p_user = M('user')->where($map2)->find();
				$map3['UE_account'] = $ppdd['g_user'];
				$g_user = M('user')->where($map3)->find();
				$this->assign('p_user', $p_user);
				$this->assign('g_user', $g_user);
				$payresult = check_vc_pay_status($p_user['eth_addr'],$g_user['eth_addr'],'eth');
				$this->assign('payresult', $payresult);
				$this->display("act/payfromethcheck");
			}
		}
	}

	public function getjhm()
	{
		$userData = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();
		if($userData['ue_check'] == 1)
		{
			$this->error('你已激活，就别再参合这件事了', '/Home/Index/home.html');
			exit;
		}
		if(C('getjhm_num') == 0)
			$this->assign('randoum', 4);
		else if(!(date("H") >= C('getjhm_start') && date("H") <= C('getjhm_end')))
		{
			$this->assign('randoum', 0);
		}else if((date("H") >= C('getjhm_start') && date("H") <= C('getjhm_end')))
		{
			$todaysum = M('jhm_log')->where("type = 'cj' and to_days(date) = to_days(now())")->count();
			if($todaysum >= C('getjhm_num'))
			    $this->assign('randoum', 5);
			else
			{
				$selfcount = M('jhm_log')->where ("user = '" . $_SESSION['uname'] ."' and type = 'cj' ")->count();
				if($selfcount >= 1)
					$this->assign('randoum', 3);
				else
				{
					M('user')->where(array(UE_account => $_SESSION['uname']))->setInc('jhmnum', 1);
				    $map['user'] = $_SESSION['uname'];
				    $map['type']= 'cj';
                    $map['info']= '抽奖获得';
                    $map['num']= 1;
			        $map['yue']= get_userinfo($_SESSION['uname'],'jhmnum');
                    $map['date']= date('Y-m-d H:i:s', time());
                    M('jhm_log')->add($map);
				    $this->assign('randoum', 1);
				}
			}
		}

		$this->assign('getjhm_active','active');

		$this->display();
	}
    public function messageinbox() {
        $this->display();
    }
    public function usenewapp() {
        $this->success('尚未开通,敬请期待...', '/Home/Index/home.html');
        exit;
        $map['usenewapp'] = I('get.isuse');
        $savepwd = M('user')->where(array('UE_ID' => $_SESSION['uid']))->save($map);
        if ($map['usenewapp'] == "0") $this->success('正在切换到旧版...', '/Home/Index/home.html');
        if ($map['usenewapp'] == "1") $this->success('正在切换到新版...', '/Home/Index/home.html');
    }
    public function uploadify() {
        if (!empty($_FILES)) {
            //图片上传设置
            $savePath = '/Uploads';
            $config = array('maxSize' => 13145728, 'savePath' => $savePath, 'saveName' => array('uniqid', ''), 'exts' => array('jpg', 'gif', 'png', 'jpeg'), 'autoSub' => true, 'subName' => array('date', 'Ymd'),);
            $upload = new \Think\Upload($config); // 实例化上传类
            $images = $upload->upload();
            //判断是否有图
            if ($images) {
                $info = $savePath.$images['file']['savepath'] . $images['file']['savename'];
                //返回文件地址和名给JS作回调用
                //echo $info;
                $result = [
                    'code'=>1,
                    'msg'=>'成功',
                    'path'=>$info,
                ];
                echo json_encode($result);
            } else {
                //$this->error($upload->getError());//获取失败信息
                //dump($upload->getError());
                $result = [
                    'code'=>0,
                    'msg'=>$upload->getError(),
                ];
                echo json_encode($result);
            }
        }
    }

    public function home()
	{

        $switch = get_pd_status(); //0表示在正常开仓  1表示不在开仓时间内
        $this->assign('switch',$switch);

        //今日开仓总量是否用尽 0表示已用尽 1表示还未用尽
        $today_kczl_status = get_today_kczl_status(0);
        $this->assign('today_kczl_status',$today_kczl_status);

        $userData = $this->userData;

        $notsellout = 1; // 0表示没有交割积分和通证奖金 1表示有
        if ($userData['qwe'] <= 0 && $userData['ue_money'] <= 0) {
        	$notsellout = 0;
        }
        $this->assign('notsellout',$notsellout);

	    if ($userData && $userData['ue_check'] == 0 && C('no_check_loginallowed') == "0")
		{
            $this->error('该帐号未激活,请耐心等待..','/Home/Login/index.html');
        }

		if ($userData && $userData['ue_check'] == 0 && C('no_check_loginallowed') == "1" && C('new_jihuo_feng_days') > 0 && $_SESSION["need_jihuo"] == "")
		{
			$now = date('Y-m-d H:i:s', time());
			$_SESSION["need_jihuo"] = "showed";
			//$jihuo_date = date('Y-m-d H:i:s',strtotime('+' . C('new_jihuo_feng_days') .' day'));
			$jihuo_date = strtotime($userData['ue_regtime']) + C('new_jihuo_feng_days') * 3600 * 24;
			//到期时间
			if (time() > $jihuo_date)
			{
				M('user')->execute("update ot_user set UE_status = 1 where UE_account = '" . $_SESSION['uname'] . "'");

                //封号扣除10张通证【排单码】20190513 start--------
                //封号扣除10张通证【排单码】
                fhkcpdm_or_jf($_SESSION['uname'],10,'wjhfh',C('new_jihuo_feng_days').'天未激活封号，扣除10个激活码');
                //封号扣除10张通证【排单码】20190513 end--------

				$this->error('未激活账户','/Home/Login/index.html');
			}
			$this->error("新用户请于" . date('Y-m-d H:i:s',$jihuo_date). "之前激活账户，否则封号",'/Home/index.html');
        }

		if (getUserTGBZCount() == 0  && $userData['ue_check'] == 1 && C('jihuo_feng_days') > 0 && $_SESSION["tgbz_need_days"] == '' && $userData['next_tgbz_time'] != null && $userData['next_tgbz_time'] != '')
		{
			$_SESSION["tgbz_need_days"] = "showed";
			//到期时间
			if ($userData['next_tgbz_time'] != null && $userData['next_tgbz_time'] != '' && time() > strtotime($userData['next_tgbz_time']))
			{
				M('user')->execute("update ot_user set UE_status = 1 where UE_account = '" . $_SESSION['uname'] . "'");

                //封号扣除10张通证【排单码】20190513 start--------
                //封号扣除10张通证【排单码】
                fhkcpdm_or_jf($_SESSION['uname'],10,'wgdfh',C('jihuo_feng_days').'天未挂单封号，扣除10个通证【排单码】');
                //封号扣除10张通证【排单码】20190513 end--------

				$this->error('未挂单封号','/Home/Login/index.html');
			}
            $this->error("请于" . $userData['next_tgbz_time']. "之前买入，否则封号",'/Home/index.html');
        }

        //a.判断买入人的防撞功能是否开启，
        if( $userData['fangzhuang'] == 1 )
        {
            //b.如果是开启状态，就判断该用户的第一个订单是否完成预付款，若完成预付款，就关闭防撞功能
            if( checkUserFirstTgbzStatus($userData['ue_account']) == 1 ){
                //关闭打款人的防撞功能
                M('user')->where(array('UE_account' => $userData['ue_account']))->save(array('fangzhuang'=>0));
            }
        }

        //////////////////----------
		$sall = ' and ppjb < total ';
		$showal=1;
		$this->assign('showall', 1);
		if(I('get.showall') == '1')
		{
			$this->assign('showall', $showall);
			$sall = '';
			$showal=0;
		}
        /*
        $User = M('tgbz'); 
        $map['user'] = $_SESSION['uname'];
		$map['_string'] = 'mainid = id' . $sall;
        $map['zt'] = array(in, array('0', '6','1'));
        $count = $User->where($map)->count(); 
        $p = getpage($count, 5);
        $plist = $User->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('plist', $plist);
        $this->assign('ppage', $p->show()); 
		$this->assign('pcount',$count); 
        /////////////////----------------
        //////////////////----------
        $User = M('jsbz'); 
        $map1['user'] = $_SESSION['uname'];
		$map1['_string'] = 'mainid = id'  . $sall;
        $map1['zt'] = array(in, array('0', '6','1'));
        $count1 = $User->where($map1)->count();
        $p1 = getpage($count1, 5);
        $jlist = $User->where($map1)->order('id DESC')->limit($p1->firstRow, $p1->listRows)->select();
        $this->assign('jlist', $jlist); 
		$this->assign('jpage', $p1->show()); 
		$this->assign('jcount', $count1); 
        //////////////////----------
        $User = M('ppdd'); // 实例化User对象
        $map2['p_user'] = $_SESSION['uname'];
        $map2['zt'] = array('in', array('0', '1'));
        $count2 = $User->where($map2)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p2 = getpage($count2, 50);
        $plist = $User->where($map2)->order('id DESC')->limit($p2->firstRow, $p2->listRows)->select();
        $plist = getPDinfo($plist);
        $this->assign('pp_p_list', $plist); // 赋值数据集
        $this->assign('pp_p_page', $p2->show()); // 赋值分页输出
        /////////////////----------------
        //////////////////----------
        $User = M('ppdd'); // 实例化User对象
        $map3['g_user'] = $_SESSION['uname'];
        $map3['zt'] = array('in', array('0', '1'));
        $count3 = $User->where($map3)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p3 = getpage($count3, 50);
        $gdlist = $User->where($map3)->order('id DESC')->limit($p3->firstRow, $p3->listRows)->select();
        $gdlist = getGDinfo($gdlist);
        $this->assign('pp_g_list', $gdlist); // 赋值数据集
        $this->assign('pp_g_page', $p3->show()); // 赋值分页输出
        /////////////////----------------
        */
        //读取两条最新的新闻
        $info = M("info");
        $info_list = $info->limit(2)->order('if_id desc')->select();
        $this->assign('info_list', $info_list);

        //开仓为匹配到的用户20190514 start-----------------
        //开仓为匹配到的用户
        $tgbzModel = M('tgbz');
        //tgbz中的zt为1（匹配）则匹配买入【开仓】zt eq 0 等待中 zt eq 1 匹配成功 zt eq 6预约中 qr_zt eq 0 未确认 qr_zt eq 1 已确认
        $where['zt'] = array('in',array('0','6'));
        $where['isprepay'] = array('eq',1);
        $count4 = $tgbzModel->where($where)->count();
        $p4 = getpage($count4, 5);
        $this->assign('tgbzcount', $count4);
        $tgbz_matching_list = $tgbzModel->where($where)->order('id DESC')->limit($p4->firstRow, $p4->listRows)->select();
        $userModel = M('user');
        foreach($tgbz_matching_list as $key => $item){
            $cur_user = $userModel->field('UE_level,levelname')->where(array('UE_account'=>array('eq',$item['user'])))->find();
            $item['levelname'] = $cur_user['levelname'];
            $tgbz_matching_list[$key] = $item;
        }
        $this->assign('tgbz_matching_list', $tgbz_matching_list); // 赋值数据集
        $this->assign('tgbz_matching_page', $p4->show()); // 赋值分页输出

        $is_yfk_unfinished_status = is_yfk_unfinished_status();//是否有未完成的开仓订单
        $is_wk_unfinished_status = is_wk_unfinished_status();//是否有未完成的平仓订单
        $is_jg_unfinished_status = is_jg_unfinished_status();//是否有未完成的交割订单
        $this->assign('is_yfk_status',$is_yfk_unfinished_status);
        $this->assign('is_wk_status',$is_wk_unfinished_status);
        $this->assign('is_jg_status',$is_jg_unfinished_status);

        //开仓为匹配到的用户20190514 end-----------------
        $total_user_count = C('virtual_user_num')+$count4;
        $this->assign('total_user_count',$total_user_count);

		$this->ex_single_process();

		$this->ex_single_process_Accountaddlevel();

		$this->assign('home_active','active');

		//是否在可排单时间内
        $paidan_switch = 1;
        $paidan_get_start = C('get_start');
        $paidan_get_end = C('get_end');
        $now_hi = date("H:i",time());
        if( $now_hi < $paidan_get_start || $now_hi > $paidan_get_end ){
            $paidan_switch = 0;
        }
        $this->assign('paidan_switch',$paidan_switch);

		if(I('get.page_a') != '')
		{
			$_SESSION['page_a'] = I('get.page_a');
			$_SESSION['page_l'] = I('get.page_l');
			$_SESSION['count'] = I('get.count');
			$_SESSION['count'] = I('get.count');
		}

        if (I('tag') == "o") {
            $this->display('ohome');
        } else $this->display('home');
    }



    public function tuiguang() {
        $this->foot_menu_high_id = "a_4";
        $this->add_css = "<link media='all' rel='stylesheet' href='/skin/styles/account.css' type='text/css'>";
        $tgurl = "http://" . $_SERVER["HTTP_HOST"] . u("Reg/index", array("uname" => $_SESSION['uname']));
        //-------------------------------start qq2970215190 千线互联 网址缩短接口PHP版本
        $start = "<url_short>";
        $end = "</url_short>";
        $url = "http://api.t.sina.com.cn/short_url/shorten.xml?source=3213676317&url_long=" . $tgurl; //原始网址
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $input = curl_exec($ch);
        curl_close($ch);
        $str = substr($input, strlen($start) + strpos($input, $start), (strlen($input) - strpos($input, $end)) * (-1));
        //输出
        $this->tgurl_m = $str;
        $this->display('tuiguang');
    }
    public function account() {
        $this->foot_menu_high_id = "a_4";
        $this->display('account');
    }
    public function foot() {
        $this->display('foot');
    }
    /*
     * start lock
    */
    public function lock() {
        $this->display('lock');
    }
    public function savePwd() {
        $map['patternpwd'] = I('post.patternpwd');
        $savepwd = M('user')->where(array('UE_ID' => $_SESSION['uid']))->save($map);
        if ($savepwd) {
            echo "1";
        } else {
            echo "0";
        }
    }
    public function getPwd() {
        $userData = M('user')->where(array('UE_ID' => $_SESSION['uid']))->find();
        if ($userData['patternpwd'] == "" || $userData['patternpwd'] == null) echo htmlspecialchars_decode("n");
        else echo htmlspecialchars_decode($userData['patternpwd']);
    }
    public function verifyPwdInServer() {
        $patternpwd = I('post.patternpwd');
        $userData = M('user')->where(array('UE_ID' => $_SESSION['uid']))->find();
        if ($patternpwd == $userData['patternpwd']) {
            $_SESSION['SAFEPWDPASS'] = "SAFEPWDPASS";
            echo "1";
        } else {
            echo "0";
        }
    }
    /*
     * end lock
    */
    public function head() {
        $this->display('head');
    }
    public function head_old() {
        $this->display('head_old');
    }
    public function jingli() {
        //////////////////----------
        $User = M('tgbz'); // 实例化User对象
        $map['user'] = $_SESSION['uname'];
        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p = getpage($count, 100);
        $tlist = $User->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('tlist', $tlist); // 赋值数据集
        $this->assign('page', $p->show()); // 赋值分页输出
        /////////////////----------------
        //////////////////----------
        $User = M('jsbz'); // 实例化User对象
        $map1['user'] = $_SESSION['uname'];
        $count1 = $User->where($map1)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p1 = getpage($count1, 100);
        $jlist = $User->where($map1)->order('id DESC')->limit($p1->firstRow, $p1->listRows)->select();
        $this->assign('jlist', $jlist); // 赋值数据集
        $this->assign('page1', $p1->show()); // 赋值分页输出
        /////////////////----------------
        //推广链接
        $userInfo = $User->where($map)->find();
        $tgurl = "http://" . $_SERVER["HTTP_HOST"] . u("Reg/index", array("uname" => $map1['user']));
        $this->tgurl = $tgurl;
        //////////////////----------
        $User = M('ppdd'); // 实例化User对象
        $map2['p_user'] = $_SESSION['uname'];
        $count2 = $User->where($map2)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p2 = getpage($count2, 100);
        $plist = $User->where($map2)->order('id DESC')->limit($p2->firstRow, $p2->listRows)->select();
        $this->assign('plist', $plist); // 赋值数据集
        $this->assign('page2', $p2->show()); // 赋值分页输出
        /////////////////----------------
        //////////////////----------
        $User = M('ppdd'); // 实例化User对象
        $map3['g_user'] = $_SESSION['uname'];
        $count3 = $User->where($map3)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p3 = getpage($count3, 100);
        $list3 = $User->where($map3)->order('id DESC')->limit($p3->firstRow, $p3->listRows)->select();
        $this->assign('glist', $list3); // 赋值数据集
        $this->assign('page3', $p3->show()); // 赋值分页输出
        /////////////////----------------
        $this->assign('mm001', C("mm001"));
        $this->assign('mm002', C("mm002"));
        $this->assign('mm003', C("mm003"));
        $this->assign('mm004', C("mm004"));
        $this->assign('mm005', C("mm005"));
        $this->assign('jj01s', C("jj01s"));
        $this->assign('jj01m', C("jj01m"));
        $this->assign('jj01', C("jj01"));
        $this->assign('jjdktime', C("jjdktime"));
        //提现设置
        $this->assign('jl_start', C("jl_start"));
        $this->assign('jl_e', C('jl_e'));
        $this->assign('jl_beishu', C("jl_beishu"));
        $this->display('jingli');
    }
    public function tuijian() {
        //////////////////----------
        $User = M('tgbz'); // 实例化User对象
        $map['user'] = $_SESSION['uname'];
        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p = getpage($count, 100);
        $tlist = $User->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('tlist', $tlist); // 赋值数据集
        $this->assign('page', $p->show()); // 赋值分页输出
        /////////////////----------------
        //////////////////----------
        $User = M('jsbz'); // 实例化User对象
        $map1['user'] = $_SESSION['uname'];
        $count1 = $User->where($map1)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p1 = getpage($count1, 100);
        $jlist = $User->where($map1)->order('id DESC')->limit($p1->firstRow, $p1->listRows)->select();
        $this->assign('jlist', $jlist); // 赋值数据集
        $this->assign('page1', $p1->show()); // 赋值分页输出
        /////////////////----------------
        //推广链接
        $userInfo = $User->where($map)->find();
        $tgurl = "http://" . $_SERVER["HTTP_HOST"] . u("Reg/index", array("uname" => $map1['user']));
        $this->tgurl = $tgurl;
        //////////////////----------
        $User = M('ppdd'); // 实例化User对象
        $map2['p_user'] = $_SESSION['uname'];
        $count2 = $User->where($map2)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p2 = getpage($count2, 100);
        $plist = $User->where($map2)->order('id DESC')->limit($p2->firstRow, $p2->listRows)->select();
        $this->assign('plist', $plist); // 赋值数据集
        $this->assign('page2', $p2->show()); // 赋值分页输出
        /////////////////----------------
        //////////////////----------
        $User = M('ppdd'); // 实例化User对象
        $map3['g_user'] = $_SESSION['uname'];
        $count3 = $User->where($map3)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p3 = getpage($count3, 100);
        $list3 = $User->where($map3)->order('id DESC')->limit($p3->firstRow, $p3->listRows)->select();
        $this->assign('glist', $list3); // 赋值数据集
        $this->assign('page3', $p3->show()); // 赋值分页输出
        /////////////////----------------
        //提现设置
        $this->assign('tj_start', C("tj_start"));
        $this->assign('tj_e', C('tj_e'));
        $this->assign('tj_beishu', C("tj_beishu"));
        $this->display('tuijian');
    }

    //帮助中心
    public function help()
    {
        $info = M('info')->select();
        $arr = [];
        foreach( $info as $item )
        {
            if( $item['if_type1id'] == 1 ){
                $arr['system_notice'][] = $item;
            }

            if( $item['if_type1id'] == 2 ){
                $arr['operation'][] = $item;
            }

            if( $item['if_type1id'] == 3 ){
                $arr['education'][] = $item;
            }

        }

        $this->assign('arr',$arr);

        $this->display();
    }

    //帮助中兴详情页面
    public function help_detail()
    {
        $id = I('get.id');
        $data = M('info')->where(array('IF_ID'=>$id))->find();
        $this->assign('data',$data);
        $this->display();
    }

    // 新闻列表页
    public function news() {
        $User = M('info'); // 实例化User对象
        $count = $User->where(array('IF_type' => 'news'))->count(); // 查询满足要求的总记录数
        $page = new \Think\Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        // $page->lastSuffix=false;
        $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录    第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('last', '末页');
        $page->setConfig('first', '首页');
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');;
        $show = $page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where(array('IF_type' => 'news'))->order('IF_ID DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        //////////////////----------
        $User = M('info'); // 实例化User对象
        $map1['IF_type'] = 'bzzx';
        $count1 = $User->where($map1)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p1 = getpage($count1, 100);
        $list1 = $User->where($map1)->order('IF_ID DESC')->limit($p1->firstRow, $p1->listRows)->select();
        $this->assign('list1', $list1); // 赋值数据集
        $this->assign('page1', $p1->show()); // 赋值分页输出
        $this->news = true;
        $this->display('news'); // 输出模板

    }
    // 新闻内页
    public function newsPage() {
        header("Content-Type:text/html; charset=utf-8");
        $id = I('id');
        $data = M('info')->where(array('IF_ID' => $id))->find();
        $this->data = $data;
        $this->display('news_page');
    }
    // 帮助中心
    public function helpCenter() {
        header("Content-Type:text/html; charset=utf-8");
        $User = M('infoweb'); // 实例化User对象
        $count = $User->where(array('IW_type' => 'bzzx'))->count(); // 查询满足要求的总记录数
        $page = new \Think\Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        // $page->lastSuffix=false;
        $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录    第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('last', '末页');
        $page->setConfig('first', '首页');
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');;
        $show = $page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where(array('IW_type' => 'bzzx'))->order('IW_ID DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
        bd_template($_GET['str']);
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display('bzzx'); // 输出模板

    }
    // 帮助中心内页
    public function helpCenterPage() {
        header("Content-Type:text/html; charset=utf-8");
        $id = I('id');
        $data = M('infoweb')->where(array('IW_ID' => $id))->find();
        gs_template($_GET['str']);
        $this->data = $data;
        $this->display('bzzx_xx');
    }
    // 新手入门
    public function novice() {
        header("Content-Type:text/html; charset=utf-8");
        $data = M('infoweb')->where(array('IW_ID' => 11))->find();
        $this->data = $data;
        $this->display('bzzx_xx');
    }
    // 安全中心
    public function safe() {
        $this->mbzt = M('user')->where(array(UE_account => $_SESSION['uname']))->find();
        $this->display('zhaq');
    }
    // 一键收币
    // 金币明细
    public function jbmx() {
        header("Content-Type:text/html; charset=utf-8");
        $User = M('userget'); // 实例化User对象
        $date1 = I('post.date1', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/');
        $date2 = I('post.date2', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/');
        $map['UG_account'] = $_SESSION['uname'];
        $map['UG_type'] = 'jb';
        //$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));
        if (!empty($date1) && !empty($date2)) {
            $map['UG_getTime'] = array(array('gt', $date1), array('lt', $date2), 'and');
        }
        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        $page = new \Think\Page($count, 12); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        // $page->lastSuffix=false;
        $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录    第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('last', '末页');
        $page->setConfig('first', '首页');
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');;
        $show = $page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where($map)->order('UG_ID DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $ztj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'tjj'))->sum('UG_money');
        $ztj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'tjj'))->sum('UG_integral');
        $this->ztj = $ztj1 + $ztj2;
        $bdj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'kdj'))->sum('UG_money');
        $bdj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'kdj'))->sum('UG_integral');
        $this->bdj = $bdj1 + $bdj2;
        $fhj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'mrfh'))->sum('UG_money');
        $fhj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'mrfh'))->sum('UG_integral');
        $this->fhj = $fhj1 + $fhj2;
        $ldj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'mrldj'))->sum('UG_money');
        $ldj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'mrldj'))->sum('UG_integral');
        $this->ldj = $ldj1 + $ldj2;
        $glj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'glj'))->sum('UG_money');
        $glj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'glj'))->sum('UG_integral');
        $this->glj = $glj1 + $glj2;
        $this->display('jbmx'); // 输出模板

    }
    public function ybmx() {
        header("Content-Type:text/html; charset=utf-8");
        $User = M('userget'); // 实例化User对象
        $date1 = I('post.date1', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/');
        $date2 = I('post.date2', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/');
        $map['UG_account'] = $_SESSION['uname'];
        $map['UG_type'] = 'yb';
        //$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));
        if (!empty($date1) && !empty($date2)) {
            $map['UG_getTime'] = array(array('gt', $date1), array('lt', $date2), 'and');
        }
        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        $page = new \Think\Page($count, 12); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        // $page->lastSuffix=false;
        $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录    第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('last', '末页');
        $page->setConfig('first', '首页');
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');;
        $show = $page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where($map)->order('UG_ID DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $ztj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'tjj'))->sum('UG_money');
        $ztj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'tjj'))->sum('UG_integral');
        $this->ztj = $ztj1 + $ztj2;
        $bdj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'kdj'))->sum('UG_money');
        $bdj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'kdj'))->sum('UG_integral');
        $this->bdj = $bdj1 + $bdj2;
        $fhj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'mrfh'))->sum('UG_money');
        $fhj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'mrfh'))->sum('UG_integral');
        $this->fhj = $fhj1 + $fhj2;
        $ldj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'mrldj'))->sum('UG_money');
        $ldj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'mrldj'))->sum('UG_integral');
        $this->ldj = $ldj1 + $ldj2;
        $glj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'glj'))->sum('UG_money');
        $glj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'glj'))->sum('UG_integral');
        $this->glj = $glj1 + $glj2;
        $this->display('ybmx'); // 输出模板

    }
    public function zsbmx() {
        header("Content-Type:text/html; charset=utf-8");
        $User = M('userget'); // 实例化User对象
        $date1 = I('post.date1', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/');
        $date2 = I('post.date2', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/');
        $map['UG_account'] = $_SESSION['uname'];
        $map['UG_type'] = 'zsb';
        //$map ['UG_dataType'] = array('IN',array('mrfh','tjj','kdj','mrldj','glj'));
        if (!empty($date1) && !empty($date2)) {
            $map['UG_getTime'] = array(array('gt', $date1), array('lt', $date2), 'and');
        }
        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        $page = new \Think\Page($count, 12); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        // $page->lastSuffix=false;
        $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录    第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('last', '末页');
        $page->setConfig('first', '首页');
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');;
        $show = $page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where($map)->order('UG_ID DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $ztj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'tjj'))->sum('UG_money');
        $ztj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'tjj'))->sum('UG_integral');
        $this->ztj = $ztj1 + $ztj2;
        $bdj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'kdj'))->sum('UG_money');
        $bdj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'kdj'))->sum('UG_integral');
        $this->bdj = $bdj1 + $bdj2;
        $fhj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'mrfh'))->sum('UG_money');
        $fhj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'mrfh'))->sum('UG_integral');
        $this->fhj = $fhj1 + $fhj2;
        $ldj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'mrldj'))->sum('UG_money');
        $ldj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'mrldj'))->sum('UG_integral');
        $this->ldj = $ldj1 + $ldj2;
        $glj1 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'glj'))->sum('UG_money');
        $glj2 = M('userget')->where(array('UG_account' => $_SESSION['uname'], 'UG_dataType' => 'glj'))->sum('UG_integral');
        $this->glj = $glj1 + $glj2;
        $this->display('zsbmx'); // 输出模板

    }
    // 奖金明细
    public function jjjl() {
        header("Content-Type:text/html; charset=utf-8");
        $User = M('userget'); // 实例化User对象
        $date1 = I('post.date1', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/');
        $date2 = I('post.date2', '', '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/');
        $map['UG_account'] = $_SESSION['uname'];
        $map['UG_dataType'] = array('IN', array('mrfh', 'tjj', 'tjj1', 'tjj2', 'tjj3', 'bdj', 'mrldj'));
        if (!empty($date1) && !empty($date2)) {
            $map['UG_getTime'] = array(array('gt', $date1), array('lt', $date2), 'and');
        }
        //$map  = array('tjj','kdj','glj');
        //	$map['UE_Faccount']  = $_SESSION ['uname']
        //$ljtc1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>array('IN',$map)))->sum('UG_money');
        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        $page = new \Think\Page($count, 12); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        // $page->lastSuffix=false;
        $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录    第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('last', '末页');
        $page->setConfig('first', '首页');
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');;
        $show = $page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where($map)->order('UG_ID DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        // 		$ztj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'tjj'))->sum('UG_money');
        // 		$ztj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'tjj'))->sum('UG_integral');
        // 		$this->ztj = $ztj1+$ztj2;
        // 		$bdj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'kdj'))->sum('UG_money');
        // 		$bdj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'kdj'))->sum('UG_integral');
        // 		$this->bdj = $bdj1+$bdj2;
        // 		$fhj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrfh'))->sum('UG_money');
        // 		$fhj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrfh'))->sum('UG_integral');
        // 		$this->fhj = $fhj1+$fhj2;
        // 		$ldj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrldj'))->sum('UG_money');
        // 		$ldj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'mrldj'))->sum('UG_integral');
        // 		$this->ldj = $ldj1+$ldj2;
        // 		$glj1 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'glj'))->sum('UG_money');
        // 		$glj2 = M('userget')->where(array('UG_account'=>$_SESSION ['uname'],'UG_dataType'=>'glj'))->sum('UG_integral');
        // 		$this->glj = $glj1+$glj2;
        $this->display('jjjl'); // 输出模板

    }
    // 金币转账
    public function jbzz() {
        header("Content-Type:text/html; charset=utf-8");
        $userData = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();
        $this->userData = $userData;
        $this->display('jbzz');
    }
    // 金币转账处理
    public function jbzzcl() {
        if (IS_POST) {
            $pin_zs = M('pin')->where(array('user' => $_SESSION['uname'], 'zt' => 0))->count();
            $data_P = I('post.');
            //$user = M ( 'user' )->where ( array (UE_account => $_SESSION ['uname']) )->find ();
            //$user1 = M ();
            $user_df = M('user')->where(array(UE_account => $data_P['user']))->find();
            //! $this->check_verify ( I ( 'post.yzm' ) )
            //! $user1->autoCheckToken ( $_POST )
            $userxx = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();
            //dump($userxx);die;
            //$userxx['ue_secpwd']<>md5($data_P['ejmm'])
            if (false) {
                die("<script>alert('二级密码输入有误！');history.back(-1);</script>");
            } else {
                $jbhe = $data_P['sh'];
                if (!preg_match('/^[0-9]{1,10}$/', $data_P['sh']) || !$data_P['sh'] > 0) {
                    die("<script>alert('数量输入有勿！');history.back(-1);</script>");
                } elseif ($pin_zs < $jbhe) {
                    die("<script>alert('诚信码不足！');history.back(-1);</script>");
                } elseif (!$user_df) {
                    die("<script>alert('对方账号不存在！');history.back(-1);</script>");
                    //}elseif ($user_df['sfjl']=='0') {
                    //	die("<script>alert('对方不是经理,不可转出！');history.back(-1);</script>");

                } else {
                    $pin_zs_df = M('pin')->where(array('user' => $data_P['user'], 'zt' => 0))->count();
                    for ($i = 0;$i < $data_P['sh'];$i++) {
                        $pin_xx = M('pin')->where(array('user' => $_SESSION['uname'], 'zt' => 0))->find();
                        M('pin')->where(array('id' => $pin_xx['id'], 'zt' => 0))->save(array('user' => $data_P['user']));
                    }
                    $pin_zs_xz = M('pin')->where(array('user' => $_SESSION['uname'], 'zt' => 0))->count();
                    $pin_zs_df_xz = M('pin')->where(array('user' => $data_P['user'], 'zt' => 0))->count();
                    $note3 = "激活码转出";
                    $record3["UG_account"] = $_SESSION['uname']; // 登入转出账户
                    $record3["UG_type"] = 'mp';
                    $record3["UG_allGet"] = $pin_zs; // 金币
                    $record3["UG_money"] = '-' . $jbhe; //
                    $record3["UG_balance"] = $pin_zs_xz; // 当前推荐人的金币馀额
                    $record3["UG_dataType"] = 'jbzc'; // 金币转出
                    $record3["UG_note"] = $note3; // 推荐奖说明
                    $record3["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作时间
                    $reg4 = M('userget')->add($record3);
                    $note3 = "激活码转入";
                    $record3["UG_account"] = $data_P['user']; // 登入转出账户
                    $record3["UG_type"] = 'mp';
                    $record3["UG_allGet"] = $pin_zs_df; // 金币
                    $record3["UG_money"] = '+' . $jbhe; //
                    $record3["UG_balance"] = $pin_zs_df_xz; // 当前推荐人的金币馀额
                    $record3["UG_dataType"] = 'jbzr'; // 金币转出
                    $record3["UG_note"] = $note3; // 推荐奖说明
                    $record3["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作时间
                    $reg4 = M('userget')->add($record3);
                    $this->success('转让成功!');
                }
            }
        }
    }
    public function ldtj() {
        if (IS_AJAX) {
            //$this->ajaxReturn ( array ('nr' => '验证码错误!','sf' => 0 ) );
            if (false) {
                $this->ajaxReturn(array('nr' => '验证码错误!', 'sf' => 0));
            } else {
                $user = M('user');
                $ztname = $user->where(array('UE_accName' => $_SESSION['uname'], 'UE_Faccount' => '0', 'UE_check' => '1', 'UE_stop' => '1'))->getField('ue_account', true);
                $zttj = count($ztname);
                $reg = $ztname;
                $datazs = $zttj;
                if ($zttj <= 10) {
                    $s = $zttj;
                } else {
                    $s = 10;
                }
                if ($zttj != 0) {
                    for ($i = 1;$i < $s;$i++) {
                        if ($reg != '') {
                            $reg = $user->where(array('UE_accName' => array('IN', $reg), 'UE_Faccount' => '0', 'UE_check' => '1', 'UE_stop' => '1'))->getField('ue_account', true);
                            $datazs+= count($reg);
                        }
                    }
                }
                $this->ajaxReturn(array('nr' => $datazs, 'sf' => 0));
            }
        }
    }
    public function zzjl() {
        $User = M('userjyinfo'); // 实例化User对象
        $map['UJ_usercount'] = $_SESSION['uname'];
        $map['UJ_dataType'] = 'zs';
        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        //dump($var)
        $page = new \Think\Page($count, 12); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        // $page->lastSuffix=false;
        $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录    第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('last', '末页');
        $page->setConfig('first', '首页');
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');;
        $show = $page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where($map)->order('UJ_ID DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
        //dump($list);die;
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display('zzjl');
    }
    public function paizzcl() {
        if (IS_POST) {
            $pin_zs = M('paidan')->where(array('user' => $_SESSION['uname'], 'zt' => 0))->count();
            $data_P = I('post.');
            //$user = M ( 'user' )->where ( array (UE_account => $_SESSION ['uname']) )->find ();
            //$user1 = M ();
            $user_df = M('user')->where(array(UE_account => $data_P['user']))->find();
            //! $this->check_verify ( I ( 'post.yzm' ) )
            //! $user1->autoCheckToken ( $_POST )
            $userxx = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();
            //dump($userxx);die;
            //$userxx['ue_secpwd']<>md5($data_P['ejmm'])
            if (false) {
                die("<script>alert('二级密码输入有误！');history.back(-1);</script>");
            } else {
                $jbhe = $data_P['sh'];
                if (!preg_match('/^[0-9]{1,10}$/', $data_P['sh']) || !$data_P['sh'] > 0) {
                    die("<script>alert('数量输入有勿！');history.back(-1);</script>");
                } elseif ($pin_zs < $jbhe) {
                    die("<script>alert('排单码不足！');history.back(-1);</script>");
                } elseif (!$user_df) {
                    die("<script>alert('对方账号不存在！');history.back(-1);</script>");
                    //}elseif ($user_df['sfjl']=='0') {
                    //	die("<script>alert('对方不是经理,不可转出！');history.back(-1);</script>");

                } else {
                    $pin_zs_df = M('paidan')->where(array('user' => $data_P['user'], 'zt' => 0))->count();
                    for ($i = 0;$i < $data_P['sh'];$i++) {
                        $pin_xx = M('paidan')->where(array('user' => $_SESSION['uname'], 'zt' => 0))->find();
                        M('paidan')->where(array('id' => $pin_xx['id'], 'zt' => 0))->save(array('user' => $data_P['user']));
                    }
                    $pin_zs_xz = M('paidan')->where(array('user' => $_SESSION['uname'], 'zt' => 0))->count();
                    $pin_zs_df_xz = M('paidan')->where(array('user' => $data_P['user'], 'zt' => 0))->count();
                    $note3 = "排单码转出" . $data_P['user'];
                    $record3["UG_account"] = $_SESSION['uname']; // 登入转出账户
                    $record3["UG_type"] = 'pd';
                    $record3["UG_allGet"] = $pin_zs; // 金币
                    $record3["UG_money"] = '-' . $jbhe; //
                    $record3["UG_balance"] = $pin_zs_xz; // 当前推荐人的金币馀额
                    $record3["UG_dataType"] = 'jbzc'; // 金币转出
                    $record3["UG_note"] = $note3; // 推荐奖说明
                    $record3["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作时间
                    $reg4 = M('userget')->add($record3);
                    $note3 = "从" . $_SESSION['uname'] . "排单码转入";
                    $record3["UG_account"] = $data_P['user']; // 登入转出账户
                    $record3["UG_type"] = 'pd';
                    $record3["UG_allGet"] = $pin_zs_df; // 金币
                    $record3["UG_money"] = '+' . $jbhe; //
                    $record3["UG_balance"] = $pin_zs_df_xz; // 当前推荐人的金币馀额
                    $record3["UG_dataType"] = 'jbzr'; // 金币转出
                    $record3["UG_note"] = $note3; // 推荐奖说明
                    $record3["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作时间
                    $reg4 = M('userget')->add($record3);
                    $this->success('转让成功!');
                }
            }
        }
    }
    public function new_first() {
        var_dump($_POST);
    }
    //买入【开仓】
    public function tgbzcl()
	{
        if (IS_POST)
		{
            $data_P = I('post.');
			$money = $data_P['amount'];
			$secpwd = $data_P['secpwd'];

			if (!isset($secpwd)) {
				$this->ajaxReturn(array('nr' => '请输入二级密码', 'sf' => 0));
			}

            $user = M('user')->where(array(UE_account => $_SESSION['uname'], UE_check => 1))->find();
            if (!$user) {
				$this->ajaxReturn(array('nr' => '该帐号未激活,不能进行操作', 'sf' => 0));
            }

            if (md5($secpwd) != $user['ue_secpwd']) {
            	$this->ajaxReturn(array('nr' => '二级密码不正确', 'sf' => 0));
            }

            //判断通证积分是否够用20190511 start******************
            //判断是否开启了自动排单功能
            if( $user['isyuyue'] == 1 && (time() > strtotime( $user['yuyue_cur_time']) ) ){
                $this->ajaxReturn(array('nr' => '该帐号已开启自动排单，不能再手动下单', 'sf' => 0));
            }


            //判断通证积分是否够用
            $jhm_pass_rate = C('jhm_pass_rate');
            $percent = C("prepaypercent");
            if( $percent > 0 ){
                $cur_money_pre = $data_P ['amount'] * $percent / 100;
                $cur_money_next = $data_P ['amount'] * (100 - $percent) / 100;
                if( $user['pdmnum'] < ( ( $cur_money_pre + $cur_money_next ) * $jhm_pass_rate ) ){
                    $this->ajaxReturn(array('nr' => '通证积分不足,不能进行操作', 'sf' => 0));
                }
            }else if( $user['pdmnum'] < ( $money * $jhm_pass_rate ) ){
                $this->ajaxReturn(array('nr' => '通证积分不足,不能进行操作', 'sf' => 0));
            }
            //判断通证积分是否够用20190511 end******************

            $first_paidan = M('tgbz')->where("user='" . $_SESSION['uname'] . "'")->find();
            if ($first_paidan == null && $user['is_first'] == 1) {
				$this->ajaxReturn(array('nr' => '/index/question', 'sf' => 2));
            }
            $usermm = M('user')->where(array(UE_account => $_SESSION['uname']))->find();
            $tgbz_time = M('tgbz')->where("user='" . $_SESSION['uname'] . "' ")->max('date');
            if (C("tgbz_time") > 0) {
                if ((strtotime($tgbz_time) + C("tgbz_time") * 3600) > time()) {
					$this->ajaxReturn(array('nr' => '你距离上次排单时间不足' . C('tgbz_time') . '小时', 'sf' => 0));
                }
            }

            //是否开启时间限制
            if (C('time_limit'))
			{
                if(!(date("H") >= C('paidan_time_start') && date("H") <= C('paidan_time_end')))
				{
					$this->ajaxReturn(array('nr' => "不好意思,排单时间为" . C('paidan_time_start') . "点到" . C('paidan_time_end') . "点", 'sf' => 0));
                }
            }

            //每天排单数量
            $paidan_num = C('paidan_num');
            if ($paidan_num >= 0)
			{
                $uname = $_SESSION['uname'];
                $starttime = date('Y-m-d 00:00:00', time());
                $endtime = date('Y-m-d 23:59:59', time());
                $countdata = M("tgbz")->field('mainid')->where("date>='$starttime' and date<='$endtime' and user='$uname'")->group('mainid')->select();
                $count = count($countdata);
                if (($count+1) > $paidan_num)
				{
					$this->ajaxReturn(array('nr' => '今日排单数量已满，欢迎明日再来!', 'sf' => 0));
                }
            }

            //平台每天开仓排单总数量
            $today_all_paidan_num = C('today_all_paidan_num');
            if( $today_all_paidan_num > 0 )
            {
                //平台所有的排单数量
                $cur_starttime = date('Y-m-d 00:00:00', time());
                $cur_endtime = date('Y-m-d 23:59:59', time());
                $cur_countdata = M("tgbz")->field('mainid')->where("date>='$cur_starttime' and date<='$cur_endtime'")->group('mainid')->select();
                $cur_count = count($cur_countdata);
                if (($cur_count+1) > $today_all_paidan_num)
                {
                    $this->ajaxReturn(array('nr' => '今日平台排单数量已满，欢迎明日再来!', 'sf' => 0));
                }
            }

            //每天排单总额度
//            $paidan_jbs = C('paidan_jbs');
//            if ($paidan_jbs > 0)
//			{
//                $sum = M("tgbz")->where("date>='$starttime' and date<='$endtime' ")->sum('jb');
//                if (($sum + $money) > $paidan_jbs) {
//					$this->ajaxReturn(array('nr' => '今日排单额度已满，记得明日抢早排单哦!', 'sf' => 0));
//                }
//            }
            //今日开仓总量是否达到最大值
            $today_kczl_status = get_today_kczl_status($money);
            if ( $today_kczl_status == 0 ) {
					$this->ajaxReturn(array('nr' => '今日排单额度已满，记得明日抢早排单哦!', 'sf' => 0));
            }

			//防撞单
            //修改------>防撞单功能：默认是开启防撞单，前台有防撞单按钮，如果开启防撞单，
			//那么此会员只能存在一笔未完成订单，需要完成这笔订单，才能继续下笔订单，
			//如果点击不开启的话，可以同时存在多笔订单进行排单
            $tgbz= M('tgbz');
            $where=array();
            $where['user'] = $_SESSION ['uname'];
            $where['qr_zt'] =  array('eq',0);
            $where['isprepay'] = 1;//预付款完成否，防撞
            $where['isreset'] =  array('in',array(0,3));
            $rs=$tgbz->where($where)->find();
            if ($user['fangzhuang'] == 1 && $rs )
            {
			     $this->ajaxReturn(array('nr' => "你已启用防撞单功能,您还有未完成的订单未处理，不能继续申请", 'sf' => 0));
            }


            //用户买入最多允许等待匹配单数
            $oneByone = C('oneByone');
            if ($oneByone > 0) {
                $tgbz_count = M('tgbz')->where("user='" . $_SESSION['uname'] . "' and zt =0")->count();
                if ($tgbz_count > $oneByone) {
					$this->ajaxReturn(array('nr' => "用户买入最多允许等待匹配" . $oneByone . "单！", 'sf' => 0));
                }
            }
            $peidui = C('peidui');
            if ($peidui > 0) {
                $ppdd = M('ppdd');
                $where = array();
                $where['g_user'] = $_SESSION['uname']; //这里用g_user也就是确认收款这。及时确认
                $where['zt'] = array('NEQ', 2);
                $rs = $ppdd->where($where)->count();
                if ($rs) {
					$this->ajaxReturn(array('nr' => "您还有未完成的" . $peidui . "个订单未处理，不能继续申请", 'sf' => 0));
                }
            }





		    $tg_min = get_min();
			$tg_max = get_max();
            if ($data_P['zffs1'] <> '1' && $data_P['zffs2'] <> '1' && $data_P['zffs3'] <> '1') {
				$this->ajaxReturn(array('nr' => '至少选择一个收款方式！', 'sf' => 0));
            } elseif ($money < $tg_min || $money > $tg_max || $money % C("jj01") > 0) {
				$this->ajaxReturn(array('nr' => "帮助金额" . $tg_min . "-" . $tg_max . ",并且是" . C("jj01") . "的倍数！", 'sf' => 0));
            } elseif ($money % C("jj01") > 0) {
				$this->ajaxReturn(array('nr' => "帮助金额" . $tg_min . "-" . $tg_max . ",并且是" . C("jj01") . "的倍数！", 'sf' => 0));
            } else {
                $timea = time();
                $kssj = strtotime($user['date_leiji']) + 86400 * 30;
                $startTime = date('Y-m-d H:i:s', $kssj);
                if ($user['tz_leiji'] == '0' || $timea >= $kssj) {
                    M('user')->where(array(UE_account => $_SESSION['uname']))->save(array('date_leiji' => date('Y-m-d H:i:s', $timea), 'tz_leiji' => '0'));

                }
                if ($user['tz_leiji'] + $money > C("month_max")) {
					$this->ajaxReturn(array('nr' => "当前投资加当月累计超过" . $user['tz_leiji'] . '|' . C("month_max") . ",请在" . $startTime . "以后在试！", 'sf' => 0));
                }

                //排单码
                $paidancount = get_userinfo($_SESSION['uname'],'pdmnum');
                if ($paidancount < 1) {
                    $this->ajaxReturn(array('nr' => C('pdm_name').'余额不足!', 'sf' => 0));
                }

				//按照每排单多少扣除
				if(C('paidanb_every') > 0 &&  C('paidanb_count') > 0)
				{
				    $paidanb = ceil(ceil($data_P ['amount'] / C('paidanb_every')) * C('paidanb_count'));
                    if ($paidancount < $paidanb)
					{
						$this->ajaxReturn(array('nr' => C('pdm_name').'余额不足!', 'sf' => 0));
                    }else
					{
					    //减去排单码【通证积分】
						M('user')->where(array('UE_account' =>$_SESSION['uname']))->setDec('pdmnum', $paidanb);

						//计算通证积分变动后后的级差奖励
                        calculation_reward_pdnum($user['ue_id'],$paidanb);

                        //增加排单码记录【通证积分】
                        $map['user'] = $_SESSION['uname'];
					    $map['type']= 'pd';
                        $map['info']= '';
						$map['yue']= get_userinfo($_SESSION['uname'],'pdmnum');
                        $map['num']= -$paidanb;
                        $map['date']= date('Y-m-d H:i:s', time());
                        $paidan_log_newid = M('paidan_log')->add($map);
					}
				}

                //支付方式
                if ($data_P['zffs1'] == '1') {
                    $data['zffs1'] = '1';
                } else {
                    $data['zffs1'] = '0';
                }
                if ($data_P['zffs2'] == '1') {
                    $data['zffs2'] = '1';
                } else {
                    $data['zffs2'] = '0';
                }
                if ($data_P['zffs3'] == '1') {
                    $data['zffs3'] = '1';
                } else {
                    $data['zffs3'] = '0';
                }
                $data['user'] = $user['ue_account'];
				$data['priority'] = $user['priority'];
                $data['user_nc'] = $user['ue_theme'];
                $data['user_tjr'] = $user['zcr'];
                $data['date'] = date('Y-m-d H:i:s', time());
                $data['zt'] = 0;
                $data['qr_zt'] = 0;
				$data['ppjb'] = 0;
				$data['isfast'] = getUserTGBZCount() == 0 ? 1 : 0;

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

				    //此次交易是否可以增加用户的等级 start----------------------------------
                    //用户提交的当前金额属于哪个级别
                    $data_P ['amount'];
                    $cur_status = cur_level_finish_tgbz_status();//当前用户完成当前级别下的订单的状态

                    if( $cur_status == 1 ){ //即可有机会升级为下个级别
                        //当前所有用户的级别
                        $jjaccountlevel_arr = explode(',',C('jjaccountlevel'));
                        //当天所有级别的投资门槛
                        $jibei_menkan_arr = explode(',',C('jibei_menkan'));
                        $usermm['levelname'];
                        foreach( $jjaccountlevel_arr as $key => $jjaccountlevel )
                        {
                            if( $jjaccountlevel == $usermm['levelname'] ){
                                $cur_jjaccountlevel_key = $key;
                                break;
                            }
                        }
                        //判断此次买入是否可以升级用户级别【买入的金额是否与下一个等级的，投资门槛一致】
                        if( isset($cur_jjaccountlevel_key) && $data_P ['amount'] > 0 && isset($jibei_menkan_arr[$cur_jjaccountlevel_key + 1]) && $jibei_menkan_arr[$cur_jjaccountlevel_key + 1] == $data_P ['amount'] ){
                            //升级用户级别
                            $next_level = $jjaccountlevel_arr[$cur_jjaccountlevel_key + 1];//下一个级别
                            M('user')->where(array('UE_account'=>$_SESSION['uname']))->save(array('levelname'=>$next_level));
                        }
                    }
                    //此次交易是否可以增加用户的等级 end----------------------------------

					M('tgbz')->where(array('id' => $newmainid))->save(array('mainid' => $newmainid));
					if($newprepayid){
						M('tgbz')->where(array('id' => $newprepayid))->save(array('mainid' => $newmainid));
                    }
					if($paidan_log_newid)
					{
						M('paidan_log')->where(array('id' => $paidan_log_newid))->save(array('info' => '开仓消耗ID:' . $mainorderid));
					}
					$this->ajaxReturn(array('nr' => '提交成功！', 'sf' => 1));
                } else
				{
					$this->ajaxReturn(array('nr' => '提交失败！!', 'sf' => 0));
                }
            }
        }
    }



    /**
     * 激活成功会员生成一个开仓【买入】的订单
     * 1.新用户注册激活后，首个自动生成买入【开仓】的订单，不扣通证积分【扣除0个】
     * @param array $user 当前激活的新用户
     * @param int|float $amount 开仓金额
     */
    public function new_user_create_first_tgbz($user = array(),$amount = 1000.00)
    {
        //支付方式
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
        $data['isfast'] = getUserTGBZCount() == 0 ? 1 : 0;

        $money = $amount;
        //是否使用预付款拆分功能
        $percent = C("prepaypercent");
        if($percent > 0)
        {
            $money_pre = $amount * $percent / 100;
            $money_next = $amount * (100 - $percent) / 100;


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
            if($newprepayid){
                M('tgbz')->where(array('id' => $newprepayid))->save(array('mainid' => $newmainid));
            }

            //生成一条扣除排单码【通证积分】的记录【激活的新用户】20190513 start----------------------

            //生成一条记录扣除0个排单码【通证积分】的记录【激活的新用户】
            //M('user')->where(array('UE_account' =>$user['ue_account']))->setDec('pdmnum', 0);
            $map['user'] = $user['ue_account'];
            $map['type']= 'pd';
            $map['info']= '开仓消耗ID:' . $mainorderid.'，消耗0个通证';
            $map['yue']= get_userinfo($user['ue_account'],'pdmnum');
            $map['num']= 0;
            $map['date']= date('Y-m-d H:i:s', time());
            M('paidan_log')->add($map);

            //生成一条扣除排单码【通证积分】的记录【激活的新用户】20190513 start----------------------

            //$this->ajaxReturn(array('nr' => '提交成功！', 'sf' => 1));
        } else
        {
            //$this->ajaxReturn(array('nr' => '提交失败！!', 'sf' => 0));
        }
    }
    //卖出【交割】
    public function jsbzcl_bx()
	{
        if (IS_POST) {
            $data_P = I('post.');
            if (!isset($data_P['secpwd'])) {
        		return_die_ajax("请输入二级密码!");
            }

            $user = M('user')->where(array(UE_account => $_SESSION['uname'], UE_check => 1))->find();
            if (!$user) {
				return_die_ajax('该帐号未激活 不能进行操作');
            }

            if (md5($data_P['secpwd']) != $user['ue_secpwd']) {
        		return_die_ajax("二级密码不正确!");
            }

            check_tx_status();

            $user1 = M();
            $usermm = M('user')->where(array(UE_account => $_SESSION['uname']))->find();
            $today = today_qb_get($_SESSION['uname']);

            $limit_get = level_limit_get($usermm['levelname'], C('qb_tx_day'));

            $tuijian_tixian = M('tixian')->where(array(UG_account => $_SESSION['uname']))->max('addtime');
            if ($tuijian_tixian) {
                $now = time();
                if ($now < (strtotime($tuijian_tixian) + 24 * 60 * 60)) {
					return_die_ajax('钱包和领导奖每天只能提一个哦 并且只能一次哦！');
                }
            }
            if ($limit_get - ($today + $data_P['get_amount']) < 0) {
				return_die_ajax("你的钱包当天只能提现" . $limit_get );
            }

            $jl_baifenbi = C('jl_baifenbi');

            $max_jb = $usermm['ue_money'] * $jl_baifenbi / 100;

            if ($data_P['get_amount'] > $max_jb) {
				return_die_ajax("钱包每轮只能最多提取总额的" . $jl_baifenbi . "%" . $limit_get );
            }
            $jl_start = C('jl_start');
            $jl_e = C('jl_e');
            $jl_beishu = C('jl_beishu');
            if ($data_P['get_amount'] > $jl_e) {
				return_die_ajax("钱包提现超过最大额度" . $jl_e );
            }
            if ($data_P['get_amount'] < $jl_start) {
				return_die_ajax("钱包提现小于最低额度" . $jl_start);
            }
            if (($data_P['get_amount'] % $jl_beishu) != "0") {
				return_die_ajax("钱包提现必须是" . $jl_beishu . "的倍数！");
            }
            //每天每个用户提现次数
            $tixian_num = C('user_tuijian_day_num');
            if ($tixian_num > 0) {
                $uname = $_SESSION['uname'];
                $starttime = date('Y-m-d 00:00:01', time());
                $endtime = date('Y-m-d 23:59:59', time());
                $count = M("jsbz")->where("date>='$starttime' and date<='$endtime' and user='$uname'")->count();
                if ($count >= $tixian_num) {
					return_die_ajax("提现失败，每天每个用户只允许提现" . $tixian_num . "次！");
                }
            }
            //每天所有用户提现次数
            $num_tx_day = C('num_tx_day');
            if ($num_tx_day > 0) {
                $starttime = date('Y-m-d 00:00:01', time());
                $endtime = date('Y-m-d 23:59:59', time());
                $count = M("jsbz")->where("date>='$starttime' and date<='$endtime' ")->count();
                if ($count >= $num_tx_day) {
					return_die_ajax("提现失败，每天所有用户只允许提现" . $num_tx_day . "次！");
                }
            }
            /*$tgbz_num = M('jsbz')->where(array('user'=>$_SESSION['uname'],'qr_zt'=>1))->find();
            $dealing_num = $tgbz_num;
            if( $dealing_num > 0)
            {
            die("<script>alert('您还有未完成的订单未处理，不能继续申请');history.back(-1);</script>");
            }*/
            //if ($data_P ['zffs1']<>'1'&&$data_P ['zffs2']<>'1'&&$data_P ['zffs3']<>'1') {
            if (false) {
				return_die_ajax("至少选择一种收款方式！");
            } elseif ($data_P['get_amount'] < C("txthemin")) {
				return_die_ajax("买出金额" . C("txthemin") . "起并且是" . C("txthebeishu") . "的倍数！");
            } elseif ($data_P['get_amount'] % C("txthebeishu") > 0) {
				return_die_ajax("买出金额" . C("txthemin") . "起并且是" . C("txthebeishu") . "的倍数！");
            } elseif ($data_P['get_amount'] > C("txthemax")) {
				return_die_ajax("买出最大金额为" . C("txthemax"));
            } elseif ($user['ue_money'] < $data_P['get_amount']) {
				return_die_ajax("余额不足！" );
            } else {
                //支付方式
                if ($data_P['zffs1'] == '1') {
                    $data['zffs1'] = '1';
                } else {
                    $data['zffs1'] = '0';
                }
                if ($data_P['zffs2'] == '1') {
                    $data['zffs2'] = '1';
                } else {
                    $data['zffs2'] = '0';
                }
                if ($data_P['zffs3'] == '1') {
                    $data['zffs3'] = '1';
                } else {
                    $data['zffs3'] = '0';
                }
                $data['user'] = $user['ue_account'];
                $data['jb'] = $data_P['get_amount'];
                $data['user_nc'] = $user['ue_theme'];
                $data['user_tjr'] = $user['zcr'];
                $data['date'] = date('Y-m-d H:i:s', time());
				$data['ppjb'] = 0;
				$data['total'] = $data_P['get_amount'];
                $data['zt'] = 0;
				$data['qb'] = 0;
				$data['orderid'] = createorderid('G');
                $data['qr_zt'] = 0;
                $user_zq = M('user')->where(array('UE_ID' => $_SESSION['uid']))->find();
                M('user')->where(array('UE_account' => $_SESSION['uname']))->setDec('UE_money', $data_P['get_amount']);
                $user_xz = M('user')->where(array('UE_ID' => $_SESSION['uid']))->find();
                $note3 = "买出扣款";
                $record3["UG_account"] = $_SESSION['uname'];
                $record3["UG_type"] = 'jb';
                $record3["UG_allGet"] = $user_zq['ue_money'];
                $record3["UG_money"] = '-' . $data_P['get_amount']; //
                $record3["UG_balance"] = $user_xz['ue_money'];
                $record3["UG_dataType"] = 'jsbz';
                $record3["UG_note"] = $note3;
                $record3["UG_getTime"] = date('Y-m-d H:i:s', time());
                $jsbz_id = M('jsbz')->add($data);
                $record3["jsbzID"] = $jsbz_id;
                $reg4 = M('userget')->add($record3);
                if ($jsbz_id) {
					M('jsbz')->where(array('id' => $jsbz_id))->save(array('mainid' => $jsbz_id));
                    return_die_ajax("提交成功！",true,1);
                } else {
                    return_die_ajax("提交失败！");
                }
            }
        }
    }

    public function jsbzcl_jj()
	{
        if (IS_POST)
		{
			check_tx_status();
            $data_P = I('post.');

            if (!isset($data_P['secpwd'])) {
        		return_die_ajax("请输入二级密码!");
            }
            $user = M('user')->where(array(UE_account => $_SESSION['uname'], UE_check => 1))->find();
            if (!$user) {
				return_die_ajax("该帐号未激活!");
            }

            if (md5($data_P['secpwd']) != $user['ue_secpwd']) {
        		return_die_ajax("二级密码不正确!");
            }

            if (isset($_SESSION['num_tx_day']) && ($_SESSION['num_tx_day'] <= 0)) {
				return_die_ajax("交割次数已达系统上限！");
            }
            $usermm = M('user')->where(array(UE_account => $_SESSION['uname']))->find();
            $today = today_get($_SESSION['uname'], 2);

            $limit_get = level_limit_get($usermm['levelname'], C('tjj_tx_day'));

            if ($limit_get - ($today + $data_P['get_amount']) < 0) {
				return_die_ajax("你的交割积分当天只能提现" . $limit_get);
            }

            $tj_baifenbi = C('tj_baifenbi');
            $max_jb = $usermm['qwe'] * $tj_baifenbi / 100;

            if ($data_P['get_amount'] > $max_jb) {
				return_die_ajax("交割积分每轮只能最多提取总额的" . $tj_baifenbi . "%");
            }
            $tj_start = C('tj_start');
            $tj_e = C('tj_e');
            $tj_beishu = C('tj_beishu');
            //推荐奖提现总额
            $tx_tuijian_total = C('tx_tuijian_total');

            $tuijian_tixian = M('tixian')->where(array(UG_account => $_SESSION['uname']))->sum('TX_money');

            if ($tuijian_tixian > $tx_tuijian_total) {
				return_die_ajax("交割积分提现超过总额限制" . $tx_tuijian_total);
            }
            if ($data_P['get_amount'] > $tj_e) {
				return_die_ajax("交割积分提现超过最大额度".$tj_e);
            }
            if ($data_P['get_amount'] > $user['qwe']) {
				return_die_ajax("交割积分提现超过钱包余额");
            }
            if ($data_P['get_amount'] < $tj_start) {
				return_die_ajax("交割积分提现小于最低额度".$tj_start);
            }
            if (($data_P['get_amount'] % $tj_beishu) != "0") {
				return_die_ajax("交割积分提现必须是" . $tj_beishu . "的倍数！");
            }

			//买出金额不小于上一轮的百分比设置：
			$limit_amount = get_js_min_compare_last();
            if ($data_P['amount'] < $limit_amount) {
				return_die_ajax("最低交割为" . $limit_amount);
            }

            /*	$ppdd= M('ppdd');
            $where=array();
            $where['g_user'] = $_SESSION ['uname'];
            $where['zt'] =array('NEQ',2);
            $ppdd_num=$ppdd->where($where)->count();*/
            /*$ppdd= M('ppdd');
            $where=array();
            $where['p_user'] = $_SESSION ['uname'];
            $where['zt'] =array('NEQ',2);
            $ppdd_num=$ppdd->where($where)->count();
            
            $tgbz_num = M('tgbz')->where(array('user'=>$_SESSION['uname'],'zt'=>0))->count();
            $dealing_num = $ppdd_num + $tgbz_num;
            $tgbz_ok_num = $ppdd->where(array('user'=>$_SESSION['uname'],'zt'=>2))->count();
            $tgbz_nums = M('tgbz')->where(array('user'=>$_SESSION['uname']))->count();
            $lingdao_ok_num = M('jsbz')->where(array('user'=>$_SESSION['uname'],'qb'=>2))->count();
            $cha = $lingdao_ok_num - $tgbz_nums;
            if( $dealing_num > 0)
            {
            die("<script>alert('您还有未完成的订单，不能继续申请');history.back(-1);</script>");
            }elseif($tgbz_ok_num == 0 || $cha >= 0){
            die("<script>alert('您须要先提供过帮助，才能继续申请');history.back(-1);</script>");
            }*/
            //if ($data_P ['zffs1']<>'1'&&$data_P ['zffs2']<>'1'&&$data_P ['zffs3']<>'1') {
            if (false) {
				return_die_ajax("至少选择一种收款方式！");
            } elseif ($data_P['get_amount'] < C("txthemin")) {
				return_die_ajax("交割" . C("txthemin") . "起并且是" . C("txthebeishu") . "的倍数！");
            } elseif ($data_P['get_amount'] % C("txthebeishu") > 0) {
				return_die_ajax("交割" . C("txthemin") . "起并且是" . C("txthebeishu") . "的倍数！");
            } elseif ($user['qwe'] < $data_P['get_amount']) {
				return_die_ajax("交割积分不足！");
            } else {
                if ($data_P['zffs1'] == '1') {
                    $data['zffs1'] = '1';
                } else {
                    $data['zffs1'] = '0';
                }
                if ($data_P['zffs2'] == '1') {
                    $data['zffs2'] = '1';
                } else {
                    $data['zffs2'] = '0';
                }
                if ($data_P['zffs3'] == '1') {
                    $data['zffs3'] = '1';
                } else {
                    $data['zffs3'] = '0';
                }
                $data['user'] = $user['ue_account'];
                $data['jb'] = $data_P['get_amount'];
                $data['user_nc'] = $user['ue_theme'];
                $data['user_tjr'] = $user['zcr'];
                $data['date'] = date('Y-m-d H:i:s', time());
                $data['zt'] = 0;
				$data['ppjb'] = 0;
				$data['total'] = $data_P['get_amount'];
				$data['orderid'] = createorderid('G');
                $data['qr_zt'] = 0;
                $data['qb'] = 2;
                $user_zq = M('user')->where(array('UE_ID' => $_SESSION['uid']))->find();
                M('user')->where(array('UE_account' => $_SESSION['uname']))->setDec('tj_he', $data_P['get_amount']);
                M('user')->where(array('UE_account' => $_SESSION['uname']))->setDec('qwe', $data_P['get_amount']);
                $user_xz = M('user')->where(array('UE_ID' => $_SESSION['uid']))->find();

                $note3 = "买出扣款";
                $record3["UG_account"] = $_SESSION['uname']; // 登入转出账户
                $record3["UG_type"] = 'jb';
                $record3["UG_allGet"] = $user_zq['qwe']; // 金币
                $record3["UG_money"] = '-' . $data_P['get_amount']; //
                $record3["UG_balance"] = $user_xz['qwe']; // 当前推荐人的金币馀额
                $record3["UG_dataType"] = 'jsbz2'; // 金币转出
                $record3["UG_note"] = $note3; // 推荐奖说明
                $record3["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作时间
                $reg4 = M('userget')->add($record3);
                $oid = M('jsbz')->add($data);
                $tixian['oid'] = $oid;
                $tixian['zffs'] = 2;
                $tixian['UG_account'] = $user['ue_account'];
                $tixian['TX_money'] = $data_P['get_amount'];
                $tixian['status'] = '0';
                $tixian['addtime'] = date('Y-m-d H:i:s', time());
                if (M('tixian')->add($tixian))
				{
					M('jsbz')->where(array('id' => $oid))->save(array('mainid' => $oid));
                    $num_tx_day = C('num_tx_day');
                    $_SESSION['num_tx_day'] = isset($_SESSION['num_tx_day']) ? ($_SESSION['num_tx_day'] - 1) : ($num_tx_day - 1);
					return_die_ajax("交割成功！",true,1);
                } else {
					return_die_ajax("交割失败！");
                }
            }
        }
    }
    public function tgbz_del() {
        if (!preg_match('/^[0-9]{1,10}$/', I('get.id'))) {
            $this->success('非法操作,将冻结账号!');
        } else {
            $userinfo = M('tgbz')->where(array('id' => I('get.id'), 'zt' => '0'))->find();
            //dump(I ('get.id'));
            //dump($userinfo['ue_accname']);die;
            if ($userinfo['user'] <> $_SESSION['uname']) {
                $this->success('订单当前状态不可取消!');
            } else {
                lkdsjfsdfj($userinfo['user'], '-' . $userinfo['jb']);
                $reg = M('tgbz')->where(array('id' => I('get.id')))->delete();
                if ($reg) {
                    die("<script>alert('取消成功!');window.location.href='/';</script>");
                } else {
                    die("<script>alert('取消失败!');window.location.href='/';</script>");
                }
            }
        }
    }

    //开仓订单
    public function open_granary_order()
    {

        /// <!--||||||||||买入订单匹配，以下-->
        //////////////////----------
        $User = M('ppdd'); // 实例化User对象
        $map2['p_user'] = $_SESSION['uname'];
        $map2['zt'] = array('in', array('0', '1'));

        $count2 = $User->alias('ppdd')
            ->field('ppdd.*')
            ->join("LEFT JOIN ot_tgbz tgbz on ppdd.p_id = tgbz.id where ppdd.p_user='"
                    . $_SESSION['uname']
                    . "' and ppdd.zt in (0,1)"
                    ." and tgbz.isprepay = 1"
            )
                    //. "' and mainid ="
                    //. $main_tgbz['mainid'])
            ->count();

        //$count2 = $User->where($map2)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p2 = getpage($count2, $count2);
        //$plist = $User->where($map2)->order('id DESC')->limit($p2->firstRow, $p2->listRows)->select();
        $plist = $User->alias('ppdd')
            ->field('ppdd.*')
            ->join("LEFT JOIN ot_tgbz tgbz on ppdd.p_id = tgbz.id where ppdd.p_user='"
                . $_SESSION['uname']
                . "' and ppdd.zt in (0,1)"
                ." and tgbz.isprepay = 1"
            )
            //. "' and mainid ="
            //. $main_tgbz['mainid'])
            ->order('ppdd.id DESC')->limit($p2->firstRow, $p2->listRows)
            ->select();
        $plist = getPDinfo($plist);
        $this->assign('pp_p_list', $plist); // 赋值数据集
        $this->assign('pp_p_page', $p2->show()); // 赋值分页输出
        /////////////////----------------



        //<!--|||||||买入订单列表，以下-->
        $User = M('tgbz');
        $sall = ' and ppjb <= total ';
        $map['user'] = $_SESSION['uname'];
        $map['_string'] = 'mainid = id' . $sall;
        $map['zt'] = array(in, array('0', '6','1'));
        //$map['isprepay'] = array('eq','1');
        $count = $User->where($map)->count();
        $p = getpage($count, $count);
        $plist = $User->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
        //获取开仓订单中最新一条的支付时间为订单的时间
        $ppddModel = M('ppdd');
        foreach( $plist as $key => $item )
        {
            $cur_tgbz_id_list = $User->field('id')->where(array('mainid'=>$item['mainid'],'isprepay'=>1))->select();
            $tgbz_ids = [];
            foreach( $cur_tgbz_id_list as $cur_tgbz_id )
            {
                $tgbz_ids[] = $cur_tgbz_id['id'];
            }
            //查询出匹配ppdd表中对应的最新一条的打款时间
            if( !empty($tgbz_ids) ){
                $cur_map['p_id'] = array('in',$tgbz_ids);
                $ppddData = $ppddModel->where($cur_map)->order('date_hk desc')->limit(1)->select();
                $item['date'] = !empty($ppddData[0]['date_hk']) ? $ppddData[0]['date_hk'] : $item['date'];
            }
            $plist[$key] = $item;
        }


        /*
        foreach( $plist as $key => $item ){
            $ppdd_data = M('ppdd')->where(array('p_id'=>$item['id']))->find();
            $item['user_jj_id'] = 0;
            if( !empty($ppdd_data) ){
                $user_jj_data = M('user_jj')->where(array('r_id'=>$ppdd_data['id']))->find();
                $item['user_jj_id'] = $user_jj_data['id'];
            }
            $plist[$key] = $item;
        }
        */
        $this->assign('plist', $plist);
        $this->assign('ppage', $p->show());
        $this->assign('pcount',$count);
        $this->display('open_granary_order');
    }
    //平仓订单
    public function fill_in_order()
    {

        /// <!--||||||||||买入订单匹配，以下-->
        //////////////////----------
        $User = M('ppdd'); // 实例化User对象
        $map2['p_user'] = $_SESSION['uname'];
        $map2['zt'] = array('in', array('0', '1'));

        $count2 = $User->alias('ppdd')
            ->field('ppdd.*')
            ->join("LEFT JOIN ot_tgbz tgbz on ppdd.p_id = tgbz.id where ppdd.p_user='"
                . $_SESSION['uname']
                . "' and ppdd.zt in (0,1)"
                ." and tgbz.isprepay = 0"
            )
            //. "' and mainid ="
            //. $main_tgbz['mainid'])
            ->count();

        //$count2 = $User->where($map2)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p2 = getpage($count2, $count2);
        //$plist = $User->where($map2)->order('id DESC')->limit($p2->firstRow, $p2->listRows)->select();
        $plist = $User->alias('ppdd')
            ->field('ppdd.*')
            ->join("LEFT JOIN ot_tgbz tgbz on ppdd.p_id = tgbz.id where ppdd.p_user='"
                . $_SESSION['uname']
                . "' and ppdd.zt in (0,1)"
                ." and tgbz.isprepay = 0"
            )
            //. "' and mainid ="
            //. $main_tgbz['mainid'])
            ->order('ppdd.id DESC')->limit($p2->firstRow, $p2->listRows)
            ->select();
        $plist = getPDinfo($plist);
        $this->assign('pp_p_list', $plist); // 赋值数据集
        $this->assign('pp_p_page', $p2->show()); // 赋值分页输出
        /////////////////----------------


        //点击确认收到钱后，显示

        //<!--|||||||买入订单列表，以下-->
        $User = M('tgbz');
//        $sall = ' and ppjb < total ';
        $map['user'] = $_SESSION['uname'];
//        $map['_string'] = 'mainid = id' . $sall;
        //$map['zt'] = array(in, array('0', '6','1'));
        $map['zt'] = array(in, array('1'));
        $map['qr_zt'] = array('eq',1);
        //$map['isprepay'] = array('eq','1');
        $count_data = $User->where($map)->group('mainid')->select();
        $count = count($count_data);
        $p = getpage($count, $count);
        $plist = $User->where($map)->group('mainid')->order('id DESC')->limit($p->firstRow, $p->listRows)->select();

        //获取平仓订单中最新一条的支付时间为订单的时间
        $ppddModel = M('ppdd');
        foreach( $plist as $key => $item )
        {
            $cur_tgbz_id_list = $User->field('id')->where(array('mainid'=>$item['mainid'],'isprepay'=>0))->select();
            $tgbz_ids = [];
            foreach( $cur_tgbz_id_list as $cur_tgbz_id )
            {
                $tgbz_ids[] = $cur_tgbz_id['id'];
            }
            //查询出匹配ppdd表中对应的最新一条的打款时间
            if( !empty($tgbz_ids) ){
                $cur_map['p_id'] = array('in',$tgbz_ids);
                $ppddData = $ppddModel->where($cur_map)->order('date_hk desc')->limit(1)->select();
                $item['date'] = !empty($ppddData[0]['date_hk']) ? $ppddData[0]['date_hk'] : $item['date'];
            }
            $plist[$key] = $item;
        }
        /*
        foreach( $plist as $key => $item ){
            $ppdd_data = M('ppdd')->where(array('p_id'=>$item['id']))->find();
            $item['user_jj_id'] = 0;
            if( !empty($ppdd_data) ){
                $user_jj_data = M('user_jj')->where(array('r_id'=>$ppdd_data['id']))->find();
                $item['user_jj_id'] = $user_jj_data['id'];
            }
            $plist[$key] = $item;
        }
        */
        $this->assign('plist', $plist);
        $this->assign('ppage', $p->show());
        $this->assign('pcount',$count);
        $this->display('fill_in_order');


//        $User = M('tgbz');
//        //$sall = ' and ppjb < total ';
//        $map['user'] = $_SESSION['uname'];
//        //$map['_string'] = 'mainid = id' . $sall;
//        $map['zt'] = array(in, array('0', '6','1'));
//        $map['isprepay'] = array('eq','0');
//        $count = $User->where($map)->count();
//        $p = getpage($count, 5);
//        $plist = $User->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
//        $this->assign('plist', $plist);
//        $this->assign('ppage', $p->show());
//        $this->assign('pcount',$count);
//        $this->display('fill_in_order');
    }

    //交割订单
    public function delivery_order()
    {
        //////////////////----------
        /// <!--||||||||||买出订单匹配，以下-->
        //////////////////----------
        $User = M('ppdd'); // 实例化User对象
        $map3['g_user'] = $_SESSION['uname'];
        $map3['zt'] = array('in', array('0', '1'));
        $count3 = $User->where($map3)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p3 = getpage($count3, $count3);
        $gdlist = $User->where($map3)->order('id DESC')->limit($p3->firstRow, $p3->listRows)->select();
        $gdlist = getGDinfo($gdlist);
        $this->assign('pp_g_list', $gdlist); // 赋值数据集
        $this->assign('pp_g_page', $p3->show()); // 赋值分页输出
        /////////////////----------------

        //////////////////----------
        $User = M('jsbz');
        $sall = ' and ppjb <= total ';
        $map1['user'] = $_SESSION['uname'];
        $map1['_string'] = 'mainid = id'  . $sall;
        $map1['zt'] = array(in, array('0', '6','1'));
        $count1 = $User->where($map1)->count();
        $p1 = getpage($count1, $count1);
        $jlist = $User->where($map1)->order('id DESC')->limit($p1->firstRow, $p1->listRows)->select();

        //查询出当前订单交割订单所对应的所有匹配订单信息【通过mainid】
        $ppddModel = M('ppdd');
        $userModel = M('user');
        foreach( $jlist as $key => $value )
        {
            //当前主订单对应的所有交割订单的id
            $where1 = [];
            $where1['mainid'] = $value['mainid'];
            $cur_jsbz_id_arr = $User->field('id')->where($where1)->select();
            $cur_jsbz_ids = [];//所有主订单对应的交割订单的id
            foreach( $cur_jsbz_id_arr as $cur_jsbz_id ){
                $cur_jsbz_ids[] = $cur_jsbz_id['id'];
            }
            $cur_ppdd_list = [];//当前交割订单的匹配订单
            if( !empty($cur_jsbz_ids) ) {
                //当前主订单对应的所有的匹配订单信息
                $where1 = [];
                $where1['g_id'] = array('in', $cur_jsbz_ids);
                $cur_ppdd_list = $ppddModel->where($where1)->select();
                foreach( $cur_ppdd_list as $k => $cur_ppdd )
                {
                    $cur_dk_user_info = $userModel->where(array('UE_account'=>$cur_ppdd['p_user']))->find();
                    $cur_ppdd['dk_ue_theme'] = $cur_dk_user_info['ue_theme'];
                    $cur_ppdd['dk_ue_account'] = $cur_dk_user_info['ue_account'];
                    $cur_ppdd['dk_weixin'] = authcode_decode($cur_dk_user_info['weixin']);
                    $cur_ppdd['dk_zfb'] = authcode_decode($cur_dk_user_info['zfb']);
                    $cur_ppdd['dk_yhzh'] = authcode_decode($cur_dk_user_info['yhzh']);
                    $cur_ppdd_list[$k] = $cur_ppdd;
                }
            }
            $value['ppdd_list'] = $cur_ppdd_list;
            $jlist[$key] = $value;
        }

        $this->assign('jlist', $jlist);
        $this->assign('jpage', $p1->show());
        $this->assign('jcount', $count1);
        $this->display();
        //////////////////----------

//        $User = M('jsbz');
//        $map1['user'] = $_SESSION['uname'];
//        //$map1['_string'] = 'mainid = id'  . $sall;
//        $map1['zt'] = array(in, array('0', '6','1'));
//        $count1 = $User->where($map1)->count();
//        $p1 = getpage($count1, 5);
//        $jlist = $User->where($map1)->order('id DESC')->limit($p1->firstRow, $p1->listRows)->select();
//        $this->assign('jlist', $jlist);
//        $this->assign('jpage', $p1->show());
//        $this->assign('jcount', $count1);
//        $this->display();
    }

    //交易大厅【超时未付款的订单列表】
    public function deal_order()
    {
        //$this->error('系统升级中，暂不开放');//20190517
        //指定时间段内【后台指定】，晚上8点之前或者指定时间点未付款的订单
        //【统一执行修改ts_zt状态为1的方法，自动投诉,ppdd表中将is_qgdt的状态修改为1，将该ppdd记录对应的tgbz表中的记录的isreset的状态修改为1】
        //【自动投诉后订单在交易大厅展示】
        //zdsjwfk_ppdd_to_ts_zt1();//迁移至定时任务中
        //可以在交易大厅抢单的用户等级
        $all_user_level = C('jjaccountlevel');
        $allow_min_level = C('allow_min_level');
        $all_user_level_arr = explode(',',$all_user_level);
        $allow_user_level_arr = [];//允许的用户等级
        $flag = 0;
        foreach($all_user_level_arr as $key => $item){
            if( $item == $allow_min_level ) $flag = 1;
            if( $flag == 1 ){
                $allow_user_level_arr[] = $item;
            }
        }
        //当前用户的等级
        $userInfo = M('user')->field('levelname')->where(array('UE_account' => $_SESSION['uname'], 'UE_status' => 0))->find();
        if( empty($userInfo) ){
            $this->error('信息有误');
        }
        if( !empty($userInfo) && !in_array($userInfo['levelname'],$allow_user_level_arr) ){
            //$this->error('对不起，您的等级不足');
            $is_yfk_unfinished_status = is_yfk_unfinished_status();//是否有未完成的开仓订单
            $is_wk_unfinished_status = is_wk_unfinished_status();//是否有未完成的平仓订单
            $is_jg_unfinished_status = is_jg_unfinished_status();//是否有未完成的交割订单
            $this->assign('is_yfk_status',$is_yfk_unfinished_status);
            $this->assign('is_wk_status',$is_wk_unfinished_status);
            $this->assign('is_jg_status',$is_jg_unfinished_status);
            echo '<script src="/assets/wns/js/layui.all.js"></script>';
            $this->display('home');
            echo "<script>layer.msg('对不起，您的等级不足');function go(){window.location.href='/Home/Index/index';}window.setTimeout(go,2000);</script>";
            exit;
        }

        //交易大厅订单交易的订单列表
        $tgbzModel = M('tgbz');
        $map = [];
        $map['isreset'] = array('eq',1);
        $tgbz_list = $tgbzModel->where($map)->group('mainid')->select();
        $this->assign('plist',$tgbz_list);

        $this->display();
    }

    //交易大厅，去抢单的逻辑【超时未付款的订单，重新匹配】
    public function rush_orders()
    {
        if(empty($_SESSION['uname']))$this->error('对不起，您无权操作');
        $mainid = I('get.mainid');
        $mainid = 0 + $mainid;

        //通过主订单id获取所有的tgbz的id列表
        $tgbz_ids = M('tgbz')->field('id,jb')->where(array('mainid'=>$mainid,'isreset'=>1))->select();
        if( empty($tgbz_ids) ){
            $this->error('对不起，该订单已被抢或不存在');
        }
        //当前主订单所对应的订单的总价
        $money = 0;//订单的总金额
        $tgbz_id_list = [];//符合的tgbz的id列表
        foreach( $tgbz_ids as $tgbz ){
            $money += $tgbz['jb'];
            $tgbz_id_list[] = $tgbz['id'];
        }
        if( $money <= 0 )
        {
            $this->error('对不起，该订单金额有误');
        }


        //如果存在就增加一条新的tgbz订单记录【开仓和平仓两部分订单】
        $user = M('user')->where(array('UE_account'=>$_SESSION['uname']))->find();
        //增加订单的记录
        //支付方式
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
        $data['isfast'] = getUserTGBZCount() == 0 ? 1 : 0;

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
            $data['isreset'] = 3;//重新创建的新的开仓订单
            $mainorderid = $data['orderid'];

            $newmainid = M('tgbz')->add($data);

            //1.提交预付款
            $data['isprepay'] = 1;
            $data['jb'] = $money_pre;
            $data['total'] = $money_pre;
            $data['orderid'] = createorderid('P');
            $data['isreset'] = 3;//重新创建的新的开仓订单

            $newprepayid = M('tgbz')->add($data);

        }else
        {
            $data['jb'] = $money;
            $data['total'] = $money;
            $data['orderid'] = createorderid('P');
            $mainorderid = $data['orderid'];
            $newmainid = M('tgbz')->add($data);
        }

        if ($newmainid) {
            M('tgbz')->where(array('id' => $newmainid))->save(array('mainid' => $newmainid));
            if ($newprepayid) {
                M('tgbz')->where(array('id' => $newprepayid))->save(array('mainid' => $newmainid));
            }

            //更新抢购订单的状态
            $where = [];
            $where['id'] = array('in',$tgbz_id_list);
            $update = array(
                'isreset'=>2,//该抢购单已处理
            );
            M('tgbz')->where($where)->save($update);//dump($update);
            //更新当前$tgbz_id_list中所对应的ppdd表中的状态
            $update_two = array(
                'is_qgdt'=>2,//已处理
            );
            $where_two = [];
            $where_two['p_id'] = array('in',$tgbz_id_list);
            M('ppdd')->where($where_two)->save($update_two);//dump($update_two);
            //die;
            $this->success('抢购成功!');
        }
        $this->error('抢购失败');

    }

    //取消等待买出
    public function jsbz_del() {
        die("<script>alert('不可取消!');window.location.href='/';</script>");
        if (!preg_match('/^[0-9]{1,10}$/', I('get.id'))) {
            $this->success('非法操作,将冻结账号!');
        } else {
            $userinfo = M('jsbz')->where(array('id' => I('get.id'), 'zt' => '0'))->find();
            //dump(I ('get.id'));
            //dump($userinfo['ue_accname']);die;
            if ($userinfo['user'] <> $_SESSION['uname']) {
                $this->success('订单当前状态不可取消!');
            } else {
                $reg = M('jsbz')->where(array('id' => I('get.id')))->delete();
                $del_getInfo = M('userget')->where(array('jsbzID' => I('get.id')))->delete();
                if ($userinfo['qb'] == 2) {
                    $oid = I('get.id');
                    M('tixian')->where(array('oid' => $oid))->setField('status', 1);
                    if ($reg && M('user')->where(array('UE_account' => $userinfo['user']))->setInc('tj_he', $userinfo['jb'])) {
                        die("<script>alert('取消成功!');window.location.href='/';</script>");
                    } else {
                        die("<script>alert('取消失败!');window.location.href='/';</script>");
                    }
                } elseif ($userinfo['qb'] == 1) {
                    $oid = I('get.id');
                    M('tixian')->where(array('oid' => $oid))->setField('status', 1);
                    if ($reg && M('user')->where(array('UE_account' => $userinfo['user']))->setInc('jl_he', $userinfo['jb'])) {
                        die("<script>alert('取消成功!');window.location.href='/';</script>");
                    } else {
                        die("<script>alert('取消失败!');window.location.href='/';</script>");
                    }
                } else {
                    if ($reg && M('user')->where(array('UE_account' => $userinfo['user']))->setInc('UE_money', $userinfo['jb'])) {
                        die("<script>alert('取消成功!');window.location.href='/';</script>");
                    } else {
                        die("<script>alert('取消失败!');window.location.href='/';</script>");
                    }
                }
            }
        }
    }
    public function pipei() {
        $xypipeije = 10;
        $data = array(1, 2, 3, 4, 5, 6, 7, 8);
        $tj = count($data);
        $sf_tcpp = '0';
        for ($b = 0;$b < $tj;$b++) {
            if ($sf_tcpp == '1') {
                break;
            }
            $tj_j = $tj - 1;
            echo '===========<br>';
            for ($i = 0;$i < $tj;$i++) {
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
    public function home_ddxx() {
        $ppddxx = M('ppdd')->where(array('id' => I('get.id')))->find();
        $g_user = M('user')->where(array('UE_account' => $ppddxx['g_user']))->find();
        $g_user_t = M('user')->where(array('UE_account' => $g_user['ue_accname']))->find();
        $p_user = M('user')->where(array('UE_account' => $ppddxx['p_user']))->find();
        $p_user_t = M('user')->where(array('UE_account' => $p_user['ue_accname']))->find();
        $this->ppddxx = $ppddxx;
        $this->g_user = $g_user;
        $this->p_user = $p_user;
        $this->g_user_t = $g_user_t;
        $this->p_user_t = $p_user_t;
        $this->display('home_ddxx');
    }
    public function home_ddxx_ly() {
        $ppddxx = M('ppdd')->where(array('id' => I('get.id')))->find();;
        $this->ppddxx = $ppddxx;
        //////////////////----------
        $User = M('ppdd_ly'); // 实例化User对象
        $map['ppdd_id'] = I('get.id');
        $list = $User->where($map)->select();
        $this->assign('list', $list); // 赋值数据集
        //dump($list);die;
        /////////////////----------------
        $this->display('act/sendmessage');
    }
    public function home_ddxx_ly_cl() {
        $data_P = I('post.');
        //echo strlen(trim($data_P['mesg']));die;
        $ppddxx = M('ppdd')->where(array('id' => $data_P['id']))->find();
        $user1 = M();
        if ($ppddxx['p_user'] <> $_SESSION['uname'] && $ppddxx['g_user'] <> $_SESSION['uname']) {
            die("<script>alert('非法操作！');history.back(-1);</script>");
        } elseif (strlen(trim($data_P['mesg'])) < 1) {
            die("<script>alert('留言内容不能为空！');history.back(-1);</script>");
        } else {
            $userData = M('user')->where(array('UE_ID' => $_SESSION['uid']))->find();
            $record['ppdd_id'] = $ppddxx['id'];
            $record['user'] = $_SESSION['uname'];
            $record['user_nc'] = $userData['ue_theme'];
            $record['nr'] = $data_P['mesg'];
            $record['date'] = date('Y-m-d H:i:s', time());;
            $reg = M('ppdd_ly')->add($record);
            if ($reg) {
                $this->success('留言成功!');
            } else {
                $this->success('留言失败!');
            }
        }
    }
    public function home_ddxx_confirmpay() {
        $this->id = I('get.id');
        $this->display('act/confirmpay');
    }
    //处理付款函数
    public function home_ddxx_confirmpay_cl()
	{
        $data_P = I('post.');
        $ppddxx = M('ppdd')->where(array('id' => $data_P['id'], 'zt' => '0'))->find();
        //如果不是本人
        if ($ppddxx['p_user'] <> $_SESSION['uname']) {
            die("<script>alert('非法操作！" .$data_P['id']. "');history.back(-1);</script>");
        } elseif ($data_P['comfir2'] <> '1') {
            die("<script>alert('请选择,我完成打款！');history.back(-1);</script>");
        } else {
            //if (empty($data_P['face180'])) {
                //$this->error('确认付款须上传付款截图哦');
            //}
            if ($data_P['comfir2'] == '1') {
                M('ppdd')->where(array('id' => $data_P['id'], 'zt' => '0'))->save(array('pic' => $data_P['face180'], 'zt' => '1', 'date_hk' => date('Y-m-d H:i:s', time())));
            }
            $tgbz = M('tgbz')->where(array('id' => $ppddxx['p_id'], 'user' => $ppddxx['p_user']))->find();
            $data2['user'] = $ppddxx['p_user'];
            $data2['r_id'] = $ppddxx['id'];
            $data2['date'] = $tgbz['date'];
            $data2['note'] = '买入';
            $data2['jb'] = $ppddxx['jb'];
			$data2['p_id'] = $tgbz['id'];
			$data2['main_p_id'] = $tgbz['mainid'];
			$data2['date_hk'] = date('Y-m-d H:i:s', time());
			$data2['isprepay'] = $tgbz['isprepay'];
			$data2['total'] = $tgbz['total'];
            if (M('user_jj')->add($data2))
			{
				$priority = getlimitpay($data_P['id']);
				//是否优先高级进场按钮时间
				if($priority)
				{
					M('user')->where(array('UE_account' => $ppddxx['p_user']))->save(array('priority' => 1));
				}else
				{
					M('user')->where(array('UE_account' => $ppddxx['p_user']))->save(array('priority' => 0));
				}

				//诚信积分【lx积分】20190513 start--------------------------
				//例如：如果用户在第二天的中午12点之前完成了打款，即可获得诚信积分//TODO
                //如何判断12点之前打【当前创建订单成功的时间，在当天的12点之前付款完成即可得到诚信积分】
                $get_cxjf_time = date('Y-m-d').' 12:00:00';
                //$get_cxjf_time = date('Y-m-d').' 19:00:00';
				$today_start_time = strtotime(date("Y-m-d").' 00:00:00');
				//获取打款时间
                $cur_ppdd = M('ppdd')->where(array('id' => $data_P['id']))->find();
                $cur_date_hk = $cur_ppdd['date_hk'];
                //$jjdktime_at = C('jjdktime_at');
				//$over_time_at = date("Y-m-d",time()).' '.$jjdktime_at;
				//tgbz订单创建的时间为准
				//$tgbz_date_next_day_time = strtotime($tgbz['date']);
                $tgbz_date_next_day_time = strtotime($cur_date_hk);//付款时间
                $over_time_at = strtotime($get_cxjf_time);
                if( $tgbz_date_next_day_time > $today_start_time  && $tgbz_date_next_day_time <= $over_time_at ){ //满足指定时间内付款，给用户增加一个诚信积分
                    //满足指定时间之前，给用户增加一个诚信积分
                    M('user')->where(array('UE_account' => $ppddxx['p_user']))->setInc('jifen', 1);
                    $jifenData['user'] = $ppddxx['p_user'];
                    $jifenData['type']= 'cxjl';
                    $jifenData['date']= date('Y-m-d H:i:s', time());
                    $jifenData['info']= '在'.$over_time_at.'前，完成了打款，获得1个诚信积分';
                    $jifenData['num']= 1;
                    $jifenData['yue']= get_userinfo($ppddxx['p_user'],'jifen');
                    $jifenData['varid'] = $ppddxx['p_id'];//买入记录表订单id
                    M('jifen_log')->add($jifenData);//增加一条诚信积分记录
                }
                //诚信积分【lx积分】20190513 end--------------------------

				if(C('sms_open_pay') == "1")
				{
					sendSMS(get_userinfo($ppddxx['g_user'],'ue_phone'),"亲爱的会员，您的订单对方已支付，请及时确认【" . C('sms_sign') . "】");
                    //sendSMS($ppddxx['g_user'],'',"SMS_165386286");
					insetSMSLog($ppddxx['g_user'],get_userinfo($ppddxx['g_user'],'ue_phone'),6,"亲爱的会员您好，您的订单对方已支付，请及时确认【" . C('sms_sign') . "】");
				}
                die("<script>alert('提交成功,请联系对方确认收款！');parent.location.reload();</script>");
            } else {
                die("<script>alert('提交失败,请联系管理员！');history.back(-1);</script>");
            }
        }
    }

    public function home_ddxx_confirmget() {
        $this->id = I('get.id');
        $this->display('act/confirmget');
    }

    //确认收款处理函数
    public function home_ddxx_confirmget_cl()
	{
        $data_P = I('post.');
        $ppddxx = M('ppdd')->where(array('id' => $data_P['id'], 'zt' => '1'))->find();
        if ($ppddxx['g_user'] <> $_SESSION['uname']) {
            die("<script>alert('非法操作！');history.back(-1);</script>");
        } elseif ($data_P['comfir'] <> '1' && $data_P['comfir'] <> '2' && $data_P['comfir'] <> '3') {
            die("<script>alert('请选择,确认收款或未收到款投诉！');history.back(-1);</script>");
        } elseif ($ppddxx['ts_zt'] == '3') {
            die("<script>alert('" . C("jjdktime") . "小时未确认收款,已被投诉！');history.back(-1);</script>");
        } else {
            if ($data_P['comfir'] == '1')
			{
                //在配对表中写人zt = 2 说明已经交易成功
                M('ppdd')->where(array('id' => $data_P['id'], 'zt' => '1'))->save(array('zt' => '2', 'dk_date' => date('Y-m-d H:i:s', time()))); //更新此订单状态
                //获取获得帮助交易的金额
                $txyqr = M('ppdd')->where(array('g_id' => $ppddxx['g_id'], 'zt' => '2'))->sum('jb');
                //echo $txyqr."</br>";
                //其实这里应该加个排序限制下
                $txzs = M('jsbz')->where(array('id' => $ppddxx['g_id']))->find();
                if ($txzs['jb'] == $txyqr) {
                    M('jsbz')->where(array('id' => $ppddxx['g_id']))->save(array('qr_zt' => '1')); //提现订单已确认

                }
                $czyqr = M('ppdd')->where(array('p_id' => $ppddxx['p_id'], 'zt' => '2'))->sum('jb');
                $czzs = M('tgbz')->where(array('id' => $ppddxx['p_id']))->find();
                if ($czzs['jb'] == $czyqr) {
                    M('tgbz')->where(array('id' => $ppddxx['p_id']))->save(array('qr_zt' => '1')); //提现订单已确认

                }

                //累计提供者的投资额度
				M('user')->where(array(UE_account => $ppddxx['p_user']))->setInc('tz_leiji', $ppddxx['jb']);

				//处理买入的推荐人是否可以升级为经理的考核
                //jlsja($ppddxx['p_user']); 


                //$tgbz_user_xx = M('user')->where(array('UE_account' => $ppddxx['p_user']))->find(); //充值人详细
				////更新提现订单状态
                //M('tgbz')->where(array('id'=>$ppddxx['p_id']))->setInc('jycg_ds',1);
                //前面已经处理下列出现所以注释了 olnho
                // 			    $tgbzcs=M('tgbz')->where(array('id'=>$ppddxx['p_id']))->find();
                // 			    if($tgbzcs['cf_ds']==$tgbzcs['jycg_ds']){
                // 			    	M('tgbz')->where(array('id'=>$ppddxx['p_id']))->save(array('qr_zt'=>'1'));//更新充值订单状态
                // 			    }
                //推荐奖10%
                //获取买入人的详细信息

                //echo $ppddxx['p_id'];die;
                //如果买入有推荐人
                /*if($tgbz_user_xx['ue_accname']<>''){               
                jlsja($tgbz_user_xx['ue_accname']);  //处理买入的推荐人是否可以升级为经理的考核
                //===2015/12/1 add
                //-------------------------->
                //fftuijianmoney($tgbz_user_xx['ue_accname'],$ppddxx['jb'],1);
                //         //---------------------------------------------------->计算推荐奖和会员级别奖
                
                $u = M('user')->where(array('UE_account'=>$ppddxx['p_user']))->find();
                
                //dyc($ppddxx['id'],$u,0);
                }*/
                ////经理奖金订单
                //dyc($ppddxx['p_id'],$ppddxx['p_user'],$ppddxx['jb'],0);
                //进行到这里表示付款成功，可以进行下一步推荐奖金的发放：
                // $underfirstuser = M('tgbz')->where(array('user'=>$ppddxx['p_user']))->order(date desc)->find();

                //付款成功，就判断是否满足增加一个诚信积分


				/*s1.*/
                $pc_finished_status = 0;//表示当前订单的主订单id即mainid对应的所有订单都已平仓完成  0未完成  1完成

                //获取买入人的详细信息
                $tgbz_user_xx = M('user')->where(array('UE_account' => $ppddxx['p_user']))->find(); //充值人详细

                //a.判断买入人的防撞功能是否开启，
                if( $tgbz_user_xx['fangzhuang'] == 1 )
                {
                    //b.如果是开启状态，就判断该用户的第一个订单是否完成预付款，若完成预付款，就关闭防撞功能
                    if( checkUserFirstTgbzStatus($tgbz_user_xx['ue_account']) == 1 ){
                        //关闭打款人的防撞功能
                        M('user')->where(array('UE_account' => $tgbz_user_xx['ue_account']))->save(array('fangzhuang'=>0));
                    }
                }

                //如果买入有推荐人
                if($tgbz_user_xx['ue_accname']<>'')
				{file_put_contents('level2.txt',$tgbz_user_xx['ue_accname']);
					//打款了才判断为有效会员
					if($tgbz_user_xx["yxhy"] == 0)
					{

					    mmtjrennumadd($tgbz_user_xx["ue_accname"]);
				        //accountaddlevel($tgbz_user_xx["ue_accname"]);
					   M('user')->where(array('UE_account' => $tgbz_user_xx["ue_account"]))->save(array('yxhy'=>1));
					}
                    $tgbz_data = M('tgbz')->where(array('mainid' => $czzs['mainid']))->select();//买入人订单的信息
                    $money = 0;
                    $yqrzf_num = 0;//已确认支付数量
                    foreach($tgbz_data as $item)
                    {
                        if($item['qr_zt'] == 1) {
                            $money += $item['jb'];
                            $yqrzf_num++;
                        }
                    }
                    file_put_contents('userlevel.txt','######'.$yqrzf_num.','.count($tgbz_data).','.$money);
                    if( count($tgbz_data) == $yqrzf_num && $yqrzf_num > 0 && $money > 0 ){//所有当前买入订单支付完成后执行升级会员
                        /*
                        //增加会员等级【注意：与20190523升级会员级别的功能迁移至买入（开仓下单的地方）】
                        accountaddlevel($czzs["user"],$money);
                        */

                        $pc_finished_status = 1;//表示当前订单的主订单id即mainid对应的所有订单都已平仓完成

                        //平仓支确认收款之后
                        fftuijianmoney($tgbz_user_xx['ue_accname'],$money,1,$ppddxx['p_id']);
                    }

                }
				/*
				e1.*/

				/*2.
				$tuijian_user = M('user')->where(array('UE_account' => $ppddxx['p_user']))->find(); //查询推荐人
                $tuijain_user_tgbz = M('tgbz')->where(array('user' => $tuijian_user['ue_accname']))->order("date desc")->find();
                //$tuijian_money = ($ppddxx['jb'] - $tuijain_user_tgbz['jb'])?$tuijain_user_tgbz['jb'] : $ppddxx['jb'];
                if (($ppddxx['jb'] - $tuijain_user_tgbz['jb']) > '0') {
                    $tuijian_money = $tuijain_user_tgbz['jb'];
                } else {
                    $tuijian_money = $ppddxx['jb'];
                }
                if ($tuijian_money > 0) {
                    lkdsjfsdfj($ppddxx['p_user'], $tuijian_money); //-推荐奖发放--------->
                }
				*/

				//自动提现的设置20190521 start----------------------------------
				//判断当前的订单是否已经完成订单的支付确认【对方已确认收款】
                if( $pc_finished_status == 1 ){ //表示当前订单的主订单id即mainid对应的所有订单都已平仓完成
                    //die("<script>alert('此次交易成功！');parent.location.href='/info/tgbz_tx_cl?id={$cur_id}&type=1';</script>");
                    //user_jj表中user_jj.p_id是tgbz.id,user_jj.main_p_id = tgbz.mainid，user_jj.r_id是ppdd.id,ppdd.date_hk是付款时间

                    $userMap['p_id'] = $ppddxx['p_id'];//$czzs['id']是tgbz表中的id ,ppdd表中的p_id对应tgbz表中的id
                    $cur_user_jj_data = M('user_jj')->where($userMap)->find();//user_jj表中user_jj.p_id是tgbz.id,
                    //user_jj表中的id，平仓完成后，所有的tgbz.mainid下对应的订单支付完成后，通过任一对应user_jj表中的id来完成提现
                    $cur_id = $cur_user_jj_data['id'];
                    file_put_contents('userjjtest21.txt',$cur_id.'||||'.json_encode($cur_user_jj_data).'||||');
                    $res = get_tgbz_tx_cl($cur_id);
                    file_put_contents('userjjtest2.txt',$cur_id.'||||'.json_encode($res).'||||');
                }
                if(isset($res))
                {
                    die("<script>alert('此次交易成功！{$res['msg']}');parent.location.reload();</script>");
                }
                //自动提现的设置20190521 end----------------------------------
                die("<script>alert('此次交易成功！');parent.location.reload();</script>");
            } elseif ($data_P['comfir'] == '2') {
                if ($ppddxx['ts_zt'] == '2') {
                    die("<script>alert('您已经投诉过了,请等待管理员审核！');parent.location.reload();</script>");
                } else {
                    if ($data_P['face180'] == '') {
                        die("<script>alert('请上传截图！');parent.location.reload();</script>");
                    } else {
                        M('ppdd')->where(array('id' => $data_P['id'], 'zt' => '1'))->save(array('ts_zt' => '2', 'pic2' => $data_P['face180']));
                        die("<script>alert('投诉成功,等待管理员审核,如果在审核过程中你收到款了,您还可以确认收款！');parent.location.reload();</script>");
                    }
                }
            }
        }
    }
    public function home_ddxx_pic_no() {
        $this->id = I('get.id');
        $this->display('home_ddxx_pic_no');
    }
    public function home_ddxx_g_wdk() {
        $this->id = I('get.id');
        $this->display('act/wdk');
    }
    public function home_ddxx_g_wqr() {
        $this->id = I('get.id');
        $this->display('home_ddxx_g_wqr');
    }
    //------------------------------------------------》投诉处理
    public function home_ddxx_g_wdk_cl() {
        $data_P = I('post.');
        $ppddxx = M('ppdd')->where(array('id' => $data_P['id'], 'zt' => '0'))->find();
        $NowTime = $ppddxx['date'];
        $aab = strtotime($NowTime);
        $aab2 = $aab + 3600 * C("jjdktime");
        $bba = date('Y-m-d H:i:s', time());
        $bba2 = strtotime($bba);
        if ($ppddxx['g_user'] <> $_SESSION['uname']) {
            die("<script>alert('非法操作！');history.back(-1);</script>");
        } elseif ($aab2 > $bba2) {
            die("<script>alert('汇款时间未超过" . C("jjdktime") . "小时,暂不能投诉,如未打款,请与买入者取得联系！');history.back(-1);</script>");
        } elseif ($data_P['comfir'] <> '1' && $data_P['comfir'] <> '2') {
            die("<script>alert('请选择,确认投诉！');history.back(-1);</script>");
        } elseif ($ppddxx['ts_zt'] == '1' && $data_P['comfir'] <> '2') {
            die("<script>alert('您已经投诉过了,请等待管理员处理！');history.back(-1);</script>");
        } else {
            if ($data_P['comfir'] == '1') {
                M('ppdd')->where(array('id' => $data_P['id'], 'zt' => '0'))->save(array('ts_zt' => '1'));
                die("<script>alert('投诉提交成功,请等待管理员审核通过！');parent.location.reload();</script>");
            } elseif ($data_P['comfir'] == '2') {
                M('ppdd')->where(array('id' => $data_P['id'], 'zt' => '0'))->save(array('ts_zt' => '0'));
                die("<script>alert('投诉取消成功,卖家可以继续汇款！');parent.location.reload();</script>");
            }
        }
    }
    public function home_ddxx_g_wqr_cl()
	{
        $data_P = I('post.');
        $ppddxx = M('ppdd')->where(array('id' => $data_P['id'], 'zt' => '1'))->find();
        $NowTime = $ppddxx['date_hk'];
        $aab = strtotime($NowTime);
        $aab2 = $aab + 3600 * C("jjqrtime");
        $bba = date('Y-m-d H:i:s', time());
        $bba2 = strtotime($bba);
        if ($ppddxx['p_user'] <> $_SESSION['uname']) {
            die("<script>alert('非法操作！');history.back(-1);</script>");
        } elseif ($aab2 > $bba2) {
            die("<script>alert('确认时间未超过" . C("jjqrtime") . "小时,暂不能投诉,如未确认,请与对方取得联系！');history.back(-1);</script>");
        } elseif ($data_P['comfir'] <> '1' && $data_P['comfir'] <> '2') {
            die("<script>alert('请选择,确认或取消！');history.back(-1);</script>");
        } elseif ($ppddxx['ts_zt'] == '2') {
            die("<script>alert('您已被对方投诉,请与对方取得联系！');history.back(-1);</script>");
        } else {
            if ($data_P['comfir'] == '1')
			{
                M('ppdd')->where(array('id' => $data_P['id'], 'zt' => '1'))->save(array('zt' => '2'));
                $txyqr = M('ppdd')->where(array('g_id' => $ppddxx['g_id'], 'zt' => '2'))->sum('jb');
                $txzs = M('jsbz')->where(array('id' => $ppddxx['g_id']))->find();
                if ($txzs['jb'] == $txyqr) {
                    M('jsbz')->where(array('id' => $ppddxx['g_id']))->save(array('qr_zt' => '1'));

                }
                $czyqr = M('ppdd')->where(array('p_id' => $ppddxx['p_id'], 'zt' => '2'))->sum('jb');
                $czzs = M('tgbz')->where(array('id' => $ppddxx['p_id']))->find();
                if ($czzs['jb'] == $czyqr) {
                    M('tgbz')->where(array('id' => $ppddxx['p_id']))->save(array('qr_zt' => '1'));

                }

                $tgbz_user_xx = M('user')->where(array('UE_account' => $ppddxx['p_user']))->find();

                if ($tgbz_user_xx['ue_accname'] <> '')
				{

                    fftuijianmoney($tgbz_user_xx['ue_accname'], $ppddxx['jb'], 1);

                    $mmtemparr = explode(',', C("jjjldsrate"));
                    if ($tgbz_user_xx['zcr'] <> '') {
                        $zcr2 = jlj($tgbz_user_xx['zcr'], $ppddxx['jb'] * ((float)$mmtemparr[0]) / 100, '经理奖' . $mmtemparr[0] . '%');
                        if ($zcr2 <> '') {
                            $zcr3 = jlj($zcr2, $ppddxx['jb'] * ((float)$mmtemparr[1]) / 100, '经理奖' . $mmtemparr[1] . '%');
                            if ($zcr3 <> '') {
                                $zcr4 = jlj($zcr3, $ppddxx['jb'] * ((float)$mmtemparr[2]) / 100, '经理奖' . $mmtemparr[2] . '%');
                                if ($zcr4 <> '') {
                                    $zcr5 = jlj($zcr4, $ppddxx['jb'] * ((float)$mmtemparr[3]) / 100, '经理奖' . $mmtemparr[3] . '%');
                                    if ($zcr5 <> '') {
                                        jlj($zcr5, $ppddxx['jb'] * ((float)$mmtemparr[4]) / 100, '经理奖' . $mmtemparr[4] . '%');
                                    }
                                }
                            }
                        }
                    }
                }
                die("<script>alert('系统自动处理成功！');parent.location.reload();</script>");
            }
        }
    }
    public function tgbz_list_cf() {
        $User = M('tgbz'); // 实例化User对象
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
        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p = getpage($count, 20);
        $list = $User->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
        //dump($list);die;
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $p->show()); // 赋值分页输出
        $this->display('index/tgbz_list_cf');
    }
    public function jsbz_list_cf() {
        $User = M('jsbz'); // 实例化User对象
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
        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p = getpage($count, 20);
        $list = $User->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
        //dump($list);die;
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $p->show()); // 赋值分页输出
        $this->display('index/jsbz_list_cf');
    }
    public function tgbz_list_cf_cl() {
        $data = I('post.');
        $p_user = M('tgbz')->where(array('id' => $data['pid']))->find();
        if (!preg_match('/^[0-9,]{1,100}$/', I('post.arrid'))) {
            $this->error('格式不对!');
            die;
        }
        $arr = explode(',', I('post.arrid'));
        //dump($arr);
        if (array_sum($arr) <> $p_user['jb']) {
            $this->error('拆分金额不对!');
            die;
        }
        $p_user1 = M('tgbz')->where(array('id' => $data['pid']))->find();
        $pipeits = 0;
        foreach ($arr as $value) {
            if ($value <> '') {
                $data2['zffs1'] = $p_user1['zffs1'];
                $data2['zffs2'] = $p_user1['zffs2'];
                $data2['zffs3'] = $p_user1['zffs3'];
                $data2['user'] = $p_user1['user'];
                $data2['jb'] = $value;
                $data2['user_nc'] = $p_user1['user_nc'];
                $data2['user_tjr'] = $p_user1['user_tjr'];
                $data2['date'] = $p_user1['date'];
                $data2['zt'] = $p_user1['zt'];
                $data2['qr_zt'] = $p_user1['qr_zt'];
                $varid = M('tgbz')->add($data2);
                $pipeits++;
            }
        }
        M('tgbz')->where(array('id' => $data['pid']))->delete();
        $this->success('匹配成功!拆分成' . $pipeits . '条订单!');
    }
    public function jsbz_list_cf_cl() {
        $data = I('post.');
        $p_user = M('jsbz')->where(array('id' => $data['pid']))->find();
        if (!preg_match('/^[0-9,]{1,100}$/', I('post.arrid'))) {
            $this->error('格式不对!');
            die;
        }
        $arr = explode(',', I('post.arrid'));
        //dump($arr);
        if (array_sum($arr) <> $p_user['jb']) {
            $this->error('拆分金额不对!');
            die;
        }
        $p_user1 = M('jsbz')->where(array('id' => $data['pid']))->find();
        $pipeits = 0;
        foreach ($arr as $value) {
            if ($value <> '') {
                $data2['zffs1'] = $p_user1['zffs1'];
                $data2['zffs2'] = $p_user1['zffs2'];
                $data2['zffs3'] = $p_user1['zffs3'];
                $data2['user'] = $p_user1['user'];
                $data2['jb'] = $value;
                $data2['user_nc'] = $p_user1['user_nc'];
                $data2['user_tjr'] = $p_user1['user_tjr'];
                $data2['date'] = $p_user1['date'];
                $data2['zt'] = $p_user1['zt'];
                $data2['qr_zt'] = $p_user1['qr_zt'];
                $varid = M('jsbz')->add($data2);
                $pipeits++;
            }
        }
        M('jsbz')->where(array('id' => $data['pid']))->delete();
        $this->success('匹配成功!拆分成' . $pipeits . '条订单!');
    }
    public function jujue() {
        if (IS_POST) {
            $id = I('id', 0, 'intval');
            if ($id > 0) {
                if (empty($pipei)) {
                    echo json_encode(array('error' => 1, 'msg' => '拒绝付款失败!无法重新匹配',));
                }
                //买入者表单信息
                //file_put_contents('./t.txt',var_export(M('jsbz')->where(array('id'=>$pipei['p_id']))->find(),true));
                $applyData = M('tgbz')->where(array('id' => $pipei['p_id']))->save(array('zt' => 0, 'cf_ds' => 0));
                //获得帮助者表单信息
                $needData = M('jsbz')->where(array('id' => $pipei['g_id']))->save(array('zt' => 0, 'cf_ds' => 0));
                M("ppdd")->where(array('id' => $id))->delete();
                echo json_encode(array('error' => 0, 'msg' => '拒绝付款成功，等待匹配中',));
            } else {
                echo json_encode(array('error' => 1, 'msg' => '拒绝付款失败!无法重新匹配',));
            }
        } else {
            $this->error('非法操作！');
        }
    }
    public function chongxinpipei($id) {
        $pipei = M("ppdd")->where(array('id' => $id))->find();
        $applyData = M('tgbz')->where(array('id' => $pipei['p_id']))->save(array('zt' => 0, 'cf_ds' => 0));
        //获得帮助者表单信息
        $needData = M('jsbz')->where(array('id' => $pipei['g_id']))->save(array('zt' => 0, 'cf_ds' => 0));
        M("ppdd")->where(array('id' => $id))->delete();
    }
    public function pdList() {
        $User = M('tgbz'); // 实例化User对象
        $map['user'] = $_SESSION['uname'];
        $map['zt'] = 0;
        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p = getpage($count, 100);
        $plist = $User->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('plist', $plist); // 赋值数据集
        $this->assign('page', $p->show()); // 赋值分页输出
        //买入配对流程
        //////////////////----------
        $User = M('ppdd'); // 实例化User对象
        $map2['p_user'] = $_SESSION['uname'];
        $count2 = $User->where($map2)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p2 = getpage($count2, 100);
        $pdlist = $User->where($map2)->order('id DESC')->limit($p2->firstRow, $p2->listRows)->select();
        $pdlist = getPDinfo($pdlist);
        $this->assign('pdlist', $pdlist); // 赋值数据集
        $this->assign('page2', $p2->show()); // 赋值分页输出
        //导航激活
        $this->she_list = true;
        $this->display('Index/pdList');
    }
    public function gdList() {
        //////////////////----------
        $User = M('jsbz'); // 实例化User对象
        $map['user'] = $_SESSION['uname'];
        $map['zt'] = 0;
        $count = $User->where($map)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p = getpage($count, 100);
        $glist = $User->where($map)->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('glist', $glist); // 赋值数据集
        $this->assign('page', $p->show()); // 赋值分页输出
        /////////////////----------------
        $User = M('ppdd'); // 实例化User对象
        $map3['g_user'] = $_SESSION['uname'];
        $count3 = $User->where($map3)->count(); // 查询满足要求的总记录数
        //$page = new \\Think\Page ( $count, 3 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $p3 = getpage($count3, 100);
        $gdlist = $User->where($map3)->order('id DESC')->limit($p3->firstRow, $p3->listRows)->select();
        $gdlist = getGDinfo($gdlist);
        $this->assign('gdlist', $gdlist); // 赋值数据集
        $this->assign('gpage', $p3->show()); // 赋值分页输出
        //导航激活
        $this->de_list = true;
        $this->display('Index/gdList');
    }
    // 抢单
    public function qdqb() {
        die("<script>alert('返现未到500不能提现!');history.back(-1);</script>");
    }
    public function qd() {
        $user = M('user')->where(array(UE_account => $_SESSION['uname'], UE_check => 1))->find();
        if (!$user) {
            die("<script>alert('该帐号未激活 不能进行操作');history.back(-1);</script>");
        }
        $count = M('jsbz')->where(array('zt' => 9))->count();
        $p = getpage($count, 100);
        $glist = M('jsbz')->where(array('zt' => 9))->order('id DESC')->limit($p->firstRow, $p->listRows)->select();
        $this->assign('list', $glist);
        $this->assign('page', $p->show());
        $this->display();
    }
    public function mrqd() {
        $uname = $_SESSION['uname'];
        $starttime = date('Y-m-d 00:00:01', time());
        $endtime = date('Y-m-d 23:59:59', time());
        $count = M("userget")->where("UG_getTime>='$starttime' and UG_getTime<='$endtime' and UG_account='$uname' and UG_note='签到'")->count();
        if ($count > 0) {
            die("<script>alert('每天只能签到一次，请明天再签!');history.back(-1);</script>");
        } else {
            $countq = M("user")->where("UE_account='$uname'")->sum('qd');
            $Dao = M("userget"); // 实例化模型类
            // 构建写入的数据数组
            $data3 = "累计签到返现";
            $data2 = $countq;
            $data["UG_account"] = $_SESSION['uname']; // 登入转出账户
            $data["UG_type"] = 'jb';
            $data["UG_allGet"] = $countq; // 金币
            $data["qd"] = '+0'; //
            $data["UG_balance"] = $data2; // 当前推荐人的金币馀额
            $data["UG_dataType"] = 'qd'; // 金币转出
            $data["UG_note"] = $data3; // 推荐奖说明
            $data["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作时间
            $qdks = M('user')->where(array('UE_account' => $_SESSION['uname']))->limit('1')->save(array('qd' => $data2));
            // 写入数据
            if ($lastInsId = $Dao->add($data)) {
                die("<script>alert('签到成功!');window.location.href='/';</script>");
            } else {
                die("<script>alert('签到失败!');history.back(-1);</script>");
            }
        }
    }
    public function mrqdq() {
        $user = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();
        $qwer = $user['ue_id'] + 1;
        if ($qwer % 10 > 0) {
            die("<script>alert('对不起你非幸运用户!');history.back(-1);</script>");
        }
        $uname = $_SESSION['uname'];
        $starttime = date('Y-m-d 00:00:01', time());
        $endtime = date('Y-09-15 23:59:59', time());
        $count = M("userget")->where("UG_getTime>='$starttime' and UG_getTime<='$endtime' and UG_account='$uname' and UG_note='签到'")->count();
        if ($count > 0) {
            die("<script>alert('你已经领取过了咯!');history.back(-1);</script>");
        } else {
            $countq = M("user")->where("UE_account='$uname'")->sum('UE_money');
            $Dao = M("userget"); // 实例化模型类
            // 构建写入的数据数组
            $data3 = "平台幸运奖";
            $data2 = $countq + 1000;
            $data["UG_account"] = $_SESSION['uname']; // 登入转出账户
            $data["UG_type"] = 'jb';
            $data["UG_allGet"] = $countq; // 金币
            $data["UG_money"] = '+1000'; //
            $data["UG_balance"] = $data2; // 当前推荐人的金币馀额
            $data["UG_dataType"] = 'qd'; // 金币转出
            $data["UG_note"] = $data3; // 推荐奖说明
            $data["UG_getTime"] = date('Y-m-d H:i:s', time()); //操作时间
            $qdks = M('user')->where(array('UE_account' => $_SESSION['uname']))->limit('1')->save(array('UE_money' => $data2));
            // 写入数据
            if ($lastInsId = $Dao->add($data)) {
                die("<script>alert('领取成功!');window.location.href='/';</script>");
            } else {
                die("<script>alert('领取失败!');history.back(-1);</script>");
            }
        }
    }
    public function qd_cl() {
        $pid = I('get.pid');
        $count = M('paidan')->where(array('user' => $_SESSION['uname'], 'zt' => 0))->count();
        $user = M('user')->where(array('UE_account' => $_SESSION['uname']))->find();
        if (!$user) {
            die("<script>alert('你账户存在安全风险，请重新登录！');parent.location.reload();</script>");
        }
        if ($count < 1) {
            $this->error('你的诚信码余额不足');
        }
        $pd = M('jsbz')->where(array('id' => $pid, 'zt' => 9))->find();
        if (!$pd) {
            die("<script>alert('该单已经不存在！');parent.location.reload();</script>");
        }
        $c = M('config')->where(array('id' => 1))->find();
        $i = $c['qiangdan'];
        $day = $c['day'];
        //抢单的金额算法
        $data['qd'] = ceil($pd['jb'] * $day * $i);
        $data['zffs1'] = '1';
        $data['zffs2'] = '1';
        $data['zffs3'] = '1';
        $data['user'] = $user['ue_account'];
        $data['jb'] = $pd['jb'];
        $data['user_nc'] = $user['ue_theme']; //昵称
        $data['user_tjr'] = $user['zcr']; //推荐人
        $data['date'] = date('Y-m-d H:i:s', time()); //排单时间
        $data['zt'] = 1; //等待匹配
        $data['qr_zt'] = 0; //未确认
        $tid = M('tgbz')->data($data)->add();
        if ($tid) {
            M('jsbz')->where(array('id' => $pd['id']))->setField('zt', 1);
            $map['p_id'] = $tid;
            $map['g_id'] = $pid;
            $map['jb'] = $pd['jb'];
            $map['p_user'] = $user['ue_account'];
            $map['g_user'] = $pd['user'];
            $map['date'] = date('Y-m-d H:i:s', time());
            $map['zt'] = 0;
            $map['zffs1'] = '1';
            $map['zffs2'] = '1';
            $map['zffs3'] = '1';
            $map['ts_zt'] = '0';
            if (M('ppdd')->add($map)) {
                die("<script>alert('抢单成功！');window.location.href='/';</script>");
            } else {
                die("<script>alert('抢单失败！');history.back(-1);</script>");
            }
        } else {
            die("<script>alert('抢单失败！');history.back(-1);</script>");
        }
    }
    public function qdd() {
        $user = M('user')->where(array(UE_account => $_SESSION['uname'], UE_check => 1))->find();
        if (!$user) {
            die("<script>alert('该帐号未激活  不能进行操作 ');history.back(-1);</script>");
        }
        $timeorder_limit = C('timeorder_limit');
        $orderstart;
        if ($timeorder_limit && $timeorder_limit) {
            //每天抢单开始时间
            $time_unix = strtotime(date('Ymd'));
            $now = time();
            $start_info = explode(':', C('get_start'));
            $start_unix = 0;
            if (is_array($start_info)) {
                $start_unix = $start_info[0] * 3600 + $start_info[1] * 60;
            }
            //每天抢单结束时间
            $end_info = explode(':', C('get_end'));
            $end_unix = 0;
            if (is_array($end_info)) {
                $end_unix = $end_info[0] * 3600 + $end_info[1] * 60;
            }
            $time_start = $time_unix + $start_unix;
            $time_end = $time_unix + $end_unix;
            if ($now > $time_start && $now < $time_end) {
                $orderstart = 1;
            } else {
                $orderstart = 0;
            }
        }
        //金额限制
        $amount_limit = C('amount_limit');
        $get_amount = C('get_amount');
        if ($amount_limit) {
            $starttime = date('Y-m-d 00:00:01', time());
            $endtime = date('Y-m-d 23:59:59', time());
            $sumjb = M("tgbz")->where("date>='$starttime' and date<='$endtime' and zt = 6 ")->sum('jb');
            if ($get_amount >= $sumjb) {
                $orderstart = 1;
            } else $orderstart = 0;
        } else {
            $orderstart = 0;
        }
        if (C('orderstart')) {
            $orderstart = 1;
        } else {
            $orderstart = 0;
        }
        $this->assign('orderstart', $orderstart);
        $this->assign('timeorder_limit', C('timeorder_limit'));
        $this->assign('amount_limit', C('amount_limit'));
        $this->assign('get_start', C('get_start'));
        $this->assign('get_end', C('get_end'));
        $this->assign('get_amount', C('get_amount'));
        $this->display();
    }
    public function question() {
        if (IS_POST && $_POST != null) {
            if ($_POST['question_1_B'] == "B" && $_POST['question_1_A'] == null) {
                if ($_POST['question_2_A'] == "A"  && $_POST['question_2_B'] == null) {
                    if ($_POST['question_3_A'] == "A"  && $_POST['question_3_B'] == null) {
						if ($_POST['question_4_A'] == "A"  && $_POST['question_4_B'] == null) {
                           if ($_POST['question_5_A'] == "A"  && $_POST['question_5_B'] == null) {
                               $rs = M('user')->where(array('UE_account' => $_SESSION['uname']))->save(array('is_first' => 0));
                               $this->redirect('index/home');
                           } else {
                               die("<script>alert('第五题答案错误，请重新回答');history.back(-1);</script>");
                           }
                        } else {
                           die("<script>alert('第四题答案错误，请重新回答');history.back(-1);</script>");
                        }
                    } else {
                        die("<script>alert('第三题答案错误，请重新回答');history.back(-1);</script>");
                    }
                } else {
                    die("<script>alert('第二题答案错误，请重新回答');history.back(-1);</script>");
                }
            } else {
                die("<script>alert('第一题答案错误，请重新回答');history.back(-1);</script>");
            }
        }
        $this->display();
    }
    // 用户自动抢单
	public function user_zdpp()
	{
		$tgbzid = $_SESSION['user_zdpp_tgbzid'];
		if($tgbzid == null || $tgbzid == "")
			$this->error('请从首页进场后才可匹配');

		$priority = getUserInEnabled();
		if($priority == 0)
			$this->error('抱歉，当前不在进场时间范围');

		$map1['user'] = $_SESSION['uname'];
		$map1['id'] = $tgbzid;
		$tgbz = M('tgbz')->where($map1)->find();
		if(!$tgbz)
			$this->error('订单不存在');
		if($tgbz['zt'] != 0)
			$this->error('你的订单已经匹配过了');

		$jsbzid = I('get.jsbzid');
		if($jsbzid == null || $jsbzid == "")
			$this->error('参数不正确');

		$map2['id'] = $jsbzid;
		$jsbz = M('jsbz')->where($map2)->find();
		if(!$jsbz)
			$this->error('订单不存在');
		if($jsbz['zt'] != 0)
			$this->error('来晚了，别人抢先一步了，试试其它的吧');

		if($tgbz['user'] == $jsbz['user'])
			$this->error('自己匹配自己？这好像并没有什么意义');

		$count = M('jsbz')->where ("zt=0 and TO_DAYS( '" . $jsbz['date'] ."') - TO_DAYS( date) > 0")->count ();

        if($count > 0){
			$this->error('为了公平，请优先匹配靠前的订单');
		}

		$where1=array();
        $where1['p_user|g_user'] = $jsbz['user'];
        $where1['zt'] =array('NEQ',2);
        $rs=M('ppdd')->where($where1)->find();
		if ($rs)
		{
             if($rs['p_user'] == $jsbz['user']){
				 //$this->error('提现用户还有未付款订单，不能匹配!');
             }else
			 {
				 //拆分的情况不在校验范围
                 $rs_jsbz = M('jsbz')->where(array('id'=>$rs['g_id']))->find();
                 if($rs_jsbz['date']<>$jsbz['date']){
					 //$this->error('提现用户还有未收款订单，不能匹配!');
                 }
             }
        }
		if($tgbz['jb'] == $jsbz['jb'])
		{
			if(ppdd_add($tgbz['id'], $jsbz['id']))
			{
				$this->success('匹配成功!');
			}else
				$this->error('匹配成功!');
		}

		if($tgbz['jb'] < $jsbz['jb'])
		{
			$jb1 = $tgbz['jb'];
			$jb2 = $jsbz['jb'] - $tgbz['jb'];
			$arr = explode(',', $jb1 . "," . $jb2);
			$pipeits = 0;
			$new_jsbzid;
            foreach ($arr as $value)
			{
               if ($value <> '')
			   {
				   if($pipeits > 0)
				   {
                       $data2['zffs1'] = $jsbz['zffs1'];
                       $data2['zffs2'] = $jsbz['zffs2'];
                       $data2['zffs3'] = $jsbz['zffs3'];
                       $data2['user'] = $jsbz['user'];
                       $data2['jb'] = $jb2;
                       $data2['user_nc'] = $jsbz['user_nc'];
                       $data2['user_tjr'] = $jsbz['user_tjr'];
                       $data2['date'] = $jsbz['date'];
                       $data2['zt'] = $jsbz['zt'];
                       $data2['qr_zt'] = $jsbz['qr_zt'];
                       $new_jsbzid = M('jsbz')->add($data2);
                  }else
				  {
					  M('jsbz')->where(array('id'=>$jsbz['id']))->save(array('jb'=>$jb1));
				  }
                  $pipeits++;
              }
			}


			if(ppdd_add($tgbz['id'], $jsbz['id']))
			{
				$this->success('匹配成功!');
			}else
				$this->error('匹配失败!');
		}

		if($tgbz['jb'] > $jsbz['jb'])
		{
			$jb1 = $jsbz['jb'];
			$jb2 = $tgbz['jb'] - $jsbz['jb'];
			$arr = explode(',', $jb1 . "," . $jb2);
			$pipeits = 0;
			$new_tgbzid;
            foreach ($arr as $value)
			{
               if ($value <> '')
			   {
				   if($pipeits > 0)
				   {
					   $data2['zffs1'] = $tgbz['zffs1'];
                       $data2['zffs2'] = $tgbz['zffs2'];
                       $data2['zffs3'] = $tgbz['zffs3'];
                       $data2['user'] = $tgbz['user'];
                       $data2['jb'] = $jb2;
                       $data2['user_nc'] = $tgbz['user_nc'];
                       $data2['user_tjr'] = $tgbz['user_tjr'];
                       $data2['date'] = $tgbz['date'];
                       $data2['zt'] = $tgbz['zt'];
                       $data2['qr_zt'] = $tgbz['qr_zt'];
					   $data2['mainid'] = $tgbz['mainid'] == 0 ? $tgbz['id'] : $tgbz['mainid'];
                       $new_tgbzid = M('tgbz')->add($data2);
                  }else
				  {
					  M('tgbz')->where(array('id'=>$tgbz['id']))->save(array('jb'=>$jb1));
					  M('tgbz')->where(array('id'=>$tgbz['id']))->save(array('mainid'=>$tgbz['mainid'] == 0 ? $tgbz['id'] : $tgbz['mainid']));
				  }
                  $pipeits++;
              }
			}


			if(ppdd_add($tgbz['id'], $jsbz['id']))
			{
				$this->success('匹配成功!');
			}else
				$this->error('匹配失败!');
		}
	}


    /**
     * 超时未确认的订单，重新被执行抢单
     * @param int $time_out_ppdd_id  【超时未确认的匹配订单的id】
     * @return array ['code'=>0,'msg'=>'string'],code为0失败 code为200成功
     */
    private function user_again_zdpp( $time_out_ppdd_id = 0 )
    {
        $whereA['id'] = $time_out_ppdd_id;
        $old_ppdd = M('ppdd')->where($whereA)->find();
        if( empty($old_ppdd) )
            //$this->error('抱歉，你下手慢了已经有人抢走了该订单！');
            return ['code'=>0,'msg'=>'抱歉，你下手慢了已经有人抢走了该订单！'];
        $tgbzid = $old_ppdd['p_id'];
        $jsbzid = $old_ppdd['g_id'];
        /*
        $tgbzid = $_SESSION['user_zdpp_tgbzid'];
        if($tgbzid == null || $tgbzid == "")
            $this->error('请从首页进场后才可匹配');
        */

        $priority = getUserInEnabled();
        if($priority == 0)
            //$this->error('抱歉，当前不在进场时间范围');
            return ['code'=>0,'msg'=>'抱歉，当前不在进场时间范围'];

        $map1['user'] = $_SESSION['uname'];
        $map1['id'] = $tgbzid;
        $tgbz = M('tgbz')->where($map1)->find();
        if(!$tgbz)
            //$this->error('订单不存在');
            return ['code'=>0,'msg'=>'订单不存在'];
        if($tgbz['zt'] != 0)
            //$this->error('你的订单已经匹配过了');
            return ['code'=>0,'msg'=>'你的订单已经匹配过了'];

        //$jsbzid = I('get.jsbzid');
        if($jsbzid == null || $jsbzid == "")
            //$this->error('参数不正确');
            return ['code'=>0,'msg'=>'参数不正确'];

        $map2['id'] = $jsbzid;
        $jsbz = M('jsbz')->where($map2)->find();
        if(!$jsbz)
            //$this->error('订单不存在');
            return ['code'=>0,'msg'=>'订单不存在'];
        /*
        if($jsbz['zt'] != 0)
            $this->error('来晚了，别人抢先一步了，试试其它的吧');
        */
        if($tgbz['user'] == $jsbz['user'])
            //$this->error('自己匹配自己？这好像并没有什么意义');
            return ['code'=>0,'msg'=>'自己匹配自己？这好像并没有什么意义'];

        //$count = M('jsbz')->where ("zt=0 and TO_DAYS( '" . $jsbz['date'] ."') - TO_DAYS( date) > 0")->count ();
        $count = M('jsbz')->where ("TO_DAYS( '" . $jsbz['date'] ."') - TO_DAYS( date) > 0")->count ();

        if($count > 0){
            //$this->error('为了公平，请优先匹配靠前的订单');
            return ['code'=>0,'msg'=>'为了公平，请优先匹配靠前的订单'];
        }

        $where1=array();
        $where1['p_user|g_user'] = $jsbz['user'];
        $where1['zt'] =array('NEQ',2);
        $rs=M('ppdd')->where($where1)->find();
        if ($rs)
        {
            if($rs['p_user'] == $jsbz['user']){
                //$this->error('提现用户还有未付款订单，不能匹配!');
            }else
            {
                //拆分的情况不在校验范围
                $rs_jsbz = M('jsbz')->where(array('id'=>$rs['g_id']))->find();
                if($rs_jsbz['date']<>$jsbz['date']){
                    //$this->error('提现用户还有未收款订单，不能匹配!');
                }
            }
        }
        if($tgbz['jb'] == $jsbz['jb'])
        {
            if(ppdd_add($tgbz['id'], $jsbz['id']))
            {
                return ['code'=>200,'msg'=>'匹配成功'];
                //$this->success('匹配成功!');
            }else
                return ['code'=>0,'msg'=>'匹配失败'];
                //$this->error('匹配失败!');
        }

        if($tgbz['jb'] < $jsbz['jb'])
        {
            $jb1 = $tgbz['jb'];
            $jb2 = $jsbz['jb'] - $tgbz['jb'];
            $arr = explode(',', $jb1 . "," . $jb2);
            $pipeits = 0;
            $new_jsbzid;
            foreach ($arr as $value)
            {
                if ($value <> '')
                {
                    if($pipeits > 0)
                    {
                        $data2['zffs1'] = $jsbz['zffs1'];
                        $data2['zffs2'] = $jsbz['zffs2'];
                        $data2['zffs3'] = $jsbz['zffs3'];
                        $data2['user'] = $jsbz['user'];
                        $data2['jb'] = $jb2;
                        $data2['user_nc'] = $jsbz['user_nc'];
                        $data2['user_tjr'] = $jsbz['user_tjr'];
                        $data2['date'] = $jsbz['date'];
                        $data2['zt'] = $jsbz['zt'];
                        $data2['qr_zt'] = $jsbz['qr_zt'];
                        $new_jsbzid = M('jsbz')->add($data2);
                    }else
                    {
                        M('jsbz')->where(array('id'=>$jsbz['id']))->save(array('jb'=>$jb1));
                    }
                    $pipeits++;
                }
            }


            if(ppdd_add($tgbz['id'], $jsbz['id']))
            {
                return ['code'=>200,'msg'=>'匹配成功'];
                //$this->success('匹配成功!');
            }else
                return ['code'=>0,'msg'=>'匹配失败'];
                //$this->error('匹配失败!');
        }

        if($tgbz['jb'] > $jsbz['jb'])
        {
            $jb1 = $jsbz['jb'];
            $jb2 = $tgbz['jb'] - $jsbz['jb'];
            $arr = explode(',', $jb1 . "," . $jb2);
            $pipeits = 0;
            $new_tgbzid;
            foreach ($arr as $value)
            {
                if ($value <> '')
                {
                    if($pipeits > 0)
                    {
                        $data2['zffs1'] = $tgbz['zffs1'];
                        $data2['zffs2'] = $tgbz['zffs2'];
                        $data2['zffs3'] = $tgbz['zffs3'];
                        $data2['user'] = $tgbz['user'];
                        $data2['jb'] = $jb2;
                        $data2['user_nc'] = $tgbz['user_nc'];
                        $data2['user_tjr'] = $tgbz['user_tjr'];
                        $data2['date'] = $tgbz['date'];
                        $data2['zt'] = $tgbz['zt'];
                        $data2['qr_zt'] = $tgbz['qr_zt'];
                        $data2['mainid'] = $tgbz['mainid'] == 0 ? $tgbz['id'] : $tgbz['mainid'];
                        $new_tgbzid = M('tgbz')->add($data2);
                    }else
                    {
                        M('tgbz')->where(array('id'=>$tgbz['id']))->save(array('jb'=>$jb1));
                        M('tgbz')->where(array('id'=>$tgbz['id']))->save(array('mainid'=>$tgbz['mainid'] == 0 ? $tgbz['id'] : $tgbz['mainid']));
                    }
                    $pipeits++;
                }
            }


            if(ppdd_add($tgbz['id'], $jsbz['id']))
            {
                return ['code'=>200,'msg'=>'匹配成功'];
                //$this->success('匹配成功!');
            }else
                return ['code'=>0,'msg'=>'匹配失败'];
                //$this->error('匹配失败!');
        }
    }

    /**
     * 超时未确认的订单，重新被执行抢单
     * @param int $time_out_ppdd_id  【超时未确认的匹配订单的id】
     * @return array ['code'=>0,'msg'=>'string'],code为0失败 code为200成功
     */
    private function new_user_again_zdpp(  $mainid = 0  ){

        //通过主订单id获取所有的tgbz的id列表
        $map1['mainid'] = $mainid;
        $tgbz_ids = M('tgbz')->field('id,jb,zt,user,qr_zt,mainid,orderid,total,isprepay')->where($map1)->select();
        $tgbz_id_list = [];
        foreach( $tgbz_ids as $tgbz_id ){
            $tgbz_id_list[] = $tgbz_id['id'];
        }

        //查询出投诉ts_zt为1，且is_qgdt为1的记录是否存在
        $map['p_id'] = array('in',$tgbz_id_list);
        $ppdd_data = M('ppdd')->where($map)->find();
        $is_qgdt_num = 0;
        foreach( $ppdd_data as $item ){
            if( $item['is_qgdt'] == 1 ){
                $is_qgdt_num++;
            }
        }
        if( $is_qgdt_num == 0 ){
            //$this->error('订单不存在');
            return ['code'=>0,'msg'=>'抱歉，你下手慢了已经有人抢走了该订单！'];
        }

        //逐条的执行业务逻辑，修改之前已经匹配的记录
        //先找出预付款是否已付过款
        $isprepay_yfk_status = 0;
        $isprepay_yfk_ids = [];

        //找出尾款是否已付过款
        $isprepay_wk_status = 0;
        $isprepay_wk_ids = [];

        foreach( $tgbz_ids as $item ){

            //先找出预付款是否已付过款
            if( $item['isprepay'] == 1 && in_array($item['zt'],[1]) ){
                $isprepay_yfk_status++;
            }
            if( $item['isprepay'] == 1 ){
                $isprepay_yfk_ids[] = $item['id'];
            }

            //找出尾款是否已付过款
            if( $item['isprepay'] == 0 && in_array($item['zt'],[1]) ){
                $isprepay_wk_status++;
            }
            if( $item['isprepay'] == 0 ){
                $isprepay_wk_ids[] = $item['id'];
            }

        }

        $login_user_data = M('user')->where(array('UE_account'=>$_SESSION['uname']))->find();
        if( empty($login_user_data) ){
            return ['code'=>0,'msg'=>'抱歉，用户信息有误！'];
        }


        //公司50个账号【模拟50个账号】

        $company_account_data = M('user')->where(array('is_company'=>1))->select();
        $fifity_num = count($company_account_data);
        $fifity_num = 50;
        $fifity_account = [];
        for($j=0;$j<$fifity_num;$j++){
            $fifity_account[] = [
                'account'=>$company_account_data[$j]['yhzh'],//银行账号
                'user' => $company_account_data[$j]['ue_account'],//用户账号
                'nickname'=>$company_account_data[$j]['ue_theme'],//推荐人
                'user_tjr'=>$company_account_data[$j]['ue_accname'],//推荐人
            ];
            /*
            $fifity_account[] = [
                'account'=>'123456'.$j,//银行账号
                'user' => 'admin'.$j.'@qq.com',//用户账号
                'nickname'=>'公司',//推荐人
                'user_tjr'=>'推荐人',//推荐人
            ];
            */
        }
        //dump($isprepay_yfk_status);
        //dump($isprepay_wk_status);
        //die;

        //第一种情况匹配后，预付款没有打款，尾款也没有打款的情况
        if( $isprepay_yfk_status == 0 && $isprepay_wk_status == 0 ){
            //将匹配的预付款先打给匹配的账号
            //修改当前已经匹配且被封号的会员的tgbz订单【匹配的这部分】,同时更新尾款【平仓】订单的信息
            $update_tgbz['user'] = $login_user_data['ue_account'];
            $update_tgbz['user_tjr'] = $login_user_data['ue_accname'];
            $update_tgbz['date'] = date('Y-m-d H:i:s',time());
            $update_tgbz['user_nc'] = $login_user_data['UE_theme'];
            $update_tgbz['isreset'] = 1; //是否通过交易大厅抢单 0否 1是
            //M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>1))->save($update_tgbz);//将更新原来预付款的订单信息
            M('tgbz')->where(array('mainid'=>$mainid))->save($update_tgbz);//将更新原来预付款的订单信息,以及尾款订单的信息

            //匹配单信息
            $update_ppdd_data = [];
            $update_ppdd_data['p_user'] = $login_user_data['ue_account'];
            $update_ppdd_data['date'] = date('Y-m-d H:i:s',time());
            $update_ppdd_data['zt'] = 0;
            $update_ppdd_data['is_qgdt'] = 2;//已更新
            $update_ppdd_data['ts_zt'] = 0;
            M('ppdd')->where(array('p_id'=>array('in',$isprepay_yfk_ids)))->save($update_ppdd_data);

            //尾款的部分有原来的数据就要删除旧数据
            //M('tgbz')->where(array('id'=>array('in'=>$isprepay_wk_ids)))->delete();

            //买入【开仓】的总金额$tgbz_ids['total']
            //预付款的金额
            //$isprepay_jb = M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>1))->sum('jb');
            $tgbz_wk_list = M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>0))->select();
            $tgbz_wk_jb = 0;
            foreach($tgbz_wk_list as $v){
                $tgbz_wk_jb += $v['jb'];
            }
            $tgbz_wk_count = count($tgbz_wk_list);
            $yushu = $fifity_num%$tgbz_wk_count;

            //平均数
            $average_num = floor($fifity_num/$tgbz_wk_count);

            $xh_arr = [];//循环的方式
            $base_rule_arr = [];//执行分配的规则
            for($n=1;$n<=$tgbz_wk_count;$n++){
                if( $n == 1 ){
                    $xh_arr[$n] = $average_num*$n + $yushu;
                    for($m=0;$m<($average_num*$n + $yushu);$m++){
                        $base_rule_arr[$m] = $tgbz_wk_list[$n-1]['id'];
                    }
                }else{
                    $xh_arr[$n] = $average_num*$n + $yushu;
                    for($m=$xh_arr[$n-1];$m<($average_num*$n + $yushu);$m++){
                        $base_rule_arr[$m] = $tgbz_wk_list[$n-1]['id'];
                    }
                }
            }

            //尾款金额
            //$tgbz_wk_jb = $tgbz_ids['total'] - $isprepay_jb;

            //将尾款部分分别打给50个公司账号

            foreach($base_rule_arr as $key => $base_rule){
                    //$cur_tgbz_wk_jb -= $tgbz_wk_jb/50;
                    $data2['zffs1'] = 1;
                    $data2['zffs2'] = 1;
                    $data2['zffs3'] = 1;
                    $data2['user'] = $fifity_account[$key]['user'];
                    $data2['jb'] = $tgbz_wk_jb/$fifity_num;
                    $data2['user_nc'] = $fifity_account[$key]['nickname'];
                    $data2['user_tjr'] = $fifity_account[$key]['user_tjr'];
                    $data2['date'] = date('Y-m-d H:i:s',time());
                    $data2['zt'] = 0;
                    $data2['qr_zt'] = 0;
                    $data2['orderid'] = createorderid('G');
                    $data2['total'] = $tgbz_wk_jb/$fifity_num;
                    $data2['ppjb'] = $tgbz_wk_jb/$fifity_num;
                    $data2['mainid'] = $mainid;
                    $data2['isreset'] = 1; //是否通过交易大厅抢单 0否 1是
                    $new_jsbzid = M('jsbz')->add($data2);
                    /*
                    if( $i == 0 ){
                        $first_id = $new_jsbzid;
                    }
                    */
                    ppdd_add($base_rule, $new_jsbzid);
            }
            M('jsbz')->where(array('mainid'=>$mainid))->save(array('zt'=>1));


        }

        //第二种情况匹配后，预付款付款成功，尾款匹配后没有打款的情况，预付款打给公司50个账号
        if( $isprepay_yfk_status > 0 && $isprepay_wk_status == 0 ){
            //将匹配的预付款先打给匹配的账号
            //修改当前已经匹配且被封号的会员的tgbz订单【匹配的这部分】,同时更新尾款【平仓】订单的信息
            $update_tgbz['user'] = $login_user_data['ue_account'];
            $update_tgbz['user_tjr'] = $login_user_data['ue_accname'];
            $update_tgbz['date'] = date('Y-m-d H:i:s',time());
            $update_tgbz['user_nc'] = $login_user_data['UE_theme'];
            $update_tgbz['isreset'] = 1; //是否通过交易大厅抢单 0否 1是
            //M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>1))->save($update_tgbz);//将更新原来预付款的订单信息
            M('tgbz')->where(array('mainid'=>$mainid))->save($update_tgbz);//将更新原来预付款的订单信息,以及尾款订单的信息
//dump($update_tgbz);
            //匹配单信息
            $update_ppdd_data = [];
            $update_ppdd_data['p_user'] = $login_user_data['ue_account'];
            $update_ppdd_data['date'] = date('Y-m-d H:i:s',time());
            $update_ppdd_data['zt'] = 0;
            $update_ppdd_data['is_qgdt'] = 2;//已更新
            $update_ppdd_data['ts_zt'] = 0;
            M('ppdd')->where(array('p_id'=>array('in',$isprepay_wk_ids)))->save($update_ppdd_data);
//dump($update_ppdd_data);
            //尾款的部分有原来的数据就要删除旧数据
            //M('tgbz')->where(array('id'=>array('in'=>$isprepay_wk_ids)))->delete();

            //买入【开仓】的总金额$tgbz_ids['total']
            //预付款的金额
            //$isprepay_jb = M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>1))->sum('jb');
            //预付款的列表
            $tgbz_yfk_list = M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>1))->select();
            $tgbz_yfk_jb = 0;
            foreach($tgbz_yfk_list as $v){
                $tgbz_yfk_jb += $v['jb'];
            }
            $tgbz_yfk_count = count($tgbz_yfk_list);
            $yushu = $fifity_num%$tgbz_yfk_count;dump($tgbz_yfk_jb);

            //平均数
            $average_num = floor($fifity_num/$tgbz_yfk_count);

            $xh_arr = [];//循环的方式
            $base_rule_arr = [];//执行分配的规则
            for($n=1;$n<=$tgbz_yfk_count;$n++){
                if( $n == 1 ){
                    $xh_arr[$n] = $average_num*$n + $yushu;
                    for($m=0;$m<($average_num*$n + $yushu);$m++){
                        $base_rule_arr[$m] = $tgbz_yfk_list[$n-1]['id'];
                    }
                }else{
                    $xh_arr[$n] = $average_num*$n + $yushu;
                    for($m=$xh_arr[$n-1];$m<($average_num*$n + $yushu);$m++){
                        $base_rule_arr[$m] = $tgbz_yfk_list[$n-1]['id'];
                    }
                }
            }
            //dump($base_rule_arr);dump($tgbz_yfk_list);//die;

            //尾款金额
            //$tgbz_wk_jb = $tgbz_ids['total'] - $isprepay_jb;

            //将尾款部分分别打给50个公司账号
            //for($i=0;$i<50;$i++){
            foreach($base_rule_arr as $key => $base_rule){

                $data2['zffs1'] = 1;
                $data2['zffs2'] = 1;
                $data2['zffs3'] = 1;
                $data2['user'] =  $fifity_account[$key]['user'];
                $data2['jb'] = $tgbz_yfk_jb/$fifity_num;
                $data2['user_nc'] = $fifity_account[$key]['nickname'];
                $data2['user_tjr'] = $fifity_account[$key]['user_tjr'];
                $data2['date'] = date('Y-m-d H:i:s',time());
                $data2['zt'] = 0;
                $data2['qr_zt'] = 0;
                $data2['orderid'] = createorderid('G');
                $data2['total'] = $tgbz_yfk_jb/$fifity_num;
                $data2['ppjb'] = $tgbz_yfk_jb/$fifity_num;
                $data2['mainid'] = $mainid;
                $data2['isreset'] = 1; //是否通过交易大厅抢单 0否 1是
                $new_jsbzid = M('jsbz')->add($data2);
                //dump($data2);die;
                /*
                if( $i == 0 ){
                    $first_id = $new_jsbzid;
                }
                */
                ppdd_add($base_rule,$new_jsbzid);

            }
            M('jsbz')->where(array('mainid'=>$mainid))->save(array('zt'=>1));

        }

    }

    /**
     * 超时未确认的订单，重新被执行抢单
     * @param int $time_out_ppdd_id  【超时未确认的匹配订单的id】
     * @return array ['code'=>0,'msg'=>'string'],code为0失败 code为200成功
     */
    private function new_user_again_zdpp_old(  $mainid = 0  ){

        //通过主订单id获取所有的tgbz的id列表
        $tgbz_ids = M('tgbz')->field('id,jb,zt,user,qr_zt,mainid,orderid,total,isprepay')->where(array('mainid'=>$mainid))->select();
        $tgbz_id_list = [];
        foreach( $tgbz_ids as $tgbz_id ){
            $tgbz_id_list[] = $tgbz_id;
        }

        //查询出投诉ts_zt为1，且is_qgdt为1的记录是否存在
        $map['p_id'] = array('in',$tgbz_id_list);
        $ppdd_data = M('ppdd')->where($map)->find();
        $is_qgdt_num = 0;
        foreach( $ppdd_data as $item ){
            if( $item['is_qgdt'] == 1 ){
                $is_qgdt_num++;
            }
        }
        if( $is_qgdt_num == 0 ){
            //$this->error('订单不存在');
            return ['code'=>0,'msg'=>'抱歉，你下手慢了已经有人抢走了该订单！'];
        }

        //逐条的执行业务逻辑，修改之前已经匹配的记录
        //先找出预付款是否已付过款
        $isprepay_yfk_status = 0;
        $isprepay_yfk_ids = [];

        //找出尾款是否已付过款
        $isprepay_wk_status = 0;
        $isprepay_wk_ids = [];

        foreach( $tgbz_ids as $item ){

            //先找出预付款是否已付过款
            if( $item['isprepay'] == 1 && in_array($item['zt'],[1]) ){
                $isprepay_yfk_status++;
            }
            if( $item['isprepay'] == 1 ){
                $isprepay_yfk_ids[] = $item['id'];
            }

            //找出尾款是否已付过款
            if( $item['isprepay'] == 0 && in_array($item['zt'],[1]) ){
                $isprepay_wk_status++;
            }
            if( $item['isprepay'] == 0 ){
                $isprepay_wk_ids[] = $item['id'];
            }

        }

        $login_user_data = M('user')->where(array('UE_account'=>$_SESSION['uname']))->find();
        if( empty($login_user_data) ){
            return ['code'=>0,'msg'=>'抱歉，用户信息有误！'];
        }


        //公司50个账号【模拟50个账号】
        $fifity_account = [];
        for($j=0;$j<50;$j++){
            //$fifity_account[] = '123456'.$j;
            $fifity_account[] = [
                'account'=>'123456'.$j,//银行账号
                'user' => 'admin'.$j.'@qq.com',//用户账号
                'nickname'=>'公司',//推荐人
                'user_tjr'=>'推荐人',//推荐人
            ];
        }


        //第一种情况匹配后，预付款没有打款，尾款也没有打款的情况
        if( $isprepay_yfk_status == 0 && $isprepay_wk_status == 0 ){
            //将匹配的预付款先打给匹配的账号
            //修改当前已经匹配且被封号的会员的tgbz订单【匹配的这部分】,同时更新尾款【平仓】订单的信息
            $update_tgbz['user'] = $login_user_data['ue_account'];
            $update_tgbz['user_tjr'] = $login_user_data['ue_accname'];
            $update_tgbz['date'] = date('Y-m-d H:i:s',time());
            $update_tgbz['user_nc'] = $login_user_data['UE_theme'];
            $update_tgbz['isreset'] = 1; //是否通过交易大厅抢单 0否 1是
            //M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>1))->save($update_tgbz);//将更新原来预付款的订单信息
            M('tgbz')->where(array('mainid'=>$mainid))->save($update_tgbz);//将更新原来预付款的订单信息,以及尾款订单的信息

            //匹配单信息
            $update_ppdd_data = [];
            $update_ppdd_data['p_user'] = $login_user_data['ue_account'];
            $update_ppdd_data['date'] = date('Y-m-d H:i:s',time());
            $update_ppdd_data['zt'] = 0;
            $update_ppdd_data['is_qgdt'] = 2;//已更新
            $update_ppdd_data['ts_zt'] = 0;
            M('ppdd')->where(array('p_id'=>array('in',$isprepay_yfk_ids)))->save($update_ppdd_data);

            //尾款的部分有原来的数据就要删除旧数据
            //M('tgbz')->where(array('id'=>array('in'=>$isprepay_wk_ids)))->delete();

            //买入【开仓】的总金额$tgbz_ids['total']
            //预付款的金额
            //$isprepay_jb = M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>1))->sum('jb');
            $tgbz_wk_list = M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>0))->select();
            $tgbz_wk_jb = 0;
            foreach($tgbz_wk_list as $v){
                $tgbz_wk_jb += $v['jb'];
            }
            $tgbz_wk_count = count($tgbz_wk_list);
            $yushu = 50%$tgbz_wk_count;

            //平均数
            $average_num = floor(50/$tgbz_wk_count);

            $xh_arr = [];//循环的方式
            $base_rule_arr = [];//执行分配的规则
            for($n=1;$n<=$tgbz_wk_count;$n++){
                if( $n == 1 ){
                    $xh_arr[$n] = $average_num*$n + $yushu;
                    for($m=0;$m<($average_num*$n + $yushu);$m++){
                        $base_rule_arr[$m] = $tgbz_wk_list[$m]['id'];
                    }
                }else{
                    $xh_arr[$n] = $average_num*$n + $yushu;
                    for($m=$xh_arr[$n-1];$m<($average_num*$n + $yushu);$m++){
                        $base_rule_arr[$m] = $tgbz_wk_list[$m]['id'];
                    }
                }
            }

            //尾款金额
            //$tgbz_wk_jb = $tgbz_ids['total'] - $isprepay_jb;

            //将尾款部分分别打给50个公司账号
            //for($i=0;$i<50;$i++){
            foreach($base_rule_arr as $key => $base_rule){
                //$cur_tgbz_wk_jb -= $tgbz_wk_jb/50;
                $data2['zffs1'] = 1;
                $data2['zffs2'] = 1;
                $data2['zffs3'] = 1;
                $data2['user'] = $fifity_account[$key]['user'];
                $data2['jb'] = $tgbz_wk_jb/50;
                $data2['user_nc'] = $fifity_account[$key]['nickname'];
                $data2['user_tjr'] = $fifity_account[$key]['user_tjr'];
                $data2['date'] = date('Y-m-d H:i:s',time());
                $data2['zt'] = 1;
                $data2['qr_zt'] = 0;
                $data2['mainid'] = $mainid;
                $data2['isreset'] = 1; //是否通过交易大厅抢单 0否 1是
                $new_jsbzid = M('jsbz')->add($data2);
                /*
                if( $i == 0 ){
                    $first_id = $new_jsbzid;
                }
                */
                ppdd_add($base_rule, $new_jsbzid);
            }

            //}
        }

        //第二种情况匹配后，预付款付款成功，尾款匹配后没有打款的情况，预付款打给公司50个账号
        if( $isprepay_yfk_status > 0 && $isprepay_wk_status == 0 ){
            //将匹配的预付款先打给匹配的账号
            //修改当前已经匹配且被封号的会员的tgbz订单【匹配的这部分】,同时更新尾款【平仓】订单的信息
            $update_tgbz['user'] = $login_user_data['ue_account'];
            $update_tgbz['user_tjr'] = $login_user_data['ue_accname'];
            $update_tgbz['date'] = date('Y-m-d H:i:s',time());
            $update_tgbz['user_nc'] = $login_user_data['UE_theme'];
            $update_tgbz['isreset'] = 1; //是否通过交易大厅抢单 0否 1是
            //M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>1))->save($update_tgbz);//将更新原来预付款的订单信息
            M('tgbz')->where(array('mainid'=>$mainid))->save($update_tgbz);//将更新原来预付款的订单信息,以及尾款订单的信息

            //匹配单信息
            $update_ppdd_data = [];
            $update_ppdd_data['p_user'] = $login_user_data['ue_account'];
            $update_ppdd_data['date'] = date('Y-m-d H:i:s',time());
            $update_ppdd_data['zt'] = 0;
            $update_ppdd_data['is_qgdt'] = 2;//已更新
            $update_ppdd_data['ts_zt'] = 0;
            M('ppdd')->where(array('p_id'=>array('in',$isprepay_wk_ids)))->save($update_ppdd_data);

            //尾款的部分有原来的数据就要删除旧数据
            //M('tgbz')->where(array('id'=>array('in'=>$isprepay_wk_ids)))->delete();

            //买入【开仓】的总金额$tgbz_ids['total']
            //预付款的金额
            //$isprepay_jb = M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>1))->sum('jb');
            //预付款的列表
            $tgbz_yfk_list = M('tgbz')->where(array('mainid'=>$mainid,'isprepay'=>1))->select();
            $tgbz_yfk_jb = 0;
            foreach($tgbz_yfk_list as $v){
                $tgbz_yfk_jb += $v['jb'];
            }
            $tgbz_yfk_count = count($tgbz_yfk_list);
            $yushu = 50%$tgbz_yfk_count;

            //平均数
            $average_num = floor(50/$tgbz_yfk_count);

            $xh_arr = [];//循环的方式
            $base_rule_arr = [];//执行分配的规则
            for($n=1;$n<=$tgbz_yfk_count;$n++){
                if( $n == 1 ){
                    $xh_arr[$n] = $average_num*$n + $yushu;
                    for($m=0;$m<($average_num*$n + $yushu);$m++){
                        $base_rule_arr[$m] = $tgbz_yfk_list[$m]['id'];
                    }
                }else{
                    $xh_arr[$n] = $average_num*$n + $yushu;
                    for($m=$xh_arr[$n-1];$m<($average_num*$n + $yushu);$m++){
                        $base_rule_arr[$m] = $tgbz_yfk_list[$m]['id'];
                    }
                }
            }

            //尾款金额
            //$tgbz_wk_jb = $tgbz_ids['total'] - $isprepay_jb;

            //将尾款部分分别打给50个公司账号
            //for($i=0;$i<50;$i++){
            foreach($base_rule_arr as $key => $base_rule){

                $data2['zffs1'] = 1;
                $data2['zffs2'] = 1;
                $data2['zffs3'] = 1;
                $data2['user'] =  $fifity_account[$key]['user'];
                $data2['jb'] = $tgbz_yfk_jb/50;
                $data2['user_nc'] = $fifity_account[$key]['nickname'];
                $data2['user_tjr'] = $fifity_account[$key]['user_tjr'];
                $data2['date'] = date('Y-m-d H:i:s',time());
                $data2['zt'] = 1;
                $data2['qr_zt'] = 0;
                $data2['mainid'] = $mainid;
                $data2['isreset'] = 1; //是否通过交易大厅抢单 0否 1是
                $new_jsbzid = M('jsbz')->add($data2);
                /*
                if( $i == 0 ){
                    $first_id = $new_jsbzid;
                }
                */
                ppdd_add($base_rule,$new_jsbzid);

            }

            //}
        }



        $whereA['id'] = $time_out_ppdd_id;
        $old_ppdd = M('ppdd')->where($whereA)->find();
        if( empty($old_ppdd) )
            //$this->error('抱歉，你下手慢了已经有人抢走了该订单！');
            return ['code'=>0,'msg'=>'抱歉，你下手慢了已经有人抢走了该订单！'];
        $tgbzid = $old_ppdd['p_id'];//买入订单表id[开仓，平仓]
        $jsbzid = $old_ppdd['g_id'];//卖出订单表id【交割】
        /*
        $tgbzid = $_SESSION['user_zdpp_tgbzid'];
        if($tgbzid == null || $tgbzid == "")
            $this->error('请从首页进场后才可匹配');
        */

        /*
        $priority = getUserInEnabled();
        if($priority == 0)
            //$this->error('抱歉，当前不在进场时间范围');
            return ['code'=>0,'msg'=>'抱歉，当前不在进场时间范围'];
        */

        //$map1['user'] = $_SESSION['uname'];
        $map1['id'] = $tgbzid;
        $tgbz = M('tgbz')->where($map1)->find();
        if(!$tgbz)
            //$this->error('订单不存在');
            return ['code'=>0,'msg'=>'订单不存在'];
        if($tgbz['zt'] != 1)
            //$this->error('你的订单已经匹配过了');
            return ['code'=>0,'msg'=>'你的订单已经匹配过了'];

        //$jsbzid = I('get.jsbzid');
        if($jsbzid == null || $jsbzid == "")
            //$this->error('参数不正确');
            return ['code'=>0,'msg'=>'参数不正确'];

        $map2['id'] = $jsbzid;
        $jsbz = M('jsbz')->where($map2)->find();
        if(!$jsbz)
            //$this->error('订单不存在');
            return ['code'=>0,'msg'=>'订单不存在'];
        /*
        if($jsbz['zt'] != 0)
            $this->error('来晚了，别人抢先一步了，试试其它的吧');
        */
        if($tgbz['user'] == $jsbz['user'])
            //$this->error('自己匹配自己？这好像并没有什么意义');
            return ['code'=>0,'msg'=>'自己匹配自己？这好像并没有什么意义'];

        //$count = M('jsbz')->where ("zt=0 and TO_DAYS( '" . $jsbz['date'] ."') - TO_DAYS( date) > 0")->count ();
        $count = M('jsbz')->where ("TO_DAYS( '" . $jsbz['date'] ."') - TO_DAYS( date) > 0")->count ();

//        if($count > 0){
//            //$this->error('为了公平，请优先匹配靠前的订单');
//            return ['code'=>0,'msg'=>'为了公平，请优先匹配靠前的订单'];
//        }

        $where1=array();
        $where1['p_user|g_user'] = $jsbz['user'];
        $where1['zt'] =array('NEQ',2);
        $rs=M('ppdd')->where($where1)->find();
        if ($rs)
        {
            if($rs['p_user'] == $jsbz['user']){
                //$this->error('提现用户还有未付款订单，不能匹配!');
            }else
            {
                //拆分的情况不在校验范围
                $rs_jsbz = M('jsbz')->where(array('id'=>$rs['g_id']))->find();
                if($rs_jsbz['date']<>$jsbz['date']){
                    //$this->error('提现用户还有未收款订单，不能匹配!');
                }
            }
        }

        $login_user_data = M('user')->where(array('UE_account'=>$_SESSION['uname']))->find();

        //公司50个账号【模拟50个账号】
        $fifity_account = [];
        for($j=0;$j<50;$j++){
            //$fifity_account[] = '123456'.$j;
            $fifity_account[] = [
                'account'=>$fifity_account[] = '123456'.$j,//银行账号
                'user' => 'admin'.$j.'@qq.com',//用户账号
                'nickname'=>'公司',//推荐人
                'user_tjr'=>'推荐人',//推荐人
            ];
        }
        //dump($login_user_data);
        //1.预付款的情况【未付款】
        //查询出已匹配 zt为1 ，未确认qr_zt为0 的订单
        $old_tgbz = M('tgbz')->where(array('id'=>$tgbz['id'],'zt'=>1,'qr_zt'=>0))->find();//dump($login_user_data);die;
        if( $old_tgbz['isprepay'] == 1 ){
            //修改当前已经匹配且被封号的会员的tgbz订单【匹配的这部分】
            $update_tgbz['user'] = $login_user_data['ue_account'];
            $update_tgbz['user_tjr'] = $login_user_data['ue_accname'];
            $update_tgbz['date'] = date('Y-m-d H:i:s',time());
            $update_tgbz['user_tjr'] = $login_user_data['ue_accname'];
            $update_tgbz['user_nc'] = $login_user_data['UE_theme'];
            $update_tgbz['isreset'] = 1; //是否通过交易大厅抢单 0否 1是
            M('tgbz')->where(array('id'=>$old_tgbz['id']))->save($update_tgbz);//将更新原来预付款的订单信息

            //找出尾款的这部分tgbz订单
            $wk_tgbz_data = M('tgbz')->where(array('zt'=>1,'qr_zt'=>0,'mainid'=>$old_tgbz['mainid'],'isprepay'=>0))->find();

            //并且将该对应的买入单的尾款部分分别打给50个公司账号
            for($i=0;$i<50;$i++){
                $data2['zffs1'] = 1;
                $data2['zffs2'] = 1;
                $data2['zffs3'] = 1;
                $data2['user'] = $fifity_account[$i]['user'];
                $data2['jb'] = $wk_tgbz_data['jb']/50;
                $data2['user_nc'] = $fifity_account[$i]['nickname'];
                $data2['user_tjr'] = $fifity_account[$i]['user_tjr'];
                $data2['date'] = date('Y-m-d H:i:s',time());
                $data2['zt'] = 1;
                $data2['qr_zt'] = 0;
                $data2['mainid'] = $old_tgbz['mainid'];
                $data2['isreset'] = 1; //是否通过交易大厅抢单 0否 1是
                $new_jsbzid = M('jsbz')->add($data2);
                /*
                if( $i == 0 ){
                    $first_id = $new_jsbzid;
                }
                */
                ppdd_add($tgbzid, $new_jsbzid);
            }

//            if(ppdd_add($old_tgbz['id'], $new_jsbzid))
//            {
//                return ['code'=>200,'msg'=>'匹配成功'];
//                //$this->success('匹配成功!');
//            }else
//                return ['code'=>0,'msg'=>'匹配失败'];
        }

        //2.尾款的情况【未付款】
        if( $old_tgbz['isprepay'] == 0 ){
            //修改当前已经匹配且被封号的会员的tgbz订单【匹配的这部分】
            $update_tgbz['user'] = $login_user_data['ue_account'];
            $update_tgbz['user_tjr'] = $login_user_data['ue_accname'];
            $update_tgbz['date'] = date('Y-m-d H:i:s',time());
            $update_tgbz['user_tjr'] = $login_user_data['ue_accname'];
            $update_tgbz['user_nc'] = $login_user_data['UE_theme'];
            $update_tgbz['isreset'] = 1; //是否通过交易大厅抢单 0否 1是
            M('tgbz')->where(array('id'=>$old_tgbz['id']))->save($update_tgbz);//将更新原来预付款的订单信息

            //找出预付款的这部分tgbz订单【已付款】
            $wk_tgbz_data = M('tgbz')->where(array('zt'=>1,'qr_zt'=>1,'mainid'=>$old_tgbz['mainid'],'isprepay'=>1))->find();
            //M('tgbz')->where(array('id'=>$wk_tgbz_data['id']))->save(array('isreset'=>1));

            //并且将该对应的买入单的尾款部分分别打给50个公司账号
            for($i=0;$i<50;$i++){
                $data2['zffs1'] = 1;
                $data2['zffs2'] = 1;
                $data2['zffs3'] = 1;
                $data2['user'] =  $fifity_account[$i]['user'];
                $data2['jb'] = $wk_tgbz_data['jb']/50;
                $data2['user_nc'] = $fifity_account[$i]['nickname'];
                $data2['user_tjr'] = $fifity_account[$i]['user_tjr'];
                $data2['date'] = date('Y-m-d H:i:s',time());
                $data2['zt'] = 1;
                $data2['qr_zt'] = 0;
                $data2['mainid'] = $old_tgbz['mainid'];
                $data2['isreset'] = 1; //是否通过交易大厅抢单 0否 1是
                $new_jsbzid = M('jsbz')->add($data2);
                /*
                if( $i == 0 ){
                    $first_id = $new_jsbzid;
                }
                */
                ppdd_add($tgbz['id'],$new_jsbzid);

            }

        }


//        if($tgbz['jb'] == $jsbz['jb'])
//        {
//            if(ppdd_add($tgbz['id'], $jsbz['id']))
//            {
//                return ['code'=>200,'msg'=>'匹配成功'];
//                //$this->success('匹配成功!');
//            }else
//                return ['code'=>0,'msg'=>'匹配失败'];
//            //$this->error('匹配失败!');
//        }

    }


	private function lockTable()
	{
		if($this->lockTableFile == NULL)
		{
			$filePath = _ABS_ROOT_.APP_NAME.'/LockFiles/vtradeTableLock.lock';
			if(!file_exists($filePath))
			{
				$fp = fopen($filePath,'w');
				fclose($fp);
			}
			$this->lockTableFile = fopen($filePath,'w');
		}
		flock($this->lockTableFile,LOCK_EX);
	}

	private function unlockTable()
	{
		if($this->lockTableFile != NULL)
		{
			flock($this->lockTableFile,LOCK_UN);
			$this->lockTableFile = NULL;
		}
	}

	private function lockTableBanUser_Limit()
	{
		if($this->lockTableFile == NULL)
		{
			$filePath = _ABS_ROOT_.APP_NAME.'/LockFiles/BanUser_LimitTableLock.lock';
			if(!file_exists($filePath))
			{
				$fp = fopen($filePath,'w');
				fclose($fp);
			}
			$this->lockTableFile = fopen($filePath,'w');
		}
		flock($this->lockTableFile,LOCK_EX);
	}

	private function unlockTableBanUser_Limit()
	{
		if($this->lockTableFile != NULL)
		{
			flock($this->lockTableFile,LOCK_UN);
			$this->lockTableFile = NULL;
		}
	}

	public function ex_single_process()
	{
		//单线程处理，只要有一个人正在处理匹配，其他人就都不需要进行这个工作，直接路过
		$filePath = _ABS_ROOT_.APP_NAME.'/LockFiles/BanUser_LimitSingleProcess.lock';
		if(!file_exists($filePath))
		{
			$fp = fopen($filePath,'w');
			fclose($fp);
		}

		$fp = fopen($filePath,'r+');
		if(!flock($fp,LOCK_EX | LOCK_NB))
			return;
		$lastTime = fgets($fp);
		$lastTime = intval($lastTime);
		if(time()-$lastTime <= 600)
		{
			flock($fp,LOCK_UN);
			return;
		}
		ftruncate($fp,0);
		rewind($fp);
		fwrite($fp,strval(time()));

		set_time_limit(0);
		ignore_user_abort(true);
		$this->lockTableBanUser_Limit();

		//auto_ban_user();//执行自动封号
        zdsjwfk_ppdd_to_ts_zt1();//超时订单的处理【20190603】

		//auto_tgbz_yuyue();//执行自动预约【功能无】

		$this->unlockTableBanUser_Limit();
	    set_time_limit(30);
	    ignore_user_abort(false);
		flock($fp,LOCK_UN);
	}

	/*单线程处理用户等级提升*/
	private function lockTableAccountaddlevel_Limit()
	{
		if($this->lockTableFile == NULL)
		{
			$filePath = _ABS_ROOT_.APP_NAME.'/LockFiles/Accountaddlevel_LimitTableLock.lock';
			if(!file_exists($filePath))
			{
				$fp = fopen($filePath,'w');
				fclose($fp);
			}
			$this->lockTableFile = fopen($filePath,'w');
		}
		flock($this->lockTableFile,LOCK_EX);
	}

	private function unlockTableAccountaddlevel_Limit()
	{
		if($this->lockTableFile != NULL)
		{
			flock($this->lockTableFile,LOCK_UN);
			$this->lockTableFile = NULL;
		}
	}

	public function ex_single_process_Accountaddlevel()
	{
		//单线程处理，只要有一个人正在处理匹配，其他人就都不需要进行这个工作，直接路过
		$filePath = _ABS_ROOT_.APP_NAME.'/LockFiles/Accountaddlevel_LimitSingleProcess.lock';
		if(!file_exists($filePath))
		{
			$fp = fopen($filePath,'w');
			fclose($fp);
		}

		$fp = fopen($filePath,'r+');
		if(!flock($fp,LOCK_EX | LOCK_NB))
			return;
		$lastTime = fgets($fp);
		$lastTime = intval($lastTime);

		if(time()-$lastTime <= 300)
		{
			flock($fp,LOCK_UN);
			return;
		}
		ftruncate($fp,0);
		rewind($fp);
		fwrite($fp,strval(time()));

		set_time_limit(0);
		ignore_user_abort(true);
		$this->lockTableAccountaddlevel_Limit();

        //accountaddlevel($_SESSION['uname']);//升级会员级别【旧方法】

		$this->unlockTableAccountaddlevel_Limit();
	    set_time_limit(30);
	    ignore_user_abort(false);
		flock($fp,LOCK_UN);
	}


    /**
     * 测试使用
     */
    public function test(){
        //dump($_SESSION);
        //getTeamProfit(480);
        /*
        $data = M('user')->where(array('UE_account'=>'admin@qq.com'))->find();
        for($i=0;$i<50;$i++){
            $arr = [
                'UE_account'=>$i.$data['ue_account'],
                'UE_accName'=>$data['ue_account'],
                'sfjl'=>$data['sfjl'],
                'zcr'=>$data['zcr'],
                'UE_check'=>1,
                'UE_password'=>$data['ue_password'],
                //'UE_question'=>,
                'UE_regTime'=>$data['ue_regtime'],
                'UE_regIP'=>$data['ue_regip'],
                'UE_level'=>0,
                'UE_money'=>0,
                'UE_secpwd'=>$data['ue_secpwd'],
                'UE_theme'=>$data['ue_theme'],
                'UE_phone'=>$data['ue_phone'],
                'UE_stop'=>$data['ue_stop'],
                'weixin'=>$data['weixin'],
                'zfb'=>$data['zfb'],
                'yhckr'=>$data['yhckr'],
                'yhmc'=>$data['yhmc'],
                'yhzh'=>$data['yhzh'],
                'tz_leiji'=>0,
                'tx_leiji'=>0,
                'date_leiji'=>null,
                'jl_he'=>0,
                'pp_user'=>'',
                'tj_num'=>0,
                'levelname'=>'v0',
                'qwe'=>0,
                'yzf'=>$data['yzf'],
                'yxhy'=>1,
                'yhzhxx'=>$data['yhzhxx'],
                'regcode'=>$data['regcode'],
                'pdmnum'=>0,
                'jhmnum'=>0,
                'next_tgbz_time'=>date("Y-m-d H:i:s",time()),
                'jihuo_time'=>date("Y-m-d H:i:s",time()),
                'isyuyue'=>0,
                'is_company'=>1,
            ];
            M('user')->add($arr);
        }
        */

        //还原删除的tgbz订单,[错删的订单]
        //$this->reduction_tgbz();

        //查询所有的主订单对应的订单只有一条的记录
        //$list = $this->mainid_only_one();dump($list);

        //将开启防撞功能的用户的该功能做关闭[手动调用]
        //$this->close_fangzhuang();

        //测试当前用户的第一个订单的预付款是否完成打款
        //$res = checkUserFirstTgbzStatus($_SESSION['uname']);

        /*
	    $tgbz_user_xx['ue_accname'] = '4439705@qq.com';
        $ppddxx['jb'] = 800;
        $ppddxx['p_id'] = 636;
        fftuijianmoney($tgbz_user_xx['ue_accname'],$ppddxx['jb'],1,$ppddxx['p_id']);
        */
    }

    /**
     * 订单详情【开仓】
     */
    public function orderdetail()
    {

        $get = I('get.');
        $mainid = $get['mainid'];
        $tgbzModel = M('tgbz');

        //开仓订单的信息
        $yfk_data = [];

        //开仓订单信息
        $yfk_tgbz_data = $tgbzModel->where(array('mainid'=>$mainid,'isprepay'=>1))->select();

        //开仓订单的总金额
        $yfk_data['total'] = 0;
        foreach( $yfk_tgbz_data as $item ){
            $yfk_data['total'] += $item['jb'];
        }

        $data = $tgbzModel->where(array('mainid'=>$mainid))->select();
        $yfk_data['all_total'] = 0;//持仓总量
        $yfk_data['pc_total'] = 0;//平仓积分
        foreach( $data as $item )
        {
            $yfk_data['all_total'] += $item['jb'];
            if( $item['isprepay'] == 0 ){
                $yfk_data['pc_total'] += $item['jb'];
            }
            if( $item['mainid'] == $item['id'] ){
                $yfk_data['main_order_no'] = $item['orderid'];
                $yfk_data['applay_date'] = $item['date'];
            }
        }

        $ppddModel = M('ppdd');//匹配订单信息
        //开仓订单对应的交割订单信息【jsbz】
        $jsbzModel = M('jsbz');//交割订单信息
        //所有的开仓订单信息
        $all_kc_info = $yfk_tgbz_data;

        foreach( $all_kc_info as $key => $kc_info )
        {
            //匹配订单的信息
            $cur_ppdd_data = $ppddModel->where(array('p_id'=>$kc_info['id']))->find();
            //$kc_info['ppdd_data'] = $cur_ppdd_data;
            //开仓订单对应的交割方的信息
            $jsbz_info = $jsbzModel->where(array('id'=>$cur_ppdd_data['g_id']))->find();//dump($jsbz_info);
            if( !empty($jsbz_info) ){
                $jsbz_info['date_hk'] = $cur_ppdd_data['date_hk'];//匹配订单打款完成时间
                $jsbz_info['pic'] = $cur_ppdd_data['pic'];
            }


            //交割方的手机号码
            $jsbz_user_info = M('user')->where(array('UE_account'=>$jsbz_info['user']))->find();
            if( !empty($jsbz_info) ){
                $jsbz_info['ue_phone'] = $jsbz_user_info['ue_phone'];//交割方的手机号码
            }

            //交割方的邀请人的号码
            $jsbz_user_yqr_info = M('user')->where(array('UE_account'=>$jsbz_info['user_tjr']))->find();
            if( !empty($jsbz_info) ){
                $jsbz_info['tjr_ue_phone'] = $jsbz_user_yqr_info['ue_phone'];
            }

            if( !empty($cur_ppdd_data) ){
                $jsbz_info['ppdd_zt'] = $cur_ppdd_data['zt'];
                $jsbz_info['ppdd_date'] = $cur_ppdd_data['date'];
                $jsbz_info['ppdd_date_hk'] = $cur_ppdd_data['date_hk'];
            }


            if( !empty($jsbz_info) ){
                $kc_info['jsbz_list'][] = $jsbz_info;
            }else{
                $kc_info['jsbz_list'] = $jsbz_info;
            }

            $kc_info = array_merge($yfk_data,$kc_info);//合并数组
            $all_kc_info[$key] = $kc_info;
        }
        //datedqsj();
        //dump($all_kc_info);


        $this->assign('all_kc_info',$all_kc_info);

        $this->display();
    }

    /**
     * 订单详情【平仓】
     */
    public function orderdetail_two()
    {

        $get = I('get.');
        $mainid = $get['mainid'];
        $tgbzModel = M('tgbz');

        //平仓订单的信息
        $wk_data = [];

        //平仓订单信息
        $wk_tgbz_data = $tgbzModel->where(array('mainid'=>$mainid,'isprepay'=>0))->select();

        //开仓订单的总金额
        $wk_data['kc_total'] = 0;
//        foreach( $wk_tgbz_data as $item ){
//            $wk_data['kc_total'] += $item['jb'];
//        }

        $data = $tgbzModel->where(array('mainid'=>$mainid))->order('date asc')->select();
        $wk_data['all_total'] = 0;//持仓总量
        $wk_data['pc_total'] = 0;//平仓积分
        foreach( $data as $key => $item )
        {
            if( $key == 0 ){
                $wk_data['applay_date'] = $item['date'];//最早的一条时间记录
            }
            $wk_data['all_total'] += $item['jb'];
            if( $item['isprepay'] == 0 ){
                $wk_data['pc_total'] += $item['jb'];
            }
            if( $item['mainid'] == $item['id'] ){
                $wk_data['main_order_no'] = $item['orderid'];
            }

            //平仓积分
            if( $item['isprepay'] == 1 )
            {
                //开仓订单的总金额
                $wk_data['kc_total'] += $item['jb'];
            }

        }

        $ppddModel = M('ppdd');//匹配订单信息
        //开仓订单对应的交割订单信息【jsbz】
        $jsbzModel = M('jsbz');//交割订单信息
        //所有的开仓订单信息
        $all_kc_info = $wk_tgbz_data;

        foreach( $all_kc_info as $key => $kc_info )
        {
            //匹配订单的信息
            $cur_ppdd_data = $ppddModel->where(array('p_id'=>$kc_info['id']))->find();
            //$kc_info['ppdd_data'] = $cur_ppdd_data;
            //开仓订单对应的交割方的信息
            $jsbz_info = $jsbzModel->where(array('id'=>$cur_ppdd_data['g_id']))->find();//dump($jsbz_info);
            if( !empty($jsbz_info) ){
                $jsbz_info['date_hk'] = $cur_ppdd_data['date_hk'];//匹配订单打款完成时间
                $jsbz_info['pic'] = $cur_ppdd_data['pic'];
            }


            //交割方的手机号码
            $jsbz_user_info = M('user')->where(array('UE_account'=>$jsbz_info['user']))->find();
            if( !empty($jsbz_info) ){
                $jsbz_info['ue_phone'] = $jsbz_user_info['ue_phone'];//交割方的手机号码
            }

            //交割方的邀请人的号码
            $jsbz_user_yqr_info = M('user')->where(array('UE_account'=>$jsbz_info['user_tjr']))->find();
            if( !empty($jsbz_info) ){
                $jsbz_info['tjr_ue_phone'] = $jsbz_user_yqr_info['ue_phone'];
            }

            if( !empty($cur_ppdd_data) ){
                $jsbz_info['ppdd_zt'] = $cur_ppdd_data['zt'];
                $jsbz_info['ppdd_date'] = $cur_ppdd_data['date'];
                $jsbz_info['ppdd_date_hk'] = $cur_ppdd_data['date_hk'];
            }


            if( !empty($jsbz_info) ){
                $kc_info['jsbz_list'][] = $jsbz_info;
            }else{
                $kc_info['jsbz_list'] = $jsbz_info;
            }

            $kc_info = array_merge($wk_data,$kc_info);//合并数组
            $all_kc_info[$key] = $kc_info;
        }
        //datedqsj();
        //dump($all_kc_info);


        $this->assign('all_kc_info',$all_kc_info);

        $this->display();
    }


    /**
     * 还原删除的tgbz订单,[错删的订单]
     */
    private function reduction_tgbz()
    {
        $map['date'] = array('lt','2019-06-01 15:04:38');
        $data = M('tgbz')->where($map)->group('mainid')->select();
        $yfk_tgbz_mainid_list = [];//获取主订单少于2条的记录【该记录就是被删除的】
        foreach($data as $item)
        {
            $yfk_tgbz_list_num = M('tgbz')->where(array('mainid'=>$item['mainid']))->count();
            if( $yfk_tgbz_list_num < 2 ) {
                $yfk_tgbz_mainid_list[] = $item['mainid'];
            }
        }
        //dump($yfk_tgbz_mainid_list);

        //查询出少记录的订单，并且还原该记录【预付款的订单】
        foreach($yfk_tgbz_mainid_list as $mainid)
        {
            $wk_cur_data = M('tgbz')->where(array('mainid'=>$mainid))->select();//dump($wk_cur_data);die;
            if( count($wk_cur_data) < 2 )
            {
                $arr= array(
                    'ppjb'=>0
                );
                M('tgbz')->where(array('id'=>$wk_cur_data[0]['id']))->save($arr);//更新匹配的金额为0
                //增加一条tgbz的记录[用于还原]
                //支付方式
                $data['zffs1'] = '1';
                $data['zffs2'] = '1';
                $data['zffs3'] = '1';
                $data['user'] = $wk_cur_data[0]['user'];
                $data['priority'] = $wk_cur_data[0]['priority'];
                $data['user_nc'] = $wk_cur_data[0]['user_nc'];
                $data['user_tjr'] = $wk_cur_data[0]['user_tjr'];
                $data['date'] = $wk_cur_data[0]['date'];
                $data['zt'] = 0;
                $data['qr_zt'] = 0;
                $data['ppjb'] = 0;
                $data['isfast'] = $wk_cur_data[0]['isfast'];
                $data['mainid'] = $mainid;

                //是否使用预付款拆分功能

                //1.提交预付款
                $data['isprepay'] = 1;
                $data['jb'] = $wk_cur_data[0]['total']-$wk_cur_data[0]['jb'];
                $data['total'] = $wk_cur_data[0]['total']-$wk_cur_data[0]['jb'];
                $data['orderid'] = createorderid('P');

                $newprepayid = M('tgbz')->add($data);

                file_put_contents('update_tgbz.txt',$newprepayid.'||'.date('Y-m-d H:i:s',time()).PHP_EOL,FILE_APPEND);
            }
        }



    }

    /**
     * 将开启防撞功能的用户的该功能做关闭[手动调用]
     */
    private function close_fangzhuang()
    {
        $user_list = M('user')->where(array('fangzhuang'=>1))->select();
        foreach( $user_list as $user )
        {
            $arr = array(
                'fangzhuang'=>0
            );
            M('user')->where(array('UE_ID'=>$user['ue_id']))->save($arr);
        }
    }

    /**
     * 查询所有的主订单对应的订单只有一条的记录
     */
    private function mainid_only_one()
    {
        $tgbzModel = M('tgbz');
        $mainid_list = $tgbzModel->field('mainid')->group('mainid')->select();
        $all_only_one_list = [];
        foreach( $mainid_list as $key=>$value )
        {
            $data = $tgbzModel->where(array('mainid'=>$value['mainid']))->select();
            if(count($data) < 2) {
                $cur_data['id'] = $data[0]['id'];
                $cur_data['user'] = $data[0]['user'];
                $cur_data['mainid'] = $data[0]['mainid'];
                $all_only_one_list[] = $cur_data;
            }
            if( $value['mainid'] == 0 )
            {
                foreach($data as $k => $v) {
                    $cur_data['id'] = $v['id'];
                    $cur_data['user'] = $v['user'];
                    $cur_data['mainid'] = $v['mainid'];
                    $all_only_one_list[] = $cur_data;
                }
            }

        }
        return $all_only_one_list;
    }

}
