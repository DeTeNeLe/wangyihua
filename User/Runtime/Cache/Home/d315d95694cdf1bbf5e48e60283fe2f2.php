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
<!-- header end -->
<style>
	.orderlist .l_item .bj.status_pp_p_0:after {
		content: '等待您的打款'
	}

	.orderlist .l_item .bj.status_pp_p_1:after {
		content: '等待对方的确认'
	}

	.orderlist .l_item .bj.status_pp_p_2:after {
		content: '已完成'
	}

	.orderlist .l_item.status_pp_p_0:after {
		background-position: 0 -50px;
	}

	.orderlist .l_item.status_pp_p_1:after {
		background-position: 0 -50px;
	}

	.orderlist .l_item.status_pp_p_2:after {
		background-position: 0 0;
	}
	/*.orderlist .l_item.status_pp_p_0 > div:last-of-type:after{content:'请于12点之前完成';display:block;color:#c00;bottom:2px;right:5px;position:absolute;}*/

	.orderlist .l_item .bj.status_pp_g_0:after {
		content: '等待对方打款'
	}

	.orderlist .l_item .bj.status_pp_g_1:after {
		content: '等待您的确认'
	}

	.orderlist .l_item .bj.status_pp_g_2:after {
		content: '已完成'
	}

	.orderlist .l_item.status_pp_g_0:after {
		background-position: 0 -50px;
	}

	.orderlist .l_item.status_pp_g_1:after {
		background-position: 0 -50px;
	}

	.orderlist .l_item.status_pp_g_2:after {
		background-position: 0 0;
	}
	/*.orderlist .l_item.status_pp_g_0 > div:last-of-type:after{content:'请于12点之前完成';display:block;color:#c00;bottom:2px;right:5px;position:absolute;}*/

	.viewcon {
		background: rgba(255, 255, 255, .7);
		padding: 15px 10px 10px;
		font-size: .8em;
		border-bottom-left-radius: 8px;
		border-bottom-right-radius: 8px;
		margin: -20px 5px 5px;
		line-height: 1.2em;
		display: none;
	}

	.viewcon .card {
		padding: 10px 0;
		margin: 5px 0;
		background: rgba(255, 255, 255, .8);
		border-radius: 8px;
		box-shadow: 0 2px 2px rgba(0, 0, 0, .5);
		font-family: monospace;
		font-size: 1.2em
	}

	.viewcon .card div {
		padding: 0 10px;
		margin: 3px 0;
	}

	.viewcon .card div:nth-child(2) {
		background: #18bc9c;
		color: #fff;
		padding: 5px 10px;
		font-size: 1.2em
	}

	.viewcon .card div:nth-child(2) span:first-child {
		color: #fff;
	}

	.viewcon .left {
		font-weight: bold;
		color: #666
	}

	.viewcon a.btn {
		margin: 5px 5px 0 0
	}

	.status_p_0:after {
		content: '排队中';
	}

	.status_p_3:after {
		content: '部分匹配';
	}

	.status_p_2:after {
		content: '已完成';
	}

	.status_p_4:after {
		content: '部分完成';
	}

	.status_p_5:after {
		content: '已全部匹配';
	}

	.orderlist .l_item.status_p_0:after {
		background-position: 0 -50px;
	}

	.orderlist .l_item.status_p_3:after {
		background-position: 0 -50px;
	}

	.orderlist .l_item.status_p_2:after {
		background-position: 0 0;
	}

	.orderlist .l_item.status_p_4:after {
		background-position: 0 -50px;
	}

	.orderlist .l_item.status_p_5:after {
		background-position: 0 -50px;
	}

	.status_g_0:after {
		content: '排队中';
	}

	.status_g_3:after {
		content: '部分匹配';
	}

	.status_g_2:after {
		content: '已完成';
	}

	.status_g_4:after {
		content: '部分完成';
	}

	.status_g_5:after {
		content: '已全部匹配';
	}

	.orderlist .l_item.status_g_0:after {
		background-position: 0 -50px;
	}

	.orderlist .l_item.status_g_3:after {
		background-position: 0 -50px;
	}

	.orderlist .l_item.status_g_2:after {
		background-position: 0 0;
	}

	.orderlist .l_item.status_g_4:after {
		background-position: 0 -50px;
	}

	.orderlist .l_item.status_g_5:after {
		background-position: 0 -50px;
	}

	.outstatus0:after {
		content: '排队中';
	}

	.outstatus1:after {
		content: '待匹配';
	}

	.outstatus2:after {
		content: '已匹配';
	}

	.outstatus3:after {
		content: '匹配成功';
	}

	.outstatus4:after {
		content: '待确认';
	}

	.outstatus5:after {
		content: '部分完成';
	}

	.outstatus6:after {
		content: '已完成';
	}

	.outstatus7:after {
		content: '部分取消';
	}

	.outstatus8:after {
		content: '取消';
	}

	.btn-success {
		background-color: #3B8CFF;
		border-color: #3B8CFF;
	}
	.order_bar h5{
		border-bottom: none;
	}
