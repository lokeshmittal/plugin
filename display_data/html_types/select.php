<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<?php
global $DIVI;
$collector = array();
$_REQUEST['additional_taxes'] = $additional_taxes;
$_REQUEST['hide_terms_count_txt'] = 0;
$divi_hide_dynamic_empty_pos = 0;

if (!function_exists('divi_draw_select_childs'))
{

    function divi_draw_select_childs(&$collector, $taxonomy_info, $term_id, $tax_slug, $childs, $level, $show_count, $show_count_dynamic, $hide_dynamic_empty_pos)
    {
        $do_not_show_childs = (int) apply_filters('divi_terms_where_hidden_childs', $term_id);

        if ($do_not_show_childs == 1)
        {
            return "";
        }

        //***

        global $DIVI;
        $request = $DIVI->get_request_data();
        $divi_hide_dynamic_empty_pos = 0;
        //***        
        $current_request = array();
        if ($DIVI->is_isset_in_request_data($tax_slug))
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

        $childs = apply_filters('divi_sort_terms_before_out', $childs, 'select');
        ?>
        <?php if (!empty($childs)): ?>
            <?php foreach ($childs as $term) : ?>
                <?php
                $count_string = "";
                $count = 0;
                if (!in_array($term['slug'], $current_request))
                {
                    if ($show_count)
                    {
                        if ($show_count_dynamic)
                        {
                            $count = $DIVI->dynamic_count($term, 'single', $_REQUEST['additional_taxes']);
                        } else
                        {
                            $count = $term['count'];
                        }
                        $count_string = '(' . $count . ')';
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
                <option <?php if ($show_count AND $count == 0 AND ! in_array($term['slug'], $current_request)): ?>disabled=""<?php endif; ?> value="<?php echo $term['slug'] ?>" <?php echo selected(in_array($term['slug'], $current_request)) ?> class="divi-padding-<?php echo $level ?>"><?php echo str_repeat('', $level) ?><?php
                    if (has_filter('divi_before_term_name'))
                        echo apply_filters('divi_before_term_name', $term, $taxonomy_info);
                    else
                        echo $term['name'];
                    ?> <?php echo $count_string ?></option>
                <?php
                if (!isset($collector[$tax_slug]))
                {
                    $collector[$tax_slug] = array();
                }

                $collector[$tax_slug][] = array('name' => $term['name'], 'slug' => $term['slug'], 'term_id' => $term['term_id']);

                //+++

                if (!empty($term['childs']))
                {
                    divi_draw_select_childs($collector, $taxonomy_info, $term['term_id'], $tax_slug, $term['childs'], $level + 1, $show_count, $show_count_dynamic, $hide_dynamic_empty_pos);
                }
                ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php
    }

}
?>
<select class="divi_select divi_select_<?php echo $tax_slug ?>" name="<?php echo $tax_slug ?>">
    <option value="0"><?php echo DIVI_HELPER::wpml_translate($taxonomy_info) ?></option>
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

    $terms = apply_filters('divi_sort_terms_before_out', $terms, 'select');
    $shown_options_tags = 0;
    ?>
    <?php if (!empty($terms)): ?>
        <?php foreach ($terms as $term) : ?>
            <?php
            $count_string = "";
            $count = 0;
            if (!in_array($term['slug'], $current_request))
            {
                if ($show_count)
                {
                    if ($show_count_dynamic)
                    {
                        $count = $this->dynamic_count($term, 'single', $_REQUEST['additional_taxes']);
                    } else
                    {
                        $count = $term['count'];
                    }
                    $count_string = '(' . $count . ')';
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
            <option <?php if ($show_count AND $count == 0 AND ! in_array($term['slug'], $current_request)): ?>disabled=""<?php endif; ?> value="<?php echo $term['slug'] ?>" <?php echo selected(in_array($term['slug'], $current_request)) ?>><?php
                if (has_filter('divi_before_term_name'))
                    echo apply_filters('divi_before_term_name', $term, $taxonomy_info);
                else
                    echo $term['name'];
                ?> <?php echo $count_string ?></option>
            <?php
            if (!isset($collector[$tax_slug]))
            {
                $collector[$tax_slug] = array();
            }

            $collector[$tax_slug][] = array('name' => $term['name'], 'slug' => $term['slug'], 'term_id' => $term['term_id']);

            //+++

            if (!empty($term['childs']))
            {
                divi_draw_select_childs($collector, $taxonomy_info, $term['term_id'], $tax_slug, $term['childs'], 1, $show_count, $show_count_dynamic, $hide_dynamic_empty_pos);
            }
            $shown_options_tags++;
            ?>
        <?php endforeach; ?>
    <?php endif; ?>
</select>
<?php if ($shown_options_tags == 0): ?>
    <style type="text/css">
        .divi_container_<?php echo $tax_slug ?>{
            display:none;
        }
    </style>
<?php endif; ?>            

<?php
//this is for divi_products_top_panel
if (!empty($collector))
{
    foreach ($collector as $ts => $values)
    {
        if (!empty($values))
        {
            foreach ($values as $value)
            {
                ?>
                <input type="hidden" value="<?php echo $value['name'] ?>" data-anchor="divi_n_<?php echo $ts ?>_<?php echo $value['slug'] ?>" />
                <?php
            }
        }
    }
}

//we need it only here, and keep it in $_REQUEST for using in function for child items
unset($_REQUEST['additional_taxes']);


