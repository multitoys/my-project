<?php
    /**
     * Created by PhpStorm.
     * User: Gololobov
     * Date: 10.11.2015
     * Time: 13:35
     */

    define('DIR_ROOT', $_SERVER['DOCUMENT_ROOT'].'/published/SC/html/scripts');
    include_once(DIR_ROOT.'/includes/init.php');
    include_once(DIR_CFG.'/connect.inc.wa.php');
    include(DIR_FUNC.'/setting_functions.php');

    $DB_tree = new DataBase();
    $DB_tree->connect(
        SystemSettings::get('DB_HOST'),
        SystemSettings::get('DB_USER'),
        SystemSettings::get('DB_PASS')
    );
    $DB_tree->selectDB(SystemSettings::get('DB_NAME'));

    $query = 'SELECT 
                      p.code_1c, p.product_code, p.default_picture, p.name_ru, pp.filename
              FROM 
                      SC_products p
              LEFT JOIN 
                      SC_product_pictures pp
              ON 
                      pp.photoID = p.default_picture
              WHERE 
                      p.enabled=1 AND p.in_stock != 0
              ORDER BY 
                      p.code_1c ASC';
    $res = mysql_query($query) or die(mysql_error()."<br>$query");

    $erorr = 0;

    $Report = new Excel('nofoto'.$date = date('d-m-Y', time()));

    $Report->label('Код 1С');
    $Report->right();
    $Report->label('Артикул');
    $Report->right();
    $Report->label('Наименование');
    $Report->right();
    $Report->label('№');

    while ($row = mysql_fetch_object($res)) {

        $erorr_type = '';

        if (!$row->default_picture) {
            $erorr_type = 'Фото не назначено';
        } elseif ($row->filename !== $row->code_1c.'.jpg') {
            $erorr_type = 'Фото не соответствует';
        } elseif (!is_file(DIR_PRODUCTS_PICTURES.'/'.$row->filename)) {
            $erorr_type = 'Отсутствует файл фотографии';
        }

        if ($erorr_type) {

            $erorr++;
            $Report->home();
            $Report->down();

            $Report->label($row->code_1c);
            $Report->right();
            $Report->label($row->product_code);
            $Report->right();
            $Report->label($row->name_ru);
            $Report->right();
            $Report->number($erorr);
        }
    }
    mysql_free_result($res);

    $Report->headers();
    $Report->send();