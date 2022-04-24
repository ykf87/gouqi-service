<!DOCTYPE html>
<html>
<head>
	<title>@yield('title')</title>
	<style type="text/css">
		html{
			font-size: 62.5%;
		}
		body{
			margin: 0;
			padding: 0;
			font-size: 1.2rem;
			font-family: "microsoft yahei";
			position: relative;
		}
		.flex{
			display: flex;
		}
		.flex.v{
			align-items: center;
		}
		.flex.c{
			justify-content: center;
		}
		.flex1{
			flex: 1;
		}
		.header-back{
			position: fixed;
			left: 0;
			top: 0;
			width: 100%;
			height: 3.5rem;
			z-index: 99;
			line-height: 3.5rem;
			background: linear-gradient(to bottom,#fafafa,#e5e5e5);
			color: #777;
		}
		.header-back  .icon-back{
			padding: 0 0.8rem 0 1.2rem;
			font-size: 1.4rem;
			display: inline-block;
		}
		.header-back  .icon-iconfont-wenhao{
			padding: 0 0.8rem 0 1.2rem;
		}
		.header-back .title{
			font-size: 1.6rem;
			font-weight: bold;
			font-family: "楷体";
		}
	</style>
	<meta name="viewport" content="width=device-width,target-densitydpi=high-dpi,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<link rel="stylesheet" type="text/css" href="//at.alicdn.com/t/font_2311559_fnulq3qrerj.css">
	<link rel="stylesheet" href="{{ asset('layui/dist/css/layui.css') }}">
	<script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
	<script src="{{ asset('layui/dist/layui.js') }}"></script>
</head>
<body>
@yield('body')
</body>
</html>