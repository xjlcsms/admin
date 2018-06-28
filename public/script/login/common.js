//背景
(function(){
	//定义画布宽高和生成点的个数
	var WIDTH, HEIGHT, POINT;
	var canvas,context;
	var circleArr = [];
	var hander = null;

	//线条：开始xy坐标，结束xy坐标，线条透明度
	function Line (x, y, _x, _y, o) {
		this.beginX = x,
		this.beginY = y,
		this.closeX = _x,
		this.closeY = _y,
		this.o = o;
	}
	//点：圆心xy坐标，半径，每帧移动xy的距离
	function Circle (x, y, r, moveX, moveY) {
		this.x = x,
		this.y = y,
		this.r = r,
		this.moveX = moveX,
		this.moveY = moveY;
	}
	//生成max和min之间的随机数
	function num (max, _min) {
		var min = arguments[1] || 0;
		return Math.floor(Math.random()*(max-min+1)+min);
	}
	// 绘制原点
	function drawCricle (cxt, x, y, r, moveX, moveY) {
		var circle = new Circle(x, y, r, moveX, moveY)
		cxt.beginPath()
		cxt.arc(circle.x, circle.y, circle.r, 0, 2*Math.PI)
		cxt.closePath()
		cxt.fill();
		return circle;
	}
	//绘制线条
	function drawLine (cxt, x, y, _x, _y, o) {
		var line = new Line(x, y, _x, _y, o)
		cxt.beginPath()
		cxt.strokeStyle = 'rgba(0,0,0,'+ o +')'
		cxt.moveTo(line.beginX, line.beginY)
		cxt.lineTo(line.closeX, line.closeY)
		cxt.closePath()
		cxt.stroke();
	}
	//初始化生成原点
	function init () {
		circleArr = [];
		for (var i = 0; i < POINT; i++) {
			circleArr.push(drawCricle(context, num(WIDTH), num(HEIGHT), num(15, 2), num(10, -10)/40, num(10, -10)/40));
		}
		draw();
	}
	//每帧绘制
	function draw () {
		context.clearRect(0,0,canvas.width, canvas.height);
		for (var i = 0; i < POINT; i++) {
			drawCricle(context, circleArr[i].x, circleArr[i].y, circleArr[i].r);
		}
		for (var i = 0; i < POINT; i++) {
			for (var j = 0; j < POINT; j++) {
				if (i + j < POINT) {
					var A = Math.abs(circleArr[i+j].x - circleArr[i].x),
						B = Math.abs(circleArr[i+j].y - circleArr[i].y);
					var lineLength = Math.sqrt(A*A + B*B);
					var C = 1/lineLength*7-0.009;
					var lineOpacity = C > 0.03 ? 0.03 : C;
					if (lineOpacity > 0) {
						drawLine(context, circleArr[i].x, circleArr[i].y, circleArr[i+j].x, circleArr[i+j].y, lineOpacity);
					}
				}
			}
		}
	}
	//调用执行
	canvas = document.getElementById('bgCanvas');
	if(canvas.getContext){	
		window.onresize = window.onload = function () {
			WIDTH = document.documentElement.clientWidth, HEIGHT = document.documentElement.clientHeight;
			POINT = Math.floor(WIDTH/48);
			canvas.height = HEIGHT;
			canvas.width = WIDTH,
			context = canvas.getContext('2d');
			context.strokeStyle = 'rgba(0,0,0,0.02)',
			context.strokeWidth = 1,
			context.fillStyle = 'rgba(0,0,0,0.05)';
			init();
			hander && clearInterval(hander);
			hander = setInterval(function () {
				for (var i = 0; i < POINT; i++) {
					var cir = circleArr[i];
					cir.x += cir.moveX;
					cir.y += cir.moveY;
					if (cir.x > WIDTH) cir.x = 0;
					else if (cir.x < 0) cir.x = WIDTH;
					if (cir.y > HEIGHT) cir.y = 0;
					else if (cir.y < 0) cir.y = HEIGHT;	
				}
				draw();
			}, 16);
		}
	}
})();

//placeholder
(function(color){
    var doc = window.document, input = doc.createElement('input');
    if( typeof input['placeholder'] == 'undefined' ){
        $('input[type=text]').each(function(ele) {
            var $this = $(this);
            var ph = $this.attr('placeholder');
            if( ph && $this.val()==""||$this.val()==ph) { //第二个条件防止浏览器缓存当前文档内容
                $this.css({'color':color}).val(ph);
            }
            $this.on({
                focus:function(){
                    if( $this.val() === ph) {
                        $this.val("").removeAttr("style");
                    }
                },
                blur:function() {
                    if( $this.val()=="" ) {
                        $this.css({'color':color}).val(ph);
                    }
                }
            });
        });
        //对password框的特殊处理1.创建一个text框 2获取焦点和失去焦点的时候切换
        $("input[type=password]").each(function(){
            var $this = $(this);
            var ph = $this.attr('placeholder');
            var $ph = $('<input class="form-control" style="color:'+color+';" type="text" value='+ph+' autocomplete="off" />').css({'display':'block'});
            $this.after($ph).hide();
            $ph.focus(function(){
                $ph.hide();
                $this.show().focus();
            });
            $this.blur(function(){
                if($this.val()==''){
                    $ph.show();
                    $this.hide();
                }
            });
        });        
    }
})('#999');

//表单处理
(function(){
	var $module = $('.mod-form');
    //number输入框特殊处理
    $module.on('input propertychange','input[type="number"]',function(){
        var $this = $(this);
        var value = $this.val();
        var maxlength = +$this.attr('maxlength');
        if(value.length>maxlength){
            $this.val(value.substr(0,maxlength));
        }
    });
    //去除报错信息
    $module.on('click','.input-wrapper',function(){
		var $this = $(this);
		var $error = $this.find('.error');
		$error.removeClass('is-visible');
		setTimeout(function(){
			$error.remove();
			$this.find('input').focus();
		},500);
	});
})();