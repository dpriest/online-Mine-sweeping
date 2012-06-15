var MineBox  = function(mineGame,dom,position){
	this.mineGame = mineGame;
	this.dom = $(dom);
	this.position = position;
	this.relativeBoxes = new Array();
	this.hasBomb = false;
	this.STATE.changeState(this, this.STATE.NORMAL);
};
MineBox.prototype = {
	STATE:{
        NORMAL:{
            value:1,
            update:function(mine){
                mine.dom.html('&nbsp;');
            }
        },
        EXPLOSION:{
            value:2,
            update:function(mine){
                mine.dom.html('雷');
            }
        },
        MARKED:{
            value:3,
            update:function(mine){
                mine.dom.html('*');
            }
        },
        UNSURE:{
            value:4,
            update:function(mine){
                mine.dom.html('?');
            }
        },
        COMPLETE:{
            value:5,
            update:function(mine){
                mine.showResult();
            }
        },
        changeState:function(mine,state){
            mine.state = state;
            state.update(mine);
        }
    },
	init:function(){
        this.number = this.mineGame.getMineBoxNumber(this);
        this.enable = true;
        this.dom.addClass('enabled');
		this.dom.bind('click',{_this:this},this.leftClickEventHandler);
		this.dom.bind('mousedown',this.mousedownEventHandler);
    },
    leftClickEventHandler:function(e){
        if(!e.data._this.enable) return;
		if(e.data._this.mineGame.firstClick){
			e.data._this.mineGame.startTimer();
			e.data._this.mineGame.firstClick = false;
		}
		date2 = new Date().getTime();
        if(e.data._this.state == e.data._this.STATE.NORMAL){	
			if(date2 - date1 > 500){
				e.data._this.STATE.changeState(e.data._this,e.data._this.STATE.MARKED);	
			}else{
				e.data._this.STATE.changeState(e.data._this,e.data._this.STATE.COMPLETE);
			}
        }else if(e.data._this.state == e.data._this.STATE.MARKED){
			if(date2 - date1 > 500){
				e.data._this.STATE.changeState(e.data._this, e.data._this.STATE.UNSURE);
			}
        }else if(e.data._this.state == e.data._this.STATE.UNSURE){
			if(date2 - date1 > 500){
				e.data._this.STATE.changeState(e.data._this, e.data._this.STATE.NORMAL);
			}
        }
    },
	mousedownEventHandler:function(e){
		date1 = new Date().getTime();
	},
    showResult:function(){
		var _this = this;
		this.enable = false;
		this.dom.removeClass('enabled');
		this.dom.addClass('disabled');
        if(this.hasBomb){
            this.STATE.changeState(this, this.STATE.EXPLOSION);
            this.mineGame.gameover();					
        }else{
			this.dom.html(this.number);
			if(this.number ===  '&nbsp;'){
				$.each(this.relativeBoxes,function(i,mineBox){
					if(!mineBox.enable)return;
					mineBox.showResult();
				});
			}
            if(this.mineGame.enable)this.mineGame.refresh();
        }
       
    }
	
}