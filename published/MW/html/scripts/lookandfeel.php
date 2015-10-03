<?php

    require_once("../../../common/html/includes/httpinit.php");

    //
    // Authorization
    //

    $errorStr = null;
    $fatalError = false;
    $SCR_ID = "LF";

    pageUserAuthorization($SCR_ID, $MW_APP_ID, true);

    //
    // Page variables setup
    //
    $kernelStrings = $loc_str[$language];
    $mw_locStrings = $mw_loc_str[$language];
    $invalidField = null;

    //
    // Functions
    //

    function listLayouts()
    {
        global $mw_locStrings;
        $Layouts = array();

        $path = WBS_DIR."published/common/html/cssbased/layout";

        if (!($handle = @opendir($path)))
            return null;

        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $filename = $path.'/'.$file;

                if (is_dir($filename)) {
                    $layout_info = $filename.'/info.php';

                    if (file_exists($layout_info)) {
                        include($layout_info);
                        $Layouts[$file] = $layoutInfo;
                        $Layouts[$file]['name'] = $mw_locStrings[$layoutInfo['name']];
                    }
                }
            }
        }

        closedir($handle);

        return $Layouts;
    }

    function mw_sortThemes($a, $b)
    {
        return strcmp($a['name'], $b['name']);
    }

    function listThemes()
    {
        global $mw_locStrings;
        $Themes = array();

        $path = WBS_DIR."published/common/html/cssbased/themes";

        if (!($handle = @opendir($path)))
            return null;

        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $filename = $path.'/'.$file;

                if (is_dir($filename)) {
                    $theme_info = $filename.'/info.php';

                    if (file_exists($theme_info)) {
                        include($theme_info);
                        $Themes[$file] = $themeInfo;
                        $Themes[$file]['name'] = $mw_locStrings[$themeInfo['name']];
                    }
                }
            }
        }

        uasort($Themes, 'mw_sortThemes');

        closedir($handle);

        return $Themes;
    }

    //
    // Form handling
    //
    $btnIndex = getButtonIndex(array(BTN_SAVE, BTN_CANCEL), $_POST);

    switch ($btnIndex) {
        case 0 :
            writeUserCommonSetting($currentUser, SCREEN_LAYOUT, $curLayout, $kernelStrings);
            writeUserCommonSetting($currentUser, SCREEN_THEME, $curTheme, $kernelStrings);
            writeUserCommonSetting($currentUser, SCREEN_LOGO, $curLogo, $kernelStrings);

            $params = array(INFO_STR => urlencode(base64_encode($mw_locStrings['lf_changeaccepted_message'])));

            redirectBrowser(PAGE_SIMPLEREPORT, $params);
        case 1 :
            redirectBrowser(PAGE_SIMPLEREPORT, array(INFO_STR => urlencode(base64_encode($mw_locStrings['lf_changecanceled_message'])), "reportType" => 2));
    }

    switch (true) {
        case true:
            $Themes = listThemes();
            $Layouts = listLayouts();

            if (!isset($edited)) {
                $curTheme = readUserCommonSetting($currentUser, SCREEN_THEME);
                if (!strlen($curTheme))
                    $curTheme = strlen($styleSet) ? $styleSet : HTML_DEFAULT_STYLESET;

                $curLayout = readUserCommonSetting($currentUser, SCREEN_LAYOUT);
                if (!strlen($curLayout))
                    $curLayout = "topmenu";

                $curLogo = readUserCommonSetting($currentUser, SCREEN_LOGO);
                if (!strlen($curLogo))
                    $curLogo = "app";
            }
    }

    //
    // Page implementation
    //
    $preproc = new php_preprocessor($templateName, $kernelStrings, $language, $MW_APP_ID);

    $preproc->assign(PAGE_TITLE, $mw_locStrings['lf_screen_long_name']);
    $preproc->assign(FORM_LINK, PAGE_LOOKANDFEEL);
    $preproc->assign(INVALID_FIELD, $invalidField);
    $preproc->assign(ERROR_STR, $errorStr);
    $preproc->assign(FATAL_ERROR, $fatalError);
    $preproc->assign(HELP_TOPIC, "preferences.htm");
    $preproc->assign("mwStrings", $mw_locStrings);

    if (!$fatalError) {
        $preproc->assign("Themes", $Themes);
        $preproc->assign("Layouts", $Layouts);
        $preproc->assign("curTheme", $curTheme);
        $preproc->assign("curLayout", $curLayout);
        $preproc->assign("curLogo", $curLogo);
    }

    $preproc->display("lookandfeel.htm");
?>