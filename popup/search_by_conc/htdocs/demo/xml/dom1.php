<?php
    // Создание объекта DOM
    $dom = new DOMDocument();

    // Загрузка XML-документа в объект
    $dom->load($_SERVER['DOCUMENT_ROOT']."/popup/search_by_conc/grandtoys_categories.html");

    //Получение корневого узла
    $root = $dom->documentElement;

    //Тип узла
    echo $root->nodeType;

    //Получение потомков корневого узла
    $children = $root->childNodes;
    //var_dump($children);

    foreach ($children as $child) {
        echo $child->textContent,
        "<br>";
    }