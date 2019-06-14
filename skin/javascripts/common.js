function ajax_post(url,data,callback,dataType){
		if(dataType=='undefined'){
			dataType='html';
		}
		$.ajax({
		  type: 'GET',
		  url: url,
		  data: data,
		  success: function(msg){
			eval(callback);
		  },
		  dataType: dataType,
		});
}

function checkit(type,value){
	if(type=='phone'){
		if(!/^[1][3|4|5|7|8][0-9]{9}$/.test(value)){  
			return false;
		}
		return true;
	}
	if(type=='phone_code'){
		if(!/^[0-9]{4}$/.test(value)){  
			return false;
		}
		return true;
	}
	if(type=='goods_num'){
		if(!/^[1-9][0-9]{0,2}$/.test(value)){  
			return false;
		}
		return true;
	}
	if(type=='money'){
		if(!/^[1-9][0-9]{0,8}$/.test(value)){  
			return false;
		}
		return true;
	}
	
}

function myalert(str){
	alert(str);
}

function load_new_order(){
	
	var callback='load_new_order_callback(msg)';
	ajax_post(load_new_order_url,{},callback);
	
}

function load_new_order_callback(msg){
	
	$('#topscroll').html(msg);
	 $("#topscroll").imgscroll({
            speed: 8,    //滚动速度
            amount: 0,    //滚动过渡时间
            width: 1,     //滚动步数
            dir: "left"   // "left" 或 "up" 向左或向上滚动
        });
}



