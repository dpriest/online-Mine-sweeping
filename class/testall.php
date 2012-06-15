<?php
header("Content-Type: text/html; charset=utf-8");

require_once("./mysql_class.php");
require_once("./battle.php");
//require_once("./user.php");
//require_once("./challenge.php");
//$user = new User();
//$challenger =  new Challenger();
$battle = new Battle();
//var_dump($challenger->ifrank(6));
var_dump($battle->recordresult(2,3,1));
?>