<?php
    // Создать объект, загрузить в него документ
    $sxml = simplexml_load_file("catalog.xml");

    //  Изменение года издания второй книги
    echo $sxml->book[1]->pubyear = "2005";

    // Сохранение содержимого
    file_put_contents("catalog.xml", $sxml->asXML());
?>