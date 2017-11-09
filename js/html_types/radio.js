function divi_init_radios() {
    if (icheck_skin != 'none') {
        jQuery('.divi_radio_term').iCheck('destroy');

        jQuery('.divi_radio_term').iCheck({
            radioClass: 'iradio_' + icheck_skin.skin + '-' + icheck_skin.color,
            //radioClass: 'iradio_square-green'        
        });

        jQuery('.divi_radio_term').unbind('ifChecked');
        jQuery('.divi_radio_term').on('ifChecked', function (event) {
            jQuery(this).attr("checked", true);
            jQuery(this).parents('.divi_list').find('.divi_radio_term_reset').removeClass('divi_radio_term_reset_visible');
            jQuery(this).parents('.divi_list').find('.divi_radio_term_reset').hide();
            jQuery(this).parents('li').eq(0).find('.divi_radio_term_reset').eq(0).addClass('divi_radio_term_reset_visible');
            var slug = jQuery(this).data('slug');
            var name = jQuery(this).attr('name');
            var term_id = jQuery(this).data('term-id');
            divi_radio_direct_search(term_id, name, slug);
        });

        //this script should be, because another way wrong way of working if to click on the label - removed
        /*
        jQuery('.divi_radio_label').unbind();
        jQuery('label.divi_radio_label').click(function () {
            jQuery(this).prev().find('.divi_radio_term').trigger('ifChecked');
            jQuery(this).parents('.divi_list_radio').find('.checked').removeClass('checked');            
            jQuery(this).prev().addClass('checked');
            return false;
        });
        */
        //***


    } else {
        jQuery('.divi_radio_term').on('change', function (event) {
            jQuery(this).attr("checked", true);
            var slug = jQuery(this).data('slug');
            var name = jQuery(this).attr('name');
            var term_id = jQuery(this).data('term-id');
            divi_radio_direct_search(term_id, name, slug);
        });
    }

    //***

    jQuery('.divi_radio_term_reset').click(function () {
        divi_radio_direct_search(jQuery(this).data('term-id'), jQuery(this).attr('data-name'), 0);
        jQuery(this).parents('.divi_list').find('.checked').removeClass('checked');
        jQuery(this).parents('.divi_list').find('input[type=radio]').removeAttr('checked');
        //jQuery(this).remove();
        jQuery(this).removeClass('divi_radio_term_reset_visible');
        return false;
    });
}

function divi_radio_direct_search(term_id, name, slug) {

    jQuery.each(divi_current_values, function (index, value) {
        if (index == name) {
            delete divi_current_values[name];
            return;
        }
    });

    if (slug != 0) {
        divi_current_values[name] = slug;
        jQuery('a.divi_radio_term_reset_' + term_id).hide();
        jQuery('divi_radio_term_' + term_id).filter(':checked').parents('li').find('a.divi_radio_term_reset').show();
        jQuery('divi_radio_term_' + term_id).parents('ul.divi_list').find('label').css({'fontWeight': 'normal'});
        jQuery('divi_radio_term_' + term_id).filter(':checked').parents('li').find('label.divi_radio_label_' + slug).css({'fontWeight': 'bold'});
    } else {
        jQuery('a.divi_radio_term_reset_' + term_id).hide();
        jQuery('divi_radio_term_' + term_id).attr('checked', false);
        jQuery('divi_radio_term_' + term_id).parent().removeClass('checked');
        jQuery('divi_radio_term_' + term_id).parents('ul.divi_list').find('label').css({'fontWeight': 'normal'});
    }

    divi_ajax_page_num = 1;
    if (divi_autosubmit) {
        divi_submit_link(divi_get_submit_link());
    }
}

