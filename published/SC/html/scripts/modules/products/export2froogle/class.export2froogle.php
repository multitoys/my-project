<?php
class Export2Froogle extends Module {
	
	function initInterfaces(){
		
		$this->Interfaces = array(
			'export_page' => array(
				'name' => 'Export product list to Froogle(www.froogle.com)',
				'method' => 'methodExport2Froogle',
				),
		);
	}
	function methodExport2Froogle(){
		
		global $smarty;
		
		require_once(DIR_FUNC.'/export_products_function.php');
		
			function _exportToFroogle( $f, $rate )
			{
				_exportHeader( $f );
				_exportProducts( $f, $rate );
			}

			function _deleteInvalid_Elements( $str )
			{
				$str = str_replace( "\t"," ", $str );
				$str = str_replace( "\r"," ", $str );
				$str = str_replace( "\n"," ", $str );
				return $str;
			}

			function _exportHeader( $f )
			{
				//fputs( $f, "product_url\tname\tdescription\timage_url\tcategory\tprice\toffer_id\tinstock\tshipping\tcurrency\n" );
			fputs( $f, "link\ttitle\tdescription\timage_link\tproduct_type\tprice\tid\tquantity\tshipping\tcurrency\tcondition\n" );
			}

			function _exportProducts( $f, $rate )
			{

				//which description should be exported?
				if ($_POST["froogle_export_description"] == 1)
				{
					$dsc = "description";
				}
				else if ($_POST["froogle_export_description"] == 2)
				{
					$dsc = "brief_description";
				}
				else
				{
					$dsc = "meta_description";
				}

				//export all active products

				function __exportProduct( $ProductID, $params){
					
					$f 		= $params['f'];
					$rate 	= $params['rate'];
					$dsc 	= $params['dsc'];
					
					$store_url = correct_URL(CONF_FULL_SHOP_URL);
					$q = db_query("select productID, ".LanguagesManager::sql_prepareField('name')." AS name, Price, categoryID, default_picture, in_stock, ".LanguagesManager::sql_prepareField($dsc)." as ".$dsc.", shipping_freight, slug from ".PRODUCTS_TABLE." where productID='{$ProductID}'");
					$product = db_fetch_row($q);
					//format data
					$rate = (float)$rate;
					if ($rate <= 0) $rate = 1;
					$product["name"] = _deleteInvalid_Elements( $product["name"] );
					$product[$dsc] = _deleteInvalid_Elements( $product[$dsc] );
					$product["Price"] = RoundFloatValue( $product["Price"] * $rate );
					$product["shipping_freight"] = RoundFloatValue( $product["shipping_freight"] * $rate );
					$instock = (!CONF_CHECKSTOCK)?'1000':(($product["in_stock"] > 0 ) ? $product["in_stock"] : 0);

					//create categories string
					$category = "";
					$cpath = catCalculatePathToCategory($product["categoryID"]);
					if ($cpath)
					{
						for ($i=1;$i<count($cpath)-1;$i++) $category .= $cpath[$i]["name"]." > ";
						if (count($cpath) > 1)
							$category .= $cpath[ count($cpath)-1 ]["name"];
					}

					//export product picture
					if ($product["default_picture"] != NULL)
					{
						$pic_clause = " and photoID=".((int)$product["default_picture"]);
					}
					else
						$pic_clause = "";

					$q1 = db_query("select filename, thumbnail from ".PRODUCT_PICTURES." where productID=".$product["productID"] . $pic_clause);
					$pic_row = db_fetch_row($q1);
					$pic = "";
					$picture_url = (SystemSettings::is_hosted())?$store_url.'products_pictures/':BASE_URL.URL_PRODUCTS_PICTURES.'/';
					if ($pic_row) //export picture as well
					{
						if ( strlen($pic_row["filename"]) && file_exists(DIR_PRODUCTS_PICTURES."/".$pic_row["filename"]) )
							$pic = $picture_url._deleteInvalid_Elements($pic_row["filename"]);
						else
							if ( strlen($pic_row["thumbnail"]) && file_exists(DIR_PRODUCTS_PICTURES."/".$pic_row["thumbnail"]) )
								$pic = $picture_url._deleteInvalid_Elements($pic_row["thumbnail"]);
					}


					fputs( $f, set_query('ukey=product&furl_enable=1&product_slug='.$product['slug'].'&productID='.$product['productID'],$store_url)."\t"
								.$product["name"]."\t"
								.str_replace('&nbsp;',' ',strip_tags($product[$dsc]))."\t"
								.$pic."\t"
								.$category."\t"
								.$product["Price"]."\t"
								.$product["productID"]."\t"
								.$instock."\t"
								.$product["shipping_freight"]."\t"
								."usd"."\t"
								."new\n" );
				}

				$exportCategories = array(array(),array());
				
				
				$_spArray = array('f'=>$f, 'rate'=>$rate, 'dsc'=>$dsc, 'exprtUNIC'=>array('mode'=>'simple'));
				export_exportSubcategories(0, $exportCategories, $_spArray);
			}


		if (isset($_GET['froogle_export_successful'])) //show successful save confirmation message
		{
			set_query('&froogle_export_successful=','',true);
			if (file_exists(DIR_TEMP.'/froogle.txt'))
			{
				$getFileParam = Crypt::FileParamCrypt( 'GetFroogleFeed', null );
				$smarty->assign( 'getFileParam', $getFileParam );

				$smarty->assign('froogle_export_successful', 1);
				$smarty->assign('froogle_filesize', (string) round( filesize(DIR_TEMP.'/froogle.txt') / 1024 ) );
			}
		}

		if (isset($_POST['froogle_export'])) //export products
		if ($_POST['froogle_export'])
		{
			$currency = currGetCurrencyByID ( (int)$_POST['froogle_currency'] );

			if (!$currency)
			{
				$smarty->assign( 'froogle_errormsg', translate("gglbase_err_select_currency") );
			}
			else //do export
			{
				$f = @fopen(DIR_TEMP.'/froogle.txt','w');
				if ($f)
				{
					_exportToFroogle( $f, $currency['currency_value'] );
					fclose($f);
					RedirectSQ('froogle_export_successful=yes');
				}
				else
				{
					$smarty->assign( 'froogle_errormsg', translate("gglbase_err_cant_create_file") );
				}
			}
		}

		require(DIR_ROOT.'/includes/modules.export_products.php');
		$currencies = currGetAllCurrencies();
		$smarty->assign('currencies', $currencies);

		//set sub-department template
		$smarty->assign('admin_sub_dpt', 'modules_froogle.tpl.html');
	}
}
?>