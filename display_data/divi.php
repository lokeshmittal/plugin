<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>

<?php
//+++
$args = array();
$args['show_count'] = get_option('divi_show_count', 0);
$args['show_count_dynamic'] = get_option('divi_show_count_dynamic', 0);
$args['hide_dynamic_empty_pos'] = 0;
$args['divi_autosubmit'] = $autosubmit;
//***
$_REQUEST['tax_only'] = $tax_only;
$_REQUEST['tax_exclude'] = $tax_exclude;
$_REQUEST['by_only'] = $by_only;


if (!function_exists('divi_only')) {

    function divi_only($key_slug, $type = 'taxonomy') {
	//var_dump($key_slug);
	switch ($type) {
	    case 'taxonomy':

		if (!empty($_REQUEST['tax_only'])) {
		    if (!in_array($key_slug, $_REQUEST['tax_only'])) {
			return FALSE;
		    }
		}

		if (!empty($_REQUEST['tax_exclude'])) {
		    if (in_array($key_slug, $_REQUEST['tax_exclude'])) {
			return FALSE;
		    }
		}

		break;

	    case 'item':
		if (!empty($_REQUEST['by_only'])) {
		    if (!in_array($key_slug, $_REQUEST['by_only'])) {
			return FALSE;
		    }
		}
		break;
	}


	return TRUE;
    }

}

