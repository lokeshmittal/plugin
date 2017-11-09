<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<?php
global $DIVI;
$_REQUEST['additional_taxes'] = $additional_taxes;
$_REQUEST['hide_terms_count_txt'] = 0;

//***

if (!function_exists('divi_draw_checkbox_childs'))
{

    function divi_draw_checkbox_childs($taxonomy_info, $tax_slug, $term_id, $childs, $show_count, $show_count_dynamic, $hide_dynamic_empty_pos)
    {
        $do_not_show_childs = (int) apply_filters('divi_terms_where_hidden_childs', $term_id);

        if ($do_not_show_childs == 1)
        {
            return "";
        }

        //***

        $current_request = array();
        global $DIVI;
        $request = $DIVI->get_request_data();
        if ($DIVI->is_isset_in_request_data($tax_slug))
        {
            $current_request = $request[$tax_slug];
            $current_request = explode(',', urldecode($current_request));
        }
        //***
        static $hide_childs = -1;
        if ($hide_childs == -1)
        {
            $hide_childs = (int) get_option('divi_checkboxes_slide');
        }


        //excluding hidden terms
        $hidden_terms = array();

        if (!isset($_REQUEST['divi_shortcode_excluded_terms']))
        {
            if (isset($DIVI->settings['excluded_terms'][$tax_slug]))
            {
                $hidden_terms = explode(',', $DIVI->settings['excluded_terms'][$tax_slug]);
            }
        } else
        {
            $hidden_terms = explode(',', $_REQUEST['divi_shortcode_excluded_terms']);
        }

        $childs = apply_filters('divi_sort_terms_before_out', $childs, 'checkbox');
        ?>
        <?php if (!empty($childs) AND is_array($childs)): ?>
            <ul class="divi_childs_list divi_childs_list_<?php echo $term_id ?>" <?php if ($hide_childs == 1): ?>style="display: none;"<?php endif; ?>>
                <?php foreach ($childs as $term) : $inique_id = uniqid(); ?>
                    <?php
                    $count_string = "";
                    $count = 0;
                    if (!in_array($term['slug'], $current_request))
                    {
                        if ($show_count)
                        {
                            if ($show_count_dynamic)
                            {
                                $count = $DIVI->dynamic_count($term, 'multi', $_REQUEST['additional_taxes']);
                            } else
                            {
                                $count = $term['count'];
                            }
                            $count_string = '<span class="divi_checkbox_count">(' . $count . ')</span>';
                        }
                        //+++
                        if ($hide_dynamic_empty_pos AND $count == 0)
                        {
                            continue;
                        }
                    }

                    if ($_REQUEST['hide_terms_count_txt'])
                    {
                        $count_string = "";
                    }

                    //excluding hidden terms
                    if (in_array($term['term_id'], $hidden_terms))
                    {
                        continue;
                    }
                    ?>
                    <li <?php if ($DIVI->settings['dispay_in_row'][$tax_slug] AND empty($term['childs'])): ?>style="display: inline-block !important;"<?php endif; ?>><input type="checkbox" <?php if (!$count AND ! in_array($term['slug'], $current_request) AND $show_count): ?>disabled=""<?php endif; ?> id="<?php echo 'divi_' . $term['term_id'] . '_' . $inique_id ?>" class="divi_checkbox_term divi_checkbox_term_<?php echo $term['term_id'] ?>" data-tax="<?php echo $tax_slug ?>" name="<?php echo $term['slug'] ?>" data-term-id="<?php echo $term['term_id'] ?>" value="<?php echo $term['term_id'] ?>" <?php echo checked(in_array($term['slug'], $current_request)) ?> /><label class="divi_checkbox_label <?php if (in_array($term['slug'], $current_request)): ?>divi_checkbox_label_selected<?php endif; ?>" for="<?php echo 'divi_' . $term['term_id'] . '_' . $inique_id ?>"><?php
                            if (has_filter('divi_before_term_name'))
                                echo apply_filters('divi_before_term_name', $term, $taxonomy_info);
                            else
                                echo $term['name'];
                            ?><?php echo $count_string ?></label>
                        <?php
                        if (!empty($term['childs']))
                        {
                            divi_draw_checkbox_childs($taxonomy_info, $tax_slug, $term['term_id'], $term['childs'], $show_count, $show_count_dynamic, $hide_dynamic_empty_pos);
                        }
                        ?>
                        <input type="hidden" value="<?php echo $term['name'] ?>" data-anchor="divi_n_<?php echo $tax_slug ?>_<?php echo $term['slug'] ?>" />

                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php
    }

}
?>
<ul class="divi_list divi_list_checkbox">
    <?php
    $divi_tax_values = array();
    $current_request = array();
    $request = $this->get_request_data();
    if ($this->is_isset_in_request_data($tax_slug))
    {
        $current_request = $request[$tax_slug];
        $current_request = explode(',', urldecode($current_request));
    }


    //excluding hidden terms
    $hidden_terms = array();
    if (!isset($_REQUEST['divi_shortcode_excluded_terms']))
    {
        if (isset($DIVI->settings['excluded_terms'][$tax_slug]))
        {
            $hidden_terms = explode(',', $DIVI->settings['excluded_terms'][$tax_slug]);
        }
    } else
    {
        $hidden_terms = explode(',', $_REQUEST['divi_shortcode_excluded_terms']);
    }

    //***

    $not_toggled_terms_count = 0;
    if (isset($DIVI->settings['not_toggled_terms_count'][$tax_slug]))
    {
        $not_toggled_terms_count = intval($DIVI->settings['not_toggled_terms_count'][$tax_slug]);
    }

    //***  

    $terms = apply_filters('divi_sort_terms_before_out', $terms, 'checkbox');
    $terms_count_printed = 0;
    $hide_next_term_li = false;
    ?>
    <?php if (!empty($terms) AND is_array($terms)): ?>
        <?php foreach ($terms as $term) : $inique_id = uniqid(); ?>
            <?php
            $count_string = "";
            $count = 0;
            if (!in_array($term['slug'], $current_request))
            {
                if ($show_count)
                {
                    if ($show_count_dynamic)
                    {
                        $count = $this->dynamic_count($term, 'multi', $_REQUEST['additional_taxes']);
                    } else
                    {
                        $count = $term['count'];
                    }
                    $count_string = '<span class="divi_checkbox_count">(' . $count . ')</span>';
                }
                //+++
                if ($hide_dynamic_empty_pos AND $count == 0)
                {
                    continue;
                }
            }

            if ($_REQUEST['hide_terms_count_txt'])
            {
                $count_string = "";
            }

            //excluding hidden terms
            if (in_array($term['term_id'], $hidden_terms))
            {
                continue;
            }

            //***

            if ($not_toggled_terms_count > 0 AND $terms_count_printed === $not_toggled_terms_count)
            {
                $hide_next_term_li = true;
            }
            ?>



            <li class="divi_term_<?php echo $term['term_id'] ?> <?php if ($hide_next_term_li): ?>divi_hidden_term<?php endif; ?>" <?php if ($this->settings['dispay_in_row'][$tax_slug] AND empty($term['childs'])): ?>style="display: inline-block !important;"<?php endif; ?>><input type="checkbox" <?php if (!$count AND ! in_array($term['slug'], $current_request) AND $show_count): ?>disabled=""<?php endif; ?> id="<?php echo 'divi_' . $term['term_id'] . '_' . $inique_id ?>" class="divi_checkbox_term divi_checkbox_term_<?php echo $term['term_id'] ?>" data-tax="<?php echo $tax_slug ?>" name="<?php echo $term['slug'] ?>" data-term-id="<?php echo $term['term_id'] ?>" value="<?php echo $term['term_id'] ?>" <?php echo checked(in_array($term['slug'], $current_request)) ?> /><label class="divi_checkbox_label <?php if (in_array($term['slug'], $current_request)): ?>divi_checkbox_label_selected<?php endif; ?>" for="<?php echo 'divi_' . $term['term_id'] . '_' . $inique_id ?>"><?php
                    if (has_filter('divi_before_term_name'))
                        echo apply_filters('divi_before_term_name', $term, $taxonomy_info);
                    else
                        echo $term['name'];
                    ?><?php echo $count_string ?></label>
                <?php
                if (!empty($term['childs']))
                {
                    divi_draw_checkbox_childs($taxonomy_info, $tax_slug, $term['term_id'], $term['childs'], $show_count, $show_count_dynamic, $hide_dynamic_empty_pos);
                }
                ?>
                <input type="hidden" value="<?php echo $term['name'] ?>" data-anchor="divi_n_<?php echo $tax_slug ?>_<?php echo $term['slug'] ?>" />

            </li>


            <?php
            $terms_count_printed++;
        endforeach;
        ?>

        <?php
        if ($not_toggled_terms_count > 0 AND $terms_count_printed > $not_toggled_terms_count):
            ?>
            <li class="divi_open_hidden_li"><?php DIVI_HELPER::draw_more_less_button('checkbox') ?></li>
            <?php endif; ?>
        <?php endif; ?>
</ul>
<?php
//we need it only here, and keep it in $_REQUEST for using in function for child items
unset($_REQUEST['additional_taxes']);
