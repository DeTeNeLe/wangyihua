    $.extend($.fn,{
        fnTimeCountDown:function(d){
				var f = {
                    haomiao: function(n){
						//n=n.substr(0,2);
						
                        if(n < 10){n="0" + n.toString()};
                        if(n < 100){n="0" + n.toString()};
                        n=n.toString();
						n=n.substr(0,2);
						return n;
                    },
                    zero: function(n){
                        var _n = parseInt(n, 10);//解析字符串,返回整数
                        if(_n > 0){
                            if(_n <= 9){
                                _n = "0" + _n
                            }
                            return String(_n);
                        }else{
                            return "00";
                        }
                    },
                    dv: function(){
                        var _d = daojishi;
                        var now = new Date(),
                            endDate = new Date(_d);
                        
                        var dur = (endDate - now.getTime()) / 1000;
						var mss = endDate - now.getTime();
						
						var pms = {
                            hm:"00",
                            sec: "00",
                            mini: "00",
                            hour: "00"
                        };
                        if(mss > 0){
                            pms.hm = f.haomiao(mss % 1000);
                            pms.sec = f.zero(dur % 60);
                            pms.mini = Math.floor((dur / 60)) > 0? f.zero(Math.floor((dur / 60)) % 60) : "00";
                            pms.hour = Math.floor((dur / 3600)) > 0? f.zero(Math.floor((dur / 3600)) % 24) : "00";
                        }else{
                            pms.hour=pms.mini=pms.sec=pms.hm = "00";
							$('.fnTimeCountDown').html('<span style="width:250px;">正在开奖...</span>');
							if(daojishi_reload_url!=''){
								
								setTimeout('window.location.href=daojishi_reload_url;',5000);
								
							}
                           
                            return;
                        }
                        return pms;
                    },
                    ui: function(){
						$(".hm").html(f.dv().hm);
						$(".sec").html(f.dv().sec);
						$(".mini").html(f.dv().mini);
						$(".hour").html(f.dv().hour);
						setTimeout(f.ui, 1);
                    }
                };
                f.ui();
            
        }
    });