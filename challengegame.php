<?php if(!isset($_COOKIE['userid'])){
	echo("请先登录！");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>扫雷游戏</title>

<link type="text/css" rel="stylesheet" href="./css/common.css" />
<link type="text/css" rel="stylesheet" href="./css/style.css" />
<script type="text/javascript" src="./js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="./js/MineGame.js"></script>
<script type="text/javascript" src="./js/MineBox.js"></script>
<script>
jQuery(function($){
	$.ajax({
		type: 'post',
		url: 'challengehandle.php',
		data: 'action=getwintimes',
		success: function(msg){
			$(".userinfo").html("当前游戏者:<?=$_COOKIE['username']?>,挑战成功"+msg+"次");
			if(getUrlVar('cuid')&&getUrlVar('cseconds')){
				$(".userinfo").append("<br/>挑战"+getUrlVar('cuname')+"的记录"+getUrlVar('cseconds')+"秒");
			}else{
				$(".userinfo").append("<br/>挑战是否能上榜");
			}
		}
	});
	var game = new MineGame(9,9,10,$("#gamebox"));
	game.onGameStart.push(function(){
                    //alert('游戏开始');
	});
	game.onGameOver.push(function(){
                    alert('你触雷了！');
	});
	game.onGameComplete.push(function(){
		var seconds = $("#time").html();
		if(getUrlVar('cuid')&&getUrlVar('cseconds')){
			$.ajax({
					type: "POST",
					url: "challengehandle.php",
					data:"action=challenge&seconds="+$("#time").html()+"&cuid="+getUrlVar('cuid')+"&cseconds="+getUrlVar('cseconds'),
					success: function(msg){
						if(msg == 1){
							if(confirm("挑战成功,查看排行榜？")){
								window.open("onlinerank.php");
							}
						}else if(msg == -1){
							alert("挑战失败");
						}else{
							alert("你们平手");
						}
					}
			
			});
		}else{
			$.ajax({
					type: "POST",
					url: "challengehandle.php",
					data:"action=rank&seconds="+$("#time").html(),
					success: function(msg){
						if(msg == 1){
							if(confirm("挑战成功,查看排行榜？")){
								window.open("onlinerank.php");
							}
						}else{
							alert("挑战失败");
						}
					}
			
			});
		}
	});
	game.start();
	$("#restart").click(function(){
		window.location.reload();
	});
});
function getUrlVar(key){
    return (document.location.search.match(new RegExp("(?:^\\?|&)"+key+"=(.*?)(?=&|$)"))||['',null])[1];
}

</script>
</head>

<body>
	<div id="main" style="height:450px;">
		<div class="userinfo" style="height:54px;"></div>
		<div id="gamebox">	
		</div>
		<div id="timebox">
			时间：<span id="time">0</span>   雷数：<span id="minenums">0</span>
			<a href="#" id="restart" title="继续挑战"><img src="./images/logo.png" style="width:40px;height:32px;"></a>
			<a href="index.html" title="新游戏">返回主页</a>
		</div>
		<div id="gametips">
		(游戏提示：长按超过0.5秒为标记雷*，点击为打开方格，从点击第一个方格开始计时)
		</div>
	</div>
</body>
</html>
