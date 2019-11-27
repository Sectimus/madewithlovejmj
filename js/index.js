
var chkb = document.getElementsByName('r');
setupSlideshowHandlers();

function setupSlideshowHandlers(){

  for (var i = 0; i < chkb.length; i++) {
    chkb[i].addEventListener('change', function(e) {
      for (var i = 0; i < chkb.length; i++) {
        document.getElementById('caro'+chkb[i].id.charAt(1)).style.display = "none";
      }
      var imgnum = e.target.id.charAt(1);
      var img = document.getElementById('caro'+imgnum);
      img.style.display = "";
    });
  }
}

var currentSlide=1;
var maxSlide=4;

(function automateSlideshow() {
  setTimeout(function () {
    var e_currentSlide = document.getElementById('r'+currentSlide);
    e_currentSlide.checked = true;
    if ("createEvent" in document) {
      var evt = document.createEvent("HTMLEvents");
      evt.initEvent("change", false, true);
      e_currentSlide.dispatchEvent(evt);
    }
    if (currentSlide == maxSlide) {
        currentSlide=1;
    } else{
      currentSlide++;
    }
    automateSlideshow()
  }, 10000);
}());
