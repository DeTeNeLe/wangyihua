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
<div class="content-wrapper tab-content tab-addtabs" style="padding-top:0px;">
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
	<div role="tabpanel" class="tab-pane active" style="overflow: auto">
		<div class="maincon">
			<div class="well bs-component">
				<legend style="text-align: left;font-weight: bold; border: none; color: white">当前<?php echo C('jifen_wallet_name');?>余额：<?php echo ($userData['jifen']); ?></legend>
				<form class="form-horizontal" <?php if(C('cxj_dhjhm_num') == 0): ?>style="display:none"<?php endif; ?>>
					<fieldset>
						<legend style="color: white; border: none;"><?php echo C('jifen_wallet_name');?>兑换通证积分 </legend>
						<span class="help-block" style="color: white; border: none;">兑换无需手续费,每<?php echo C('cxj_dhjhm_num');?>个<?php echo C('jifen_wallet_name');?>可兑换1个通证积分</span>
						<div class="form-group">
							<div class="">
								<input style="width: 80%; margin: 0px auto;" type="text" class="form-control" id="num_zz_user" value="<?php echo ($_SESSION['uname']); ?>" placeholder="兑换账号或手机号" disabled="disabled">
							</div>
							<label for="num_zz_user" class=" text-left control-label"></label>
						</div>
						<div class="form-group">
							<div class="">
								<input style="width: 80%; margin: 0px auto;" type="text" class="form-control" id="num_zz_nums" placeholder="要兑换的通证积分数量">
							</div>
							<label for="num_zz_nums" class="col-lg-4 col-xs-4 text-left control-label"></label>
						</div>
						<input type="button" style="height: 42px;width: 80%; margin: 0px auto;line-height: 28px; font-size: 18px;" class="form-control" value="提交" disabled="disabled" id="num_zz_submit_pdm">
					</fieldset>
				</form>
			</div>
			<div class="well bs-component" style="display:none">
				<form class="form-horizontal">
					<fieldset>
						<legend><?php echo C('jifen_wallet_name');?>激活会员</legend>
						<span class="help-block">你可以使用<?php echo C('cxj_dhjhm_num'); echo C('jifen_wallet_name');?>激活你的一代和二代直推会员</span>
						<div class="form-group col-lg-6 col-xs-12">
							<table class="table">
								<tbody>
									<tr>
										<th>层级</th>
										<th>昵称</th>
										<th>账号</th>
										<th>手机号</th>
										<th>操作</th>
									</tr>
									<tr>
										<td>一代</td>
										<td>李沧海</td>
										<td>97693188@qq.com</td>
										<td>15188358607</td>
										<td><span class='btn btn-success activeone' data='94028'>激活</span></td>
									</tr>
								</tbody>
							</table>
						</div>
					</fieldset>
				</form>
			</div>
			<div class="walletpanel">
				<div class="contitle active">
					<?php echo C('jifen_wallet_name');?> 消费纪录
				</div>
				<!--<table class="table">
					<tbody>
						<tr>
							<th>编号</th>
							<th>日期</th>
							<th>类型</th>
							<th>数量</th>
							<th>余额</th>
							<th>说明</th>
						</tr>
						<?php if(is_array($jifen_list)): foreach($jifen_list as $key=>$v): ?><tr>
								<td><?php echo ($v["id"]); ?></td>
								<td><?php echo ($v["date"]); ?></td>
								<td>
									<?php if($v["type"] == 'zc'): ?>转出<?php endif; ?>
									<?php if($v["type"] == 'dhjhm'): ?>兑换激活码<?php endif; ?>
									<?php if($v["type"] == 'cf'): ?>惩罚<?php endif; ?>
									<?php if($v["type"] == 'jj'): ?>奖金<?php endif; ?>
								</td>
								<td><?php echo ($v["num"]); ?></td>
								<td><?php echo ($v["yue"]); ?></td>
								<td><?php echo ($v["info"]); ?></td>
							</tr><?php endforeach; endif; ?>
					</tbody>
				</table>-->
				<div>
					<?php if(is_array($jifen_list)): foreach($jifen_list as $key=>$v): ?><div class="order_bar">
							<h5 style="overflow: hidden;">
								<span style="float: left;">编号:<?php echo ($v["id"]); ?></span>
								<span style="float: right;">类型：<?php if($v["type"] == 'zc'): ?>转出<?php endif; ?>
									<?php if($v["type"] == 'dhjhm'): ?>兑换通证积分<?php endif; ?>
									<?php if($v["type"] == 'cf'): ?>惩罚<?php endif; ?>
									<?php if($v["type"] == 'jj'): ?>奖金<?php endif; ?>
								</span>
							</h5>
							<p style="display: block; text-align: left">日期：<?php echo ($v["date"]); ?></p>
							<p style="display: block; text-align: left;">数量：<?php echo ($v["num"]); ?></p>
							<p style="display: block; text-align: left">余额：<?php echo ($v["yue"]); ?></p>
							<p style="display: block; text-align: left">说明：<?php echo ($v["info"]); ?></p>
						</div><?php endforeach; endif; ?>
				</div>
				<!--<div class="fixed-table-pagination" style="display: block;">
					<div class="pull-left pagination-detail">
						<span class="pagination-info"> 共 <?php echo ($jifen_count); ?> 条纪录</span>
						<span class="page-list2">每页显示 <span class="btn-group dropup"> <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> <span class="page-size2">10</span> <span class="caret"></span> </button>
						<ul class="dropdown-menu" role="menu">
							<li role="dropdown-menu" class="active">
								<a>10</a>
							</li>
							<li role="dropdown-menu">
								<a>25</a>
							</li>
							<li role="dropdown-menu">
								<a>50</a>
							</li>
						</ul>
						</span>条记录</span>
					</div>
					<div class="pull-right pagination">
						<div class="page">
							<?php echo ($jifen_page); ?>
						</div>
					</div>
				</div>-->
			</div>
		</div>
	</div>