</style>
<div class="content-wrapper tab-content tab-addtabs" style=" padding-top: 0;">
	<span class="btn btn-primary" style="display: inline-block;font-size: 1.4em;margin:20px;" onclick="goback()">
	   	<i class="fa fa-chevron-circle-left"></i>返回
	    <script>
			function goback(){
                window.history.back();
            }
            (function(){
                $(document).on('click','#tglj',function(){
                    var o = $(this).prev('input');
                    o.select();
                    layer.tips('已复制至剪贴板',o);
                })
            })();
		</script>
	</span>
	<div role="tabpanel" class="tab-pane active" style="overflow: auto">
		<!--订单列表-->
		<div class="maincon">
			<div class="orderlist">

			</div>
			<div class="maincon">
				<div class="orderlist out">
					<?php if($pcount == 0): ?><div style="color:#fff">当前没有开仓订单</div><?php endif; ?>
					<?php if(is_array($plist)): $i = 0; $__LIST__ = $plist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?><div class="order_bar">
								<h5><?php echo ($p["orderid"]); ?><span><?php echo ($p["date"]); ?></span></h5>
								<!--<p>持仓总量: <?php echo (get_tgbz_totaljb($p["mainid"])); ?> </p>-->
								<!--<p>开仓积分: <?php echo (get_kc_jb($p["mainid"])); ?>-->
								<!--</p>-->
								<div class=""> <button class="btn btn-qd" style="background:#0B61A4;color:white;font-size:15px;" data="<?php echo ($p["mainid"]); ?>">抢单</button></div>
								<!---<div>等待平仓积分撮合交易</div>--->
							</div><?php endforeach; endif; else: echo "" ;endif; ?>

				</div>
			</div>
			<!--<a href="?showall=<?php echo ($showall); ?> " class="btn btn-success showall ">
				<?php if($showall == 1): ?>显示
					<?php else: ?>隐藏<?php endif; ?>所有已完成订单</a>-->
		</div>
		<script>
            var $_GET = (function() {
                var u = window.document.location.href.toString().split("? ");
                if(typeof(u[1]) == "string ") {
                    u = u[1].split("& ");
                    var g = {};
                    for(var i in u) {
                        var j = u[i].split("=");
                        g[j[0]] = j[1];
                    }
                    return g;
                } else {
                    return {};
                }
            })();

            (function() {
                $(document).on('click', '.l_item', function() {
                    var o = $(this);
                    if(o.hasClass('open')) {
                        $(this).removeClass('open').addClass('closed');
                        $(this).next('.viewcon').slideToggle(200);
                    } else if(o.hasClass('closed')) {
                        $(this).removeClass('closed').addClass('open');
                        $(this).next('.viewcon').slideToggle(200);
                    } else {
                        var orderid = o.attr('data');
                        var act = o.attr('act');
                        o.addClass('open');
                        $.ajax({
                            type: 'post',
                            url: '/Home/Index/home_post',
                            data: 'act=' + act + '&orderid=' + orderid,
                            timeout: 7000,
                            success: function(data) {
                                o.after(data);
                                o.next('.viewcon').slideDown(200);
                            },
                            complete: function(XMLHttprequest) {
                                if(XMLHttprequest.StatusText == 'timeout') layer.msg('网络连接超时，请更换网络环境或稍后再试！')
                            }

                        });
                    }
                })
                var curpage1 = parseInt($(document).find('.page-number1.active').text());
                var curpage2 = parseInt($(document).find('.page-number2.active').text());
                var totalpage1 = $(document).find('.page-next1').prev('.page-number1').text();
                var totalpage2 = $(document).find('.page-next2').prev('.page-number2').text();

                function refreshpage($p1, $p2) {
                    if(!$p1) {
                        $p1 = curpage1;
                    } else {
                        curpage1 = $p1;
                    }
                    if(!$p2) {
                        $p2 = curpage2;
                    } else {
                        curpage2 = $p2;
                    }
                    var limit1 = $(document).find('.page-size1').text();
                    var limit2 = $(document).find('.page-size2').text();
                    var str = '';
                    if(limit1 > 0) {
                        str += '&limit1=' + limit1;
                    }
                    if(limit2 > 0) {
                        str += '&limit2=' + limit2;
                    }
                    str += '&page1=' + $p1;
                    str += '&page2=' + $p2;
                    if($_GET['showall']) str += '&showall=1';
                    $url = '/?o' + str;
                    location.href = $url;
                }
                $(document).on('click', '.page-list1 a', function() {
                    var p1 = $(this).text();
                    $(document).find('.page-size1').text(p1);
                    refreshpage();
                })
                $(document).on('click', '.page-list2 a', function() {
                    var p2 = $(this).text();
                    $(document).find('.page-size2').text(p2);
                    refreshpage();
                })
                $(document).on('click', '.page-number1:not(.active)', function() {
                    var p1 = parseInt($(this).text());
                    refreshpage(p1, curpage2);
                })
                $(document).on('click', '.page-number2:not(.active)', function() {
                    var p2 = parseInt($(this).text());
                    refreshpage(curpage1, p2);
                })
                $(document).on('keydown', '.page-number1.active', function(event) {
                    var p1 = parseInt($(this).text());
                    if(event.keyCode == 13) {
                        refreshpage(p1, curpage2);
                    }
                })
                $(document).on('keydown', '.page-number2.active', function(event) {
                    var p2 = parseInt($(this).text());
                    if(event.keyCode == 13) {
                        refreshpage(curpage1, p2);
                    }
                })
                $(document).on('click', '.page-pre1', function() {
                    if(curpage1 == 1) {
                        refreshpage(totalpage1, curpage2);
                    } else {
                        refreshpage(curpage1 - 1, curpage2);
                    }
                })
                $(document).on('click', '.page-next1', function() {
                    if(curpage1 == totalpage1) {
                        refreshpage(1, curpage2);
                    } else {
                        refreshpage(curpage1 + 1, curpage2);
                    }
                })
                $(document).on('click', '.page-pre2', function() {
                    if(curpage2 == 1) {
                        refreshpage(curpage1, totalpage2);
                    } else {
                        refreshpage(curpage1, curpage2 - 1);
                    }
                })
                $(document).on('click', '.page-next2', function() {
                    if(curpage2 == totalpage2) {
                        refreshpage(curpage1, 1);
                    } else {
                        refreshpage(curpage1, curpage2 + 1);
                    }
                })

            })();
		</script>
	</div>
