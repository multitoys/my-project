<?php
ini_set("display_errors","1");
include('CToys.php');


if(@$_GET['api']=="info"){include('info.php');die();}

$id=1;
$sName="Главная";
if(isset($_GET['idcat']))$id=$_GET['idcat'];
if(isset($_GET['name']))$sName=$_GET['name'];
$aCat=Toys::getCategory($id);
$qCat=Toys::$query;
$aTov=Toys::getTovar($id);
$qTov=Toys::$query;

include('view.php');
?>