<?php
    // ������� ������, ��������� � ���� ��������
    $sxml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/popup/search_by_conc/grandtoys_categories.html");

    //  ����� �������� 1-� �����
    echo $sxml->ul->li[0]->a;