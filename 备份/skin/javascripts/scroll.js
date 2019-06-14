
var doscroll = function(){
     var $parent = $('.js-slide-list');
     var $first = $parent.find('li:first');
     var height = $first.height();
     $first.animate({
         marginTop: -height + 'px'
         }, 500, function() {
         $first.css('marginTop', 0).appendTo($parent);
     });    
};


if (typeof(InterValObj_2) != "undefined"){ 
		window.clearInterval(InterValObj_2);
		InterValObj_2=null; 
}

var InterValObj_2=setInterval(function(){doscroll()},300);