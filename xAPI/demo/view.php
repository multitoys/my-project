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

    <title>xAPI Toysi</title>
</head>
<body class="metro">
    
    <div class="container">
                <h1>
                    <a href="javascript:history.back(1);"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
                    <?=$sName;?><small class="on-right"></small>
                </h1>

               
           
               

                <a href="index.php?api=info"><h2 id="_dropdown">API JSON</h2></a>
                    <div class="grid fluid">
                        <div class="row">
                            <div class="span4">
                                <h4>Категории</h4>
                                <ul class="dropdown-menu open keep-open" style="position: relative; width: 200px; z-index: 1">
                                   <?
								   
								   for($i=0;$i<count($aCat);$i++)
								   {
								    ?><li><a href="index.php?idcat=<?=$aCat[$i]->categoryID;?>&name=<?=urlencode($aCat[$i]->name_ru);?>"><?=$aCat[$i]->name_ru;?></a></li><?
								   }
								   
								   ?>
                                    
                                  
                                </ul>
                            </div>
                            <div class="span8">
                                <h4>Товар</h4>
								 <h2>Список игрушек</h2>
                                <div class="listview-outlook" data-role="listview">
								<?
								if($aTov!="")
								for($i=0;$i<count($aTov);$i++)
								{
								 ?>
								 <a class="list" href="#">
								        <?if(isset($aTov[$i]->pic[0]->thumbnail)){?>
								        <img src="<?=$aTov[$i]->pic[0]->thumbnail;?>" class="span2" style="display: inline-block;max-height:85px;">
										<?}?>
								        <div class="list-content" style="display: inline-block;max-width:550px;">
										     
                                            <span class="list-title"><?=$aTov[$i]->name_ru?></span>
                                            <span class="list-remark">Цена : <?=$aTov[$i]->Price?> грн.</span>
											<span class="list-remark">На складе : <?=$aTov[$i]->ostatok?></span>
											<span class="list-remark">Код : <?=$aTov[$i]->product_code?></span>
											<span class="list-remark">1C Код : <?=$aTov[$i]->code_1c?></span>
									    </div>
                                 </a>
								 <?
								}
								?>
                                    
                                   
                                </div>
                            </div>
                                <?
							
								?>
                            </div>
                          
                        </div>
                   

                

              


               

                <h5>Запрос для получения списка категорий</h5>
                <pre class="prettyprint linenums"> <?=$qCat;?></pre>
                <h5>Запрос для получения списка товаров</h5>
                <pre class="prettyprint linenums"> <?=$qTov;?></pre>
    </div>

    <script src="js/hitua.js"></script>

</body>
</html>