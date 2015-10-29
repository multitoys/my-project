<?php
    $string = <<<XML
<html>
	<head>
		<title> ������ XML-��������� </title>
	</head>
	<body background="silver">
		<h1 align="center">����� ����������</h1>
		<p>��� ������ �����</p>
		<p>
			� ��� ������ �����, � ������� ������������
			<br />�������������� ������� ������
		</p>
		<p>
			� ������� ������ ������������ ������ �� <a href="http://specialist.ru">www.specialist.ru</a> &mdash; 
			����� ������������� ��������.
		</p>
	</body>
</html>
XML;

    $sxml = simplexml_load_string($string);

?> 