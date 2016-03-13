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
});