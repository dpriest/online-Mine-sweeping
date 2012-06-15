<?php
require_once("./class/mysql_class.php");
require("./class/user.php");
$user = new User();
if($_POST['action'] == 'checklogin'){
	if($user->checklogin()){
		$msg['result'] = 1;
		$msg['username'] =  $_COOKIE['username'];
	}else{
		$msg['result'] = 0;
	}
	echo json_encode($msg);
}elseif($_POST['action'] == 'logout'){
	$user->logout();
}elseif($_POST['action'] == 'login'){
	$username = $_POST['username'];
	$pass = $_POST['password'];
	if(!$user->ifexist($username)){
		$msg['result'] = 0;
	}elseif($user->login($username,$pass)){
		$msg['result'] = 2;
	}else{
		$msg['result'] = 1;
	}
	echo json_encode($msg);
}elseif($_POST['action'] == 'register'){
	$username = $_POST['username'];
	$pass = $_POST['password'];
	$email = $_POST['email'];
	if($user->ifexist($username)){
		$msg['result'] = 0;
	}elseif($user->register($username,$pass,$email)){
		$user->login($username,$pass);
		$msg['result'] = 2;		
	}else{
		$msg['result'] = 1;
	}
	echo json_encode($msg);
}

?>