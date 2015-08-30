<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
function smarty_modifier_now($log)
{
   $now = strftime('%H-%M');
   return $now;
    }
?>