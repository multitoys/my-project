<?php
    function getRate($country1, $country2)
    {
        //����������� ���� ����� � ������
        $rur2grn = 0.17;
        //����������� ���� ������ � �����
        $grn2rur = 5.6;
        if ($country1 == "russia") {//&& country2
            return $rur2grn;
        } elseif ($country1 == "ukraina") {//&& country2
            return $grn2rur;
        } else {
            return 0.0;//throw new SoapFault("Server", "Unknown rate: $country1 and $country2.")
        }
    }

//��������� �����������
    ini_set("soap.wsdl_cache_enabled", "0");
    $server = new SoapServer("http://localhost/primer/soap/CurrencyExchangeService.wsdl");
    $server->addFunction("getRate");
// ������ �������
    $server->handle();

?>