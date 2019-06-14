// JavaScript Document
$(function(){
	var $div_li =$(".newsTitle ul li");
	$div_li.click(function(){
		$(this).addClass("h")            //当前<li>元素高亮
			   .siblings().removeClass("h");  //去掉其它同辈<li>元素的高亮
		var index =  $div_li.index(this);  // 获取当前点击的<li>元素 在 全部li元素中的索引。
		$("div.newsList > div")   	//选取子节点。不选取子节点的话，会引起错误。如果里面还有div 
				.eq(index).show()   //显示 <li>元素对应的<div>元素
				.siblings().hide(); //隐藏其它几个同辈的<div>元素
	})
})

$(function(){
	$(".ho").parent("a").mouseover(function(){
		$(this).children(".ho").addClass("hover")	
	})
	$(".ho").parent("a").mouseout(function(){
		$(this).children(".ho").removeClass("hover")	
	})	
})


function expect(){
	alert("敬请期待");
}
	
function showid(idname){
var oBtn=document.getElementById('hide');
var isIE = (document.all) ? true : false;
 var isIE6 = false;
    try {
        isIE6 = isIE && ([/MSIE (\d)\.0/i.exec(navigator.userAgent)][0][1] == 6);
    }
    catch (e) {

    }
var newbox=document.getElementById(idname);
newbox.style.zIndex="9999";
newbox.style.display="block"
newbox.style.position = !isIE6 ? "fixed" : "absolute";
newbox.style.top =newbox.style.left = "50%";
newbox.style.marginTop = - newbox.offsetHeight / 2 + "px";
newbox.style.marginLeft = - newbox.offsetWidth / 2 + "px";  
var layer=document.createElement("div");
layer.id="layer";
layer.style.width=layer.style.height="100%";
layer.style.position= !isIE6 ? "fixed" : "absolute";
layer.style.top=layer.style.left=0;
layer.style.backgroundColor="#000";
layer.style.zIndex="9998";
layer.style.opacity="0.6";
document.body.appendChild(layer);
var sel=document.getElementsByTagName("select");
for(var i=0;i<sel.length;i++){        
sel[i].style.visibility="hidden";
}
function layer_iestyle(){      
layer.style.width=Math.max(document.documentElement.scrollWidth, document.documentElement.clientWidth)
+ "px";
layer.style.height= Math.max(document.documentElement.scrollHeight, document.documentElement.clientHeight) +
"px";
}
function newbox_iestyle(){      
newbox.style.marginTop = document.documentElement.scrollTop - newbox.offsetHeight / 2 + "px";
newbox.style.marginLeft = document.documentElement.scrollLeft - newbox.offsetWidth / 2 + "px";
}
if(isIE){layer.style.filter ="alpha(opacity=60)";}
if(isIE6){  
layer_iestyle()
newbox_iestyle();
window.attachEvent("onscroll",function(){                              
newbox_iestyle();
})
window.attachEvent("onresize",layer_iestyle)          
}  
oBtn.onclick=function(){newbox.style.display="none";layer.style.display="none";for(var i=0;i<sel.length;i++){
sel[i].style.visibility="visible";
}}
}

/*屏幕宽度小于1000，没有二维码*/
$(function(){
		var heg=$(".weixinIco");
		var a1=1024;
		if($(window).width()<a1){
			$(heg).hide();
		}
		else if($(window).width()>a1)
		{
			$(heg).show();	
		}
})

$(function(){
	$(".FriendLink :nth-child(4n)").css("margin","0 0 7px 0")	
})



