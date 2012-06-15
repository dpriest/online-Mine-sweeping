<?php
class User{
	
	public $db;
	
	public function __construct(){
		$this->db =  new mysql('localhost','root','sushi','saolei',"utf-8");
	}
	public function checklogin(){
		if(isset($_COOKIE['userid'])){
			return true;
		}else{
			return false;
		}
	}
	
	public function login($username,$pass){
		$sql = "SELECT * FROM  `user` WHERE  `username` LIKE  '".$username."' and password Like '".md5($pass)."'";
		$query = $this->db->query($sql);
		$result = $this->db->fetch_array($query);
		if($this->db->num_rows($query) > 0){
			setcookie("userid", $result['id'], time()+3600*24);
			setcookie("username", $result['username'], time()+3600*24);
			return true;
		}else{
			return false;
		}
	}
	
	public function logout(){
		setcookie("userid", $result['id'], time()-3600);
		setcookie("username", $result['username'], time()-3600);
	}
	
	public function register($username,$pass,$email){
		$sql = "INSERT INTO `saolei`.`user` (`id`, `username`, `password`, `email`) VALUES (NULL, '".$username."', '".md5($pass)."', '".$email."')";
		return $this->db->query($sql);
	}
	
	public function getusername($uid){
		$sql = "SELECT * FROM  `user` WHERE  `id` =".$uid;
		$query = $this->db->query($sql);
		if( $result = $this->db->fetch_array($query)){
			return $result['username'];
		}else{
			return -1;
		}
	}
	
	public function ifexist($username){
		$sql = "SELECT * FROM  `user` WHERE  `username` Like '".$username."'";
		$query = $this->db->query($sql);
		if( $result = $this->db->fetch_array($query)){
			return true;
		}else{
			return false;
		}
	}
}
?>