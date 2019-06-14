<?php if (!defined('THINK_PATH')) exit();?><!-- header start -->
 <!DOCTYPE html>
<html lang="zh-cmn-Hans">
 <head> 
  <meta charset="UTF-8" /> 
  <title>跳转中...</title> 
  <meta name="viewport" content="width=device-width, initial-scale=.8, user-scalable=yes" /> 
  <meta name="renderer" content="webkit" /> 
  <meta http-equiv="Cache-Control" content="public" /> 
  <link rel="shortcut icon" href="/assets/wns/img/favicon.ico" /> 
  <link rel="stylesheet" href="/assets/wns/css/layui.css" /> 
  <link rel="stylesheet" href="/assets/wns/css/backend.css" /> 
  <link rel="stylesheet" href="/assets/wns/css/bootstrap.min.css" /> 
  <link rel="stylesheet" href="/assets/wns/css/skins/skin-blue.css" /> 
  <script src="/assets/wns/js/jquery.min.js"></script> 
  <script src="/assets/wns/js/jquery.slimscroll.min.js"></script> 
  <script src="/assets/wns/js/adminlte.js"></script> 
  <script src="/assets/wns/js/bootstrap.min.js"></script> 
  <script src="/assets/wns/js/layui.all.js"></script> 
  <script src="/assets/js/jquery.countdown.js"></script> 
  <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. --> 
  <!--[if lt IE 9]>
  <script src="/assets/wns/js/html5shiv.js"></script>
  <script src="/assets/wns/js/respond.min.js"></script>
<![endif]--> 
<style>
	div.switch{display: inline-block;height: 40px;margin:20px 0;}
	.help{margin:10px;font-size: 1.1em;}
