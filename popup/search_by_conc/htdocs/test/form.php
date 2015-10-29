<?php
    /*
    ������� 1
    - ���� ��� ������� �������� ��� ����������� ����� POST:
        - �������� ������ �� ������ �����
        - ��������� ��������� ������ � cookie
        - ��������� ���������� �������� ������� GET, ���������� �� ������ POST �������
    - ���� ��� ������� �������� ��� ����������� ����� GET:
        - ��������� ������ �� cookie(���� ��� ����)
        - ��������� � ������� 2
    */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
    <title>������� �����</title>
</head>

<body>
<h1>������� �����</h1>

<form action="form.php" method="post">
    ���� ���:
    <input type="text" name="name"><br>
    ��� �������:
    <input type="text" name="age"><br>
    <input type="submit" value="��������">
</form>
<?php
    /*
    ������� 2
    - �������� ���������� �� cookie ������
    */
?>
</body>
</html>
