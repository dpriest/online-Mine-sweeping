var MineGame = function(width,height,mineCount,container){
	this.width = width;
	this.height = height;
	this.mineCount = mineCount;
	this.container = container || document.body;
	this.onGameStart = [];
	this.onGameOver = [];
	this.onGameComplete = [];
	this.completed = 0;
	this.enable = true;
	this.firstClick = true;
};
MineGame.prototype = {
	start:function(){
        var mines = new Array();
        var mineslist = new Array();
        var _this = this;
        
        this.dom = document.createElement('TABLE');
        var tbody = document.createElement('TBODY');
        for(var i = 0; i < this.height; i ++){
            var tr = document.createElement('TR');
            var mineRow = new Array();
            for(var j = 0; j < _this.width; j ++){
                var td = document.createElement('TD');
                tr.appendChild(td);
				var mine = new MineBox(_this,td,[i,j]);
                mineRow.push(mine);
            }
            tbody.appendChild(tr);
            mines.push(mineRow);
            mineslist = mineslist.concat(mineRow);
        }
        
        this.mines = mines;
        this.mineslist = mineslist;
        this.dom.tbody = tbody;
        this.dom.appendChild(tbody);
        this.container.html(this.dom);
        this.dom.className = 'mineGame';
        
		this.generateMap();
		this.init();
    },
    generateMap:function(){
        var _this = this;
        for(var i = 0; i < this.mineCount; i ++){
            var num = _this.mineslist.length;
            var rnd = Math.floor(Math.random() * num);
            var mine = _this.mineslist[rnd];
            mine.hasBomb = true;
            _this.mineslist.splice(rnd,1);
        }
    },
    init:function(){
        $.each(this.mines,function(i,row){
            $.each(row,function(j,mine){
                mine.init();
            });
        });
        $.each(this.onGameStart,function(i,fn){fn();});
		$("#minenums").html(this.mineCount);
    },
    getMineBoxNumber:function(mine){
        if(mine.hasBomb)return -1;
        var x = mine.position[0];
        var y = mine.position[1];
        var count = 0;
        var queue = new Array();
        if(x > 0){
            queue.push(this.mines[x-1][y]);
        }
        if(y > 0){
            queue.push(this.mines[x][y-1]);
        }
        if(x > 0 && y > 0){
            queue.push(this.mines[x-1][y-1]);
        }
        if(x < this.height - 1){
            queue.push(this.mines[x+1][y]);
        }
        if(y < this.width - 1){
            queue.push(this.mines[x][y+1]);
        }
        if(x < this.height - 1 && y < this.width - 1){
            queue.push(this.mines[x+1][y+1]);
        }
        if(x > 0 && y < this.width - 1){
            queue.push(this.mines[x-1][y+1]);
        }
        if(x < this.height - 1 && y > 0){
            queue.push(this.mines[x+1][y-1]);
        }
        $.each(queue,function(i,minebox){
            if(minebox.hasBomb)count ++;
        });
        mine.relativeBoxes = queue;
        if(count == 0)count = '&nbsp;';
        return count;
    },
    gameover:function(){
        if(this.enable == false)return;
        this.enable = false;
        $.each(this.mines,function(i,row){
            $.each(row,function(j,mineBox){
                mineBox.showResult();
            });
        });
        $.each(this.onGameOver,function(i,fn){fn();});
		this.stopTimer();
    },
    refresh:function(){
        this.completed ++;
        if(this.completed == this.width * this.height - this.mineCount){
            $.each(this.onGameComplete,function(i,fn){fn();});
            this.enable = false;
			this.stopTimer();
        }
    },
	startTimer:function(){
		this.timer = setInterval(function(){
			var timeNum =parseInt($("#time").html());
			timeNum ++;
			$("#time").html(timeNum);
		},1000);
	},
	stopTimer:function(){
		clearInterval(this.timer);
	},
	ifRecord:function(seconds){
		if(!window.localStorage) return false;
		seconds = parseInt(seconds);
		storage = window.localStorage;
		storage.lastRankNum = parseInt(storage.lastRankNum) || 0;
		if(storage.lastRankNum < 10 || seconds < parseInt(storage['s'+storage.lastRankNum])){
			return true;
		}
		return false;
	},
	rankRecord:function(name,seconds){
		seconds = parseInt(seconds);
		var last = 	parseInt(storage.lastRankNum);
		if(last == 0){
			storage['n1'] = name;
			storage['s1'] = seconds;
		}else{
				for(var i = last;i > 0;i --){
					if(seconds > storage['s'+i] ){
						break;
					}
				}
				for(var j = Math.min(last + 1,10);j > i + 1;j--){
					storage['n'+j] = storage['n'+(j-1)];
					storage['s'+j] = storage['s'+(j-1)];
				}
				storage['n'+(i+1)] = name;
				storage['s'+(i+1)] = seconds;
		}	
		storage.lastRankNum = Math.min(last + 1,10);
		
	}
};