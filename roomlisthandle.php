<?php
require_once("./class/mysql_class.php");
require_once("./class/battle.php");
$battle = new Battle();
if($_POST['action'] == 'fleshnum'){
	$roomlist = $battle->getroomlist();
	$i=1;
	foreach($roomlist as $room){
		$arr[$i] = $room['num'];
		$i++;
	}
	echo json_encode($arr);
}
?>