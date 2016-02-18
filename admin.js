jQuery(document).ready(function ($) {
    if (typeof wp.media !== 'undefined') {
        console.log("I'm here!");
        $('#publish').click(function () {
            alert("Publishing!");
        });

        var featuredImage = wp.media.featuredImage;

        featuredImage.frame().on('select', function () {
            var attachment_id = featuredImage.get();
            var attachment = featuredImage.frame().state().get('selection').first().toJSON();
            console.log(attachment);
            var attachment_url = attachment.sizes.large.url;
            var attachment_width = attachment.sizes.large.width;
            var attachment_height = attachment.sizes.large.height;
            makeImageDialog(attachment_url, attachment_width, attachment_height);
        });

        function makeImageDialog(attachment_url, attachment_width, attachment_height) {
            var dialog = $(
                '<div class="featuredCropper">' +
                '<div id="featuredWrapper"><img src="'+attachment_url+'" /><div class="drag"></div></div>' +
                '</div>'
            );
            $('body').append(dialog);
            var drag_height = (2*attachment_width) / 19;
            var $drag = $('.drag');
            $drag.css({'height':drag_height});
            $drag.draggable({ containment: "#featuredWrapper", axis: "y" })
        }
    }
});