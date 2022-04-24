@extends('default.Sweepstake.app')
@section('title', '抽奖')

@section('body')
<style type="text/css">
.contents{
	margin-top: 3.5rem;
}
.contBg{
	background: url('/image/sw.gif');
	min-height: 20rem;
	background-size: 100%;
	background-repeat: no-repeat;
	background-position: center;
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
	<div class="contBg">sfsdf</div>
</div>

<script type="text/javascript">
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
</script>
@endsection