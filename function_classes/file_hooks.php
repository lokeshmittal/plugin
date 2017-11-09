<?php

if (!defined('ABSPATH'))
    die('No direct access allowed');

final class DIVI_HOOKS
{

    public static function divi_get_front_css_file_link()
    {
        return apply_filters('divi_get_front_css_file_link', DIVI_LINK . 'css/front.css');
    }

}
