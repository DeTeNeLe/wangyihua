/*内容分页JS*/
function showContent(act,dataID,showID){
	var loca	= parseInt(location.hash.split("#", 2)[1]);//取得锚点值，数值无法计算时返回NAN
	var page;
	var URL		= location.href;
	var data	= document.getElementById(dataID).value;//取得内容
	var content = data.split(/<hr\s+style\=[\"|\']page-break-after\:always\;[\"|\']\s+class\=[\"|\']ke-pagebreak[\"|\']\s+\/>/);//分解成数组
	
	URL = URL.replace(/\#\d+/,'');
	if(act=='prev'){//点击上一页操作
		if(isNaN(loca) || loca<=1){//不存在锚点值
			page = 1;
			alert('已是第一页，没有再上一页！');
		}else{//存在锚点值
			page = loca-1;
			document.location.href=URL+'#'+page;	
		}
	}else if(act=='next'){
		if(isNaN(loca)){//不存在锚点值
			page = 1;
		}else{
			page = loca;
		}
		if(page>=content.length){
			alert('已是最后页，没有再下一页！');
			page = content.length;
		}else{
			if(isNaN(loca)){//不存在锚点值
				page = 2;
				document.location.href=URL+'#'+page;
			}else{
				page = loca+1;
				document.location.href=URL+'#'+page;
			}
		}
	}else{
		if(isNaN(loca)){
			page = 1;
		}else{
			page = loca;
			if(page>=content.length){
				document.location.href=URL+'#'+1;
				page = 1;
			}
		}
	}
	document.getElementById(showID).innerHTML=content[page-1];
}
		
function construct(dataID,showID){
	/*参数说明：
		dataID：数据存放处ID，<textarea>中的ID
		showID：显示内容信息ID，显示内容的DIV处ID
	*/
	showContent('',dataID,showID);
	document.write('<div style="font-size:12px; color:#999; text-align:left; margin:10px;"><span style="cursor:pointer;" onclick="showContent(\'prev\',\''+dataID+'\',\''+showID+'\');">上一页</span><span style="cursor:pointer; margin-left:15px;" onclick="showContent(\'next\',\''+dataID+'\',\''+showID+'\');">下一页</span></div>');
}