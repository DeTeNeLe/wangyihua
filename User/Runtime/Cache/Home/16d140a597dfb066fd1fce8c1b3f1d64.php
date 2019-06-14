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
<link rel="stylesheet" href="/zTree_v3/css/zTreeStyle/zTreeStyle.css" type="text/css" />
<script type="text/javascript" src="/zTree_v3/js/jquery.ztree.core-3.5.js"></script>
<style>
	#teamlist {
		background: #002333;
		padding: 10px 5px;
		color: white;
	}
	
	.nllist {
		margin: 50px auto;
		text-align: left;
		display: block;
		padding: 20px;
		zoom: .6;
	}
	
	.treeblock {
		margin-left: 40px;
		border-left: 1px dotted #999
	}
	
	.nllist>.treeblock {
		border-left: none
	}
	
	.treeitem {
		margin: 10px 0 10px -10px;
	}
	
	.treeitem>span {
		padding: 0 3px;
		vertical-align: middle;
	}
	
	.treeitem>span:nth-child(n+3) {
		display: inline-block;
		margin: 0 15px 0 0;
	}
	
	.treeitem>span:nth-child(2) {
		display: inline-block;
		border: 1px solid rgba(0, 0, 0, .3);
		border-radius: 50%;
		width: 26px;
		height: 24px;
		line-height: 24px;
		text-align: center;
		margin-left: 10px;
	}
	
	.treeitem>span:first-child {
		font: normal normal normal 14px/1 FontAwesome;
		font-size: inherit;
		text-rendering: auto;
		-webkit-font-smoothing: antialiased;
	}
	
	.treeitem>span.plus:before {
		content: "\f0fe";
		cursor: pointer;
		margin-right: 4px;
		color: #2196f3
	}
	
	.treeitem>span.minus:before {
		content: "\f146";
		cursor: pointer;
		margin-right: 4px;
		color: #aaa
	}
	
	.treeitem>span:nth-child(3) {
		width: 350px;
		text-overflow: ellipsis;
		overflow: hidden;
		white-space: nowrap;
		line-height: 1em;
		height: 1em
	}
	
	.treeitem>span:nth-child(4) {
		width: 220px;
	}
	
	.treeitem>span:nth-child(5) {
		width: 200px;
	}
	
	.lx_search {
		height: 2.2em
	}
	
	@media screen and (max-width: 768px) {
		.treeblock {
			margin-left: 50px;
		}
		.nllist {
			font-size: .5em;
			zoom: .4;
			margin-top: 85px;
		}
		.treeitem {
			display: inline-block;
			border-bottom: 1px solid rgba(0, 0, 0, .05)
		}
		.treeitem>span:nth-child(2) {
			margin-left: 10px;
			width: 44px;
			height: 40px;
			line-height: 40px;
		}
		.treeitem>span:nth-child(3) {
			width: 280px;
		}
		.treeitem>span:nth-child(4) {
			width: 180px;
		}
		.treeitem>span:nth-child(5) {
			width: 190px;
		}
		.lx_search {
			height: 2em
		}
	}
	
	.noactive {
		color: #fb6f00
	}
	
	.table>thead>tr>th,
	.table>tbody>tr>th,
	.table>tfoot>tr>th,
	.table>thead>tr>td,
	.table>tbody>tr>td,
	.table>tfoot>tr>td {
		padding: 8px 0px;
		width: 27px;
	}
	
	.btn {}
	
	.ztree li a {
		color: white;
	}
	
	.ztree * {
		font-size: 37px;
	}
	
	.ztree li {
		line-height: inherit;
	}
	
	.ztree li a.curSelectedNode {
		height: auto;
	}
	
	.ztree li span {
		line-height: inherit;
	}
	
	.ztree li a {
		height: auto;
	}
	
	.ztree li span.button {
		content: '';
	}
	
	.ztree li span.button.root_close {
		background-image: url(/img/qin/addicon.png);
		background-size: 100%;
		width: 50px;
		height: 50px;
		background-position: 0px 0px;
	}
	
	.ztree li span.button.root_open {
		background-image: url(/img/qin/minusicon.png);
		background-size: 100%;
		width: 50px;
		height: 50px;
		background-position: 0px 0px;
	}
	
	.ztree li span.button.center_close {
		background-image: url(/img/qin/addicon.png);
		background-size: 100%;
		width: 30px;
		height: 30px;
		background-position: 0px 0px;
	}
	
	.ztree li span.button.center_open {
		background-image: url(/img/qin/minusicon.png);
		background-size: 100%;
		width: 30px;
		height: 30px;
		background-position: 0px 0px;
	}
