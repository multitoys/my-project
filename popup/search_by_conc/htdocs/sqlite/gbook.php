<?php
    /* ЗАДАНИЕ 1
    - Подключите файл с описанием класса GbookDB
    - Создайте объект gbook, экземпляр класса GbookDB
    - Создайте переменную $errMsg со строковым значением "" (пустая строка)
    */

    /* ЗАДАНИЕ 3
    - Проверьте, была ли отправлена HTML-форма?
    - Если ДА, то подключите файл с кодом для обработки HTML-формы
    */

    /*
    ЗАДАНИЕ 5
    - Проверьте, был ли запрос методом GET на удаление записи
    - Если ДА, то подключите файл с кодом для удаления записи
    */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <title>Гостевая книга</title>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251"/>
</head>
<body>

<h1>Гостевая книга</h1>
<?php
    /* ЗАДАНИЕ 2
    - Проверьте, не является ли переменная $errMsg пустой строкой?
    - Если НЕТ, то выведите значение переменной $errMsg
    */
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

    Ваше имя:<br/>
    <input type="text" name="name"/><br/>
    Ваш e-mail:<br/>
    <input type="text" name="email"/><br/>
    Сообщение:<br/>
    <textarea name="msg" cols="50" rows="5"></textarea><br/>
    <br/>
    <input type="submit" value="Добавить!"/>

</form>

<?php
    /*
    ЗАДАНИЕ 4
    - Подключите файл с кодом для обработки полученных записей Гостевой книги
    */
?>

</body>
</html>