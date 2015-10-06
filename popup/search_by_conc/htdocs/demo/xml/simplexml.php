<?php
    // Создать объект, загрузить в него документ
    $sxml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/popup/search_by_conc/grandtoys_categories.html");

    //  Вывод названия 1-й книги
    echo $sxml->ul->li[0]->a;