<?php
    $local_settings = &$Args[0]['local_settings'];

    if (!$local_settings['list_id']) {
        return;
    }

    $productList = new ProductList();
    $res = $productList->loadByID($local_settings['list_id']);
    if (!$res) {
        return;
    }

    $products = $productList->getProducts(true, $local_settings['limit']);

    $Register = &Register::getInstance();
    $smarty = &$Register->get(VAR_SMARTY);
    /* @var $smarty Smarty */

    $smarty->assign('__products', $products);
    $smarty->assign('__block_height', (int)$local_settings['block_height']);
    $smarty->assign('__limit', (int)$local_settings['limit']);
    $smarty->display('product_list.html');
