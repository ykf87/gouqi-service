@extends('default.Sweepstake.app')
@section('title', '抽奖')

@section('body')
<script src="https://cdn.jsdelivr.net/npm/lucky-canvas@1.7.20"></script>
<style type="text/css">
body{
	background: linear-gradient(#19124F, #6054E1, #E771D6);
	color: #000;
	font-size: 100%;
	max-height: 100vh;
}
img{
	max-width: 100%;
	max-height: 100%;
}
.contents{
	padding-top: 1.5rem;
}

.container{
   position: relative;
   width: 80%;
   height: auto;
   border-radius: 50%;
   background: #f7c894;
   display: flex;
   justify-content: center;
   /*overflow: hidden;#6054E1*/
   /*border: 20px solid linear-gradient(blue, red);*/
   padding: 20px;
   background: linear-gradient(#6054E1, #E771D6);
   margin: 0 auto;
   overflow: hidden;
   color: #7F7BAF;
}
.content{
	position: relative;
	width: 100%;
	height: 100%;
	display: flex;
	justify-content: center;
	border-radius: 50%;
	overflow: hidden;
	z-index: 999;
	/*transition: transform 3s cubic-bezier(.2,.93,.43,1);*/
	/*animation: scccontent 240s linear infinite;*/
	/*animation-fill-mode: forwards;*/
}
.content.nomal{
	animation: scccontent 240s linear forwards infinite;
}
.content.starcj{
	/*animation: scccontentDoing .6s linear forwards infinite;*/
	/*transform:rotateZ(0deg);*/
}
.fan-blade{
   position: absolute;
   width: 100px;
   height: 50%;
   clip-path: polygon(50% 100%, 0% 0%, 100% 0%);
   -webkit-clip-path: -webkit-polygon(50% 100%, 0% 0%, 100% 0%);
   transform-origin: 50% 100%;
   text-align: center;
}
.fan-blade .ttxt{
	font-size: 0;
}
.fan-text{
	position: absolute;
	width: 170%;
	height: 100%;
	margin-left: -35%;
	clip-path: polygon(50% 100%, 0% 0%, 100% 0%);
	-webkit-clip-path: -webkit-polygon(50% 100%, 0% 0%, 100% 0%);
	transform-origin: 50% 100%;
	border-radius: 50% 50% 0 0;
	font-size: 1.4rem;
}
.fan-text img{
	max-width: 25%;
	max-height: 25%;
	text-align: center;
	border-radius: 8px;
	overflow: hidden;
	margin-top: 15px;
	/*box-shadow: 0px 0px 15px #777;*/
	margin-bottom: 10px;
}
.fan-text i{
	color: rgba(183,170,249,0.6);
	font-size: 1.4rem;
}
.fan-text > .flex{
	height: 40%;
}
svg{
	overflow: visible;
}
.container .fan-blade:nth-child(1){
    transform: rotateZ(45deg);
}
.container .fan-blade:nth-child(2){
    transform: rotateZ(90deg);
}
.container .fan-blade:nth-child(3){
    transform: rotateZ(135deg);
}
.container .fan-blade:nth-child(4){
    transform: rotateZ(180deg);
}
.container .fan-blade:nth-child(5){
    transform: rotateZ(225deg);
}
.container .fan-blade:nth-child(6){
    transform: rotateZ(270deg);
}
.container .fan-blade:nth-child(7){
    transform: rotateZ(315deg);
}
.container .fan-blade:nth-child(8){
    transform: rotateZ(360deg);
}

.container .fan-blade:nth-child(odd){
    background-color: #B7AAF9;
    color: #fff;
}
.container .fan-blade:nth-child(odd) .fan-text{
	background: #EFE8FD;
}
.container .fan-blade:nth-child(even){
    background-color: #EFE7FC;
}
.container .fan-blade:nth-child(even) .fan-text{
	background: #ffffff;
	color: #9F86D7;
}
.container .fan-blade:nth-child(odd) svg text{
	fill: white;
}
.container .fan-blade:nth-child(odd) .fan-text svg text{
	fill: tan;
}
.container .fan-blade:nth-child(even) svg text{
	fill: #9F86D7;
}

.lights-content{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    border-radius: 50%;
    z-index: 9;
}
.lights{
	position: absolute;
	width: 16px;
	height: 50%;
	transform-origin: 50% 100%;
	z-index: 9;
}
.light{
    position: absolute;
    top: 4px;
    width: 12px;
    height: 12px;
    border-radius: 50% 50% 0 0;
	z-index: 9;
    
}
    
.lights-content .lights:nth-child(1){
    transform: rotateZ(45deg);
}
.lights-content .lights:nth-child(2){
    transform: rotateZ(90deg);
}
.lights-content .lights:nth-child(3){
    transform: rotateZ(135deg);
}
.lights-content .lights:nth-child(4){
    transform: rotateZ(180deg);
}
.lights-content .lights:nth-child(5){
    transform: rotateZ(225deg);
}
.lights-content .lights:nth-child(6){
    transform: rotateZ(270deg);
}
.lights-content .lights:nth-child(7){
    transform: rotateZ(315deg);
}
.lights-content .lights:nth-child(8){
    transform: rotateZ(360deg);
}

.lights-content .lights:nth-child(odd) .light{
    box-shadow: 0 0 7.5px #FCD340;
	background-color: #FCD340;
	animation: lottery_light1 1.4s ease-in-out infinite;
}
.lights-content .lights:nth-child(even) .light{
    box-shadow: 0 0 7.5px #ffffff;
	background-color: #ffffff;
	animation: lottery_light2 1.4s ease-in-out infinite;
}
.lights-content.starcj .lights:nth-child(odd) .light{
    box-shadow: 0 0 7.5px #FCD340;
	background-color: #FCD340;
	animation: lottery_light1 .15s ease-in-out infinite;
}
.lights-content.starcj .lights:nth-child(even) .light{
    box-shadow: 0 0 7.5px #ffffff;
	background-color: #ffffff;
	animation: lottery_light2 .15s ease-in-out infinite;
}

@keyframes scccontent{
	from{
		transform: rotate(0deg);
	}
	to{
		transform: rotate(360deg);
	}
}
@keyframes scccontentDoing{
	0%{
		transform: rotate(0deg);
	}
	100%{
		transform: rotate(720deg);
	}
}

@keyframes lottery_light1 {
	0%{
		box-shadow: 0 0 7.5px #FCD340;
		background-color: #FCD340;
	}
	10%{
		box-shadow: 0 0 7.5px #FCD340;
		background-color: #FCD340;
	}
	40%{
		box-shadow: 0 0 7.5px #ffffff;
		background-color: #ffffff;
	}
	60%{
		box-shadow: 0 0 7.5px #ffffff;
		background-color: #ffffff;
	}
	90%{
		box-shadow: 0 0 7.5px #FCD340;
		background-color: #FCD340;
	}
	100%{
		box-shadow: 0 0 7.5px #FCD340;
		background-color: #FCD340;
	}
}

@keyframes lottery_light2 {
	0%{
		box-shadow: 0 0 7.5px #ffffff;
		background-color: #ffffff;
	}
	10%{
		box-shadow: 0 0 7.5px #ffffff;
		background-color: #ffffff;
	}
	40%{
		box-shadow: 0 0 7.5px #FCD340;
		background-color: #FCD340;
	}
	60%{
		box-shadow: 0 0 7.5px #FCD340;
		background-color: #FCD340;
	}
	90%{
		box-shadow: 0 0 7.5px #ffffff;
		background-color: #ffffff;
	}
	100%{
		box-shadow: 0 0 7.5px #ffffff;
		background-color: #ffffff;
	}
}
.start{
	width: 20%;
	height: 20%;
	border-radius: 50%;
	background: linear-gradient(#E771D6, #6054E1);
	position: absolute;
	left: 40%;
	top: 40%;
	z-index: 99999;
	box-shadow: 0 0 20px #9D61DC;
	/*transition: transform 3s cubic-bezier(.2,.93,.43,1);*/
	/*transition: transform*/
}
.start > .jiantou{
	position: absolute;
	top: -10px;
	width: 0;
	height: 0;
	background: transparent;

	-moz-border-bottom: 15px solid #E771D6;
	-moz-border-left: 15px solid transparent;
	-moz-border-right: 15px solid transparent;

	-khtml-border-bottom: 15px solid #E771D6;
	-khtml-border-left: 15px solid transparent;
	-khtml-border-right: 15px solid transparent;

	-ms-border-bottom: 15px solid #E771D6;
	-ms-border-left: 15px solid transparent;
	-ms-border-right: 15px solid transparent;

	-webkit-border-bottom: 15px solid #E771D6;
	-webkit-border-left: 15px solid transparent;
	-webkit-border-right: 15px solid transparent;

	border-bottom: 15px solid #E771D6;
	border-left: 15px solid transparent;
	border-right: 15px solid transparent;
}
.start > div{
	width: 80%;
	height: 80%;
	margin: 0 auto;
	background: rgba(255,255,255,0.2);
	border-radius: 50%;
	color: #fff;
	font-weight: bold;
	font-size: 18px;
}
.fan-text i.icon-jinbi2{
	color: #FBCE33;
	font-size: 3rem;
	margin-top: 16px;
}
.products{
	/*background: linear-gradient(#E771D6, #6054E1);*/
	margin-top: 40px;
	background: rgba(255,255,255,1);
	box-shadow: 0 -30px 50px #E771D6;
	color: #373737;
	border-radius: 5px 5px 0 0;
	padding-bottom: 50px;
	padding-top: 10px;
}
.products > *{
	width: 49%;
	text-align: center;
	margin: 0px 0 14px 2%;
	box-shadow: 0 0 10px #ccc;
	border-radius: 0 0 6px 6px;
	overflow: hidden;
}
.products > *:nth-child(odd){
	margin-left: 0;
}
.products > *:nth-child(even){
	margin-right: 0;
}
.products .proimg{
	width: 100%;
	height: 170px;
	overflow: hidden;
	background-repeat: no-repeat;
	background-position: center;
	background-size: cover;
}
.products .title{
	margin-top: 5px;
	text-align: left;
	padding: 2px 6px;
	font-weight: bold;
	color: #989898;
}
.stock-price{
	padding: 0 6px;
}
.pro-btns{
	margin-top: 8px;
	height: 30px;
	background: #EFE7FC;
	color: #9F86D7;
	font-size: 1.4rem;
}
.products .sale{
	color: #febb01;
	font-weight: bold;
	text-align: right;
}
.products .sale:before{
	content: "\e616";
	font-family: "iconfont";
	font-size: 1.4rem;
	margin-right: -3px;
}
.nostock{
	background: #ccc;
	color: darkgrey;
}
.tips{
	background: linear-gradient(#9C4BD5, #A3387B);
	position: absolute;
	right: 2px;
	top: 0;
	width: 40px;
	height: auto;
	color: #E78FF3;
	border-radius: 0 0 30% 30%;
	font-size: 1.4rem;
	text-align: center;
	padding: 4px 8px 6px;
	z-index: 99999;
	font-weight: bold;
}
.slogan{
	padding-top: 5rem;
	text-align: center;
	color: #FFFFFF;
	width: 100%;
	position: relative;
	height: 10vh;
}
.slogan img{
	max-width: 70%;
	opacity: .7;
	max-height: 100%;
}
.showgeted{
	position: absolute;
	top: 0;
	left: 0;
	color: #ffffff;
	width: 100%;
	height: 40px;
	overflow: hidden;
}
.showgeted > div{
	display: none;
	transition: all .2s;
	position: absolute;
	left: 100%;
	top: 0;
	width: auto;
	padding: 8px;
}
.showgeted > div.actived{
	display: block;
	animation: showtips .5s ease-in-out forwards;
}
@keyframes showtips{
	form{
		left: 100%;
	}
	to{
		left: 0;
	}
}

.gailv{
	padding: 10px;
	margin-left: 1rem;
	background: rgba(255,255,255,.1);
	border-radius: 0 0 7px 7px;
	opacity: .8;
	color: #ffd60a;
	font-weight: bold;
	margin-top: 1rem;
}
.gailv .ppp{
	font-size: 1.6rem;
	font-weight: bolder;
	margin-top: .2rem;
}
.addadv{
	/*position: absolute;
	right: 10px;
	top: 20%;*/
	margin-right: 1rem;
	opacity: .8;
	color: #ffffff;
	height: 100%;
}
.replace-pro{
	margin-bottom: 1rem;
	color: #999;
}
.replace-pro:last-child{
	margin-bottom: 0;
}
.replace-pro .rep-img{
	width: 40px;
	height: 40px;
	margin-right: .8rem;
}
.replace-pro .rep-img img{
	border-radius: 4px;
	overflow: hidden;
}
</style>
<!-- <div class="header-back">
	<div class="flex v">
		<i class="iconfont icon-back"></i>
		<div class="flex c flex1">
			<div class="title">拼手气赢大奖</div>
		</div>
		<i class="iconfont icon-iconfont-wenhao"></i>
	</div>
	<div class="tips"></div>
</div> -->
<div class="showgeted">
	@foreach ($geted as $get)
	<div class="sdsf">{{$get}}</div>
	@endforeach
</div>
<div class="tips">活动说明</div>
<div class="slogan flex v">
	<div class="gailv">
		<span>运气值</span>
		<div class="ppp"><span class="num" id="upnum">0</span></div>
	</div>
	<div class="flex1 flex v c" style="height: 100%;">
		<img src="/image/bbg.png">
	</div>
	<div class="addadv flex v" onclick="showvideo(1);">
		<i class="iconfont icon-shipintianchong" style="margin-right: 4px;font-size: 2rem;"></i>
		<span style="font-size: 1rem;">看广告<br>涨运气</span>
	</div>
</div>
<div class="contents">
	<div class="aaabbs">
		<div class="container">
			<div class="start flex v c">
				<div class="jiantou"></div>
				<div class="flex v c starttxt">开始</div>
			</div>
			<div class="content nomal"></div>
			<div class="lights-content"></div>
		</div>
	</div>
</div>
<div class="products flex break"></div>
<div style="display: none;">
	<div id="v-svgs">
		<div class="flex v c">
			<svg viewBox="0 0 100 20">
				<path d="M10 25 C 40 11, 60 11, 90 24" fill="transparent" id="circle" />
				<text text-anchor="middle" font-size="7">
					<textPath xlink:href="#circle" startOffset="50%">
						<tspan class="txt">%s</tspan>
					</textPath>
				</text>
			</svg>
		</div>
	</div>
	<div id="v-icon">
		<div class="flex v c">
			<i class="iconfont icon-jinbi2"></i>
		</div>
	</div>
</div>
<script type="text/javascript" src="https://cdn.bootcss.com/countup.js/1.9.3/countUp.js"></script>
<script type="text/javascript">
var prize 		= <?php echo json_encode($prize);?>;
var prizelen	= prize.length;
var page 		= 1;
var chooseProIndex 	= -1;
var chooseProLen 	= 0;
var tips 		= '<?php echo $info['tips'];?>';
var _today 		= {!! isset($today) ? json_encode($today) : null !!};
$(document).ready(function(){
	draw(prize);

	// $('.start').click(function(){
	// 	startChouJiang();
	// });
	getProduct();

	setTimeout(function(){
		$('.showgeted').children('*:eq(0)').addClass('actived');
		showgetedFun();
	}, 1000);

	// $('.icon-refresh').click(function(){
	// 	var ppp 		= $(this).closest('.fan-blade');
	// 	chooseProIndex 	= ppp.index();
	// 	var product 	= $('.products');
	// 	layer.msg('请挑选您心仪的产品!');
	// 	$('html, body').stop().animate({
	// 		scrollTop: product.offset().top
	// 	}, 300);
	// });
	// var endNum 	= parseInt($('#upnum').text());
	// new CountUp('upnum', 0, endNum, 0, 2).start();
	fmtStartBtn();
});
$(window).resize(function(){
	fmtZhuanPan(prizelen);
});

// 选择产品按钮点击事件
function choosePros(t){
	var ppp 		= $(t).closest('.fan-blade');
	chooseProIndex 	= ppp.index();
	var product 	= $('.products');
	layer.msg('请挑选您心仪的产品!');
	$('html, body').stop().animate({
		scrollTop: product.offset().top
	}, 300);
}

// 左上角弹出层
function showgetedFun(){
	var showgeted 	= $('.showgeted');
	var showChids 	= showgeted.children('*');
	var showLen 	= showChids.length - 1;
	setInterval(function(){
		var n = $('.showgeted').children('.actived');
		var index 	= n.index();
		index++;
		if(index >= showLen){
			index 	= 0;
		}
		n.removeClass('actived');
		$('.showgeted').children('*:eq('+index+')').addClass('actived');
	}, 4000);
}

// 绘制转盘
function draw(pri){
	let lite 		= $('.lights-content');
	let cont 		= $('.content');
	let liteHtml 	= '';
	let contHtml 	= '';
	for(var i = 0; i < prizelen; i++){
		liteHtml 	+= '<div class="lights"><div class="light"></div></div>';
		let pp 		= pri[i];
		contHtml 	+= '<div class="fan-blade"><div class="ttxt"><svg viewBox="0 0 100 20"><path d="M10 23 C 40 11, 60 11, 90 23" fill="transparent" id="circle" /><text font-size="12" text-anchor="middle"><textPath xlink:href="#circle" startOffset="50%"><tspan>'+pp.title+'</tspan></textPath></text></svg></div><div class="fan-text">';

		if(typeof(pp['text']) != 'undefined'){
			let zz 	= $('#v-svgs').html();
			zz 		= zz.replace('%s', pp['text']);
			contHtml	+= zz;
		}else if(typeof(pp['icon']) != 'undefined'){
			let zz 	= $('#v-icon').html();
			contHtml	+= zz;
		}else if(typeof(pp['proimg']) != 'undefined'){
			let pid 	= 0;
			if(typeof(pp['id']) != 'undefined'){
				pid 	= pp['id'];
			}
			contHtml 	+= '<img src="'+pp['proimg']+'"><div class="fresh-pro" data-id="'+pid+'"><i class="iconfont icon-refresh" onclick="choosePros(this);"></i></div>';
		}
		contHtml	+= '</div></div>';
	}
	lite.html(liteHtml);
	cont.html(contHtml);
	fmtZhuanPan(prizelen);
}

// 格式化转盘
function fmtZhuanPan(num){
	// let num = prizelen;        				//个数
	let diameter = $('.container').width()      //转盘直径
	$('.container').height(diameter);
	let width = 0           //扇叶元素宽度
	let deg = 360 / num     //每一叶的旋转角度
	width = diameter * Math.tan((deg/2) * Math.PI/180)
	$('.fan-blade').each(function(){
		$(this).css('width', width + 'px');
		let svg 	= $(this).find('svg');
		let txt 	= svg.find('tspan').text();
		let txtLen 	= getLen(txt);
	});
}

// 设置开始按钮
function fmtStartBtn(){
	var btn 	= $('.starttxt');
	var startTimes = 0;
	if(typeof(_today['times']) != 'undefined'){
		startTimes 	= parseInt(_today['times']);
	}
	if(startTimes > 0){
		btn.text('开始');
		btn.closest('.start').attr('onclick', 'startChouJiang();');
	}else{
		var html 	= '<div style="text-align:center;font-size:1.2rem;"><i class="iconfont icon-shipintianchong"></i><div>看视频</div></div>';
		btn.html(html);
		btn.closest('.start').attr('onclick', 'showvideo(2);');
	}

	//设置运气值
	var yunqi 	= 0;
	var oldYq 	= parseInt($('#upnum').text());
	if(typeof(_today['yunqi']) != 'undefined'){
		yunqi 	= parseInt(_today['yunqi']);
	}
	if(oldYq == yunqi){
		return;
	}
	$('#upnum').text(yunqi);
	if(yunqi > 0){
		new CountUp('upnum', 0, yunqi, 0, 2).start();
	}
}

// 开始抽奖
var ischou 	= false;
function startChouJiang(){
	if(ischou == true){
		return;
	}
	ischou		= true;
	var isdone	= false;
	var ajax 	= $.post('<?php echo route('sweepstake.prize');?>', {}, function(r){
		isdone	= true;
		if(r.code == 401){
			ischou = false;
			return gotologin(r.msg);
		}else if(r.code == 200){
			let cha 	= 15;
			let res 	= parseInt(r.data.res);
			let quan 	= random(10,25);
			let deg 	= 360 / prize.length;

			let midDeg 		= 360 - (res + 1) * deg;
			let startDeg 	= midDeg - cha;
			let endDeg 		= midDeg + cha;
			let todeg		= random(startDeg, endDeg);

			let findeg 		= parseInt(todeg + (quan*360));
			let coseTiem 	= random(4, 8);

			let getPrize 	= '';
			let isgeted 	= false;
			try{
				getPrize 		= prize[res];
				if(typeof(getPrize['prize']) != 'undefined' && getPrize['prize'] == false){
					isgeted 	= false;
				}else{
					isgeted		= true;
				}
			}catch(e){

			}
			$('.content,.lights-content').removeClass('nomal').addClass('starcj');
			$('.content').css('transform', 'rotateZ(0deg)');
			setTimeout(function(){
				$('.content').css('transition', 'transform '+coseTiem+'s cubic-bezier(.2,.93,.43,1)').css('transform', 'rotateZ('+findeg+'deg)');
				setTimeout(function(){
					$('.lights-content').removeClass('starcj');
					$('.content').css('transition', '').removeClass('starcj');
					ischou	= false;
					if(isgeted == false){
						layer.msg('很抱歉,本次没有中奖,再接再厉!');
					}else{// 如果是商品,需要选择收货地址!
						layer.open({
							title: '恭喜您抽中以下奖品!',
							content: '<div class="show-price">'+getPrize['title']+'</div>',
							btn: ['笑纳']
						});
					}
					_today 	= r.data.today;
					fmtStartBtn(r.data.today);
				}, coseTiem*1000);
			}, 20);
		}else{
			layer.msg(r.msg);
		}
	});
	setTimeout(function(){
		if(isdone != true){
			ajax.abort();
			ischou = false;
			layer.msg('无法正常抽奖,请联系我们!');
		}
	}, 6000);
}

// 生成随机数
function random(lower, upper) {
	return Math.floor(Math.random() * (upper - lower)) + lower;
}

// 获取产品列表
function getProduct(){
	$.post('<?php echo route('sweepstake.products');?>', {page: page}, function(r){
		if(r.code == 200){
			page++;
			setProduct(r.data.list);
		}
	});
}

// 设置抽奖产品
function setProduct(pros){
	var p = $('.products');
	for(var i in pros){
		let op 		= pros[i];
		let html 	= '<div class="pro-box" data-id="'+op['id']+'">\
			<div class="proimg" style="background-image:url('+op['cover']+')">\
			</div>\
			<div class="title flex v"><div class="flex1">'+op['title']+'</div><div class="sale flex1">'+op['sale']+'</div></div>\
			';
		if(op['stocks'] > 0){
			html 	+= '<div class="flex v pro-btns"><div class="flex1" onclick="iwantyou(this);">我要这个</div></div>';
		}else{
			html 	+= '<div class="flex v pro-btns nostock"><div class="flex1" onclick="layer.msg(\'库存不足,请选其他产品.\');">库存不足</div></div>';
		}
		html 		+= '</div>';
		p.append(html);
	}
}

$('.tips').click(function(){
	layer.open({
		title: '活动规则',
		shade: 0.6,
		shadeClose: true,
		btn: [],
		anim: 2,
		content: tips,
	});
});
$('.icon-back').click(function(){
	history.go(-1);
});

function getLen(val){
   var len=0;
   var chineseReg=/[^\x00=\xff]/g;
   var newVal=val.replace(chineseReg,'**');
   return newVal.length;
}

// 选中产品作为奖品
function iwantyou(t){
	var pid 	= parseInt($(t).closest('.pro-box').attr('data-id'));
	for(var i in prize){
		if(typeof(prize[i]['id']) != 'undefined'){
			oopid 	= parseInt(prize[i]['id']);
			if(oopid == pid){
				layer.msg('您已经选择了该产品,请换其他商品试试!');
				return;
			}
		}
	}
	if(chooseProIndex > -1){
		var oldid 		= typeof(prize[chooseProIndex]['id']) != 'undefined' ? prize[chooseProIndex]['id'] : 0;
		if(oldid > 0){
			layer.confirm('是否替换 '+prize[chooseProIndex]['title']+'?', function(){
				choosePro(chooseProIndex, pid, function(){chooseProIndex=-1;});
			});
		}else{
			choosePro(chooseProIndex, pid, function(){chooseProIndex=-1;});
		}
	}else{
		$('.fresh-pro').each(function(){
			var pppppid 		= parseInt($(this).attr('data-id'));
			if(chooseProIndex < 0 && pppppid < 1){
				chooseProIndex 	= $(this).closest('.fan-blade').index();
			}
		});
		if(chooseProIndex < 0){
			var html 	= '';
			for(var i in prize){
				let s 	= prize[i];
				if(typeof(s['id']) != 'undefined'){
					html 	+= '<div class="replace-pro flex v" data-index="'+i+'">\
						<div class="rep-img flex v c"><img src="'+s['proimg']+'"></div>\
						<div class="rep-title flex1">'+s['title']+'</div>\
						<div class="rep-btn"><i class="iconfont icon-refresh" onclick="reppro(this, '+pid+', '+i+');"></i></div>\
					</div>';
				}
			}
			layer.open({
				title: '选择您要替换的产品',
				content: html,
				shadeClose: true,
				btn: []
			});
		}else{
			choosePro(chooseProIndex, pid, function(){chooseProIndex=-1;});
		}
	}
}

// 替换产品
function reppro(t, pid, index){
	choosePro(index, pid, function(){
		chooseProIndex=-1;
		$(t).closest('.layui-layer').find('.layui-layer-close').click();
	});
}

// ajax请求设置产品
function choosePro(index, pid, callback){
	$.post('<?php echo route('sweepstake.product');?>', {id: pid, index: index}, function(r){
		if(r.code == 200){
			prize 	= r.data;
			draw(prize);
			callback && callback();
		}else if(r.code == 401){
			return gotologin(r.msg);
		}
		layer.msg(r.msg);
	});
}

// 跳转登录
function gotologin(msg){
	if(!msg) msg = '请先登录!';
	return layer.confirm(msg, function(){
		try{
			appobject.postMessage("toLogin");
		}catch(e){
			layer.msg('请在app中打开!');
		}
	});
}

// 观看视频
var ggtp = false;//看广告的目的, 1是增加运气值, 2是增加抽奖次数
var sendadvid = 0;
function showvideo(tttp, advid){
	ggtp	= tttp;
	if(!advid){
		advid 	= 1;
	}
	try{
		sendadvid = advid;
		appobject.postMessage(advid);
	}catch(e){
		layer.msg('请在app中打开!');
	}
}

//视频看完回调
function videoDone(forwhat, platform){
	if(!forwhat){
		forwhat 	= ggtp;
	}
	if(!platform){
		platform 	= sendadvid;
	}
	var isDone 		= false;
	var ajax 	= $.post('<?php echo route('sweepstake.plaied');?>', {forwhat: forwhat, platform: platform}, function(r){
		isDone 		= true;
		if(r.code == 200){
			_today 	= r.data;
			fmtStartBtn();
		}else if(r.code == 401){
			return gotologin(r.msg);
		}
		layer.msg(r.msg);
	});
	setTimeout(function(){
		if(isDone == false){
			ajax.abort();
			isDone = true;
			layer.msg('错误!');
		}
	}, 6000);
}
</script>
@endsection
