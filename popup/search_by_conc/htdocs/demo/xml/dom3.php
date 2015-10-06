<?php
    // Создание объекта DOM
    $dom = new DOMDocument();

    // Загрузка XML-документа в объект
    $dom->load("catalog.xml");

    // Вывод заголовкой всех книг
    $titles = $dom->getElementsByTagName("title");

    foreach ($titles as $title) {
        echo "<p>",
        iconv("UTF-8", "windows-1251", $title->textContent),
        "</p>";
    }
?>