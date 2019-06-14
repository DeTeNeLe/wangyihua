function showAndHidden(id){//显示与隐藏
	var treaty = document.getElementById(id).style.display;
	if(treaty=='none'){
		document.getElementById(id).style.display = '';
	}else{
		document.getElementById(id).style.display = 'none';
	}
}