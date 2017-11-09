function divi_init_selects() {
    if (is_divi_use_chosen) {
        try {
            // jQuery("select.divi_select").chosen('destroy').trigger("liszt:updated");
            jQuery("select.divi_select, select.divi_price_filter_dropdown").chosen(/*{disable_search_threshold: 10}*/);
        } catch (e) {

        }
    }

    jQuery('.divi_select').change(function () {
        var slug = jQuery(this).val();
        var name = jQuery(this).attr('name');
        divi_select_direct_search(this, name, slug);
    });
}

function divi_select_direct_search(_this, name, slug) {

    jQuery.each(divi_current_values, function (index, value) {
        if (index == name) {
            delete divi_current_values[name];
            return;
        }
    });

    if (slug != 0) {
        divi_current_values[name] = slug;
    }

    divi_ajax_page_num = 1;
    if (divi_autosubmit || jQuery(_this).within('.divi').length == 0) {
        divi_submit_link(divi_get_submit_link());
    }

}


