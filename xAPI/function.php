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
                $prod = array_map('self::_deleteHTML_Elements', $prod);
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
            $BASE_URL = $_SERVER['HTTP_HOST']."/published/publicdata/MULTITOYS/attachments/SC/products_pictures/";
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
        
        
        public static function OutFormat($Cat = '', $Tov = '', $format = "xml", $type = 'tov')
        {
            
            switch ($format) {
                case 'json':
                    self::OutJSON($Cat);
                    self::OutJSON($Tov);
                    break;
                case 'xml':
                    self::OutXML($Cat, $Tov, $type);
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
        
        public static function OutXML($Cat = '', $Tov = '', $type = "tov")
        {
            
            header("Content-Type: text/xml");
            header("Expires: Thu, 19 Feb 1998 13:24:18 GMT");
            header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Cache-Control: post-check=0,pre-check=0");
            header("Cache-Control: max-age=0");
            header("Pragma: no-cache");
            
//            $data = self::_deleteHTML_Elements($data);
            $sOut = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
            $sOut .= "<doc>\n";
            if ($type == 'tov') {
                for ($i = 0; $i < count($Tov); $i++) {
                    $sOut .= "<item>\n";
                    foreach ($Tov[$i] as $key => $value) {
                        if ($key != "pic") {
                            $sOut .= "<".$key.">".$value."</".$key.">\n";
                        }
                    }
                    
                    $sOut .= "<pic>\n";
                    for ($j = 0; $j < count($Tov[$i]['pic']); $j++) {
                        
                        $sOut .= "<image>\n";
                        foreach ($Tov[$i]['pic'][$j] as $key => $value) {
                            $sOut .= "<".$key.">".$value."</".$key.">\n";
                        }
                        $sOut .= "</image>\n";
                    }
                    $sOut .= "</pic>\n";
                    $sOut .= "</item>\n";
                }
            }
            
            if ($type == 'cat') {
                for ($i = 0; $i < count($Cat); $i++) {
                    $sOut .= "<item>\n";
                    foreach ($Cat[$i] as $key => $value) {
                        if ($key != "pic") {
                            $sOut .= "<".$key.">".$value."</".$key.">\n";
                        }
                    }
                    $sOut .= "</item>\n";
                }
                //print_r($data);
            }
            
            if ($type == 'all') {
                $fname = "cache/".date("Y-m-d")."_".((int)(date("H") / 4)).".xml";
                $sOut .= "<category>\n";
                $cat = $Cat;
                for ($i = 0; $i < count($cat); $i++) {
                    $sOut .= "\t<item>\n";
                    foreach ($cat[$i] as $key => $value) {
                        if ($key != "pic") {
                            $sOut .= "\t\t<".$key.">".$value."</".$key.">\n";
                        }
                    }
                    $sOut .= "</item>\n";
                }
                $sOut .= "</category>\n";
//                unset($cat);
                
                $tov = $Tov;
                $sOut .= "<tovar>\n";
                for ($i = 0; $i < count($tov); $i++) {
                    $sOut .= "\t<item>\n";
                    foreach ($tov[$i] as $key => $value) {
                        if ($key != "pic") {
                            $sOut .= "\t\t<".$key.">".$value."</".$key.">\n";
                        }
                    }
                    
                    $sOut .= "<pic>\n";
                    for ($j = 0; $j < count($tov[$i]['pic']); $j++) {
                        
                        $sOut .= "\t<image>\n";
                        foreach ($tov[$i]['pic'][$j] as $key => $value) {
                            $sOut .= "\t\t<".$key.">".$value."</".$key.">\n";
                        }
                        $sOut .= "\t</image>\n";
                    }
                    $sOut .= "</pic>\n";
                    $sOut .= "\t</item>\n";
                }
                $sOut .= "</tovar>\n";
            }
            $sOut .= "</doc>\n";
//            unset($tov);
            
            //echo "</doc>";
            if ($type == 'all') {
                file_put_contents($fname, $sOut);
            }
            echo $sOut;
        }
        
        protected static function _deleteHTML_Elements($str/*, $strip_tags = true*/)
        {
//            if ($strip_tags) {
                $str = strip_tags($str);
//            }
            $str = str_replace('&nbsp;', ' ', $str);
            $str = str_replace("&", "&amp;", $str);
            $str = str_replace("<", "&lt;", $str);
            $str = str_replace(">", "&gt;", $str);
            $str = str_replace("\"", "&quot;", $str);
            $str = str_replace("'", "&apos;", $str);
            $str = str_replace("\r", "", $str);

            return $str;
        }
        
    }