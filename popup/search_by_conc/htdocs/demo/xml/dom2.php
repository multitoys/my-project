<?php
    // �������� ������� DOM
    $dom = new DOMDocument();
    // �������� XML-��������� � ������
    $dom->load("catalog.xml");
    //��������� ��������� ����
    $root = $dom->documentElement;
    //��������� �������� ��������� ����
    $children = $root->childNodes;
    //��������� ��������� ����� � �����
    foreach ($children as $child) {
        echo "<p>",
        iconv("UTF-8", "windows-1251", $child->textContent),
        "</p>";
    }
?>