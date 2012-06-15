<?php 
header("Content-Type: text/html; charset=utf-8");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>扫雷游戏--房间<?=$_GET['id']?></title>
<link type="text/css" rel="stylesheet" href="./css/common.css" />
<link type="text/css" rel="stylesheet" href="./css/style.css" />
<script type="text/javascript" src="./js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="./js/MineGame.js"></script>
<script type="text/javascript" src="./js/MineBox.js"></script>
<script>
jQuery(function($){
	//var updatetimer = setInterval(updatestate,5000);
	var ready = false;
	$("#main").hide();
	$("#main2").show();
	battlegame();
	$.ajax({
			type: "POST",
			url: "battlehandle.php",
			data: "action=testonline",
			success: function(msg){
				console.log("dd");
			}
	});
	function updatestate(){
		$.ajax({
				type: "POST",
				url: "battlehandle.php",
				data: "action=updatestate&roomid=<?=$_GET['id']?>",
				success: function(msg){
					msg = $.parseJSON(msg);
					if(msg['result1'] == 0){
						$("#challenger1").html("");
					}else if(msg['result1'] == 1){
						var state1 = parseInt(msg['state1']) ? "已准备" : "未准备";
						if( $.trim($("#challenger1").html()) != ""){
							$("#battle1").html(msg['username1']);
							$("#state1").html(state1);
						}else{
							$("#challenger1").html('<div class="touxiang"><img src="./images/touxiang/<?=rand(1,7)?>.jpg"/></div><div class="info">对战者1：<span id="battle1">'+msg['username1']+'</span><br/>状态：<span id="state1">'+state1+'</span></div>');
						}
					}
					if(msg['result2'] == 0){
						$("#challenger2").html("");
					}else if(msg['result2'] == 1){
						var state2 = parseInt(msg['state2']) ? "已准备" : "未准备";
						if( $.trim($("#challenger2").html()) != ""){
							$("#battle2").html(msg['username2']);
							$("#state2").html(state2);
						}else{
							$("#challenger2").html('<div class="info">对战者2：<span id="battle1">'+msg['username2']+'</span><br/>状态：<span id="state2">'+state2+'</span></div><div class="touxiang"><img src="./images/touxiang/<?=rand(1,7)?>.jpg"/></div>');
						}
					}
					if(msg['result1'] == 1 && msg['result2'] == 1 && msg['state1'] == 1 &&  msg['state2'] == 1){
						//var position = 1;
						//var tplayer = (position == 1) ? msg['username1'] ：msg['username2'] ;
						//var oplayer = (position == 2) ? msg['username1'] ：msg['username2'] ;
						//$(".userinfo").html("当前游戏者："+tplayer+"<br/>对手："+oplayer);
						$("#clicker").css({"visibility":"visible"});
						$("#readybtn").hide();
						clearInterval(updatetimer);
						var i = 7;
						var	timer = setInterval(function(){
							i--;
							$("#seconds").html(i);
							if(i <=0){
								clearInterval(timer);
								
								
							}
						},1000);
					}
				}
		});
	}
	$("#readybtn").click(function(){
		if(ready){
			var readystate = 0;
		}else{
			var readystate = 1;
		}
		$.ajax({
			type: "POST",
			url: "battlehandle.php",
			data: "action=updateuserstate&roomid=<?=$_GET['id']?>&uposition=<?=$battle->uposition?>&state="+readystate,
			success: function(msg){
					if(msg == 1){
						updatestate();
						ready = !ready;
						if(ready){
							$("#readybtn").html("取消");
						}else{
							$("#readybtn").html("准备");
						}
						
					}
			}
			
		});
		return false;
	});
	function battlegame(){
		var game = new MineGame(9,9,3,$("#gamebox"));
		game.onGameStart.push(function(){
						//alert('游戏开始');
		});
		game.onGameOver.push(function(){
						alert('你挂了！');
		});
		game.onGameComplete.push(function(){});
		game.start();
	}
});

</script>
</head>

<body>
	
	<div id="main2" style="height:450px;display:none;">
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
