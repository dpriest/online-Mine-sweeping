<?php
require_once("./class/mysql_class.php");
require_once("./class/battle.php");
require_once("./class/user.php");
require_once("./class/memcached.php");
$battle = new Battle();
$battle->testonline();
if($_POST['action'] == 'updatestate'){
	$user = new User();
	$battlelist = $battle->getbattlelist($_POST['roomid']);
	if($battlelist['uid1'] == NULL){
		$msg['result1'] = 0;
	}else{
		$msg['result1'] = 1;
		$msg['username1'] = $user->getusername($battlelist['uid1']);
		//$msg['state1'] = $battlelist['state1'] ? "已准备" : "未准备";
		$msg['state1'] = $battlelist['state1'];
	}
	if($battlelist['uid2'] == NULL){
		$msg['result2'] = 0;
	}else{
		$msg['result2'] = 1;
		$msg['username2'] = $user->getusername($battlelist['uid2']);
		//$msg['state2'] = $battlelist['state2'] ? "已准备" : "未准备";
		$msg['state2'] = $battlelist['state2'];
	}
	echo json_encode($msg);
}elseif($_POST['action'] == 'updateuserstate'){
	$roomid = $_POST['roomid'];
	$state =  $_POST['state'];
	$position = $_POST['uposition'];
	if($battle->updatestate($roomid,$state,$position)){
		$msg = 1;
	}else{
		$msg = 0;
	}
	echo $msg;
}elseif($_POST['action'] == 'updateresult'){
	$roomid = $_POST['roomid'];
	$uid = $_COOKIE['userid'];
	echo $battle->sendresult($roomid,$uid);
}elseif($_POST['action'] == 'recordwin'){
	$roomid = $_POST['roomid'];
	$uid = $_COOKIE['userid'];
	$position = $battle->findposition($roomid,$uid);
	$battle->updatestate($roomid,2,$position);
	$battlelist = $battle->getbattlelist($roomid);
	if($battlelist['state1'] == 2 && $battlelist['state2'] == 2){
		$result = 0;
	}
	elseif($uid == $battlelist['uid2'] ){
		$result = 1;
	}else{
		$result = -1;
	}
	$battle->recordresult($battlelist['uid1'],$battlelist['uid2'],$result);
}elseif($_POST['action'] == 'recordlose'){
	$roomid = $_POST['roomid'];
	$uid = $_COOKIE['userid'];
	$position = $battle->findposition($roomid,$uid);
	if($position == 1){
		$position = 2;
	}elseif($position == 2){
		$position = 1;
	}
	$battle->updatestate($roomid,2,$position);
	$battlelist = $battle->getbattlelist($roomid);
	if($battlelist['state1'] == 2 && $battlelist['state2'] == 2){
		$result = 0;
	}
	elseif($uid == $battlelist['uid2'] ){
		$result = 1;
	}else{
		$result = -1;
	}
	$battle->recordresult($battlelist['uid1'],$battlelist['uid2'],$result);
}elseif($_POST['action'] == 'record0'){
	$roomid = $_POST['roomid'];
	$battle->updatestate($roomid,0,1);
	$battle->updatestate($roomid,0,2);
}
?>