//Sort logic  for shortcode [divi] attr tax_only
if (!function_exists('divi_print_tax')) {

    function get_order_by_tax_only($t_order, $t_only) {
	$temp_array = array_intersect($t_order, $t_only);
	$i = 0;
	foreach ($temp_array as $key => $val) {
	    $t_order[$key] = $t_only[$i];
	    $i++;
	}
	return $t_order;
    }

}
//***
if (!function_exists('divi_print_tax')) {

    function divi_print_tax($taxonomies, $tax_slug, $terms, $exclude_tax_key, $taxonomies_info, $additional_taxes, $divi_settings, $args, $counter) {

	global $DIVI;

	if ($exclude_tax_key == $tax_slug) {
	    //$terms = apply_filters('divi_exclude_tax_key', $terms);
	    if (empty($terms)) {
		return;
	    }
	}

	//***

	if (!divi_only($tax_slug, 'taxonomy')) {
	    return;
	}

	//***


	$args['taxonomy_info'] = $taxonomies_info[$tax_slug];
	$args['tax_slug'] = $tax_slug;
	$args['terms'] = $terms;
	$args['all_terms_hierarchy'] = $taxonomies[$tax_slug];
	$args['additional_taxes'] = $additional_taxes;

	//***
	$divi_container_styles = "";
	if ($divi_settings['tax_type'][$tax_slug] == 'radio' OR $divi_settings['tax_type'][$tax_slug] == 'checkbox') {
	    if ($DIVI->settings['tax_block_height'][$tax_slug] > 0) {
		$divi_container_styles = "max-height:{$DIVI->settings['tax_block_height'][$tax_slug]}px; overflow-y: auto;";
	    }
	}
	//***
	//https://wordpress.org/support/topic/adding-classes-divi_container-div
	$primax_class = sanitize_key(DIVI_HELPER::wpml_translate($taxonomies_info[$tax_slug]));
	?>
	<div data-css-class="divi_container_<?php echo $tax_slug ?>" class="divi_container divi_container_<?php echo $divi_settings['tax_type'][$tax_slug] ?> divi_container_<?php echo $tax_slug ?> divi_container_<?php echo $counter ?> divi_container_<?php echo $primax_class ?>">
	    <div class="divi_container_overlay_item"></div>
	    <div class="divi_container_inner divi_container_inner_<?php echo $primax_class ?>">
	<?php
	$css_classes = "divi_block_html_items";
	$show_toggle = 0;
	if (isset($DIVI->settings['show_toggle_button'][$tax_slug])) {
	    $show_toggle = (int) $DIVI->settings['show_toggle_button'][$tax_slug];
	}
	//***
	$search_query = $DIVI->get_request_data();
	$block_is_closed = true;
	if (in_array($tax_slug, array_keys($search_query))) {
	    $block_is_closed = false;
	}
	if ($show_toggle === 1 AND ! in_array($tax_slug, array_keys($search_query))) {
	    $css_classes .= " divi_closed_block";
	}

	if ($show_toggle === 2 AND ! in_array($tax_slug, array_keys($search_query))) {
	    $block_is_closed = false;
	}

	if (in_array($show_toggle, array(1, 2))) {
	    $block_is_closed = apply_filters('divi_block_toggle_state', $block_is_closed);
	    if($block_is_closed){
		$css_classes .= " divi_closed_block";
	    }else{
		$css_classes = str_replace('divi_closed_block', '', $css_classes);
	    }
	}
	//***
	switch ($divi_settings['tax_type'][$tax_slug]) {
	    case 'checkbox':
		if ($DIVI->settings['show_title_label'][$tax_slug]) {
		    ?>
		    	<<?php echo apply_filters('divi_title_tag', 'h4'); ?>><?php echo DIVI_HELPER::wpml_translate($taxonomies_info[$tax_slug]) ?><?php DIVI_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?></<?php echo apply_filters('divi_title_tag', 'h4'); ?>>
			    <?php
			}

			if (!empty($divi_container_styles)) {
			    $css_classes .= " divi_section_scrolled";
			}
			?>
			<div class="<?php echo $css_classes ?>" <?php if (!empty($divi_container_styles)): ?>style="<?php echo $divi_container_styles ?>"<?php endif; ?>>
			<?php
			echo $DIVI->render_html(DIVI_PATH . 'display_data/html_types/checkbox.php', $args);
			?>
			</div>
			<?php
			break;
		    case 'select':
			if ($DIVI->settings['show_title_label'][$tax_slug]) {
			    ?>
		    	<<?php echo apply_filters('divi_title_tag', 'h4'); ?>><?php echo DIVI_HELPER::wpml_translate($taxonomies_info[$tax_slug]) ?><?php DIVI_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?></<?php echo apply_filters('divi_title_tag', 'h4'); ?>>
			    <?php
			}
			?>
			<div class="<?php echo $css_classes ?>">
			<?php
			echo $DIVI->render_html(DIVI_PATH . 'display_data/html_types/select.php', $args);
			?>
			</div>
			<?php
			break;
		    case 'mselect':
			if ($DIVI->settings['show_title_label'][$tax_slug]) {
			    ?>
		    	<<?php echo apply_filters('divi_title_tag', 'h4'); ?>><?php echo DIVI_HELPER::wpml_translate($taxonomies_info[$tax_slug]) ?><?php DIVI_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?></<?php echo apply_filters('divi_title_tag', 'h4'); ?>>
				<?php
			    }
			    ?>
			<div class="<?php echo $css_classes ?>">
			<?php
			echo $DIVI->render_html(DIVI_PATH . 'display_data/html_types/mselect.php', $args);
			?>
			</div>
			<?php
			break;

		    default:
			if ($DIVI->settings['show_title_label'][$tax_slug]) {
			    $title = DIVI_HELPER::wpml_translate($taxonomies_info[$tax_slug]);
			    $title = explode('^', $title); //for hierarchy drop-down and any future manipulations
			    if (isset($title[1])) {
				$title = $title[1];
			    } else {
				$title = $title[0];
			    }
			    ?>
		    	<<?php echo apply_filters('divi_title_tag', 'h4'); ?>><?php echo $title ?><?php DIVI_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?></<?php echo apply_filters('divi_title_tag', 'h4'); ?>>
			    <?php
			}

			if (!empty($divi_container_styles)) {
			    $css_classes .= " divi_section_scrolled";
			}
			?>

			<div class="<?php echo $css_classes ?>" <?php if (!empty($divi_container_styles)): ?>style="<?php echo $divi_container_styles ?>"<?php endif; ?>>
			    <?php
			    if (!empty(DIVI_EXT::$includes['taxonomy_type_objects'])) {
				$is_custom = false;
				foreach (DIVI_EXT::$includes['taxonomy_type_objects'] as $obj) {
				    if ($obj->html_type == $divi_settings['tax_type'][$tax_slug]) {
					$is_custom = true;
					$args['divi_settings'] = $divi_settings;
					$args['taxonomies_info'] = $taxonomies_info;
					echo $DIVI->render_html($obj->get_html_type_view(), $args);
					break;
				    }
				}


				if (!$is_custom) {
				    echo $DIVI->render_html(DIVI_PATH . 'display_data/html_types/radio.php', $args);
				}
			    } else {
				echo $DIVI->render_html(DIVI_PATH . 'display_data/html_types/radio.php', $args);
			    }
			    ?>

			</div>
			<?php
			break;
		}
		?>

		<input type="hidden" name="divi_t_<?php echo $tax_slug ?>" value="<?php echo $taxonomies_info[$tax_slug]->labels->name ?>" /><!-- for red button search nav panel -->

	    </div>
	</div>
		    <?php
		}

	    }

	    if (!function_exists('divi_print_item_by_key')) {

		function divi_print_item_by_key($key, $divi_settings, $additional_taxes) {

		    if (!divi_only($key, 'item')) {
			return;
		    }

		    //***

		    global $DIVI;
		    switch ($key) {
			case 'by_price':
			    $price_filter = 0;
			    if (isset($DIVI->settings['by_price']['show'])) {
				$price_filter = (int) $DIVI->settings['by_price']['show'];
			    }
			    ?>

			<?php if ($price_filter == 1): ?>
		    <div data-css-class="divi_price_search_container" class="divi_price_search_container divi_container">
		        <div class="divi_container_overlay_item"></div>
		        <div class="divi_container_inner">
		    	<div class="woocommerce widget_price_filter">
		    <?php //the_widget('WC_Widget_Price_Filter', array('title' => ''));        ?>
		    <?php if (isset($DIVI->settings['by_price']['title_text']) AND ! empty($DIVI->settings['by_price']['title_text'])): ?>
				    <<?php echo apply_filters('divi_title_tag', 'h4'); ?>><?php echo DIVI_HELPER::wpml_translate(null, $DIVI->settings['by_price']['title_text']); ?><a href="javascript: void(0);" title="toggle" class="divi_front_toggle divi_front_toggle_closed" data-condition="closed"></a></<?php echo apply_filters('divi_title_tag', 'h4'); ?>>
		    <?php endif; ?>
			<div class="price_block_show_hide divi_block_html_items divi_closed_block divi_closed_block" style="display:none;">
		    <?php DIVI_HELPER::price_filter(); ?>
			</div>
		    	</div>
		        </div>
		    </div>
		    <div style="clear:both;"></div>
		<?php endif; ?>

		<?php if ($price_filter == 2): ?>
		    <div data-css-class="divi_price2_search_container" class="divi_price2_search_container divi_container">
		        <div class="divi_container_overlay_item"></div>
		        <div class="divi_container_inner">
		    <?php if (isset($DIVI->settings['by_price']['title_text']) AND ! empty($DIVI->settings['by_price']['title_text'])): ?>
				<<?php echo apply_filters('divi_title_tag', 'h4'); ?>><?php echo DIVI_HELPER::wpml_translate(null, $DIVI->settings['by_price']['title_text']); ?></<?php echo apply_filters('divi_title_tag', 'h4'); ?>>
		    <?php endif; ?>

		    <?php echo do_shortcode('[divi_price_filter type="select" additional_taxes="' . $additional_taxes . '"]'); ?>

		        </div>
		    </div>
		<?php endif; ?>


		<?php if ($price_filter == 3): ?>
		    <div data-css-class="divi_price3_search_container" class="divi_price3_search_container divi_container">
		        <div class="divi_container_overlay_item"></div>
		        <div class="divi_container_inner">
		    <?php if (isset($DIVI->settings['by_price']['title_text']) AND ! empty($DIVI->settings['by_price']['title_text'])): ?>
				<<?php echo apply_filters('divi_title_tag', 'h4'); ?>><?php echo DIVI_HELPER::wpml_translate(null, $DIVI->settings['by_price']['title_text']); ?></<?php echo apply_filters('divi_title_tag', 'h4'); ?>>
		    <?php endif; ?>

		    <?php echo do_shortcode('[divi_price_filter type="slider" additional_taxes="' . $additional_taxes . '"]'); ?>

		        </div>
		    </div>
			    <?php endif; ?>


			    <?php if ($price_filter == 4): ?>
		    <div data-css-class="divi_price4_search_container" class="divi_price4_search_container divi_container">
		        <div class="divi_container_overlay_item"></div>
		        <div class="divi_container_inner">
		    <?php if (isset($DIVI->settings['by_price']['title_text']) AND ! empty($DIVI->settings['by_price']['title_text'])): ?>
				<<?php echo apply_filters('divi_title_tag', 'h4'); ?>><?php echo DIVI_HELPER::wpml_translate(null, $DIVI->settings['by_price']['title_text']); ?></<?php echo apply_filters('divi_title_tag', 'h4'); ?>>
		    <?php endif; ?>

		    <?php echo do_shortcode('[divi_price_filter type="text" additional_taxes="' . $additional_taxes . '"]'); ?>

		        </div>
		    </div>
			<?php endif; ?>
			<?php if ($price_filter == 5): ?>
		    <div data-css-class="divi_price5_search_container" class="divi_price5_search_container divi_container">
		        <div class="divi_container_overlay_item"></div>
		        <div class="divi_container_inner">
			    <?php if (isset($DIVI->settings['by_price']['title_text']) AND ! empty($DIVI->settings['by_price']['title_text'])): ?>
				<<?php echo apply_filters('divi_title_tag', 'h4'); ?>><?php echo DIVI_HELPER::wpml_translate(null, $DIVI->settings['by_price']['title_text']); ?></<?php echo apply_filters('divi_title_tag', 'h4'); ?>>
		    <?php endif; ?>

		    <?php echo do_shortcode('[divi_price_filter type="radio" additional_taxes="' . $additional_taxes . '"]'); ?>

		        </div>
		    </div>
		<?php endif; ?>

			<?php
			break;

		    default:
			do_action('divi_print_html_type_' . $key);
			break;
		}
	    }

	}
	?>


