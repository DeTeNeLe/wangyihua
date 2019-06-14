// JavaScript Document
		 function gg(){
			 if(this.className='gga1')
			 {
				 this.className='gga2'
				 }
				 else{this.className='gga1'}
			 }
			 
function show1(idstr){
	var getNum = parseInt(document.getElementById(idstr).value);
	if(getNum < 1000){
		document.getElementById(idstr).value = getNum + 1;
	}else{
		alert("不可以大于1000");
	}
}
function show2(idstr){
	var getNum = parseInt(document.getElementById(idstr).value);
	if(getNum > 1){
		document.getElementById(idstr).value = getNum - 1;
	}else{
		alert("请至少购买一个商品");
	}
}

function sc()
{
  var t=document.getElementById("t");
  if(t.className=="sc1"){
  t.className="sc2"
  }else{
  t.className="sc1"
  }
}