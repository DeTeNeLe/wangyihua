<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>无标题文档</title>
    <link href="/sncss/css/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="/zTree_v3/css/zTreeStyle/zTreeStyle.css" type="text/css"/>
    <script type=text/javascript src="/zTree_v3/js/jquery.min.js"></script>
    <script type="text/javascript" src="/zTree_v3/js/jquery.ztree.core-3.5.js"></script>

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

        var zNodes = [
            {id: 1, pId: 0, name: "父節點1 - 展開", open: true},
            {id: 11, pId: 1, name: "父節點11 - 摺疊"},
            {id: 111, pId: 11, name: "葉子節點111"},
            {id: 112, pId: 11, name: "葉子節點112"},
            {id: 113, pId: 11, name: "葉子節點113"},
            {id: 114, pId: 11, name: "葉子節點114"},
            {id: 12, pId: 1, name: "父節點12 - 摺疊"},
            {id: 121, pId: 12, name: "葉子節點121"},
            {id: 122, pId: 12, name: "葉子節點122"},
            {id: 123, pId: 12, name: "葉子節點123"},
            {id: 124, pId: 12, name: "葉子節點124"},
            {id: 13, pId: 1, name: "父節點13 - 沒有子節點", isParent: true},
            {id: 2, pId: 0, name: "父節點2 - 摺疊"},
            {id: 21, pId: 2, name: "父節點21 - 展開", open: true},
            {id: 211, pId: 21, name: "葉子節點211"},
            {id: 212, pId: 21, name: "葉子節點212"},
            {id: 213, pId: 21, name: "葉子節點213"},
            {id: 214, pId: 21, name: "葉子節點214"},
            {id: 22, pId: 2, name: "父節點22 - 摺疊"},
            {id: 221, pId: 22, name: "葉子節點221"},
            {id: 222, pId: 22, name: "葉子節點222"},
            {id: 223, pId: 22, name: "葉子節點223"},
            {id: 224, pId: 22, name: "葉子節點224"},
            {id: 23, pId: 2, name: "父節點23 - 摺疊"},
            {id: 231, pId: 23, name: "葉子節點231"},
            {id: 232, pId: 23, name: "葉子節點232"},
            {id: 233, pId: 23, name: "葉子節點233"},
            {id: 234, pId: 23, name: "葉子節點234"},
            {id: 3, pId: 0, name: "父節點3 - 沒有子節點", isParent: true}
        ];


        $(document).ready(function () {
            var $user1 = $('#user1').val();
            $.ajax({
                type: "post",
                dataType: "json",
                global: false,
                url: "/Yshclbssb.php/Home/Common/getTree",
                data: {
                    user1: $user1
                },
                success: function (data, textStatus) {
                    if (data.status == 0) {
                        zNodes1 = data.data;
                        $.fn.zTree.init($("#treeDemo"), setting, zNodes1);
                    } else {
                        alert("您還沒有");
                    }

                    return;
                }

            });

            //$.fn.zTree.init($("#treeDemo"), setting, zNodes);
        });


        $(function () {


            $('#btn').click(function () {

                var $user = $('#user').val();
                $.ajax({
                    type: "post",
                    dataType: "json",
                    global: false,
                    <?php iniInfo(); ?>
                    url: "/Yshclbssb.php/Home/Common/getTreeso",
                    data: {
                        user: $user
                    },
                    success: function (data, textStatus) {
                        if (data.status == 0) {
                            //alert(data.nr);

                            zNodes1 = data.data;
                            $.fn.zTree.init($("#treeDemo"), setting, zNodes1);
                        } else {
                            alert(data.data);
                        }

                        return;
                    }

                });


            })


        })


    </script>
</head>
<style>
    input {
        border: 1px #cccccc solid;
        height: 25px;
        line-height: 25px;
    }
</style>
<body>

<div class="place">
    <span>位置：</span>
    <ul class="placeul">
        <li><a href="#">首页</a></li>
        <li><a href="#">奖金设定</a></li>
    </ul>
</div>