<?php if ($autohide): ?>
    <div>
	    <?php
	    if (isset($this->settings['divi_auto_hide_button_img']) AND ! empty($this->settings['divi_auto_hide_button_img'])) {
		if ($this->settings['divi_auto_hide_button_img'] != 'none') {
		    ?>
	        <style type="text/css">
	    	.divi_show_auto_form,.divi_hide_auto_form
	    	{
	    	    background-image: url('<?php echo $this->settings['divi_auto_hide_button_img'] ?>') !important;
	    	}
	        </style>
	    <?php
	} else {
	    ?>
	        <style type="text/css">
	    	.divi_show_auto_form,.divi_hide_auto_form
	    	{
	    	    background-image: none !important;
	    	}
	        </style>
	    <?php
	}
    }
    //***
    $divi_auto_hide_button_txt = '';
    if (isset($this->settings['divi_auto_hide_button_txt'])) {
	$divi_auto_hide_button_txt = DIVI_HELPER::wpml_translate(null, $this->settings['divi_auto_hide_button_txt']);
    }
    ?>
        <a href="javascript:void(0);" class="divi_show_auto_form <?php if (isset($this->settings['divi_auto_hide_button_img']) AND $this->settings['divi_auto_hide_button_img'] == 'none') echo 'divi_show_auto_form_txt'; ?>"><?php echo __($divi_auto_hide_button_txt) ?></a><br />
        <div class="divi_auto_show divi_overflow_hidden" style="opacity: 0; height: 1px;">
    	<div class="divi_auto_show_indent divi_overflow_hidden">