</style>
<div class="content-wrapper tab-content tab-addtabs" style="padding-top: 0px;">
	<div role="tabpanel" class="tab-pane active" style="overflow: auto">
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
							document.execCommand("copy",false,null);
							layer.tips('已复制至剪贴板',o);
						})
					})();
				</script>
     		</span>
		<div class="maincon">
			<div id="teamlist" style="padding: 0px;">
				<div class="btn-group" style="width: 100%;float: left;margin:5px;">
					<a class="btn btn-default active ftab">直推</a>
					<a class="btn btn-default ftab">团队</a>
				</div>
				<div class="tab">
					<div>
						<?php if(is_array($fristList)): foreach($fristList as $key=>$v): ?><div class="order_bar">
								<h5 style="overflow: hidden; display: block;">
									<span style="float: left;">昵称：<?php echo ($v["ue_theme"]); ?></span>
									<span style="float: right;">状态:
										<?php if($v["ue_check"] == 0 ): ?><font color="red">未激活</font>
											<?php else: ?>已激活<?php endif; ?>
									</span>
								</h5>
								<p style="display: block; text-align: left">账户：<?php echo ($v["ue_account"]); ?></p>
								<p style="display: block; text-align: left">手机号：<?php echo ($v["ue_phone"]); ?></p>
								<!--<p style="display: block; text-align: left;">推荐人：<?php echo ($v["ue_accname"]); ?></p>-->
								<!--<p style="display: block; text-align: left">注册时间：<?php echo (date("Y-m-d",strtotime($v["ue_regtime"]))); ?></p>-->
								<p style="display: block; text-align: left">注册时间：<?php echo ($v["ue_regtime"]); ?></p>
								<p style="display: block; text-align: left">操作：
									<!--自己直推的会员才有资格激活-->
									<?php if($v["ue_accname"] == $userData['ue_account']): if($v["ue_check"] == 0 ): ?><span style=" border: none;" class="btn btn-success btn-jihuo" data="<?php echo ($v["ue_account"]); ?>">激活</span>
										<?php else: ?>
										<span style="background: #C0C0C0; border: none;" class="btn btn-success btn-jihuo" data="<?php echo ($v["ue_account"]); ?>">激活</span><?php endif; endif; ?>
								</p>
							</div><?php endforeach; endif; ?>
					</div>
					<div style="margin-top: 20px;background: #000;color: #fff;padding: 5px;display:none">
						最近七天团队新增成员
					</div>
					<table class="table" style="display:none">
						<tbody>
							<tr>
								<th>层级</th>
								<th>昵称</th>
								<th>手机号</th>
								<th>注册时间</th>
								<th>激活时间</th>
								<th>排单</th>
							</tr>
							<tr>
								<td colspan="7">查询结果为空</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab" style="display: none;">
					<div style="width: 100%; overflow: hidden; margin-top: 5%;">
						<form action="" method="get">
							<input name="user1" id="user1" type="hidden" value="<?php echo ($user); ?>" />
							<input type="text" class=" lx_search col-md-7 col-xs-7" style="height: 35px;color: black; margin-left: 5%;" 
								name="user" id="user" value="<?php echo ($user); ?>" placeholder="搜索会员" />
							<input name="" type="button" id="btn" value="搜索" class="btn btn-info btn-sm">
						</form>
					</div>
					<div style="text-align: center;padding-left: 5%; line-height: 50px;">
						我的团队：<?php echo ($all_user_num); ?> 人
					</div>
					<div>
						<?php if(is_array($list)): foreach($list as $key=>$v): ?><div class="order_bar">
								<h5 style="overflow: hidden; display: block;">
									<span style="float: left;">姓名：<?php echo ($v["ue_theme"]); ?></span>
								</h5>
								<p style="display: block; text-align: left">账户：<?php echo ($v["ue_account"]); ?></p>
								<p style="display: block; text-align: left">手机号：<?php echo ($v["ue_phone"]); ?></p>
								<p style="display: block; text-align: left">上级会员：<?php echo ($v["ue_accname"]); ?></p>
								<p style="display: block; text-align: left">上级会员的手机：<?php echo ($v["parent_phone"]); ?></p>
								<!--<p style="display: block; text-align: left">注册时间：<?php echo (date("Y-m-d",strtotime($v["ue_regtime"]))); ?></p>-->
								<p style="display: block; text-align: left">注册时间：<?php echo ($v["ue_regtime"]); ?></p>
							</div><?php endforeach; endif; ?>
					</div>
					
					<!--<div class="nllist" style="padding-top: 0px; margin-top: 0px;">
						<div class="row">
							<div class="col-md-4 col-xs-4 pull-left">

							</div>
							<div class="col-md-7 col-xs-7 pull-right row">
								<form action="" method="get">
									<input name="user1" id="user1" type="hidden" value="<?php echo ($user); ?>" />
									<input type="text" class=" lx_search col-md-7 col-xs-7" style="height: 35px;" name="user" id="user" value="<?php echo ($user); ?>" placeholder="搜索会员" />
									<input name="" type="button" id="btn" value="搜索" class="btn btn-info btn-sm">
								</form>
							</div>
						</div>
						<div class="treeblock" style="margin-left: 0px;">
							<div class="treeitem" style="width: 100%;">
								<div style="display:none">
									<span class="plus"></span>
									<span data-id="70040">1</span>
									<span>高飞(13815594900)</span>
									<span>2018-04-10</span>
									<span>7天以前排单</span>
									<span>正常</span>
								</div>
								<ul id="treeDemo" class="ztree" style="font-size:20px;color:white; width: 100%;"></ul>
							</div>
						</div>
					</div>-->
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	(function() {
		var curpage = parseInt($(document).find('.page-number.active').text());
		var totalpage = $(document).find('.page-next').prev('.page-number').text();

		function refreshpage($p) {
			if($p == '') {
				$p = curpage;
			} else {
				curpage = $p;
			}
			var limit = $(document).find('.page-size').text();
			var str = '';
			if(limit > 0) {
				str += '&pagesize=' + limit;
			}
			str += '&p=' + $p;
			str += $('#lookall').is(':checked') ? '&all' : '';
			$url = '/Home/<?php echo (CONTROLLER_NAME); ?>/<?php echo (ACTION_NAME); ?>?' + str;
			location.href = $url;
		}
		$(document).on('click', '.btn-jihuo', function() {
			if(confirm('确定激活吗？')) {
				var o = $(this);
				var uname = o.attr('data');
				$.ajax({
					type: 'post',
					url: '/Home/Index/home_post',
					data: 'act=jihuo&uname=' + uname,
					timeout: 7000,
					success: function(data) {
						if(data.sf == 1) {
							layer.msg(data.nr);
							setTimeout(function() {
								location.reload();
							}, 1000)
						} else {
							layer.msg(data.nr);
						}
					},
					compplete: function(XMLHttprequest) {
						if(XMLHttprequest.statusText == 'timeout') {
							layer.msg('连接超时,请更换网络环境或稍后再试!')
						}
					}
				})
			}
		});
		$(document).on('keypress', '.lx_search', function(e) {
			var o = $(this);
			if(e.keyCode == 13) {
				o.next('a').click();
			}
		})
		$(document).on('click', '.page-list a', function() {
			var p = $(this).text();
			$(document).find('.page-size').text(p);
			refreshpage();
		})
		$(document).on('click', '.page-number:not(.active)', function() {
			var p = parseInt($(this).text());
			refreshpage(p);
		})
		$(document).on('keydown', '.page-number.active', function(event) {
			var p = parseInt($(this).text());
			if(event.keyCode == 13) {
				refreshpage(p);
			}
		})
		$(document).on('click', '.page-pre', function() {
			if(curpage == 1) {
				refreshpage(totalpage);
			} else {
				refreshpage(curpage - 1);
			}
		})
		$(document).on('click', '.page-next', function() {
			if(curpage == totalpage) {
				refreshpage(1);
			} else {
				refreshpage(curpage + 1);
			}
		})
		$(document).on('click', '.btn-tuoguan', function() {
			var id = $(this).attr('data');
			layer.open({
				type: 2,
				content: '?act=manage&id=' + id,
				title: '您正在访问您直推会员的账户',
				offset: '0',
				area: ['100%', '100%'],
				btn: ['返回我的账户', '关闭'],
				yes: function(inedx, layero) {
					location.href = '/team?act=goback';
				},
				end: function() {
					location.href = '/';
				}
			});
		})
		$(document).on('click', '.ftab', function() {
			var o = $(this);
			o.parent('div').find('a').toggleClass('active');
			$(document).find('.tab').toggle();
		})
		$(document).on('click', '.plus', function() {
			var t = $(this);
			var ex = t.siblings('div');
			if(!ex.length) {
				var id = t.next('span').attr('data-id');
				var me = t.next('span').text();
				$.ajax({
					type: 'post',
					url: '/team?',
					data: 'act=nextlevel&juid=' + id + '&method=' + me,
					success: function(data) {
						t.addClass('minus').parent('div').append(data);
					}
				})
			} else {
				t.toggleClass('minus')
				ex.toggle();
			}
		})
		document.title = '团队';
	})();
