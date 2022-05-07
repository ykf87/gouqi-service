@extends('default.Sweepstake.app')
@section('title', '抽奖')

@section('body')
<script src="https://cdn.jsdelivr.net/npm/lucky-canvas@1.7.20"></script>
<style type="text/css">
body{
	background: #19124F;
	color: #000;
}
.contents{
	margin-top: 3.5rem;
}

.aaabbs{
	padding-top: 30px;
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
}
.content{
   position: relative;
   width: 100%;
   height: 100%;
   display: flex;
   justify-content: center;
   border-radius: 50%;
   overflow: hidden;
   animation: scccontent 20s linear infinite;
}
.fan-blade{
   position: absolute;
   width: 100px;
   height: 50%;
   clip-path: polygon(50% 100%, 0% 0%, 100% 0%);
   -webkit-clip-path: -webkit-polygon(50% 100%, 0% 0%, 100% 0%);
   transform-origin: 50% 100%;
}
.fan-text{
	position: absolute;
	width: 170%;
	height: 100%;
	margin-left: -36%;
	clip-path: polygon(50% 100%, 0% 0%, 100% 0%);
	-webkit-clip-path: -webkit-polygon(50% 100%, 0% 0%, 100% 0%);
	transform-origin: 50% 100%;
	border-radius: 50% 50% 0 0;
	font-size: 1.4rem;
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
    background-color: #ffffff;
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
    z-index: 9999;
}
.lights{
	position: absolute;
	width: 16px;
	height: 50%;
	transform-origin: 50% 100%;
	z-index: 9999;
}
.light{
    position: absolute;
    top: 4px;
    width: 12px;
    height: 12px;
    border-radius: 50% 50% 0 0;
	z-index: 9999;
    
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

@keyframes scccontent{
	from{
		transform: rotate(0deg);
	}
	to{
		transform: rotate(360deg);
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
</style>
<div class="header-back">
	<div class="flex v">
		<i class="iconfont icon-back"></i>
		<div class="flex c flex1">
			<div class="title">拼手气赢大奖</div>
		</div>
		<i class="iconfont icon-iconfont-wenhao"></i>
	</div>
</div>
<div class="contents">
	<div class="aaabbs">
		<div class="container">
			<div class="content">
				<div class="fan-blade" style="text-align: center;">
					<div style="font-size: 0;">
						<svg viewBox="0 0 100 12">
							<path d="M10 16 C 40 6.5, 60 6.5, 90 16.4" fill="transparent" id="circle" />
							<text style="fill:white;" font-size="8" text-anchor="middle">
								<textPath xlink:href="#circle" startOffset="50%">
									<tspan>现金</tspan>
								</textPath>
							</text>
						</svg>
					</div>
					<div class="fan-text">现金</div>
					<div class="fan-img"></div>
				</div>
				<div class="fan-blade">
					<div class="fan-text">现金</div>
					<div class="fan-img"></div>
				</div>
				<div class="fan-blade"></div>
				<div class="fan-blade"></div>
				<div class="fan-blade"></div>
				<div class="fan-blade"></div>
				<div class="fan-blade"></div>
				<div class="fan-blade"></div>
			</div>
			<div class="lights-content">
				<div class="lights"><div class="light"></div></div>
				<div class="lights"><div class="light"></div></div>
				<div class="lights"><div class="light"></div></div>
				<div class="lights"><div class="light"></div></div>
				<div class="lights"><div class="light"></div></div>
				<div class="lights"><div class="light"></div></div>
				<div class="lights"><div class="light"></div></div>
				<div class="lights"><div class="light"></div></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	
});


let num = 8             					//个数
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
	console.log(width - (txtLen * 12));
});

$('.icon-iconfont-wenhao').click(function(){
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
</script>
@endsection


<!-- #EXTM3U
#EXT-X-TARGETDURATION:12
#EXT-X-ALLOW-CACHE:YES
#EXT-X-PLAYLIST-TYPE:VOD
#EXT-X-VERSION:3
#EXT-X-MEDIA-SEQUENCE:1
#EXTINF:5.000,
693622.mp4/seg-1-v1-a1.ts
#EXTINF:10.000,
693622.mp4/seg-2-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-3-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-4-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-5-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-6-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-7-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-8-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-9-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-10-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-11-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-12-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-13-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-14-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-15-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-16-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-17-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-18-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-19-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-20-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-21-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-22-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-23-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-24-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-25-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-26-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-27-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-28-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-29-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-30-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-31-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-32-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-33-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-34-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-35-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-36-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-37-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-38-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-39-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-40-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-41-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-42-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-43-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-44-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-45-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-46-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-47-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-48-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-49-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-50-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-51-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-52-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-53-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-54-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-55-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-56-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-57-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-58-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-59-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-60-v1-a1.ts
#EXTINF:10.000,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-61-v1-a1.ts
#EXTINF:11.640,
https://ip260027942.ahcdn.com/key=byUeORlW+iiQ7BbSmCaqBQ,s=,end=1651795135,ip=154.17.19.73,limit=2/state=YnRdmhET/reftag=073313961/media=hls/39/102/4/275496614.mp4/seg-62-v1-a1.ts
#EXT-X-ENDLIST
 -->