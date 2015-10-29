<?php
    $author = "���� ������";
    $title = "������������� ���������������� �� PHP5";
    $pubyear = "2009";
    $price = "100";

    // �������� ������� DOM
    $dom = new DOMDocument();

    // �������� XML-��������� � ������
    $dom->load("catalog.xml");

    // ��������� ��������� ��������
    $root = $dom->documentElement;

    // �������� ����� XML-���������
    $bookDOM = $dom->createElement("book");

    $authorDOM = $dom->createElement("author");
    // ��������� ���������� ����
    $authortext = $dom->createTextNode(iconv("windows-1251", "UTF-8", $author));
    // ������������� �����
    $authorDOM->appendChild($authortext);
    $bookDOM->appendChild($authorDOM);

    $titleDOM = $dom->createElement("title");
    // ��������� ���������� ����
    $titletext = $dom->createTextNode(iconv("windows-1251", "UTF-8", $title));
    // ������������� �����
    $titleDOM->appendChild($titletext);
    $bookDOM->appendChild($titleDOM);

    $pubyearDOM = $dom->createElement("pubyear");
    // ��������� ���������� ����
    $pubyeartext = $dom->createTextNode(iconv("windows-1251", "UTF-8", $pubyear));
    // ������������� �����
    $pubyearDOM->appendChild($pubyeartext);
    $bookDOM->appendChild($pubyearDOM);

    $priceDOM = $dom->createElement("price");
    // ��������� ���������� ����
    $pricetext = $dom->createTextNode(iconv("windows-1251", "UTF-8", $price));
    // ������������� �����
    $priceDOM->appendChild($pricetext);
    $bookDOM->appendChild($priceDOM);

    // ������������ ����� � ��������
    $root->appendChild($bookDOM);

    // ���������� �����
    $dom->save("newcatalog.xml");
?>
<p>����� ���������!
	
	
	
	