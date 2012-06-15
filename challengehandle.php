<?php
require_once("./class/mysql_class.php");
require("./class/challenge.php");
$challenger = new Challenger();
if($_POST['action'] == 'getwintimes'){
	echo $challenger->wintimes($_COOKIE['userid']);
}elseif($_POST['action'] == 'rank'){
	$seconds = $_POST['seconds'];
	if($challenger->ifrank($seconds)&&$challenger->recorddata($_COOKIE['userid'],$seconds)){
		echo 1;
	}else{
		echo 0;
	}
}elseif($_POST['action'] == 'challenge'){
	$cuid = $_POST['cuid'];
	$cseconds = intval($_POST['cseconds']);
	$seconds = intval($_POST['seconds']);
	
	$result = $challenger->getresult($seconds,$cseconds);
	//echo $cseconds."|".$seconds."|".$result;
	echo $result;
	$challenger->recorddata($_COOKIE['userid'],$seconds);
	$challenger->recordresult($_COOKIE['userid'],$cuid,$result);
}
?>