jQuery(document).ready(function ($) {
    var image;

    if (typeof wp.media !== 'undefined') {
        var imageSelector = wp.media.featuredImage.frame();

        imageSelector.on('select', function () {
            image = imageSelector.state().get('selection').first().toJSON().sizes.mediumLarge;
            makeCropper();
        });

        $(document).keyup(function (e) {
            if (e.keyCode === 13) {
                featuredImageCropSave();
            } // enter
            if (e.keyCode === 27) {
                featuredImageCropCancel();
            } // esc
        });
    }

    function makeCropper() {
        var cropper =
            '<div class="featuredCropper">' +
            '<div id="featuredWrapper"><img src="' + image.url + '" /><div class="drag"></div></div>' +
            '<div class="cropperControls">' +
            '<p>Please drag the highlighted region up and down to select the primary portion of this image.</p>' +
            '<a href="#" id="cropperCancel" class="button button-large">Cancel</a> ' +
            '<a href="#" id="cropperSave" class="button button-primary button-large">Save</a>' +
            '</div>' +
            '</div>';

        $('body').append(cropper);

        resizeCropperElements();
        initDragWindow();
        initCropperControls();
    }

    function initCropperControls() {
        $('#cropperCancel').click(featuredImageCropCancel);
        $('#cropperSave').click(featuredImageCropSave);
    }

    function resizeCropperElements() {
        var featuredWrapper = $('#featuredWrapper');
        $('.cropperControls').css('width', image.width);
        featuredWrapper.css('width', image.width);
        featuredWrapper.css('height', image.height);
    }

    function initDragWindow() {
        var drag_window = $('.drag');
        drag_window.css({
            'height': (2 * image.width) / 15,
            'background-image': 'url(' + image.url + ')'
        });
        drag_window.draggable({
            containment: "#featuredWrapper",
            axis: "y",
            drag: function () {
                drag_window.css('background-position','0 ' + calculatePercent() + '%');
            }
        })
    }

    function calculatePercent() {
        var y1 = $('.drag').position().top;
        var y2 = y1 + (2 * image.width) / 15;
        var decimal = y1 / (image.height - (y2 - y1));

        if (decimal > 1)
            decimal = 1;

        return decimal * 100;
    }

    function featuredImageCropSave() {
        console.log('Saving!');

        var $_GET = {};

        document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
            function decode(s) {
                return decodeURIComponent(s.split("+").join(" "));
            }

            $_GET[decode(arguments[1])] = decode(arguments[2]);
        });

        var data = {
            'action': 'save_featured_image_position',
            'percent': calculatePercent(),
            'post': $_GET['post']
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function(response) {
            console.log(response);
            if (response === 'error') {
                alert("Oops! We had a problem saving this image's position. Sorry about that.");
            }
        });

        closeCropper();
    }

    function featuredImageCropCancel() {
        console.log('Canceling!');
        closeCropper();
    }

    function closeCropper() {
        $('.featuredCropper').remove();
    }
});

