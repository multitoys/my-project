<?php
    /*
    ЗАДАНИЕ 1
    - Если при запросе страницы был использован метод POST:
        - создайте массив из данных формы
        - сохраните созданный массив в cookie
        - выполните перезапрос страницы методом GET, избавляясь от буфера POST запроса
    - Если при запросе страницы был использован метод GET:
        - зачитайте данные из cookie(если они есть)
        - перейдите к ЗАДАНИЮ 2
    */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
    <title>Простая форма</title>
</head>

<body>
<h1>Простая форма</h1>

<form action="form.php" method="post">
    Ваше имя:
    <input type="text" name="name"><br>
    Ваш возраст:
    <input type="text" name="age"><br>
    <input type="submit" value="Передать">
</form>
<?php
    /*
    ЗАДАНИЕ 2
    - Выведите полученные из cookie данные
    */
?>
</body>
</html>
