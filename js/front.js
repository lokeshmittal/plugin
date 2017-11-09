var divi_redirect = '';//if we use redirect attribute in shortcode [divi]
//***

jQuery(function ($) {
    jQuery('body').append('<div id="divi_html_buffer" class="divi_info_popup" style="display: none;"></div>');
    jQuery.fn.life = function (types, data, fn) {
	jQuery(this.context).on(types, this.selector, data, fn);
	return this;
    };
//http://stackoverflow.com/questions/2389540/jquery-hasparent
    jQuery.extend(jQuery.fn, {
	within: function (pSelector) {
	    // Returns a subset of items using jQuery.filter
	    return this.filter(function () {
		// Return truthy/falsey based on presence in parent
		return jQuery(this).closest(pSelector).length;
	    });
	}
    });

    //+++

    if (jQuery('#divi_results_by_ajax').length > 0) {
	divi_is_ajax = 1;
    }

    //listening attributes in shortcode [divi]
    divi_autosubmit = parseInt(jQuery('.divi').eq(0).data('autosubmit'), 10);
    divi_ajax_redraw = parseInt(jQuery('.divi').eq(0).data('ajax-redraw'), 10);



    //+++

    divi_ext_init_functions = jQuery.parseJSON(divi_ext_init_functions);

    //fix for native woo price range
    divi_init_native_woo_price_filter();


    jQuery('body').bind('price_slider_change', function (event, min, max) {
	if (divi_autosubmit && !divi_show_price_search_button && jQuery('.price_slider_wrapper').length < 2) {

	    jQuery('.divi .widget_price_filter form').trigger('submit');

	} else {
	    var min_price = jQuery(this).find('.price_slider_amount #min_price').val();
	    var max_price = jQuery(this).find('.price_slider_amount #max_price').val();
	    divi_current_values.min_price = min_price;
	    divi_current_values.max_price = max_price;
	}
    });

    jQuery('.divi_price_filter_dropdown').life('change', function () {
	var val = jQuery(this).val();
	if (parseInt(val, 10) == -1) {
	    delete divi_current_values.min_price;
	    delete divi_current_values.max_price;
	} else {
	    var val = val.split("-");
	    divi_current_values.min_price = val[0];
	    divi_current_values.max_price = val[1];
	}

	if (divi_autosubmit || jQuery(this).within('.divi').length == 0) {
	    divi_submit_link(divi_get_submit_link());
	}
    });





    //change value in textinput price filter if WOOCS is installed
    divi_recount_text_price_filter();
    //+++
    jQuery('.divi_price_filter_txt').life('change', function () {

	var from = parseInt(jQuery(this).parent().find('.divi_price_filter_txt_from').val(), 10);
	var to = parseInt(jQuery(this).parent().find('.divi_price_filter_txt_to').val(), 10);

	if (to < from || from < 0) {
	    delete divi_current_values.min_price;
	    delete divi_current_values.max_price;
	} else {
	    if (typeof woocs_current_currency !== 'undefined') {
		from = Math.ceil(from / parseFloat(woocs_current_currency.rate));
		to = Math.ceil(to / parseFloat(woocs_current_currency.rate));
	    }

	    divi_current_values.min_price = from;
	    divi_current_values.max_price = to;
	}

	if (divi_autosubmit || jQuery(this).within('.divi').length == 0) {
	    divi_submit_link(divi_get_submit_link());
	}
    });


    //***

    jQuery('.divi_open_hidden_li_btn').life('click', function () {
	var state = jQuery(this).data('state');
	var type = jQuery(this).data('type');

	if (state == 'closed') {
	    jQuery(this).parents('.divi_list').find('.divi_hidden_term').addClass('divi_hidden_term2');
	    jQuery(this).parents('.divi_list').find('.divi_hidden_term').removeClass('divi_hidden_term');
	    if (type == 'image') {
		jQuery(this).find('img').attr('src', jQuery(this).data('opened'));
	    } else {
		jQuery(this).html(jQuery(this).data('opened'));
	    }

	    jQuery(this).data('state', 'opened');
	} else {
	    jQuery(this).parents('.divi_list').find('.divi_hidden_term2').addClass('divi_hidden_term');
	    jQuery(this).parents('.divi_list').find('.divi_hidden_term2').removeClass('divi_hidden_term2');

	    if (type == 'image') {
		jQuery(this).find('img').attr('src', jQuery(this).data('closed'));
	    } else {
		jQuery(this).text(jQuery(this).data('closed'));
	    }

	    jQuery(this).data('state', 'closed');
	}


	return false;
    });
    //open hidden block
    divi_open_hidden_li();

    //*** woocommerce native "AVERAGE RATING" widget synchronizing
    jQuery('.widget_rating_filter li.wc-layered-nav-rating a').click(function () {
	var is_chosen = jQuery(this).parent().hasClass('chosen');
	var parsed_url = divi_parse_url(jQuery(this).attr('href'));
	var rate = 0;
	if (parsed_url.query !== undefined) {
	    if (parsed_url.query.indexOf('min_rating') !== -1) {
		var arrayOfStrings = parsed_url.query.split('min_rating=');
		rate = parseInt(arrayOfStrings[1], 10);
	    }
	}
	jQuery(this).parents('ul').find('li').removeClass('chosen');
	if (is_chosen) {
	    delete divi_current_values.min_rating;
	} else {
	    divi_current_values.min_rating = rate;
	    jQuery(this).parent().addClass('chosen');
	}

	divi_submit_link(divi_get_submit_link());

	return false;
    });

    //DIVI start filtering button action
    jQuery('.divi_start_filtering_btn').life('click', function () {

	var shortcode = jQuery(this).parents('.divi').data('shortcode');
	jQuery(this).html(divi_lang_loading);
	jQuery(this).addClass('divi_start_filtering_btn2');
	jQuery(this).removeClass('divi_start_filtering_btn');
	//redrawing [divi ajax_redraw=1] only
	var data = {
	    action: "divi_draw_products",
	    page: 1,
	    shortcode: 'divi_nothing', //we do not need get any products, seacrh form data only
	    divi_shortcode: shortcode
	};
	jQuery.post(divi_ajaxurl, data, function (content) {
	    content = jQuery.parseJSON(content);
	    jQuery('div.divi_redraw_zone').replaceWith(jQuery(content.form).find('.divi_redraw_zone'));
	    divi_mass_reinit();
	});


	return false;
    });

    //***
    var str = window.location.href;
    window.onpopstate = function (event) {
	try {
	    if (Object.keys(divi_current_values).length) {
		var temp = str.split('?');
		var get1 = temp[1].split('#');
		var str2 = window.location.href;
		var temp2 = str2.split('?');
		var get2 = temp2[1].split('#');
		//console.log(get1[0]);
		//console.log(get2[0]);
		if (get2[0] != get1[0]) {
		    divi_show_info_popup(divi_lang_loading);
		    window.location.reload();
		}
		return false;
	    }
	} catch (e) {
	    console.log(e);
	}
    };
    //***

    //ion-slider price range slider
    divi_init_ion_sliders();

    //***

    divi_init_show_auto_form();
    divi_init_hide_auto_form();

    //***
    divi_remove_empty_elements();

    divi_init_search_form();
    divi_init_pagination();
    divi_init_orderby();
    divi_init_reset_button();
    divi_init_beauty_scroll();
    //+++
    divi_draw_products_top_panel();
    divi_shortcode_observer();


//+++
    //if we use redirect attribute in shortcode [divi is_ajax=0]
    //not for ajax, for redirect mode only
    if (!divi_is_ajax) {
	divi_redirect_init();
    }

    divi_init_toggles();

});

