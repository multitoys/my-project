<?php
    // Создание объекта DOM
    $dom = new DOMDocument();
    // Загрузка XML-документа в объект
    $dom->load("catalog.xml");
    //Получение корневого узла
    $root = $dom->documentElement;
    //Получение потомков корневого узла
    $children = $root->childNodes;
    //Получение текстовых узлов в цикле
    foreach ($children as $child) {
        echo "<p>",
        iconv("UTF-8", "windows-1251", $child->textContent),
        "</p>";
    }
?>