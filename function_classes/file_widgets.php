<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

class DIVI_Widget extends WP_Widget {

//Widget Setup
    public function __construct()
    {
        parent::__construct(__CLASS__, __('WooCommerce Products Filter', 'products-filter-custom'), array(
            'classname' => __CLASS__,
            'description' => __('Products Filter by webcosoftech', 'products-filter-custom')
                )
        );
    }

//Widget view
    public function widget($args, $instance)
    {
        $args['instance'] = $instance;
        $args['sidebar_id'] = $args['id'];
        $args['sidebar_name'] = $args['name'];
        //+++
        global $DIVI;
        $price_filter = 0;
        if (isset($DIVI->settings['by_price']['show']))
        {
            $price_filter = (int) $DIVI->settings['by_price']['show'];
        }



        if (isset($args['before_widget']))
        {
            echo $args['before_widget'];
        }
        ?>
        <div class="widget widget-divi">
            <?php
            if (!empty($instance['title']))
            {
                if (isset($args['before_title']))
                {
                    echo $args['before_title'];
                    echo $instance['title'];
                    echo $args['after_title'];
                } else
                {
                    ?>
                    <<?php echo apply_filters('divi_widget_title_tag', 'h3'); ?> class="widget-title"><?php echo $instance['title'] ?></<?php echo apply_filters('divi_widget_title_tag', 'h3'); ?>>
                    <?php
                }
            }
            ?>


            <?php
            if (isset($instance['additional_text_before']))
            {
                echo do_shortcode($instance['additional_text_before']);
            }

            $redirect = '';
            if (isset($instance['redirect']))
            {
                $redirect = $instance['redirect'];
            }

            //+++

            $divi_start_filtering_btn = 0;
            if (isset($instance['divi_start_filtering_btn']))
            {
                $divi_start_filtering_btn = (int) $instance['divi_start_filtering_btn'];
            }

            //+++

            $ajax_redraw = '';
            if (isset($instance['ajax_redraw']))
            {
                $ajax_redraw = $instance['ajax_redraw'];
            }
            ?>

            <?php echo do_shortcode('[divi sid="widget" start_filtering_btn=' . $divi_start_filtering_btn . ' price_filter=' . $price_filter . ' redirect="' . $redirect . '" ajax_redraw="' . $ajax_redraw . '"]'); ?>
        </div>
        <?php
        if (isset($args['after_widget']))
        {
            echo $args['after_widget'];
        }
    }

//Update widget
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['additional_text_before'] = $new_instance['additional_text_before'];
        $instance['redirect'] = $new_instance['redirect'];
        $instance['divi_start_filtering_btn'] = $new_instance['divi_start_filtering_btn'];
        $instance['ajax_redraw'] = $new_instance['ajax_redraw'];
        return $instance;
    }

//Widget form
    public function form($instance)
    {
//Defaults
        $defaults = array(
            'title' => __('WooCommerce Products Filter', 'products-filter-custom'),
            'additional_text_before' => '',
            'redirect' => '',
            'divi_start_filtering_btn' => 0,
            'ajax_redraw' => 0
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        $args = array();
        $args['instance'] = $instance;
        $args['widget'] = $this;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'products-filter-custom') ?>:</label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('additional_text_before'); ?>"><?php _e('Additional text before', 'products-filter-custom') ?>:</label>
            <textarea class="widefat" type="text" id="<?php echo $this->get_field_id('additional_text_before'); ?>" name="<?php echo $this->get_field_name('additional_text_before'); ?>"><?php echo $instance['additional_text_before']; ?></textarea>
        </p>
		<!--
        <p>
            <label for="<?php //echo $this->get_field_id('redirect'); ?>"><?php //_e('Redirect to', 'products-filter-custom') ?>:</label>
            <input class="widefat" type="text" id="<?php //echo $this->get_field_id('redirect'); ?>" name="<?php //echo $this->get_field_name('redirect'); ?>" value="<?php //echo $instance['redirect']; ?>" /><br />
            <i><?php //_e('Redirect to any page - use it by your own logic. Leave it empty for default behavior.', 'products-filter-custom') ?></i>
        </p>
		-->
        <p>
            <label for="<?php echo $this->get_field_id('divi_start_filtering_btn'); ?>"><?php _e('Hide search form by default and show one button instead', 'products-filter-custom') ?>:</label>
            <?php
            $options = array(
                0 => __('No', 'products-filter-custom'),
                1 => __('Yes', 'products-filter-custom')
            );
            ?>
            <select class="widefat" id="<?php echo $this->get_field_id('divi_start_filtering_btn') ?>" name="<?php echo $this->get_field_name('divi_start_filtering_btn') ?>">
                <?php foreach ($options as $k => $val) : ?>
                    <option <?php selected($instance['divi_start_filtering_btn'], $k) ?> value="<?php echo $k ?>" class="level-0"><?php echo $val ?></option>
                <?php endforeach; ?>
            </select>
            <i><?php _e('User on the site front will have to press button like "Show products filter form" to load search form by ajax and start filtering. Good feature when search form is quite big and page loading takes more time because of it!', 'products-filter-custom') ?></i>
        </p>
		<!--
        <p>
            <label for="<?php //echo $this->get_field_id('ajax_redraw'); ?>"><?php //_e('Form AJAX redrawing', 'products-filter-custom') ?>:</label>
            <?php /*
            $options = array(
                0 => __('No', 'products-filter-custom'),
                1 => __('Yes', 'products-filter-custom')
            ); */
            ?>
            <select class="widefat" id="<?php //echo $this->get_field_id('ajax_redraw') ?>" name="<?php //echo $this->get_field_name('ajax_redraw') ?>">
                <?php /* foreach ($options as $k => $val) : */ ?>
                    <option <?php //selected($instance['ajax_redraw'], $k) ?> value="<?php //echo $k ?>" class="level-0"><?php //echo $val ?></option>
                <?php //endforeach; ?>
            </select>
            <i><?php //_e('Redraws search form by AJAX, and to start filtering "Filter" button should be pressed. Useful when uses hierarchical drop-down for example', 'products-filter-custom') ?></i>
        </p>
		-->
        <?php
    }

}
