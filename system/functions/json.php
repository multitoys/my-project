<?php

    function json_encode($var)
    {
        $json = new Services_JSON();

        return $json->encode($var);
    }

    function json_decode($var)
    {
        $json = new Services_JSON();

        return $json->decode($var);
    }

?>
