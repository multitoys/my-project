<?
    $name = strip_tags($_POST["name"]);
    $age = $_POST["age"] * 1;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>�������� ������ ������� POST ����� �����</title>
</head>

<body>
<h1>�������� ������ ������� POST ����� �����</h1>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($name and $age) {
            echo "<h1>������, $name</h1>";
            echo "<h3>���� $age ���</h3>";
        } else {
            print "<h3>��� ������ ��� ������!</h3>";
        }
    }
?>
<form action="dummy.php" method="POST">
    <label>���� ���: </label><input type="text" name="name"><br>
    <label>��� �������: </label><input type="text" name="age"><br>
    <input type="text" name="submit">
</form>
</body>
</html>
