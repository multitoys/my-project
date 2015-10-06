<?
    $name = strip_tags($_POST["name"]);
    $age = $_POST["age"] * 1;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Передача данных методом POST через сокет</title>
</head>

<body>
<h1>Передача данных методом POST через сокет</h1>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($name and $age) {
            echo "<h1>Привет, $name</h1>";
            echo "<h3>Тебе $age лет</h3>";
        } else {
            print "<h3>Нет данных для вывода!</h3>";
        }
    }
?>
<form action="dummy.php" method="POST">
    <label>Ваше имя: </label><input type="text" name="name"><br>
    <label>Ваш возраст: </label><input type="text" name="age"><br>
    <input type="text" name="submit">
</form>
</body>
</html>