//if we use redirect attribute in shortcode [divi is_ajax=0]
//not for ajax, for redirect mode only
function divi_redirect_init() {

    try {
	if (jQuery('.divi').length) {
	    //https://wordpress.org/support/topic/javascript-error-in-frontjs?replies=1
	    if (undefined !== jQuery('.divi').val()) {
		divi_redirect = jQuery('.divi').eq(0).data('redirect');//default value
		if (divi_redirect.length > 0) {
		    divi_shop_page = divi_current_page_link = divi_redirect;
		}


		//***
		/*
		 var events = ['click', 'change', 'ifChecked', 'ifUnchecked'];
		 
		 for (var i = 0; i < events.length; i++) {
		 
		 jQuery('div.divi input, div.divi option, div.divi div, div.divi label').live(events[i], function (e) {
		 try {
		 if (jQuery(this).parents('.divi').data('redirect').length > 0) {
		 divi_redirect = jQuery(this).parents('.divi').data('redirect');
		 }
		 } catch (e) {
		 console.log('Error: attribute redirection doesn works!');
		 }
		 e.stopPropagation();
		 });
		 
		 }
		 */
		//***


		return divi_redirect;
	    }
	}
    } catch (e) {
	console.log(e);
    }

}

function divi_init_orderby() {
    jQuery('form.woocommerce-ordering').life('submit', function () {
	return false;
    });
    jQuery('form.woocommerce-ordering select.orderby').life('change', function () {
	divi_current_values.orderby = jQuery(this).val();
	divi_ajax_page_num = 1;
	divi_submit_link(divi_get_submit_link());
	return false;
    });
}

function divi_init_reset_button() {
    jQuery('.divi_reset_search_form').life('click', function () {
	//var link = jQuery(this).data('link');
	divi_ajax_page_num = 1;
	if (divi_is_permalink) {
	    divi_current_values = {};
	    divi_submit_link(divi_get_submit_link().split("page/")[0]);
	    //divi_submit_link(divi_get_submit_link());
	} else {
	    var link = divi_shop_page;
	    if (divi_current_values.hasOwnProperty('page_id')) {
		link = location.protocol + '//' + location.host + "/?page_id=" + divi_current_values.page_id;
		divi_current_values = {'page_id': divi_current_values.page_id};
		divi_get_submit_link();
	    }
	    //***
	    divi_submit_link(link);
	    if (divi_is_ajax) {
		history.pushState({}, "", link);
		if (divi_current_values.hasOwnProperty('page_id')) {
		    divi_current_values = {'page_id': divi_current_values.page_id};
		} else {
		    divi_current_values = {};
		}
	    }
	}
	return false;
    });
}

function divi_init_pagination() {

    if (divi_is_ajax === 1) {
	//jQuery('.woocommerce-pagination ul.page-numbers a.page-numbers').life('click', function () {
	jQuery('a.page-numbers').life('click', function () {
	    var l = jQuery(this).attr('href');

	    if (divi_ajax_first_done) {
		//http://woocommerce-filter.pluginus.net/wp-admin/admin-ajax.php?paged=2
		var res = l.split("paged=");
		if (typeof res[1] !== 'undefined') {
		    divi_ajax_page_num = parseInt(res[1]);
		} else {
		    divi_ajax_page_num = 1;
		}
	    } else {
		//http://woocommerce-filter.pluginus.net/tester/page/2/
		var res = l.split("page/");
		if (typeof res[1] !== 'undefined') {
		    divi_ajax_page_num = parseInt(res[1]);
		} else {
		    divi_ajax_page_num = 1;
		}
	    }

	    //+++

	    //if (divi_autosubmit) - pagination doesn need pressing any submit button!!
	    {
		divi_submit_link(divi_get_submit_link());
	    }

	    return false;
	});
    }
}

