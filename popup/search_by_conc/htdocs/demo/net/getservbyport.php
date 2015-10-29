<?php
    $tcp80 = getservbyport(80, "tcp");
    $tcp23 = getservbyport(23, "tcp");
    echo "�� ����� 80 ����� ������: $tcp80, � �� ����� 23: $tcp23";
?>