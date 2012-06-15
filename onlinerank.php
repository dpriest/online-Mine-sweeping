<?php if(!isset($_COOKIE['userid'])){
	echo("请先登录！");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>扫雷游戏--用户排行</title>
<link type="text/css" rel="stylesheet" href="./css/reset.css" />
<link type="text/css" rel="stylesheet" href="./css/common.css" />
</head>

<body>
	<div id="main">
		<div id="rank">
			<div class="title">联机用户排行榜</div>
			
			<table id="onlinerank">
				<tr><td>排名</td><td>用户名</td><td>秒数</td><td>操作</td></tr>
				<?php
					require_once("./class/mysql_class.php");
					require_once("./class/challenge.php");
					$challenger =  new Challenger();
					$onlinerankarr = $challenger->onlinerank();
					$i = 1;
					foreach($onlinerankarr as $rank){
				?>
				<tr><td><?=$i?></td><td><?=$rank['username']?></td><td><?=$rank['seconds']?></td>
				<td><a href="challengegame.php?cuid=<?=$rank['uid']?>&cseconds=<?=$rank['seconds']?>&cuname=<?=$rank['username']?>">挑战</a></td></tr>
				<?
					$i++;
					}
				?>
			</table>
			<a href="challengegame.php" id="wantrank">我要上榜</a>
		</div>
	</div>
</body>
</html>
