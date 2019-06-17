<?php if (!defined('THINK_PATH')) exit();?><!-- header start -->
<!DOCTYPE html>
<html lang="zh-cmn-Hans">

	<head>
		<meta charset="UTF-8" />
		<title>跳转中...</title>
		<meta name="viewport" content="width=device-width, initial-scale=.8, user-scalable=no" />
		<meta name="renderer" content="webkit" />
		<meta http-equiv="Cache-Control" content="public" />
		<link rel="shortcut icon" href="/assets/wns/img/favicon.ico" />
		<link rel="stylesheet" href="/assets/wns/css/layui.css" />
		<link rel="stylesheet" href="/assets/wns/css/backend.css" />
		<link rel="stylesheet" href="/assets/wns/css/bootstrap.min.css" />
		<link rel="stylesheet" href="/assets/wns/css/skins/skin-blue.css" />
		<link rel="stylesheet" type="text/css" href="/assets/wns/css/style.css" />
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
			div.switch {
				display: inline-block;
				height: 40px;
				margin: 20px 0;
			}
			
			.help {
				margin: 10px;
				font-size: 1.1em;
			}
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
			
			.rows {
				color: white;
			}
		</style>
	</head>

	<body class="hold-transition skin-blue sidebar-mini fixed" id="tabs">
		<div class="wrapper" style="">
			<!--<header id="header" class="main-header">
				<a class="logo" style="text-decoration: none"> <span class="logo-mini"><?php echo C('webname_full');?></span> <span class="logo-lg"><b><?php echo C('webname_full');?></b></span> </a>
				<nav class="navbar navbar-static-top">
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" style="width: auto;text-decoration: none;font-size: 2em;padding: 8px;background: rgba(0,0,0,.2);z-index: 999"> <span class="sr-only">菜单</span><span>菜单</span> </a>
					<div id="nav" class="pull-left">
						<ul class="nav nav-tabs nav-addtabs disable-top-badge" role="tablist">
						</ul>
					</div>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<li>
								<a style="font-size:1.2em;padding-left: 0;padding-right: 0;cursor: pointer;" class="kefuwechat" data="有您的加入更精彩">专属客服:<span style="display: inline-block;border:2px solid red">小玲&nbsp;&nbsp;&nbsp;</span></a> &nbsp;&nbsp;&nbsp;</li>
							<li>
								<a href="/Home/Login/logout.html" class="btn btn-danger" style="height: 50px;border: none;font-size: 1.4em"><i class="fa fa-sign-out"></i> <span>退出</span></a>
							</li>
						</ul>
					</div>
				</nav>
			</header>-->
			<!--<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="3000">
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
							<li class="<?php echo ($jifen_active); ?>">
								<a href="/Info/jifen" url="/Info/jifen" py="jf" pinyin="jifen"><i class="fa fa-money"></i> <span><?php echo C('jifen_wallet_name');?></span> <span class="pull-right-container"> </span></a>
							</li>
							<li>
								<a href="<?php echo ($tgurl); ?>" url="<?php echo ($tgurl); ?>" py="xw" pinyin="zhucexinhuiyuan"><i class="fa fa-user-plus"></i> <span>注册新会员</span> <span class="pull-right-container"> </span></a>
							</li>
							<li class="<?php echo ($shopjifen_active); ?>">
								<a href="/Info/shopjifen" url="/Info/shopjifen" url=""><i class="fa fa-shopping-cart"></i> <span><?php echo C('shopjifen_wallet_name');?></span> <span class="pull-right-container"> </span></a>
							</li>
						</ul>
					</section>
				</aside>-->

				<script>
					var layer = layui.layer;
					(function() {
						$(document).on('click', '.sidebar-menu li', function() {
							$('.sidebar-menu li').removeClass('active');
							$(this).addClass('active');
						})

						//全屏事件
						$(document).on('click', "[data-toggle='fullscreen']", function() {
							var doc = document.documentElement;
							if($(document.body).hasClass("full-screen")) {
								$(document.body).removeClass("full-screen");
								document.exitFullscreen ? document.exitFullscreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitExitFullscreen && document.webkitExitFullscreen();
							} else {
								$(document.body).addClass("full-screen");
								doc.requestFullscreen ? doc.requestFullscreen() : doc.mozRequestFullScreen ? doc.mozRequestFullScreen() : doc.webkitRequestFullscreen ? doc.webkitRequestFullscreen() : doc.msRequestFullscreen && doc.msRequestFullscreen();
							}
						});
						var sss = location.href.split(location.host + '/')[1];
						if($.inArray(sss, ['team', 'setting', 'wallet', 'jifen', 'deal']) > -1) {
							$('.sidebar-menu li').removeClass('active');
							$(document).find('[href=\'/' + sss + '\']').parents('li').addClass('active');
						}
						$(document).on('click', '.kefuwechat', function() {
							var wechat = $(this).attr('data');
							layer.open({
								type: 1,
								content: '<div style=\'padding:30px;font-size:1.2em;text-align:center\'> <span style=\'display:inline-block;border:1px solid #555 ;padding:2px 4px\'>' + wechat + '</span><br><br><span style=\'font-size:.85em;color:#777\'>有您的加入更精彩!</span></div>',
								title: '注意'
							})
						})
					})();
					
				</script>
				<style>
					div.maincon {
						width: 100%;
						text-align: center;
						margin: 20px auto;
					}
					
					.contitle {
						text-align: left;
						background: rgba(255, 255, 255, .8);
						padding: 0.5em 1em;
						border-bottom: 3px solid #3c8dbc;
						font-size: 1.2em;
						margin-top: 10px;
						font-weight: bold
					}
					
					.content-wrapper {
						background: url('/assets/wns/img/newbg.jpg') 50% 50% repeat #000;
						background-size: contain;
					}
					
					table.table td,
					table.table th {
						text-align: center;
					}
					
					.table tr:nth-child(odd) {
						background: rgba(0, 0, 0, .03);
					}
					
					.table tr:hover {
						background: rgba(255, 255, 255, .4);
					}
					
					.skin-blue .main-header .navbar {}
					
					.skin-blue .main-header .logo {}
					
					@media (max-width: 767px) {
						.skin-blue .main-header .navbar {
							background-size: cover;
						}
					}
				</style>
