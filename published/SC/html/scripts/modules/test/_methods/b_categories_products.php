<?php
if(isset($_POST['save_products'])){
	$_POST['action'] = 'save_products';
}elseif($_POST['delete_selected']){
	$_POST['action'] = 'delete_selected_products';
}elseif($_POST['move_selected']){
	$_POST['action'] = 'move_selected_products';
}elseif($_POST['duplicate_selected']){
	$_POST['action'] = 'duplicate_selected_products';
}

class CategoriesProductsController extends ActionsController{

	function save_products(){

		safeMode(true);
		$data = scanArrayKeysForID($_POST, array( "price", "left", "sort_order", "ordering_available", "enabled" ) );
foreach( $data as $key => $val ){
    $sqlValues = array();
//             print_r ($val);
//             break;
    if ( isset($val["price"]) ){
        $temp = $val["price"];
        $temp = round($temp*100)/100;
        $sqlValues[] = "Price='{$temp}'";
        //db_query( "UPDATE ".PRODUCTS_TABLE." SET Price='$temp' "." WHERE productID=".$key );
    }
    if ( isset($val["left"]) ){
        $sqlValues[] = 'in_stock = \''.intval($val["left"]).'\'';
        //db_query( "UPDATE ".PRODUCTS_TABLE." SET in_stock = '".$val["left"]."' WHERE productID=".$key);
    }
    if ( isset($val["sort_order"]) ){
        $sqlValues[] = 'sort_order = '.$val["sort_order"];
        //db_query( "UPDATE ".PRODUCTS_TABLE." SET sort_order = '".$val["sort_order"]."' WHERE productID=".$key);
    }
    /*if(isset($_POST['categoryID'])){
        $sqlValues[] = 'categoryID =\''.intval($_POST['categoryID']).'\'';
        }*/

    if ( isset($val["ordering_available"]) ){
        $sqlValues[] = 'ordering_available = 1';
    }else{
        $sqlValues[] = 'ordering_available = 0';
    }

    if ( isset($val["enabled"]) ){
        $sqlValues[] = 'enabled = 0';
    }else{
        $sqlValues[] = 'enabled = 1';
    }

    if(count($sqlValues)){
        $sql = 'UPDATE `'.PRODUCTS_TABLE.'` SET '.implode(', ',$sqlValues).' WHERE productID='.$key;
        db_query($sql);
    }
}

if ( CONF_UPDATE_GCV == '1' )update_products_Count_Value_For_Categories(1);

Message::raiseMessageRedirectSQ(MSG_SUCCESS, '', 'msg_information_save');
	}

	function delete_product(){

		safeMode(true);
		DeleteProduct($this->getData('productID'));
		RedirectSQ('productID');
	}

