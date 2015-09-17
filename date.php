<?php
    function add_days($my_date, $numdays)
    {
        $date_t = strtotime($my_date.' UTC');

        return gmdate('Y-m-d', $date_t + ($numdays * 86400));
    }

    function oneDay($days)
    {
        return $days = ($days - 1) * 60 * 60 * 24;
    }

    //    echo oneDay(2);
    echo mktime();
    $days = 3;
    $date = time() - (($days - 1) * 24 * 60 * 60);
    $date_postup = date('d-m-Y', $date);
