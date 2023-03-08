/*
$(document).ready(function() {
	var s = $("header");
	var pos = s.position();					   
	$(window).scroll(function() {
		var windowpos = $(window).scrollTop();
		if (windowpos >= pos.top & windowpos <=1500) {
			s.addClass("dark-header");
		} else {
			s.removeClass("dark-header");	
		}
	});
});
*/
$(document).ready(function() {
    var scroll = $(window).scrollTop();
    var header = $("header");
    if (scroll >= 20) {
        header.addClass("dark-header");
    }
});

$(function() {
    //caches a jQuery object containing the header element
    var header = $("header");
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 20) {
            header.addClass("dark-header");
        } else {
            header.removeClass("dark-header");
        }
    });
});

$(document).ready(function() {
	setTimeout(function(){
		$('body').addClass('loaded');
	}, 3000);
});