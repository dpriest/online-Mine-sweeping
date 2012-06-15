<?php 
if(!isset($_COOKIE['userid'])){
	echo("请先登录！");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>扫雷游戏--房间列表</title>
<link type="text/css" rel="stylesheet" href="./css/reset.css" />
<link type="text/css" rel="stylesheet" href="./css/common.css" />
<script type="text/javascript" src="./js/jquery-1.7.2.min.js"></script>
<script>
jQuery(function($){
	setInterval(fleshnum,5000);
});

function fleshnum(){
	$.ajax({
			type: "POST",
			url: "roomlisthandle.php",
			data: "action=fleshnum",
			success: function(msg){
				msg = $.parseJSON(msg);
				for(var i in msg){
					$("#num"+i).html(msg[i]);
					if(msg[i] < 2){
						$("#state"+i).html("可进");
					}else{
						$("#state"+i).html("已满");
					}
				}
			}
	});
}
</script>
</head>

<body>
	<div id="main">
		<div id="rank">
			<div class="title">房间列表</div>
			<table>
				<tr><td>房间号</td><td>人数</td><td>状态</td></tr>
				<?php 
					require_once("./class/mysql_class.php");
					require_once("./class/battle.php");
					$battle = new Battle();
					$roomlist = $battle->getroomlist();
					$i = 1;
					foreach($roomlist as $room){
				?>
				<tr><td><a href="room.php?id=<?=$room['roomid']?>">房间<?=$i?></a></td><td><span id="num<?=$i?>"><?=$room['num']?></span>/2</td><td><span id ="state<?=$i?>"><?if($room['num'] < 2){echo"可进";}else{echo"已满";}?></span></td></tr>
				<?php
					$i++;
					}
				?>
			</table>
		</div>
	</div>
</body>
</html>
