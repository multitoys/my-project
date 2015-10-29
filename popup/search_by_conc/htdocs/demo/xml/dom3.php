<?php
    // �������� ������� DOM
    $dom = new DOMDocument();

    // �������� XML-��������� � ������
    $dom->load("catalog.xml");

    // ����� ���������� ���� ����
    $titles = $dom->getElementsByTagName("title");

    foreach ($titles as $title) {
        echo "<p>",
        iconv("UTF-8", "windows-1251", $title->textContent),
        "</p>";
    }
?>