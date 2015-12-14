<?
    
    class API
    {
        
        /*  получить категории в категории  */
        public static function GetCategory($par = 1)
        {
            $sSQl = "select categoryID,parent,name_ru from `SC_categories` where parent=$par";
            $nResult = mysql_query($sSQl);
            $nCols = mysql_num_rows($nResult);
            $row = array();
            for ($i = 0; $i < $nCols; $i++) {
                $row[] = mysql_fetch_array($nResult, MYSQL_ASSOC);
            }
            
            return $row;
        }
        
        public static function GetCategoryAll()
        {
            $sSQl = 'SELECT categoryID,parent,name_ru FROM `SC_categories`';
            $nResult = mysql_query($sSQl);
            $nCols = mysql_num_rows($nResult);
            $row = array();
            for ($i = 0; $i < $nCols; $i++) {
                $row[] = mysql_fetch_array($nResult, MYSQL_ASSOC);
            }
            
            return $row;
        }
        
        /*  получить товар в категории  */
        public static function GetProductByCat($par = 1)
        {
            $sSQl = "select productID,name_ru,Price,product_code,ostatok,code_1c from `SC_products` where  enabled=1 and categoryID=$par";
            $nResult = mysql_query($sSQl) or die($sSQl);
            $nCols = mysql_num_rows($nResult);
            $row = array();
            for ($i = 0; $i < $nCols; $i++) {
                
                $prod = mysql_fetch_array($nResult, MYSQL_ASSOC);
                $prod['pic'] = self::GetPhotoTovar($prod['productID']);
                $row[] = $prod;
            }
            
            return $row;
        }
        
        public static function GetProductTovarAll()
        {
            $sSQl = "SELECT productID,categoryID,name_ru,Price,product_code,ostatok,code_1c FROM `SC_products` WHERE  enabled=1";
            $nResult = mysql_query($sSQl) or die($sSQl);
            $nCols = mysql_num_rows($nResult);
            $row = array();
            for ($i = 0; $i < $nCols; $i++) {
                
                $prod = mysql_fetch_array($nResult, MYSQL_ASSOC);
                $prod['pic'] = self::GetPhotoTovar($prod['productID']);
                $row[] = $prod;
            }
            
            return $row;
        }
        
        public static function TestToken($token)
        {
            $sSql = "SELECT count(*) 
         FROM `SC_customers`
         where MD5(concat(Login,'_',cust_password))='{$token}'";
            
            $nResult = mysql_query($sSql) or die($sSql);
            $nCols = mysql_num_rows($nResult);
            if (mysql_result($nResult, 0, 0) == 0) return false;
            
            return true;
        }
        
        public static function TestLogin($login, $pass)
        {
            $pass = base64_decode($pass);
            $sSql = "select count(*) `SC_customers` where  Login='$login' and cust_password='$pass'";
            $nResult = mysql_query($sSql) or die($sSql);
            $nCols = mysql_num_rows($nResult);
            if (mysql_result($nResult, 0, 0) == 0) return false;
            
            return true;
        }
        
        /*  получить фото товара  */
        public static function GetPhotoTovar($ID)
        {
            
            $BASE = "../published/publicdata/MULTITOYS/attachments/SC/products_pictures/";
            $BASE_URL = "http://toysi.com.ua/published/publicdata/MULTITOYS/attachments/SC/products_pictures/";
            $sSQl = "select filename,thumbnail,enlarged from `SC_product_pictures` where productID=$ID";
            $nResult = mysql_query($sSQl) or die($sSQl);
            $nCols = mysql_num_rows($nResult);
            $row = array();
            for ($i = 0; $i < $nCols; $i++) {
                
                $buf = mysql_fetch_array($nResult, MYSQL_ASSOC);
                if (!file_exists($BASE.$buf['filename'])) unset($buf['filename']);
                if (!file_exists($BASE.$buf['thumbnail'])) unset($buf['thumbnail']);
                if (!file_exists($BASE.$buf['enlarged'])) unset($buf['enlarged']);
                
                if (isset($buf['filename'])) $buf['filename'] = $BASE_URL.$buf['filename'];
                if (isset($buf['thumbnail'])) $buf['thumbnail'] = $BASE_URL.$buf['thumbnail'];
                if (isset($buf['enlarged'])) $buf['enlarged'] = $BASE_URL.$buf['enlarged'];
                
                $row[] = $buf;
            }
            
            return $row;
        }
        
        
        public static function OutFormat($data, $format = "json", $type = 'tov')
        {
            
            switch ($format) {
                case 'json':
                    self::OutJSON($data);
                    break;
                case 'xml':
                    self::OutXML($data, $type);
                    break;
            }
        }
        
        public static function OutJSON($data)
        {
            header("Content-Type: text/json");
            header("Expires: Thu, 19 Feb 1998 13:24:18 GMT");
            header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Cache-Control: post-check=0,pre-check=0");
            header("Cache-Control: max-age=0");
            header("Pragma: no-cache");
            echo json_encode($data);
        }
        
        public static function OutXML($data, $type = "tov")
        {
            
            header("Content-Type: text/xml");
            header("Expires: Thu, 19 Feb 1998 13:24:18 GMT");
            header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Cache-Control: post-check=0,pre-check=0");
            header("Cache-Control: max-age=0");
            header("Pragma: no-cache");
            
            $sOut = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>";
            $sOut .= "<doc>";
            if ($type == 'tov') {
                for ($i = 0; $i < count($data); $i++) {
                    $sOut .= "<item>";
                    foreach ($data[$i] as $key => $value) {
                        if ($key != "pic") {
                            $sOut .= "<".$key.">".$value."</".$key.">";
                        }
                    }
                    
                    $sOut .= "<pic>";
                    for ($j = 0; $j < count($data[$i]['pic']); $j++) {
                        
                        $sOut .= "<image>";
                        foreach ($data[$i]['pic'][$j] as $key => $value) {
                            $sOut .= "<".$key.">".$value."</".$key.">";
                        }
                        $sOut .= "</image>";
                    }
                    $sOut .= "</pic>";
                    $sOut .= "</item>";
                }
            }
            
            if ($type == 'cat') {
                for ($i = 0; $i < count($data); $i++) {
                    $sOut .= "<item>";
                    foreach ($data[$i] as $key => $value) {
                        if ($key != "pic") {
                            $sOut .= "<".$key.">".$value."</".$key.">";
                        }
                    }
                    $sOut .= "</item>";
                }
                //print_r($data);
            }
            
            if ($type == 'all') {
                $fname = "cahe/".date("Y-m-d")."_".((int)(date("H") / 4)).".xml";
                $sOut .= "<category>";
                $cat = $data->cat;
                for ($i = 0; $i < count($cat); $i++) {
                    $sOut .= "<item>";
                    foreach ($cat[$i] as $key => $value) {
                        if ($key != "pic") {
                            $sOut .= "<".$key.">".$value."</".$key.">";
                        }
                    }
                    $sOut .= "</item>";
                }
                $sOut .= "</category>";
                
                $tov = $data->tov;
                $sOut .= "<tovar>";
                for ($i = 0; $i < count($tov); $i++) {
                    $sOut .= "<item>";
                    foreach ($tov[$i] as $key => $value) {
                        if ($key != "pic") {
                            $sOut .= "<".$key.">".$value."</".$key.">";
                        }
                    }
                    
                    $sOut .= "<pic>";
                    for ($j = 0; $j < count($tov[$i]['pic']); $j++) {
                        
                        $sOut .= "<image>";
                        foreach ($tov[$i]['pic'][$j] as $key => $value) {
                            $sOut .= "<".$key.">".$value."</".$key.">";
                        }
                        $sOut .= "</image>";
                    }
                    $sOut .= "</pic>";
                    $sOut .= "</item>";
                }
                $sOut .= "</tovar>";
            }
            $sOut .= "</doc>";
            //echo "</doc>";
            if ($type == 'all') {
                file_put_contents($fname, $sOut);
            }
            echo $sOut;
        }
        
        
    }

?>