</div>
<style>
	.walletpanel {
		background: rgba(255, 255, 255, .9);
		padding: 10px 5px;
	}
	
	.dddd {
		width: 96%;
		text-align: center;
		margin: 0 auto;
	}
	
	.dddd>div {
		height: 5em;
		padding: 5px;
		border: 1px solid rgba(0, 0, 0, .1)
	}
	
	.dddd>div .num {
		font-size: 1.5em;
		font-weight: bold;
		display: inline-block;
		height: 100%;
		float: left;
		line-height: 2.8em
	}
	
	.form-control {
		height: 40px;
		font-size: 18px;
	}
	
	tr.green {
		background: rgba(0, 250, 0, .2) !important;
		color: #444
	}
	
	tr.red {
		background: rgba(250, 0, 0, .2) !important;
		color: #444
	}
	
	.contitle {
		background: white;
		color: black;
		border-bottom: none;
	}
	
	.well {
		width: 95%;
		margin-left: 2.5%;
		background: #002333;
		border: none;
		color: white;
	}
	
	.walletpanel {
		width: 95%;
		margin-left: 2.5%;
		background: #002333;
		color: white;
	}
</style>
</div>
<script>
	(function() {
		document.title = "<?php echo C('jifen_wallet_name');?>";
		//积分转账
		$(document).on('blur', '#num_zz_user', function() {
			var o = $(this).val();
			if(o == '') return;
			$.ajax({
				type: 'post',
				url: '/Home/Index/home_post',
				data: 'act=get_userinfo&field=ue_theme&u_p=' + o,
				success: function(data) {
					if(data.sf == 1) {
						$('[for=\'num_zz_user\']').html('<span style=\'color:#090\'>' + data.nr + '</span>');
					} else {
						$('[for=\'num_zz_user\']').html('<span style=\'color:#a00\'>' + data.nr + '</span>');
					}
				}
			});
		}).on('input', '#num_zz_nums', function() {
			var o = parseInt($(this).val());
			if(o <= 0) {
				$('#num_zz_submit').attr('disabled', 'disabled');
				return;
			} else {
				$('[for=\'num_zz_nums\']').text('需扣除0%手续费：' + (o * parseInt(<?php echo C('cxj_dhjhm_num');?>)) + "<?php echo C('jifen_wallet_name');?>");
				$('#num_zz_submit_pdm').removeAttr('disabled');
			}
		}).on('blur', '#num_zz_nums', function() {
			var o = $(this).val();

			if(o > 0) {
				$.ajax({
					type: 'post',
					url: '/Home/Index/home_post',
					data: "act=get_userinfo&field=jifen&u_p=<?php echo ($userData['ue_account']); ?>",
					success: function(data) {
						if(data.sf == 1) {
							if(data.nr / (<?php echo C('cxj_dhjhm_num');?>) < parseInt(o)) {
								$('[for=\'num_zz_nums\']').html('<span style=\'color:#090\'>' + "您的<?php echo C('jifen_wallet_name');?>不足！" + '</span>');
								$('#num_zz_submit').removeAttr('disabled');
							}

						} else {
							$('[for=\'num_zz_nums\']').html('<span style=\'color:#a00\'>' + data.nr + '</span>');
							$('#num_zz_submit').attr('disabled', 'disabled');
						}
					}
				});
			}
		}).on('click', '#num_zz_submit', function() {
			var to_user = $('#num_zz_user').val();
			var user = $('[for=num_zz_user]').text();
			var num = $('#num_zz_nums').val();
			layer.confirm('是否向会员' + to_user + '兑换' + num + "通证积分？", {
					title: '请确认',
				},
				function(index, layero) {
					$.ajax({
						type: 'post',
						url: '/Home/Index/home_post',
						data: 'act=jifen_dhjhm&to_user=' + to_user + '&num=' + num,
						success: function(data) {
							if(data.sf == 1) {
								layer.closeAll();
								layer.msg('兑换成功');
								setTimeout(function() {
									location.reload()
								}, 2000);
							} else {
								layer.msg(data.nr);
							}
						}
					});
				}
			);
		}).on('click', '#num_zz_submit_pdm', function() {
            var to_user = $('#num_zz_user').val();
            var user = $('[for=num_zz_user]').text();
            var num = $('#num_zz_nums').val();
            layer.confirm('是否向会员' + to_user + '兑换' + num + "通证积分？", {
                    title: '请确认',
                },
                function(index, layero) {
                    $.ajax({
                        type: 'post',
                        url: '/Home/Index/home_post',
                        data: 'act=jifen_dhpdm&to_user=' + to_user + '&num=' + num,
                        success: function(data) {
                            if(data.sf == 1) {
                                layer.closeAll();
                                layer.msg('兑换成功');
                                setTimeout(function() {
                                    location.reload()
                                }, 2000);
                            } else {
                                layer.msg(data.nr);
                            }
                        }
                    });
                }
            );
        })


        $(document).on('click', '#num_mall_submit', function() {
			var num = parseInt($('#num_mall_num').val());
			console.log(num);
			if(num <= 0) {
				layer.msg('请输入正确金额！');
				return;
			}
			layer.load();
			$.ajax({
				type: 'post',
				url: '?',
				data: 'act=convertmoney&num=' + num,
				success: function(data) {
					layer.closeAll('loading');
					layer.msg(data);
				}
			})

		})

		//激活会员
		$(document).on('click', '.activeone', function() {
			var id = $(this).attr('data');
			$.ajax({
				type: 'post',
				url: '?',
				data: 'act=activeone&id=' + id,
				success: function(data) {
					var res = eval("(" + data + ")");
					layer.msg(res.content);
					if(res.status == 1) location.reload();
				}
			});
		})

		//查看积分转账记录
		$(document).on('click', '.btn_num_record', function() {
			var o = $(this);
			var start = parseInt($(this).attr('data'));
			o.text('查看更多...').attr('data', start + 10);
			$.ajax({
				type: 'post',
				url: '?',
				data: 'act=jifenrecord&start=' + start,
				timeout: 7000,
				success: function(data) {
					var res = eval("(" + data + ")");
					if(res.status == 0) {
						layer.msg(res.content);
						return;
					} else {
						o.prev('table').find('tr:first-child').before(res.content);
					}

				}
			})
		})

		//查看转积分至商城记录
		$(document).on('click', '.btn_jifentomall_record', function() {
			var o = $(this);
			var start = parseInt($(this).attr('data'));
			o.text('查看更多...').attr('data', start + 10);
			$.ajax({
				type: 'post',
				url: '?',
				data: 'act=jifentomallrec&start=' + start,
				timeout: 7000,
				success: function(data) {
					var res = eval("(" + data + ")");
					if(res.status == 0) {
						layer.msg(res.content);
						return;
					} else {
						o.prev('table').find('tr:first-child').before(res.content);
					}

				}
			})
		})
	})();
</script>
<!-- footer start -->
  </div>
 </body>
</html>
<!-- footer end -->