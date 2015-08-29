<?php
class Pricelist extends Module {

	function callFromInstallConfig(){

		$PricelistDivision = new Division();
		$PricelistDivision->setName('���������');
		$PricelistDivision->setParentID(DivisionModule::getDivisionIDByUnicKey('TitlePage'));
		$PricelistDivision->setEnabled(1);
		$PricelistDivision->save();
		$PricelistDivision->addCustomSetting('������', 'icon');
		$PricelistDivision->loadCustomSettings();
		$PricelistDivision->setCustomSetting('icon', 'images/price.gif');
		$PricelistDivision->save();
		$PricelistDivision->addInterface($this->getConfigID().'_pricelist');
	}

	function initInterfaces(){

		$this->Interfaces = array(
		'pricelist' => array(
		'name' => '����� ����',
		'method' => 'methodPricelist',
		),
		);
	}

	function methodPricelist(){

		global $smarty;

		$sort_string = translate('prd_sort_pricelist_control_string');
		$sort_string = str_replace( '{ASC_NAME}', '<a rel="nofollow" href="'.xHtmlSetQuery('&sort=name&direction=ASC').'">'.translate('str_ascending').'</a>',$sort_string );
		$sort_string = str_replace( '{DESC_NAME}', '<a rel="nofollow" href="'.xHtmlSetQuery('&sort=name&direction=DESC').'">'.translate('str_descending').'</a>',$sort_string );
		$sort_string = str_replace( '{ASC_PRICE}', '<a rel="nofollow" href="'.xHtmlSetQuery('&sort=Price&direction=ASC').'">'.translate('str_ascending').'</a>',	$sort_string );
		$sort_string = str_replace( '{DESC_PRICE}', '<a rel="nofollow" href="'.xHtmlSetQuery('&sort=Price&direction=DESC').'">'.translate('str_descending').'</a>',	$sort_string );
		$sort_string = str_replace( '{ASC_RATING}', '<a rel="nofollow" href="'.xHtmlSetQuery('&sort=customers_rating&direction=ASC').'">'.translate('str_ascending').'</a>',	$sort_string );
		$sort_string = str_replace( '{DESC_RATING}', '<a rel="nofollow" href="'.xHtmlSetQuery('&sort=customers_rating&direction=DESC').'">'.translate('str_descending').'</a>',	$sort_string );
		$smarty->assign( 'string_product_sort', $sort_string );

		$smarty->assign('pricelist_elements', $this->_pricessCategories(1, 0));
		$smarty->assign('main_content_template', 'pricelist.tpl.html');
	}

