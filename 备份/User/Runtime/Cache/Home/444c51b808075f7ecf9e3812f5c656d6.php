<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" src="/js/jquery-2.1.1.min.js"></script>
<script src="/cssmmm/jquery.min.js"></script>
<link rel="stylesheet" href="/assets/styles/style.css">
<link rel="stylesheet" href="/assets/vendor/fontawesome/css/font-awesome.css">
<link rel="stylesheet" href="/assets/vendor/metisMenu/dist/metisMenu.css">
<link rel="stylesheet" href="/assets/vendor/animate.css/animate.css">
    <link rel="stylesheet" href="/assets/vendor/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/vendor/sweetalert/lib/sweet-alert.css">
    <link rel="stylesheet" href="/assets/vendor/toastr/build/toastr.min.css">
   

<style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style></head>
<body>

<!-- Main Wrapper -->

  <div class="color-line"></div>
            <div class="modal-header">
                <h5 class="modal-title" id="title24">超过<?php echo ($jjdktime); ?>小时未打款投诉</h5>
                <small class="font-bold"></small>
            </div> <form class="" method="post" id="pfrom" enctype="multipart/form-data" action="/Home/Index/home_ddxx_g_wdk_cl/">
			<div class="modal-body" style="height:300px;">
                匹配成功后,想尽快的收到款,请立即与"存入金额"用户取得联系(电话或微信)!<br>
              如果从匹配成功起<?php echo ($jjdktime); ?>小时内会员未打款,可以在此投诉,管理员审核通过后将重新匹配!
				<br>

                <input type="radio" class="comfir" value="1" name="comfir">确认投诉<br>
                <input type="radio" class="comfir" value="2" name="comfir">取消投诉<br>
                <!--<input type="radio" class="comfir" value="3" name="comfir"/>延长付款24小时<br>-->

                <input type="hidden" value="<?php echo ($id); ?>" id="id" name="id"><br>
<br>

<input name="提交" type="submit" id="提交" value="提交" class="btn-primary">
            </div>
			</form>
</body></html>