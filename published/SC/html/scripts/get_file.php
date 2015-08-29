<?php
	/* @var $CurrDivision Division */
	$DebugMode = false;
	$Warnings = array();
	// -------------------------INITIALIZATION-----------------------------//
	define('DIR_ROOT', str_replace("\\","/",realpath(dirname(__FILE__))));
	include(DIR_ROOT.'/includes/init.php');
	include_once DIR_CFG.'/connect.inc.wa.php';

	include_once(DIR_FUNC.'/db_functions.php' );
	include_once(DIR_FUNC.'/setting_functions.php' );

	if(isset($_GET['debug'])&&$_GET['debug']=='total_time'){
		
		$T = new Timer();
		$T->timerStart();
	}
	
	$DB_tree = new DataBase();
	db_connect(SystemSettings::get('DB_HOST'),SystemSettings::get('DB_USER'),SystemSettings::get('DB_PASS')) or die (db_error());
	db_select_db(SystemSettings::get('DB_NAME')) or die (db_error());
	$DB_tree->connect(SystemSettings::get('DB_HOST'), SystemSettings::get('DB_USER'), SystemSettings::get('DB_PASS'));
	$DB_tree->query("SET character_set_client='".MYSQL_CHARSET."'");
	$DB_tree->query("SET character_set_connection='".MYSQL_CHARSET."'");
	$DB_tree->query("SET character_set_results='".MYSQL_CHARSET."'");

	$DB_tree->selectDB(SystemSettings::get('DB_NAME'));
	define('VAR_DBHANDLER','DBHandler');
	
	$Register = &Register::getInstance();
	$Register->set(VAR_DBHANDLER, $DB_tree);
	
	settingDefineConstants();

	require_once(DIR_CFG.'/language_list.php');
	require_once(DIR_FUNC.'/category_functions.php');
	require_once(DIR_FUNC.'/product_functions.php');
	require_once(DIR_FUNC.'/statistic_functions.php');
	require_once(DIR_FUNC.'/country_functions.php' );
	require_once(DIR_FUNC.'/zone_functions.php' );
	require_once(DIR_FUNC.'/datetime_functions.php' );
	require_once(DIR_FUNC.'/picture_functions.php' ); 
	require_once(DIR_FUNC.'/configurator_functions.php' );
	require_once(DIR_FUNC.'/option_functions.php' );
	require_once(DIR_FUNC.'/discount_functions.php' ); 
	require_once(DIR_FUNC.'/custgroup_functions.php' ); 
	require_once(DIR_FUNC.'/currency_functions.php' );
	require_once(DIR_FUNC.'/module_function.php' );
	require_once(DIR_FUNC.'/registration_functions.php' );
	
	require_once(DIR_FUNC.'/order_amount_functions.php' );
	require_once(DIR_FUNC.'/catalog_import_functions.php');
	require_once(DIR_FUNC.'/cart_functions.php');
	require_once(DIR_FUNC.'/subscribers_functions.php' );
	require_once(DIR_FUNC.'/discussion_functions.php' );
	require_once(DIR_FUNC.'/order_status_functions.php' );
	require_once(DIR_FUNC.'/order_functions.php' );
	require_once(DIR_FUNC.'/shipping_functions.php' );
	require_once(DIR_FUNC.'/payment_functions.php' );
	require_once(DIR_FUNC.'/reg_fields_functions.php' );
	require_once(DIR_FUNC.'/tax_function.php' );
	require_once(DIR_CLASSES.'/class.virtual.shippingratecalculator.php');
//	require_once(DIR_CLASSES.'/class.virtual.paymentmodule.php');
//	require_once(DIR_ROOT.'/smarty/smarty.class.php');
//	require_once(DIR_CLASSES.'/class.view.php');
//	require_once(DIR_ROOT.'/smarty/resources/resource.rfile.php');
//	require_once(DIR_ROOT.'/smarty/resources/resource.register.php');

	require_once(DIR_FUNC.'/search_function.php' );
	$LanguageEntry = &LanguagesManager::getCurrentLanguage();
	$locals = $LanguageEntry->getLocals(array(LOCALTYPE_FRONTEND, LOCALTYPE_GENERAL, LOCALTYPE_HIDDEN), false, false);
	$Register->set('CURRLANG_LOCALS', $locals);
	$Register->set('CURR_LANGUAGE', $LanguageEntry);
