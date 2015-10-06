<?php
    $tcp80 = getservbyport(80, "tcp");
    $tcp23 = getservbyport(23, "tcp");
    echo "На порту 80 живет сервис: $tcp80, а на порту 23: $tcp23";
?>