<?php endif; ?>

            <div class="divi <?php if (!empty($sid)): ?>divi_sid divi_sid_<?php echo $sid ?><?php endif; ?>" <?php if (!empty($sid)): ?>data-sid="<?php echo $sid; ?>"<?php endif; ?> data-shortcode="<?php echo(isset($_REQUEST['divi_shortcode_txt']) ? $_REQUEST['divi_shortcode_txt'] : 'divi') ?>" data-redirect="<?php echo $redirect ?>" data-autosubmit="<?php echo $autosubmit ?>" data-ajax-redraw="<?php echo $ajax_redraw ?>">

<?php if ($show_divi_edit_view AND ! empty($sid)): ?>
    		<a href="#" class="divi_edit_view" data-sid="<?php echo $sid ?>"><?php _e('show blocks helper', 'products-filter-custom') ?></a>
    		<div></div>
    <?php endif; ?>

                <!--- here is possible drop html code which is never redraws by AJAX ---->

                <div class="divi_redraw_zone" data-divi-ver="<?php echo DIVI_VERSION ?>">
				<div class="serch_div_height">
    <?php echo apply_filters('divi_print_content_before_search_form', '') ?>

<?php
if (isset($start_filtering_btn) AND (int) $start_filtering_btn == 1) {
    $start_filtering_btn = true;
} else {
    $start_filtering_btn = false;
}