<style>
	.content-wrapper{
		background: #001926 !important;
	}
	#MEIQIA-BTN-HOLDER{
		right: 17px;
		bottom: 90px;
	}
	#looyu_dom_0{
		/*display:none;*/
		background-size:89px 139px !important;
	}
	#doyoo_panel{
		top:340px !important;
	}

</style>
<!-- header end -->
<div class="content-wrapper tab-content tab-addtabs" style="padding-top: 0px;">
	<div style="text-align: center;width: 90%;background: #002639; margin: 10px auto; line-height: 48px;   font-size: 18px; color: white;">帮助中心</div>
	<div>
		<ul class="help_box">
			<li class="help_box_bar">
				<img src="/img/qin/aid_1.png" style="width: 20px;margin-right: 10px; " />系统公告
				<img class="img1" state='1' src="/img/qin/xiangxia2.png" style="width: 1em; float:right; margin-top: 20px;" />
				<ul class="help_box_bar_box">
					<?php if(is_array($arr["system_notice"])): $i = 0; $__LIST__ = $arr["system_notice"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="bar_box">
							<a style="display: block; width: 100%; color: #3b8cff;" href="<?php echo U('help_detail');?>?id=<?php echo ($vo["if_id"]); ?>">
								<?php echo ($vo["if_theme"]); ?><img src="/img/qin/down-trangle-copy.png" style="width: 1em; float:right" />
							</a>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
					<!--<li class="bar_box">-->
					<!--<a style="display: block; width: 100%; color: #3b8cff;" href="Index/help_detail/">-->
					<!--下级1<img src="/img/qin/down-trangle-copy.png" style="width: 1em; float:right" />-->
					<!--</a>-->
					<!--</li>-->
					<!--<li class="bar_box">-->
					<!--<a style="display: block; width: 100%; color: #3b8cff;" href="Index/help_detail/">-->
					<!--下级1<img src="/img/qin/down-trangle-copy.png" style="width: 1em; float:right;" />-->
					<!--</a>-->
					<!--</li>-->
				</ul>
			</li>
			<li class="help_box_bar">
				<img src="/img/qin/aid_2.png" style="width: 20px;margin-right: 10px;"  />操作帮助
				<img class="img1" state='1' src="/img/qin/xiangxia2.png" style="width: 1em; float:right; margin-top: 20px;" />
				<ul class="help_box_bar_box">
					<?php if(is_array($arr["operation"])): $i = 0; $__LIST__ = $arr["operation"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><li class="bar_box">
							<a style="display: block; width: 100%; color: #3b8cff;" href="<?php echo U('help_detail');?>?id=<?php echo ($vo1["if_id"]); ?>">
								<?php echo ($vo1["if_theme"]); ?><img src="/img/qin/down-trangle-copy.png" style="width: 1em; float:right" />
							</a>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</li>
			<a onclick="doyoo.util.openChat('g=10083611');return false;">
				<li class="help_box_bar">
					<img src="/img/qin/aid_3.png" style="width: 20px;margin-right: 10px;"  />联系客服
					<img class="img1" state='1' src="/img/qin/xiangxia2.png" style="width: 1em; float:right; margin-top: 20px;" />
					<ul class="help_box_bar_box">
						<!--<?php if(is_array($arr["education"])): $i = 0; $__LIST__ = $arr["education"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>-->
						<!--<li class="bar_box">-->
						<!--<a style="display: block; width: 100%; color: #3b8cff;" href="<?php echo U('help_detail');?>?id=<?php echo ($vo2["if_id"]); ?>">-->
						<!--<?php echo ($vo2["if_theme"]); ?><img src="/img/qin/down-trangle-copy.png" style="width: 1em; float:right" />-->
						<!--</a>-->
						<!--</li>-->
						<!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
					</ul>
				</li>
			</a>
		</ul>
	</div>
</div>
<div class="footer_box">
	<a href="/Index/home/">
		<img src="/img/qin/deal_u.png" />
		<p>交易</p>
	</a>
	<a href="/Info/jhm/">
		<img src="/img/qin/token_u.png" />
		<p>通证</p>
	</a>
	<a href="/Index/help/">
		<img src="/img/qin/aid_s.png" />
		<p style="color: #3B8CFF">帮助</p>
	</a>
	<a href="/Info/grsz/">
		<img src="/img/qin/mine_u.png" />
		<p>我的</p>
	</a>
</div>
<!-- footer start -->
  </div>
 </body>
</html>
<!-- footer end -->
<script>
    $(function() {
        document.title = "帮助中心";
        //$('.help_box_bar').toggle();
        $('.help_box_bar').click(function() {
            //$(this).find('.help_box_bar_box').toggle();
            if($(this).find('.img1').attr('state') == "1") {
                $(this).find('.img1').attr('state', '2');
                $(this).find('.img1').attr('src', '/img/qin/xiasanjiao.png');
                $(this).find('.help_box_bar_box').show();
            } else {
                $(this).find('.img1').attr('state', '1');
                $(this).find('.img1').attr('src', '/img/qin/xiangxia2.png');
                $(this).find('.help_box_bar_box').hide();
            }
        })
        $('.bar_box').click(function() {
            $(this).parent().parent().find('.img1').attr('src', '/img/qinngxia2.png');
            $(this).parent('.help_box_bar_box').css('display', 'none');
        })
        $('#kefu').on('click',function(){
            $('#doyoo_panel').click();
            $('#looyu_dom_1').click();
        })
    })
</script>
<script type="text/javascript" charset="utf-8" src="http://lead.soperson.com/20004057/10107234.js"></script>