	function duplicate_selected_products(){
		$ProductIDs = scanArrayKeysForID($this->getData(),'selected_product');
		$sourceProductID = $this->getData('productID');
		$session_id = session_id();
		$session_id = session_id();
		session_write_close();
		$maxCount=false;
		$msg='';

		$limitExceed=false;
		if(SystemSettings::is_hosted()){
			$messageClient = new WbsHttpMessageClient($db_key, 'wbs_msgserver.php');
			$messageClient->putData('action', 'ALLOW_ADD_PRODUCT');
			$messageClient->putData('language',LanguagesManager::getCurrentLanguage()->iso2);
			$messageClient->putData('session_id',$session_id);
			$res=$messageClient->send();
		}else{
			$res = false;
		}

		if($res&&($messageClient->getResult('max')>0)){
			$maxCount=$messageClient->getResult('max')-$messageClient->getResult('current');
			$msg=$messageClient->getResult('msg');
		}else{
			$maxCount = false;
		}

		$duplicated_count = 0;

		foreach($ProductIDs as $productID=>$selected){
			if(!$selected)continue;
			if(($maxCount !== false) &&($maxCount<1)){
				break;
			}
			$product = new Product();
			$product->loadByID($productID);
			$product->slug = '';
			$product->items_sold = 0;
			$product->viewed_times = 0;
			$product->add2cart_counter = 0;

			//product name
			$product->name.=($product->name?' (1)':'');
			$names = LanguagesManager::ml_getLangFieldNames('name');
			foreach($names as $name){
				$product->$name.=($product->$name?' (1)':'');
			}
			//article
			if($product->product_code){
				$product->product_code .= ' (1)';
			}

			//product files
			$file_name = $product->eproduct_filename;
			$product->eproduct_filename = '';
			if($file_name &&file_exists(DIR_PRODUCTS_FILES.'/'.$file_name)){
				$duplicate_file_name = getUnicFile(2, preg_replace('@\.([^\.]+)$@', '%s.$1', $file_name), DIR_PRODUCTS_FILES);
				$res = Functions::exec('file_copy', array(DIR_PRODUCTS_FILES.'/'.$file_name, DIR_PRODUCTS_FILES.'/'.$duplicate_file_name));
				if(PEAR::isError($res)){
					$error = $res;
					break;
				}
				if(file_exists(DIR_PRODUCTS_FILES.'/'.$duplicate_file_name)){
					$product->eproduct_filename = $duplicate_file_name;
				}
			}
			$product->productID = null;
			$product->default_picture = null;
				
			$product->save();
			$maxCount--;
			$duplicated_count++;
				
			//additional categories
			$appended_categories = catGetAppendedCategoriesToProduct($productID);
			foreach ($appended_categories as $appended_categorie){
				catAddProductIntoAppendedCategory($product->productID, $appended_categorie['categoryID'] );
				if ( CONF_UPDATE_GCV == '1' )catUpdateProductCount($product->productID, $appended_categorie['categoryID']);

			}
			
			//extra options
			$res = db_phquery('SELECT * FROM ?#PRODUCT_OPTIONS_VALUES_TABLE WHERE `productID`=?',$productID);
			if(!isset($insert_sql)){
				$fields = LanguagesManager::ml_getLangFieldNames('option_value');
				$insert_sql = 'INSERT INTO ?#PRODUCT_OPTIONS_VALUES_TABLE (`optionID`, `productID`, `option_type`, `option_show_times`, `variantID`';
				foreach($fields as $field){
					$insert_sql .= ", `{$field}`";
				}
				$insert_sql .= ') VALUES (?optionID, ?productID, ?option_type, ?option_show_times, ?variantID';
				foreach($fields as $field){
					$insert_sql .= ", ?{$field}";
				}
				$insert_sql .= ')';
			}
			while($row = db_fetch_row($res)){
				$row['productID'] = $product->productID;
				db_phquery($insert_sql,$row);
					
			}
			
			$res = db_phquery('SELECT * FROM ?#PRODUCTS_OPTIONS_SET_TABLE WHERE `productID`=?',$productID);
			while($row = db_fetch_row($res)){
				$row['productID'] = $product->productID;
				db_phquery('INSERT INTO ?#PRODUCTS_OPTIONS_SET_TABLE (`productID`,`optionID`,`variantID`,`price_surplus`) VALUES(?productID, ?optionID, ?variantID, ?price_surplus)',$row);
			}

			//Downloadable options
				
			$Pictures = GetPictures($productID);
			foreach ($Pictures as $order=>$Picture){
				//filename, thumbnail, enlarged
				$filename = $Picture['filename'];
				$name = null;
				$new_name = null;
				if(preg_match('/([^\?]+)\.([^\.]+)/',$filename,$name)){
					$name = $name[1];
				}else{
					$name = null;
				}

				if($filename){
					if(file_exists(DIR_PRODUCTS_PICTURES.'/'.$filename)){
						$filename =getUnicFile(2, preg_replace('@\.([^\.]+)$@', '%s.$1', $filename), DIR_PRODUCTS_PICTURES);
						if(preg_match('/([^\?]+)\.([^\.]+)/',$filename,$new_name)){
							$new_name = $new_name[1];
						}else{
							$new_name = null;
						}
						$res = Functions::exec('file_copy', array(DIR_PRODUCTS_PICTURES.'/'.$Picture['filename'], DIR_PRODUCTS_PICTURES.'/'.$filename));
						if(PEAR::isError($res)){
							$error = $res;
							break;
						}
					}else{
						$filename = null;
					}
				}

				$thumbnail = $Picture['thumbnail'];
				if($thumbnail){
						
						
					if(file_exists(DIR_PRODUCTS_PICTURES.'/'.$thumbnail)){
						if($name&&$new_name){
							$thumbnail = str_replace($name,$new_name,$thumbnail);
						}
						if(file_exists(DIR_PRODUCTS_PICTURES.'/'.$thumbnail)){
							$thumbnail =getUnicFile(2, preg_replace('@\.([^\.]+)$@', '%s.$1', $thumbnail), DIR_PRODUCTS_PICTURES);
						}
						$res = Functions::exec('file_copy', array(DIR_PRODUCTS_PICTURES.'/'.$Picture['thumbnail'], DIR_PRODUCTS_PICTURES.'/'.$thumbnail));
						if(PEAR::isError($res)){
							$error = $res;
							break;
						}
					}else{
						$thumbnail = null;
					}
				}
				$enlarged = $Picture['enlarged'];
				if($enlarged){
					if(file_exists(DIR_PRODUCTS_PICTURES.'/'.$enlarged)){
						if($name&&$new_name)
						$enlarged = str_replace($name,$new_name,$enlarged);
						if(file_exists(DIR_PRODUCTS_PICTURES.'/'.$enlarged)){
							$enlarged =getUnicFile(2, preg_replace('@\.([^\.]+)$@', '%s.$1', $enlarged), DIR_PRODUCTS_PICTURES);
						}
						$res = Functions::exec('file_copy', array(DIR_PRODUCTS_PICTURES.'/'.$Picture['enlarged'], DIR_PRODUCTS_PICTURES.'/'.$enlarged));
						if(PEAR::isError($res)){
							$error = $res;
							break;
						}
					}else{
						$enlarged = null;
					}
				}

				$res = db_phquery("INSERT ?#PRODUCT_PICTURES (productID, filename, thumbnail, enlarged, priority) VALUES( ?, ?, ?, ?,?)",
			 $product->productID, $filename, $thumbnail, $enlarged,$order);
			 if(PEAR::isError($res)){
			 	$error = $res;
					break;
			 }
			 if($order == 0){
			 	prdSetProductDefaultPicture($product->productID,db_insert_id());
			 }
			}
			//db_phquery("INSERT ?#PRODUCT_PICTURES (productID, filename, thumbnail, enlarged, priority) VALUES( ?, ?, ?, ?,?)",
			// $product->productID, $standard_file_name, $thumbnail_file_name, $enlarged_file_name,$this->getData('upload_picture_priority'));

			//product pictures
				
			unset($product);
		}
		$msg = '';
		if($maxCount !== false){
			$messageClient = new WbsHttpMessageClient($db_key, 'wbs_msgserver.php');
			$messageClient->putData('action', 'ALLOW_ADD_PRODUCT');
			$messageClient->putData('language',LanguagesManager::getCurrentLanguage()->iso2);
			$messageClient->putData('session_id',$session_id);
			$messageClient->send();
			$msg='<br>'.$messageClient->getResult('msg');
			$limitExceed=!$messageClient->getResult('success');

			session_id($session_id);
			session_start();
		}

		Message::raiseMessageRedirectSQ($error?MSG_ERROR:MSG_SUCCESS, '', ($error?$error->getMessage():sprintf(translate('prdcat_product_n_duplicated'),$duplicated_count)).$msg);
	}