if (is_ajax()) {
    $start_filtering_btn = false;
}

if ($this->is_isset_in_request_data($this->get_sdivi_search_slug())) {
    $start_filtering_btn = false;
}

$txt = apply_filters('divi_start_filtering_btn_txt', __('Show products filter form', 'products-filter-custom'));
?>

    <?php if ($start_filtering_btn): ?>
    		    <a href="#" class="divi_button divi_start_filtering_btn"><?php echo $txt ?></a>
    <?php else: ?>
	<?php
	global $wp_query;
	//+++
	//if (!empty($taxonomies))
	{
	    $exclude_tax_key = '';
	    //code-bone for pages like
	    //http://dev.pluginus.net/product-category/clothing/ with GET params
	    //another way when GET is actual no possibility get current taxonomy
	    if ($this->is_really_current_term_exists()) {
		$o = $this->get_really_current_term();
		$exclude_tax_key = $o->taxonomy;
		//do_shortcode("[divi_products_ids_prediction taxonomies=product_cat:{$o->term_id}]");
		//echo $o->term_id;exit;
	    }
	    //***
	    if (!empty($wp_query->query)) {
		if (isset($wp_query->query_vars['taxonomy']) AND in_array($wp_query->query_vars['taxonomy'], get_object_taxonomies('product'))) {
		    $taxes = $wp_query->query;
		    if (isset($taxes['paged'])) {
			unset($taxes['paged']);
		    }

		    foreach ($taxes as $key => $value) {
			if (in_array($key, array_keys($this->get_request_data()))) {
			    unset($taxes[$key]);
			}
		    }
		    //***
		    if (!empty($taxes)) {
			$t = array_keys($taxes);
			$v = array_values($taxes);
			//***
			$exclude_tax_key = $t[0];
			$_REQUEST['DIVI_IS_TAX_PAGE'] = $exclude_tax_key;
		    }
		}
	    } else {
		//***
	    }

	    //***

	    $items_order = array();

	    $taxonomies_keys = array_keys($taxonomies);
	    if (isset($divi_settings['items_order']) AND ! empty($divi_settings['items_order'])) {
		$items_order = explode(',', $divi_settings['items_order']);
	    } else {
		$items_order = array_merge($this->items_keys, $taxonomies_keys);
	    }

	    //*** lets check if we have new taxonomies added in woocommerce or new item
	    foreach (array_merge($this->items_keys, $taxonomies_keys) as $key) {
		if (!in_array($key, $items_order)) {
		    $items_order[] = $key;
		}
	    }

	    //lets print our items and taxonomies
	    $counter = 0;
	    // var_dump($items_order);
	    if (count($tax_only) > 0) {
		$items_order = get_order_by_tax_only($items_order, $tax_only);
	    }
	    foreach ($items_order as $key) {
		if (in_array($key, $this->items_keys)) {
		    divi_print_item_by_key($key, $divi_settings, $additional_taxes);
		} else {
		    if (!isset($divi_settings['tax'][$key])) {
			continue;
		    }

		    divi_print_tax($taxonomies, $key, $taxonomies[$key], $exclude_tax_key, $taxonomies_info, $additional_taxes, $divi_settings, $args, $counter);
		}
		$counter++;
	    }
	}
	?>


               </div>

    		    <div class="divi_submit_search_form_container">

			<?php if ($this->is_isset_in_request_data($this->get_sdivi_search_slug())): global $divi_link; ?>

			    <?php
			    
			    $divi_reset_btn_txt = __('Reset', 'products-filter-custom');
			    $divi_reset_btn_txt = DIVI_HELPER::wpml_translate(null, $divi_reset_btn_txt);
			    ?>

			    <?php if ($divi_reset_btn_txt != 'none'): ?>
	    			<button class="button divi_reset_search_form" data-link="<?php echo $divi_link ?>"><?php echo $divi_reset_btn_txt ?></button>
			    <?php endif; ?>
			<?php endif; ?>

			<?php if (!$autosubmit OR $ajax_redraw): ?>
			    <?php
			    $divi_filter_btn_txt = __('Apply', 'products-filter-custom');

			    $divi_filter_btn_txt = DIVI_HELPER::wpml_translate(null, $divi_filter_btn_txt);
			    ?>
				<button class="button divi_submit_search_form"><?php echo $divi_filter_btn_txt ?></button>
			<?php endif; ?>

    		    </div>


		    <?php endif; ?>



                </div>

            </div>



		    <?php if ($autohide): ?>
    	</div>
        </div>
    </div>
		    <?php endif; ?>


