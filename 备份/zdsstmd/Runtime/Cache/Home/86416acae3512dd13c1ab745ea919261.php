<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>利息页面</title>
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
        <li><a href="#">匹配系统</a></li>
        <li><a href="#">利息配置</a></li>
    </ul>
</div>

<div class="rightinfo">
    <div class="tools"></div>
    <table class="tablelist">
        <form action="<?php echo U('Home/Index/lixi');?>" method="post">
            <thead>
            <tr >
                <th width="15%">排队利息</th>
                <th width="85%"><input name="lixi1" value="<?php echo ($lixi1); ?>" type="text"/>%</th>
            </tr>
            <tr >
                <th>配对打款后利息</th>
                <th><input name="lixi2" value="<?php echo ($lixi2); ?>" type="text"/>%</th>
            </tr>
            <tr >
                <th>提前打款多少小时</th>
                <th><input name="tiqian_time" value="<?php echo ($tiqian_time); ?>" type="number"/>小时</th>
            </tr>
            <tr >
                <th>提供帮助提前打款奖励本金的%</th>
                <th><input name="tiqian_lx" value="<?php echo ($tiqian_lx); ?>" type="number"/>%,进入<?php echo C('jifen_wallet_name');?></th>
            </tr>
            <tr >
                <th><?php echo C('jifen_wallet_name');?>冻结本金的%</th>
                <th><input name="chenxin_dj" value="<?php echo ($chenxin_dj); ?>" type="number"/>%</th>
            </tr>
             <tr >
                <th><?php echo C('ldj_wallet_name');?>冻结本金的%</th>
                <th><input name="tuijian_dj" value="<?php echo ($tuijian_dj); ?>" type="number"/>%</th>
            </tr>
            <tr >
                <th>获得帮助提前确认多少小时</th>
                <th><input name="tiqian_time_j" value="<?php echo ($tiqian_time_j); ?>" type="number"/>小时</th>
            </tr>
            <tr >
                <th>获得帮助提前确认奖励本金的%</th>
                <th><input name="tiqian_lx_j" value="<?php echo ($tiqian_lx_j); ?>" type="number"/>%</th>
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