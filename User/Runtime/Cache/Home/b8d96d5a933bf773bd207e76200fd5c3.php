<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" src="/js/jquery-2.1.1.min.js"></script>
<script src="/cssmmm/jquery.min.js"></script>
<link rel="stylesheet" href="/assets/styles/style.css">
 <link rel="stylesheet" href="/assets/vendor/fontawesome/css/font-awesome.css">
    <link rel="stylesheet" href="/assets/vendor/metisMenu/dist/metisMenu.css">
    <link rel="stylesheet" href="/assets/vendor/animate.css/animate.css">
    <link rel="stylesheet" href="/assets/vendor/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/vendor/sweetalert/lib/sweet-alert.css">
    <link rel="stylesheet" href="/assets/vendor/toastr/build/toastr.min.css">
   

    <!-- App styles -->
    <link rel="stylesheet" href="/assets/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="/assets/fonts/pe-icon-7-stroke/css/helper.css">
    <link rel="stylesheet" href="/assets/styles/style.css">
<link rel="stylesheet" href="/Public/Uploadify/uploadify.css"/>
<script type="text/javascript" src='/Public/Uploadify/jquery.uploadify.min.js'></script>
 <script type='text/javascript'>
    
        var PUBLIC = '/Public';
        var uploadUrl = '<?php echo U("Common/uploadFace");?>';
        var ROOT = '';
    </script>


<style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style></head>
<body>

<!-- Main Wrapper -->

  <div class="color-line"></div>
            <div class="modal-header">
                <h5 class="modal-title" id="title24">请选择</h5>
                <small class="font-bold"></small>
            </div> <form class="" method="post" id="pfrom" enctype="multipart/form-data" action="<?php echo U('Home/Index/home_ddxx_confirmget_cl');?>">
			<div class="modal-body" style="height:300px;">
                如果未收款到并且汇款截图是假的,请先对方取得联系,联系后还不能解决问题,在选择未收到款投诉,并且上传您的截图!<br>

                <input type="radio" class="comfir" value="1" name="comfir">确认收款<br>
				<input type="radio" class="comfir" value="2" name="comfir">未收到款投诉<br>
              <p>如果未收到款,请上传您的截图!</p>
                    <p><link rel="stylesheet" type="text/css" href="/js/xz/css.css" />
					 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
				  <div class="container">
        
            <div style="font-size:9pt;">
                <a class="btn btn-primary btn-sm" id="btn">上传图片</a>　　　　<input type="submit" id="btn_approve_donation" class="btn btn-primary btn-sm" value="提交">
                <br>
<br>

                <ul id="ul_pics" class="ul_pics clearfix"></ul>
            </div>
        </div> <script type="text/javascript" src="/js/xz/jquery.js"></script>
        <script type="text/javascript" src="/plupload/plupload.full.min.js"></script>
        <script type="text/javascript" src="/js/xz/sucaihuo.js"></script>
		
                    </p>

                <input type="hidden" value="<?php echo ($id); ?>" id="id" name="id"><br>
<br>
			</div>
			</form>
<!--gethelp modal end-->






</body></html>