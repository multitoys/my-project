<?php
    // �������� SOAP-�������
    $client = new SoapClient("http://localhost/primer/soap/CurrencyExchangeService.wsdl");

    // ������� SOAP-������� c ���������� ���������
    $result = $client->getRate("us", "russia");
    echo "������� ����: ", $result;
?>