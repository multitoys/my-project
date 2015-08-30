<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
function smarty_modifier_welcome($log)
{
   $hour = (int)strftime('%H');
	$welcome = '';
	if ($hour > 0 and $hour < 6)
		$welcome = 'Доброй ночи';
	elseif ($hour >= 6 and $hour < 12)
		$welcome = 'Доброе утро';
	elseif ($hour >= 12 and $hour < 18)
		$welcome = 'Добрый день';
	elseif ($hour >= 18 and $hour < 23)
		$welcome = 'Добрый вечер';
	else
		$welcome = 'Доброй ночи';
	//echo $welcome;
   return $welcome;
    }
?>