<style>
.divi_submit_search_form{
 color: <?php echo $divi_settings['divi_apply_color']; ?>;
 background: <?php echo $divi_settings['divi_apply_bgcolor']; ?>; 
 font-size:<?php echo $divi_settings['divi_apply_size']; ?>px;
 font-weight:<?php echo $divi_settings['divi_apply_weight']; ?>;
 border: <?php echo $divi_settings['divi_apply_bsize']; ?>px solid <?php echo $divi_settings['divi_apply_bcolor']; ?>;
 font-family:<?php echo $divi_settings['divi_apply_fontfamily']; ?>;
 box-shadow: <?php echo $divi_settings['divi_box_show1'].'px '.$divi_settings['divi_box_show2'].'px '.$divi_settings['divi_box_show3'].'px '.$divi_settings['divi_box_show4'].'px '.$divi_settings['divi_box_show5']; ?>;
 border-radius: 0;
}
.divi_reset_search_form{
 color: <?php echo $divi_settings['divi_reset_color']; ?>;
 background: <?php echo $divi_settings['divi_reset_bgcolor']; ?>; 
 font-size:<?php echo $divi_settings['divi_reset_size']; ?>px;
 font-weight:<?php echo $divi_settings['divi_reset_weight']; ?>;
 border: <?php echo $divi_settings['divi_reset_bsize']; ?>px solid <?php echo $divi_settings['divi_reset_bcolor']; ?>;  
 font-family:<?php echo $divi_settings['divi_reset_fontfamily']; ?>; 
 box-shadow: <?php echo $divi_settings['divi_box_show1'].'px '.$divi_settings['divi_box_show2'].'px '.$divi_settings['divi_box_show3'].'px '.$divi_settings['divi_box_show4'].'px '.$divi_settings['divi_box_show5']; ?>;
 border-radius: 0;
}
.divi_redraw_zone h4 {
    color: <?php echo $divi_settings['divi_filter_name_color']; ?> !important;
    line-height: <?php echo $divi_settings['divi_filter_name_lineheight']; ?>px;
    font-size: <?php echo $divi_settings['divi_filter_name_size']; ?>px;
    font-weight: <?php echo $divi_settings['divi_filter_name_weight']; ?>;
    padding: <?php echo $divi_settings['divi_filter_name_top'].'px '.$divi_settings['divi_filter_name_right'].'px '.$divi_settings['divi_filter_name_buttom'].'px '.$divi_settings['divi_filter_name_left']; ?>px;
	font-family:<?php echo $divi_settings['divi_filter_name_fontfamily']; ?>;
}
.divi_redraw_zone .divi_block_html_items ul li label {
    color: <?php echo $divi_settings['divi_filter_content_color']; ?> !important;
    line-height: <?php echo $divi_settings['divi_filter_content_lineheight']; ?>px;
    font-size: <?php echo $divi_settings['divi_filter_content_size']; ?>px;
    font-weight: <?php echo $divi_settings['divi_filter_content_weight']; ?>;
    padding: <?php echo $divi_settings['divi_filter_content_top'].'px '.$divi_settings['divi_filter_content_right'].'px '.$divi_settings['divi_filter_content_buttom'].'px '.$divi_settings['divi_filter_content_left']; ?>px;
	font-family:<?php echo $divi_settings['divi_filter_content_fontfamily']; ?>;
}
.divi_submit_search_form_container {
    overflow: visible;
}
.filter_hide_show{
	width: 0% !important;
    background: <?php echo $divi_settings['divi_box_backgroung']; ?>;   
    border: <?php echo $divi_settings['divi_box_border_size']; ?>px solid <?php echo $divi_settings['divi_box_border_color']; ?>;
    border-radius: <?php echo $divi_settings['divi_box_radius']; ?>px;
    opacity: <?php echo $divi_settings['divi_box_opacity']; ?>;
	position: fixed;
    z-index: 999999;
}



