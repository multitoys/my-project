<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="product" content="Metro UI CSS Framework">
    <meta name="description" content="Simple responsive css framework">
    <meta name="author" content="Sergey S. Pimenov, Ukraine, Kiev">

    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">


    <!-- Load JavaScript Libraries -->
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/jquery/jquery.widget.min.js"></script>
    <script src="js/jquery/jquery.mousewheel.js"></script>
    <script src="js/prettify/prettify.js"></script>

    <!-- Metro UI CSS JavaScript plugins -->
    <script src="js/load-metro.js"></script>

    <!-- Local JavaScript -->
    <script src="js/docs.js"></script>
    <script src="js/github.info.js"></script>

    <title>xAPI multitoys</title>
</head>
<body class="metro">
    
    <div class="container">
                <h1>
                    <a href="javascript:history.back(1);"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
                    multitoys<small class="on-right">PHP Class</small>
                </h1>

               
             <pre class="prettyprint linenums">
 /*
PHP Class
*/

    class Toys
    {
      static $login="demo";
      static $pass="demo";
      static $type="json";
      static $token;
     
      
      static function GetToken()
      {
         if(self::$token=="") {
           self::$token=md5(self::$login."_".base64_encode(self::$pass));
         }
                     
         return self::$token;
      }
      
      static function GetURL($url)
      {
        $token=self::GetToken();
        $url.="&type=".self::$type."&cid=".$token;
        $data=file_get_contents($url);
                     
        return $data;
      }
      
      static function getCategory($id=1)
      {
        $url="http://multitoys.com.ua/xAPI/index.php?mod=GCAT&idcat={$id}";
        $aCat=json_decode(self::GetURL($url));
        
        return $aCat;
      }
      
      static function getTovar($id=1)
      {
        $url="http://multitoys.com.ua/xAPI/index.php?mod=GTOV&idcat={$id}";
        $aTov=json_decode(self::GetURL($url));
        
        return $aTov;
      }
      
      static function getAlls()
      {
        $url="http://multitoys.com.ua/xAPI/index.php?mod=GTOVA";
        $aAll=json_decode(self::GetURL($url));
        
        return $aAll;
      }
      
      static function getAll($id=1)
      {
        $url="http://multitoys.com.ua/xAPI/index.php?mod=GCAT&idcat={$id}";
        $aCat=json_decode(self::GetURL($url));
        if($aCat!="") {
            for($i=0;$i<count($aCat);$i++) {
              $aCat[$i]->c_cat=getAll($aCat[$i]->categoryID);
            }
        }
                     
        return $aCat;
      }
    }</pre>             
    </div>

    <script src="js/hitua.js"></script>

</body>
</html>