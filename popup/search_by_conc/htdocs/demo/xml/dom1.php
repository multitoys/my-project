<?php
    // �������� ������� DOM
    $dom = new DOMDocument();

    // �������� XML-��������� � ������
    $dom->load($_SERVER['DOCUMENT_ROOT']."/popup/search_by_conc/grandtoys_categories.html");

    //��������� ��������� ����
    $root = $dom->documentElement;

    //��� ����
    echo $root->nodeType;

    //��������� �������� ��������� ����
    $children = $root->childNodes;
    //var_dump($children);

    foreach ($children as $child) {
        echo $child->textContent,
        "<br>";
    }