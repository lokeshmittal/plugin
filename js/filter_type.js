/**
 * diviTabs v1.0.0
 */
;
(function ($, window) {

    'use strict';

    $.fn.diviTabs = function (options) {

	if (!this.length)
	    return;

	return this.each(function () {

	    var $this = $(this);

	    ({
		init: function () {
		    this.tabsNav = $this.children('nav');
		    this.items = $this.children('.content-wrap').children('section');
		    this._show();
		    this._initEvents();
		},
		_initEvents: function () {
		    var self = this;
		    this.tabsNav.on('click', 'a', function (e) {
			e.preventDefault();
			self._show($(this));
		    });
		},
		_show: function (element) {

		    if (element == undefined) {
			this.firsTab = this.tabsNav.find('li').first();
			this.firstSection = this.items.first();

			if (!this.firsTab.hasClass('tab-current')) {
			    this.firsTab.addClass('tab-current');
			}

			if (!this.firstSection.hasClass('content-current')) {
			    this.firstSection.addClass('content-current');
			}
		    }

		    var $this = $(element),
			    $to = $($this.attr('href'));

		    if ($to.length) {
			$this.parent('li').siblings().removeClass().end().addClass('tab-current');
			$to.siblings().removeClass().end().addClass('content-current');
		    }

		}

	    }).init();

	});
    };

})(jQuery, window);


/*	Popup
 /* --------------------------------------------- */

/**
 * diviPopupPrepare v1.0.0
 */
