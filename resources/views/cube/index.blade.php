<!DOCTYPE html>
<html>
	<head>
		<title>Cube</title>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
	</head>
	<body>
		<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
			<h1 class="text-center">Cube Summation</h1>	

			<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2">
				{!! Form::open(['route' => 'cube.queries']) !!}
					{!! Field::textarea('text') !!}
					<button type="submit" value="Send" class="btn btn-block btn-primary">Send</button>
				{!! Form::close() !!}
			</div>
			@if(isset($result))
				<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2">
					<h2>Resultado</h2>
					<p>{{ $result }}</p>
				</div>
			@endif
		</div>
		
		<script src="/assets/bootstrap/js/jquery-3.1.1.min.js"></script>
		<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>