/*

	$smarty = new View; //core smarty object
	$smarty_mail = new View; //for e-mails
	
	if(!file_exists(DIR_COMPILEDTEMPLATES)){
		mkdir(DIR_COMPILEDTEMPLATES);
	}
	
	$smarty->compile_dir = DIR_COMPILEDTEMPLATES;
	$smarty_mail->compile_dir = DIR_COMPILEDTEMPLATES;
	$smarty->debugging_ctrl = 'URL';
	if (CONF_SMARTY_FORCE_COMPILE){ //this forces Smarty to recompile templates each time someone runs index.php
		$smarty->force_compile = true;
		$smarty_mail->force_compile = true;
	}
	$smarty->register_resource('rfile', array('smarty_resource_rfile_source', 'smarty_resource_rfile_timestamp', 'smarty_resource_rfile_secure', 'smarty_resource_rfile_trusted'));
	$smarty->register_resource('register', array('smarty_resource_register_source', 'smarty_resource_register_timestamp', 'smarty_resource_register_secure', 'smarty_resource_register_trusted'));
	
	define('VAR_SMARTY','Smarty');
	$Register = &Register::getInstance();
	$Register->set(VAR_SMARTY,$smarty);
	*/
	//select a new language?
	if (isset($_POST['lang'])){
		LanguagesManager::setCurrentLanguage($_POST['lang']);
		RedirectSQ();
	}
	if (isset($_GET['lang'])){
		LanguagesManager::setCurrentLanguage($_GET['lang']);
		RedirectSQ('lang=');
	}
	
	checkLogin();
	
