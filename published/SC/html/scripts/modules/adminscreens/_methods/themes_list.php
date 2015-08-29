<?php
/**
 * @package Modules
 * @subpackage AdministratorScreens
 */
	$smarty = &$Register->get(VAR_SMARTY);
	/* @var $smarty Smarty */
	$GetVars = &$Register->get(VAR_GET);
	$PostVars = &$Register->get(VAR_POST);
	
	if(isset($GetVars['apply_theme'])){
		
		_setSettingOptionValue('CONF_CURRENT_THEME', $GetVars['apply_theme']);
		Theme::cleanUpCache();
		Message::raiseMessageRedirectSQ(MSG_SUCCESS, 'apply_theme', 'thm_msg_theme_applied');
	}

	$themes = ns_themes::getThemes();
	
	$smarty->assign('current_theme', $themes[CONF_CURRENT_THEME]);
	unset($themes[CONF_CURRENT_THEME]);
	
	$smarty->assign('themes', $themes);
	$smarty->assign('admin_sub_dpt', 'themes_list.htm');
?>