	function move_selected_products(){
		if(isset($_POST['categoryID'])){
			$categoryID = intval($_POST['categoryID']);
			$m_productID = array_keys(scanArrayKeysForID($this->getData(), 'selected_product'));
			$m_productID = array_map('intval',$m_productID);
			if(count($m_productID)){
				$sql = 'UPDATE `'.PRODUCTS_TABLE.'` SET categoryID ='.$categoryID.' WHERE productID IN ('.implode(', ',$m_productID).')';
				db_query($sql);
			}
		}
		if ( CONF_UPDATE_GCV == '1' )update_products_Count_Value_For_Categories(1);

		db_num_rows();
		Message::raiseMessageRedirectSQ(MSG_SUCCESS, '&categoryID='.$categoryID, 'msg_information_save');
	}

	function delete_selected_products(){

		safeMode(true);

		$r_productID = array_keys(scanArrayKeysForID($this->getData(), 'selected_product'));
		foreach ($r_productID as $productID)
		{
			DeleteProduct($productID, 0);
		}

		update_products_Count_Value_For_Categories(1);

		Message::raiseMessageRedirectSQ(MSG_SUCCESS, '', 'msg_information_save');
	}

	function delete_category(){

		safeMode(true);

		$res = catDeleteCategory( $_GET['categoryID'] );

		RedirectSQ('?ukey=categorygoods');
	}

