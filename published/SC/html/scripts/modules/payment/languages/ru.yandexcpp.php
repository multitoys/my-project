<?php
	define('YANDEXCPP_TTL', 'Яндекс.Деньги (ЦПП)');
	define('YANDEXCPP_DSCR', 'Интеграция с <a href="http://money.yandex.ru" target="_top">Яндекс.Деньгами</a> по методу «<a href="http://money.yandex.ru/doc.xml?id=459801" target="_top">Центр Приема Платежей</a>».<br />Настройки подключения к системе вы можете получить у Яндекса.');
	
	define('YANDEXCPP_CFG_SHOPID_TTL', 'Shop ID');
	define('YANDEXCPP_CFG_SHOPID_DSCR', 'Идентификатор магазина в ЦПП - уникальное значение, присваивается Магазину платежной ');
	
	define('YANDEXCPP_CFG_BANKID_TTL', 'Bank ID');
	define('YANDEXCPP_CFG_BANKID_DSCR', 'Идентификатор процессингового центра платежной системы');
	
	define('YANDEXCPP_CFG_TARGETBANKID_TTL', 'Target bank ID');
	define('YANDEXCPP_CFG_TARGETBANKID_DSCR', 'Идентификатор процессингового центра платежной системы');
	
	define('YANDEXCPP_CFG_MODE_TTL', 'Режим работы модуля');
	define('YANDEXCPP_CFG_MODE_DSCR', 'Определяет адрес (URL), куда будут отправлены данные о платеже');
	
	define('YANDEXCPP_TXT_TESTMODE', 'Тестовый');
	define('YANDEXCPP_TXT_LIVEMODE', 'Рабочий');
	
	define('YANDEXCPP_CFG_TARGETCURRENCY_TTL', 'Валюта платежей');
	define('YANDEXCPP_CFG_TARGETCURRENCY_DSCR', 'Выберите Рубли для рабочего режима и Деморубли для тестового');

	define('YANDEXCPP_CFG_TRANSCURRENCY_TTL', 'Валюта платежей в Вашем магазине');
	define('YANDEXCPP_CFG_TRANSCURRENCY_DSCR', 'Выберите из списка валют Вашего интернет-магазина валюту, которая соответствует Рублям или Деморублям (валюте системы Яндекс.Деньги). Необходимо для перерасчета стоимости заказа.');

	define('YANDEXCPP_TXT_RUR', 'Рубли');
	define('YANDEXCPP_TXT_DEMORUR', 'Деморубли');
	
	define('YANDEXCPP_TXT_PROCESS', 'Оплатить через Яндекс.Деньги сейчас!');
	
	define('YANDEXCPP_CFG_SHOPPASSWORD_TTL', 'Секретный пароль');
	define('YANDEXCPP_CFG_SHOPPASSWORD_DSCR', 'используется при расчете криптографического хэша.');
?>