</script>

<script type=text/javascript>
	var setting = {
		view: {
			showLine: true
		},
		data: {
			simpleData: {
				enable: true
			}
		}
	};

	var zNodes = [{
			id: 1,
			pId: 0,
			name: "父節點1 - 展開",
			open: true
		},
		{
			id: 11,
			pId: 1,
			name: "父節點11 - 摺疊"
		},
		{
			id: 234,
			pId: 23,
			name: "葉子節點234"
		},
		{
			id: 3,
			pId: 0,
			name: "父節點3 - 沒有子節點",
			isParent: true
		}
	];
	$(document).ready(function() {
		var $user = "<?php echo ($userData['ue_account']); ?>";
		$.ajax({
			type: "post",
			dataType: "json",
			global: false,
			url: "/index.php/Home/Common/getTree",
			data: {
				user: $user
			},
			success: function(data, textStatus) {
				if(data.status == 0) {
					zNodes1 = data.data;
					$.fn.zTree.init($("#treeDemo"), setting, zNodes1);
				} else {
					alert(data.data);
				}
				return;
			}
		});

		$('#btn').click(function() {
			var $user = $('#user').val();
			$.ajax({
				type: "post",
				dataType: "json",
				global: false,
				url: "/index.php/Home/Common/getTreeso",
				data: {
					user: $user
				},
				success: function(data, textStatus) {
					if(data.status == 0) {
						zNodes1 = data.data;
						$.fn.zTree.init($("#treeDemo"), setting, zNodes1);
					} else {
						alert(data.data);
					}
					return;
				}
			});
		});
	});
</script>

<!-- footer start -->
  </div>
 </body>
</html>
<!-- footer end -->