</div>
<iframe id=" exec_target " name="exec_target " style="display: none "></iframe>
<script>
    var orderid;
    var upbtn = document.createElement("button");
    var t = document.createTextNode("选择图片 ");
    upbtn.appendChild(t);
    (function() {
        document.title = '首页';
        $(document).on('click', '.btn-buyin', function() {
            $.ajax({
                type: 'post',
                url: '/Home/Index/home_post',
                data: 'act=buyincon',
                timeout: 7000,
                success: function(rlt) {
                    layer.open({
                        title: '买入',
                        content: rlt,
                        btn: ['提交', '取消'],
                        yes: function(index, layero) {
                            var bnum = parseInt($('#buyin_num').val() * 1);
                            var tigongshangxian = parseInt($('#tigongshangxian').text());
                            var tigongxiaxian = parseInt($('#tigongxiaxian').text());
                            var buyin_num = $(document).find('#buyin_num');
                            if(bnum > tigongshangxian) {
                                layer.tips('买入金额不得超过当前上限', buyin_num);
                                return;
                            }
                            if(bnum < tigongxiaxian) {
                                layer.tips('买入金额不得少于当前下限', buyin_num);
                                return;
                            }
                            if(bnum % {
                                    $jj01
                                } > 0) {
                                layer.tips('买入金额必须为<?php echo ($jj01); ?>的整数倍', buyin_num);
                                return;
                            }
                            var pdb = Math.ceil(bnum / 1000);
                            var lock = false;
                            layer.confirm('您申请的' + bnum + '元订单', {
                                    title: '确认是否提交买入申请？'
                                },
                                function() {
                                    if(!lock) {
                                        lock = true;
                                        $.ajax({
                                            type: 'post',
                                            url: '/Home/Index/tgbzcl',
                                            data: 'zffs1=1&zffs2=1&zffs3=1&amount=' + bnum,
                                            timeout: 7000,
                                            success: function(data) {
                                                if(data.sf == 1) {
                                                    layer.closeAll();
                                                    layer.msg('提交成功！');
                                                    setTimeout(function() {
                                                        location.reload();
                                                    }, 5000)
                                                } else if(data.sf == 2) {
                                                    setTimeout(function() {
                                                        location.href = data.nr;
                                                    }, 1000);
                                                } else {
                                                    layer.msg(data.nr);
                                                }
                                            },
                                            complete: function(XMLHttprequest) {
                                                if(XMLHttprequest.statusText == 'timeout') {
                                                    layer.msg('连接超时，请更换网络环境或稍后再试');
                                                }
                                            }
                                        });
                                    } else {
                                        layer.msg('请勿多次提交');
                                    }
                                },
                                function() {}
                            );
                        },
                        btn2: function(index, layero) {
                            layer.close(index);
                        }
                    })
                },
                complete: function(XMLHttprequest) {
                    if(XMLHttprequest.statusText == 'timeout') {
                        layer.msg('连接超时,请更换网络环境或稍后再试!');
                    }
                }
            });
        })
        $(document).on('click', '.btn-sellout', function() {
            $.ajax({
                type: 'post',
                url: '/Home/Index/home_post',
                data: 'act=selloutcon',
                timeout: 7000,
                success: function(rlt) {
                    layer.open({
                        title: '卖出',
                        content: rlt,
                        btn: ['提交', '取消'],
                        yes: function(index, layero) {
                            var sell_num = $(document).find('#sell_num');
                            var selltype = $('#selltype').val() == 1 ? '/Home/Index/jsbzcl_jj' : '/Home/Index/jsbzcl_bx';
                            var num = $('#sell_num').val();
                            var card = $('#bankcard').val();
                            if(num <= 10) {
                                layer.tips('金额有误', sell_num);
                                return;
                            }
                            var lock = false;
                            layer.confirm('您申请订单成交后将获得' + num + '元', {
                                    title: '确认是否提交卖出申请？'
                                },
                                function() {
                                    if(!lock) {
                                        lock = true;
                                        $.ajax({
                                            type: 'post',
                                            url: selltype,
                                            data: 'zffs1=1&zffs2=1&zffs3=1&get_amount=' + num + '&type=' + selltype + '&card=' + card,
                                            timeout: 7000,
                                            success: function(data) {
                                                if(data.sf == 1) {
                                                    layer.closeAll();
                                                    layer.msg(data.nr);
                                                    setTimeout(function() {
                                                        location.reload();
                                                    }, 1000)
                                                } else {
                                                    layer.msg(data.nr);
                                                }
                                            },
                                            complete: function(XMLHttprequest) {
                                                if(XMLHttprequest.statusText == 'timeout') {
                                                    layer.msg('连接超时，请更换网络环境或稍后再试');
                                                }
                                            }
                                        });
                                    } else {
                                        layer.msg('请勿多次提交');
                                    }
                                },
                                function() {
                                    console.log('已取消')
                                }
                            );
                        },
                        btn2: function(index, layero) {
                            layer.close(index);
                        }
                    })
                },
                complete: function(XMLHttprequest) {
                    if(XMLHttprequest.statusText == 'timeout') {
                        layer.msg('连接超时,请更换网络环境或稍后再试!');
                    }
                }
            });
        })
        //买入
        $(document).on('input', '#buyin_num', function() {
            var o = $(this);
            var bnum = o.val();
            var num = Math.floor(bnum / 1000);
            $(document).find('.winnum').text(num);
            var tigongshangxian = parseInt($('#tigongshangxian').text());
            if(bnum > tigongshangxian) {
                layer.tips('买入金额不得超过当前上限', o);
            }
        })
		//抢单操作
		$(document).on('click','.btn-qd',function(){
			var cur_btn = $(this);
			var cur_mainid = cur_btn.attr('data');
			window.location.href = "<?php echo U('rush_orders');?>?mainid="+cur_mainid;
		})

        $(document).on('change', '#selltype', function() {
            if($(this).val() == 1) {
                $('#sell_num').attr('placeholder', '最多可提现金额' + $('.jiangli_cash').val() + '元');
                $('#js_tips').html('<br />最低取出金额：<?php echo ($tj_start); ?></br>最高取出金额：<?php echo ($tj_e); ?></br><?php echo ($tj_beishu); ?>的整数倍</br>每轮提现不得超过总金额的<?php echo ($tj_baifenbi); ?>%');
            } else {
                $('#sell_num').attr('placeholder', '最多可提现金额' + $('.benxi_cash').val() + '元');
                $('#js_tips').html('<br />最低取出金额：<?php echo ($txthemin); ?></br>最高取出金额：<?php echo ($txthemax); ?></br><?php echo ($jl_beishu); ?>的整数倍');
            }

        })
        $(document).on('input', '#sell_num', function() {
            var o = $(this);
            if($('#selltype').val() == 1) {
                var max = $('.jiangli_cash').val();
            } else {
                var max = $('.benxi_cash').val();
            }
            max = parseInt(max);
            if(parseInt(o.val()) > max) {
                layer.msg('输入金额不得大于账户内余额');
                o.val(max);
            }
        })

        //预约订单
        $(document).on('click', '.btn-book', function() {
            layer.open({
                title: '预约订单',
                type: 2,
                content: '/Home/Index/home_post?act=yuyue',
                area: ['400px', '75%'],
                btn: ['关闭']
            })
        })

        //留言
        $(document).on('click', '.sendmessage', function() {
            var o = $(this);
            id = o.attr('data');
            layer.open({
                title: '留言',
                type: 2,
                content: '/Home/Index/home_ddxx_ly?id=' + id,
                area: ['400px', '55%'],
                btn: ['关闭']
            })
        })

        //预览图片
        $(document).on('click', '.viewimg', function() {
            var o = $(this);
            pic = o.attr('data');
            layer.open({
                title: '预览图片',
                type: 2,
                content: '/Home/Index/home_post?act=viewimg&pic=' + pic,
                area: ['400px', '65%'],
                btn: ['关闭']
            })
        })

        //确认已付款
        $(document).on('click', '.confirmpay', function() {
            var o = $(this);
            id = o.attr('data');
            layer.open({
                title: '确认已付款',
                type: 2,
                content: '/Home/Index/home_ddxx_confirmpay?id=' + id,
                area: ['400px', '65%'],
                btn: ['关闭']
            })
        })

        //ETH打款检测
        $(document).on('click', '.payfromethcheck', function() {
            var o = $(this);
            id = o.attr('data');
            layer.open({
                title: 'ETH打款检测',
                type: 2,
                content: '/Home/Index/home_post?act=payfromethcheck&ppid=' + id,
                area: ['500px', '65%'],
                btn: ['关闭']
            })
        })

        //确认收款
        $(document).on('click', '.confirmget', function() {
            var o = $(this);
            id = o.attr('data');
            layer.open({
                title: '确认收款',
                type: 2,
                content: '/Home/Index/home_ddxx_confirmget?id=' + id,
                area: ['400px', '65%'],
                btn: ['关闭']
            })
        })

        //未打款投诉
        $(document).on('click', '.wdktousu', function() {
            var o = $(this);
            gid = o.attr('data');
            layer.open({
                title: '未打款投诉',
                type: 2,
                content: '/Home/Index/home_ddxx_g_wdk?id=' + gid,
                area: ['400px', '65%'],
                btn: ['关闭']
            })
        })

        //上传凭证
        $(document).on('click', '.shangchuanpingzheng', function() {
            var o = $(this);
            orderid = o.attr('data');
            var rec = o.prev('div').prev('div').find('span:last-child').text();
            layer.open({
                title: '上传给会员' + rec + '打款的凭证',
                type: 1,
                content: "<div style='padding:20px;'><input type='file' name='file' id='file_upload' /></div>",
                btn: ['上传', '取消'],
                yes: function(index, layero) {
                    var fd = new FormData();
                    fd.append('file', $("#file_upload")[0].files[0]);
                    fd.append('orderid', orderid);
                    layer.load();
                    $.ajax({
                        type: "post",
                        url: "https://img.lxjy2017.com/?act=uploadimg",
                        async: false,
                        processData: false,
                        contentType: false,
                        data: fd,
                        dataType: 'text',
                        success: function(data) {
                            var res = eval("(" + data + ")");
                            if(res.status == 0) {
                                layer.closeAll();
                                layer.msg(res.content);
                            } else if(res.status == 1) {
                                var imgpath = res.content;
                                $.ajax({
                                    type: 'post',
                                    url: '?',
                                    data: 'act=uploadimgdone&id=' + orderid + '&img=' + imgpath,
                                    success: function(data2) {
                                        var res2 = eval("(" + data2 + ")");
                                        if(res2.status == 0) {
                                            layer.closeAll();
                                            layer.msg(res2.content);
                                        } else {
                                            layer.msg(res2.content);
                                            location.reload();
                                        }
                                    }
                                })

                            }
                        }
                    });
                    layer.close(index);
                },
            });
        })

        //确认收款
        $(document).on('click', '.querenshoukuan', function() {
            var id = $(this).attr('data');
            layer.open({
                title: '请确认',
                offset: '20%',
                area: ['300px', '200px'],
                type: 1,
                content: '<div style=\'padding:30px\'>请确认是否收到对方打款</div>',
                btn: ['确认', '取消'],
                yes: function(index, layero) {
                    layer.load();
                    $.ajax({
                        type: 'post',
                        url: '?',
                        data: 'act=querenshoukuan&id=' + id,
                        timeout: 7000,
                        success: function(data) {
                            if(data = 1) {
                                layer.msg('操作成功,请刷新！');
                                layer.close(index);
                                location.reload();
                            } else {
                                layer.msg(data);
                            }
                        },
                        complete: function(XMLHttprequest) {
                            layer.closeAll('loading');
                            if(XMLHttprequest.statusText == 'timeout') {
                                layer.msg('连接超时，请稍后再试！');
                            }
                        }
                    });
                }
            });
        })

        //加快进场
        $(document).on('click', '.jiakuai', function() {
            var id = $(this).attr('data');
            var jiakuai = $(this).is(':checked') ? 1 : 0;
            var tip = jiakuai ? '加快进场后排单3天后即可打款，确定加快吗？<br><span style=\'color:red\'>如果您当前为防撞会员，开启加速将使防撞功能失效，48小时内取消加速可恢复防撞，不取消的将永远失去防撞功能。</span>' : '确定取消加快该订单吗？';
            layer.confirm(tip, {
                title: '请确认'
            }, function() {
                $.ajax({
                    type: 'post',
                    url: '?',
                    data: 'act=jiakuaijinchang&id=' + id + '&do=' + jiakuai,
                    success: function(data) {
                        var res = eval("(" + data + ")");
                        layer.msg(res.content);
                        if(res.status == 1) {
                            location.reload();
                        }
                    }
                });
            }, function() {
                location.reload();
            })
        })

        //防撞
        $(document).on('click', '.fangzhuang', function() {
            var fangzhuang = $(this).is(':checked') ? 1 : 0;
            var tip = fangzhuang ? '开启防撞后收一笔打一笔，进场速度可能较慢，是否确定开启防撞？' : '取消防撞可能导致撞单，同时允许加快进场，是否取消防撞功能？';
            layer.confirm(tip, {
                title: '请确认'
            }, function() {
                $.ajax({
                    type: 'post',
                    url: '/Home/Index/home_post',
                    data: 'act=fangzhuang&do=' + fangzhuang,
                    success: function(data) {
                        layer.msg(data.nr);
                        setTimeout(function() {
                            location.reload()
                        }, 400);
                    }
                })
            }, function() {
                location.reload();
            })
        })

        $(document).on('click', '.clickbank', function() {
            var o = $(this);
            var t = o.text();
            t = t.replace(/ /g, '');
            o.text(t);
        })

        //tips
        $(document).on('click', '.hptip.alert', function() {
            var o = $(this);
            var p = o.next('.window').html();
            layer.open({
                type: 1,
                content: p,
                title: '警告',
                btn: ['关闭']
            })
        })
        var initime = Math.round(new Date().getTime() / 1000);
        setInterval(function() {
            $('.daojishi').each(function() {
                var o = $(this);
                var ts = parseInt(o.attr('data'));
                var newnow = Math.round(new Date().getTime() / 1000);
                var past = newnow - initime;
                var n = ts - past;
                var newtime = Math.floor(n / 3600) + '小时' + Math.floor((n % 3600) / 60) + '分' + (n % 60) + '秒';
                o.html(newtime);
            })
        }, 1000);
    })();
