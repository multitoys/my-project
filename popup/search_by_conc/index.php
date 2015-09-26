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

