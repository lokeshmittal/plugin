<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');

//***

global $wp_query;
?>

<?php if ($this->is_isset_in_request_data($this->get_sdivi_search_slug())): ?>
    <span class="divi_found_count"><?php echo(isset($_REQUEST['divi_wp_query_found_posts']) ? $_REQUEST['divi_wp_query_found_posts'] : $wp_query->found_posts) ?></span>
<?php endif; ?>