function divi_init_search_form() {
    divi_init_checkboxes();
    divi_init_mselects();
    divi_init_radios();
    divi_price_filter_radio_init();
    divi_init_selects();


    //for extensions
    if (divi_ext_init_functions !== null) {
	jQuery.each(divi_ext_init_functions, function (type, func) {
	    eval(func + '()');
	});
    }
    //+++
    //var containers = jQuery('.divi_container');

    //+++
    jQuery('.divi_submit_search_form').click(function () {
	if (divi_ajax_redraw) {
	    //[divi redirect="http://www.dev.woocommerce-filter.com/test-all/" autosubmit=1 ajax_redraw=1 is_ajax=1 tax_only="locations" by_only="none"]
	    divi_ajax_redraw = 0;
	    divi_is_ajax = 0;
	}
	//***
	divi_submit_link(divi_get_submit_link());
	return false;
    });



    //***
    jQuery('ul.divi_childs_list').parent('li').addClass('divi_childs_list_li');

    //***

    divi_remove_class_widget();
    divi_checkboxes_slide();
}

var divi_submit_link_locked = false;
function divi_submit_link(link) {

    if (divi_submit_link_locked) {
	return;
    }

    divi_submit_link_locked = true;
    divi_show_info_popup(divi_lang_loading);

    if (divi_is_ajax === 1 && !divi_ajax_redraw) {
	divi_ajax_first_done = true;
	var data = {
	    action: "divi_draw_products",
	    link: link,
	    page: divi_ajax_page_num,
	    shortcode: jQuery('#divi_results_by_ajax').data('shortcode'),
	    divi_shortcode: jQuery('div.divi').data('shortcode')
	};
	jQuery.post(divi_ajaxurl, data, function (content) {
	    content = jQuery.parseJSON(content);
	    if (jQuery('.divi_results_by_ajax_shortcode').length) {
		jQuery('#divi_results_by_ajax').replaceWith(content.products);
	    } else {
		jQuery('.divi_shortcode_output').replaceWith(content.products);
	    }

	    jQuery('div.divi_redraw_zone').replaceWith(jQuery(content.form).find('.divi_redraw_zone'));
	    divi_draw_products_top_panel();
	    divi_mass_reinit();
	    divi_submit_link_locked = false;
	    //removing id divi_results_by_ajax - multi in ajax mode sometimes
	    //when uses shorcode divi_products in ajax and in settings try ajaxify shop is Yes
	    jQuery.each(jQuery('#divi_results_by_ajax'), function (index, item) {
		if (index == 0) {
		    return;
		}

		jQuery(item).removeAttr('id');
	    });
	    //infinite scroll
	    divi_infinite();
	    //*** script after ajax loading here
	    divi_js_after_ajax_done();
	});

    } else {

	if (divi_ajax_redraw) {
	    //redrawing [divi ajax_redraw=1] only
	    var data = {
		action: "divi_draw_products",
		link: link,
		page: 1,
		shortcode: 'divi_nothing', //we do not need get any products, seacrh form data only
		divi_shortcode: jQuery('div.divi').eq(0).data('shortcode')
	    };
	    jQuery.post(divi_ajaxurl, data, function (content) {
		content = jQuery.parseJSON(content);
		jQuery('div.divi_redraw_zone').replaceWith(jQuery(content.form).find('.divi_redraw_zone'));
		divi_mass_reinit();
		divi_submit_link_locked = false;
	    });
	} else {

	    window.location = link;
	    divi_show_info_popup(divi_lang_loading);
	}
    }
}

function divi_remove_empty_elements() {
    // lets check for empty drop-downs
    jQuery.each(jQuery('.divi_container select'), function (index, select) {
	var size = jQuery(select).find('option').size();
	if (size === 0) {
	    jQuery(select).parents('.divi_container').remove();
	}
    });
    //+++
    // lets check for empty checkboxes, radio, color conatiners
    jQuery.each(jQuery('ul.divi_list'), function (index, ch) {
	var size = jQuery(ch).find('li').size();
	if (size === 0) {
	    jQuery(ch).parents('.divi_container').remove();
	}
    });
}

