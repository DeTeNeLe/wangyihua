<?php
namespace Home\Controller;
use Think\Controller;
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

				 //pin值
                 $this->pin_zs = get_userinfo($_SESSION['uname'],'pdmnum');

				 $this->display("act/buyincon");
			}elseif($act == "selloutcon")
			{
				 //获取诚信奖钱包总额;
				 $data['UG_account'] = $_SESSION['uname'];
				 $chenxin_total = M('user')->where(array('UE_account' => $_SESSION['uname']))->sum('jifen');
				 $benxi_cash = M('user')->where(array('UE_account' => $_SESSION['uname']))->sum('ue_money');
				 $jiangli_cash = M('user')->where(array('UE_account' => $_SESSION['uname']))->sum('qwe');
				 $this->assign('benxi_cash', $benxi_cash);
				 $this->assign('jiangli_cash', $jiangli_cash);

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
				 $map['yuyuezhouqi'] = I('post.d');
				 $map['yuyuemoney'] = I('post.m');

				 $tg_min = get_min();
				 $tg_max = get_max();

				 if ($map['yuyuemoney'] < $tg_min || $map['yuyuemoney'] > $tg_max || $map['yuyuemoney'] % C("jj01") > 0) 
				 {
				     $this->ajaxReturn(array('nr' => "预约金额" . $tg_min . "-" . $tg_max . ",并且是" . C("jj01") . "的倍数！", 'sf' => 0));
                 } elseif ($map['yuyuemoney'] % C("jj01") > 0) {
				    $this->ajaxReturn(array('nr' => "预约金额" . $tg_min . "-" . $tg_max . ",并且是" . C("jj01") . "的倍数！", 'sf' => 0));
                 }

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
					$yuyue_table = $yuyue_table . 
					   "<tr style='color:Red'>
				          <td>".date('Y-m-d',strtotime($tgbz[0]['date']))."</td>
				          <td>".$tgbz[0]['jb']."</td>
				          <td>已成功挂单</td>
				        </tr> ";				}

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
            $config = array('maxSize' => 3145728, 'savePath' => '', 'saveName' => array('uniqid', ''), 'exts' => array('jpg', 'gif', 'png', 'jpeg'), 'autoSub' => true, 'subName' => array('date', 'Ymd'),);
            $upload = new ThinkUpload($config); // 实例化上传类
            $images = $upload->upload();
            //判断是否有图
            if ($images) {
                $info = $images['Filedata']['savepath'] . $images['Filedata']['savename'];
                //返回文件地址和名给JS作回调用
                echo $info;
            } else {
                //$this->error($upload->getError());//获取失败信息
                
            }
        }
    }

    public function home()
	{
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
				$this->error('未挂单封号','/Home/Login/index.html');
			}
            $this->error("请于" . $userData['next_tgbz_time']. "之前提供帮助，否则封号",'/Home/index.html');
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
        //读取两条最新的新闻
        $info = M("info");
        $info_list = $info->limit(2)->order('if_id desc')->select();
        $this->assign('info_list', $info_list);


		$this->ex_single_process();

		$this->ex_single_process_Accountaddlevel();

		$this->assign('home_active','active');

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
    public function tgbzcl()
	{
        if (IS_POST) 
		{
            $data_P = I('post.');
			$money = $data_P['amount'];

            $user = M('user')->where(array(UE_account => $_SESSION['uname'], UE_check => 1))->find();
            if (!$user) {
				$this->ajaxReturn(array('nr' => '该帐号未激活,不能进行操作', 'sf' => 0));
            }

            $first_paidan = M('tgbz')->where("user='" . $_SESSION['uname'] . "'")->find();
            if ($first_paidan == null && $user['is_first'] == 1) {
				$this->ajaxReturn(array('nr' => '/index/question', 'sf' => 2));
            }
            $usermm = M('user')->where(array(UE_account => $_SESSION['uname']))->find();
            $tgbz_time = M('tgbz')->where("user='" . $_SESSION['uname'] . "' ")->max('date');
            if (C("tgbz_time") > 0) {
                if ((strtotime($tgbz_time) + C("tgbz_time") * 3600 * 24) > time()) {
					$this->ajaxReturn(array('nr' => '你距离上次排单时间不足' . C('tgbz_time') . '天', 'sf' => 0));
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
                $starttime = date('Y-m-d 00:00:01', time());
                $endtime = date('Y-m-d 23:59:59', time());
                $count = M("tgbz")->where("date>='$starttime' and date<='$endtime' and user='$uname'")->count();
                if (($count+1) > $paidan_num) 
				{
					$this->ajaxReturn(array('nr' => '今日排单数量已满，欢迎明日再来!', 'sf' => 0));
                }
            }
            //每天排单总额度
            $paidan_jbs = C('paidan_jbs');
            if ($paidan_jbs > 0) 
			{
                $sum = M("tgbz")->where("date>='$starttime' and date<='$endtime' ")->sum('jb');
                if (($sum + $money) > $paidan_jbs) {
					$this->ajaxReturn(array('nr' => '今日排单额度已满，记得明日抢早排单哦!', 'sf' => 0));
                }
            }
            

			//防撞单
            //修改------>防撞单功能：默认是开启防撞单，前台有防撞单按钮，如果开启防撞单，
			//那么此会员只能存在一笔未完成订单，需要完成这笔订单，才能继续下笔订单，
			//如果点击不开启的话，可以同时存在多笔订单进行排单
            $tgbz= M('tgbz');
            $where=array();
            $where['user'] = $_SESSION ['uname'];
            $where['qr_zt'] =  array('eq',0);
            $rs=$tgbz->where($where)->find();
            if ($user['fangzhuang'] == 1 && $rs )
            {
			     $this->ajaxReturn(array('nr' => "你已启用防撞单功能,您还有未完成的订单未处理，不能继续申请", 'sf' => 0));
            }


            //用户提供帮助最多允许等待匹配单数
            $oneByone = C('oneByone');
            if ($oneByone > 0) {
                $tgbz_count = M('tgbz')->where("user='" . $_SESSION['uname'] . "' and zt =0")->count();
                if ($tgbz_count > $oneByone) {
					$this->ajaxReturn(array('nr' => "用户提供帮助最多允许等待匹配" . $oneByone . "单！", 'sf' => 0));
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
						M('user')->where(array('UE_account' =>$_SESSION['uname']))->setDec('pdmnum', $paidanb);

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
					M('tgbz')->where(array('id' => $newmainid))->save(array('mainid' => $newmainid));
					if($newprepayid)
						M('tgbz')->where(array('id' => $newprepayid))->save(array('mainid' => $newmainid));
					if($paidan_log_newid)
					{
						M('paidan_log')->where(array('id' => $paidan_log_newid))->save(array('info' => '排单消耗ID:' . $mainorderid));
					}
					$this->ajaxReturn(array('nr' => '提交成功！', 'sf' => 1));
                } else 
				{
					$this->ajaxReturn(array('nr' => '提交失败！!', 'sf' => 0));
                }
            }
        }
    }

    public function jsbzcl_bx() 
	{
        if (IS_POST) {
            $data_P = I('post.');
			check_tx_status();
            $user = M('user')->where(array(UE_account => $_SESSION['uname'], UE_check => 1))->find();
            if (!$user) {
				return_die_ajax('该帐号未激活 不能进行操作');
            }
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
				return_die_ajax("接受帮助金额" . C("txthemin") . "起并且是" . C("txthebeishu") . "的倍数！");
            } elseif ($data_P['get_amount'] % C("txthebeishu") > 0) {
				return_die_ajax("接受帮助金额" . C("txthemin") . "起并且是" . C("txthebeishu") . "的倍数！");
            } elseif ($data_P['get_amount'] > C("txthemax")) {
				return_die_ajax("接受帮助最大金额为" . C("txthemax"));
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
                $note3 = "接受帮助扣款";
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
            $user = M('user')->where(array(UE_account => $_SESSION['uname'], UE_check => 1))->find();
            if (!$user) {
				return_die_ajax("该帐号未激活!");
            }

            if (isset($_SESSION['num_tx_day']) && ($_SESSION['num_tx_day'] <= 0)) {
				return_die_ajax("提现次数已达系统上限！");
            }
            $usermm = M('user')->where(array(UE_account => $_SESSION['uname']))->find();
            $today = today_get($_SESSION['uname'], 2);

            $limit_get = level_limit_get($usermm['levelname'], C('tjj_tx_day'));

            if ($limit_get - ($today + $data_P['get_amount']) < 0) {
				return_die_ajax("你的推荐奖钱包当天只能提现" . $limit_get);
            }

            $tj_baifenbi = C('tj_baifenbi');
            $max_jb = $usermm['qwe'] * $tj_baifenbi / 100;
  
            if ($data_P['get_amount'] > $max_jb) {
				return_die_ajax("推荐奖每轮只能最多提取总额的" . $tj_baifenbi . "%");
            }
            $tj_start = C('tj_start');
            $tj_e = C('tj_e');
            $tj_beishu = C('tj_beishu');
            //推荐奖提现总额
            $tx_tuijian_total = C('tx_tuijian_total');

            $tuijian_tixian = M('tixian')->where(array(UG_account => $_SESSION['uname']))->sum('TX_money');

            if ($tuijian_tixian > $tx_tuijian_total) {
				return_die_ajax("推荐奖提现超过总额限制" . $tx_tuijian_total);
            }
            if ($data_P['get_amount'] > $tj_e) {
				return_die_ajax("推荐奖提现超过最大额度".$tj_e);
            }
            if ($data_P['get_amount'] > $user['qwe']) {
				return_die_ajax("推荐奖提现超过钱包余额");
            }
            if ($data_P['get_amount'] < $tj_start) {
				return_die_ajax("推荐奖提现小于最低额度".$tj_start);
            }
            if (($data_P['get_amount'] % $tj_beishu) != "0") {
				return_die_ajax("推荐奖提现必须是" . $tj_beishu . "的倍数！");
            }

			//接受帮助金额不小于上一轮的百分比设置：
			$limit_amount = get_js_min_compare_last();
            if ($data_P['amount'] < $limit_amount) {
				return_die_ajax("最低提现金额为" . $limit_amount);
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
				return_die_ajax("接受帮助金额" . C("txthemin") . "起并且是" . C("txthebeishu") . "的倍数！");
            } elseif ($data_P['get_amount'] % C("txthebeishu") > 0) {
				return_die_ajax("接受帮助金额" . C("txthemin") . "起并且是" . C("txthebeishu") . "的倍数！");
            } elseif ($user['qwe'] < $data_P['get_amount']) {
				return_die_ajax("余额不足！");
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

                $note3 = "接受帮助扣款";
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
					return_die_ajax("提交成功！",true,1);
                } else {
					return_die_ajax("提交失败！");
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
    //取消等待接受帮助
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
            $data2['note'] = '提供帮助'; 
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

				if(C('sms_open_pay') == "1")
				{
					sendSMS($ppddxx['g_user'],"亲爱的会员您好，您的订单对方已支付，请及时确认【" . C('sms_sign') . "】");
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
				
				//处理提供帮助的推荐人是否可以升级为经理的考核
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
                //获取提供帮助人的详细信息
                
                //echo $ppddxx['p_id'];die;
                //如果提供帮助有推荐人
                /*if($tgbz_user_xx['ue_accname']<>''){               
                jlsja($tgbz_user_xx['ue_accname']);  //处理提供帮助的推荐人是否可以升级为经理的考核
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

				/*s1.*/
                //获取提供帮助人的详细信息
                $tgbz_user_xx = M('user')->where(array('UE_account' => $ppddxx['p_user']))->find(); //充值人详细
                //如果提供帮助有推荐人
                if($tgbz_user_xx['ue_accname']<>'')
				{
					//打款了才判断为有效会员
					if($tgbz_user_xx["yxhy"] == 0)
					{
					   mmtjrennumadd($tgbz_user_xx["ue_accname"]);
				       accountaddlevel($tgbz_user_xx["ue_accname"]);
					   M('user')->where(array('UE_account' => $tgbz_user_xx["ue_account"]))->save(array('yxhy'=>1));
					}
                    fftuijianmoney($tgbz_user_xx['ue_accname'],$ppddxx['jb'],1,$ppddxx['p_id']);
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
            die("<script>alert('汇款时间未超过" . C("jjdktime") . "小时,暂不能投诉,如未打款,请与提供帮助者取得联系！');history.back(-1);</script>");
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
                //提供帮助者表单信息
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
        //提供帮助配对流程
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
		if(time()-$lastTime <= 1800)
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
		
		auto_ban_user();

		auto_tgbz_yuyue();

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

        accountaddlevel($_SESSION['uname']);

		$this->unlockTableAccountaddlevel_Limit();
	    set_time_limit(30);
	    ignore_user_abort(false);
		flock($fp,LOCK_UN);
	}

}
