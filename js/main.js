// (Slightly modified) SmartResize jQuery Plugin credit Paul Irish
// http://www.paulirish.com/2009/throttled-smartresize-jquery-event-handler/

(function($,sr){

    // debouncing function from John Hann
    // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
    var debounce = function (func, threshold, execAsap) {
        var timeout;

        return function debounced () {
            var obj = this, args = arguments;
            function delayed () {
                if (!execAsap)
                    func.apply(obj, args);
                timeout = null;
            }

            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 1000);
        };
    };
    // smartresize
    jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');


jQuery(document).ready(function($) {
    $('.news .slider').unslider({
        nav: false,
        autoplay: true,
        delay: 7000,
        animation: 'fade',
        animateHeight: true
    });

    var slider = $('.images .slider');

    slider.unslider({
        nav: false,
        autoplay: true,
        animation: 'fade'
    });

    var container = $('.images');

    if (container.length) {
        var images = $('.gallery img');
        var gallery = $('.gallery');

        showOrHideGallery(container, slider, gallery);

        $(window).smartresize(function() {
            showOrHideGallery(container, slider, gallery);
        });
    }

    function showOrHideGallery(container, slider, gallery) {
        var availableWidth = container.width();
        if (availableWidth < 500) {
            slider.show();
            gallery.hide();
        } else {
            slider.hide();
            gallery.show();
        }
    }
});