function divi_get_submit_link() {
//filter divi_current_values values
    if (divi_is_ajax) {
	divi_current_values.page = divi_ajax_page_num;
    }
//+++
    if (Object.keys(divi_current_values).length > 0) {
	jQuery.each(divi_current_values, function (index, value) {
	    if (index == sdivi_search_slug) {
		delete divi_current_values[index];
	    }
	    if (index == 's') {
		delete divi_current_values[index];
	    }
	    if (index == 'product') {
//for single product page (when no permalinks)
		delete divi_current_values[index];
	    }
	    if (index == 'really_curr_tax') {
		delete divi_current_values[index];
	    }
	});
    }


    //***
    if (Object.keys(divi_current_values).length === 2) {
	if (('min_price' in divi_current_values) && ('max_price' in divi_current_values)) {
	    var l = divi_current_page_link + '?min_price=' + divi_current_values.min_price + '&max_price=' + divi_current_values.max_price;
	    if (divi_is_ajax) {
		history.pushState({}, "", l);
	    }
	    return l;
	}
    }



    //***

    if (Object.keys(divi_current_values).length === 0) {
	if (divi_is_ajax) {
	    history.pushState({}, "", divi_current_page_link);
	}
	return divi_current_page_link;
    }
    //+++
    if (Object.keys(divi_really_curr_tax).length > 0) {
	divi_current_values['really_curr_tax'] = divi_really_curr_tax.term_id + '-' + divi_really_curr_tax.taxonomy;
    }
    //+++
    var link = divi_current_page_link + "?" + sdivi_search_slug + "=1";
    //console.log(divi_current_page_link);
    //just for the case when no permalinks enabled
    if (!divi_is_permalink) {

	if (divi_redirect.length > 0) {
	    link = divi_redirect + "?" + sdivi_search_slug + "=1";
	    if (divi_current_values.hasOwnProperty('page_id')) {
		delete divi_current_values.page_id;
	    }
	} else {
	    link = location.protocol + '//' + location.host + "?" + sdivi_search_slug + "=1";
	    /*
	     if (!divi_is_ajax) {
	     link = location.protocol + '//' + location.host + "?" + sdivi_search_slug + "=1";
	     }
	     
	     if (divi_current_values.hasOwnProperty('page_id')) {
	     link = location.protocol + '//' + location.host + "?" + sdivi_search_slug + "=1";
	     }
	     */
	}
    }
    //console.log(link);
    //throw('STOP!');

    //any trash for different sites, useful for quick support
    var divi_exclude_accept_array = ['path'];

    if (Object.keys(divi_current_values).length > 0) {
	jQuery.each(divi_current_values, function (index, value) {
	    if (index == 'page' && divi_is_ajax) {
		index = 'paged';//for right pagination if copy/paste this link and send somebody another by email for example
	    }

	    //http://www.dev.woocommerce-filter.com/?sdivi=1&divi_author=3&divi_sku&divi_text=single
	    //avoid links where values is empty
	    if (typeof value !== 'undefined') {
		if ((typeof value && value.length > 0) || typeof value == 'number')
		{
		    if (jQuery.inArray(index, divi_exclude_accept_array) == -1) {
			link = link + "&" + index + "=" + value;
		    }
		}
	    }

	});
    }

    //+++
    //remove wp pagination like 'page/2'
    link = link.replace(new RegExp(/page\/(\d+)\//), "");
    if (divi_is_ajax) {
	history.pushState({}, "", link);

    }

    //throw ("STOP!");
    return link;
}



function divi_show_info_popup(text) {
    if (divi_overlay_skin == 'default') {
	jQuery("#divi_html_buffer").text(text);
	jQuery("#divi_html_buffer").fadeTo(200, 0.9);
    } else {
	//http://jxnblk.com/loading/
	switch (divi_overlay_skin) {
	    case 'loading-balls':
	    case 'loading-bars':
	    case 'loading-bubbles':
	    case 'loading-cubes':
	    case 'loading-cylon':
	    case 'loading-spin':
	    case 'loading-spinning-bubbles':
	    case 'loading-spokes':
		jQuery('body').plainOverlay('show', {progress: function () {
			return jQuery('<div id="divi_svg_load_container"><img style="height: 100%;width: 100%" src="' + divi_link + 'img/loading-master/' + divi_overlay_skin + '.svg" alt=""></div>');
		    }});
		break;
	    default:
		jQuery('body').plainOverlay('show', {duration: -1});
		break;
	}
    }
}


function divi_hide_info_popup() {
    if (divi_overlay_skin == 'default') {
	window.setTimeout(function () {
	    jQuery("#divi_html_buffer").fadeOut(400);
	}, 200);
    } else {
	jQuery('body').plainOverlay('hide');
    }
}

function divi_draw_products_top_panel() {

    if (divi_is_ajax) {
	jQuery('#divi_results_by_ajax').prev('.divi_products_top_panel').remove();
    }

    var panel = jQuery('.divi_products_top_panel');

    panel.html('');
    if (Object.keys(divi_current_values).length > 0) {
	panel.show();
	panel.html('<ul></ul>');
	var is_price_in = false;
	//lets show this on the panel
	jQuery.each(divi_current_values, function (index, value) {

	    //lets filter data for the panel
	    if (jQuery.inArray(index, divi_accept_array) == -1) {
		return;
	    }


	    //***


	    if ((index == 'min_price' || index == 'max_price') && is_price_in) {
		return;
	    }

	    if ((index == 'min_price' || index == 'max_price') && !is_price_in) {
		is_price_in = true;
		index = 'price';
		value = divi_lang_pricerange;
	    }
	    //+++
	    value = value.toString().trim();
	    if (value.search(',')) {
		value = value.split(',');
	    }
	    //+++
	    jQuery.each(value, function (i, v) {
		if (index == 'page') {
		    return;
		}

		if (index == 'post_type') {
		    return;
		}

		var txt = v;
		if (index == 'orderby') {
		    if (divi_lang[v] !== undefined) {
			txt = divi_lang.orderby + ': ' + divi_lang[v];
		    } else {
			txt = divi_lang.orderby + ': ' + v;
		    }
		} else if (index == 'perpage') {
		    txt = divi_lang.perpage;
		} else if (index == 'price') {
		    txt = divi_lang.pricerange;
		} else {

		    var is_in_custom = false;
		    if (Object.keys(divi_lang_custom).length > 0) {
			jQuery.each(divi_lang_custom, function (i, tt) {
			    if (i == index) {
				is_in_custom = true;
				txt = tt;
				if (index == 'divi_sku') {
				    txt += " " + v;//because search by SKU can by more than 1 value
				}
			    }
			});
		    }

		    if (!is_in_custom) {
			try {
			    //txt = jQuery('.divi_n_' + index + '_' + v).val();
			    txt = jQuery("input[data-anchor='divi_n_" + index + '_' + v + "']").val();
			} catch (e) {
			    console.log(e);
			}

			if (typeof txt === 'undefined')
			{
			    txt = v;
			}
		    }


		    /* hidden feature
		     if (jQuery('input[name=divi_t_' + index + ']').length > 0) {
		     txt = jQuery('input[name=divi_t_' + index + ']').val() + ': ' + txt;
		     }
		     */


		}

		panel.find('ul').append(
			jQuery('<li>').append(
			jQuery('<a>').attr('href', v).attr('data-tax', index).append(
			jQuery('<span>').attr('class', 'divi_remove_ppi').append(txt)
			)));

	    });


	});
    }


    if (jQuery(panel).find('li').size() == 0 || !jQuery('.divi_products_top_panel').length) {
	panel.hide();
    }

    //+++
    jQuery('.divi_remove_ppi').parent().click(function () {
	var tax = jQuery(this).data('tax');
	var name = jQuery(this).attr('href');

	//***

	if (tax != 'price') {
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
	} else {
	    delete divi_current_values['min_price'];
	    delete divi_current_values['max_price'];
	}

	divi_ajax_page_num = 1;
	//if (divi_autosubmit)
	{
	    divi_submit_link(divi_get_submit_link());
	}
	jQuery('.divi_products_top_panel').find("[data-tax='" + tax + "'][href='" + name + "']").hide(333);
	return false;
    });

}

//control conditions if proucts shortcode uses on the page
function divi_shortcode_observer() {
    if (jQuery('.divi_shortcode_output').length) {
	divi_current_page_link = location.protocol + '//' + location.host + location.pathname;
    }

    if (jQuery('#divi_results_by_ajax').length) {
	divi_is_ajax = 1;
    }
}



function divi_init_beauty_scroll() {
    if (divi_use_beauty_scroll) {
	try {
	    var anchor = ".divi_section_scrolled, .divi_sid_auto_shortcode .divi_container_radio .divi_block_html_items, .divi_sid_auto_shortcode .divi_container_checkbox .divi_block_html_items, .divi_sid_auto_shortcode .divi_container_label .divi_block_html_items";
	    jQuery("" + anchor).mCustomScrollbar('destroy');
	    jQuery("" + anchor).mCustomScrollbar({
		scrollButtons: {
		    enable: true
		},
		advanced: {
		    updateOnContentResize: true,
		    updateOnBrowserResize: true
		},
		theme: "dark-2",
		horizontalScroll: false,
		mouseWheel: true,
		scrollType: 'pixels',
		contentTouchScroll: true
	    });
	} catch (e) {
	    console.log(e);
	}
    }
}

//just for inbuilt price range widget
function divi_remove_class_widget() {
    jQuery('.divi_container_inner').find('.widget').removeClass('widget');
}

function divi_init_show_auto_form() {
    jQuery('.divi_show_auto_form').unbind('click');
    jQuery('.divi_show_auto_form').click(function () {
	var _this = this;
	jQuery(_this).addClass('divi_hide_auto_form').removeClass('divi_show_auto_form');
	jQuery(".divi_auto_show").show().animate(
		{
		    height: (jQuery(".divi_auto_show_indent").height() + 20) + "px",
		    opacity: 1
		}, 377, function () {
	    //jQuery(_this).text(divi_lang_hide_products_filter);
	    divi_init_hide_auto_form();
	    jQuery('.divi_auto_show').removeClass('divi_overflow_hidden');
	    jQuery('.divi_auto_show_indent').removeClass('divi_overflow_hidden');
	    jQuery(".divi_auto_show").height('auto');
	});


	return false;
    });


}

function divi_init_hide_auto_form() {
    jQuery('.divi_hide_auto_form').unbind('click');
    jQuery('.divi_hide_auto_form').click(function () {
	var _this = this;
	jQuery(_this).addClass('divi_show_auto_form').removeClass('divi_hide_auto_form');
	jQuery(".divi_auto_show").show().animate(
		{
		    height: "1px",
		    opacity: 0
		}, 377, function () {
	    //jQuery(_this).text(divi_lang_show_products_filter);
	    jQuery('.divi_auto_show').addClass('divi_overflow_hidden');
	    jQuery('.divi_auto_show_indent').addClass('divi_overflow_hidden');
	    divi_init_show_auto_form();
	});

	return false;
    });


}

//if we have mode - child checkboxes closed - append openers buttons by js
function divi_checkboxes_slide() {
    if (divi_checkboxes_slide_flag == true) {
	var childs = jQuery('ul.divi_childs_list');
	if (childs.size()) {
	    jQuery.each(childs, function (index, ul) {

		if (jQuery(ul).parents('.divi_no_close_childs').length) {
		    return;
		}

		var span_class = 'divi_is_closed';
		if (jQuery(ul).find('input[type=checkbox],input[type=radio]').is(':checked')) {
		    jQuery(ul).show();
		    span_class = 'divi_is_opened';
		}

		jQuery(ul).before('<a href="javascript:void(0);" class="divi_childs_list_opener"><span class="' + span_class + '"></span></a>');
	    });

	    jQuery.each(jQuery('a.divi_childs_list_opener'), function (index, a) {
		jQuery(a).click(function () {
		    var span = jQuery(this).find('span');
		    if (span.hasClass('divi_is_closed')) {
			//lets open
			jQuery(this).parent().find('ul.divi_childs_list').first().show(333);
			span.removeClass('divi_is_closed');
			span.addClass('divi_is_opened');
		    } else {
			//lets close
			jQuery(this).parent().find('ul.divi_childs_list').first().hide(333);
			span.removeClass('divi_is_opened');
			span.addClass('divi_is_closed');
		    }

		    return false;
		});
	    });
	}
    }
}

function divi_init_ion_sliders() {
    jQuery.each(jQuery('.divi_range_slider'), function (index, input) {
	try {
	    jQuery(input).ionRangeSlider({
		min: jQuery(input).data('min'),
		max: jQuery(input).data('max'),
		from: jQuery(input).data('min-now'),
		to: jQuery(input).data('max-now'),
		type: 'double',
		prefix: jQuery(input).data('slider-prefix'),
		postfix: jQuery(input).data('slider-postfix'),
		prettify: true,
		hideMinMax: false,
		hideFromTo: false,
		grid: true,
		step: jQuery(input).data('step'),
		onFinish: function (ui) {
		    divi_current_values.min_price = parseInt(ui.from, 10);
		    divi_current_values.max_price = parseInt(ui.to, 10);
		    //woocs adaptation
		    if (typeof woocs_current_currency !== 'undefined') {
			divi_current_values.min_price = Math.ceil(divi_current_values.min_price / parseFloat(woocs_current_currency.rate));
			divi_current_values.max_price = Math.ceil(divi_current_values.max_price / parseFloat(woocs_current_currency.rate));
		    }
		    //***
		    divi_ajax_page_num = 1;
		    //jQuery(input).within('.divi').length -> if slider is as shortcode
		    if (divi_autosubmit || jQuery(input).within('.divi').length == 0) {
			divi_submit_link(divi_get_submit_link());
		    }
		    return false;
		}
	    });
	} catch (e) {

	}
    });
}

function divi_init_native_woo_price_filter() {
    jQuery('.widget_price_filter form').unbind('submit');
    jQuery('.widget_price_filter form').submit(function () {
	var min_price = jQuery(this).find('.price_slider_amount #min_price').val();
	var max_price = jQuery(this).find('.price_slider_amount #max_price').val();
	divi_current_values.min_price = min_price;
	divi_current_values.max_price = max_price;
	divi_ajax_page_num = 1;
	if (divi_autosubmit || jQuery(input).within('.divi').length == 0) {
	    //comment next code row to avoid endless ajax requests
	    divi_submit_link(divi_get_submit_link());
	}
	return false;
    });

}

//we need after ajax redrawing of the search form
function divi_reinit_native_woo_price_filter() {

    // woocommerce_price_slider_params is required to continue, ensure the object exists
    if (typeof woocommerce_price_slider_params === 'undefined') {
	return false;
    }

    // Get markup ready for slider
    jQuery('input#min_price, input#max_price').hide();
    jQuery('.price_slider, .price_label').show();

    // Price slider uses jquery ui
    var min_price = jQuery('.price_slider_amount #min_price').data('min'),
	    max_price = jQuery('.price_slider_amount #max_price').data('max'),
	    current_min_price = parseInt(min_price, 10),
	    current_max_price = parseInt(max_price, 10);

    if (divi_current_values.hasOwnProperty('min_price')) {
	current_min_price = parseInt(divi_current_values.min_price, 10);
	current_max_price = parseInt(divi_current_values.max_price, 10);
    } else {
	if (woocommerce_price_slider_params.min_price) {
	    current_min_price = parseInt(woocommerce_price_slider_params.min_price, 10);
	}
	if (woocommerce_price_slider_params.max_price) {
	    current_max_price = parseInt(woocommerce_price_slider_params.max_price, 10);
	}
    }

    //***

    var currency_symbol = woocommerce_price_slider_params.currency_symbol;
    if (typeof currency_symbol == undefined) {
	currency_symbol = woocommerce_price_slider_params.currency_format_symbol;
    }
    jQuery(document.body).bind('price_slider_create price_slider_slide', function (event, min, max) {

	if (typeof woocs_current_currency !== 'undefined') {
	    var label_min = min;
	    var label_max = max;


	    if (woocs_current_currency.rate !== 1) {
		label_min = Math.ceil(label_min * parseFloat(woocs_current_currency.rate));
		label_max = Math.ceil(label_max * parseFloat(woocs_current_currency.rate));
	    }

	    //+++
	    label_min = number_format(label_min, 2, '.', ',');
	    label_max = number_format(label_max, 2, '.', ',');
	    if (jQuery.inArray(woocs_current_currency.name, woocs_array_no_cents) || woocs_current_currency.hide_cents == 1) {
		label_min = label_min.replace('.00', '');
		label_max = label_max.replace('.00', '');
	    }
	    //+++


	    if (woocs_current_currency.position === 'left') {

		jQuery('.price_slider_amount span.from').html(currency_symbol + label_min);
		jQuery('.price_slider_amount span.to').html(currency_symbol + label_max);

	    } else if (woocs_current_currency.position === 'left_space') {

		jQuery('.price_slider_amount span.from').html(currency_symbol + " " + label_min);
		jQuery('.price_slider_amount span.to').html(currency_symbol + " " + label_max);

	    } else if (woocs_current_currency.position === 'right') {

		jQuery('.price_slider_amount span.from').html(label_min + currency_symbol);
		jQuery('.price_slider_amount span.to').html(label_max + currency_symbol);

	    } else if (woocs_current_currency.position === 'right_space') {

		jQuery('.price_slider_amount span.from').html(label_min + " " + currency_symbol);
		jQuery('.price_slider_amount span.to').html(label_max + " " + currency_symbol);

	    }

	} else {

	    if (woocommerce_price_slider_params.currency_pos === 'left') {

		jQuery('.price_slider_amount span.from').html(currency_symbol + min);
		jQuery('.price_slider_amount span.to').html(currency_symbol + max);

	    } else if (woocommerce_price_slider_params.currency_pos === 'left_space') {

		jQuery('.price_slider_amount span.from').html(currency_symbol + ' ' + min);
		jQuery('.price_slider_amount span.to').html(currency_symbol + ' ' + max);

	    } else if (woocommerce_price_slider_params.currency_pos === 'right') {

		jQuery('.price_slider_amount span.from').html(min + currency_symbol);
		jQuery('.price_slider_amount span.to').html(max + currency_symbol);

	    } else if (woocommerce_price_slider_params.currency_pos === 'right_space') {

		jQuery('.price_slider_amount span.from').html(min + ' ' + currency_symbol);
		jQuery('.price_slider_amount span.to').html(max + ' ' + currency_symbol);

	    }
	}

	jQuery(document.body).trigger('price_slider_updated', [min, max]);
    });

    jQuery('.price_slider').slider({
	range: true,
	animate: true,
	min: min_price,
	max: max_price,
	values: [current_min_price, current_max_price],
	create: function () {

	    jQuery('.price_slider_amount #min_price').val(current_min_price);
	    jQuery('.price_slider_amount #max_price').val(current_max_price);

	    jQuery(document.body).trigger('price_slider_create', [current_min_price, current_max_price]);
	},
	slide: function (event, ui) {

	    jQuery('input#min_price').val(ui.values[0]);
	    jQuery('input#max_price').val(ui.values[1]);

	    jQuery(document.body).trigger('price_slider_slide', [ui.values[0], ui.values[1]]);
	},
	change: function (event, ui) {
	    jQuery(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
	}
    });


    //***
    divi_init_native_woo_price_filter();
}

function divi_mass_reinit() {
    divi_remove_empty_elements();
    divi_open_hidden_li();
    divi_init_search_form();
    divi_hide_info_popup();
    divi_init_beauty_scroll();
    divi_init_ion_sliders();
    divi_reinit_native_woo_price_filter();//native woo price range slider reinit
    divi_recount_text_price_filter();
    divi_draw_products_top_panel();
}

function divi_recount_text_price_filter() {
    //change value in textinput price filter if WOOCS is installed
    if (typeof woocs_current_currency !== 'undefined') {
	jQuery.each(jQuery('.divi_price_filter_txt_from, .divi_price_filter_txt_to'), function (i, item) {
	    jQuery(this).val(Math.ceil(jQuery(this).data('value')));
	});
    }
}

function divi_init_toggles() {
    jQuery('.divi_front_toggle').life('click', function () {
	if (jQuery(this).data('condition') == 'opened') {
	    jQuery(this).removeClass('divi_front_toggle_opened');
	    jQuery(this).addClass('divi_front_toggle_closed');
	    jQuery(this).data('condition', 'closed');
	    if (divi_toggle_type == 'text') {
		jQuery(this).text(divi_toggle_closed_text);
	    } else {
		jQuery(this).find('img').prop('src', divi_toggle_closed_image);
	    }
	} else {
	    jQuery(this).addClass('divi_front_toggle_opened');
	    jQuery(this).removeClass('divi_front_toggle_closed');
	    jQuery(this).data('condition', 'opened');
	    if (divi_toggle_type == 'text') {
		jQuery(this).text(divi_toggle_opened_text);
	    } else {
		jQuery(this).find('img').prop('src', divi_toggle_opened_image);
	    }
	}


	jQuery(this).parents('.divi_container_inner').find('.divi_block_html_items').toggle(500);
	return false;
    });
}

//for "Show more" blocks
function divi_open_hidden_li() {
    if (jQuery('.divi_open_hidden_li_btn').length > 0) {
	jQuery.each(jQuery('.divi_open_hidden_li_btn'), function (i, b) {
	    if (jQuery(b).parents('ul').find('li.divi_hidden_term input[type=checkbox],li.divi_hidden_term input[type=radio]').is(':checked')) {
		jQuery(b).trigger('click');
	    }
	});
    }
}

//http://stackoverflow.com/questions/814613/how-to-read-get-data-from-a-url-using-javascript
function $_divi_GET(q, s) {
    s = (s) ? s : window.location.search;
    var re = new RegExp('&' + q + '=([^&]*)', 'i');
    return (s = s.replace(/^\?/, '&').match(re)) ? s = s[1] : s = '';
}

function divi_parse_url(url) {
    var pattern = RegExp("^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\\?([^#]*))?(#(.*))?");
    var matches = url.match(pattern);
    return {
	scheme: matches[2],
	authority: matches[4],
	path: matches[5],
	query: matches[7],
	fragment: matches[9]
    };
}


//      divi price radio;
function divi_price_filter_radio_init() {
    if (icheck_skin != 'none') {
	jQuery('.divi_price_filter_radio').iCheck('destroy');

	jQuery('.divi_price_filter_radio').iCheck({
	    radioClass: 'iradio_' + icheck_skin.skin + '-' + icheck_skin.color,
	    //radioClass: 'iradio_square-green'        
	});

	jQuery('.divi_price_filter_radio').siblings('div').removeClass('checked');

	jQuery('.divi_price_filter_radio').unbind('ifChecked');
	jQuery('.divi_price_filter_radio').on('ifChecked', function (event) {
	    jQuery(this).attr("checked", true);
	    jQuery('.divi_radio_price_reset').removeClass('divi_radio_term_reset_visible');
	    jQuery(this).parents('.divi_list').find('.divi_radio_price_reset').removeClass('divi_radio_term_reset_visible');
	    jQuery(this).parents('.divi_list').find('.divi_radio_price_reset').hide();
	    jQuery(this).parents('li').eq(0).find('.divi_radio_price_reset').eq(0).addClass('divi_radio_term_reset_visible');
	    var val = jQuery(this).val();
	    if (parseInt(val, 10) == -1) {
		delete divi_current_values.min_price;
		delete divi_current_values.max_price;
		jQuery(this).removeAttr('checked');
		jQuery(this).siblings('.divi_radio_price_reset').removeClass('divi_radio_term_reset_visible');
	    } else {
		var val = val.split("-");
		divi_current_values.min_price = val[0];
		divi_current_values.max_price = val[1];
		jQuery(this).siblings('.divi_radio_price_reset').addClass('divi_radio_term_reset_visible');
		jQuery(this).attr("checked", true);
	    }
	    if (divi_autosubmit || jQuery(this).within('.divi').length == 0) {
		divi_submit_link(divi_get_submit_link());
	    }
	});

    } else {
	jQuery('.divi_price_filter_radio').life('change', function () {
	    var val = jQuery(this).val();
	    jQuery('.divi_radio_price_reset').removeClass('divi_radio_term_reset_visible');
	    if (parseInt(val, 10) == -1) {
		delete divi_current_values.min_price;
		delete divi_current_values.max_price;
		jQuery(this).removeAttr('checked');
		jQuery(this).siblings('.divi_radio_price_reset').removeClass('divi_radio_term_reset_visible');
	    } else {
		var val = val.split("-");
		divi_current_values.min_price = val[0];
		divi_current_values.max_price = val[1];
		jQuery(this).siblings('.divi_radio_price_reset').addClass('divi_radio_term_reset_visible');
		jQuery(this).attr("checked", true);
	    }
	    if (divi_autosubmit || jQuery(this).within('.divi').length == 0) {
		divi_submit_link(divi_get_submit_link());
	    }
	});
    }
    //***
    jQuery('.divi_radio_price_reset').click(function () {
	delete divi_current_values.min_price;
	delete divi_current_values.max_price;
	jQuery(this).siblings('div').removeClass('checked');
	jQuery(this).parents('.divi_list').find('input[type=radio]').removeAttr('checked');
	//jQuery(this).remove();
	jQuery(this).removeClass('divi_radio_term_reset_visible');
	if (divi_autosubmit) {
	    divi_submit_link(divi_get_submit_link());
	}
	return false;
    });
}
//    END  divi price radio;



//compatibility with YITH Infinite Scrolling
function divi_serialize(serializedString) {
    var str = decodeURI(serializedString);
    var pairs = str.split('&');
    var obj = {}, p, idx, val;
    for (var i = 0, n = pairs.length; i < n; i++) {
	p = pairs[i].split('=');
	idx = p[0];

	if (idx.indexOf("[]") == (idx.length - 2)) {
	    // Eh um vetor
	    var ind = idx.substring(0, idx.length - 2)
	    if (obj[ind] === undefined) {
		obj[ind] = [];
	    }
	    obj[ind].push(p[1]);
	} else {
	    obj[idx] = p[1];
	}
    }
    return obj;
}


//compatibility with YITH Infinite Scrolling
function divi_infinite() {

    if (typeof yith_infs === 'undefined') {
	return;
    }
    
  
    //***
    var infinite_scroll1 = {
	//'nextSelector': ".divi_infinity .nav-links .next",
        'nextSelector': '.woocommerce-pagination li .next',
	'navSelector': yith_infs.navSelector,
	'itemSelector': yith_infs.itemSelector,
	'contentSelector': yith_infs.contentSelector,
	'loader': '<img src="' + yith_infs.loader + '">',
	'is_shop': yith_infs.shop
    };
var curr_l = window.location.href;
var curr_link = curr_l.split('?');
var get="";
    if (curr_link[1] != undefined) {
	var temp = divi_serialize(curr_link[1]);
	delete temp['paged'];
	get = decodeURIComponent(jQuery.param(temp))
    }
    
  var page_link = jQuery('.woocommerce-pagination li .next').attr("href");
    //console.log(page_link);
    if(page_link==undefined){
       page_link=curr_link+"page/1/"
    }
console.log(page_link );
var ajax_link=page_link.split('?');
var page="";
    if (ajax_link[1] != undefined) {
	var temp1 = divi_serialize(ajax_link[1]);
        if(temp1['paged']!=undefined){
          page= "page/"+ temp1['paged']+"/"; 
        }
    }

    page_link = curr_link[0] +page+ '?' + get;
    //console.log(page_link);
    jQuery('.woocommerce-pagination li .next').attr('href', page_link);
    
    jQuery(window).unbind("yith_infs_start"), jQuery(yith_infs.contentSelector).yit_infinitescroll(infinite_scroll1)
}
//End infinity scroll