</style>
 <style>
 .b-page {
    background: #fff;
    box-shadow: 0px 1px 2px 0px #E2E2E2;
}
.page {
    width: 100%;
    padding: 0px 0px 0 px 20px;
    text-align: right;
    overflow: hidden;
}
.page .first,
.page .prev,
.page .current,
.page .num,
.page .current,
.page .next,
.page .end {
    padding: 8px 16px;
    margin: 0px 5px;
    display: inline-block;
    color: #008CBA;
    border: 1px solid #F2F2F2;
    border-radius: 5px;
}
.page .first:hover,
.page .prev:hover,
.page .current:hover,
.page .num:hover,
.page .current:hover,
.page .next:hover,
.page .end:hover {
    text-decoration: none;
    background: #F8F5F5;
}
.page .current {
    background-color: #008CBA;
    color: #FFF;
    border-radius: 5px;
    border: 1px solid #008CBA;
}
.page .current:hover {
    text-decoration: none;
    background: #008CBA;
}
.page .not-allowed {
    cursor: not-allowed;
}
.rows
{
  color:white;
}
 </style>
 </head> 
 <body class="hold-transition skin-blue sidebar-mini fixed" id="tabs"> 
  <div class="wrapper"> 
   <header id="header" class="main-header">
    <a class="logo" style="text-decoration: none"> <span class="logo-mini"><?php echo C('webname_full');?></span> <span class="logo-lg"><b><?php echo C('webname_full');?></b></span> </a> 
    <nav class="navbar navbar-static-top"> 
     <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" style="width: auto;text-decoration: none;font-size: 2em;padding: 8px;background: rgba(0,0,0,.2);z-index: 999"> <span class="sr-only">菜单</span><span>菜单</span> </a> 
     <div id="nav" class="pull-left"> 
      <ul class="nav nav-tabs nav-addtabs disable-top-badge" role="tablist"> 
      </ul> 
     </div> 
     <div class="navbar-custom-menu"> 
      <ul class="nav navbar-nav"> 
       <li> <a style="font-size:1.2em;padding-left: 0;padding-right: 0;cursor: pointer;" class="kefuwechat" data="LXJY-168">点击专属客服:<span style="display: inline-block;border:2px solid red">小玲&nbsp;&nbsp;&nbsp;</span></a> &nbsp;&nbsp;&nbsp;</li> 
       <li> <a href="/Home/Login/logout.html" class="btn btn-danger" style="height: 50px;border: none;font-size: 1.4em"><i class="fa fa-sign-out"></i> <span>退出</span></a> </li> 
      </ul> 
     </div> 
    </nav> 
   </header> 
   <aside class="main-sidebar" style="background: #000;border-right: 1px solid rgba(255,255,255,.1);font-size: 1.8em;"> 
    <section class="sidebar"> 
     <ul class="sidebar-menu"> 
      <li class="<?php echo ($home_active); ?>">
	    <a href="/?o" url="/?o" py="kb" pinyin="shouye">
	    <i class="fa fa-dashboard"></i> <span>首页</span> 
	    <span class="pull-right-container"></span>
		</a>
	  </li>
	  <li class="<?php echo ($getjhm_active); ?> " <?php if($userData['ue_check'] != 0): ?>style='display:none'<?php endif; ?>>
	    <a href="/Home/Index/getjhm.html" url="/Home/Index/getjhm.html" py="xw" pinyin="tuandui"><i class="fa fa-users"></i> <span>抢激活码</span> <span class="pull-right-container"> </span></a> 
	  </li>
      <li class="<?php echo ($team_active); ?>">
	    <a href="/Home/Myuser/index.html" url="/Home/Myuser/index.html" py="xw" pinyin="tuandui"><i class="fa fa-users"></i> <span>团队</span> <span class="pull-right-container"> </span></a> 
	  </li> 
      <li class="<?php echo ($pinfo_active); ?>">
	    <a href="/Home/Info/index.html" url="/Home/Info/index.html" py="xw" pinyin="gerenshezhi"><i class="fa fa-cogs"></i> <span>个人设置</span> <span class="pull-right-container"> </span></a> 
	  </li> 
      <li class="<?php echo ($wallet_active); ?>">
	    <a href="/Info/wallet" url="/Info/wallet" py="xw" pinyin="qianbao"><i class="fa fa-rmb"></i> <span>钱包</span> <span class="pull-right-container"> </span></a> 
	  </li>
	  <li class="<?php echo ($pdm_active); ?>">
	    <a href="/Info/paidan" url="/Info/paidan" py="xw" pinyin="qianbao"><i class="fa fa-rmb"></i> <span><?php echo C('pdm_name');?></span> <span class="pull-right-container"> </span></a> 
	  </li>
	  <li class="<?php echo ($jhm_active); ?>">
	    <a href="/Info/jhm" url="/Info/jhm" py="xw" pinyin="qianbao"><i class="fa fa-rmb"></i> <span><?php echo C('jhm_name');?></span> <span class="pull-right-container"> </span></a> 
	  </li>
      <li  class="<?php echo ($jifen_active); ?>">
	    <a href="/Info/jifen" url="/Info/jifen" py="jf" pinyin="jifen"><i class="fa fa-money"></i> <span><?php echo C('jifen_wallet_name');?></span> <span class="pull-right-container"> </span></a>
	  </li> 
      <li>
	    <a href="<?php echo ($tgurl); ?>" url="<?php echo ($tgurl); ?>" py="xw" pinyin="zhucexinhuiyuan"><i class="fa fa-user-plus"></i> <span>注册新会员</span> <span class="pull-right-container"> </span></a> </li> 
      <li  class="<?php echo ($shopjifen_active); ?>">
	    <a href="/Info/shopjifen" url="/Info/shopjifen" url=""><i class="fa fa-shopping-cart"></i> <span><?php echo C('shopjifen_wallet_name');?></span> <span class="pull-right-container"> </span></a> 
	  </li> 
      <li>
	    <a href="javascript:alert('模块开发中');" url=""><i class="fa fa-balance-scale"></i> <span>积分交易系统</span> <span class="pull-right-container"> </span></a>
	  </li> 
     </ul> 
    </section> 
   </aside> 

   <script>
    var layer = layui.layer;
	(function(){
		$(document).on('click','.sidebar-menu li',function(){
			$('.sidebar-menu li').removeClass('active');
			$(this).addClass('active');
		})

        //全屏事件
        $(document).on('click', "[data-toggle='fullscreen']", function () {
            var doc = document.documentElement;
            if ($(document.body).hasClass("full-screen")) {
                $(document.body).removeClass("full-screen");
                document.exitFullscreen ? document.exitFullscreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitExitFullscreen && document.webkitExitFullscreen();
            } else {
                $(document.body).addClass("full-screen");
                doc.requestFullscreen ? doc.requestFullscreen() : doc.mozRequestFullScreen ? doc.mozRequestFullScreen() : doc.webkitRequestFullscreen ? doc.webkitRequestFullscreen() : doc.msRequestFullscreen && doc.msRequestFullscreen();
            }
        });
        var sss = location.href.split(location.host+'/')[1];
        if ($.inArray(sss,['team','setting','wallet','jifen','deal'])>-1) {
            $('.sidebar-menu li').removeClass('active');
            $(document).find('[href=\'/'+sss+'\']').parents('li').addClass('active');
        }
        $(document).on('click','.kefuwechat',function(){
            var wechat = $(this).attr('data');
            layer.open({
                type:1,
                content:'<div style=\'padding:30px;font-size:1.2em;text-align:center\'>官方客服微信号→ <span style=\'display:inline-block;border:1px solid #555 ;padding:2px 4px\'>'+wechat+'</span><br><br><span style=\'font-size:.85em;color:#777\'>请仔细核对微信号，防止骗子!</span></div>',
                title:'注意'
            })
        })
	})();
