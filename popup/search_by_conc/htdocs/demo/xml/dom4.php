<?php
    $author = "Вася Пупкин";
    $title = "Экстремальное программирование на PHP5";
    $pubyear = "2009";
    $price = "100";

    // Создание объекта DOM
    $dom = new DOMDocument();

    // Загрузка XML-документа в объект
    $dom->load("catalog.xml");

    // Получение корневого элемента
    $root = $dom->documentElement;

    // Создание новых XML-элементов
    $bookDOM = $dom->createElement("book");

    $authorDOM = $dom->createElement("author");
    // Текстовое содержимое узла
    $authortext = $dom->createTextNode(iconv("windows-1251", "UTF-8", $author));
    // Присоединение узлов
    $authorDOM->appendChild($authortext);
    $bookDOM->appendChild($authorDOM);

    $titleDOM = $dom->createElement("title");
    // Текстовое содержимое узла
    $titletext = $dom->createTextNode(iconv("windows-1251", "UTF-8", $title));
    // Присоединение узлов
    $titleDOM->appendChild($titletext);
    $bookDOM->appendChild($titleDOM);

    $pubyearDOM = $dom->createElement("pubyear");
    // Текстовое содержимое узла
    $pubyeartext = $dom->createTextNode(iconv("windows-1251", "UTF-8", $pubyear));
    // Присоединение узлов
    $pubyearDOM->appendChild($pubyeartext);
    $bookDOM->appendChild($pubyearDOM);

    $priceDOM = $dom->createElement("price");
    // Текстовое содержимое узла
    $pricetext = $dom->createTextNode(iconv("windows-1251", "UTF-8", $price));
    // Присоединение узлов
    $priceDOM->appendChild($pricetext);
    $bookDOM->appendChild($priceDOM);

    // Присоединени книги к каталогу
    $root->appendChild($bookDOM);

    // Сохранение файла
    $dom->save("newcatalog.xml");
?>
<p>Книга добавлена!
	
	
	
	