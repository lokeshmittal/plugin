<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<?php
$request_data = $this->get_request_data();
$min_price = 0;
$max_price = DIVI_HELPER::get_max_price();

$min_price_txt = __('min price', 'products-filter-custom');
$max_price_txt = __('max price', 'products-filter-custom');



if (isset($request_data['min_price']))
{
    $min_price = $request_data['min_price'];
}

if (isset($request_data['max_price']))
{
    $max_price = $request_data['max_price'];
}

//+++
$min_price_data = $min_price;
$max_price_data = $max_price;
//WOOCS compatibility
if (class_exists('WOOCS'))
{
    $min_price_data = apply_filters('woocs_exchange_value', $min_price_data);
    $max_price_data = apply_filters('woocs_exchange_value', $max_price_data);
}
?>


<div class="divi_price_filter_txt_container">

    <input type="text" class="divi_price_filter_txt divi_price_filter_txt_from" placeholder="<?php echo $min_price_txt ?>" data-value="<?php echo $min_price_data ?>" value="<?php echo $min_price ?>" />&nbsp;<input type="text" class="divi_price_filter_txt divi_price_filter_txt_to" placeholder="<?php echo $max_price_txt ?>" name="max_price" data-value="<?php echo $max_price_data ?>" value="<?php echo $max_price ?>" />
    <?php if (class_exists('WOOCS')): ?>
        &nbsp;(<?php echo get_woocommerce_currency_symbol() ?>)
    <?php endif; ?>

</div>


<?php
