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

    /* === Nav === */

    var navbar = $('.ohNavbar');

    /* Nav Setup */

    $('.ohNav > li.page_item_has_children > a, .section-selector > a').click(function(e){
        e.preventDefault();
    });

    var toggles = $('.toggle');

    toggles.click(function() {
        $(this).toggleClass('open');
        toggles.not(this).removeClass('open');
    });

    $('.burger').click(function() {
       $('#content').toggle();
    });

    /* Nav Breakpoints */

    var body = $('body');

    var calculateWidthNeeded = function() {
        body.addClass('desktop');
        body.removeClass('mobile');

        var needed = 0;
        $('.ohNav > li').each(function() {
            needed += $(this).width();
        });
        needed += $('.section-selector').width();
        return needed;
    };

    var setMobileClass = function(needed) {
        if ($('.ohNavbar').width() < needed) {
            body.addClass('mobile');
            body.removeClass('desktop');
        } else {
            body.addClass('desktop');
            body.removeClass('mobile');
            $('.toggle').removeClass('open');
            $('.ohNav').show();
            $('#content').show();
        }
    };

    var needed = calculateWidthNeeded();

    setMobileClass(needed);

    $(window).resize(function() {
        setMobileClass(needed);
    });

    /* Sticky Nav */

    if (navbar.length) {
        var stickyNavTop = navbar.offset().top;

        var stickyNav = function(){
            var navbar = $('.ohNavbar');
            var scrollTop = $(window).scrollTop();

            if (scrollTop > stickyNavTop) {
                navbar.addClass('fixed');
            } else {
                navbar.removeClass('fixed');
            }
        };

        stickyNav();

        $(window).on('scroll mousewheel', function() {
            stickyNav();
        });
    }
});