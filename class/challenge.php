<?php

class Challenger{
	public $db;
	
	public function __construct(){
		$this->db =  new mysql('localhost','root','sushi','saolei',"utf-8");
	}
	
	public function recorddata($uid,$seconds){//$seconds int
		$sql = "INSERT INTO  `saolei`.`data` (`id` ,`userid` ,`seconds`)VALUES (NULL ,  '".$uid."',  '".$seconds."')";
		return $this->db->query($sql);
	}
	
	public function recordresult($uid,$cuid,$result){
		$sql = "INSERT INTO  `saolei`.`challenge` (`id` ,`uid` ,`cuid` ,`result`)VALUES (NULL ,  '".$uid."',  '".$cuid."',  '".$result."')";
		return $this->db->query($sql);
	}
	
	public function wintimes($uid){
		$sql = "SELECT * FROM  `challenge` WHERE  `uid` =".$uid." AND  `result` = 1";
		$query = $this->db->query($sql);
		return $this->db->num_rows($query);
	}
	
	public function onlinerank(){
		$sql = "select * from `data`,`user`  where `data`.`userid` = `user`.`id` order by seconds asc,data.id desc limit 0,10";
		$query = $this->db->query($sql);
		while($result = $this->db->fetch_array($query)){
			$r['uid'] = $result['userid'];
			$r['username'] = $result['username'];
			$r['seconds'] = $result['seconds'];
			$onlinerankarr[] = $r;
		}
		return $onlinerankarr;
	}
	
	public function ifrank($seconds){
		$onlinearr = $this->onlinerank();
		if($onlinearr != NULL){
			$lowest = end($onlinearr);
			$lowest = $lowest['seconds'];
		}
		
		if($onlinearr == NULL || $seconds <= $lowest ){
			return true;
		}else{
			return false;
		}
	}
	
	public function getresult($seconds,$cseconds){
		if($seconds < $cseconds){
			return 1;
		}elseif($seconds > $cseconds){
			return -1;
		}else{
			return 0;
		}
	}
}
?>