	function _pricessCategories($parent,$level){

		$out = array();
		$cnt = 0;

		$sql = 'SELECT categoryID, '.LanguagesManager::sql_prepareField('name',true)
		.', slug, parent, sort_order FROM ?#CATEGORIES_TABLE WHERE categoryID>1 ORDER BY parent,'.LanguagesManager::ml_getLangFieldName('name');//
		$q = db_phquery($sql);
		$priceList = new DataTree();
		while ($row = db_fetch_row($q))
		{
			$priceList->setData(array('is_category'=>'1','id'=>$row['categoryID'],'slug'=>$row['slug'],'name'=>$row['name'],'sort_order'=>$row['sort_order']),
			$row['categoryID'],$row['parent']);
		}
		
		$sortfunction = create_function('$a,$b','{
if(isset($a["data"])&&isset($b["data"])){
	$a_val = intval($a["data"]["sort_order"]);
	$b_val = intval($b["data"]["sort_order"]);
	return $a_val>$b_val?1:(($a_val<$b_val)?-1:0);
}else{
	return 0;
}}');
		
		$priceList->sortNodes($sortfunction,1);
		

		if ( !isset($_GET['sort']) ){
			$order_clause = 'order by sort_order';
		}else{
			//verify $_GET['sort']
			switch ($_GET['sort']){
				default:
					$_GET['sort'] = 'name';
				case 'name':
				case 'Price':
				case 'customers_rating':
					break;
			}

			$order_clause = ' order by '.$_GET['sort'];
			if ( isset($_GET['direction']) )
			{
				if ( !strcmp( $_GET['direction'] , 'DESC' ) )
				$order_clause .= ' DESC ';
				else
				$order_clause .= ' ASC ';
			}
		}

		$sql = 'SELECT productID, '.LanguagesManager::sql_prepareField('name',true).', Price, in_stock, slug, categoryID, product_code from ?#PRODUCTS_TABLE WHERE categoryID>1 and Price>0 and enabled=1 '.
		$order_clause.'
				';
		//add products
		$q = db_phquery( $sql);
		while ($row = db_fetch_row($q))
		{
			$row['price'] = show_price($row['Price']);
			$priceList->setData(array('is_category'=>'0','id'=>$row['productID'],'slug'=>$row['slug'],'name'=>$row['name'],'in_stock'=>$row['in_stock'],'price'=>$row['price'],'product_code'=>$row['product_code']),
			$priceList->getMaxNodeId()+1,$row['categoryID']);
		}

		$sql = 'SELECT `product`.productID, '.LanguagesManager::sql_prepareField('name',true).', Price, in_stock, slug, `category`.categoryID as categoryID, product_code from ?#PRODUCTS_TABLE as `product` LEFT JOIN ?#CATEGORIY_PRODUCT_TABLE as `category` ON (`product`.productID=`category`.productID) WHERE `category`.categoryID>1 and Price>0 and enabled=1 '.
		$order_clause.'
				';
		//add products
		$q = db_phquery( $sql);
		while ($row = db_fetch_row($q))
		{
			$row['price'] = show_price($row['Price']);
			$priceList->setData(array('is_category'=>'0',
										'id'=>$row['productID'],
										'slug'=>$row['slug'],
										'name'=>$row['name'],
										'in_stock'=>$row['in_stock'],
										'price'=>$row['price'],
										'product_code'=>$row['product_code']),
								$priceList->getMaxNodeId()+1,
								$row['categoryID']);
		}

		$out = $priceList->plainData(-2);

		return $out;

		//OLD CODE

		//same as processCategories(), except it creates a pricelist of the shop

		$out = array();
		$cnt = 0;

		$sql = '
			SELECT categoryID, '.LanguagesManager::sql_prepareField('name').' AS name, slug FROM ?#CATEGORIES_TABLE WHERE parent=? ORDER BY sort_order, name
		';
		$q1 = db_phquery($sql, $parent);

		while ($row = db_fetch_row($q1))
		{
			$priceList->setData($row,$row[0]);


			//add category to the output
			$out[$cnt][0] = $row[0];
			$out[$cnt][1] = $row[1];
			$out[$cnt][2] = $level;
			$out[$cnt][3] = 'background1';
			$out[$cnt][4] = 0; //0 is for category, 1 - product
			$out[$cnt]['slug'] = $row['slug']; //0 is for category, 1 - product
			$cnt++;

			if ( !isset($_GET['sort']) )
			$order_clause = 'order by sort_order';
			else
			{
				//verify $_GET['sort']
				switch ($_GET['sort']){
					default:
						$_GET['sort'] = 'name';
					case 'name':
					case 'Price':
					case 'customers_rating':
						break;
				}

				$order_clause = ' order by '.$_GET['sort'];
				if ( isset($_GET['direction']) )
				{
					if ( !strcmp( $_GET['direction'] , 'DESC' ) )
					$order_clause .= ' DESC ';
					else
					$order_clause .= ' ASC ';
				}
			}

			$sql = '
				SELECT productID, '.LanguagesManager::sql_prepareField('name').' AS name, Price, in_stock, slug from ?#PRODUCTS_TABLE WHERE categoryID=? and Price>0 and enabled=1 '.
			$order_clause.'
				';
			//add products
			$q = db_phquery( $sql,$row[0] );
			while ($row1 = db_fetch_row($q))
			{
				if ($row1[2] <= 0)
				$row1[2]= 'n/a';
				else
				$row1[2] = show_price($row1[2]);

				//add product to the output
				$out[$cnt][0] = $row1[0];
				$out[$cnt][1] = $row1[1];
				$out[$cnt][2] = $level;
				$out[$cnt][3] = 'FFFFFF';
				$out[$cnt][4] = 1; //0 is for category, 1 - product
				$out[$cnt][5] = $row1[2];
				$out[$cnt][6] = $row1[3];
				$out[$cnt]['slug'] = $row1['slug'];
				$cnt++;
			}

			//process all subcategories
			$sub_out = $this->_pricessCategories($row[0], $level+1);

			//add $sub_out to the end of $out
			for ($j=0; $j<count($sub_out); $j++)
			{
				$out[] = $sub_out[$j];
				$cnt++;
			}

		}
			
		return $out;

	}
}
?>