jQuery(document).ready(function ($) {
    var image;

    if (typeof wp.media !== 'undefined') {
        console.log("I'm here!");
        $('#publish').click(function () {
            alert("Publishing!");
        });

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
            '<a href="#" class="button button-large">Cancel</a> <a href="#" class="button button-primary button-large">Save</a>' +
            '</div>' +
            '</div>';

        $('body').append(cropper);

        resizeCropperElements();
        initDragWindow();

    }

    function resizeCropperElements() {
        var featuredWrapper = $('#featuredWrapper');
        $('.cropperControls').css('width', image.width);
        featuredWrapper.css('width', image.width);
        featuredWrapper.css('height', image.height);
    }

    function initDragWindow() {
        var drag_height = (2 * image.width) / 15;
        var drag_window = $('.drag');
        drag_window.css({
            'height': drag_height,
            'background-image': 'url(' + image.url + ')'
        });
        drag_window.draggable({
            containment: "#featuredWrapper",
            axis: "y",
            drag: function () {
                var y1 = drag_window.position().top;
                var y2 = y1 + drag_height;
                drag_window.css('background-position','0 ' + calculatePercent(y1,y2) + '%');
            }
        })
    }

    function calculatePercent(y1, y2) {
        var height = image.height;
        var decimal = y1 / (height - (y2 - y1));

        if (decimal > 1)
            decimal = 1;

        return decimal * 100;
    }

    function featuredImageCropSave() {
        console.log('Saving!');
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

