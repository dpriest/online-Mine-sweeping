<?php
class Battle{
	public $db;
	public $memcache;
	public $uposition = 0;
	public function __construct(){
		$this->db =  new mysql('localhost','root','sushi','saolei',"utf-8");
	}
	
	public function getroomlist(){
		$sql = "SELECT * FROM  `room` ";
		$query = $this->db->query($sql);
		while($r = $this->db->fetch_array($query)){
			$room['roomid'] = $r['id'];
			$room['num'] = $r['num'];
			$roomlist[] = $room;
		}
		return $roomlist;
	}
	
	public function iffull($roomid){
		$sql = "SELECT * FROM  `room` where `id`=".$roomid;
		$query = $this->db->query($sql);
		$r = $this->db->fetch_array($query);
		if($r['num'] == 2){
			return true;
		}else{
			return false;
		}	
	}
	
	public function ifinotherroom($roomid,$uid){
		$sql = "SELECT * FROM  `room` where `id`!=".$roomid." and (`uid1` = ".$uid." or `uid2` = ".$uid.")";
		$query = $this->db->query($sql);
		//var_dump($this->db->fetch_array($query));
		if($this->db->num_rows($query) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	public function ifinthisroom($roomid,$uid){
		$sql = "SELECT * FROM  `room` where `id` = ".$roomid." and (`uid1` = ".$uid." or `uid2` = ".$uid.")";
		$query = $this->db->query($sql);
		if($this->db->num_rows($query) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	public function recordroom($uid,$roomid){
		$sql = "SELECT * FROM  `room` where `id` = ".$roomid;
		$query = $this->db->query($sql);
		$r = $this->db->fetch_array($query);
		if($r['uid1'] == NULL && $r['uid2'] != $uid){
			$sql = "UPDATE  `saolei`.`room` SET  `uid1` =  '".$uid."' ,`num` = num + 1 ,`state1` = 0 WHERE  `room`.`id` =".$roomid;
			$this->db->query($sql);
			$this->uposition = 1;
		}elseif($r['uid2'] == NULL && $r['uid1'] != $uid){
			$sql = "UPDATE  `saolei`.`room` SET  `uid2` =  '".$uid."' ,`num` = num + 1 ,`state2` = 0 WHERE  `room`.`id` =".$roomid;
			$this->db->query($sql);
			$this->uposition = 2;	
		}
	}
	
	public function updatestate($roomid,$state,$position){
		$sql = "UPDATE  `saolei`.`room` SET  `state".$position."` = ".$state." WHERE  `room`.`id` =".$roomid;
		return $this->db->query($sql);
	}
	
	public function getbattlelist($roomid){
		$sql = "SELECT * FROM  `room` WHERE  `id` =".$roomid;
		$query = $this->db->query($sql);
		return $this->db->fetch_array($query);
	}
	
	public function getwintimes(){
		
	}
	
	public function testonline(){
		$this->memcache = Memcached::singleton();
		$uid = md5($_COOKIE['userid']);
		$this->memcache->set($uid,time());
		$query = "select * from room";
		$q = $this->db->query($query);
		while($r = $this->db->fetch_array($q)){
			if($r['uid1'] != NULL){
				$uidarr1[] = $r['uid1'];
			}
			if($r['uid2'] != NULL){
				$uidarr2[] = $r['uid2'];
			}
		}
		if($uidarr1){
			foreach($uidarr1 as $onlineuid){
				//var_dump($onlineuid);
				$jiange = time() - $this->memcache->get(md5($onlineuid)) ;
				//echo $memcache->get(md5($onlineuid)).":".$jiange."<br/>";
				if(time() - $this->memcache->get(md5($onlineuid)) > 30){
					$sql = "UPDATE  `saolei`.`room` SET  `uid1` =  NULL ,`num` = num - 1 ,`state1` = NULL WHERE  `room`.`uid1` =".$onlineuid;
					$this->db->query($sql);
				}
			}
		}
		if($uidarr2){
			foreach($uidarr2 as $onlineuid){
				//var_dump($onlineuid);
				$jiange = time() - $this->memcache->get(md5($onlineuid)) ;
				//echo $memcache->get(md5($onlineuid)).":".$jiange."<br/>";
				if(time() - $this->memcache->get(md5($onlineuid)) > 30){
					$sql = "UPDATE  `saolei`.`room` SET  `uid2` =  NULL ,`num` = num - 1 ,`state2` = NULL WHERE  `room`.`uid2` =".$onlineuid;
					$this->db->query($sql);
				}
			}
		}
	}
	
	public function sendresult($roomid,$uid){
		$query = "select * from room where id=".$roomid;
		$q = $this->db->query($query);
		$r = $this->db->fetch_array($q);
		if($r['uid1'] == $uid){
			if($r['state2'] == NULL){
				$msg['result']="outline";
			}elseif($r['state2'] == 2){
				$msg['result']="lose";
			}elseif($r['state1'] == 2){
				$msg['result']="win";
			}elseif($r['state1'] == 1 && $r['state2'] == 1){
				$msg['result']="palying";
			}else{
				$msg['result']=0;
			}
		}elseif($r['uid2'] == $uid){
			if($r['state1'] == NULL){
				$msg['result']="outline";
			}elseif($r['state1'] == 2 && $r['state2'] == 2){
				$msg['result']="pingshou";
			}elseif($r['state1'] == 2){
				$msg['result']="lose";
			}elseif($r['state2'] == 2){
				$msg['result']="win";
			}elseif($r['state1'] == 1 && $r['state2'] == 1){
				$msg['result']="palying";
			}else{
				$msg['result']=0;
			}
		}
		return $msg['result'];
	}
	
	public function findposition($roomid,$uid){
		$query = "select * from room where id=".$roomid;
		$q = $this->db->query($query);
		$r = $this->db->fetch_array($q);
		if($r['uid1'] == $uid){
			return 1;
		}elseif($r['uid2'] == $uid){
			return 2;
		}else{
			return 0;
		}
	}
	
	public function recordresult($uid,$buid,$result){
		$sql = "INSERT INTO  `saolei`.`battle` (`id` ,`uid` ,`buid` ,`result`)VALUES (NULL ,  '".$uid."',  '".$buid."',  '".$result."')";
		return $this->db->query($sql);
	}
	
	
}
?>