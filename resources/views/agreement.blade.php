<!DOCTYPE html>
<html>
<head>
	<title>{{ $title }}</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name=""viewport""></head>
	<style type="text/css">
		body{
			max-width: 680px;
			margin: 0 auto;
			font-size: 12px;
		}
	</style>
</head>
<body>
	<h1>{{ $title }}</h1>
	{!! $content !!}
</body>
</html>