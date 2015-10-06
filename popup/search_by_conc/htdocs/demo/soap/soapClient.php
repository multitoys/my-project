<?php
    // Создание SOAP-клиента
    $client = new SoapClient("http://localhost/primer/soap/CurrencyExchangeService.wsdl");

    // Посылка SOAP-запроса c получением результат
    $result = $client->getRate("us", "russia");
    echo "Текущий курс: ", $result;
?>