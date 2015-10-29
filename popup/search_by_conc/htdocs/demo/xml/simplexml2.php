<?php
    // ������� ������, ��������� � ���� ��������
    $sxml = simplexml_load_file("catalog.xml");

    //  ��������� ���� ������� ������ �����
    echo $sxml->book[1]->pubyear = "2005";

    // ���������� �����������
    file_put_contents("catalog.xml", $sxml->asXML());
?>