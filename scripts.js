// Lazy Load for Background Images
document.addEventListener("DOMContentLoaded", function() {
  var lazyloadImages;    

  if ("IntersectionObserver" in window) {
    lazyloadImages = document.querySelectorAll(".lazy");
    var imageObserver = new IntersectionObserver(function(entries, observer) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          var image = entry.target;
          image.classList.remove("lazy");
          imageObserver.unobserve(image);
        }
      });
    });

    lazyloadImages.forEach(function(image) {
      imageObserver.observe(image);
    });
  } else {  
    var lazyloadThrottleTimeout;
    lazyloadImages = document.querySelectorAll(".lazy");
    
    function lazyload () {
      if(lazyloadThrottleTimeout) {
        clearTimeout(lazyloadThrottleTimeout);
      }    

      lazyloadThrottleTimeout = setTimeout(function() {
        var scrollTop = window.pageYOffset;
        lazyloadImages.forEach(function(img) {
            if(img.offsetTop < (window.innerHeight + scrollTop)) {
              img.src = img.dataset.src;
              img.classList.remove('lazy');
            }
        });
        if(lazyloadImages.length == 0) { 
          document.removeEventListener("scroll", lazyload);
          window.removeEventListener("resize", lazyload);
          window.removeEventListener("orientationChange", lazyload);
        }
      }, 20);
    }

    document.addEventListener("scroll", lazyload);
    window.addEventListener("resize", lazyload);
    window.addEventListener("orientationChange", lazyload);
  }
})


// Wrap Search inside div
jQuery($ => {
  let $contents = $("#search").nextUntil('.xwc--ls-element').add('.xwc--ls-element');
  $contents.wrapAll('<div class="search-bar" />');
});


// Add Class on click for Search Popup
jQuery(document).on('click', "#header-search .fl-icon", function(){
  jQuery(".search-bar").addClass("popup-opened");
  jQuery(".search-bar .xwc--ls-element").append('<span class="close-popup">X</span>');
});
jQuery(document).on('click', '.search-bar .close-popup', function(){
  jQuery(".search-bar").removeClass("popup-opened");
  jQuery(".search-bar .xwc--ls-element .close-popup").remove();
});