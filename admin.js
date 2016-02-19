jQuery(document).ready(function ($) {
    if (typeof wp.media !== 'undefined') {
        console.log("I'm here!");
        $('#publish').click(function () {
            alert("Publishing!");
        });

        var imageSelector = wp.media.featuredImage.frame();

        imageSelector.on('select', function () {
            var attachment = getAttachment();
            console.log(attachment);
            var attachment_url = attachment.sizes.large.url;
            var attachment_width = attachment.sizes.large.width;
            makeCropper(attachment_url, attachment_width);
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

    function makeCropper(attachment_url, attachment_width) {
        var cropper =
            '<div class="featuredCropper">' +
            '<div id="featuredWrapper"><img src="' + attachment_url + '" /><div class="drag"></div></div>' +
            '</div>';
        $('body').append(cropper);
        initDrag(attachment_url,attachment_width);
    }

    function initDrag(attachment_url, attachment_width) {
        var drag_height = (2 * attachment_width) / 19;
        var drag_window = $('.drag');
        drag_window.css({
            'height': drag_height,
            'background-image': 'url('+attachment_url+')'
        });
        drag_window.draggable({
            containment: "#featuredWrapper",
            axis: "y",
            drag: function() {
                var y1 = drag_window.position().top;
                var y2 = y1 + drag_height;
            }
        })
    }

    function featuredImageCropSave() {
        console.log('Saving!');
        var attachment = getAttachment();
        var attachment_height = attachment.sizes.large.height;
        closeCropper();
    }

    function getAttachment() {
        return imageSelector.state().get('selection').first().toJSON();
    }

    function featuredImageCropCancel() {
        console.log('Canceling!');
        closeCropper();
    }

    function closeCropper() {
        $('.featuredCropper').remove();
    }
});

