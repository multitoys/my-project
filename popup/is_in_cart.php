<?php

  ini_set('display_errors', false);
  define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT']."/published/SC/html/scripts");
  $DebugMode = false;
  $Warnings = array();
  include_once(DIR_ROOT.'/includes/init.php');
  include_once(DIR_CFG.'/connect.inc.wa.php');
  include(DIR_FUNC.'/setting_functions.php' );
    
  $DB_tree = new DataBase();
  $DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
  $DB_tree->selectDB(SystemSettings::get('DB_NAME'));

  $customerLogin = isset($_SESSION['log'])?$_SESSION['log']:'';
  $id = isset($_GET['id'])?$_GET['id']:0;
  $found = 0;

  $current_currency = $_SESSION['current_currency'];
  if (isset($_SESSION['log']))
  {
    $login = $_SESSION['log'];
    $res = mysql_query("
    SELECT *, Price
      FROM SC_shopping_carts
      LEFT JOIN SC_customers ON SC_customers.customerID = SC_shopping_carts.customerID
      LEFT JOIN SC_shopping_cart_items ON SC_shopping_cart_items.itemID = SC_shopping_carts.itemID
      LEFT JOIN SC_products ON SC_products.productID = SC_shopping_cart_items.productID
      LEFT JOIN SC_product_pictures ON SC_products.productID = SC_product_pictures.productID
      WHERE Login = '$login' AND SC_products.productID = '$id'
      GROUP BY SC_shopping_carts.itemID;
      ");
    $found = mysql_num_rows($res);
  }

  echo $found;