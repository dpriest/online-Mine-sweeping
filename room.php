<?php 
header("Content-Type: text/html; charset=utf-8");

if(!isset($_GET['id'])){
	exit;
}
if(!isset($_COOKIE['userid'])){	
	echo("请先登录！");
	exit;
}
require_once("./class/mysql_class.php");
require_once("./class/battle.php");
require_once("./class/user.php");
$user = new User();
$battle = new Battle();
$battle->uposition = $battle->findposition($_GET['id'],$_COOKIE['userid']);
if($battle->iffull($_GET['id']) && $battle->uposition == 0){
	echo("房间已满！");
	exit;
}
if($battle->ifinotherroom($_GET['id'],$_COOKIE['userid'])){
	echo("你已进入其他房间,不允许同时进入多个房间");
	exit;
}elseif($battle->ifinthisroom($_GET['id'],$_COOKIE['userid'])){
	
}else{
	$battle->recordroom($_COOKIE['userid'],$_GET['id']);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>扫雷游戏--房间<?=$_GET['id']?></title>
<link type="text/css" rel="stylesheet" href="./css/style.css" />
<link type="text/css" rel="stylesheet" href="./css/common.css" />
<script type="text/javascript" src="./js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="./js/MineGame.js"></script>
<script type="text/javascript" src="./js/MineBox.js"></script>
<script>
jQuery(function($){
	var updatetimer = setInterval(updatestate,1000);
	var ready = false;
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
					
						$("#seconds").html('7');
						$("#clicker").css({"visibility":"visible"});
						$("#readybtn").hide();
						clearInterval(updatetimer);
						var i = 7;
						var	timer = setInterval(function(){
							i--;
							$("#seconds").html(i);
							if(i <=0){
								clearInterval(timer);
								resulttimer = setInterval(updateresult,2000);
								$("#main").hide();
								var position = "<?=$battle->uposition?>";
								console.log(position);
								if(position == '1'){
									$(".userinfo").html("当前游戏者："+msg['username1']+"<br/>对手："+msg['username2']);
								}else{
									$(".userinfo").html("当前游戏者："+msg['username2']+"<br/>对手："+msg['username1']);
								}
								$("#main2").show();
								battlegame();
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
		var game = new MineGame(9,9,10,$("#gamebox"));
		game.onGameStart.push(function(){
						//alert('游戏开始');
		});
		game.onGameOver.push(function(){
			recordlose();
			//alert('你挂了！');
				
		});
		game.onGameComplete.push(function(){
			recordwin();
			//alert('你完成了！');
		});
		game.start();
	}
	
	function updateresult(){
		$.ajax({
			type: "POST",
			url: "battlehandle.php",
			data: "action=updateresult&roomid=<?=$_GET['id']?>",
			success:function(msg){
				if(msg == 'outline'){
					alert("对方已离线，游戏结束");
					clearInterval(resulttimer);							
					record0();
					updatetimer = setInterval(updatestate,1000);
					ready = !ready;
					
					updatestate();		
					$("#main2").hide();
					$("#readybtn").html("准备");
					$("#readybtn").show();						
					$("#challenger1").html("");
					$("#challenger2").html("");
					$("#clicker").css({"visibility":"hidden"});
					$("#main").show();
				}else if(msg == 'win'){
					alert("你赢了");
					clearInterval(resulttimer);							
					record0();
					updatetimer = setInterval(updatestate,1000);
					ready = !ready;		
					updatestate();		
					$("#main2").hide();
					$("#readybtn").html("准备");
					$("#readybtn").show();						
					$("#challenger1").html("");
					$("#challenger2").html("");
					$("#clicker").css({"visibility":"hidden"});
					$("#main").show();
				}else if(msg == 'lose'){
					alert("你输了");
					clearInterval(resulttimer);							
					record0();
					updatetimer = setInterval(updatestate,1000);
					ready = !ready;
					
					updatestate();		
					$("#main2").hide();
					$("#readybtn").html("准备");
					$("#readybtn").show();						
					$("#challenger1").html("");
					$("#challenger2").html("");
					$("#clicker").css({"visibility":"hidden"});
					$("#main").show();
				}else if(msg == 'pingshou'){
					alert("你们平手");
					clearInterval(resulttimer);							
					record0();
					updatetimer = setInterval(updatestate,1000);
					ready = !ready;			
					updatestate();		
					$("#main2").hide();
					$("#readybtn").html("准备");
					$("#readybtn").show();						
					$("#challenger1").html("");
					$("#challenger2").html("");
					$("#clicker").css({"visibility":"hidden"});
					$("#main").show();
				}
			}
		});
	}
	function recordwin() {
		$.ajax({
			type: "POST",
			url: "battlehandle.php",
			data: "action=recordwin&roomid=<?=$_GET['id']?>",
			success:function(msg){
			}
		});
	}
	function recordlose() {
		$.ajax({
			type: "POST",
			url: "battlehandle.php",
			data: "action=recordlose&roomid=<?=$_GET['id']?>",
			success:function(msg){
			}
		});
	}
	function record0(){
		$.ajax({
			type: "POST",
			url: "battlehandle.php",
			data: "action=record0&roomid=<?=$_GET['id']?>",
			success:function(msg){
				console.log(msg);
			}
		});
	}
});

</script>
</head>

<body>
	<div id="main">
		<div id="rank">
			<div class="title">房间<?=$_GET['id']?></div>
			
			<div class="challenger left2" id="challenger1">
			<?php
				$battles = $battle->getbattlelist($_GET['id']);
				if($battles['uid1'] != NULL){
			?>
				<div class="touxiang">
					<img src="./images/touxiang/<?=rand(1,7)?>.jpg"/>
				</div>
				<div class="info">
					对战者1：<span id="battle1"><?=$user->getusername($battles['uid1'])?></span><br/>
					状态：<span id="state1"><?if($battles['state1'] == 1){echo"已准备";}else{echo "未准备";}?></span>
				</div>
			<?
				}
			?>
			</div>
			<div class="challenger right2" id="challenger2">
			<?if($battles['uid2'] != NULL){?>
				<div class="info">
					对战者2：<span id="battle2"><?=$user->getusername($battles['uid2'])?></span><br/>
					状态：<span id="state2"><?if($battles['state2'] == 1){echo"已准备";}else{echo "未准备";}?></span>
				</div>
				<div class="touxiang">
					<img src="./images/touxiang/<?=rand(1,7)?>.jpg"/>
				</div>
			<?}?>
			</div>
			<div id="clicker" style="visibility:hidden;">
				倒计时：<span id="seconds"></span>秒
			</div>
			<a href="#" id="readybtn">
				准备
			</a>
		</div>
	</div>
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