//	$smarty->assign('lang_list', $lang_list);
//	if (isset($_SESSION['current_language'])) $smarty->assign('current_language', $_SESSION['current_language']);


	//get order data
	if ( isset($_GET["getFileParam"]) )
	{
			$getFileParam = Crypt::FileParamDeCrypt( $_GET["getFileParam"], null );//echo $getFileParam;
			$params = explode( "&", $getFileParam );
			foreach( $params as $param )
			{
				$param_value = explode( "=", $param );

				if ( count($param_value) >= 2 )
				{
					if ( $param_value[0] == "orderID" )
						$orderID = (int)$param_value[1];
					else if ( $param_value[0] == "productID" )
						$productID = (int)$param_value[1];
					else if ( $param_value[0] == "customerID" )
						$customerID = (int)$param_value[1];
					else if ( $param_value[0] == "order_time" )
						$order_time = base64_decode($param_value[1]);
				}
			}

	}
	
	{
		$fileToDownLoad = "";
		$fileToDownLoadShortName = "";
		$res = 0;
		$direct_mode = false;
		$charset = false;

		if ( !isset($_GET["getFileParam"]) )
			die( translate("err_forbidden") );
		else
		{
			$getFileParam = Crypt::FileParamDeCrypt( $_GET["getFileParam"], null );

	 		if ( $getFileParam == "GetDataBaseSqlScript" )
			{
//				if ( CONF_BACKEND_SAFEMODE != 1 && (strcmp($_SESSION["log"],ADMIN_LOGIN) != 0 ))
//					die( translate("err_forbidden") );
//				else
				{
					$fileToDownLoad = DIR_TEMP."/database.sql";
					$fileToDownLoadShortName = "database.sql";
				}
			}
			else if ( $getFileParam == "GetCustomerExcelSqlScript" )
			{
//				if ( CONF_BACKEND_SAFEMODE != 1 && (strcmp($_SESSION["log"], ADMIN_LOGIN) != 0 ))
//					die( translate("err_forbidden") );
//				else
				{
					$fileToDownLoad = DIR_TEMP."/customers.csv";
					//$fileToDownLoadShortName = "customers.csv";
					$fileToDownLoadShortName = (CONF_SHOP_NAME?preg_replace('/[^a-z_0-9]/ui', '_', strtolower(translit(CONF_SHOP_NAME))).'_':'').'customers.csv';
				}
			}
			else if ( $getFileParam == "GetOrdersExcelSqlScript" )
			{
//				if ( CONF_BACKEND_SAFEMODE != 1 && (strcmp($_SESSION["log"], ADMIN_LOGIN) != 0 ))
//					die( translate("err_forbidden") );
//				else
				{
					$fileToDownLoad = DIR_TEMP."/orders.csv";
//					$fileToDownLoadShortName = "orders.csv";
					$fileToDownLoadShortName = (CONF_SHOP_NAME?preg_replace('/[^a-z_0-9]/ui', '_', strtolower(translit(CONF_SHOP_NAME))).'_':'').'orders.csv';
				}
			}
			else if ( $getFileParam == "GetFroogleFeed" )
			{
//				if ( CONF_BACKEND_SAFEMODE != 1 && (strcmp($_SESSION["log"], ADMIN_LOGIN) != 0 ))
//					die( translate("err_forbidden") );
//				else
				{
					$fileToDownLoad = DIR_TEMP."/froogle.txt";
					$fileToDownLoadShortName = "froogle.txt";
				}
			}
			else if ($getFileParam == "GetYandex")
			{
				$direct_mode = isset($_GET['download'])?false:true;
				$charset = 'windows-1251';
				$fileToDownLoad = DIR_TEMP."/yandex.xml";
				$fileToDownLoadShortName="yandex.xml";
			}
			else if ( preg_match('/GetCSVCatalog=(.+)$/u', $getFileParam, $sp) )
			{
//				if ( CONF_BACKEND_SAFEMODE != 1 && (strcmp($_SESSION["log"], ADMIN_LOGIN) != 0 ))
//					die( translate("err_forbidden") );
//				else
				{
					$file = base64_decode($sp[1]);
					$fileToDownLoad = DIR_TEMP."/{$file}";
					$fileToDownLoadShortName = $file;
				}
			}
			elseif( $getFileParam == "GetSubscriptionsList" ){
//				if ( CONF_BACKEND_SAFEMODE != 1 && (strcmp($_SESSION["log"], ADMIN_LOGIN) != 0 ))
//					die( translate("err_forbidden") );
//				else
				{
					$fileToDownLoad = DIR_TEMP."/subscribers.txt";
					$fileToDownLoadShortName = "subscribers.txt";
				}
			}
			else{
				
				$params = explode( "&", $getFileParam );
				foreach( $params as $param )
				{
					$param_value = explode( "=", $param );

					if ( count($param_value) >= 2 )
					{

						if ( $param_value[0] == "orderID" )
							$orderID = (int)$param_value[1];
						else if ( $param_value[0] == "productID" )
							$productID = (int)$param_value[1];
						else if ( $param_value[0] == "customerID" )
							$customerID = (int)$param_value[1];
						else if ( $param_value[0] == "order_time" )
							{
								for ($k = 2; $k<count($param_value); $k++)
								{
									$param_value[1] .= $param_value[$k]."=";

								}
								$order_time = base64_decode($param_value[1]);
							}

					}

				}

				if (isset($orderID) && isset($productID) && isset($customerID))
				{
					$res = ordAccessToLoadFile( $orderID, $productID,$customerID, $pathToProductFile, $fileToDownLoadShortName );
				}else{
					$res = 4;
				}

				if ($customerID == -1 && isset($order_time)) //verify order time
				{
					$q = db_query("select order_time from ".ORDERS_TABLE." where orderID=$orderID");
					$row = db_fetch_row($q);
					if (!$row || strcmp($row[0],$order_time))
					{
						$res = 4;
					}
				}elseif($customerID == -1){
					$res = 4;
				}
				

				if ( $res == 0 )
					$fileToDownLoad = $pathToProductFile;
					
			}
		}
		if ( $res == 1 )
			echo( "<font color=red><b>".translate("prd_download_number_of_downloads_exceeded")."</b></font>" );
		else if ( $res == 2 )
			echo( "<font color=red><b>".translate("prd_download_period_expired")."</b></font>" );
		else if ( $res == 3 )
			echo( "<font color=red><b>".translate("err_access_to_product_downloadable_file_denied")."</b></font>" );
		else if ( $res == 4 )
			echo( "<font color=red><b>$res".translate("err_forbidden")."</b></font>" );
		else if( $res == 0 && strlen($fileToDownLoad)>0 && file_exists($fileToDownLoad) &&$direct_mode){
			$matches = '';
			if(preg_match('/\.([^\.]+)$/',$fileToDownLoad,$matches)){
				$content_type = null;
				switch($matches[1]){
					case 'xml':$content_type = 'text/xml;';break;
					case 'txt':$content_type = 'text/plain;';break;
					case 'log':$content_type = 'text/plain;';break;
				}
				header('Content-type: '.$content_type.($charset?(" charset={$charset};"):''));
				readfile($fileToDownLoad);
			}else{
				header('Content-type: application/force-download');
				header('Content-Transfer-Encoding: Binary');
				header('Content-length: '.filesize($fileToDownLoad));
				header('Content-disposition: attachment; filename='.(isset($fileToDownLoadShortName)&&strlen($fileToDownLoadShortName)?$fileToDownLoadShortName:basename($fileToDownLoad)) );
				readfile($fileToDownLoad);
			}
			
		}
		else if ( $res == 0 && strlen($fileToDownLoad)>0 && file_exists($fileToDownLoad) )
		{
			header('Content-type: application/force-download');
			header('Content-Transfer-Encoding: Binary');
			header('Content-length: '.filesize($fileToDownLoad));
			header('Content-disposition: attachment; filename='.(isset($fileToDownLoadShortName)&&strlen($fileToDownLoadShortName)?$fileToDownLoadShortName:basename($fileToDownLoad)) );
			readfile($fileToDownLoad);
		}
		else
		{
			//error_404 err_cant_read_file
			header("HTTP/1.0 404 Not Found");
			echo ("<font color=red><b>".translate("err_cant_read_file")."</b></font>" );
		}
		if($res>4 || $res<0)
			echo( "<font color=red><b>".translate("err_forbidden")."</b></font>" );
			
	}
	

 ?>