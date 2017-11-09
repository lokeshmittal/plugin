var divi_edit_view = false;
var divi_current_conatiner_class = '';
var divi_current_containers_data = {};

jQuery(function () {
    jQuery('.divi_edit_view').click(function () {
        divi_edit_view = true;
        var sid = jQuery(this).data('sid');
        var css_class = 'divi_sid_' + sid;
        jQuery(this).next('div').html(css_class);
        //+++
        jQuery("." + css_class + " .divi_container_overlay_item").show();
        jQuery("." + css_class + " .divi_container").addClass('divi_container_overlay');
        jQuery.each(jQuery("." + css_class + " .divi_container_overlay_item"), function (index, ul) {
            jQuery(this).html(jQuery(this).parents('.divi_container').data('css-class'));
        });

        return false;
    });
    
    
    divi_init_masonry();
    
});

function divi_init_masonry() {
    return;
    /*
    var $container = jQuery('.divi_sid');
    $container.imagesLoaded(function () {
        $container.masonry({
            itemSelector: '.divi_container',
            columnWidth: 300
        });
    });
    */
}


/*
function divi_change_cont_width(select) {
    var width = parseFloat(jQuery(select).val()) * 100;
    jQuery('.' + divi_current_conatiner_class).css('width', width + '%');
}
*/