	function main(){
		$Register = &Register::getInstance();
		/*@var $Register Register*/
		$smarty = &$Register->get(VAR_SMARTY);
		/*@var $smarty Smarty*/

		$GetVars = &$Register->get(VAR_GET);
		$categoryID = isset($GetVars['categoryID'])?intval($GetVars['categoryID']):1;

		renderURL('categoryID='.$categoryID, '', true);

		$c = currGetCurrencyByID(CONF_DEFAULT_CURRENCY);

		$gridEntry = ClassManager::getInstance('grid');
		/*@var $gridEntry Grid*/

		$gridEntry->show_rows_num_select = false;

		$gridEntry->query_total_rows_num = 'SELECT COUNT(*) FROM ?#PRODUCTS_TABLE t1, ?#CATEGORIY_PRODUCT_TABLE t2 WHERE t1.categoryID='.intval($categoryID).' OR t2.categoryID='.intval($categoryID).' GROUP BY t1.productID';
		$gridEntry->query_select_rows = 'SELECT t1.*, '.LanguagesManager::sql_prepareField('t1.name').' AS name FROM ?#PRODUCTS_TABLE t1 LEFT JOIN ?#CATEGORIY_PRODUCT_TABLE t2 ON t1.productID=t2.productID WHERE t1.categoryID='.intval($categoryID).' OR t2.categoryID='.intval($categoryID).' GROUP BY t1.productID';

		$gridEntry->get_direction_name = 'sort_dir';
		$gridEntry->default_sort_direction = 'ASC';
		$gridEntry->rows_num = 20;

		$gridEntry->registerHeader("prdset_product_code", 'product_code', false, 'asc');
		$gridEntry->registerHeader("prdset_product_name", 'name', true, 'asc');
		$gridEntry->registerHeader("prdset_str_invisible", 'enabled', false, 'asc', '', 'checkbox');
		$gridEntry->registerHeader("prdset_str_ordering_available", 'ordering_available', false, 'asc', '', 'checkbox');
		$gridEntry->registerHeader("prdset_product_rating", 'customers_rating', false, 'desc');
		$gridEntry->registerHeader("str_price", 'Price', false, 'desc', '', ', '.$c['currency_iso_3']);
		if(CONF_CHECKSTOCK){
			$gridEntry->registerHeader("str_in_stock", 'in_stock', false, 'desc');

		}
		$gridEntry->registerHeader('prdset_product_sold', 'items_sold', false, 'desc', 'right');
		$gridEntry->registerHeader('str_sort_order', 'sort_order', false, 'desc');
		$gridEntry->registerHeader('');


		$gridEntry->prepare_headers();
	}
}
ActionsController::exec('CategoriesProductsController');

$Register = &Register::getInstance();
$smarty = &$Register->get(VAR_SMARTY);
/* @var $smarty Smarty */

