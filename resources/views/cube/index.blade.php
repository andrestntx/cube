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

			<div class="row" style="margin-top: 40px;">
				<div class="col-sm-6 col-sm-offset-2">
					{!! Form::open(['route' => 'cube.queries']) !!}
						{!! Field::textarea('text', ['tpl' => 'themes.bootstrap.fields.simple']) !!}
						<button type="submit" value="Send" class="btn btn-block btn-primary">Send</button>
					{!! Form::close() !!}
				</div>
				<div class="col-sm-2 bg-info">
					<h4>Result</h4>
					@if(session('result'))
						<div class="col-xs-12">
							<h5>Test OK:</h5>
							@foreach(session('result') as $test)
								@foreach($test as $output)
									<p>{{ $output }}</p>
								@endforeach
							@endforeach
						</div>
					@endif
				</div>	
			</div>
		</div>
		
		<script src="/assets/bootstrap/js/jquery-3.1.1.min.js"></script>
		<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>