(function ($) {

    $.divi_popup_prepare = function (el, options) {
	this.el = el;
	this.options = $.extend({}, $.divi_popup_prepare.DEFAULTS, options);
	this.init();
    };

    $.divi_popup_prepare.DEFAULTS = {};
    $.divi_popup_prepare.openInstance = [];

    $.divi_popup_prepare.prototype = {
	init: function () {

	    $.divi_popup_prepare.openInstance.unshift(this);

	    var base = this;
	    base.scope = false;
	    base.body = $('body');
	    base.wrap = $('#wpwrap');
	    base.modal = $('<div class="divi-modal divi-style"></div>');
	    base.overlay = $('<div class="divi-modal-backdrop"></div>');
	    base.container = $('.divi-tabs');
	    base.instance = $.divi_popup_prepare.openInstance.length;
	    base.namespace = '.popup_modal_' + base.instance;

	    base.support = {
		touch: Modernizr.touch
	    };
	    base.eventtype = base.support.touch ? 'touchstart' : 'click';
	    base.loadPopup();
	},
	loadPopup: function () {
	    this.container.on(this.eventtype, this.el, $.proxy(function (e) {
		if (!this.scope) {
		    this.body.addClass('divi-noscroll');
		    this.openPopup(e);
		}
		this.scope = true;
	    }, this));
	},
	openPopup: function (e) {
	    e.preventDefault();

	    var base = this,
		    el = $(e.target),
		    data = el.data();

	    if (el.hasClass('js_divi_options')) {
		//for 'by-' items
		var key = data['key'],
			name = data['name'] + ' [' + data['key'] + ']',
			type = false,
			info = $("#divi-modal-content-" + key),
			content = info.html();
	    } else {
		//for taxonomies
		var type = el.parent().find('.divi_select_tax_type').val();
		var key = data['taxonomy'];
		var name = data['taxonomyName'] + ' [' + key + ']';
		var info = $("#divi-modal-content");
		info.find('.divi_option_container').hide();
		info.find('.divi_option_all').show();
		info.find('.divi_option_' + type).show();
		var content = info.html();
	    }

	    base.create_html(key, name, content, info, type);
	    base.add_behavior(key, name, content, info, type);
	},
	create_html: function (key, name, content, info, type) {

	    var base = this,
		    title = name ? '<h3 class="divi-modal-title"> ' + name + '</h3>' : '',
		    loading = ' preloading ',
		    output = '<div class="divi-modal-inner">';
	    output += '<div class="divi-modal-inner-header">' + title + '<a href="javascript:void(0)" class="divi-modal-close"></a></div>';
	    output += '<div class="divi-modal-inner-content ' + loading + '">' + content + '</div>';
	    output += '<div class="divi-modal-inner-footer">';
	    output += '<a href="javascript:void(0)" class="divi-modal-save button button-primary button-large">Apply</a>';
	    output += '</div>';
	    output += '</div>';

	    base.wrap.append(base.modal).append(base.overlay);
	    base.modal.html(output);
	    base.modal.find('.divi-modal-inner-content').removeClass('preloading');

	    var multiplier = base.instance - 1,
		    old = parseInt(base.modal.css('zIndex'), 10);
	    base.modal.css({margin: (30 * multiplier), zIndex: (old + multiplier + 1)});
	    base.overlay.css({zIndex: (old + multiplier)});

	    base.on_load_callback(key, name, content, info, type);
	},
	closeModal: function () {
	    var base = this;

	    $.divi_popup_prepare.openInstance.shift();

	    base.modal.remove();
	    base.overlay.remove();

	    base.body.removeClass('divi-noscroll');
	    base.scope = false;
	},
	add_behavior: function (key, name, content, info, type) {
	    var base = this;

	    base.modal.on(base.eventtype + base.namespace, '.divi-modal-save', function (e) {
		e.preventDefault();
		base.on_close_callback(key, name, content, info, type);
		base.closeModal();
	    });

	    base.modal.on(base.eventtype + base.namespace, '.divi-modal-close', function (e) {
		e.preventDefault();
		base.closeModal();
	    });

	    base.overlay.on(base.eventtype + base.namespace, function (e) {
		e.preventDefault();
		base.closeModal();
	    });

	},
	on_load_callback: function (key, name, content, info, type) {

	    if (type) {

		info.find('.divi_option_container').hide();
		info.find('.divi_option_all').show();
		info.find('.divi_option_' + type).show();

		$.each($('.divi_popup_option', this.modal), function () {
		    var option = $(this).data('option'),
			    val = $('input[name="divi_settings[' + option + '][' + key + ']"]').val();
		    $(this).val(val);
		});

	    } else {

		$.each($('.divi_popup_option', this.modal), function () {
		    var option = $(this).data('option'),
			    val = $('input[name="divi_settings[' + key + '][' + option + ']"]').val();
		    $(this).val(val);
		});

	    }

	},
	on_close_callback: function (key, name, content, info, type) {

	    if (type) {

		$.each($('.divi_popup_option', this.modal), function () {
		    var option = $(this).data('option'), val = $(this).val();
		    $('input[name="divi_settings[' + option + '][' + key + ']"]').val(val);
		});

	    } else {

		$.each($('.divi_popup_option', this.modal), function () {
		    var option = $(this).data('option'), val = $(this).val();
		    $('input[name="divi_settings[' + key + '][' + option + ']"]').val(val);
		});

	    }

	}
    };

})(jQuery);

var divi_sort_order = [];