//products and categories tree view
function _getUrlToSubmit(){

	$res = set_query('__tt=');
	static $params = array('categoryID',
	'offset',
	'sort',
	'sort_dir',
	'search_criteria',
	'search_value',
	'search',
	'show_all');

	foreach($params as $param){
		$value = isset($_POST[$param])?$_POST[$param]:(isset($_GET[$param])?$_GET[$param]:'');
		$res = set_query("&{$param}=".$value,$res);
	}
	/*
	 if ( isset($_GET['categoryID']) )
	 $res = set_query('&categoryID='.$_GET['categoryID'], $res);
	 if ( isset($_GET['offset']) )
	 $res = set_query('&offset='.$_GET['offset'], $res);
	 if ( isset($_GET['sort']) )
	 $res = set_query('&sort='.$_GET['sort'], $res);
	 if ( isset($_GET['sort_dir']) )
	 $res = set_query('&sort_dir='.$_GET['sort_dir'], $res);

	 if ( isset($_GET['search_criteria']) )
	 $res = set_query('&search_criteria='.$_GET['search_criteria'], $res);
	 if ( isset($_GET['search_value']) )
	 $res = set_query('&search_value='.$_GET['search_value'], $res);
	 if ( isset($_POST['search_criteria']) )
	 $res = set_query('&search_criteria='.$_POST['search_criteria'], $res);
	 if ( isset($_POST['search_value']) )
	 $res = set_query('&search_value='.$_POST['search_value'], $res);

	 if ( isset($_GET['search']) )
	 $res = set_query('&search='.$_GET['search'], $res);
	 if ( isset($_POST['search']) )
	 $res = set_query('&search='.$_POST['search'], $res);
	 if ( isset($_GET['show_all']) )
	 $res = set_query('&show_all='.$_GET['show_all'], $res);
	 */
	return $res;
}

function _getUrlToDelete()
{
	return _getUrlToSubmit();
}

function _getUrlToCategoryTreeExpand()
{
	return _getUrlToSubmit();
}

function _getUrlToNavigate(){
	return _getUrlToSubmit();
}

function _getUrlToSort(){
	return _getUrlToSubmit();
}

$callBackParam = array();

if ( isset($_GET["search"]) )
{
	$search_value = isset($_POST["search_value"])?$_POST["search_value"]:(isset($_GET["search_value"])?$_GET["search_value"]:null);
	if($search_value){
		storePOST('search_value',$search_value);
	}else{
		$search_value = loadPOST('search_value');
	}
	if($search_value){
		$array = explode( " ", $search_value );
		$search_value_array = array();
		foreach( $array as $val )
		{
			$val = trim($val);
			if ($val)$search_value_array[] = $val;
		}
		//$search_criteria = isset($_POST["search_criteria"])?$_POST["search_criteria"]:(isset($_GET["search_criteria"])?$_GET["search_criteria"]:null);
		//if ( $search_criteria == "name" )
		$callBackParam["name"] = $search_value_array;
		//if ( $search_criteria == "product_code" )
		$callBackParam["product_code"] = $search_value_array;
	
		//$smarty->assign( "search_criteria", $search_criteria );
		$smarty->hassign( "search_value", $search_value );
		$smarty->assign( "searched_done", 1 );
	}else{
		unsetPOST('search_value');
	}
}

if ( isset($_GET["expandCat"]) ){
	catExpandCategory( $_GET["expandCat"], "expandedCategoryID_Array" );
	renderURL('expandCat=', '', true);
}

if ( isset($_GET["shrinkCat"]) ){
	catShrinkCategory( $_GET["shrinkCat"], "expandedCategoryID_Array" );
	renderURL('shrinkCat=', '', true);
}

if (isset($_POST["update_gc_value"])) //update button pressed
{
	@set_time_limit(60*4);
	update_products_Count_Value_For_Categories(1);
	Redirect( "admin.php?dpt=catalog&sub=products_categories&categoryID=".$_POST["categoryID"]);
}

//calculate how many products are there in root category


//$q = db_query("SELECT count(*) FROM ".PRODUCTS_TABLE." WHERE categoryID=1") or die (db_error());
//$cnt = db_fetch_row($q);
$smarty->assign("products_in_root_category",catGetCategoryProductCount(1));

if ( !isset($_SESSION["expandedCategoryID_Array"]) )
$_SESSION["expandedCategoryID_Array"] = array( 1 );

