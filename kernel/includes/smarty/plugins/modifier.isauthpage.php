<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     basename
 * Purpose:  see basename() in php manual
 * -------------------------------------------------------------
 */
function smarty_modifier_isauthpage($string)
{
    $enabled = array('auxpage_1',
                     'auxpage_2',
                     'auxpage_programka-shtrih-kod',
                     'auxpage_preimushestva',
                     'news',
                     'auxpage_cash-carry',
                     'auxpage_zamena-braka',
                     'register',
                     'feedback',
                     'auth',
                     'successful_registration',
                     'office',
                     'contact_info',
                     'address_book',
                     'order_history',
                     'add_address',
                     'address_editor');

    return in_array($string, $enabled) || (substr($string,0,8)=='auxpage_');
}

/* vim: set expandtab: */

?>