.filter_hide_show.filter_hide{
	width: <?php echo $divi_settings['divi_box_maxwidth']; ?>% !important;
}

.filter_hide_show .divi .serch_div_height{
	max-height: <?php echo $divi_settings['divi_box_maxheight']; ?>px;
	overflow-y: auto;
}
.widget-divi ul li, .widget-divi ol li{
   border-bottom: 1px solid #ddd;
   border-top: none;
}
.widget-divi ul li:nth-child(1), .widget-divi ol li:nth-child(1){
    border-top: 1px solid #ddd;	
}
button.hide_show_button {
    float: right;
    margin-top: -47px;
}
.filter_hide_show h3 {
    color: <?php echo $divi_settings['divi_filter_title_color']; ?> !important;
	border-bottom: 1px solid #ddd;
    font-size: <?php echo $divi_settings['divi_filter_title_size']; ?>px;
    font-weight: <?php echo $divi_settings['divi_filter_title_weight']; ?>;
    padding: <?php echo $divi_settings['divi_filter_title_top'].'px '.$divi_settings['divi_filter_title_right'].'px '.$divi_settings['divi_filter_title_buttom'].'px '.$divi_settings['divi_filter_title_left']; ?>px;
	font-family:<?php echo $divi_settings['divi_filter_title_fontfamily']; ?>;
}
button.hide_show_button {
    color: <?php echo $divi_settings['divi_mainbutton_color']; ?>;
    background: <?php echo $divi_settings['divi_mainbutton_bgcolor']; ?>;
    border: <?php echo $divi_settings['divi_mainbutton_bsize']; ?>px solid <?php echo $divi_settings['divi_mainbutton_bcolor']; ?>;
    font-size: <?php echo $divi_settings['divi_mainbutton_size']; ?>px;
    padding: <?php echo $divi_settings['divi_mainbutton_top']; ?>px <?php echo $divi_settings['divi_mainbutton_right']; ?>px <?php echo $divi_settings['divi_mainbutton_buttom']; ?>px <?php echo $divi_settings['divi_mainbutton_left']; ?>px;
	border-radius: <?php echo $divi_settings['divi_mainbutton_radius']; ?>px;
	line-height: 0;
}
.divi {
    padding: 0 5px;
}
.divi_container {
    border-bottom: 1px solid rgba(230, 230, 230, 0.57);
}
.screen_opacity_class{
	opacity: <?php echo $divi_settings['divi_screen_opacity']; ?>;
}
<?php 
$filter_left_right_manage = $divi_settings['divi_box_position'];