$c = catGetCategoryCList( $_SESSION["expandedCategoryID_Array"] );
$smarty->assign("categories", $c);

//show category name as a title
$row = array();
if (!isset($_GET["categoryID"]) && !isset($_POST["categoryID"]))
{
	$categoryID = 1;
	$row[0] = translate("prdcat_category_root");
}
else //go to the root if category doesn't exist
{
	$categoryID = isset($_GET["categoryID"]) ? $_GET["categoryID"] : $_POST["categoryID"];
	$q = db_query("SELECT ".LanguagesManager::sql_prepareField('name')." AS name FROM ".CATEGORIES_TABLE." WHERE categoryID<>0 and categoryID='$categoryID'") or die (db_error());
	$row = db_fetch_row($q);
	if (!$row)
	{
		$categoryID = 0;
		$row[0] = translate("prdcat_category_root");
	}
}

$smarty->assign("categoryID", $categoryID);
$smarty->assign("category_name", $row[0]);



$count_row	= 0;
$offset		= 0;
$products	= null;

if ( isset($_GET["sort"]) )
{
	$callBackParam["sort"] = $_GET["sort"];
	if ( isset($_GET["sort_dir"]) )
	$callBackParam["direction"] = $_GET["sort_dir"];
}else{
	$callBackParam['sort'] = 'name';
	$callBackParam['direction'] = 'asc';
}

if ( isset($_GET["search"]) ){

}else{
	$callBackParam["categoryID"] = $categoryID;
	$callBackParam["searchInSubcategories"] = false;
}

if(isset($_GET['show_all'])){
	$Register->assign('show_all',1);
	renderURL('show_all=','',true);
}
$count = 0;

$navigatorHtml = GetNavigatorHtml(_getUrlToNavigate(), 20,'prdSearchProductByTemplate', $callBackParam, $products, $offset, $count );

$ProductsIds = array();
$TC = count($products);
for( $i=0; $i < $TC; $i++ )$ProductsIds[$products[$i]['productID']] = $i;
/*
 * UNUSED DATA
 *
	if(count($ProductsIds)){

	$sql = '
	SELECT COUNT(photoID) as cnt, productID FROM ?#PRODUCT_PICTURES WHERE filename<>"" AND productID IN(?@)
	GROUP BY productID
	';
	$Result = db_phquery($sql,array_keys($ProductsIds));
	while ($_Row = db_fetch_assoc($Result)){

	$products[$ProductsIds[$_Row['productID']]]['picture_count']	= $_Row['cnt'];
	}
	$sql = '
	SELECT COUNT(photoID) as cnt, productID FROM ?#PRODUCT_PICTURES WHERE thumbnail<>"" AND productID IN(?@)
	GROUP BY productID
	';
	$Result = db_phquery($sql,array_keys($ProductsIds));
	while ($_Row = db_fetch_assoc($Result)){

	$products[$ProductsIds[$_Row['productID']]]['thumbnail_count']	= $_Row['cnt'];
	}
	$sql = '
	SELECT COUNT(photoID) as cnt, productID FROM ?#PRODUCT_PICTURES WHERE enlarged<>"" AND productID IN(?@)
	GROUP BY productID
	';
	$Result = db_phquery($sql,array_keys($ProductsIds));
	while ($_Row = db_fetch_assoc($Result)){

	$products[$ProductsIds[$_Row['productID']]]['enlarged_count']	= $_Row['cnt'];
	}
	}
	*/
$smarty->assign("navigatorHtml", $navigatorHtml );

$smarty->hassign( "urlToSort", _getUrlToSort() );
$smarty->hassign( "urlToSubmit", _getUrlToSubmit() );
$smarty->hassign( "urlToDelete", _getUrlToDelete() );
$smarty->hassign( "urlToCategoryTreeExpand", _getUrlToCategoryTreeExpand());

$smarty->assign( "searched_count",
str_replace( "{N}",
$count,  translate("msg_n_matches_found") )  );

//products list
$smarty->assign("GridRows", $products );
//set main template
$smarty->assign("admin_sub_dpt", "categories_products.html");
?>