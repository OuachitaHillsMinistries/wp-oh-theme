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
            var attachment_url = attachment.url;
            console.log(attachment_id);
            console.log(attachment_url);
            var link = $('<img class="featuredImage" src="'+attachment_url+'" />');
            var dialog_width = ($(window).width()) * .8;
            var dialog_height = ($(window).height()) * .8;
            $(link).dialog({
                width: dialog_width,
                height: dialog_height
            });
        });
    }
});