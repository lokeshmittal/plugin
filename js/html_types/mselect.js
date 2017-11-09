function divi_init_mselects() {
    try {
        // jQuery("select.divi_select").chosen('destroy').trigger("liszt:updated");
        jQuery("select.divi_mselect").chosen(/*{disable_search_threshold: 10}*/);
    } catch (e) {

    }

    jQuery('.divi_mselect').change(function (a) {
        var slug = jQuery(this).val();
        var name = jQuery(this).attr('name');

        //fix for multiselect if in chosen mode remove options
        if (is_divi_use_chosen) {
            var vals = jQuery(this).chosen().val();
            jQuery('.divi_mselect[name=' + name + '] option:selected').removeAttr("selected");
            jQuery('.divi_mselect[name=' + name + '] option').each(function (i, option) {
                var v = jQuery(this).val();
                if (jQuery.inArray(v, vals) !== -1) {
                    jQuery(this).prop("selected", true);
                }
            });
        }

        divi_mselect_direct_search(name, slug);
        return true;
    });
}

function divi_mselect_direct_search(name, slug) {
    //mode with Filter button
    var values = [];
    jQuery('.divi_mselect[name=' + name + '] option:selected').each(function (i, v) {
        values.push(jQuery(this).val());
    });

    //duplicates removing
    //http://stackoverflow.com/questions/9229645/remove-duplicates-from-javascript-array
    values = values.filter(function (item, pos) {
        return values.indexOf(item) == pos;
    });

    values = values.join(',');
    if (values.length) {
        divi_current_values[name] = values;
    } else {
        delete divi_current_values[name];
    }

    divi_ajax_page_num = 1;
    if (divi_autosubmit) {
        divi_submit_link(divi_get_submit_link());
    }
}


