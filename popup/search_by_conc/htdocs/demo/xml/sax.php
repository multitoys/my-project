<?php
    // �������� �������
    $xml = xml_parser_create("UTF-8"); // Windows-1251 �� ��������������

    // ������� ���������� ��������� �����
    function start_tag_handler($xml, $tag, $attributes)
    {
        if ($tag != "CATALOG" and $tag != "BOOK") {
            echo "<p><b>", $tag, "</b>: ";
        }
    }

    // ������� ���������� ����������� �����
    function end_tag_handler($xml, $tag)
    {
        if ($tag != "CATALOG") {
            if ($tag != "BOOK") {
                echo "</p>\n";
            } else {
                echo "<hr>";
            }
        }
    }

    // ������� ���������� ���������� �����������
    function character_handler($xml, $data)
    {
        echo iconv("UTF-8", "windows-1251", $data);
    }

    // ���������� ������������ ��������� � �������� �����
    xml_set_element_handler($xml, "start_tag_handler", "end_tag_handler");

    //  ���������� ����������� ���������� �����������
    xml_set_character_data_handler($xml, "character_handler");
?>
<html>
<head>
    <title>�������</title>
</head>
<body>
<?php
    // ������ ��������
    xml_parse($xml, file_get_contents("catalog.xml"));
?>
</body>
</html>