</script> 
   <style>
    div.maincon{width: 100%;text-align: center;margin: 20px auto;}
    .contitle{text-align: left;background: rgba(255,255,255,.8);padding: 0.5em 1em;border-bottom: 3px solid #3c8dbc;font-size: 1.2em;margin-top: 10px;font-weight: bold}
    .content-wrapper{background: url('/assets/wns/img/newbg.jpg') 50% 50% repeat #000;background-size: contain;}
    table.table td , table.table th{text-align: center;}
    .table tr:nth-child(odd){background: rgba(0,0,0,.03);}
    .table tr:hover{background: rgba(255,255,255,.4);}
    .skin-blue .main-header .navbar{}
    .skin-blue .main-header .logo{}
    @media (max-width: 767px) {
        .skin-blue .main-header .navbar{background-size: cover;}
    }
</style> 
<!-- header end -->
   <div class="content-wrapper tab-content tab-addtabs"> 
    <div role="tabpanel" class="tab-pane active" style="overflow: auto"> 
     <div class="maincon"> 
      <div class="walletpanel"> 
       <div class="row dddd"> 
        <div class="clo-lg-12"> 
         <div class="num">
          <?php echo C('pdm_name');?>:<?php echo ($userData['pdmnum']); ?>,<?php echo C('jhm_name');?>:<?php echo ($userData['jhmnum']); ?>
         </div> 
         <span>支付<?php echo C('pdm_name');?>请联系客服,<!--客服QQ：<a style="margin:0 10px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=1063156322&amp;site=&amp;menu=yes"><img style="vertical-align:middle;" border="0" src="http://wpa.qq.com/pa?p=2:1063156322:51" alt="" title="" /></a> --> 微信:LXJY-168</span> 
        </div> 
        <div class="clo-lg-12"> 
         <div class="num">
          <?php echo C('bx_wallet_name');?>:<?php echo ($userData['ue_money']); ?>
         </div> 
         <span></span> 
        </div> 
        <div class="clo-lg-12"> 
         <div class="num">
          <?php echo C('ldj_wallet_name');?>:<?php echo ($userData['qwe']); ?>
         </div> 
         <span></span> 
        </div>
		<div class="clo-lg-12"> 
         <div class="num">
          <?php echo C('jifen_wallet_name');?>:<?php echo ($userData['jifen']); ?>
         </div> 
         <span> 
		    <a class="btn btn-primary" style="margin: 3px;" href="/Info/jifen.html">兑换<?php echo C('jhm_name');?></a>&nbsp;&nbsp;
		 </span> 
        </div> 
        <div class="clo-lg-12"> 
         <div class="num">
          <?php echo C('shopjifen_wallet_name');?>:<?php echo ($userData['shopjifen']); ?>
         </div> 
         <span> 
		    <a class="btn btn-primary" style="margin: 3px;" href="javascript:alert('模块开发中...');">积分转账</a>&nbsp;&nbsp;
		    <a class="btn btn-primary" style="margin: 3px;" href="javascript:alert('模块开发中...');" target="_blank"><i class="fa fa-shop-cart"></i><span class="titl"><?php echo C('webname_eazy');?>商城</span></a>
		 </span> 
        </div> 

       </div> 
      </div> 
      <div class="walletpanel" style="display:none"> 
       <a href="/jiesuan" class="btn btn-success">查看历史结算数据</a> 
      </div> 
      <div class="walletpanel"> 
       <div class="contitle active">
         利息 
       </div> 
       <table class="table"> 
        <tbody>
         <tr> 
          <th>编号</th>
          <th>日期</th>
          <th>金额</th>
          <th>利息</th>
          <th>天数</th>
          <th>提现</th>
          <th>是否转出</th>
          <th>匹配编号  </th>
          <th>匹配状态</th>
         </tr>
		 <?php if(is_array($tgbz_list)): foreach($tgbz_list as $key=>$v): ?><tr>
          <td class="sorting_1"><?php echo ($aab=$v["orderid"]); ?></td>
          <td><?php echo ($v["date"]); ?></td>
          <td><?php echo ($v["total"]); ?></td>
          <td><?php echo (w_peidui_lx($v)); ?></td>
          <td><?php echo (w_peidui_day($v)); ?></td>
          <td>
          <?php if($v["zt"] == '0'): ?>(不可提现)
          <?php else: ?>
            已转出<?php endif; ?>
          </td>
          <td><?php echo (user_tgbz_jerry($v["id"])); ?></td>
          <td>未匹配</td>
          <td>排队中</td>
         </tr><?php endforeach; endif; ?>

		 <?php if(is_array($jj_list)): foreach($jj_list as $key=>$v): ?><tr>
          <td ><?php echo (getporderid_by_jj_r_id($aab=$v["r_id"])); ?></td>
          <td><?php echo ($v["date"]); ?></td>
          <td><?php echo ($v["total"]); ?></td>
          <td><?php echo (user_jj_zong_lx($v["id"])); ?></td>
          <td><?php echo (user_jj_zong_ts($v["id"])); ?></td>
          <td>
             <?php echo (canable_tixian($v)); ?>
          </td>
          <td>
             <?php if($v["zt"] == '0'): ?>未转出<?php endif; ?>
             <?php if($v["zt"] == '1'): ?>已转出(<?php echo (get_had_zc($v["r_id"])); ?>)<?php endif; ?>
          </td>
          <td><?php echo (getpporderid_by_jj_r_id($bbh=$v["r_id"])); ?></td>
          <td><?php echo (user_jj_pipei_z($bbh,$ztrs2)); ?></td>
         </tr><?php endforeach; endif; ?>
        </tbody>
       </table> 
       <div class="fixed-table-pagination" style="display: block;"> 
	   <div class="pull-left pagination-detail"> 
         <span class="pagination-info"> 共 <?php echo ($jj_tg_count_sum); ?> 条纪录
		 <a href="?jjshowall=<?php echo ($jjshowall); ?>" class="btn btn-success showall"><?php if($jjshowall == 1): ?>显示<?php else: ?>隐藏<?php endif; ?>所有已提现订单</a></span> 
         <span class="page-list2">每页显示 <span class="btn-group dropup"> <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> <span class="page-size2">10</span> <span class="caret"></span> </button> 
           <ul class="dropdown-menu" role="menu"> 
            <li role="dropdown-menu" class="active"><a>10</a></li>
            <li role="dropdown-menu"><a>25</a></li>
            <li role="dropdown-menu"><a>50</a></li> 
           </ul> </span>条记录</span> 
        </div> 
        <div class="pull-right pagination"> 
         <div class="page"> 
		      <?php echo ($jj_page); ?>
         </div> 
        </div> 
        </div> 
       <div class="contitle active">
         奖励 
       </div> 
       <table class="table"> 
	    <tr> 
          <th>类型</th>
          <th>日期 </th>
          <th>详情</th>
          <th>+收入/-支出</th>
		  <th>余额</th>		
         </tr> 
        <tbody>
          <?php if(is_array($bonus_list)): foreach($bonus_list as $key=>$b): ?><tr>
                <td><?php echo (get_jj_type($b["ug_datatype"])); ?></td>
                <td><?php echo ($b["ug_gettime"]); ?></td>
                <td><?php echo ($b["ug_note"]); ?></td>
                <td><?php echo ($b["ug_money"]); ?></td>
                <td><?php echo ($b["ug_balance"]); ?></td>
             </tr><?php endforeach; endif; ?>
        </tbody>
       </table> 
       <div class="fixed-table-pagination" style="display: block;"> 
        <div class="pull-left pagination-detail"> 
         <span class="pagination-info"> 共 <?php echo ($bonus_count); ?> 条纪录</span> 
         <span class="page-list2">每页显示 <span class="btn-group dropup"> <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> <span class="page-size2">10</span> <span class="caret"></span> </button> 
           <ul class="dropdown-menu" role="menu"> 
            <li role="dropdown-menu" class="active"><a>10</a></li>
            <li role="dropdown-menu"><a>25</a></li>
            <li role="dropdown-menu"><a>50</a></li> 
           </ul> </span>条记录</span> 
        </div> 
        <div class="pull-right pagination"> 
         <div class="page"> 
		      <?php echo ($bonus_page); ?>
         </div> 
        </div> 
       </div> 
      </div> 
     </div> 
    </div> 
   </div> 
   <style>
	.walletpanel{background: rgba(255,255,255,.9);padding: 10px 5px;}
	.dddd{width: 96%;text-align: center;margin:0 auto;}
	.dddd > div{height: 5em;padding: 5px;border: 1px solid rgba(0,0,0,.1)}
	.dddd > div .num{font-size: 1.5em;font-weight: bold;display: inline-block;height: 100%;float: left;line-height: 2.8em}
	tr.green{background: rgba(0,250,0,.2) !important;color: #444}
	tr.red{background: rgba(250,0,0,.2) !important;color: #444}
</style> 
</div>

<script>
	(function(){
		document.title="钱包";
		//积分转账
    })();
</script>
<!-- footer start -->
  </div>
 </body>
</html>
<!-- footer end -->