(function ($) {

    jQuery.fn.life = function (types, data, fn) {
	jQuery(this.context).on(types, this.selector, data, fn);
	return this;
    };

    $.divi_mod = $.divi_mod || {};

    $.divi_mod.popup_prepare = function () {
	new $.divi_popup_prepare('.js_divi_options');
	new $.divi_popup_prepare('.js_divi_add_options');
    };

    $(function () {

	$('.divi-tabs').diviTabs();

	$.divi_mod.popup_prepare();

	try {
	    $('.divi-color-picker').wpColorPicker();
	} catch (e) {
	    console.log(e);
	}

	$("#divi_options").sortable({
	    update: function (event, ui) {
		divi_sort_order = [];
		$.each($('#divi_options').children('li'), function (index, value) {
		    var key = $(this).data('key');
		    divi_sort_order.push(key);
		});
		$('input[name="divi_settings[items_order]"]').val(divi_sort_order.toString());
	    },
	    opacity: 0.8,
	    cursor: "crosshair",
	    handle: '.divi_drag_and_drope',
	    placeholder: 'divi-options-highlight'
	});


	//options saving
	$('#mainform').submit(function () {
	    $('input[name=save]').hide();
	    divi_show_info_popup(divi_lang_saving);
	    var data = {
		action: "divi_save_options",
		formdata: $(this).serialize()
	    };
	    $.post(ajaxurl, data, function () {
		window.location = divi_save_link;
	    });

	    return false;
	});


	$('.divi_reset_order').click(function () {
	    if (prompt('To reset order of items write word "reset". The page will be reloaded!') == 'reset') {
		$('input[name="divi_settings[items_order]"]').val('');
		$('#mainform').submit();
	    }
	});


	$('.js_cache_count_data_clear').click(function () {
	    $(this).next('span').html('clearing ...');
	    var _this = this;
	    var data = {
		action: "divi_cache_count_data_clear"
	    };
	    $.post(ajaxurl, data, function () {
		$(_this).next('span').html('cleared!');
	    });

	    return false;
	});


	$('.js_cache_terms_clear').click(function () {
	    $(this).next('span').html('clearing ...');
	    var _this = this;
	    var data = {
		action: "divi_cache_terms_clear"
	    };
	    $.post(ajaxurl, data, function () {
		$(_this).next('span').html('cleared!');
	    });

	    return false;
	});

	//in extension tab
	$('#divi_manipulate_with_ext').change(function () {
	    var val = parseInt($(this).val(), 10);
	    switch (val) {
		case 1:
		    $('ul.divi_extensions li').hide();
		    $('ul.divi_extensions li.is_enabled').show();
		    break;
		case 2:
		    $('ul.divi_extensions li').hide();
		    $('ul.divi_extensions li.is_disabled').show();
		    break;
		default:
		    $('ul.divi_extensions li').show();
		    break;
	    }
	});

	//***

	jQuery('.divi_select_image').life('click', function ()
	{
	    var input_object = jQuery(this).prev('input[type=text]');
	    window.send_to_editor = function (html)
	    {
		jQuery('#divi_buffer').html(html);
		var imgurl = jQuery('#divi_buffer').find('a').eq(0).attr('href');
		jQuery('#divi_buffer').html("");
		jQuery(input_object).val(imgurl);
		jQuery(input_object).trigger('change');
		tb_remove();
	    };
	    tb_show('', 'media-upload.php?post_id=0&type=image&TB_iframe=true');

	    return false;
	});

	//***

	$('.divi_ext_remove').life('click', function () {
	    if (confirm('Sure?')) {
		divi_show_info_popup('Extension removing ...');
		var _this = this;
		var data = {
		    action: "divi_remove_ext",
		    idx: $(this).data('idx')
		};
		$.post(ajaxurl, data, function () {
		    divi_show_info_popup('Extension is removed!');
		    $(_this).parents('.divi_ext_li').remove();
		    divi_hide_info_popup();
		});
	    }

	    return false;
	});

	//***

	$('#toggle_type').change(function () {
	    if ($(this).val() == 'text') {
		$('.toggle_type_text').show(200);
		$('.toggle_type_image').hide(200);
	    } else {
		$('.toggle_type_image').show(200);
		$('.toggle_type_text').hide(200);
	    }
	});

	//***
	//to avoid logic errors with the count options
	

	$('#divi_show_count_dynamic').change(function () {
	    if ($(this).val() == 1) {
		$('#divi_show_count').val(1);
	    }
	    
	    
	    $('#divi_hide_dynamic_empty_pos').val(0);
	});

	$('#divi_show_count').change(function () {
	    if ($(this).val() == 0) {
		$('#divi_show_count_dynamic').val(0);
	    }
	});

	//***


	//loader
	$(".divi-admin-preloader").fadeOut("slow");

    });

})(jQuery);


function divi_show_info_popup(text) {
    jQuery("#divi_html_buffer").text(text);
    jQuery("#divi_html_buffer").fadeTo(333, 0.9);
}

function divi_hide_info_popup() {
    window.setTimeout(function () {
	jQuery("#divi_html_buffer").fadeOut(500);
    }, 333);
}
