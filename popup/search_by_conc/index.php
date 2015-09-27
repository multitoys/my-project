<?php
    clearstatcache();
    echo "Последнее изменение: ".date("F d Y H:i:s.", getlastmod());
    echo "UID владельца скрипта: ".getmyuid();
    $stat = stat($_SERVER['DOCUMENT_ROOT'].'/popup/search_by_conc/search_conc.php');

    foreach ($stat as $key => $stats) {
        if ($key === 'atime' || $key === 'mtime' || $key === 'ctime') {
            $stat2[$key] = date('F d Y H:i:s.', $stats);
        }
    }

    var_dump($stat2);
    var_dump(array_slice($stat, 13));

    function odd($var)
    {
        // является ли переданное число нечетным
        return ($var & 1);
    }

    function even($var)
    {
        // является ли переданное число четным
        return (!($var & 1));
    }

    $array1 = array("a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5);
    $array2 = array(6, 7, 8, 9, 10, 11, 12);

    echo '<br>Нечетные:';
    print_r(array_filter($array1, 'odd'));
    echo '<br>Четные:';
    print_r(array_filter($array2, 'even'));

    $fruit = array('a' => 'apple', 'b' => 'banana', 'c' => 'cranberry');

    reset($fruit);
    while (list($key, $val) = each($fruit)) {
        echo "<br>$key => $val";
    }

