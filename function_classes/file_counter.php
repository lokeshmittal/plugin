<?php

if (!defined('ABSPATH'))
    die('No direct access allowed');

final class WP_QueryWoofCounter
{

    public $post_count = 0;
    public $found_posts = 0;
    public $key_string = "";
    public $table = "";

    //public static $collector = array();

    public function __construct($query)
    {
        global $wpdb;
        global $DIVI;
        $query = (array) $query;
        $key = md5(json_encode($query));
        //***
        $this->key_string = 'divi_count_cache_' . $key;
        $this->table = DIVI::$query_cache_table;
        //***
        $divi_settings = get_option('divi_settings', array());

        $_REQUEST['divi_before_recount_query'] = 1;
        if ($divi_settings['cache_count_data'])
        {
            $value = $this->get_value();
            if ($value != -1)
            {
                $this->post_count = $this->found_posts = $value;
            } else
            {
                $q = new WP_QueryDIVICounterIn($query);
                $this->post_count = $this->found_posts = $q->post_count;
                unset($q);
                $this->set_value();
            }
        } else
        {
            $q = new WP_QueryDIVICounterIn($query);
            $this->post_count = $this->found_posts = $q->post_count;
            unset($q);
        }
        unset($_REQUEST['divi_before_recount_query']);
    }

    private function set_value()
    {
        global $wpdb;
        $wpdb->query($wpdb->prepare("INSERT INTO {$this->table} (mkey, mvalue) VALUES (%s, %d)", $this->key_string, $this->post_count));
    }

    private function get_value()
    {
        global $wpdb;
        $result = -1;
        $sql = $wpdb->prepare("SELECT mkey,mvalue FROM {$this->table} WHERE mkey=%s", $this->key_string);
        $value = $wpdb->get_results($sql);

        if (!empty($value))
        {
            $value = end($value);
            if (isset($value->mkey))
            {
                $result = $value->mvalue;
            }
        }

        return $result;
    }

}

final class WP_QueryDIVICounterIn extends WP_Query
{

    function __construct($query = '')
    {
        parent::__construct($query);
    }

    function set_found_posts($q, $limits)
    {
        return false;
    }

    function setup_postdata($post)
    {
        return false;
    }

    function the_post()
    {
        return FALSE;
    }

    function have_posts()
    {
        return FALSE;
    }

}