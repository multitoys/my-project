<?php
/**
 * http://plutov.by/post/html5_history_api
 */
$content = 'Hello. It\'s History API';
if (isset($_GET['ajax'])) {
    echo $content;
} else {
    include_once 'layout.php';
}