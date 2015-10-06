<?php
    $string = <<<XML
<html>
	<head>
		<title> Пример XML-документа </title>
	</head>
	<body background="silver">
		<h1 align="center">Добро пожаловать</h1>
		<p>Это первый абзац</p>
		<p>
			А это второй абзац, в котором используется
			<br />принудительный перевод строки
		</p>
		<p>
			В третьем абзаце присутствует ссылка на <a href="http://specialist.ru">www.specialist.ru</a> &mdash; 
			Центр Компьютерного Обучения.
		</p>
	</body>
</html>
XML;

    $sxml = simplexml_load_string($string);

?> 