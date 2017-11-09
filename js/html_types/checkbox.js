function divi_init_checkboxes() {
    if (icheck_skin != 'none') {
        jQuery('.divi_checkbox_term').iCheck('destroy');

        jQuery('.divi_checkbox_term').iCheck({
            checkboxClass: 'icheckbox_' + icheck_skin.skin + '-' + icheck_skin.color,
            //checkboxClass: 'icheckbox_square-green'
        });


        jQuery('.divi_checkbox_term').unbind('ifChecked');
        jQuery('.divi_checkbox_term').on('ifChecked', function (event) {
            jQuery(this).attr("checked", true);
            divi_checkbox_process_data(this, true);
        });

        jQuery('.divi_checkbox_term').unbind('ifUnchecked');
        jQuery('.divi_checkbox_term').on('ifUnchecked', function (event) {
            jQuery(this).attr("checked", false);
            divi_checkbox_process_data(this, false);
        });

        //this script should be, because another way wrong way of working if to click on the label
        jQuery('.divi_checkbox_label').unbind();
        jQuery('label.divi_checkbox_label').click(function () {
            if (jQuery(this).prev().find('.divi_checkbox_term').is(':checked')) {
                jQuery(this).prev().find('.divi_checkbox_term').trigger('ifUnchecked');
                jQuery(this).prev().removeClass('checked');
            } else {
                jQuery(this).prev().find('.divi_checkbox_term').trigger('ifChecked');
                jQuery(this).prev().addClass('checked');
            }
            
            return false;
        });
        //***

    } else {
        jQuery('.divi_checkbox_term').on('change', function (event) {
            if (jQuery(this).is(':checked')) {
                jQuery(this).attr("checked", true);
                divi_checkbox_process_data(this, true);
            } else {
                jQuery(this).attr("checked", false);
                divi_checkbox_process_data(this, false);
            }
        });
    }
}
function divi_checkbox_process_data(_this, is_checked) {
    var tax = jQuery(_this).data('tax');
    var name = jQuery(_this).attr('name');
    var term_id = jQuery(_this).data('term-id');
    divi_checkbox_direct_search(term_id, name, tax, is_checked);
}
function divi_checkbox_direct_search(term_id, name, tax, is_checked) {

    var values = '';
    var checked = true;
    if (is_checked) {
        if (tax in divi_current_values) {
            divi_current_values[tax] = divi_current_values[tax] + ',' + name;
        } else {
            divi_current_values[tax] = name;
        }
        checked = true;
    } else {
        values = divi_current_values[tax];
        values = values.split(',');
        var tmp = [];
        jQuery.each(values, function (index, value) {
            if (value != name) {
                tmp.push(value);
            }
        });
        values = tmp;
        if (values.length) {
            divi_current_values[tax] = values.join(',');
        } else {
            delete divi_current_values[tax];
        }
        checked = false;
    }
    jQuery('.divi_checkbox_term_' + term_id).attr('checked', checked);
    divi_ajax_page_num = 1;
    if (divi_autosubmit) {
        divi_submit_link(divi_get_submit_link());
    }
}


