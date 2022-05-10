@extends('default.Sweepstake.app')
@section('title', '抽奖')

@section('body')
<script src="https://cdn.jsdelivr.net/npm/lucky-canvas@1.7.20"></script>
<style type="text/css">
body{
	background: linear-gradient(#19124F, #6054E1, #E771D6);
	color: #000;
	font-size: 62.5%;
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
}
.pro-btns{
	margin-top: 8px;
	height: 30px;
	background: #EFE7FC;
	color: #9F86D7;
}
.products .sale{
	color: brown;
	font-weight: bold;
}
.products .sale:before{
	content: "\e616";
	font-family: "iconfont";
	font-size: 1.4rem;
	margin-right: -3px;
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
	padding-top: 4rem;
	text-align: center;
	color: #FFFFFF;
	width: 100%;
	position: relative;
	min-height: 50px;
}
.slogan img{
	width: 45%;
	opacity: .7;
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
	position: absolute;
	left: 10px;
	top: 55px;
	width: 70px;
	height: 40px;
	padding: 10px 0;
	background: rgba(255,255,255,.1);
	border-radius: 0 0 7px 7px;
	opacity: .8;
	color: #ffd60a;
}
.gailv .ppp{
	font-size: 1.6rem;
	font-weight: bolder;
	margin-top: .8rem;
}
.addadv{
	position: absolute;
	right: 10px;
	top: 20%;
	opacity: .8;
	color: #ffffff;
	height: 100%;
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
<div class="slogan">
	<div class="gailv">
		<span>运气值</span>
		<div class="ppp"><span class="num">+15</span>%</div>
	</div>
	<img src="/image/bbg.png">
	<div class="addadv flex v" onclick="showvideo();">
		<i class="iconfont icon-shipintianchong" style="margin-right: 4px;font-size: 2rem;"></i>
		<span style="font-size: 1rem;">看广告<br>涨运气</span>
	</div>
</div>
<div class="contents">
	<div class="aaabbs">
		<div class="container">
			<div class="start flex v c">
				<div class="jiantou"></div>
				<div class="flex v c">开始</div>
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
	<div id="v-pros">
		<img src="%s">
		<div><i class="iconfont icon-refresh"></i></div>
	</div>
</div>
<script type="text/javascript">
var prize 		= <?php echo json_encode($prize);?>;
var prizelen	= prize.length;
var page 		= 1;
$(document).ready(function(){
	draw(prize);

	fmtZhuanPan(prizelen);
	$('.start').click(function(){
		startChouJiang();
	});
	getProduct();

	setTimeout(function(){
		$('.showgeted').children('*:eq(0)').addClass('actived');
		showgetedFun();
	}, 1000);

	$('.icon-refresh').click(function(){
		var ppp 	= $(this).closest('.fan-blade');
		var index 	= ppp.index();
		var product = $('.products');
		layer.msg('请挑选您心仪的产品!');
		$('html, body').stop().animate({
			scrollTop: product.offset().top
		}, 300);
	});
});
$(window).resize(function(){
	fmtZhuanPan(prizelen);
});

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
			let zz 	= $('#v-pros').html();
			zz 		= zz.replace('%s', pp['proimg']);
			contHtml	+= zz;
		}
		contHtml	+= '</div></div>';
	}
	lite.html(liteHtml);
	cont.html(contHtml);
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

// 开始抽奖
var ischou 	= false;
function startChouJiang(){
	if(ischou == true){
		return;
	}
	ischou	= true;
	$('.content,.lights-content').removeClass('nomal').addClass('starcj');
	$('.content').css('transform', 'rotateZ(0deg)');
	setTimeout(function(){
		$('.content').css('transition', 'transform 7s cubic-bezier(.2,.93,.43,1)').css('transform', 'rotateZ(1885deg)');
		setTimeout(function(){
			$('.lights-content').removeClass('starcj');
			$('.content').css('transition', '').removeClass('starcj');
			ischou	= false;
			layer.msg('很抱歉没有中奖!');
		}, 7000);
	}, 10);
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
		let html 	= '<div>\
			<div class="proimg" style="background-image:url('+op['cover']+')">\
			</div>\
			<div class="title flex v"><div class="flex1">'+op['title']+'</div><div class="sale">'+op['sale']+'</div></div>\
			<div class="flex v pro-btns"><div class="flex1">我要这个</div></div>\
		</div>';
		p.append(html);
	}
}

$('.tips').click(function(){
	$('.sdsf').addClass('animate__backInRight');
	layer.open({
		title: '抽奖规则',
		shade: 0.6,
		shadeClose: true,
		btn: [],
		anim: 2,
		content: '1.每天凌晨0点重置抽奖次数.',
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

// 观看视频
function showvideo(){
	try{
		appobject.postMessage('1');
	}catch(e){
		layer.msg('请在app中打开!');
	}
}

//视频看完回调
function videoDone(){
	layer.msg('获得奖励!');
}
</script>
@endsection