<div class="rightinfo">
    <table class="tablelist">
        <form action="<?php echo U('Home/Index/jjset');?>" method="post">
            <thead>
            <tr >
                <th width="15%">仅需互助</th>
                <th width="85%"><input name="jj01s" value="<?php echo ($jj01s); ?>" type="" />元 — <input name="jj01m" value="<?php echo ($jj01m); ?>" type="" />元 必须<input name="jj01" value="<?php echo ($jj01); ?>" type="" />元的整倍数</th>
            </tr> 
            <tr >
                <th width="15%">新用户注册奖励 暂时保留</th>
                <th width="85%"><input name="reg_jiangli" value="<?php echo ($reg_jiangli); ?>" type="number" />元</th>
            </tr>
			<tr>
                <th width="15%">每天限制激活</th>
                <th width="85%"><input name="jihuo_limit_day" value="<?php echo ($jihuo_limit_day); ?>" type="number" step="1" />人</th>
            </tr>
            <tr >
                <th width="15%">排队分红天数</th>
                <th width="85%"><input name="pdfhdays" value="<?php echo ($pdfhdays); ?>" type="number" />天</th>
            </tr>
             <tr >
                <th width="15%">打款后分红天数</th>
                <th width="85%"><input name="jjfhdays" value="<?php echo ($jjfhdays); ?>" type="number" />天</th>
            </tr>
            <tr >
                <th width="15%">提现冻结天数</th>
                <th width="85%"><input name="jjdjdays" value="<?php echo ($jjdjdays); ?>" type="number" step="0.001" />天</th>
            </tr>
            <tr >
                <th width="15%">激活用户几天未提供帮助封号</th>
                <th width="85%"><input name="jihuo_feng_days" value="<?php echo ($jihuo_feng_days); ?>" type="number" step="0.01" />天;(0表示关闭)</th>
            </tr>
			<tr >
                <th width="15%">注册用户几天不激活封号</th>
                <th width="85%"><input name="new_jihuo_feng_days" value="<?php echo ($new_jihuo_feng_days); ?>" type="number" step="0.01" />天;(0表示关闭)</th>
            </tr>
           <!--  <tr>
                <th width="15%">匹配天数 </th>
                <th width="85%"><input name="jjppdays" value="<?php echo ($jjppdays); ?>" type="number" />天</th>
            </tr> -->
            <tr>
                <th width="15%">排单码封顶</th>
                <th width="85%"><input name="paidan_ma_max" value="<?php echo ($paidan_ma_max); ?>" type="number" />个</th>
            </tr>
			<tr >
                <th width="15%">玩家级别,排单金额设置开启</th>
                <th width="85%">
                    <select name="open_zhitui_add_money">
                        <option <?php if($open_zhitui_add_money == 1): ?>selected<?php endif; ?> value="1">开启</option>
                        <option <?php if($open_zhitui_add_money == 0): ?>selected<?php endif; ?> value="0">关闭</option>
                    </select>
                </th>
            </tr>
            <tr >
                <th width="15%">排单金额设置</th>
                <th width="85%"><input name="my_member_min" value="<?php echo ($my_member_min); ?>" type="number" />元 — <input name="my_member_max" value="<?php echo ($my_member_max); ?>" type="number" />元  ( 玩家级别：静态玩家)</th>
            </tr> 
            <tr >
                <th width="15%"></th>
                <th width="85%"><input name="my_member_min1" value="<?php echo ($my_member_min1); ?>" type="number" />元 — <input name="my_member_max1" value="<?php echo ($my_member_max1); ?>" type="number" />元  ( 玩家级别：直推一人)</th>
            </tr>
            <tr >
                <th width="15%"></th>
                <th width="85%"><input name="my_member_min2" value="<?php echo ($my_member_min2); ?>" type="number" />元 — <input name="my_member_max2" value="<?php echo ($my_member_max2); ?>" type="number" />元  ( 玩家级别：直推二人)</th>
            </tr>
            <tr >
                <th width="15%"></th>
                <th width="85%"><input name="my_member_min3" value="<?php echo ($my_member_min3); ?>" type="number" />元 — <input name="my_member_max3" value="<?php echo ($my_member_max3); ?>" type="number" />元  ( 玩家级别：直推三人)</th>
            </tr>
			<tr >
                <th width="15%"></th>
                <th width="85%">
				每完成<input name="tg_add_circle" value="<?php echo ($tg_add_circle); ?>" type="number" />轮,额度提升 
				<input name="tg_add_circle_money" value="<?php echo ($tg_add_circle_money); ?>" type="number" />元(1000的倍数)</th>
            </tr> 
			<tr >
                <th width="15%"></th>
                <th width="85%">
				每排单<input name="paidanb_every" value="<?php echo ($paidanb_every); ?>" type="number" />元,扣除
				<input name="paidanb_count" value="<?php echo ($paidanb_count); ?>" type="number" />个拍单币</th>
            </tr> 
			<tr >
                <th width="15%"></th>
                <th width="85%">
				封顶<input name="max_tg_add_circle" value="<?php echo ($max_tg_add_circle); ?>" type="number" />轮</th>
            </tr> 
            <tr>
                <th width="15%">提供帮助最多允许等待匹配单数</th>
                <th width="85%">
                    <input name="oneByone" value="<?php echo ($oneByone); ?>" type="number" />单(0表示无限制，1表示只能等待1单)
                </th>
            </tr>
            <tr>
                <th width="15%">提供帮助配对后最多允许等待交易数</th>
                <th width="85%">
                    <input name="peidui" value="<?php echo ($peidui); ?>" type="number" />单;(0表示无限制，1表示只能等待1单)
                </th>
            </tr>
			<tr>
                <th width="15%">是否开启时间限制</th>
                <th width="85%">
                    <select name="time_limit">
                        <option <?php if($time_limit == 1): ?>selected<?php endif; ?> value="1">开启</option>
                        <option <?php if($time_limit == 0): ?>selected<?php endif; ?> value="0">关闭</option>
                    </select>
                </th>
            </tr>
			<tr>
                <th width="15%">每日提供帮助排单时间从</th>
                <th width="85%"><input name="paidan_time_start" value="<?php echo ($paidan_time_start); ?>" type="number"  size="40"/>时 - 
				<input name="paidan_time_end" value="<?php echo ($paidan_time_end); ?>" type="number"  size="40"/>时</th>
            </tr>
			<tr>
                <th width="15%">普通进场时间</th>
                <th width="85%">
				    <input  name="n_in_start"  placeholder="请输入结束时间" value="<?php echo ($n_in_start); ?>"> 到
					<input  name="n_in_end"  placeholder="请输入开始时间" value="<?php echo ($n_in_end); ?>">(根据系统时间,格式：2018-05-10 00:00:00)
				</th>
            </tr>
			<tr>
                <th width="15%">高级进场时间</th>
                <th width="85%">
				     <input  name="s_in_start"  placeholder="请输入结束时间" value="<?php echo ($s_in_start); ?>"> 到
					 <input  name="s_in_end"  placeholder="请输入开始时间" value="<?php echo ($s_in_end); ?>">(根据系统时间,格式：2018-05-10 00:00:00)
				</th>
            </tr>
            </tr>
			<tr>
                <th width="15%">普通进场按钮</th>
                <th width="85%">
				    <input  name="n_in_startbtn"  placeholder="请输入结束时间" value="<?php echo ($n_in_startbtn); ?>"> 到
					<input  name="n_in_endbtn"  placeholder="请输入开始时间" value="<?php echo ($n_in_endbtn); ?>">(根据排单时间,格式：2018-05-10 00:00:00)
				</th>
            </tr>
			<tr>
                <th width="15%">高级进场按钮</th>
                <th width="85%">
				     <input  name="s_in_startbtn"  placeholder="请输入结束时间" value="<?php echo ($s_in_startbtn); ?>"> 到
					 <input  name="s_in_endbtn"  placeholder="请输入开始时间" value="<?php echo ($s_in_endbtn); ?>">(根据排单时间,格式：2018-05-10 00:00:00)
				</th>
            </tr>
            <tr>
                <th width="15%">用户每天提供帮助排单数量</th>
                <th width="85%"><input  name="paidan_num"  placeholder="用户每天排单数量" value="<?php echo ($paidan_num); ?>">单</th>
            </tr>
            <tr>
                <th width="15%">用户距离上一单提供帮助的间隔天数</th>
                <th width="85%"><input  name="tgbz_time"  placeholder="提供帮助间隔天数" value="<?php echo ($tgbz_time); ?>">天</th>
            </tr>
            <tr>
                <th width="15%">每天用户提供帮助排单总额度</th>
                <th width="85%"><input  name="paidan_jbs"  placeholder="用户每天排单总额度" value="<?php echo ($paidan_jbs); ?>">元</th>
            </tr>
            <tr >
                <th width="15%">配对模式</th>
                <th width="85%">
					<select name="jjppms">
						<option <?php if($jjppms == 1): ?>selected<?php endif; ?> value="1">自动匹配</option>
						<option <?php if($jjppms == 0): ?>selected<?php endif; ?> value="0">手动匹配</option>
					</select>
				</th>
            </tr>

            <tr>
                <th width="15%">用户提供帮助月投资额度封顶</th>
                <th width="85%"><input name="month_max" value="<?php echo ($month_max); ?>" type="number" />元</th>
            </tr>
            <tr >
                <th width="15%">A4 级别团体奖设置</th>
               <th width="85%"><input name="tuanti_jiang_a4" value="<?php echo ($tuanti_jiang_a4); ?>" style="font-size: 15px" />单位“ % ”  请直接填入数字</th>
            </tr>
            <tr >
                <th width="15%">A5 级别团体奖设置</th>
               <th width="85%"><input name="tuanti_jiang_a5" value="<?php echo ($tuanti_jiang_a5); ?>" style="font-size: 15px" />单位“ % ”  请直接填入数字</th>
            </tr>
            <tr >
                <th width="15%">推荐奖</th>
                <th width="85%"><input name="jjtuijianratenew" value="<?php echo ($jjtuijianratenew); ?>" style="font-size: 15px;width:400px;"/>单位“ % ”</th>
            </tr>
			<tr >
                <th width="15%">奖金流入比例</th>
                <th width="85%">
				   奖金的
				   <input name="jj_to_jifen" value="<?php echo ($jj_to_jifen); ?>" type="number" />%进入<?php echo ($jifen_wallet_name); ?>,
				   <input name="jj_to_ldj" value="<?php echo ($jj_to_ldj); ?>" type="number" />%进入<?php echo ($ldj_wallet_name); ?>,
				   <input name="jj_to_shopjifen" value="<?php echo ($jj_to_shopjifen); ?>" type="number" />%进入<?php echo ($shopjifen_wallet_name); ?>
				</th>
            </tr>
           <!--  <tr>
                <th width="15%">升级经理条件</th>
                <th width="85%">下线提供帮助的金额达到<input name="xiaxian_jb" value="<?php echo ($xiaxian_jb); ?>" type="" />元的会员人数有<input name="xiaxian_num" value="<?php echo ($xiaxian_num); ?>" type="" />位 ，且自己提供帮助的金额最少为<input name="my_jb" value="<?php echo ($my_jb); ?>" type="" />元</th>
            </tr> -->
            <!-- <tr>
                <th width="15%">经理代数奖</th>
                <th width="85%"><input name="jjjldsrate" value="<?php echo ($jjjldsrate); ?>" style="width:300px;" type="" />% 用,号分隔</th>
            </tr> -->
              <tr>
                <th width="15%">是否必须经理才可以注册下线</th>
                <th width="85%">
                    <select name="iscan_reg">
                        <option <?php if($iscan_reg == 1): ?>selected<?php endif; ?> value="1">必须</option>
                        <option <?php if($iscan_reg == 0): ?>selected<?php endif; ?> value="0">不必</option>
                    </select>
                </th>
            </tr>
            <tr>
                <th width="15%">开启会员级别奖励</th>
                <th width="85%">
					<select name="jjaccountflag">
						<option <?php if($jjaccountflag == 1): ?>selected<?php endif; ?> value="1">开启</option>
						<option <?php if($jjaccountflag == 0): ?>selected<?php endif; ?> value="0">关闭</option>
					</select>
				</th>
            </tr>
            <tr >
                <th width="15%">会员级别</th>
                <th width="85%"><input name="jjaccountlevel" value="<?php echo ($jjaccountlevel); ?>" style="font-size: 15px"/></th>
            </tr>
            <tr >
                <th width="15%">会员升级下线人数</th>
                <th width="85%"><input name="jjaccountnum" value="<?php echo ($jjaccountnum); ?>" style="font-size: 15px"/></th>
            </tr>
            <tr >
                <th width="15%">会员升级直推人数</th>
                <th width="85%"><input name="zhitui_num_level" value="<?php echo ($zhitui_num_level); ?>" style="font-size: 15px" />用“,”号分隔</th>
            </tr>
			<tr >
                <th width="15%">会员升级投资额度</th>
                <th width="85%"><input name="tz_money_level" value="<?php echo ($tz_money_level); ?>" style="font-size: 15px" />用“,”号分隔</th>
            </tr>
            <tr >
                <th width="15%">会员级别奖金比率</th>
                <th width="85%"><input name="jjaccountrate" value="<?php echo ($jjaccountrate); ?>" style="font-size: 15px" />单位“ % ” </th>
            </tr>
            <tr >
                <th width="15%">会员级别投资门槛</th>
                <th width="85%"><input name="jibei_menkan" value="<?php echo ($jibei_menkan); ?>"  style="font-size: 15px"/></th>
            </tr>
			
            <tr >
                <th width="15%">打款时间</th>
                <th width="85%"><input name="jjdktime" value="<?php echo ($jjdktime); ?>" type="number" style="font-size: 15px"/>小时</th>
            </tr>

			<tr >
                <th width="15%">确认时间</th>
                <th width="85%"><input name="jjqrtime" value="<?php echo ($jjqrtime); ?>" type="number" style="font-size: 15px"/>小时</th>
            </tr>
			
            <tr >
                <th width="15%">超时未打款冻结提示语</th>
                <th width="85%"><input name="jjhydjmsg" value="<?php echo ($jjhydjmsg); ?>" type="text" /></th>
            </tr>
            <tr >
                <th width="15%">超时未打款扣除上级奖金钱包</th>
                <th width="85%"><input name="jjhydjkcsjmoeney" value="<?php echo ($jjhydjkcsjmoeney); ?>" type="number" />元</th>
            </tr>
			<tr >
                <th width="15%">商城钱包名称</th>
                <th width="85%"><input name="shopjifen_wallet_name" value="<?php echo ($shopjifen_wallet_name); ?>"/></th>
            </tr>
			<tr >
                <th width="15%">排单码名称</th>
                <th width="85%"><input name="pdm_name" value="<?php echo ($pdm_name); ?>"/></th>
            </tr>
			<tr >
                <th width="15%">激活码名称</th>
                <th width="85%"><input name="jhm_name" value="<?php echo ($jhm_name); ?>"/></th>
            </tr>
			<tr >
                <th width="15%">本息钱包名称</th>
                <th width="85%"><input name="bx_wallet_name" value="<?php echo ($bx_wallet_name); ?>"/></th>
            </tr>
			<tr >
                <th width="15%">领导奖钱包名称</th>
                <th width="85%">
				<input name="ldj_wallet_name" value="<?php echo ($ldj_wallet_name); ?>"/>
				
            </tr>
			<tr >
                <th width="15%">诚信奖钱包名称</th>
                <th width="85%">
				   <input name="jifen_wallet_name" value="<?php echo ($jifen_wallet_name); ?>"/>
				   ,可使用
				   <input name="cxj_dhjhm_num" value="<?php echo ($cxj_dhjhm_num); ?>"/><?php echo ($jifen_wallet_name); ?>兑换1个<?php echo ($jhm_name); ?>,0为禁用
				   ,是否允许转出到本息
				    <select name="cxj_fun_zcbx">
                        <option <?php if($cxj_fun_zcbx == 1): ?>selected<?php endif; ?> value="1">允许</option>
                        <option <?php if($cxj_fun_zcbx == 0): ?>selected<?php endif; ?> value="0">不允许</option>
                    </select>,
					超时不确认扣除<input name="chaoshi_kcjf" value="<?php echo ($chaoshi_kcjf); ?>" type="number" style="font-size: 15px"/>分
				</th>
            </tr>
			<tr >
                <th width="15%">是否开启自适应</th>
                <th width="85%">
                    <select name="open_auto_m">
                        <option <?php if($open_auto_m == 1): ?>selected<?php endif; ?> value="1">开启</option>
                        <option <?php if($open_auto_m == 0): ?>selected<?php endif; ?> value="0">关闭</option>
                    </select>
                </th>
            </tr>
			<tr >
                <th width="15%">注册短信通知开关</th>
                <th width="85%">
                    <select name="sms_open_reg">
                        <option <?php if($sms_open_reg == 1): ?>selected<?php endif; ?> value="1">开启</option>
                        <option <?php if($sms_open_reg == 0): ?>selected<?php endif; ?> value="0">关闭</option>
                    </select>
                </th>
            </tr>
			 <tr>
                <th width="15%">进场短信通知开启</th>
                <th width="85%">
                    <select name="sms_open_in">
                        <option <?php if($sms_open_in == 1): ?>selected<?php endif; ?> value="1">开启</option>
                        <option <?php if($sms_open_in == 0): ?>selected<?php endif; ?> value="0">关闭</option>
                    </select>
                </th>
            </tr>
			 <tr>
                <th width="15%">打款短信通知开关</th>
                <th width="85%">
                    <select name="sms_open_pay">
                        <option <?php if($sms_open_pay == 1): ?>selected<?php endif; ?> value="1">开启</option>
                        <option <?php if($sms_open_pay == 0): ?>selected<?php endif; ?> value="0">关闭</option>
                    </select>
                </th>
            </tr>
			 <tr>
                <th width="15%">资料修改短信通知开关</th>
                <th width="85%">
                    <select name="sms_open_mod">
                        <option <?php if($sms_open_mod == 1): ?>selected<?php endif; ?> value="1">开启</option>
                        <option <?php if($sms_open_mod == 0): ?>selected<?php endif; ?> value="0">关闭</option>
                    </select>
                </th>
            </tr>
			 <tr>
                <th width="15%">匹配短信通知开关</th>
                <th width="85%">
                    <select name="sms_open_pp">
                        <option <?php if($sms_open_pp == 1): ?>selected<?php endif; ?> value="1">开启</option>
                        <option <?php if($sms_open_pp == 0): ?>selected<?php endif; ?> value="0">关闭</option>
                    </select>
                </th>
            </tr>
			<tr>
                <th width="15%">安全验证短信开关</th>
                <th width="85%">
                    <select name="sms_open_safecheck">
                        <option <?php if($sms_open_safecheck == 1): ?>selected<?php endif; ?> value="1">开启</option>
                        <option <?php if($sms_open_safecheck == 0): ?>selected<?php endif; ?> value="0">关闭</option>
                    </select>
                </th>
            </tr>
			<tr>
                <th width="15%">未激活是否可登录</th>
                <th width="85%">
                    <select name="no_check_loginallowed">
                        <option <?php if($no_check_loginallowed == 1): ?>selected<?php endif; ?> value="1">可登录</option>
                        <option <?php if($no_check_loginallowed == 0): ?>selected<?php endif; ?> value="0">不可登录</option>
                    </select>
                </th>
            </tr>
			<tr>
                <th width="15%">每日可抢激活码数量</th>
                <th width="85%"><input name="getjhm_num" value="<?php echo ($getjhm_num); ?>" type="number" />个,设置为0不开启</th>
            </tr>
			<tr>
                <th width="15%">每日抢激活码时间从</th>
                <th width="85%"><input name="getjhm_start" value="<?php echo ($getjhm_start); ?>" type="number"  size="40"/>时 - 
				<input name="getjhm_end" value="<?php echo ($getjhm_end); ?>" type="number"  size="40"/>时</th>
            </tr>
			</tr>
			<tr >
                <th width="30%"><font color="red">(*new)</font>预付款功能.自动拆分挂单的：</th>
                <th width="70%"><input name="prepaypercent" value="<?php echo ($prepaypercent); ?>" type="number" />%作为预付款,设为0不启用</th>
            </tr>
			<tr>
                <th width="15%"><font color="red">(*new)</font>强制复投</th>
                <th width="85%">
                    <select name="force_tgbz">
                        <option <?php if($force_tgbz == 1): ?>selected<?php endif; ?> value="1">开启</option>
                        <option <?php if($force_tgbz == 0): ?>selected<?php endif; ?> value="0">关闭</option>
                    </select>
                </th>
            </tr>
            <tr>
                <th></th>
                <th><input name="submit" value="提交" type="submit"/></th>
            </tr>
            </thead>
        </form>
    </table>

</div>
</body>
</html>