if($filter_left_right_manage == 'right'){
?>
.filter_hide_show {	
    right: 0;
    bottom: 0;
}	
<?php	
}
else{
?>	
.filter_hide_show {	
    left: 0;
    bottom: 0;
}
button.hide_show_button {
    float: left;
}
.divi_submit_search_form_container {
    text-align: right;
}	
<?php		
} 
?>
</style>
<?php 
$divi_mainbutton_font_icon = $divi_settings['divi_mainbutton_font_icon'];
if($divi_mainbutton_font_icon == ''){
	$divi_mainbutton_font_icon1 = '<i class="fa fa-sliders" aria-hidden="true"></i>';
}
else{
	$divi_mainbutton_font_icon1 = $divi_mainbutton_font_icon;
}
?>
<script>
	 jQuery(document).ready(function(){
		//jQuery(".divi").append( jQuery( '<button class="hide_show_button"><i class="fa fa-sliders" aria-hidden="true"></i></button>' ) );
		jQuery(".hide_show_button").html('<?php echo $divi_mainbutton_font_icon1; ?>'); 

		//jQuery(".filter_hide_show").css("background", "none");
		//jQuery(".filter_hide_show").css("border", "none");
		//jQuery(".filter_hide_show").css("width", "auto");
		
		
		jQuery(".divi").hide();
		jQuery(".hide_show_button").click(function(){
			//alert('fffffffffffff');
			jQuery(".divi").toggle('slow');
			  jQuery(".filter_hide_show").toggleClass("filter_hide");
			  jQuery("#page").toggleClass("screen_opacity_class");
			if (jQuery(this).html() == '<?php echo $divi_mainbutton_font_icon1; ?>') { 
			   jQuery(this).html('<i class="fa fa-times" aria-hidden="true"></i>'); 			
				
			   //jQuery(".filter_hide_show").css("background", "#<?php echo $divi_settings['divi_box_backgroung']; ?>");
			   //jQuery(".filter_hide_show").css("border", "<?php echo $divi_settings['divi_box_border_size']; ?>px solid #<?php echo $divi_settings['divi_box_border_color']; ?>");
			   //jQuery(".filter_hide_show").css("width", "<?php echo $divi_settings['divi_box_maxwidth']; ?>");
				
			} else { 
			   jQuery(this).html('<?php echo $divi_mainbutton_font_icon1; ?>');
				
               //jQuery(".filter_hide_show").css("background", "none");
			   //jQuery(".filter_hide_show").css("border", "none");
			   //jQuery(".filter_hide_show").css("width", "auto");
				
				
			}; 	
		});
	 });
</script>