</script>
<style>
	div#contentbtn .btn {
		padding: 10px 0;
		font-size: 20px;
		color: white;
		/* font-weight: bold; */
		width: 31%;
		display: inline-block;
	}

	div#contentbtn .btn:first-child {
		float: left;
	}

	div#contentbtn .btn:last-child {
		float: right;
	}

	div#contentbtn .btn:hover {
		background: #499ac8
	}

	div#contentbtn .btn:focus {
		background: #2471a6
	}

	div#contentbtn .btn span {
		display: block;
		font-size: .5em;
		font-weight: normal;
		opacity: 0.7;
	}

	.skin-green {
		background-color: #3c8dbc;
	}

	a.normal {
		display: block;
		font-size: 1.3em;
		padding: 10px 0;
		background: rgba(20, 100, 80, .6);
		border: rgba(255, 255, 255, .8);
		text-align: center;
		color: #fff;
		text-decoration: none
	}

	.buyincon,
	.sellcon {
		background: #fff;
		width: 100%;
		padding: 10px;
		margin-top: -5px;
		text-align: left;
	}

	.wine {
		display: inline-block;
		width: 40px;
		height: 40px;
		vertical-align: bottom;
		margin: 3px 5px 0;
		border: 1px solid rgba(0, 0, 0, .3);
		background: url('/assets/wns/img/wine.jpg')center center no-repeat;
		background-size: contain;
	}

	.btn.btn-buyin {
		background: #e0f7bb;
		background: -moz-linear-gradient(top, #e0f7bb 1%, #b7f751 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(1%, #e0f7bb), color-stop(100%, #b7f751));
		background: -webkit-linear-gradient(top, #e0f7bb 1%, #b7f751 100%);
		background: -o-linear-gradient(top, #e0f7bb 1%, #b7f751 100%);
		background: -ms-linear-gradient(top, #e0f7bb 1%, #b7f751 100%);
		background: linear-gradient(to bottom, #e0f7bb 1%, #b7f751 100%);
		color: #333 !important;
		text-shadow: 0 1px 1px #fff
	}

	.btn.btn-sellout {
		background: #f9f1db;
		background: -moz-linear-gradient(top, #f9f1db 0%, #f9ca52 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #f9f1db), color-stop(100%, #f9ca52));
		background: -webkit-linear-gradient(top, #f9f1db 0%, #f9ca52 100%);
		background: -o-linear-gradient(top, #f9f1db 0%, #f9ca52 100%);
		background: -ms-linear-gradient(top, #f9f1db 0%, #f9ca52 100%);
		background: linear-gradient(to bottom, #f9f1db 0%, #f9ca52 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f9f1db', endColorstr='#f9ca52', GradientType=0);
		color: #333 !important;
		text-shadow: 0 1px 1px #fff
	}

	.btn.btn-buyin:hover,
	.btn.btn-sellout:hover {
		color: #fff !important;
		text-shadow: none
	}

	#upbtncon {
		padding: 20px;
		min-height: 50px;
	}

	.btn.showall {
		display: block;
		margin: 20px auto;
	}

	.hptip {
		display: block;
		width: 100%;
		background: #f00;
		padding: 10px 0;
		text-align: center;
		color: #fff;
		font-size: 1.2em;
		cursor: pointer;
	}

	.hptip+.window {
		display: none;
	}

	.windowin {
		padding: 5px 10px;
	}
</style>

<!-- footer start -->
  </div>
 </body>
</html>
<!-- footer end -->