<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<div class="divi-admin-preloader"></div>
<div class="subsubsub_section">

    <?php if (isset($_GET['settings_saved'])): ?>
        <div id="message" class="updated"><p><strong><?php _e("Your settings have been saved.", 'products-filter-custom') ?></strong></p></div>
    <?php endif; ?>

    <?php
    if (!empty(DIVI_HELPER::$notices)) {
	foreach (DIVI_HELPER::$notices as $key) {
	    DIVI_HELPER::show_admin_notice($key);
	}
    }
    ?>


    <?php if (isset($_GET['divi_hide_notice'])): ?>
        <script type="text/javascript">
    	window.location = "<?php echo admin_url('admin.php?page=wc-settings&tab=divi'); ?>";
        </script>
    <?php endif; ?>

    <section class="divi-section">
        <h3><span class="by_icon"><i class="fa fa-circle" aria-hidden="true"></i></span> <?php printf(__('Woocommerce Products Filter', 'products-filter-custom'), DIVI_VERSION) ?> <span class="by_divi">by diviunlimited</span><span class="info_circle"><i class="fa fa-info-circle" aria-hidden="true"></i></span></h3>
        <input type="hidden" name="divi_settings" value="" />
        <input type="hidden" name="divi_settings[items_order]" value="<?php echo(isset($divi_settings['items_order']) ? $divi_settings['items_order'] : '') ?>" />

	<?php if (version_compare(WOOCOMMERCE_VERSION, DIVI_MIN_WOOCOMMERCE_VERSION, '<')): ?>

    	<div id="message" class="error fade"><p><strong><?php _e("ATTENTION! Your version of the woocommerce plugin is too obsolete. There is no warranty for working with DIVI!!", 'products-filter-custom') ?></strong></p></div>

	<?php endif; ?>

        <svg class="hidden">
        <defs>
        <path id="tabshape" d="M80,60C34,53.5,64.417,0,0,0v60H80z"/>
        </defs>
        </svg>

        <div id="tabs" class="divi-tabs divi-tabs-style-shape">

            <nav>
                <ul>
                    <li class="tab-current">
                        <a href="#tabs-1">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php _e("Filters", 'products-filter-custom') ?></span>
                        </a>
                    </li>
					<li>
                        <a href="#tabs-5">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php _e("Appearance", 'products-filter-custom') ?></span>
                        </a>
                    </li>
					<li>
                        <a href="#tabs-2">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php _e("Basic Setting", 'products-filter-custom') ?></span>
                        </a>
                    </li>
					<li>
                        <a href="#tabs-4">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php _e("Advanced Setting", 'products-filter-custom') ?></span>
                        </a>
                    </li>
					<!--------------------------------MOSTTTTTTTTTTTTTTTTTTTTTTT-------------------------------------------------------------
                    <li>
                        <a href="#tabs-3">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php //_e("Design", 'products-filter-custom') ?></span>
                        </a>
                    </li>
					--------------------------------MOSTTTTTTTTTTTTTTTTTTTTTTT------------------------------------------------------------->

		    <?php
		    if (!empty(DIVI_EXT::$includes['applications'])) {
			foreach (DIVI_EXT::$includes['applications'] as $obj) {
			    $dir1 = $this->get_custom_ext_path() . $obj->folder_name;
			    $dir2 = DIVI_EXT_PATH . $obj->folder_name;
			    $checked1 = DIVI_EXT::is_ext_activated($dir1);
			    $checked2 = DIVI_EXT::is_ext_activated($dir2);
			    if ($checked1 OR $checked2) {
				do_action('divi_print_applications_tabs_' . $obj->folder_name);
			    }
			}
		    }
		    ?>
                    <!--   
                    <li>
                        <a href="#tabs-6">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php /* _e("Extensions", 'products-filter-custom') */ ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="#tabs-7">
                            <svg viewBox="0 0 80 60" preserveAspectRatio="none"><use xlink:href="#tabshape"></use></svg>
                            <span><?php /* _e("Info", 'products-filter-custom') */ ?></span>
                        </a>
                    </li>
					-->
                </ul>
            </nav>

            <div class="content-wrap">

                <section id="tabs-1" class="content-current">
                  <div class="section_left"> 
                    <?php //include('filter_form.php'); ?>				  
                    <ul id="divi_options" style="display:block;">

							<?php
							$items_order = array();
							$taxonomies = $this->get_taxonomies();
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
							foreach ($items_order as $key) {
								if (in_array($key, $this->items_keys)) {
								divi_print_item_by_key($key, $divi_settings);
								} else {
								if (isset($taxonomies[$key])) {
									divi_print_tax($key, $taxonomies[$key], $divi_settings);
								}
								}
							}
							?>
                    </ul>
				   </div>
					<?php include('preview_form.php'); ?>
					<div class="preview_reset">
						<input type="button" class="divi_reset_order" style="float: right;" value="<?php _e('Reset', 'products-filter-custom') ?>" />
						<input type="button" class="divi_reset_order11" style="float: right;" value="<?php _e('Hide preview', 'products-filter-custom') ?>" />
                    </div>     
                    <div class="clear"></div>

                </section>
                <section id="tabs-2">
                  <div class="content_left">    
				   <!---############################################################=My Code= ######################################################################---->
				   <?php include('basic_setting.php'); ?> 		   
				    <!---############################################################=end My Code=######################################################################---->
		           <?php  //woocommerce_admin_fields($this->get_options()); ?>
                  </div> 
				  <?php include('preview_form.php'); ?>
				  <div class="preview_reset">
					  <input type="button" class="divi_reset_appren" style="float: right;" value="<?php _e('Reset', 'products-filter-custom') ?>" />
					  <input type="button" class="divi_hide_preview" style="float: right;" value="<?php _e('Hide preview', 'products-filter-custom') ?>" />
				  </div>
                </section>

                <section id="tabs-3">
             <div>
		    <?php
		    $skins = array(
			'none' => array('none'),
			'flat' => array(
			    'flat_aero',
			    'flat_blue',
			    'flat_flat',
			    'flat_green',
			    'flat_grey',
			    'flat_orange',
			    'flat_pink',
			    'flat_purple',
			    'flat_red',
			    'flat_yellow'
			),
			'minimal' => array(
			    'minimal_aero',
			    'minimal_blue',
			    'minimal_green',
			    'minimal_grey',
			    'minimal_minimal',
			    'minimal_orange',
			    'minimal_pink',
			    'minimal_purple',
			    'minimal_red',
			    'minimal_yellow'
			),
			'square' => array(
			    'square_aero',
			    'square_blue',
			    'square_green',
			    'square_grey',
			    'square_orange',
			    'square_pink',
			    'square_purple',
			    'square_red',
			    'square_yellow',
			    'square_square'
			)
		    );
		    $skin = 'none';
		    if (isset($divi_settings['icheck_skin'])) {
			$skin = $divi_settings['icheck_skin'];
		    }
		    ?>

                    <div class="divi-control-section">

                        <h4><?php _e('Radio and checkboxes skin', 'products-filter-custom') ?></h4>

                        <div class="divi-control-container">
                            <div class="divi-control">

                                <select name="divi_settings[icheck_skin]" class="chosen_select">
				    <?php foreach ($skins as $key => $schemes) : ?>
    				    <optgroup label="<?php echo $key ?>">
					    <?php foreach ($schemes as $scheme) : ?>
						<option value="<?php echo $scheme; ?>" <?php if ($skin == $scheme): ?>selected="selected"<?php endif; ?>><?php echo $scheme; ?></option>
					    <?php endforeach; ?>
    				    </optgroup>
				    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="divi-description"></div>
                        </div>

                    </div><!--/ .divi-control-section-->

		    <?php
		    $skins = array(
			'default' => __('Default', 'products-filter-custom'),
			'plainoverlay' => __('Plainoverlay - CSS', 'products-filter-custom'),
			'loading-balls' => __('Loading balls - SVG', 'products-filter-custom'),
			'loading-bars' => __('Loading bars - SVG', 'products-filter-custom'),
			'loading-bubbles' => __('Loading bubbles - SVG', 'products-filter-custom'),
			'loading-cubes' => __('Loading cubes - SVG', 'products-filter-custom'),
			'loading-cylon' => __('Loading cyclone - SVG', 'products-filter-custom'),
			'loading-spin' => __('Loading spin - SVG', 'products-filter-custom'),
			'loading-spinning-bubbles' => __('Loading spinning bubbles - SVG', 'products-filter-custom'),
			'loading-spokes' => __('Loading spokes - SVG', 'products-filter-custom'),
		    );
		    if (!isset($divi_settings['overlay_skin'])) {
			$divi_settings['overlay_skin'] = 'default';
		    }
		    $skin = $divi_settings['overlay_skin'];
		    ?>


                    <div class="divi-control-section">

                        <h4><?php _e('Overlay skins', 'products-filter-custom') ?></h4>

                        <div class="divi-control-container">
                            <div class="divi-control">

                                <select name="divi_settings[overlay_skin]" class="chosen_select">
				    <?php foreach ($skins as $scheme => $title) : ?>
    				    <option value="<?php echo $scheme; ?>" <?php if ($skin == $scheme): ?>selected="selected"<?php endif; ?>><?php echo $title; ?></option>
				    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="divi-description">

                            </div>
                        </div>

                    </div><!--/ .divi-control-section-->

		    <?php
		    if (!isset($divi_settings['overlay_skin_bg_img'])) {
			$divi_settings['overlay_skin_bg_img'] = '';
		    }
		    $overlay_skin_bg_img = $divi_settings['overlay_skin_bg_img'];
		    ?>


                    <div class="divi-control-section" <?php if ($skin == 'default'): ?>style="display: none;"<?php endif; ?>>

                        <h4><?php _e('Overlay image background', 'products-filter-custom') ?></h4>

                        <div class="divi-control-container">
                            <div class="divi-control divi-upload-style-wrap">

                                <input type="text" name="divi_settings[overlay_skin_bg_img]" value="<?php echo $overlay_skin_bg_img ?>" />

                                <a href="#" class="divi-button divi_select_image"><?php _e('Select Image', 'products-filter-custom') ?></a><br />

                                <div <?php if ($skin != 'plainoverlay'): ?>style="display: none;"<?php endif; ?>>
                                    <br />
				    <?php
				    if (!isset($divi_settings['plainoverlay_color'])) {
					$divi_settings['plainoverlay_color'] = '';
				    }
				    $plainoverlay_color = $divi_settings['plainoverlay_color'];
				    ?>

                                    <h4<?php _e('Plainoverlay color', 'products-filter-custom') ?></h4>
                                    <input type="text" name="divi_settings[plainoverlay_color]" value="<?php echo $plainoverlay_color ?>" id="divi_color_picker_plainoverlay_color" class="divi-color-picker" />

                                </div>

                            </div>
                            <div class="divi-description">
                                <p class="description">
				    <?php _e('Example', 'products-filter-custom') ?>: <?php echo DIVI_LINK ?>img/overlay_bg.png
                                </p>
                            </div>
                        </div>

                    </div><!--/ .divi-control-section-->


                    <div class="divi-control-section" <?php if ($skin != 'default'): ?>style="display: none;"<?php endif; ?>>

                        <h4><?php _e('Loading word', 'products-filter-custom') ?></h4>

                        <div class="divi-control-container">

                            <div class="divi-control divi-upload-style-wrap">

				<?php
				if (!isset($divi_settings['default_overlay_skin_word'])) {
				    $divi_settings['default_overlay_skin_word'] = '';
				}
				$default_overlay_skin_word = $divi_settings['default_overlay_skin_word'];
				?>



                                <input type="text" name="divi_settings[default_overlay_skin_word]" value="<?php echo $default_overlay_skin_word ?>" />


                            </div>
                            <div class="divi-description">
                                <p class="description">
				    <?php _e('Word while searching is going on front when "Overlay skins" is default.', 'products-filter-custom') ?>
                                </p>
                            </div>
                        </div>
                    </div><!--/ .divi-control-section-->


                 <!--
                    <div class="divi-control-section">

                        <h4><?php /* _e('Use chosen', 'products-filter-custom') */?></h4>

                        <div class="divi-control-container">

                            <div class="divi-control divi-upload-style-wrap">

				<?php /*
				$chosen_selects = array(
				    0 => __('No', 'products-filter-custom'),
				    1 => __('Yes', 'products-filter-custom')
				);

				if (!isset($divi_settings['use_chosen'])) {
				    $divi_settings['use_chosen'] = 1;
				}
				$chosen_select = $divi_settings['use_chosen']; */
				?>

                                <div class="select-wrap">
                                    <select name="divi_settings[use_chosen]" class="chosen_select">
					<?php /* foreach ($chosen_selects as $key => $value) : */ ?>
    					<option value="<?php //echo $key; ?>" <?php /* if ($chosen_select == $key): */ ?>selected="selected"<?php //endif; ?>><?php //echo $value; ?></option>
					<?php //endforeach; ?>
                                    </select>
                                </div>


                            </div>
                            <div class="divi-description">
                                <p class="description">
				    <?php /* _e('Use chosen javascript library on the front of your site for drop-downs.', 'products-filter-custom') */ ?>
                                </p>
                            </div>
                        </div>
                    </div>
					-->  
					<!--/ .divi-control-section-->
                      

                    <div class="divi-control-section">

                        <h4><?php _e('Use beauty scroll', 'products-filter-custom') ?></h4>

                        <div class="divi-control-container">

                            <div class="divi-control divi-upload-style-wrap">

				<?php
				$use_beauty_scroll = array(
				    0 => __('No', 'products-filter-custom'),
				    1 => __('Yes', 'products-filter-custom')
				);

				if (!isset($divi_settings['use_beauty_scroll'])) {
				    $divi_settings['use_beauty_scroll'] = 0;
				}
				$use_scroll = $divi_settings['use_beauty_scroll'];
				?>

                                <div class="select-wrap">
                                    <select name="divi_settings[use_beauty_scroll]" class="chosen_select">
					<?php foreach ($use_beauty_scroll as $key => $value) : ?>
    					<option value="<?php echo $key; ?>" <?php if ($use_scroll == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
                                    </select>
                                </div>


                            </div>
                            <div class="divi-description">
                                <p class="description">
				    <?php _e('Use beauty scroll when you apply max height for taxonomy block on the front', 'products-filter-custom') ?>
                                </p>
                            </div>
                        </div>
                    </div><!--/ .divi-control-section-->


                    <div class="divi-control-section">

                        <h4><?php _e('Range-slider skin', 'products-filter-custom') ?></h4>

                        <div class="divi-control-container">

                            <div class="divi-control divi-upload-style-wrap">

				<?php
				$skins = array(
				    'skinNice' => 'skinNice',
				    'skinFlat' => 'skinFlat',
				    'skinHTML5' => 'skinHTML5',
				    'skinModern' => 'skinModern',
				    'skinSimple' => 'skinSimple'
				);

				if (!isset($divi_settings['ion_slider_skin'])) {
				    $divi_settings['ion_slider_skin'] = 'skinNice';
				}
				$skin = $divi_settings['ion_slider_skin'];
				?>

                                <div class="select-wrap">
                                    <select name="divi_settings[ion_slider_skin]" class="chosen_select">
					<?php foreach ($skins as $key => $value) : ?>
    					<option value="<?php echo $key; ?>" <?php if ($skin == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
                                    </select>
                                </div>


                            </div>
                            <div class="divi-description">
                                <p class="description">
				    <?php _e('Ion-Range slider js lib skin for range-sliders of the plugin', 'products-filter-custom') ?>
                                </p>
                            </div>
                        </div>
                    </div><!--/ .divi-control-section-->



		    <?php if (get_option('divi_set_automatically')): ?>
    		    <div class="divi-control-section">

    			<h4><?php _e('Hide auto filter by default', 'products-filter-custom') ?></h4>

    			<div class="divi-control-container">
    			    <div class="divi-control">

				    <?php
				    $divi_auto_hide_button = array(
					0 => __('No', 'products-filter-custom'),
					1 => __('Yes', 'products-filter-custom')
				    );
				    if (!isset($divi_settings['divi_auto_hide_button'])) {
					$divi_settings['divi_auto_hide_button'] = 0;
				    }
				    $divi_auto_hide_button_val = $divi_settings['divi_auto_hide_button'];
				    ?>

    				<select name="divi_settings[divi_auto_hide_button]" class="chosen_select">
					<?php foreach ($divi_auto_hide_button as $v => $n) : ?>
					    <option value="<?php echo $v; ?>" <?php if ($divi_auto_hide_button_val == $v): ?>selected="selected"<?php endif; ?>><?php echo $n; ?></option>
					<?php endforeach; ?>
    				</select>

    			    </div>
    			    <div class="divi-description">
    				<p class="description"><?php _e('If in options tab option "Set filter automatically" is "Yes" you can hide filter and show hide/show button instead of it.', 'products-filter-custom') ?></p>
    			    </div>
    			</div>

    		    </div><!--/ .divi-control-section-->

		    <?php endif; ?>

		    <?php
		    if (!isset($divi_settings['divi_auto_hide_button_img'])) {
			$divi_settings['divi_auto_hide_button_img'] = '';
		    }

		    if (!isset($divi_settings['divi_auto_hide_button_txt'])) {
			$divi_settings['divi_auto_hide_button_txt'] = '';
		    }
			
			
			if (!isset($divi_settings['divi_apply_color'])) {
			$divi_settings['divi_apply_color'] = '';
		    }
			if (!isset($divi_settings['divi_apply_bgcolor'])) {
			$divi_settings['divi_apply_bgcolor'] = '';
		    }
			
			
		    ?>
                   <!--    
                    <div class="divi-control-section">

                        <h4><?php //_e('Auto filter close/open image', 'products-filter-custom') ?></h4>

                        <div class="divi-control-container">
                            <div class="divi-control divi-upload-style-wrap">
                                <input type="text" name="divi_settings[divi_auto_hide_button_img]" value="<?php //echo $divi_settings['divi_auto_hide_button_img'] ?>" />
                                <a href="#" class="divi-button divi_select_image"><?php //_e('Select Image', 'products-filter-custom') ?></a>
                            </div>
                            <div class="divi-description">
                                <p class="description"><?php //_e('Image which displayed instead filter while it is closed if selected. Write "none" here if you want to use text only!', 'products-filter-custom') ?></p>
                            </div>
                        </div>

                    </div>


                    <div class="divi-control-section">

                        <h4><?php _e('Auto filter close/open text', 'products-filter-custom') ?></h4>

                        <div class="divi-control-container">
                            <div class="divi-control">
                                <input type="text" name="divi_settings[divi_auto_hide_button_txt]" value="<?php //echo $divi_settings['divi_auto_hide_button_txt'] ?>" />
                            </div>
                            <div class="divi-description">
                                <p class="description"><?php //_e('Text which displayed instead filter while it is closed if selected.', 'products-filter-custom') ?></p>
                            </div>
                        </div>

                    </div>

                    <div class="divi-control-section">

                        <h4><?php //_e('Image for subcategories [<i>open</i>]', 'products-filter-custom') ?></h4>

                        <div class="divi-control-container">
                            <div class="divi-control divi-upload-style-wrap">
                                <input type="text" name="divi_settings[divi_auto_subcats_plus_img]" value="<?php //echo(isset($divi_settings['divi_auto_subcats_plus_img']) ? $divi_settings['divi_auto_subcats_plus_img'] : '') ?>" />
                                <a href="#" class="divi-button divi_select_image"><?php //_e('Select Image', 'products-filter-custom') ?></a>
                            </div>
                            <div class="divi-description">
                                <p class="description"><?php //_e('Image when you select in tab Options "Hide childs in checkboxes and radio". By default it is green cross.', 'products-filter-custom') ?></p>
                            </div>
                        </div>

                        <h4><?php //_e('Image for subcategories [<i>close</i>]', 'products-filter-custom') ?></h4>

                        <div class="divi-control-container">
                            <div class="divi-control divi-upload-style-wrap">
                                <input type="text" name="divi_settings[divi_auto_subcats_minus_img]" value="<?php //echo(isset($divi_settings['divi_auto_subcats_minus_img']) ? $divi_settings['divi_auto_subcats_minus_img'] : '') ?>" />
                                <a href="#" class="divi-button divi_select_image"><?php //_e('Select Image', 'products-filter-custom') ?></a>
                            </div>
                            <div class="divi-description">
                                <p class="description"><?php //_e('Image when you select in tab Options "Hide childs in checkboxes and radio". By default it is green minus.', 'products-filter-custom') ?></p>
                            </div>
                        </div>

                    </div>


                    <div class="divi-control-section">

                        <h4><?php //_e('Toggle block type', 'products-filter-custom') ?></h4>

                        <div class="divi-control-container">

                            <div class="divi-control divi-upload-style-wrap">

				<?php /*
				$toggle_types = array(
				    'text' => __('Text', 'products-filter-custom'),
				    'image' => __('Images', 'products-filter-custom')
				);

				if (!isset($divi_settings['toggle_type'])) {
				    $divi_settings['toggle_type'] = 'text';
				}
				$toggle_type = $divi_settings['toggle_type'];
				*/
				?>

                                <div class="select-wrap">
                                    <select name="divi_settings[toggle_type]" class="chosen_select" id="toggle_type">
					<?php //foreach ($toggle_types as $key => $value) : ?>
    					<option value="<?php //echo $key; ?>" <?php //if ($toggle_type == $key): ?>selected="selected"<?php //endif; ?>><?php //echo $value; ?></option>
					<?php //endforeach; ?>
                                    </select>
                                </div>


                            </div>
                            <div class="divi-description">
                                <p class="description">
				    <?php //_e('Type of the toogle on the front for block of html-items as: radio, checkbox .... Works only if the block title is not hidden!', 'products-filter-custom') ?>
                                </p>
                            </div>
                        </div>

                        <div class="toggle_type_text" <?php //if ($toggle_type == 'image'): ?>style="display: none;"<?php //endif; ?>>

                            <h4><?php //_e('Text for block toggle [<i>opened</i>]', 'products-filter-custom') ?></h4>

                            <div class="divi-control-container">
                                <div class="divi-control divi-upload-style-wrap">
				    <?php
					/*
				    if (!isset($divi_settings['toggle_opened_text'])) {
					$divi_settings['toggle_opened_text'] = '';
				    }
					*/
				    ?>
                                    <input type="text" name="divi_settings[toggle_opened_text]" value="<?php //echo $divi_settings['toggle_opened_text'] ?>" />
                                </div>
                                <div class="divi-description">
                                    <p class="description"><?php //_e('Toggle text for opened html-items block. Example: close. By default applied sign minus "-"', 'products-filter-custom') ?></p>
                                </div>
                            </div>

                            <h4><?php //_e('Text for block toggle [<i>closed</i>]', 'products-filter-custom') ?></h4>

                            <div class="divi-control-container">
                                <div class="divi-control divi-upload-style-wrap">
				    <?php /*
				    if (!isset($divi_settings['toggle_closed_text'])) {
					$divi_settings['toggle_closed_text'] = '';
				    }
					*/
				    ?>
                                    <input type="text" name="divi_settings[toggle_closed_text]" value="<?php //echo $divi_settings['toggle_closed_text'] ?>" />
                                </div>
                                <div class="divi-description">
                                    <p class="description"><?php //_e('Toggle text for closed html-items block. Example: open. By default applied sign plus "+"', 'products-filter-custom') ?></p>
                                </div>
                            </div>

                        </div>


                        <div class="toggle_type_image" <?php //if ($toggle_type == 'text'): ?>style="display: none;"<?php //endif; ?>>
                            <h4><?php //_e('Image for block toggle [<i>opened</i>]', 'products-filter-custom') ?></h4>

                            <div class="divi-control-container">
                                <div class="divi-control divi-upload-style-wrap">
				    <?php
					/*
				    if (!isset($divi_settings['toggle_opened_image'])) {
					$divi_settings['toggle_opened_image'] = '';
				    }
					*/
				    ?>
                                    <input type="text" name="divi_settings[toggle_opened_image]" value="<?php //echo(isset($divi_settings['toggle_opened_image']) ? $divi_settings['toggle_opened_image'] : '') ?>" />
                                    <a href="#" class="divi-button divi_select_image"><?php //_e('Select Image', 'products-filter-custom') ?></a>
                                </div>
                                <div class="divi-description">
                                    <p class="description"><?php //_e('Any image for opened html-items block 20x20', 'products-filter-custom') ?></p>
                                </div>
                            </div>


                            <h4><?php //_e('Image for block toggle [<i>closed</i>]', 'products-filter-custom') ?></h4>

                            <div class="divi-control-container">
                                <div class="divi-control divi-upload-style-wrap">
				    <?php
					/*
				    if (!isset($divi_settings['toggle_closed_image'])) {
					$divi_settings['toggle_closed_image'] = '';
				    }
					*/
				    ?>
                                    <input type="text" name="divi_settings[toggle_closed_image]" value="<?php //echo(isset($divi_settings['toggle_closed_image']) ? $divi_settings['toggle_closed_image'] : '') ?>" />
                                    <a href="#" class="divi-button divi_select_image"><?php //_e('Select Image', 'products-filter-custom') ?></a>
                                </div>
                                <div class="divi-description">
                                    <p class="description"><?php //_e('Any image for closed html-items block 20x20', 'products-filter-custom') ?></p>
                                </div>
                            </div>
                        </div>


                    </div>

		    <?php
			/*
		    if (!isset($divi_settings['custom_front_css'])) {
			$divi_settings['custom_front_css'] = '';
		    }
			*/
		    ?>

                    <div class="divi-control-section">

                        <h4><?php //_e('Custom front css styles file link', 'products-filter-custom') ?></h4>

                        <div class="divi-control-container">
                            <div class="divi-control">
                                <input type="text" name="divi_settings[custom_front_css]" value="<?php //echo $divi_settings['custom_front_css'] ?>" />
                            </div>
                            <div class="divi-description">
                                <p class="description"><?php //_e('For developers who want to rewrite front css of the plugin front side. You are need to know CSS for this!', 'products-filter-custom') ?></p>
                            </div>
                        </div>

                    </div>
            -->  
		    <?php do_action('divi_print_design_additional_options'); ?>
               
			   </div>
				  <?php include('preview_form.php'); ?>
			   <div class="preview_reset">
				   <input type="button" class="divi_reset_appren" style="float: right;" value="<?php _e('Reset', 'products-filter-custom') ?>" />
				   <input type="button" class="divi_hide_preview" style="float: right;" value="<?php _e('Hide preview', 'products-filter-custom') ?>" />
			   </div>	   
                </section>

                <section id="tabs-4">
                   <div class="content_left">
                    <div class="divi-tabs divi-tabs-style-line">                  
                        <div class="content-wrap">
                            <section id="tabs-41">
							   <div class="active_texonomy">
							      <div class="first_texo"><span class="active_checkbox_texo"><input type="checkbox" <?php if($divi_settings['color_texonomy'] == 1) { ?> checked <?php } ?> name="divi_settings[color_texonomy]" value="1">  Activate color texonomy </span>  <span class="more_info more_info_color" style="cursor: pointer;">More info</span></div>
								  <div class="more_info_color_div" style="display:none;"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p></div>
								  <div class="first_texo"><span class="active_checkbox_texo"><input type="checkbox" <?php if($divi_settings['brand_texonomy'] == 1) { ?> checked <?php } ?> name="divi_settings[brand_texonomy]" value="1">  Activate brand texonomy </span>  <span class="more_info more_info_brand" style="cursor: pointer; ">More info</span></div>
								  <div class="more_info_brand_div" style="display:none;"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p></div> 
								  <?php 
								  
								  //echo $divi_settings['color_texonomy'].'rrrrrrr';
								  
								 
								  ?>
								  <!--
								  <input type="text" name="divi_settings[color_texonomy]" value="0">
								  <input type="text" name="divi_settings[brand_texonomy]" value="0">
								  -->
								  
							   </div>
                                <table class="form-table">
                                    <tr>
                                        <!--<th scope="row"><label for="custom_css_code"><?php //_e('Custom CSS', 'products-filter-custom') ?></label></th>-->

                                        <td>
                                            <textarea placeholder="<?php _e('Custom CSS...', 'products-filter-custom') ?>" class="wide divi_custom_css1" id="custom_css_code" style="height: 300px; width: 100%;" name="divi_settings[custom_css_code]"><?php echo(isset($this->settings['custom_css_code']) ? stripcslashes($this->settings['custom_css_code']) : '') ?></textarea>
                                            <p class="description"><?php _e("No compilation errors", 'products-filter-custom') ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <!-- <th scope="row"><label for="js_after_ajax_done"><?php //_e('Custom JavaScript', 'products-filter-custom') ?></label></th> -->
                                        <td>
                                            <textarea  placeholder="<?php _e('Custom JavaScript...', 'products-filter-custom') ?>" class="wide divi_custom_css1" id="js_after_ajax_done" style="height: 300px; width: 100%;" name="divi_settings[js_after_ajax_done]"><?php echo(isset($this->settings['js_after_ajax_done']) ? stripcslashes($this->settings['js_after_ajax_done']) : '') ?></textarea>
                                            <p class="description"><?php _e('No compilation errors') ?></p>
                                        </td>
                                    </tr>
				    <?php if (class_exists('SitePress')): ?>
    				    <tr>
    					<th scope="row"><label for="wpml_tax_labels">
						    <?php _e('WPML taxonomies labels translations', 'products-filter-custom') ?> <img class="help_tip" data-tip="Syntax:
    						     es:Locations^Ubicaciones
    						     es:Size^Tamaño
    						     de:Locations^Lage
    						     de:Size^Größe" src="<?php echo WP_PLUGIN_URL ?>/woocommerce/assets/images/help.png" height="16" width="16" />
    					    </label></th>
    					<td>

						<?php
						$wpml_tax_labels = "";
						if (isset($divi_settings['wpml_tax_labels']) AND is_array($divi_settings['wpml_tax_labels'])) {
						    foreach ($divi_settings['wpml_tax_labels'] as $lang => $words) {
							if (!empty($words) AND is_array($words)) {
							    foreach ($words as $key_word => $translation) {
								$wpml_tax_labels .= $lang . ':' . $key_word . '^' . $translation . PHP_EOL;
							    }
							}
							//$first_value = reset($value); // First Element's Value
							//$first_key = key($value); // First Element's Key
						    }
						}
						?>

    					    <textarea class="wide divi_custom_css" id="wpml_tax_labels" style="height: 300px; width: 100%;" name="divi_settings[wpml_tax_labels]"><?php echo $wpml_tax_labels ?></textarea>
    					    <p class="description"><?php _e('Use it if you can not translate your custom taxonomies labels and attributes labels by another plugins.', 'products-filter-custom') ?></p>

    					</td>
    				    </tr>
				    <?php endif; ?>

                                </table>

                            </section>

                            <section id="tabs-42">

                                <div class="divi-control-section">

                                    <h5><?php _e('Search slug', 'products-filter-custom') ?></h5>

                                    <div class="divi-control-container">
                                        <div class="divi-control">

					    <?php
					    if (!isset($divi_settings['sdivi_search_slug'])) {
						$divi_settings['sdivi_search_slug'] = '';
					    }
					    ?>

                                            <input placeholder="sdivi" type="text" name="divi_settings[sdivi_search_slug]" value="<?php echo $divi_settings['sdivi_search_slug'] ?>" id="sdivi_search_slug" />

                                        </div>
                                        <div class="divi-description">
                                            <p class="description"><?php _e('If you do not like search key "sdivi" in the search link you can replace it by your own word. But be care to avoid conflicts with any themes and plugins, + never define it as symbol "s".<br /> Not understood? Simply do not touch it!', 'products-filter-custom') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .divi-control-section-->

                                <div class="divi-control-section">

                                    <h5><?php _e('Products per page', 'products-filter-custom') ?></h5>

                                    <div class="divi-control-container">
                                        <div class="divi-control">
					    <?php
					    if (!isset($divi_settings['per_page'])) {
						$divi_settings['per_page'] = -1;
					    }
					    ?>

                                            <input type="text" name="divi_settings[per_page]" value="<?php echo $divi_settings['per_page'] ?>" id="per_page" />
                                        </div>
                                        <div class="divi-description">
                                            <p class="description"><?php _e('Products per page when searching is going only. Set here -1 to prevent pagination managing from here!', 'products-filter-custom') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .divi-control-section-->

                                <div class="divi-control-section">

                                    <h5><?php _e("In the terms slugs uses non-latin characters", 'products-filter-custom') ?></h5>

                                    <div class="divi-control-container">
                                        <div class="divi-control">

					    <?php
					    $non_latin_mode = array(
						0 => __("No", 'products-filter-custom'),
						1 => __("Yes", 'products-filter-custom')
					    );
					    ?>

					    <?php
					    if (!isset($divi_settings['non_latin_mode']) OR empty($divi_settings['non_latin_mode'])) {
						$divi_settings['non_latin_mode'] = 0;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select name="divi_settings[non_latin_mode]">
						    <?php foreach ($non_latin_mode as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($divi_settings['non_latin_mode'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="divi-description">
                                            <p class="description"><?php _e("If your site taxonomies terms is in: russian, chinese, arabic, hebrew, persian, korean, japanese and any another non-latin characters language - set this option to Yes, better do it instantly after installation, because later if you will activate this option: color options for example - you will have to set them by hands again.", 'products-filter-custom') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .divi-control-section-->


				<div class="divi-control-section">

                                    <h5><?php _e("Optimize loading of DIVI JavaScript files", 'products-filter-custom') ?></h5>

                                    <div class="divi-control-container">
                                        <div class="divi-control">

					    <?php
					    $non_latin_mode = array(
						0 => __("No", 'products-filter-custom'),
						1 => __("Yes", 'products-filter-custom')
					    );
					    ?>

					    <?php
					    if (!isset($divi_settings['optimize_js_files']) OR empty($divi_settings['optimize_js_files'])) {
						$divi_settings['optimize_js_files'] = 0;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select name="divi_settings[optimize_js_files]">
													<?php foreach ($non_latin_mode as $key => $value) : ?>
														<option value="<?php echo $key; ?>" <?php if ($divi_settings['optimize_js_files'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
													<?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="divi-description">
                                            <p class="description"><?php _e("This option place DIVI JavaScript files on the site footer. Use it for page loading optimization. Be care with this option, and always after enabling of it test your site frontend!", 'products-filter-custom') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .divi-control-section-->


                                <!--
                                <div class="divi-control-section">

                                    <h5><?php _e("Storage type", 'products-filter-custom') ?></h5>

                                    <div class="divi-control-container">
                                        <div class="divi-control">

				<?php
				$storage_types = array(
				    'session' => 'session',
				    'transient' => 'transient'
				);
				?>

				<?php
				if (!isset($divi_settings['storage_type']) OR empty($divi_settings['storage_type'])) {
				    $divi_settings['storage_type'] = 'transient';
				}
				?>
                                            <div class="select-wrap">
                                                <select name="divi_settings[storage_type]">
				<?php foreach ($storage_types as $key => $value) : ?>
                                                                                                                                                                                                            <option value="<?php echo $key; ?>" <?php if ($divi_settings['storage_type'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
				<?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="divi-description">
                                            <p class="description"><?php _e("If you have troubles with relevant terms recount on categories pages with dynamic recount for not logged in users - select transient.", 'products-filter-custom') ?></p>
                                        </div>
                                    </div>

                                </div> -->

                                <div class="divi-control-section divi_premium_only">

                                    <h5><?php _e("Hide terms count text", 'products-filter-custom') ?></h5>

                                    <div class="divi-control-container">
                                        <div class="divi-control">

					    <?php
					    $hide_terms_count_txt = array(
						0 => __("No", 'products-filter-custom'),
					    );
					    ?>

					    <?php
					    if (!isset($divi_settings['hide_terms_count_txt']) OR empty($divi_settings['hide_terms_count_txt'])) {
						$divi_settings['hide_terms_count_txt'] = 0;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select name="divi_settings[hide_terms_count_txt]">
						    <?php foreach ($hide_terms_count_txt as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($divi_settings['hide_terms_count_txt'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="divi-description">
                                            <p class="description"><?php _e("If you want show relevant tags on the categories pages you should activate show count, dynamic recount and <b>hide empty terms</b> in the tab Options. But if you do not want show count (number) text near each term - set Yes here.", 'products-filter-custom') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .divi-control-section-->

                                <div class="divi-control-section">

                                    <h5><?php _e("Listen catalog visibility", 'products-filter-custom') ?></h5>

                                    <div class="divi-control-container">
                                        <div class="divi-control">

					    <?php
					    $listen_catalog_visibility = array(
						0 => __("No", 'products-filter-custom'),
						1 => __("Yes", 'products-filter-custom')
					    );
					    ?>

					    <?php
					    if (!isset($divi_settings['listen_catalog_visibility']) OR empty($divi_settings['listen_catalog_visibility'])) {
						$divi_settings['listen_catalog_visibility'] = 0;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select name="divi_settings[listen_catalog_visibility]">
						    <?php foreach ($listen_catalog_visibility as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($divi_settings['listen_catalog_visibility'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="divi-description">
                                            <p class="description">
						<?php _e("Listen catalog visibility - options in each product backend page in 'Publish' sidebar widget.", 'products-filter-custom') ?><br />
                                                <a href="<?php echo DIVI_LINK ?>img/plugin_options/listen_catalog_visibility.png" target="_blank"><img src="<?php echo DIVI_LINK ?>img/plugin_options/listen_catalog_visibility.png" width="150" alt="" /></a>
                                            </p>
                                        </div>
                                    </div>

                                </div><!--/ .divi-control-section-->


                                <div class="divi-control-section">

                                    <h5><?php _e("Disable sdivi influence", 'products-filter-custom') ?></h5>

                                    <div class="divi-control-container">
                                        <div class="divi-control">

					    <?php
					    $disable_sdivi_influence = array(
						0 => __("No", 'products-filter-custom'),
						1 => __("Yes", 'products-filter-custom')
					    );
					    ?>

					    <?php
					    if (!isset($divi_settings['disable_sdivi_influence']) OR empty($divi_settings['disable_sdivi_influence'])) {
						$divi_settings['disable_sdivi_influence'] = 0;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select name="divi_settings[disable_sdivi_influence]">
						    <?php foreach ($disable_sdivi_influence as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($divi_settings['disable_sdivi_influence'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="divi-description">
                                            <p class="description"><?php _e("Sometimes code '<code>wp_query->is_post_type_archive = true</code>' does not necessary. Try to disable this and try divi-search on your site. If all is ok - leave its disabled. Disabled code by this option you can find in index.php by mark disable_sdivi_influence.", 'products-filter-custom') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .divi-control-section-->

                                <div class="divi-control-section">

                                    <h5><?php _e("Cache dynamic recount number for each item in filter", 'products-filter-custom') ?></h5>

                                    <div class="divi-control-container">
                                        <div class="divi-control">

					    <?php
					    $cache_count_data = array(
						0 => __("No", 'products-filter-custom'),
						1 => __("Yes", 'products-filter-custom')
					    );
					    ?>

					    <?php
					    if (!isset($divi_settings['cache_count_data']) OR empty($divi_settings['cache_count_data'])) {
						$divi_settings['cache_count_data'] = 0;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select name="divi_settings[cache_count_data]">
						    <?php foreach ($cache_count_data as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($divi_settings['cache_count_data'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

					    <?php if ($divi_settings['cache_count_data']): ?>
    					    <br />
    					    <br /><a href="#" class="button js_cache_count_data_clear"><?php _e("clear cache", 'products-filter-custom') ?></a>&nbsp;<span style="color: green"></span><br />
    					    <br />
						<?php
						$clean_period = 0;
						if (isset($this->settings['cache_count_data_auto_clean'])) {
						    $clean_period = $this->settings['cache_count_data_auto_clean'];
						}
						$periods = array(
						    0 => __("do not clean cache automatically", 'products-filter-custom'),
						    'hourly' => __("clean cache automatically hourly", 'products-filter-custom'),
						    'twicedaily' => __("clean cache automatically twicedaily", 'products-filter-custom'),
						    'daily' => __("clean cache automatically daily", 'products-filter-custom'),
						    'days2' => __("clean cache automatically each 2 days", 'products-filter-custom'),
						    'days3' => __("clean cache automatically each 3 days", 'products-filter-custom'),
						    'days4' => __("clean cache automatically each 4 days", 'products-filter-custom'),
						    'days5' => __("clean cache automatically each 5 days", 'products-filter-custom'),
						    'days6' => __("clean cache automatically each 6 days", 'products-filter-custom'),
						    'days7' => __("clean cache automatically each 7 days", 'products-filter-custom')
						);
						?>
    					    <div class="select-wrap">
    						<select name="divi_settings[cache_count_data_auto_clean]">
							<?php foreach ($periods as $key => $txt): ?>
							    <option <?php selected($clean_period, $key) ?> value="<?php echo $key ?>"><?php echo $txt; ?></option>
							<?php endforeach; ?>
    						</select>
    					    </div>

					    <?php endif; ?>

                                        </div>
                                        <div class="divi-description">

					    <?php
					    global $wpdb;

					    $charset_collate = '';
					    if (method_exists($wpdb, 'has_cap') AND $wpdb->has_cap('collation')) {
						if (!empty($wpdb->charset)) {
						    $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
						}
						if (!empty($wpdb->collate)) {
						    $charset_collate .= " COLLATE $wpdb->collate";
						}
					    }
					    //***
					    $sql = "CREATE TABLE IF NOT EXISTS `" . DIVI::$query_cache_table . "` (
                                    `mkey` varchar(64) NOT NULL,
                                    `mvalue` text NOT NULL,
				    KEY `mkey` (`mkey`)
                                  ) {$charset_collate}";

					    if ($wpdb->query($sql) === false) {
						?>
    					    <p class="description"><?php _e("DIVI cannot create the database table! Make sure that your mysql user has the CREATE privilege! Do it manually using your host panel&phpmyadmin!", 'products-filter-custom') ?></p>
    					    <code><?php echo $sql; ?></code>
    					    <input type="hidden" name="divi_settings[cache_count_data]" value="0" />
						<?php
						echo $wpdb->last_error;
					    }
					    ?>

                                            <p class="description"><?php _e("Useful thing when you already set your site IN THE PRODUCTION MODE and use dynamic recount -> it make recount very fast! Of course if you added new products which have to be in search results you have to clean this cache OR you can set time period for auto cleaning!", 'products-filter-custom') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .divi-control-section-->



                                <div class="divi-control-section">

                                    <h5><?php _e("Cache terms", 'products-filter-custom') ?></h5>

                                    <div class="divi-control-container">
                                        <div class="divi-control">

					    <?php
					    $cache_terms = array(
						0 => __("No", 'products-filter-custom'),
						1 => __("Yes", 'products-filter-custom')
					    );
					    ?>

					    <?php
					    if (!isset($divi_settings['cache_terms']) OR empty($divi_settings['cache_terms'])) {
						$divi_settings['cache_terms'] = 0;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select name="divi_settings[cache_terms]">
						    <?php foreach ($cache_terms as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($divi_settings['cache_terms'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

					    <?php if ($divi_settings['cache_terms']): ?>
    					    <br />
    					    <br /><a href="#" class="button js_cache_terms_clear"><?php _e("clear terms cache", 'products-filter-custom') ?></a>&nbsp;<span style="color: green"></span><br />
    					    <br />
						<?php
						$clean_period = 0;
						if (isset($this->settings['cache_terms_auto_clean'])) {
						    $clean_period = $this->settings['cache_terms_auto_clean'];
						}
						$periods = array(
						    0 => __("do not clean cache automatically", 'products-filter-custom'),
						    'hourly' => __("clean cache automatically hourly", 'products-filter-custom'),
						    'twicedaily' => __("clean cache automatically twicedaily", 'products-filter-custom'),
						    'daily' => __("clean cache automatically daily", 'products-filter-custom'),
						    'days2' => __("clean cache automatically each 2 days", 'products-filter-custom'),
						    'days3' => __("clean cache automatically each 3 days", 'products-filter-custom'),
						    'days4' => __("clean cache automatically each 4 days", 'products-filter-custom'),
						    'days5' => __("clean cache automatically each 5 days", 'products-filter-custom'),
						    'days6' => __("clean cache automatically each 6 days", 'products-filter-custom'),
						    'days7' => __("clean cache automatically each 7 days", 'products-filter-custom')
						);
						?>
    					    <div class="select-wrap">
    						<select name="divi_settings[cache_terms_auto_clean]">
							<?php foreach ($periods as $key => $txt): ?>
							    <option <?php selected($clean_period, $key) ?> value="<?php echo $key ?>"><?php echo $txt; ?></option>
							<?php endforeach; ?>
    						</select>
    					    </div>

					    <?php endif; ?>

                                        </div>
                                        <div class="divi-description">
                                            <p class="description"><?php _e("Useful thing when you already set your site IN THE PRODUCTION MODE - its getting terms for filter faster without big MySQL queries! If you actively adds new terms every day or week you can set cron period for cleaning. Another way set: '<b>not clean cache automatically</b>'!", 'products-filter-custom') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .divi-control-section-->

                                <div class="divi-control-section">

                                    <h5><?php _e("Show blocks helper button", 'products-filter-custom') ?></h5>

                                    <div class="divi-control-container">
                                        <div class="divi-control">

					    <?php
					    $show_divi_edit_view = array(
						0 => __("No", 'products-filter-custom'),
						1 => __("Yes", 'products-filter-custom')
					    );
					    ?>

					    <?php
					    if (!isset($divi_settings['show_divi_edit_view'])) {
						$divi_settings['show_divi_edit_view'] = 1;
					    }
					    ?>
                                            <div class="select-wrap">
                                                <select id="show_divi_edit_view" name="divi_settings[show_divi_edit_view]">
						    <?php foreach ($show_divi_edit_view as $key => $value) : ?>
    						    <option value="<?php echo $key; ?>" <?php if ($divi_settings['show_divi_edit_view'] == $key): ?>selected="selected"<?php endif; ?>><?php echo $value; ?></option>
						    <?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="divi-description">
                                            <p class="description"><?php _e("Show helper button for shortcode [divi] on the front when 'Set filter automatically' is Yes", 'products-filter-custom') ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .divi-control-section-->

                                <div class="divi-control-section">

                                    <h5><?php _e('Custom extensions folder', 'products-filter-custom') ?></h5>

                                    <div class="divi-control-container">
                                        <div class="divi-control">
					    <?php
					    if (!isset($divi_settings['custom_extensions_path'])) {
						$divi_settings['custom_extensions_path'] = '';
					    }
					    ?>

                                            <input type="text" name="divi_settings[custom_extensions_path]" value="<?php echo $divi_settings['custom_extensions_path'] ?>" id="custom_extensions_path" placeholder="Example: my_divi_extensions" />
                                        </div>
                                        <div class="divi-description">
                                            <p class="description"><?php printf(__('Custom extensions folder path relative to: %s', 'products-filter-custom'), WP_CONTENT_DIR . DIRECTORY_SEPARATOR) ?></p>
                                        </div>
                                    </div>

                                </div><!--/ .divi-control-section-->

                            </section>

                        </div>

                    </div>
                  
				  </div>
				    <?php include('preview_form.php'); ?>
				   <div class="preview_reset">
					   <input type="button" class="divi_reset_appren" style="float: right;" value="<?php _e('Reset', 'products-filter-custom') ?>" />
					   <input type="button" class="divi_hide_preview" style="float: right;" value="<?php _e('Hide preview', 'products-filter-custom') ?>" />	
                   </div>				   
                </section>

<!-- ============================================================Advance-Design-hanu===========================================================================--->
		<?php
        $fontfamily = '<option value="-webkit-body">-webkit-body</option>';
        $fontfamily .= '<option value="serif">serif</option>';
		$fontfamily .= '<option value="cursive">cursive</option>';
		$fontfamily .= '<option value="sans-serif">sans-serif</option>';
		$fontfamily .= '<option value="monospace">monospace</option>';
		$fontfamily .= '<option value="fantasy">fantasy</option>';
		$fontfamily .= '<option value="Verdana, Geneva, sans-serif">Verdana, Geneva, sans-serif</option>';
		$fontfamily .= '<option value="Abel, sans-serif">Abel *</option>';
		$fontfamily .= '<option value="Abril Fatface">Abril Fatface *</option>';
		$fontfamily .= '<option value="Aclonica">Aclonica *</option>';
		$fontfamily .= '<option value="Acme">Acme *</option>';
		$fontfamily .= '<option value="Actor">Actor *</option>';
		$fontfamily .= '<option value="Adamina">Adamina *</option>';
		$fontfamily .= '<option value="Advent Pro">Advent Pro *</option>';
		$fontfamily .= '<option value="Aguafina Script">Aguafina Script *</option>';
		$fontfamily .= '<option value="Aladin">Aladin *</option>';
		$fontfamily .= '<option value="Aldrich">Aldrich *</option>';
		$fontfamily .= '<option value="Alegreya">Alegreya *</option>';
		$fontfamily .= '<option value="Alegreya SC">Alegreya SC *</option>';
		$fontfamily .= '<option value="Alex Brush">Alex Brush *</option>';
		$fontfamily .= '<option value="Alfa Slab One">Alfa Slab One *</option>';
		$fontfamily .= '<option value="Alice">Alice *</option>';
		$fontfamily .= '<option value="Alike">Alike *</option>';
		$fontfamily .= '<option value="Alike Angular">Alike Angular *</option>';
		$fontfamily .= '<option value="Allan">Allan *</option>';
		$fontfamily .= '<option value="Allerta">Allerta *</option>';
		$fontfamily .= '<option value="Allerta Stencil">Allerta Stencil *</option>';
		$fontfamily .= '<option value="Allura">Allura *</option>';
		$fontfamily .= '<option value="Almendra">Almendra *</option>';
		$fontfamily .= '<option value="Almendra SC">Almendra SC *</option>';
		$fontfamily .= '<option value="Amaranth">Amaranth *</option>';
		$fontfamily .= '<option value="Amatic SC">Amatic SC *</option>';
		$fontfamily .= '<option value="Amethysta">Amethysta *</option>';
		$fontfamily .= '<option value="Andada">Andada *</option>';
		$fontfamily .= '<option value="Andika">Andika *</option>';
		$fontfamily .= '<option value="Annie Use Your Telescope">Annie Use Your Telescope *</option>';
		$fontfamily .= '<option value="Anonymous Pro">Anonymous Pro *</option>';
		$fontfamily .= '<option value="Antic">Antic *</option>';
		$fontfamily .= '<option value="Antic Didone">Antic Didone *</option>';
		$fontfamily .= '<option value="Antic Slab">Antic Slab *</option>';
		$fontfamily .= '<option value="Anton">Anton *</option>';
		$fontfamily .= '<option value="Arapey">Arapey *</option>';
		$fontfamily .= '<option value="Arbutus">Arbutus *</option>';
		$fontfamily .= '<option value="Architects Daughter">Architects Daughter *</option>';
		$fontfamily .= '<option value="Archivo Narrow">Archivo Narrow *</option>';
		$fontfamily .= '<option value="Arimo">Arimo *</option>';
		$fontfamily .= '<option value="Arizonia">Arizonia *</option>';
		$fontfamily .= '<option value="Armata">Armata *</option>';
		$fontfamily .= '<option value="Artifika">Artifika *</option>';
		$fontfamily .= '<option value="Arvo">Arvo *</option>';
		$fontfamily .= '<option value="Asap">Asap *</option>';
		$fontfamily .= '<option value="Asset">Asset *</option>';
		$fontfamily .= '<option value="Astloch">Astloch *</option>';
		$fontfamily .= '<option value="Asul">Asul *</option>';
		$fontfamily .= '<option value="Atomic Age">Atomic Age *</option>';
		$fontfamily .= '<option value="Aubrey">Aubrey *</option>';
		$fontfamily .= '<option value="Audiowide">Audiowide *</option>';
		$fontfamily .= '<option value="Average">Average *</option>';
		$fontfamily .= '<option value="Averia Gruesa Libre">Averia Gruesa Libre *</option>';
		$fontfamily .= '<option value="Averia Libre">Averia Libre *</option>';
		$fontfamily .= '<option value="Averia Sans Libre">Averia Sans Libre *</option>';
		$fontfamily .= '<option value="Averia Serif Libre">Averia Serif Libre *</option>';
		$fontfamily .= '<option value="Bad Script">Bad Script *</option>';
		$fontfamily .= '<option value="Balthazar">Balthazar *</option>';
		$fontfamily .= '<option value="Bangers">Bangers *</option>';
		$fontfamily .= '<option value="Basic">Basic *</option>';
		$fontfamily .= '<option value="Baumans">Baumans *</option>';
		$fontfamily .= '<option value="Belgrano">Belgrano *</option>';
		$fontfamily .= '<option value="Belleza">Belleza *</option>';
		$fontfamily .= '<option value="BenchNine">BenchNine *</option>';
		$fontfamily .= '<option value="Bentham">Bentham *</option>';
		$fontfamily .= '<option value="Berkshire Swash">Berkshire Swash *</option>';
		$fontfamily .= '<option value="Bevan">Bevan *</option>';
		$fontfamily .= '<option value="Bigshot One">Bigshot One *</option>';
		$fontfamily .= '<option value="Bilbo">Bilbo *</option>';
		$fontfamily .= '<option value="Bilbo Swash Caps">Bilbo Swash Caps *</option>';
		$fontfamily .= '<option value="Bitter">Bitter *</option>';
		$fontfamily .= '<option value="Cabin">Cabin *</option>';
		$fontfamily .= '<option value="Cabin Condensed">Cabin Condensed *</option>';
		$fontfamily .= '<option value="Cabin Sketch">Cabin Sketch *</option>';
		$fontfamily .= '<option value="Caesar Dressing">Caesar Dressing *</option>';
		$fontfamily .= '<option value="Cagliostro">Cagliostro *</option>';
		$fontfamily .= '<option value="Calligraffitti">Calligraffitti *</option>';
		$fontfamily .= '<option value="Cambo">Cambo *</option>';
		$fontfamily .= '<option value="Candal">Candal *</option>';
		$fontfamily .= '<option value="Cantarell">Cantarell *</option>';
		$fontfamily .= '<option value="Cantata One">Cantata One *</option>';
		$fontfamily .= '<option value="Cardo">Cardo *</option>';
		$fontfamily .= '<option value="Carme">Carme *</option>';
		$fontfamily .= '<option value="Chango">Chango *</option>';
		$fontfamily .= '<option value="Cookie">Cookie *</option>';
		$fontfamily .= '<option value="Copse">Copse *</option>';
		$fontfamily .= '<option value="Damion">Damion *</option>';
		$fontfamily .= '<option value="Dancing Script">Dancing Script *</option>';
		$fontfamily .= '<option value="Dawning of a New Day">Dawning of a New Day *</option>';
		$fontfamily .= '<option value="Days One">Days One *</option>';
		$fontfamily .= '<option value="Delius">Delius *</option>';
		$fontfamily .= '<option value="Delius Swash Caps">Delius Swash Caps *</option>';
		$fontfamily .= '<option value="EB Garamond">EB Garamond *</option>';
		$fontfamily .= '<option value="Eater">Eater *</option>';
		$fontfamily .= '<option value="Economica">Economica *</option>';
		$fontfamily .= '<option value="Electrolize">Electrolize *</option>';
		$fontfamily .= '<option value="Emblema One">Emblema One *</option>';
		$fontfamily .= '<option value="Emilys Candy">Emilys Candy *</option>';
		$fontfamily .= '<option value="Engagement">Engagement *</option>';
		$fontfamily .= '<option value="Enriqueta">Enriqueta *</option>';
		$fontfamily .= '<option value="Erica One">Erica One *</option>';
		$fontfamily .= '<option value="Fanwood Text">Fanwood Text *</option>';
		$fontfamily .= '<option value="Fascinate">Fascinate *</option>';
		$fontfamily .= '<option value="Fascinate Inline">Fascinate Inline *</option>';
		$fontfamily .= '<option value="Federant">Federant *</option>';
		$fontfamily .= '<option value="Galdeano">Galdeano *</option>';
		$fontfamily .= '<option value="Gentium Basic">Gentium Basic *</option>';
		$fontfamily .= '<option value="Gentium Book Basic">Gentium Book Basic *</option>';
		$fontfamily .= '<option value="Geo">Geo *</option>';
		$fontfamily .= '<option value="Geostar">Geostar *</option>';
		$fontfamily .= '<option value="Geostar Fill">Geostar Fill *</option>';
		$fontfamily .= '<option value="Germania One">Germania One *</option>';
		$fontfamily .= '<option value="Habibi">Habibi *</option>';
		$fontfamily .= '<option value="Hammersmith One">Hammersmith One *</option>';
		$fontfamily .= '<option value="Handlee">Handlee *</option>';
		$fontfamily .= '<option value="IM Fell DW Pica">IM Fell DW Pica *</option>';
		$fontfamily .= '<option value="IM Fell DW Pica SC">IM Fell DW Pica SC *</option>';
		$fontfamily .= '<option value="IM Fell Double Pica">IM Fell Double Pica *</option>';
		$fontfamily .= '<option value="IM Fell Double Pica SC">IM Fell Double Pica SC *</option>';
		$fontfamily .= '<option value="Jim Nightshade">Jim Nightshade *</option>';
		$fontfamily .= '<option value="Jockey One">Jockey One *</option>';
		$fontfamily .= '<option value="Kameron">Kameron *</option>';
		$fontfamily .= '<option value="Karla">Karla *</option>';
		$fontfamily .= '<option value="Kaushan Script">Kaushan Script *</option>';
		$fontfamily .= '<option value="Kelly Slab">Kelly Slab *</option>';
		$fontfamily .= '<option value="Lato">Lato *</option>';
		$fontfamily .= '<option value="League Script">League Script *</option>';
		$fontfamily .= '<option value="Leckerli One">Leckerli One *</option>';
		$fontfamily .= '<option value="Ledger">Ledger *</option>';
		$fontfamily .= '<option value="Lekton">Lekton *</option>';
		$fontfamily .= '<option value="Love Ya Like A Sister">Love Ya Like A Sister *</option>';
		$fontfamily .= '<option value="Marvel">Marvel *</option>';
		$fontfamily .= '<option value="Mate">Mate *</option>';
		$fontfamily .= '<option value="Mate SC">Mate SC *</option>';
		$fontfamily .= '<option value="Maven Pro">Maven Pro *</option>';
		$fontfamily .= '<option value="Meddon">Meddon *</option>';
		$fontfamily .= '<option value="Neucha">Neucha *</option>';
		$fontfamily .= '<option value="Neuton">Neuton *</option>';
		$fontfamily .= '<option value="News Cycle">News Cycle *</option>';
		$fontfamily .= '<option value="Niconne">Niconne *</option>';
		$fontfamily .= '<option value="Nixie One">Nixie One *</option>';
		$fontfamily .= '<option value="Nobile">Nobile *</option>';
		$fontfamily .= '<option value="Overlock SC">Overlock SC *</option>';
		$fontfamily .= '<option value="Ovo">Ovo *</option>';
		$fontfamily .= '<option value="Oxygen">Oxygen *</option>';
		$fontfamily .= '<option value="PT Mono">PT Mono *</option>';
		$fontfamily .= '<option value="Quantico">Quantico *</option>';
		$fontfamily .= '<option value="Quattrocento">Quattrocento *</option>';
		$fontfamily .= '<option value="Quattrocento Sans">Quattrocento Sans *</option>';
		$fontfamily .= '<option value="Questrial">Questrial *</option>';
		$fontfamily .= '<option value="Radley">Radley *</option>';
		$fontfamily .= '<option value="Raleway">Raleway *</option>';
		$fontfamily .= '<option value="Rammetto One">Rammetto One *</option>';
		$fontfamily .= '<option value="Rancho">Rancho *</option>';
		$fontfamily .= '<option value="Sail">Sail *</option>';
		$fontfamily .= '<option value="Salsa">Salsa *</option>';
		$fontfamily .= '<option value="Sancreek">Sancreek *</option>';
		$fontfamily .= '<option value="Sansita One">Sansita One *</option>';
		$fontfamily .= '<option value="Sarina">Sarina *</option>';
		$fontfamily .= '<option value="Satisfy">Satisfy *</option>';
		$fontfamily .= '<option value="Schoolbell">Schoolbell *</option>';
		$fontfamily .= '<option value="Seaweed Script">Seaweed Script *</option>';
		$fontfamily .= '<option value="Tinos">Tinos *</option>';
		$fontfamily .= '<option value="Titan One">Titan One *</option>';
		$fontfamily .= '<option value="Trade Winds">Trade Winds *</option>';
		$fontfamily .= '<option value="Ubuntu">Ubuntu *</option>';
		$fontfamily .= '<option value="Ubuntu Condensed">Ubuntu Condensed *</option>';
		$fontfamily .= '<option value="Ubuntu Mono">Ubuntu Mono *</option>';
		$fontfamily .= '<option value="VT323">VT323 *</option>';
		$fontfamily .= '<option value="Varela">Varela *</option>';
		$fontfamily .= '<option value="Varela Round">Varela Round *</option>';
		$fontfamily .= '<option value="Vast Shadow">Vast Shadow *</option>';
		$fontfamily .= '<option value="Waiting for the Sunrise">Waiting for the Sunrise *</option>';
		$fontfamily .= '<option value="Wallpoet">Wallpoet *</option>';
		$fontfamily .= '<option value="Walter Turncoat">Walter Turncoat *</option>';
		$fontfamily .= '<option value="Wellfleet">Wellfleet *</option>';
		$fontfamily .= '<option value="Wire One">Wire One *</option>';
		$fontfamily .= '<option value="Yanone Kaffeesatz">Yanone Kaffeesatz *</option>';
		$fontfamily .= '<option value="Yellowtail">Yellowtail *</option>';
		$fontfamily .= '<option value="Zeyada">Zeyad *a</option>';
		?>       
                <section id="tabs-5">
				 <div class="content_left">
				  <div class="accordion"><span>Plugin Box</span></div>
				  <div class="ad_design_block">				  
					  <!--<h4>Plugin Box</h4>-->
					   <div class="div2block appreance1">
					      <label>Position</label>
						   <span class="for_drop_down_icon">
						   <select class="position_select" name="divi_settings[divi_box_position]">
						     <option value="right" <?php if($divi_settings['divi_box_position'] == 'right'){ echo 'selected';} ?>>right</option>
							 <option value="left" <?php if($divi_settings['divi_box_position'] == 'left'){ echo 'selected';} ?>>left</option>
						   </select>
                           </span> 						   
					   </div>
					   <div class="div2block pluginbgdiv">
                       
                        <div class="back_opacity">
                       
					      <label>Background</label>
						    <input type="text" class="cp1 color1" name="divi_settings[divi_box_backgroung]" id="preferredHex3" value="<?php echo $divi_settings['divi_box_backgroung']; ?>" />                           
                            </div>                       
                          <div class="back_opacity">   
						  <label class="width_small">Opacity</label>
						   <span class="for_drop_down_icon">
 						     <select name="divi_settings[divi_box_opacity]">
						     <option value="unset" <?php if($divi_settings['divi_box_opacity'] == 'unset'){ echo 'selected';} ?>>unset</option>
							 <option value="inherit" <?php if($divi_settings['divi_box_opacity'] == 'inherit'){ echo 'selected';} ?>>inherit</option>
							 <option value="initial" <?php if($divi_settings['divi_box_opacity'] == 'initial'){ echo 'selected';} ?>>initial</option>
							 <option value="0.1" <?php if($divi_settings['divi_box_opacity'] == 0.1){ echo 'selected';} ?>>0.1</option>
							 <option value="0.2" <?php if($divi_settings['divi_box_opacity'] == 0.2){ echo 'selected';} ?>>0.2</option>
							 <option value="0.3" <?php if($divi_settings['divi_box_opacity'] == 0.3){ echo 'selected';} ?>>0.3</option>
							 <option value="0.4" <?php if($divi_settings['divi_box_opacity'] == 0.4){ echo 'selected';} ?>>0.4</option>
							 <option value="0.5" <?php if($divi_settings['divi_box_opacity'] == 0.5){ echo 'selected';} ?>>0.5</option>
							 <option value="0.6" <?php if($divi_settings['divi_box_opacity'] == 0.6){ echo 'selected';} ?>>0.6</option>
							 <option value="0.7" <?php if($divi_settings['divi_box_opacity'] == 0.7){ echo 'selected';} ?>>0.7</option>
							 <option value="0.8" <?php if($divi_settings['divi_box_opacity'] == 0.8){ echo 'selected';} ?>>0.8</option>
							 <option value="0.9" <?php if($divi_settings['divi_box_opacity'] == 0.9){ echo 'selected';} ?>>0.9</option>
							 <option value="1" <?php if($divi_settings['divi_box_opacity'] == 1){ echo 'selected';} ?>>1</option>
							 <option value="1.1" <?php if($divi_settings['divi_box_opacity'] == 1.1){ echo 'selected';} ?>>1.1</option>
						   </select>
                           </span>
                           </div>
                           	
					   </div>
					   <div class="div2block plugin_box_border">
                       
                       <div class="border_radius">
					     <label>Border</label>
						    <input type="text" name="divi_settings[divi_box_border_size]" value="<?php if($divi_settings['divi_box_border_size'] != '') { echo $divi_settings['divi_box_border_size']; } else { echo '1'; } ?>" />
							<span class="for_drop_down_icon">
							 <select name="divi_settings[divi_box_border_type]">
								 <option value="solid" <?php if($divi_settings['divi_box_border_type'] == 'solid'){ echo 'selected';} ?>>solid</option>
								 <option value="dotted" <?php if($divi_settings['divi_box_border_type'] == 'dotted'){ echo 'selected';} ?>>dotted</option>
								 <option value="dashed" <?php if($divi_settings['divi_box_border_type'] == 'dashed'){ echo 'selected';} ?>>dashed</option>
								 <option value="none" <?php if($divi_settings['divi_box_border_type'] == 'none'){ echo 'selected';} ?>>none</option>
							 </select>
							</span>
							<input type="text" class="cp1 color2" name="divi_settings[divi_box_border_color]" value="<?php echo $divi_settings['divi_box_border_color']; ?>" />
                        </div> 
                         <div class="border_radius">    
						 <label class="width_small">Radius</label>	
						    <input type="text" name="divi_settings[divi_box_radius]" value="<?php if($divi_settings['divi_box_radius'] != '') {echo $divi_settings['divi_box_radius']; } else{ echo '0'; }?>" />
                         </div>
                            
					   </div>
                       
                       
					   <div class="div2block">
					     <label>Max-width (%)</label>
						    <input type="text" name="divi_settings[divi_box_maxwidth]" value="<?php if($divi_settings['divi_box_maxwidth'] != '') { echo $divi_settings['divi_box_maxwidth']; } else { echo '30'; } ?>" />
					   </div>
					   <div class="div2block">
					     <label>Max-height</label>
						    <input type="text" name="divi_settings[divi_box_maxheight]" value="<?php if($divi_settings['divi_box_maxheight'] != '') { echo $divi_settings['divi_box_maxheight']; } else { echo '400'; } ?>" />
					   </div>
                       
                       
                       <div class="div2block">
					     <label>Screen opacity</label>
						    <input type="text" name="divi_settings[divi_screen_opacity]" value="<?php echo $divi_settings['divi_screen_opacity']; ?>" />
					   </div>
				  </div> 
                  <div class="accordion"><span>Filter Name</span></div>
                  <div class="ad_design_block">
					   <div class="div2block">
					     <div class="color_filter">
						   <label>Color</label>
						   <input type="text" class="cp1 color3" name="divi_settings[divi_filter_name_color]" value="<?php echo $divi_settings['divi_filter_name_color']; ?>" />
						 </div>
					   </div>
					   <div class="div2block">
					   <label>Font-Family</label>
					   <span class="for_drop_down_icon">
					   <select name="divi_settings[divi_filter_name_fontfamily]" class="family-width"> 
						 <option value="<?php echo $divi_settings['divi_filter_name_fontfamily'] ?>"><?php echo $divi_settings['divi_filter_name_fontfamily'] ?></option>
						 <?php echo $fontfamily; ?>
					   </select>
					   </span>
					   </div>
					   <div class="div2block">
					     <div class="color_filter">
						   <label>Size</label>
						   <input type="text" name="divi_settings[divi_filter_name_size]" value="<?php if($divi_settings['divi_filter_name_size'] != '') { echo $divi_settings['divi_filter_name_size']; } else{ echo '13'; } ?>" />
						 </div>
						 <div>
						   <label>Weight</label>
						   <span class="for_drop_down_icon">
						   <select class="apply_select" name="divi_settings[divi_filter_name_weight]">
						     <option value="100" <?php if($divi_settings['divi_filter_name_weight'] == 100){ echo 'selected';} ?>>100</option>
							 <option value="200" <?php if($divi_settings['divi_filter_name_weight'] == 200){ echo 'selected';} ?>>200</option>
							 <option value="300" <?php if($divi_settings['divi_filter_name_weight'] == 300){ echo 'selected';} ?>>300</option>
							 <option value="400" <?php if($divi_settings['divi_filter_name_weight'] == 400){ echo 'selected';} ?>>400</option>
							 <option value="500" <?php if($divi_settings['divi_filter_name_weight'] == 500){ echo 'selected';} ?>>500</option>						 
							 <option value="bold" <?php if($divi_settings['divi_filter_name_weight'] == 'bold'){ echo 'selected';} ?>>bold</option>
							 <option value="bolder" <?php if($divi_settings['divi_filter_name_weight'] == 'bolder'){ echo 'selected';} ?>>bolder</option>
							 <option value="normal" <?php if($divi_settings['divi_filter_name_weight'] == 'normal'){ echo 'selected';} ?>>normal</option>
						   </select> 
						   </span>
						 </div>
						 <div>
						   <label>Line Height</label>
						   <input type="text" name="divi_settings[divi_filter_name_lineheight]" value="<?php if($divi_settings['divi_filter_name_lineheight'] != '') { echo $divi_settings['divi_filter_name_lineheight']; } else{ echo '12'; } ?>" />
						 </div>
					   </div>
					   <div class="div2block">
					     <label>Padding</label>
						    <input type="text" name="divi_settings[divi_filter_name_top]" value="<?php if($divi_settings['divi_filter_name_top'] != '') { echo $divi_settings['divi_filter_name_top']; } else { echo '5'; }  ?>" />
							<input type="text" name="divi_settings[divi_filter_name_right]" value="<?php if($divi_settings['divi_filter_name_right'] != '') { echo $divi_settings['divi_filter_name_right']; } else { echo '5'; } ?>" />
							<input type="text" name="divi_settings[divi_filter_name_buttom]" value="<?php if($divi_settings['divi_filter_name_buttom'] != '') { echo $divi_settings['divi_filter_name_buttom']; } else { echo '5'; } ?>" />
							<input type="text" name="divi_settings[divi_filter_name_left]" value="<?php if($divi_settings['divi_filter_name_left'] != '') { echo $divi_settings['divi_filter_name_left']; } else { echo '5'; } ?>" />
					   </div>
				  </div>         
                  <div class="accordion"><span>Filter Content</span></div>
                  <div class="ad_design_block">
					   <div class="div2block">
					     <div class="color_filter">
						   <label>Color</label>
						   <input type="text" class="cp1 color4" name="divi_settings[divi_filter_content_color]" value="<?php echo $divi_settings['divi_filter_content_color']; ?>" />
						 </div>
					   </div>
					   <div class="div2block">
					   <label>Font-Family</label>
					   <span class="for_drop_down_icon">
					   <select name="divi_settings[divi_filter_content_fontfamily]" class="family-width"> 
						 <option value="<?php echo $divi_settings['divi_filter_content_fontfamily'] ?>"><?php echo $divi_settings['divi_filter_content_fontfamily']; ?></option>
						 <?php echo $fontfamily; ?>
					   </select>
					   </span>
					   </div>
					   <div class="div2block">
					     <div class="color_filter">
						   <label>Size</label>
						   <input type="text" name="divi_settings[divi_filter_content_size]" value="<?php if($divi_settings['divi_filter_content_size'] != '') { echo $divi_settings['divi_filter_content_size']; } else{ echo '12'; } ?>" />
						 </div>
						 <div>
						   <label>Weight</label>
						   <span class="for_drop_down_icon">
						   <select class="apply_select" name="divi_settings[divi_filter_content_weight]">
						     <option value="100" <?php if($divi_settings['divi_filter_content_weight'] == 100){ echo 'selected';} ?>>100</option>
							 <option value="200" <?php if($divi_settings['divi_filter_content_weight'] == 200){ echo 'selected';} ?>>200</option>
							 <option value="300" <?php if($divi_settings['divi_filter_content_weight'] == 300){ echo 'selected';} ?>>300</option>
							 <option value="400" <?php if($divi_settings['divi_filter_content_weight'] == 400){ echo 'selected';} ?>>400</option>
							 <option value="500" <?php if($divi_settings['divi_filter_content_weight'] == 500){ echo 'selected';} ?>>500</option>						 
							 <option value="bold" <?php if($divi_settings['divi_filter_content_weight'] == 'bold'){ echo 'selected';} ?>>bold</option>
							 <option value="bolder" <?php if($divi_settings['divi_filter_content_weight'] == 'bolder'){ echo 'selected';} ?>>bolder</option>
							 <option value="normal" <?php if($divi_settings['divi_filter_content_weight'] == 'normal'){ echo 'selected';} ?>>normal</option>
						   </select> 
						   </span>
						 </div>
						 <div>
						   <label>Line Height</label>
						   <input type="text" name="divi_settings[divi_filter_content_lineheight]" value="<?php if($divi_settings['divi_filter_content_lineheight'] != '') { echo $divi_settings['divi_filter_content_lineheight']; } else { echo '12'; } ?>" />
						 </div>
					   </div>
					   <div class="div2block">
					     <label>Padding</label>
						    <input type="text" name="divi_settings[divi_filter_content_top]" value="<?php if($divi_settings['divi_filter_content_top'] != '') { echo $divi_settings['divi_filter_content_top']; } else { echo '5'; } ?>" />
							<input type="text" name="divi_settings[divi_filter_content_right]" value="<?php if($divi_settings['divi_filter_content_right'] != '') { echo $divi_settings['divi_filter_content_right']; } else { echo '5'; } ?>" />
							<input type="text" name="divi_settings[divi_filter_content_buttom]" value="<?php if($divi_settings['divi_filter_content_buttom'] != '') { echo $divi_settings['divi_filter_content_buttom']; } else { echo '5'; } ?>" />
							<input type="text" name="divi_settings[divi_filter_content_left]" value="<?php if($divi_settings['divi_filter_content_left'] != '') { echo $divi_settings['divi_filter_content_left']; } else { echo '5'; } ?>" />
					   </div>
				  </div>     
                  <div class="accordion"><span>Buttons</span></div>
                  <div class="ad_design_block">
					   <div class="div2block">
					    <label>Box-Shadow</label>
					    <input type="text" style="float: left;margin-right: 8px;" name="divi_settings[divi_box_show1]" value="<?php if($divi_settings['divi_box_show1'] != '') { echo $divi_settings['divi_box_show1']; } else { echo '0'; } ?>" />
					    <input type="text" style="float: left;margin-right: 8px;" name="divi_settings[divi_box_show2]" value="<?php if($divi_settings['divi_box_show2'] != '') { echo $divi_settings['divi_box_show2']; } else { echo '0'; } ?>" />
					    <input type="text" style="float: left;margin-right: 8px;" name="divi_settings[divi_box_show3]" value="<?php if($divi_settings['divi_box_show3'] != '') { echo $divi_settings['divi_box_show3']; } else { echo '0'; } ?>" />
					    <input type="text" style="float: left;margin-right: 8px;" name="divi_settings[divi_box_show4]" value="<?php if($divi_settings['divi_box_show4'] != '') { echo $divi_settings['divi_box_show4']; } else { echo '0'; } ?>" />
                        <input type="text" class="cp1 color15" name="divi_settings[divi_box_show5]" value="<?php echo $divi_settings['divi_box_show5'] ?>" />
					   </div>
				  </div>
                  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
				  <div class="accordion11" style="color:#ccc;"><span>Main Button</span></div>	
                  <div class="ad_design_block mainbtn_div" style="overflow:visible; position:relative; max-height: none;">
					  <div class="div2block">
					   <label>Icon</label>
					     <span class="icon1 iconfont"><i class="fa fa-sliders" aria-hidden="true"></i> 
						 <input type="radio" value='<i class="fa fa-sliders" aria-hidden="true"></i>' name="divi_settings[divi_mainbutton_font_icon]" <?php if($divi_settings['divi_mainbutton_font_icon'] == '<i class="fa fa-sliders" aria-hidden="true"></i>'){ ?> checked <?php } ?>/>
						 </span>
						 
						 <span class="icon2 iconfont"><i class="fa fa-align-left" aria-hidden="true"></i> 
						 <input type="radio" value= '<i class="fa fa-align-left" aria-hidden="true"></i>' name="divi_settings[divi_mainbutton_font_icon]" <?php if($divi_settings['divi_mainbutton_font_icon'] == '<i class="fa fa-align-left" aria-hidden="true"></i>'){ ?> checked <?php } ?> />
						 </span>

						 <span class="icon3 iconfont"><i class="fa fa-align-justify" aria-hidden="true"></i> 
						 <input type="radio" value= '<i class="fa fa-align-justify" aria-hidden="true"></i>' name="divi_settings[divi_mainbutton_font_icon]" <?php if($divi_settings['divi_mainbutton_font_icon'] == '<i class="fa fa-align-justify" aria-hidden="true"></i>'){ ?> checked <?php } ?> />
						 </span>
						 
						 <span class="icon4 iconfont"><i class="fa fa-list" aria-hidden="true"></i> 
						 <input type="radio" value = '<i class="fa fa-list" aria-hidden="true"></i>' name="divi_settings[divi_mainbutton_font_icon]"  <?php if($divi_settings['divi_mainbutton_font_icon'] == '<i class="fa fa-list" aria-hidden="true"></i> '){ ?> checked <?php } ?> />
						 </span>					  
					  </div>
                      
                      <div class="div2block" style="display:none;">
					   <label>Icon</label>
					     
                         <span><img src="http://demo4customer.com/projects/plugin/wp-content/plugins/products-filter-custom/display_data/lineder.jpg"></span>
                    				  
					  </div>
				
				
					  <div class="div2block">
					     <div class="color_filter">
						   <label>Color</label>
						   <input type="text" class="cp1 color5" name="divi_settings[divi_mainbutton_color]" id="preferredHex3" value="<?php echo $divi_settings['divi_mainbutton_color']; ?>" />
						 </div>
						 <div>
						   <label style="width:88px; font-size: 13px;">Back-ground</label>
						   <input type="text" class="cp1 color6" name="divi_settings[divi_mainbutton_bgcolor]" value="<?php echo $divi_settings['divi_mainbutton_bgcolor']; ?>" />
						 </div>
					   </div>
					   <div class="div2block border_main_button">
					   <div class="border_radius">
					     <label>Border</label>
						    <input type="text" name="divi_settings[divi_mainbutton_bsize]" value="<?php if($divi_settings['divi_mainbutton_bsize'] != '') { echo $divi_settings['divi_mainbutton_bsize']; } else { echo '0'; } ?>" />
							<span class="for_drop_down_icon">
							 <select name="divi_settings[divi_mainbutton_border_type]">
								 <option value="solid" <?php if($divi_settings['divi_mainbutton_border_type'] == 'solid'){ echo 'selected';} ?>>solid</option>
								 <option value="dotted" <?php if($divi_settings['divi_mainbutton_border_type'] == 'dotted'){ echo 'selected';} ?>>dotted</option>
								 <option value="dashed" <?php if($divi_settings['divi_mainbutton_border_type'] == 'dashed'){ echo 'selected';} ?>>dashed</option>
								 <option value="none" <?php if($divi_settings['divi_mainbutton_border_type'] == 'none'){ echo 'selected';} ?>>none</option>
							 </select>
							</span>
							<input type="text" class="cp1 color7" name="divi_settings[divi_mainbutton_bcolor]" value="<?php echo $divi_settings['divi_mainbutton_bcolor']; ?>" />
                        </div> 
                         <div class="border_radius">    
						 <label class="width_small">Radius</label>	
						    <input type="text" name="divi_settings[divi_mainbutton_radius]" value="<?php echo $divi_settings['divi_mainbutton_radius']; ?>" />
                         </div>    
					   </div>
					   <div class="div2block">
					     <div class="color_filter">
						   <label>Size</label>
						   <input type="text" name="divi_settings[divi_mainbutton_size]" value="<?php if($divi_settings['divi_mainbutton_size'] != '') { echo $divi_settings['divi_mainbutton_size']; } else { echo '45'; }?>" />
						 </div>
                       </div>
                       <div class="div2block">
					     <label>Padding</label>
						    <input type="text" name="divi_settings[divi_mainbutton_top]" value="<?php if($divi_settings['divi_mainbutton_top'] != '') { echo $divi_settings['divi_mainbutton_top']; } else { echo '1'; } ?>" />
							<input type="text" name="divi_settings[divi_mainbutton_right]" value="<?php if($divi_settings['divi_mainbutton_right'] != '') { echo $divi_settings['divi_mainbutton_right']; } else { echo '25'; } ?>" />
							<input type="text" name="divi_settings[divi_mainbutton_buttom]" value="<?php if($divi_settings['divi_mainbutton_buttom'] != '') { echo $divi_settings['divi_mainbutton_buttom']; } else { echo '1'; } ?>" />
							<input type="text" name="divi_settings[divi_mainbutton_left]" value="<?php if($divi_settings['divi_mainbutton_left'] != '') { echo $divi_settings['divi_mainbutton_left']; } else { echo '25'; } ?>" />
					   </div>					    
				  </div>  
                  <div class="accordion11" style="color:#ccc;"><span>Apply</span><span class="pencil"><i class="fa fa-pencil"></i></span></div>
				  <div class="ad_design_block" style="overflow:visible; position:relative; max-height: none;">
					   <div class="div2block apply_color">
					     <div class="color_filter">
						   <label>Color</label> 
						   <input type="text" class="cp1 color8" name="divi_settings[divi_apply_color]" id="preferredHex3" value="<?php echo $divi_settings['divi_apply_color']; ?>" />
						 </div>
						 <div>
						   <label style="width:88px; font-size: 13px;">Back-ground</label>
						   <input type="text" class="cp1 color9" name="divi_settings[divi_apply_bgcolor]" value="<?php echo $divi_settings['divi_apply_bgcolor']; ?>" />
						 </div>
					   </div>
					   <div class="div2block apply_border">
					     <label>Border</label>
						    <input type="text" name="divi_settings[divi_apply_bsize]" value="<?php if($divi_settings['divi_apply_bsize'] != '') { echo $divi_settings['divi_apply_bsize']; } else { echo '0'; } ?>" />
							<span class="for_drop_down_icon">
							<select name="divi_settings[divi_apply_border_type]">
								<option value="solid" <?php if($divi_settings['divi_apply_border_type'] == 'solid'){ echo 'selected';} ?>>solid</option>
								<option value="dotted" <?php if($divi_settings['divi_apply_border_type'] == 'dotted'){ echo 'selected';} ?>>dotted</option>
								<option value="dashed" <?php if($divi_settings['divi_apply_border_type'] == 'dashed'){ echo 'selected';} ?>>dashed</option>
								<option value="none" <?php if($divi_settings['divi_apply_border_type'] == 'none'){ echo 'selected';} ?>>none</option>
							</select>
							</span>
							<input class="color_code cp1 color10" type="text" name="divi_settings[divi_apply_bcolor]" value="<?php echo $divi_settings['divi_apply_bcolor']; ?>" />
					    </div>
					   
					   <div class="div2block">
					   <label>Font-Family</label>
                       
                       <span class="for_drop_down_icon">
					   <select name="divi_settings[divi_apply_fontfamily]" class="family-width"> 
						 <option value="<?php echo $divi_settings['divi_apply_fontfamily'] ?>"><?php echo $divi_settings['divi_apply_fontfamily']; ?></option>
						 <?php echo $fontfamily; ?>
					   </select>
                       
                       </span>
					   </div>
					   
					   <div class="div2block">
					     <div class="color_filter">
						   <label>Size</label>
						   <input type="text" name="divi_settings[divi_apply_size]" value="<?php if($divi_settings['divi_apply_size'] != '') { echo $divi_settings['divi_apply_size']; } else { echo '14'; }?>" />
						 </div>
						 <div>
						   <label>Weight</label>
						   <span class="for_drop_down_icon">
						   <select class="apply_select" name="divi_settings[divi_apply_weight]">
						     <option value="100" <?php if($divi_settings['divi_apply_weight'] == 100){ echo 'selected';} ?>>100</option>
							 <option value="200" <?php if($divi_settings['divi_apply_weight'] == 200){ echo 'selected';} ?>>200</option>
							 <option value="300" <?php if($divi_settings['divi_apply_weight'] == 300){ echo 'selected';} ?>>300</option>
							 <option value="400" <?php if($divi_settings['divi_apply_weight'] == 400){ echo 'selected';} ?>>400</option>
							 <option value="500" <?php if($divi_settings['divi_apply_weight'] == 500){ echo 'selected';} ?>>500</option>						 
							 <option value="bold" <?php if($divi_settings['divi_apply_weight'] == 'bold'){ echo 'selected';} ?>>bold</option>
							 <option value="bolder" <?php if($divi_settings['divi_apply_weight'] == 'bolder'){ echo 'selected';} ?>>bolder</option>
							 <option value="normal" <?php if($divi_settings['divi_apply_weight'] == 'normal'){ echo 'selected';} ?>>normal</option>
						   </select> 
						   </span>
						 </div>
					   </div>
				   </div>
				   <div class="accordion11" style="color:#ccc;"><span>Reset</span><span class="pencil"><i class="fa fa-pencil"></i></span></div>
                   <div class="ad_design_block" style="overflow:visible; position:relative; max-height: none;">
					   <div class="div2block reset_color">
					     <div class="color_filter">
						   <label>Color</label>
						   <input type="text" class="cp1 color11" name="divi_settings[divi_reset_color]" value="<?php echo $divi_settings['divi_reset_color']; ?>" />
						 </div>
						 <div>
						   <label style="width:88px; font-size:13px;">Fondo</label>
						   <input type="text" class="cp1 color12" name="divi_settings[divi_reset_bgcolor]" value="<?php echo $divi_settings['divi_reset_bgcolor']; ?>" />
						 </div>
					   </div>
					   <div class="div2block reset_border">
					     <label>Border</label>
						    <input type="text" name="divi_settings[divi_reset_bsize]" value="<?php if($divi_settings['divi_reset_bsize'] != '') { echo $divi_settings['divi_reset_bsize']; } else { echo '0';} ?>" />
							<span class="for_drop_down_icon">
							 <select name="divi_settings[divi_reset_bsize_border_type]">
								 <option value="solid" <?php if($divi_settings['divi_reset_bsize_border_type'] == 'solid'){ echo 'selected';} ?>>solid</option>
								 <option value="dotted" <?php if($divi_settings['divi_reset_bsize_border_type'] == 'dotted'){ echo 'selected';} ?>>dotted</option>
								 <option value="dashed" <?php if($divi_settings['divi_reset_bsize_border_type'] == 'dashed'){ echo 'selected';} ?>>dashed</option>
								 <option value="none" <?php if($divi_settings['divi_reset_bsize_border_type'] == 'none'){ echo 'selected';} ?>>none</option>
							 </select>
							</span>
							<input type="text" class="cp1 color13" name="divi_settings[divi_reset_bcolor]" value="<?php echo $divi_settings['divi_reset_bcolor']; ?>" />
					   </div>
					   <div class="div2block">
					   <label>Font-Family</label>
					   <span class="for_drop_down_icon">
					   <select name="divi_settings[divi_reset_fontfamily]" class="family-width"> 
						 <option value="<?php echo $divi_settings['divi_reset_fontfamily'] ?>"><?php echo $divi_settings['divi_reset_fontfamily']; ?></option>
						 <?php echo $fontfamily; ?>
					   </select>
					   </span>
					   </div>
					   <div class="div2block">
					     <div class="color_filter"> 
						   <label>Size</label>
						   <input type="text" name="divi_settings[divi_reset_size]" value="<?php if($divi_settings['divi_reset_size'] != '') { echo $divi_settings['divi_reset_size']; } else { echo '14'; } ?>" />
						 </div>
						 <div>
						   <label>Weight</label>
						   <span class="for_drop_down_icon">
						   <select class="apply_select" name="divi_settings[divi_reset_weight]">
						     <option value="100" <?php if($divi_settings['divi_reset_weight'] == 100){ echo 'selected';} ?>>100</option>
							 <option value="200" <?php if($divi_settings['divi_reset_weight'] == 200){ echo 'selected';} ?>>200</option>
							 <option value="300" <?php if($divi_settings['divi_reset_weight'] == 300){ echo 'selected';} ?>>300</option>
							 <option value="400" <?php if($divi_settings['divi_reset_weight'] == 400){ echo 'selected';} ?>>400</option>
							 <option value="500" <?php if($divi_settings['divi_reset_weight'] == 500){ echo 'selected';} ?>>500</option>						 
							 <option value="bold" <?php if($divi_settings['divi_reset_weight'] == 'bold'){ echo 'selected';} ?>>bold</option>
							 <option value="bolder" <?php if($divi_settings['divi_reset_weight'] == 'bolder'){ echo 'selected';} ?>>bolder</option>
							 <option value="normal" <?php if($divi_settings['divi_reset_weight'] == 'normal'){ echo 'selected';} ?>>normal</option>
						   </select>
						   </span>
						 </div>
					   </div>
				  </div>                 
                  <div class="accordion"><span>Filter Title Name</span></div>
                  <div class="ad_design_block">
					   <div class="div2block">
					     <div class="color_filter">
						   <label>Color</label>
						   <input type="text" class="cp1 color14" name="divi_settings[divi_filter_title_color]" value="<?php echo $divi_settings['divi_filter_title_color']; ?>" />
						 </div>
					   </div>
					   <div class="div2block">
					   <label>Font-Family</label>
					   <span class="for_drop_down_icon">
					   <select name="divi_settings[divi_filter_title_fontfamily]" class="family-width"> 
						 <option value="<?php echo $divi_settings['divi_filter_title_fontfamily'] ?>"><?php echo $divi_settings['divi_filter_title_fontfamily']; ?></option>
						 <?php echo $fontfamily; ?>
					   </select>
					   </span>
					   </div>
					   <div class="div2block">
					     <div class="color_filter">
						   <label>Size</label>
						   <input type="text" name="divi_settings[divi_filter_title_size]" value="<?php if($divi_settings['divi_filter_title_size'] != '') { echo $divi_settings['divi_filter_title_size']; } else { echo '16'; } ?>" />
						 </div>
						 <div>
						   <label>Weight</label>
						   <span class="for_drop_down_icon">
						   <select class="apply_select" name="divi_settings[divi_filter_title_weight]">
						     <option value="100" <?php if($divi_settings['divi_filter_title_weight'] == 100){ echo 'selected';} ?>>100</option>
							 <option value="200" <?php if($divi_settings['divi_filter_title_weight'] == 200){ echo 'selected';} ?>>200</option>
							 <option value="300" <?php if($divi_settings['divi_filter_title_weight'] == 300){ echo 'selected';} ?>>300</option>
							 <option value="400" <?php if($divi_settings['divi_filter_title_weight'] == 400){ echo 'selected';} ?>>400</option>
							 <option value="500" <?php if($divi_settings['divi_filter_title_weight'] == 500){ echo 'selected';} ?>>500</option>						 
							 <option value="bold" <?php if($divi_settings['divi_filter_title_weight'] == 'bold'){ echo 'selected';} ?>>bold</option>
							 <option value="bolder" <?php if($divi_settings['divi_filter_title_weight'] == 'bolder'){ echo 'selected';} ?>>bolder</option>
							 <option value="normal" <?php if($divi_settings['divi_filter_title_weight'] == 'normal'){ echo 'selected';} ?>>normal</option>
						   </select> 
						   </span>
						 </div>
					   </div>
					   <div class="div2block">
					     <label>Padding</label>
						    <input type="text" name="divi_settings[divi_filter_title_top]" value="<?php if($divi_settings['divi_filter_title_top'] != '') { echo $divi_settings['divi_filter_title_top']; } else { echo '5'; } ?>" />
							<input type="text" name="divi_settings[divi_filter_title_right]" value="<?php if($divi_settings['divi_filter_title_right'] != '') { echo $divi_settings['divi_filter_title_right']; } else { echo '5'; } ?>" />
							<input type="text" name="divi_settings[divi_filter_title_buttom]" value="<?php if($divi_settings['divi_filter_title_buttom'] != '') { echo $divi_settings['divi_filter_title_buttom']; } else { echo '5'; } ?>" />
							<input type="text" name="divi_settings[divi_filter_title_left]" value="<?php if($divi_settings['divi_filter_title_left'] != '') { echo $divi_settings['divi_filter_title_left']; } else { echo '5'; } ?>" />
					   </div>
				  </div>   				  
                 <script>
					var acc = document.getElementsByClassName("accordion");
					var i;
					for (i = 0; i < acc.length; i++) {
					  acc[i].onclick = function() {
						this.classList.toggle("active");
						var ad_design_block = this.nextElementSibling;
						if (ad_design_block.style.maxHeight){
						  ad_design_block.style.maxHeight = null;
						} else {
						  ad_design_block.style.maxHeight = ad_design_block.scrollHeight + "px";
						} 
					  }
					}	
					</script> 
				  </div>	
				    <?php include('preview_form.php'); ?>
				  <div class="preview_reset">
				  <input type="button" class="divi_reset_appren" style="float: right;" value="<?php _e('Reset', 'products-filter-custom') ?>" />
				  <input type="button" class="divi_hide_preview" style="float: right;" value="<?php _e('Hide preview', 'products-filter-custom') ?>" />
				  </div>
				</section>
		<?php
		if (!empty(DIVI_EXT::$includes['applications'])) {
		    foreach (DIVI_EXT::$includes['applications'] as $obj) {
			$dir1 = $this->get_custom_ext_path() . $obj->folder_name;
			$dir2 = DIVI_EXT_PATH . $obj->folder_name;
			$checked1 = DIVI_EXT::is_ext_activated($dir1);
			$checked2 = DIVI_EXT::is_ext_activated($dir2);
			if ($checked1 OR $checked2) {
			    do_action('divi_print_applications_tabs_content_' . $obj->folder_name);
			}
		    }
		}
		?>
                <section id="tabs-6">

                    <div class="divi-tabs divi-tabs-style-line">

                        <nav>
                            <ul>
                                <li>
                                    <a href="#tabs-61">
                                        <span><?php _e("Extensions", 'products-filter-custom') ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#tabs-62">
                                        <span><?php _e("Ext-Applications options", 'products-filter-custom') ?></span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <div class="content-wrap">


                            <section id="tabs-61">

                                <div class="select-wrap">
                                    <select id="divi_manipulate_with_ext">
                                        <option value="0"><?php _e('All', 'products-filter-custom') ?></option>
                                        <option value="1"><?php _e('Enabled', 'products-filter-custom') ?></option>
                                        <option value="2"><?php _e('Disabled', 'products-filter-custom') ?></option>
                                    </select>
                                </div>

                                <input type="hidden" name="divi_settings[activated_extensions]" value="" />


				<?php if (true): ?>


    				<!-- ----------------------------------------- -->
				    <?php if (isset($this->settings['custom_extensions_path']) AND ! empty($this->settings['custom_extensions_path'])): ?>

					<br />
					<hr />
					<h3><?php _e('Custom extensions installation', 'products-filter-custom') ?></h3>

					<?php
					$is_custom_extensions = false;
					if (is_dir($this->get_custom_ext_path())) {
					    //$dir_writable = substr(sprintf('%o', fileperms($this->get_custom_ext_path())), -4) == "0774" ? true : false;
					    $dir_writable = is_writable($this->get_custom_ext_path());
					    if ($dir_writable) {
						$is_custom_extensions = true;
					    }
					} else {
					    if (!empty($this->settings['custom_extensions_path'])) {
						//ext dir auto creation
						$dir = $this->get_custom_ext_path();
						try {
						    mkdir($dir, 0777);
						    $dir_writable = is_writable($this->get_custom_ext_path());
						    if ($dir_writable) {
							$is_custom_extensions = true;
						    }
						} catch (Exception $e) {
						    //***
						}
					    }
					}
					//***
					if ($is_custom_extensions):
					    ?>
	    				<input type="button" id="upload-btn" class="button" value="<?php _e('Choose an extension zip', 'products-filter-custom') ?>">
	    				<span style="padding-left:5px;vertical-align:middle;"><i><?php _e('(zip)', 'products-filter-custom') ?></i></span>

	    				<div id="errormsg" class="clearfix redtext" style="padding-top: 10px;"></div>

	    				<div id="pic-progress-wrap" class="progress-wrap" style="margin-top:10px;margin-bottom:10px;"></div>

	    				<div id="picbox" class="clear" style="padding-top:0px;padding-bottom:10px;"></div>

	    				<script>
	    				    jQuery(function ($) {
	    					divi_init_ext_uploader("<?php echo ABSPATH ?>", "<?php echo $this->get_custom_ext_path() ?>", "<?php echo DIVI_LINK ?>lib/simple-ajax-uploader/action.php");
	    				    });
	    				</script>

					<?php else: ?>
	    				<span style="color:orangered;"><?php printf(__('Note for admin: Folder %s for extensions is not writable OR doesn exists! Ignore this message if you not planning using DIVI custom extensions!', 'products-filter-custom'), $this->get_custom_ext_path()) ?></span>
					<?php endif; ?>
				    <?php else: ?>
					<?php if (!empty($this->settings['custom_extensions_path'])): ?>
	    				<span style="color:orangered;"><?php _e('<b>Note for admin</b>: Create folder for custom extensions in wp-content folder: tab Advanced -> Options -> Custom extensions folder', 'products-filter-custom') ?></span>
					<?php endif; ?>
				    <?php endif; ?>
    				<!-- ----------------------------------------- -->




				    <?php
				    if (!isset($divi_settings['activated_extensions']) OR ! is_array($divi_settings['activated_extensions'])) {
					$divi_settings['activated_extensions'] = array();
				    }
				    ?>
				    <?php if (!empty($extensions) AND is_array($extensions)): ?>


					<ul class="divi_extensions divi_custom_extensions">
					    <?php foreach ($extensions['custom'] as $dir): ?>
						<?php
						//$idx = md5($dir);
						//$checked = in_array($idx, $divi_settings['activated_extensions']);
						$checked = DIVI_EXT::is_ext_activated($dir);
						$idx = DIVI_EXT::get_ext_idx($dir);
						?>
	    				    <li class="divi_ext_li <?php echo($checked ? 'is_enabled' : 'is_disabled'); ?>">
						    <?php
						    $info = array();
						    if (file_exists($dir . DIRECTORY_SEPARATOR . 'info.dat')) {
							$info = DIVI_HELPER::parse_ext_data($dir . DIRECTORY_SEPARATOR . 'info.dat');
						    }
						    ?>
	    					<table style="width: 100%;">
	    					    <tr>
	    						<td style="vertical-align: top;">
	    						    <img style="width: 85px;" src="<?php echo DIVI_LINK ?>img/divi_ext_cover.png" alt="ext cover" /><br />
	    						    <br />
	    						    <span class="divi_ext_ver"><?php
								    if (isset($info['version'])) {
									printf(__('<i>ver.:</i> %s', 'products-filter-custom'), $info['version']);
								    }
								    ?></span>
	    						</td>
	    						<td><div style="width:5px;"></div></td>
	    						<td style="width: 100%; vertical-align: top; position: relative;">
	    						    <a href="#" class="divi_ext_remove" data-title="" data-idx="<?php echo $idx ?>" title="<?php _e('remove extension', 'products-filter-custom') ?>"><img src="<?php echo DIVI_LINK ?>img/delete2.png" alt="<?php _e('remove extension', 'products-filter-custom') ?>" /></a>
								<?php
								if (!empty($info)) {
								    if (!empty($info) AND is_array($info)) {
									?>
		    						    <label for="<?php echo $idx ?>">
		    							<input type="checkbox" id="<?php echo $idx ?>" <?php if (isset($info['status']) AND $info['status'] == 'premium'): ?>disabled="disabled"<?php endif; ?> <?php if ($checked): ?>checked=""<?php endif; ?> value="<?php echo $idx ?>" name="divi_settings[activated_extensions][]" />
									    <?php
									    if (isset($info['link'])) {
										?>
										<a href="<?php echo $info['link'] ?>" class="divi_ext_title" target="_blank"><?php echo $info['title'] ?></a>
										<?php
									    } else {
										echo $info['title'];
									    }
									    ?>
		    						    </label><br />
									<?php
									if (isset($info['description'])) {
									    echo '<br />';
									    echo '<p class="description">' . $info['description'] . '</p>';
									}
								    } else {
									echo $dir;
									echo '<br />';
									_e('You should write extension info in info.dat file!', 'products-filter-custom');
								    }
								} else {
								    printf(__('Looks like its not the DIVI extension here %s!', 'products-filter-custom'), $dir);
								}
								?>
	    						</td>
	    					    </tr>
	    					</table>
	    				    </li>
					    <?php endforeach; ?>
					<?php endif; ?>
    				</ul>
    				<div style="clear: both;"></div>
    				<br />
    				<hr />

				    <?php if (!empty($extensions['default'])): ?>

					<h3><?php _e('Default extensions', 'products-filter-custom') ?></h3>

					<ul class="divi_extensions">
					    <?php foreach ($extensions['default'] as $dir): ?>
						<?php
						//$idx = md5($dir);
						//$checked = in_array($idx, $divi_settings['activated_extensions']);
						$checked = DIVI_EXT::is_ext_activated($dir);
						$idx = DIVI_EXT::get_ext_idx($dir);
						?>
	    				    <li class="divi_ext_li <?php echo($checked ? 'is_enabled' : 'is_disabled'); ?>">
						    <?php
						    $info = array();
						    if (file_exists($dir . DIRECTORY_SEPARATOR . 'info.dat')) {
							$info = DIVI_HELPER::parse_ext_data($dir . DIRECTORY_SEPARATOR . 'info.dat');
						    }
						    ?>
	    					<table style="width: 100%;">
	    					    <tr>
	    						<td style="vertical-align: top;">
	    						    <img style="width: 85px;" src="<?php echo DIVI_LINK ?>img/divi_ext_cover.png" alt="ext cover" /><br />
	    						    <br />
	    						    <span class="divi_ext_ver"><?php
								    if (isset($info['version'])) {
									printf(__('<i>ver.:</i> %s', 'products-filter-custom'), $info['version']);
								    }
								    ?></span>
	    						</td>
	    						<td><div style="width:5px;"></div></td>
	    						<td style="width: 100%;">
								<?php
								if (!empty($info)) {
								    $info = DIVI_HELPER::parse_ext_data($dir . DIRECTORY_SEPARATOR . 'info.dat');
								    if (!empty($info) AND is_array($info)) {
									?>
		    						    <label for="<?php echo $idx ?>">
		    							<input type="checkbox" id="<?php echo $idx ?>" <?php if (isset($info['status']) AND $info['status'] == 'premium'): ?>disabled="disabled"<?php endif; ?> <?php if ($checked): ?>checked=""<?php endif; ?> value="<?php echo $idx ?>" name="divi_settings[activated_extensions][]" />
									    <?php
									    if (isset($info['link'])) {
										?>
										<a href="<?php echo $info['link'] ?>" class="divi_ext_title" target="_blank"><?php echo $info['title'] ?></a>
										<?php
									    } else {
										echo $info['title'];
									    }
									    ?>
		    						    </label><br />
									<?php
									echo '<br />';
									echo '<p class="description">' . $info['description'] . '</p>';
								    } else {
									echo $dir;
									echo '<br />';
									_e('You should write extension info in info.dat file!', 'products-filter-custom');
								    }
								} else {
								    echo $dir;
								}
								?>
	    						</td>
	    					    </tr>
	    					</table>

	    				    </li>
					    <?php endforeach; ?>
					</ul>
				    <?php endif; ?>

				<?php endif; ?>
                                <div class="clear"></div>


                            </section>


                            <section id="tabs-62">

                                <div class="divi-tabs divi-tabs-style-line">

                                    <nav class="divi_ext_nav">
                                        <ul>
					    <?php
					    $is_custom_extensions = false;
					    if (is_dir($this->get_custom_ext_path())) {
						//$dir_writable = substr(sprintf('%o', fileperms($this->get_custom_ext_path())), -4) == "0774" ? true : false;
						$dir_writable = is_writable($this->get_custom_ext_path());
						if ($dir_writable) {
						    $is_custom_extensions = true;
						}
					    }

					    if ($is_custom_extensions) {
						if (!empty(DIVI_EXT::$includes['applications'])) {
						    foreach (DIVI_EXT::$includes['applications'] as $obj) {

							$dir = $this->get_custom_ext_path() . $obj->folder_name;
							//$idx = md5($dir);
							//$checked = in_array($idx, $divi_settings['activated_extensions']);
							$checked = DIVI_EXT::is_ext_activated($dir);
							if (!$checked) {
							    continue;
							}
							?>
	    					    <li>

							    <?php
							    if (file_exists($dir . DIRECTORY_SEPARATOR . 'info.dat')) {
								$info = DIVI_HELPER::parse_ext_data($dir . DIRECTORY_SEPARATOR . 'info.dat');
								if (!empty($info) AND is_array($info)) {
								    $name = $info['title'];
								} else {
								    $name = $obj->folder_name;
								}
							    } else {
								$name = $obj->folder_name;
							    }
							    ?>
	    						<a href="#tabs-<?php echo sanitize_title($obj->folder_name) ?>" title="<?php printf(__("%s", 'products-filter-custom'), $name) ?>">
	    						    <span style="font-size: 11px;"><?php printf(__("%s", 'products-filter-custom'), $name) ?></span>
	    						</a>
	    					    </li>
							<?php
						    }
						}
					    }
					    ?>


                                        </ul>
                                    </nav>


                                    <div class="content-wrap divi_ext_opt">

					<?php
					if ($is_custom_extensions) {
					    if (!empty(DIVI_EXT::$includes['applications'])) {
						foreach (DIVI_EXT::$includes['applications'] as $obj) {

						    $dir = $this->get_custom_ext_path() . $obj->folder_name;
						    //$idx = md5($dir);
						    //$checked = in_array($idx, $divi_settings['activated_extensions']);
						    $checked = DIVI_EXT::is_ext_activated($dir);
						    if (!$checked) {
							continue;
						    }
						    do_action('divi_print_applications_options_' . $obj->folder_name);
						}
					    }
					}
					?>

                                    </div>


                                    <div class="clear"></div>

                                </div>




                            </section>

                        </div>

                    </div>

                </section>



                <section id="tabs-7">

                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row"><label><?php _e("Docs", 'products-filter-custom') ?></label></th>
                                <td>

                                    <ul>

                                        <li>
                                            <a class="button" href="http://woocommerce-filter.com/documentation/" target="_blank">DIVI documentation</a>
                                            <a class="button" href="http://www.woocommerce-filter.com/category/faq/" target="_blank">FAQ</a>
                                            <a class="button" href="http://www.woocommerce-filter.com/video-tutorials/" target="_blank" style="border: solid 1px greenyellow;">Video tutorials</a>
                                        </li>

                                    </ul>

                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><label><?php _e("Demo site", 'products-filter-custom') ?></label></th>
                                <td>

                                    <ul>

                                        <li>
                                            <a href="http://www.demo.woocommerce-filter.com/" target="_blank">DIVI - WooCommerce Products Filter</a>
                                        </li>
                                        <li>
                                            <a href="http://www.woocommerce-filter.com/styles-codes-applied-on-demo-site/" target="_blank"><?php _e("Styles and codes which are applied on the demo site", 'products-filter-custom') ?></a>
                                        </li>

                                    </ul>

                                </td>
                            </tr>                            


                            <tr valign="top">
                                <th scope="row"><label><?php _e("Quick video tutorial", 'products-filter-custom') ?></label></th>
                                <td>

                                    <ul>

                                        <li>
                                            <iframe width="560" height="315" src="https://www.youtube.com/embed/jZPtdWgAxKk" frameborder="0" allowfullscreen></iframe>
                                        </li>

                                    </ul>

                                </td>
                            </tr>


                            <tr valign="top">
                                <th scope="row"><label><?php _e("More video", 'products-filter-custom') ?></label></th>
                                <td>

                                    <ul>

                                        <li>
                                            <a href="http://www.woocommerce-filter.com/video-tutorials/" target="_blank"><?php _e("Video tutorials", 'products-filter-custom') ?></a>
                                        </li>

                                    </ul>

                                </td>
                            </tr>




                            <tr valign="top">
                                <th scope="row"><label><?php _e("Recommended plugins for your site flexibility and features", 'products-filter-custom') ?></label></th>
                                <td>

                                    <ul class="list_plugins">


                                        <li>
                                            <a href="https://wordpress.org/plugins/woocommerce-currency-switcher/" target="_blank"><img src="<?php echo DIVI_LINK ?>img/woocs_banner.jpg" /></a>
                                            <p class="description"><?php _e("WooCommerce Currency Switcher – is the plugin that allows you to switch to different currencies and get their rates converted in the real time!", 'products-filter-custom') ?></p>
                                        </li>

                                        <li>
                                            <a href="https://wordpress.org/plugins/inpost-gallery/" target="_blank">InPost Gallery - flexible photo gallery</a>
                                            <p class="description"><?php _e("Insert Gallery in post, page and custom post types just in two clicks. You can create great galleries for your products.", 'products-filter-custom') ?></p>
                                            <p class="description"><a href="http://www.demo.woocommerce-filter.com/shop/music/woo-single-2/" target="_blank" class="button"><?php _e("Example", 'products-filter-custom') ?></a></p>
                                        </li>


                                        <li>
                                            <a href="https://wordpress.org/plugins/autoptimize/" target="_blank">Autoptimize</a>
                                            <p class="description"><?php _e("It concatenates all scripts and styles, minifies and compresses them, adds expires headers, caches them, and moves styles to the page head, and scripts to the footer", 'products-filter-custom') ?></p>
                                        </li>


                                        <li>
                                            <a href="https://wordpress.org/plugins/pretty-link/" target="_blank">Pretty Link Lite</a>
                                            <p class="description"><?php _e("Shrink, beautify, track, manage and share any URL on or off of your WordPress website. Create links that look how you want using your own domain name!", 'products-filter-custom') ?></p>
                                        </li>

                                        <li>
                                            <a href="https://wordpress.org/plugins/custom-post-type-ui/" target="_blank">Custom Post Type UI</a>
                                            <p class="description"><?php _e("This plugin provides an easy to use interface to create and administer custom post types and taxonomies in WordPress.", 'products-filter-custom') ?></p>
                                        </li>

                                        <li>
                                            <a href="https://wordpress.org/plugins/widget-logic/other_notes/" target="_blank">Widget Logic</a>
                                            <p class="description"><?php _e("Widget Logic lets you control on which pages widgets appear using", 'products-filter-custom') ?></p>
                                        </li>

                                        <li>
                                            <a href="https://wordpress.org/plugins/wp-super-cache/" target="_blank">WP Super Cache</a>
                                            <p class="description"><?php _e("Cache pages, allow to make a lot of search queries on your site without high load on your server!", 'products-filter-custom') ?></p>
                                        </li>


                                        <li>
                                            <a href="https://wordpress.org/plugins/wp-migrate-db/" target="_blank">WP Migrate DB</a>
                                            <p class="description"><?php _e("Exports your database, does a find and replace on URLs and file paths, then allows you to save it to your computer.", 'products-filter-custom') ?></p>
                                        </li>

                                        <li>
                                            <a href="https://wordpress.org/plugins/duplicator/" target="_blank">Duplicator</a>
                                            <p class="description"><?php _e("Duplicate, clone, backup, move and transfer an entire site from one location to another.", 'products-filter-custom') ?></p>
                                        </li>

                                    </ul>

                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><label><?php _e("Adv", 'products-filter-custom') ?></label></th>
                                <td>

                                    <ul>

                                        <li>
                                            <a href="https://share.payoneer.com/nav/6I2wmtpBuitGE6ZnmaMXLYlP8iriJ-63OMLi3PT8SRGceUjGY1dvEhDyuAGBp91DEmf8ugfF3hkUU1XhP_C6Jg2" target="_blank"><img src="<?php echo DIVI_LINK ?>img/plugin_options/100125.png" alt="" /></a>
                                        </li>

                                    </ul>

                                </td>
                            </tr>

                        </tbody>
                    </table>

                </section>

            </div>

        </div>

        <style type="text/css">
            .form-table th {  width: 300px; }
        </style>

    </section><!--/ .divi-section-->

    <div id="divi-modal-content" style="display: none;">

        <div class="divi_option_container divi_option_all">

            <div class="divi-form-element-container">

                <div class="divi-name-description">
                    <strong><?php _e('Show title label', 'products-filter-custom') ?></strong>
                    <span><?php _e('Show/Hide taxonomy block title on the front', 'products-filter-custom') ?></span>
                </div>

                <div class="divi-form-element">

                    <div class="select-wrap">
                        <select class="divi_popup_option" data-option="show_title_label">
                            <option value="0"><?php _e('No', 'products-filter-custom') ?></option>
                            <option value="1"><?php _e('Yes', 'products-filter-custom') ?></option>
                        </select>
                    </div>

                </div>

            </div>

            <div class="divi-form-element-container">

                <div class="divi-name-description">
                    <strong><?php _e('Show toggle button', 'products-filter-custom') ?></strong>
                    <span><?php _e('Show toggle button near the title on the front above the block of html-items', 'products-filter-custom') ?></span>
                </div>

                <div class="divi-form-element">

                    <div class="select-wrap">
                        <select class="divi_popup_option" data-option="show_toggle_button">
                            <option value="0"><?php _e('No', 'products-filter-custom') ?></option>
                            <option value="1"><?php _e('Yes, show as closed', 'products-filter-custom') ?></option>
                            <option value="2"><?php _e('Yes, show as opened', 'products-filter-custom') ?></option>
                        </select>
                    </div>

                </div>

            </div>

        </div>


        <div class="divi_option_container divi_option_all">

            <div class="divi-form-element-container">

                <div class="divi-name-description">
                    <strong><?php _e('Not toggled terms count', 'products-filter-custom') ?></strong>
                    <span><?php _e('Enter count of terms which should be visible to make all other collapsible. "Show more" button will be appeared. This feature is works with: radio, checkboxes, labels, colors.', 'products-filter-custom') ?></span>
                    <span><?php printf(__('Advanced info is <a href="%s" target="_blank">here</a>', 'products-filter-custom'), 'http://www.woocommerce-filter.com/hook/divi_get_more_less_button_xxxx/') ?></span>
                </div>

                <div class="divi-form-element">
                    <input type="text" class="divi_popup_option regular-text code" data-option="not_toggled_terms_count" placeholder="<?php _e('leave it empty to show all terms', 'products-filter-custom') ?>" value="0" />
                </div>

            </div>

        </div>
        <!--  
        <div class="divi_option_container divi_option_all">

            <div class="divi-form-element-container">

                <div class="divi-name-description">
                    <strong><?php //_e('Taxonomy custom label', 'products-filter-custom') ?></strong>
                    <span><?php //_e('For example you want to show title of Product Categories as "My Products". Just for your convenience.', 'products-filter-custom') ?></span>
                </div>

                <div class="divi-form-element">
                    <input type="text" class="divi_popup_option regular-text code" data-option="custom_tax_label" placeholder="<?php //_e('Taxonomy Title Name', 'products-filter-custom') ?>" value="0" />
                </div>

            </div>

        </div>
        -->
        <div class="divi_option_container divi_option_radio divi_option_checkbox divi_option_label">

            <div class="divi-form-element-container">

                <div class="divi-name-description">
                    <strong><?php _e('Max height of the block', 'products-filter-custom') ?></strong>
                    <span><?php _e('Container max-height (px). 0 means no max-height.', 'products-filter-custom') ?></span>
                </div>

                <div class="divi-form-element">
                    <input type="text" class="divi_popup_option regular-text code" data-option="tax_block_height" placeholder="<?php _e('Max height of  the block', 'products-filter-custom') ?>" value="0" />
                </div>

            </div>

        </div>

        <div class="divi_option_container divi_option_radio divi_option_checkbox">

            <div class="divi-form-element-container">

                <div class="divi-name-description">
                    <strong><?php _e('Display items in a row', 'products-filter-custom') ?></strong>
                    <span><?php _e('Works for radio and checkboxes only. Allows show radio/checkboxes in 1 row!', 'products-filter-custom') ?></span>
                </div>

                <div class="divi-form-element">

                    <div class="select-wrap">
                        <select class="divi_popup_option" data-option="dispay_in_row">
                            <option value="0"><?php _e('No', 'products-filter-custom') ?></option>
                            <option value="1"><?php _e('Yes', 'products-filter-custom') ?></option>
                        </select>
                    </div>

                </div>

            </div>

        </div>

        <!------------- options for extensions ------------------------>

	<?php
	if (!empty(DIVI_EXT::$includes['taxonomy_type_objects'])) {
	    foreach (DIVI_EXT::$includes['taxonomy_type_objects'] as $obj) {
		if (!empty($obj->taxonomy_type_additional_options)) {
		    foreach ($obj->taxonomy_type_additional_options as $key => $option) {
			switch ($option['type']) {
			    case 'select':
				?>
				<div class="divi_option_container divi_option_<?php echo $obj->html_type ?>">

				    <div class="divi-form-element-container">

					<div class="divi-name-description">
					    <strong><?php echo $option['title'] ?></strong>
					    <span><?php echo $option['tip'] ?></span>
					</div>

					<div class="divi-form-element">

					    <div class="select-wrap">
						<select class="divi_popup_option" data-option="<?php echo $key ?>">
						    <?php foreach ($option['options'] as $val => $title): ?>
			    			    <option value="<?php echo $val ?>"><?php echo $title ?></option>
						    <?php endforeach; ?>
						</select>
					    </div>

					</div>

				    </div>

				</div>
				<?php
				break;

			    case 'text':
				?>
				<div class="divi_option_container divi_option_<?php echo $obj->html_type ?>">

				    <div class="divi-form-element-container">

					<div class="divi-name-description">
					    <strong><?php echo $option['title'] ?></strong>
					    <span><?php echo $option['tip'] ?></span>
					</div>

					<div class="divi-form-element">
					    <input type="text" class="divi_popup_option regular-text code" data-option="<?php echo $key ?>" placeholder="<?php echo $option['placeholder'] ?>" value="" />px
					</div>

				    </div>

				</div>
				<?php
				break;

			    case 'image':
				?>
				<div class="divi_option_container divi_option_<?php echo $obj->html_type ?>">

				    <div class="divi-form-element-container">

					<div class="divi-name-description">
					    <strong><?php echo $option['title'] ?></strong>
					    <span><?php echo $option['tip'] ?></span>
					</div>

					<div class="divi-form-element">
					    <input type="text" class="divi_popup_option regular-text code" data-option="<?php echo $key ?>" placeholder="<?php echo $option['placeholder'] ?>" value="" />
					    <a href="#" class="button divi_select_image"><?php _e('select image', 'products-filter-custom') ?></a>
					</div>

				    </div>

				</div>
				<?php
				break;

			    default:
				break;
			}
		    }
		}
	    }
	}
	?>

    </div>

    <div id="divi_ext_tpl" style="display: none;">
        <li class="divi_ext_li is_disabled">

            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="vertical-align: top;">
                            <img alt="ext cover" src="<?php echo DIVI_LINK ?>img/divi_ext_cover.png" style="width: 85px;">
                        </td>
                        <td><div style="width:5px;"></div></td>
                        <td style="width: 100%; vertical-align: top; position: relative;">
                            <a href="#" class="divi_ext_remove" data-title="__TITLE__" data-idx="__IDX__" title="<?php _e('remove extension', 'products-filter-custom') ?>"><img src="<?php echo DIVI_LINK ?>img/delete2.png" alt="<?php _e('remove extension', 'products-filter-custom') ?>" /></a>
                            <label for="__IDX__">
                                <input type="checkbox" name="__NAME__" value="__IDX__" id="__IDX__">
                                __TITLE__
                            </label><br>
                            <i>ver.:</i> __VERSION__<br><p class="description">__DESCRIPTION__</p>
                        </td>
                    </tr>
                </tbody>
            </table>

        </li>
    </div>

    <div id="divi-modal-content-by_price" style="display: none;">

        <div class="divi-form-element-container">

            <div class="divi-name-description">
                <strong><?php _e('Show button', 'products-filter-custom') ?></strong>
                <span><?php _e('Show button for woocommerce filter by price inside divi search form when it is dispayed as woo range-slider', 'products-filter-custom') ?></span>
            </div>

            <div class="divi-form-element">

		<?php
		$show_button = array(
		    0 => __('No', 'products-filter-custom'),
		    1 => __('Yes', 'products-filter-custom')
		);
		?>

                <div class="select-wrap">
                    <select class="divi_popup_option" data-option="show_button">
			<?php foreach ($show_button as $key => $value) : ?>
    			<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
			<?php endforeach; ?>
                    </select>
                </div>

            </div>

        </div>
        <!--
        <div class="divi-form-element-container">

            <div class="divi-name-description">
                <strong><?php //_e('Title text', 'products-filter-custom') ?></strong>
                <span><?php //_e('Text before the price filter range slider. Leave it empty if you not need it!', 'products-filter-custom') ?></span>
            </div>

            <div class="divi-form-element">
                <input type="text" class="divi_popup_option" data-option="title_text" placeholder="" value="" />
            </div>

        </div>
        -->
        <div class="divi-form-element-container">

            <div class="divi-name-description">
                <h3><?php _e('Drop-down OR radio', 'products-filter-custom') ?></h3>
                <strong><?php _e('Drop-down OR radio price filter ranges', 'products-filter-custom') ?></strong>
                <span><?php _e('Ranges for price filter.', 'products-filter-custom') ?></span>
                <span><?php printf(__('Example: 0-50,51-100,101-i. Where "i" is infinity. Max price is %s.', 'products-filter-custom'), DIVI_HELPER::get_max_price()) ?></span>
            </div>

            <div class="divi-form-element">
                <input type="text" class="divi_popup_option" data-option="ranges" placeholder="" value="" />
            </div>

        </div>

        <div class="divi-form-element-container">

            <div class="divi-name-description">
                <strong><?php _e('Drop-down price filter text', 'products-filter-custom') ?></strong>
                <span><?php _e('Drop-down price filter first option text', 'products-filter-custom') ?></span>
            </div>

            <div class="divi-form-element">
                <input type="text" class="divi_popup_option" data-option="first_option_text" placeholder="" value="" />
            </div>

        </div>

        <div class="divi-form-element-container">

            <div class="divi-name-description">
                <h3><?php _e('Ion Range slider', 'products-filter-custom') ?></h3>
                <strong><?php _e('Step', 'products-filter-custom') ?></strong>
                <span><?php _e('predifined step', 'products-filter-custom') ?></span>
            </div>

            <div class="divi-form-element">
                <input type="text" class="divi_popup_option" data-option="ion_slider_step" placeholder="" value="" />
            </div>

        </div>



    </div>



    <div id="divi_buffer" style="display: none;"></div>

    <div id="divi_html_buffer" class="divi_info_popup" style="display: none;"></div>

    <?php if ($this->is_free_ver): ?>
        <script>
    	jQuery(function () {
    	    //for premium only
    	    jQuery('#divi_filter_btn_txt').prop('disabled', true);
    	    jQuery('#divi_filter_btn_txt').val('In the premium version');
    	    jQuery('#divi_reset_btn_txt').prop('disabled', true);
    	    jQuery('#divi_reset_btn_txt').val('In the premium version');
    	    jQuery('#divi_hide_dynamic_empty_pos').prop('disabled', true);
    	    jQuery('select[name="divi_settings[hide_terms_count_txt]"]').prop('disabled', true);
    	    //***
    	    jQuery('#sdivi_search_slug').prop('disabled', true);
    	    jQuery('#sdivi_search_slug').val('In the premium version');
    	    jQuery('#sdivi_search_slug').parents('.divi-control-section').addClass('divi_premium_only');
    	    jQuery('#hide_terms_count_txt').prop('disabled', true);
    	    jQuery('#hide_terms_count_txt').parents('.divi-control-section').addClass('divi_premium_only');
    	});
        </script>

        <style type="text/css">
    	.divi_premium_only{
    	    color:red !important;
    	}

    	label[for=divi_filter_btn_txt],
    	label[for=divi_reset_btn_txt],label[for=sdivi_search_slug],
    	label[for=divi_hide_dynamic_empty_pos],label[for=hide_terms_count_txt]
    	{
    	    color:red;
    	}
        </style>
    <?php endif; ?>

</div>

<?php
function divi_print_tax($key, $tax, $divi_settings) {
    global $DIVI;
    ?>
    <li data-key="<?php echo $key ?>" class="divi_options_li <?php echo $key ?>">

        <a href="#" style="display:none;" class="help_tip divi_drag_and_drope" data-tip="<?php _e("drag and drope", 'products-filter-custom'); ?>"><img src="<?php echo DIVI_LINK ?>img/move.png" alt="<?php _e("move", 'products-filter-custom'); ?>" /></a>

        <div class="select-wrap">
    	<select name="divi_settings[tax_type][<?php echo $key ?>]" class="divi_select_tax_type">
		<?php foreach ($DIVI->html_types as $type => $type_text) : ?>
		    <option value="<?php echo $type ?>" <?php if (isset($divi_settings['tax_type'][$key])) echo selected($divi_settings['tax_type'][$key], $type) ?>><?php echo $type_text ?></option>
		<?php endforeach; ?>
    	</select>
		
		
		
		<?php 		
	    if(((isset($DIVI->settings['tax']) ? is_array($DIVI->settings['tax']) : FALSE) ? in_array($key, (array) array_keys($DIVI->settings['tax'])) : false)){	   
	    //print_r($DIVI->settings['tax']);
	    //echo 'fff';
	    $cat_name = $tax->name;
	    }
		foreach ($DIVI->html_types as $type => $type_text){ 
		$chekbox_radio_type_check = 'type_'.$divi_settings['tax_type'][$key].'_'.$cat_name; 
		//print_r($type);
		
		//print_r($DIVI);
		
		//$chekbox_radio_type_check = str_replace(' ', '_', $divi_settings['tax_type'][$key].' '.$divi_settings['tax_type'][$key]);
		if($chekbox_radio_type_check == 'type_radio_'.$cat_name){
		?>	
		 <style>
		 .type_radio_<?php echo $cat_name; ?>{
			  display:block !important;
		 }
		 </style>	
		<?php } elseif($chekbox_radio_type_check == 'type_checkbox_'.$cat_name) { ?>
		<style>
		.type_checkbox_<?php echo $cat_name; ?>{
			  display:block !important;
		  }
        </style>
		<?php } } ?>
        </div>
        <!--------Move-part---->
		<?php
		$excluded_terms = '';
		if (isset($divi_settings['excluded_terms'][$key])) {
			$excluded_terms = $divi_settings['excluded_terms'][$key];
		}
		?>

        <input type="text" style="width: 420px;display:none;" name="divi_settings[excluded_terms][<?php echo $key ?>]" placeholder="<?php _e('excluded terms ids', 'products-filter-custom') ?>" value="<?php echo $excluded_terms ?>"/>
        <img style="display:none;"class="help_tip" data-tip="<?php _e('If you want to exclude some current taxonomies terms from the seacrh form! Example: 11,23,77', 'products-filter-custom') ?>" src="<?php echo WP_PLUGIN_URL ?>/woocommerce/assets/images/help.png" height="16" width="16" />

        <input type="button" value="<?php _e('additional options', 'products-filter-custom') ?>" data-taxonomy="<?php echo $key ?>" data-taxonomy-name="<?php echo $tax->labels->name ?>" class="divi-button js_divi_add_options" style="display:none;"/>
		
		<?php
	    $custom_tax_label = '';
	    if (isset($divi_settings['custom_tax_label'][$key])) {
		$custom_tax_label = $divi_settings['custom_tax_label'][$key];
	    }
	    ?>
    	<input type="text" class="remove_icon_dropdown" name="divi_settings[custom_tax_label][<?php echo $key ?>]" placeholder="(e.g. <?php echo $tax->labels->name ?>)" value="<?php echo $custom_tax_label ?>" />
<?php
##################################################################################################################################################################################
##																																												##
##            											       Adiitional option Value																							##
##																																												##
##################################################################################################################################################################################
?>	 
        <div style="display: none;">
	    <?php
	    $max_height = 0;
	    if (isset($divi_settings['tax_block_height'][$key])) {
		$max_height = $divi_settings['tax_block_height'][$key];
	    }
	    ?>
    	<input type="text" name="divi_settings[tax_block_height][<?php echo $key ?>]" placeholder="" value="<?php echo $max_height ?>" />
	    <?php
		/*
	    $show_title_label = 0;
	    if (isset($divi_settings['show_title_label'][$key])) {
		$show_title_label = $divi_settings['show_title_label'][$key];
	    }
		*/
		$show_title_label = 1;
	    ?>
    	<input type="text" name="divi_settings[show_title_label][<?php echo $key ?>]" placeholder="" value="<?php echo $show_title_label ?>" />


	    <?php
		/*
	    $show_toggle_button = 0;
	    if (isset($divi_settings['show_toggle_button'][$key])) {
		$show_toggle_button = $divi_settings['show_toggle_button'][$key];
	    }
		*/
		$show_toggle_button = 1;
	    ?>
    	<input type="text" name="divi_settings[show_toggle_button][<?php echo $key ?>]" placeholder="" value="<?php echo $show_toggle_button ?>" />

	    <?php
	    $dispay_in_row = 0;
	    if (isset($divi_settings['dispay_in_row'][$key])) {
		$dispay_in_row = $divi_settings['dispay_in_row'][$key];
	    }
	    ?>
    	<input type="text" name="divi_settings[dispay_in_row][<?php echo $key ?>]" placeholder="" value="<?php echo $dispay_in_row ?>" />


	    <?php
		/*
	    $custom_tax_label = '';
	    if (isset($divi_settings['custom_tax_label'][$key])) {
		$custom_tax_label = $divi_settings['custom_tax_label'][$key];
	    } */
	    ?>
		<!--
    	<input type="text" name="divi_settings[custom_tax_label][<?php //echo $key ?>]" placeholder="" value="<?php //echo $custom_tax_label ?>" />
        -->

	    <?php
	    $not_toggled_terms_count = '';
	    if (isset($divi_settings['not_toggled_terms_count'][$key])) {
		$not_toggled_terms_count = $divi_settings['not_toggled_terms_count'][$key];
	    }
	    ?>
    	<input type="text" name="divi_settings[not_toggled_terms_count][<?php echo $key ?>]" placeholder="" value="<?php echo $not_toggled_terms_count ?>" />


    	<!------------- options for extensions ------------------------>
	    <?php
	    if (!empty(DIVI_EXT::$includes['taxonomy_type_objects'])) {
		foreach (DIVI_EXT::$includes['taxonomy_type_objects'] as $obj) {
		    if (!empty($obj->taxonomy_type_additional_options)) {
			foreach ($obj->taxonomy_type_additional_options as $option_key => $option) {
			    $option_val = 0;
			    if (isset($divi_settings[$option_key][$key])) {
				$option_val = $divi_settings[$option_key][$key];
			    }
			    ?>
		    	<input type="text" name="divi_settings[<?php echo $option_key ?>][<?php echo $key ?>]" value="<?php echo $option_val ?>" />
			    <?php
			}
		    }
		}
	    }
	    ?>
        </div>
<?php
##################################################################################################################################################################################
##            												End Adiitional option Value																							##
##################################################################################################################################################################################
?>   
        <div class="filter_types"> 
        <input style="display:block;" <?php echo(((isset($DIVI->settings['tax']) ? is_array($DIVI->settings['tax']) : FALSE) ? in_array($key, (array) array_keys($DIVI->settings['tax'])) : false) ? 'checked="checked"' : '') ?> type="checkbox" name="divi_settings[tax][<?php echo $key ?>]" id="tax_<?php echo md5($key) ?>" value="1" />
        <label style="display:block;" for="tax_<?php echo md5($key) ?>" style="font-weight:bold;"><?php echo $tax->labels->name ?></label>
		</div>
		
		<div class="three_button" style="width: 200px;float: right;font-size: 25px;"><i class="fa fa-eye" aria-hidden="true"></i>  <i class="fa fa-clone" aria-hidden="true"></i>  <i class="fa fa-trash" aria-hidden="true"></i> </div>
		
		<?php 
		
	  if(((isset($DIVI->settings['tax']) ? is_array($DIVI->settings['tax']) : FALSE) ? in_array($key, (array) array_keys($DIVI->settings['tax'])) : false)){	   
	   //print_r($DIVI->settings['tax']);
	   //echo 'fff';
	   //echo $tax->name;
	   ?>
       <style>
	   .hide_show_<?php echo $tax->name; ?>{
		   display:block;
	   }
       </style>	   
	   <?php
	   }
	   else{
	   ?>
       <style>
	   .hide_show_<?php echo $tax->name; ?>{
		   display:none;
	   }
       </style>	   
	   <?php
	   }	
		?>		
	<?php
	if (isset($divi_settings['tax_type'][$key])) {
	    do_action('divi_print_tax_additional_options_' . $divi_settings['tax_type'][$key], $key);
	}
	?>
    </li>
    <?php
}

//***

function divi_print_item_by_key($key, $divi_settings) {
    switch ($key) {
	case 'by_price':
	    ?>
	    <li data-key="<?php echo $key ?>" class="divi_options_li">

		<?php
		$show = 0;
		if (isset($divi_settings[$key]['show'])) {
		    $show = $divi_settings[$key]['show'];
		}
		
		if($show == 1 || $show == 3){ ?>
		<style>
		.price_slider_one{
			display:block !important;
		}
		</style>		
		<?php } ?>

	        <a href="#" style="display:none;" class="help_tip divi_drag_and_drope" data-tip="<?php _e("drag and drope", 'products-filter-custom'); ?>"><img src="<?php echo DIVI_LINK ?>img/move.png" alt="<?php _e("move", 'products-filter-custom'); ?>" /></a>
			
			<!--#############################remmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb###################-->

	        <!--#############################remmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb###################--> 
	        <div class="select-wrap">
	    	<select name="divi_settings[<?php echo $key ?>][show]" class="divi_setting_select">
	    	    <option value="0" <?php echo selected($show, 0) ?>><?php _e('No', 'products-filter-custom') ?></option>
	    	    <option class="toogle_icon" value="1" <?php echo selected($show, 1) ?>><?php _e('&#8887; As Slider', 'products-filter-custom') ?></option>
	    	    <!--<option value="2" <?php //echo selected($show, 2) ?>><?php //_e('&#9674; As drop-down', 'products-filter-custom') ?></option>
	    	    <option value="5" <?php //echo selected($show, 5) ?>><?php //_e('O As radio button', 'products-filter-custom') ?></option>
	    	    <option value="4" <?php //echo selected($show, 4) ?>><?php //_e('&#9645; As textinputs', 'products-filter-custom') ?></option>
	    	    <option value="3" <?php //echo selected($show, 3) ?>><?php //_e('&#8789; As ion range-slider', 'products-filter-custom') ?></option>-->

	    	</select>
	        </div>
			<input type="text" class="remove_icon_dropdown" placeholder="(e.g. Search by Price)" name="divi_settings[<?php echo $key ?>][title_text]" value="<?php echo $divi_settings[$key]['title_text'] ?>" />
	        <input style="display:none;" type="button" value="<?php _e('additional options', 'products-filter-custom') ?>" data-key="<?php echo $key ?>" data-name="<?php _e("Search by Price", 'products-filter-custom'); ?>" class="divi-button js_divi_options js_divi_options_<?php echo $key ?>" />

			<!--<strong style="display: inline-block; width: 176px;"><?php //_e("Search by Price", 'products-filter-custom'); ?></strong> -->
            <div class="three_button" style="width: 200px;float: right;font-size: 25px;"><i class="fa fa-eye" aria-hidden="true"></i>  <i class="fa fa-clone" aria-hidden="true"></i>  <i class="fa fa-trash" aria-hidden="true"></i> </div>
		<?php
		if (!isset($divi_settings[$key]['show_button'])) {
		    $divi_settings[$key]['show_button'] = 0;
		}

		if (!isset($divi_settings[$key]['title_text'])) {
		    $divi_settings[$key]['title_text'] = '';
		}


		if (!isset($divi_settings[$key]['ranges'])) {
		    $divi_settings[$key]['ranges'] = '';
		}

		if (!isset($divi_settings[$key]['first_option_text'])) {
		    $divi_settings[$key]['first_option_text'] = '';
		}

		if (!isset($divi_settings[$key]['ion_slider_step'])) {
		    $divi_settings[$key]['ion_slider_step'] = 1;
		}
		?>

	        <input type="hidden" name="divi_settings[<?php echo $key ?>][show_button]" value="<?php echo $divi_settings[$key]['show_button'] ?>" />
	        <!--<input type="hidden" name="divi_settings[<?php //echo $key ?>][title_text]" value="<?php //echo $divi_settings[$key]['title_text'] ?>" />-->
	        <input type="hidden" name="divi_settings[<?php echo $key ?>][ranges]" value="<?php echo $divi_settings[$key]['ranges'] ?>" />
	        <input type="hidden" name="divi_settings[<?php echo $key ?>][first_option_text]" value="<?php echo $divi_settings[$key]['first_option_text'] ?>" />
	        <input type="hidden" name="divi_settings[<?php echo $key ?>][ion_slider_step]" value="<?php echo $divi_settings[$key]['ion_slider_step'] ?>" />

	    </li>
	    <?php
	    break;

	default:
	    //options for extensions
	    do_action('divi_print_html_type_options_' . $key);
	    break;
    }
}

?>
<script>
	 jQuery(document).ready(function(){
		 //alert('fffffffff');
		jQuery('input.button-primary.woocommerce-save-button').val('Save');
	});
</script>
<?php 
if($divi_settings['divi_box_backgroung'] != '') { $color1 = $divi_settings['divi_box_backgroung']; }
else{ $color1 = '#d8d8d8'; }
if($divi_settings['divi_box_border_color'] != '') { $color2 = $divi_settings['divi_box_border_color']; }
else{ $color2 = '#7f7f7f'; }
if($divi_settings['divi_filter_name_color'] != '') { $color3 = $divi_settings['divi_filter_name_color']; }
else{ $color3 = '#1d1b10'; }
if($divi_settings['divi_filter_content_color'] != '') { $color4 = $divi_settings['divi_filter_content_color']; }
else{ $color4 = '#3f3f3f'; }
if($divi_settings['divi_mainbutton_color'] != '') { $color5 = $divi_settings['divi_mainbutton_color']; }
else{ $color5 = '#ffffff'; }
if($divi_settings['divi_mainbutton_bgcolor'] != '') { $color6 = $divi_settings['divi_mainbutton_bgcolor']; }
else{ $color6 = '#607D8B'; }
if($divi_settings['divi_mainbutton_bcolor'] != '') { $color7 = $divi_settings['divi_mainbutton_bcolor']; }
else{ $color7 = '#a5a5a5'; }
if($divi_settings['divi_apply_color'] != '') { $color8 = $divi_settings['divi_apply_color']; }
else{ $color8 = '#ffffff'; }
if($divi_settings['divi_apply_bgcolor'] != '') { $color9 = $divi_settings['divi_apply_bgcolor']; }
else{ $color9 = '#9e9e9e'; }
if($divi_settings['divi_apply_bcolor'] != '') { $color10 = $divi_settings['divi_apply_bcolor']; }
else{ $color10 = '#ffc000'; }
if($divi_settings['divi_reset_color'] != '') { $color11 = $divi_settings['divi_reset_color']; }
else{ $color11 = '#ffffff'; }
if($divi_settings['divi_reset_bgcolor'] != '') { $color12 = $divi_settings['divi_reset_bgcolor']; }
else{ $color12 = '#9e9e9e'; }
if($divi_settings['divi_reset_bcolor'] != '') { $color13 = $divi_settings['divi_reset_bcolor']; }
else{ $color13 = '#b2a2c7'; }
if($divi_settings['divi_filter_title_color'] != '') { $color14 = $divi_settings['divi_filter_title_color']; }
else{ $color14 = '#000000'; }
if($divi_settings['divi_box_show5'] != '') { $color15 = $divi_settings['divi_box_show5']; }
else{ $color15 = '#000000'; }
?>

<script>
jQuery(document).ready(function(){
	// Methods demo
	jQuery('#getVal').on('click', function(){
		alert('Selected color = "' + jQuery('.cp1').colorpicker("val") + '"');
	});
	jQuery('#setVal').on('click', function(){
		jQuery('.cp1').colorpicker("val",'#31859b');
	});
	jQuery('#enable').on('click', function(){
		jQuery('#cp1').colorpicker("enable");
	});
	jQuery('#disable').on('click', function(){
		jQuery('.cp1').colorpicker("disable");
	});
	jQuery('#clear').on('click', function(){
		jQuery('.cp1').colorpicker("clear");
	});
	jQuery('#destroy1').on('click', function(){
		jQuery('.cp1').colorpicker("destroy");
	});
	
	// Instanciate colorpickers
	jQuery('.cp1').colorpicker({
		color:'#000000',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})

	jQuery('.color1').colorpicker({
		color:'<?php echo $color1; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	jQuery('.color2').colorpicker({
		color:'<?php echo $color2; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	jQuery('.color3').colorpicker({
		color:'<?php echo $color3; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	jQuery('.color4').colorpicker({
		color:'<?php echo $color4; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	jQuery('.color5').colorpicker({
		color:'<?php echo $color5; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	jQuery('.color6').colorpicker({
		color:'<?php echo $color6; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	jQuery('.color7').colorpicker({
		color:'<?php echo $color7; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	jQuery('.color8').colorpicker({
		color:'<?php echo $color8; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	jQuery('.color9').colorpicker({
		color:'<?php echo $color9; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	jQuery('.color10').colorpicker({
		color:'<?php echo $color10; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	jQuery('.color11').colorpicker({
		color:'<?php echo $color11; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	jQuery('.color12').colorpicker({
		color:'<?php echo $color12; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	jQuery('.color13').colorpicker({
		color:'<?php echo $color13; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	jQuery('.color14').colorpicker({
		color:'<?php echo $color14; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})
	jQuery('.color15').colorpicker({
		color:'<?php echo $color15; ?>',
		initialHistory: ['#ff0000','#000000','red', 'purple']
	})

	jQuery('.more_info_color').on('click', function(){
		jQuery(".more_info_color_div").toggle('slow');
	});
	jQuery('.more_info_brand').on('click', function(){
		jQuery(".more_info_brand_div").toggle('slow');
	});

});

</script>


<style>
.section_right{
	width: <?php echo $divi_settings['divi_box_maxwidth']; ?>% !important;
	background: <?php echo $color1; ?>;   
    border: <?php echo $divi_settings['divi_box_border_size']; ?>px solid <?php echo $color2; ?>;
    border-radius: <?php echo $divi_settings['divi_box_radius']; ?>px;
    opacity: <?php echo $divi_settings['divi_box_opacity']; ?>;
}
.section_right h3 {
    color: <?php echo $divi_settings['divi_filter_title_color']; ?> !important;
	border-bottom: 1px solid #ddd;
    font-size: <?php echo $divi_settings['divi_filter_title_size']; ?>px;
    font-weight: <?php echo $divi_settings['divi_filter_title_weight']; ?>;
    padding: <?php echo $divi_settings['divi_filter_title_top'].'px '.$divi_settings['divi_filter_title_right'].'px '.$divi_settings['divi_filter_title_buttom'].'px '.$divi_settings['divi_filter_title_left']; ?>px;
	font-family:<?php echo $divi_settings['divi_filter_title_fontfamily']; ?>;
	margin: 0 0 0.75em;
}
.reset_text{
 color: <?php echo $color11; ?> !important;
 background: <?php echo $color12; ?>; 
 font-size:<?php echo $divi_settings['divi_reset_size']; ?>px;
 font-weight:<?php echo $divi_settings['divi_reset_weight']; ?>;
 border: <?php echo $divi_settings['divi_reset_bsize']; ?>px solid <?php echo $color13; ?>;  
 font-family:<?php echo $divi_settings['divi_reset_fontfamily']; ?>; 
 box-shadow: <?php echo $divi_settings['divi_box_show1'].'px '.$divi_settings['divi_box_show2'].'px '.$divi_settings['divi_box_show3'].'px '.$divi_settings['divi_box_show4'].'px '.$color15; ?>;
 padding: 1em 2em;
}
.apply_text{
 color: <?php echo $color8; ?>;
 background: <?php echo $color9; ?>; 
 font-size:<?php echo $divi_settings['divi_apply_size']; ?>px;
 font-weight:<?php echo $divi_settings['divi_apply_weight']; ?>;
 border: <?php echo $divi_settings['divi_apply_bsize']; ?>px solid <?php echo $color10; ?>;
 font-family:<?php echo $divi_settings['divi_apply_fontfamily']; ?>;
 box-shadow: <?php echo $divi_settings['divi_box_show1'].'px '.$divi_settings['divi_box_show2'].'px '.$divi_settings['divi_box_show3'].'px '.$divi_settings['divi_box_show4'].'px '.$color15; ?>;
 padding: 1em 2em;
}
button.preview_apply_reset_right {
    color: <?php echo $color5; ?>;
    background: <?php echo $color6; ?>;
    border: <?php echo $divi_settings['divi_mainbutton_bsize']; ?>px solid <?php echo $color7; ?>;
    font-size: <?php echo $divi_settings['divi_mainbutton_size']; ?>px;
    padding: <?php echo $divi_settings['divi_mainbutton_top']; ?>px <?php echo $divi_settings['divi_mainbutton_right']; ?>px <?php echo $divi_settings['divi_mainbutton_buttom']; ?>px <?php echo $divi_settings['divi_mainbutton_left']; ?>px;
	border-radius: <?php echo $divi_settings['divi_mainbutton_radius']; ?>px;
	line-height: 0;
	margin-top: -3px;
    margin-right: -1px;
}
.preview_cat{
    max-height: <?php echo $divi_settings['divi_box_maxheight']; ?>px;
	overflow-y: auto;	
}
.filter_back_end_h4{
    color: <?php echo $color3; ?> !important;
    line-height: <?php echo $divi_settings['divi_filter_name_lineheight']; ?>px;
    font-size: <?php echo $divi_settings['divi_filter_name_size']; ?>px;
    font-weight: <?php echo $divi_settings['divi_filter_name_weight']; ?>;
    padding: <?php echo $divi_settings['divi_filter_name_top'].'px '.$divi_settings['divi_filter_name_right'].'px '.$divi_settings['divi_filter_name_buttom'].'px '.$divi_settings['divi_filter_name_left']; ?>px;
	font-family:<?php echo $divi_settings['divi_filter_name_fontfamily']; ?>;
}
span.label_mname{
    color: <?php echo $color4; ?> !important;
    line-height: <?php echo $divi_settings['divi_filter_content_lineheight']; ?>px;
    font-size: <?php echo $divi_settings['divi_filter_content_size']; ?>px;
    font-weight: <?php echo $divi_settings['divi_filter_content_weight']; ?>;
    padding: <?php echo $divi_settings['divi_filter_content_top'].'px '.$divi_settings['divi_filter_content_right'].'px '.$divi_settings['divi_filter_content_buttom'].'px '.$divi_settings['divi_filter_content_left']; ?>px;
	font-family:<?php echo $divi_settings['divi_filter_content_fontfamily']; ?>;
}
.product_visibility {
    display: none;
}
</style>