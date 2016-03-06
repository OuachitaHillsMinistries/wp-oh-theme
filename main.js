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

    /* === Home page news slider === */

    $('.news .slider').unslider({
        nav: false,
        autoplay: true,
        delay: 7000,
        animation: 'fade',
        animateHeight: true,
        arrows: {
            prev: '<a class="unslider-arrow prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>',
            next: '<a class="unslider-arrow next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>'
        }
    });

    /* === Sticky navigation === */

    var navbar = $('.navbar');
    
    if (navbar.length) {
        var stickyNavTop = navbar.offset().top;

        var stickyNav = function(){
            var navbar = $('.navbar');
            var scrollTop = $(window).scrollTop();

            if (scrollTop > stickyNavTop) {
                navbar.addClass('navbar-fixed-top');
            } else {
                navbar.removeClass('navbar-fixed-top');
            }
        };

        stickyNav();

        $(window).on('scroll mousewheel', function() {
            stickyNav();
        });
    }
    

    /* === Page Gallery & Slider === */

    var slider = $('.images .slider');

    slider.unslider({
        nav: false,
        autoplay: true,
        animation: 'fade'
    });

    var container = $('.images');
    var text = $('.entry-content .text');

    if (container.length) {
        var images = $('.gallery img');
        var gallery = $('.gallery');

        adjustImages(container, slider, gallery, text);

        $(window).smartresize(function() {
            adjustImages(container, slider, gallery, text);
        });
    }

    function adjustImages(images, slider, gallery, content) {
        toggleSlider(images, slider, gallery);
    }

    function toggleSlider(container, slider, gallery) {
        var availableWidth = container.width();
        var areImagesBelow = container.css('clear') == 'both';
        var isEnoughSpace = availableWidth < 500;

        if (areImagesBelow && isEnoughSpace) {
            showSlider(slider, gallery);
        } else {
            hideSlider(slider, gallery);
        }
    }

    function showSlider(slider, gallery) {
        slider.show();
        gallery.hide();
    }

    function hideSlider(slider, gallery) {
        slider.hide();
        gallery.show();
    }
});