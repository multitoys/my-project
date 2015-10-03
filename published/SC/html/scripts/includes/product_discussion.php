<?php
    $Register = &Register::getInstance();
    /*@var $Register Register*/
    $Message = $Register->get(VAR_MESSAGE);
    /*@var $Message Message*/

    // product discussion page
    if (isset($_GET['productID'])) {
        $product = new Product();
        $productID = $_GET['productID'];
        if (!$product->loadByID($productID) && !$product->enabled)
            RedirectSQ("?ukey=product_not_found");
    } else {
        RedirectSQ("?ukey=product_not_found");
    }

    if (isset($_POST["add_topic"])) { // add post to the product discussion

        if (CONF_ENABLE_CONFIRMATION_CODE) {
            require_once(DIR_CLASSES.'/class.ivalidator.php');
            $iVal = new IValidator();
            if (!$iVal->checkCode($_POST['fConfirmationCode'])) {

                Message::raiseMessageRedirectSQ(MSG_ERROR, '#add-review', "err_wrong_ccode", '', array('topic_data' => $_POST));
            }
        }
        discAddDiscussion($productID, $_POST["nick"], $_POST["topic"], $_POST["body"]);
        RedirectSQ('productID='.$productID.'&ukey=discuss_product');
    }
    /*
        if (isset($_GET["remove_topic"]) && isset($productID) && isset($_SESSION["log"]) && !strcmp($_SESSION["log"], ADMIN_LOGIN)) // delete topic in the discussion
        {
            discDeleteDiscusion( $_GET["remove_topic"] );
            Redirect(set_query('productID='.$productID.'&ukey=discuss_product&remove_topic='));
        }
    */

    $smarty->assign('productID', $productID);
    $smarty->assign("discuss", "yes");

    //$q = db_query("SELECT ".LanguagesManager::sql_prepareField('name')." AS name from ".PRODUCTS_TABLE." where productID='$productID' and enabled=1") or die (db_error());
    //$a = db_fetch_row($q);
    //if (!$a)return;
    //if (!$product->loadByID($productID)&&!$product->enabled)return;

    //$smarty->hassign("product_name", $a[0]);
    $smarty->hassign("product_name", $product->name);

    $gridEntry = new Grid();

    $gridEntry->rows_num = 10;
    $gridEntry->show_rows_num_select = false;

    $gridEntry->registerHeader('', 'add_time', true, 'desc');

    $gridEntry->query_select_rows = 'SELECT * FROM ?#DISCUSSIONS_TABLE WHERE productID='.intval($productID);
    $gridEntry->query_total_rows_num = 'SELECT COUNT(*) FROM ?#DISCUSSIONS_TABLE WHERE productID='.xEscapeSQLstring($productID);
    $gridEntry->setRowHandler('
		$row["add_time_str"] = Time::standartTime($row["add_time"]);
		return $row;
	');

    $gridEntry->prepare();

    if (Message::isMessage($Message) && $Message->is_set()) {

        $smarty->assign('new_topic', $Message->topic_data);
    }

    $smarty->assign('conf_image', URL_ROOT.'/imgval.php?'.generateRndCode(4).'=1');

    //$product_info = GetProduct($productID);
    $product_info = $product->getVars();
    $q = db_query("SELECT categoryID, ".LanguagesManager::sql_prepareField('name')." AS name, ".LanguagesManager::sql_prepareField('description')." AS description, picture FROM ".CATEGORIES_TABLE." WHERE categoryID=".intval($product_info['categoryID'])) or die (db_error());
    $row = db_fetch_row($q);
    if ($row) {

        if (!file_exists(DIR_PRODUCTS_PICTURES."/".$row[3])) $row[3] = "";
        $smarty->assign("selected_category", $row);
    }
    $smarty->assign("product_category_path", catCalculatePathToCategory($product_info['categoryID']));
    $smarty->assign("main_content_template", "product_discussion.html");

?>
