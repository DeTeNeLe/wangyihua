// 获取元素id
function $id(str){
	return document.getElementById(str);
}

// 获取元素name
function $name(str){
	return document.getElementsByName(str);
}

//打开选择商品的窗口
//model-单选或多选(radio|check)；inputID-文本框ID
function OT_OpenSelGoods(model,inputID){
	var arr = window.open("goods_info.php?model="+model+"&inputID="+inputID,"","top=200,left=300,width=800,height=500,menubar=no,scrollbars=yes,status=no,resizable=yes");
	//var arr = window.open("sel_tableinfo.php?fileMode="+ fileMode +"&fileFormName="+ fileFormName +"&upPath="+ fileDir +"&upFileType=images","","top=200,left=350,width=800,height=500,menubar=no,scrollbars=yes,status=no,resizable=yes");
}


// 缩略图参数，默认开启 isThumb=(false关闭)&thumbW=200&thumbH=0
// 文字水印参数，默认关闭 isWatermark=font&watermarkPos=centerMiddle&watermarkPadding=6&watermarkFontContent=盛典网络&watermarkFontSize=14&watermarkFontColor=black
// 图片水印参数，默认关闭 isWatermark=img&watermarkPath=upFile/water.gif&watermarkPos=centerMiddle&watermarkPadding=6
// 区域模式 areaMode=
// 上传模式 upMode：more批量上传，其他单文件上传
// 打开上传载入图片窗口
function OT_OpenUpFile(fileMode,fileFormName,fileDir){
	var arr = window.open("info_upFile.php?fileMode="+ fileMode +"&fileFormName="+ fileFormName +"&upPath="+ fileDir +"&upFileType=images","","top=250,left=340,width=550,height=240,menubar=no,scrollbars=yes,status=no,resizable=yes");
//	if (arr != null){
//		OT_InsertImg(arr);
//	}
}

// 打开上传载入图片窗口
function OT_OpenUpFile2(fileMode,fileFormName,fileDir){
	var arr = window.open("info_upFile2.php?fileMode="+ fileMode +"&fileFormName="+ fileFormName +"&upPath="+ fileDir +"","","top=250,left=340,width=550,height=240,menubar=no,scrollbars=yes,status=no,resizable=yes");
//	if (arr != null){
//		OT_InsertImg(arr);
//	}
}

// 打开上传大文件窗口
function OT_OpenUpBigFile(fileMode,fileFormName,fileDir,upMode){
	var arr = window.open("info_upBigFile.php?fileMode="+ fileMode +"&fileFormName="+ fileFormName +"&upPath="+ fileDir +"&upMode="+ upMode,"","top=250,left=340,width=660,height=240,menubar=no,scrollbars=yes,status=no,resizable=yes");
//	if (arr != null){
//		OT_InsertImg(arr);
//	}
}

// if(isNaN(value))execCommand('undo');

// 过滤小数
// 应用例子 onkeyup="if (this.value!=FiltDecimal(this.value)){this.value=FiltDecimal(this.value)}"
// 应用例子 onkeyup="this.value=FiltDecimal(this.value)"
function FiltDecimal(str){
	return str.replace(/[^\d*\.?\d{0,2}$]/g,'')
}

// 过滤整数
// 应用例子 onkeyup="if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}"
// 应用例子 onkeyup="this.value=FiltInt(this.value)"
function FiltInt(str){
	return str.replace(/\D/g,'')
}

// 把Option的text值覆盖toID文本框
// 应用例子 onchange="OptionTextTo('labItemID','labItemName');"
function OptionTextTo(sourceID,toID){
	document.getElementById(toID).value=document.getElementById(sourceID).options[document.getElementById(sourceID).selectedIndex].text;
}


// 生成随机数
// num：生成个数
function RndNumber(num) {
   var a = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "Z", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
   var b = "", c;
   for(i=1; i<=num; i++){
      c = Math.floor(Math.random() * a.length);
      b = b + a[c];
//      a = a.del(c);
   }
   return b;
}

function RndNumberSort(num) {
   var a = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
   var b = "", c;
   for(i=1; i<=num; i++){
      c = Math.floor(Math.random() * a.length);
      b = b + a[c];
//      a = a.del(c);
   }
   return b;
}



// 使用AJAX异步无刷新
function UseAjax(urlStr){
	var ajax=new AJAXRequest();
	ajax.get(
		// 请求的地址
		urlStr,
		// 回调函数，注意，是回调函数名，不要带括号
		function(obj) { eval(obj.responseText); }
	);
}

// 下拉框内容载入数组变量
function SelectOptionArr(selectName){
	var SelectOptionArray = new Array();

	for (soi=0; soi<document.getElementById(selectName).options.length; soi++){
		SelectOptionArray[document.getElementById(selectName).options[soi].value] = document.getElementById(selectName).options[soi].text;
	}
	return SelectOptionArray;
}

// 下拉框内容检索
function SelectOptionSearch(sourceID,selectName,arrObj){
	document.getElementById(selectName).options.length=0;
	for (var key in arrObj){
		if (arrObj[key].lastIndexOf(document.getElementById(sourceID).value)>=0){
			document.getElementById(selectName).options.add(new Option(arrObj[key],key));
		}
	}
}

// 清理下拉框内容
function SelectOptionClear(selectName,defText){
	document.getElementById(selectName).options.length=0; 
	document.getElementById(selectName).options.add(new Option(defText,""));
	document.getElementById(selectName).value = "";
}