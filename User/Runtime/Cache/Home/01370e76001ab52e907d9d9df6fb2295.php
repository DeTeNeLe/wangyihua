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
<!--<script type="text/javascript" src='/Public/Uploadify/jquery.uploadify.min.js'></script>-->
 <script type='text/javascript'>
    
//        var PUBLIC = '/Public';
//        var uploadUrl = '<?php echo U("Common/uploadFace");?>';
//        var ROOT = '';
    </script>

 <script type="text/javascript">
 //上传插件
//$(function() {
//
//// $('#face').uploadify({
////		swf : PUBLIC + '/Uploadify/uploadify.swf',	//引入Uploadify核心Flash文件
////		uploader : uploadUrl,	//PHP处理脚本地址
////		width : 120,	//上传按钮宽度
////		height : 30,	//上传按钮高度
////		buttonImage : PUBLIC + '/Uploadify/browse-btn.png',	//上传按钮背景图地址
////		fileTypeDesc : 'Image File',	//选择文件提示文字
////		fileTypeExts : '*.jpeg; *.jpg; *.png; *.gif',	//允许选择的文件类型
////     	sizeLimit: 4194304,
////     	fileObjName:'file',
//////		formData : {'session_id' : sid},
////		//上传成功后的回调函数
////		onUploadSuccess : function (file, data, response) {
////			eval('var data = ' + data);
////			if (data.status) {
////				$('#face-img').attr('src', ROOT + '/Uploads/' + data.path);
////				$('input[name=face180]').val('/Uploads/'+data.path);
////			} else {
////				alert(data.msg);
////			}
////		}
////	});
//
// });
 </script>
<style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style></head>
<body>
<div class="color-line"></div>
	<div class="modal-header">
		<h5 class="modal-title" id="title24" style="padding: 10px 10px;">请选择</h5>
		<small class="font-bold"></small>
	</div> 
	<form class="" method="post" id="pfrom" enctype="multipart/form-data" action="<?php echo U('Home/Index/home_ddxx_confirmpay_cl');?>">
		<div class="modal-body" >
			<input type="radio" class="comfir2" value="1" name="comfir2">我完成打款<br>
			<input type="hidden" value="" id="comid2">
		</div>
		<div class="modal-body" style="height:370px;">
			<p>上传打款图片,请一定要上传真实汇款证明截图!</p>
			<p><link rel="stylesheet" type="text/css" href="/js/xz/css.css" />
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<div class="container">
				<div style="font-size:9pt;">
					<a class="btn btn-primary btn-sm" id="btn">上传图片</a>　　　　
					<input type="submit"  id="btn_approve_donation" class="btn btn-primary btn-sm" value="确认已付款"><br>
					<ul id="ul_pics" class="ul_pics clearfix" style="text-align:left;padding:0px;margin:0px;margin-top:10px;"></ul>
				</div>
			</div> 
			<script type="text/javascript" src="/js/xz/jquery.js"></script>
			<!--<script type="text/javascript" src="/plupload/plupload.full.min.js"></script>-->
			<!--<script type="text/javascript" src="/js/xz/sucaihuo.js"></script>-->
			</p>
			<input type="hidden" name="id" id="id" value="<?php echo ($id); ?>">
			<input type="hidden" name="face180" id="face180">
		</div>
	</form>
	<form id="formTag" enctype="multipart/form-data">
		<div class="uploadImgBtn" id="uploadImgBtn" style="display:none;">
			<input class="uploadImg" type="file" name="file" id="tpfile">
		</div>
	</form>
</body>
</html>
<script>
	$(function(){
	    $(document).on('click','#btn',function(){
	        $("#tpfile").click();
		})

		$("#tpfile").change(function(){
			$.ajax({
                url: "<?php echo U('uploadify');?>",
                type: "post",
                dataType: "json",
                cache: false,
                data: new FormData($("#formTag")[0]),
                processData: false,// 不处理数据
                contentType: false, // 不设置内容类型
                success: function(data){
                    if(data.code == 1){
                        $("#face180").val(data.path);
                        $imgStr = '<img style="width:100px;height:100px;" src="'+data.path+'">';
                        $(".ul_pics").html($imgStr);
					}else{
                        layer.msg(data.msg);
					}
					/*
					 图片显示路径出错，没解决：反斜杠转义
					 $(self).parent().css({
					 "background-image": "url("+data.path+")"
					 })*/;
                }
			})
		})
	})
</script>