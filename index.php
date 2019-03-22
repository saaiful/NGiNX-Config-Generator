<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>NGiNX Config Generator</title>

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<style type="text/css">
			pre{-webkit-user-select: all;user-select: all;}
			#_node{display: none;}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="text-center">
				<h2>NGiNX Config Generator</h2>
			</div>
			<form action="#" id="setting">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="domains">Domains (separated by space) *</label>
							<input type="text" id="domains" name="domains" class="form-control" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="root">Document Root *</label>
							<input type="text" id="root" name="root" class="form-control" required>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="app">Select App *</label>
							<select type="text" id="app" name="app" class="form-control" required>
								<option value="">No App</option>
								<option value="php">Basic PHP</option>
								<option value="wordpress">WordPress</option>
								<option value="laravel">Laravel</option>
								<option value="js-front">JS/Html</option>
								<option value="node">Node</option>
								<option value="django">Django</option>
								<option value="durpal">Durpal</option>
							</select>
						</div>
					</div>
					<div class="col-md-3" id="_php">
						<div class="form-group">
							<label for="php">PHP *</label>
							<select type="text" id="php" name="php" class="form-control" required>
								<option value="">No PHP</option>
								<option value="php5">php 5</option>
								<option value="php7.0">php 7.0</option>
								<option value="php7.1">php 7.1</option>
								<option value="php7.2">php 7.2</option>
								<option value="php7.3">php 7.3</option>
							</select>
						</div>
					</div>
					<div class="col-md-3" id="_node">
						<div class="form-group">
							<label for="port">Internal Port *</label>
							<input type="text" id="port" name="port" class="form-control" required>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="cache">Cache</label>
							<select type="text" id="cache" name="cache" class="form-control">
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="gzip">gZip</label>
							<select type="text" id="gzip" name="gzip" class="form-control">
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="ssl">SSL (Let's Encrypt)</label>
							<select type="text" id="ssl" name="ssl" class="form-control">
								<option value="0">No</option>
								<option value="1">Yes</option>
							</select>
						</div>
					</div>

					<div class="col-md-12">
						<strong>Configuration Direct Download</strong>
						<pre id="direct">*****************************************************</pre>
						<br>

						<strong>Configuration Direct Download & Execute</strong>
						<pre id="exec">*****************************************************</pre>
						<br>

						<strong>Nginx Configuration</strong>
						<pre id="conf" style="margin-top: 5px;">*****************************************************</pre>
					</div>
				</div>
			</form>

			<div class="footer">
				&copy; Saiful Islam
			</div>
		</div>

		<!-- jQuery -->
		<script src="//code.jquery.com/jquery.js"></script>
		<script type="text/javascript">
			function makeConf(){
				var params = $('#setting').serialize();
				$("#direct").html('wget -O conf.txt "' + window.location.href + 'api.php?' + params + '"');
				$("#exec").html('wget -O conf.sh "' + window.location.href + 'api.php?' + params + '&type=sh" && sudo bash conf.sh');
				$.ajax({
					url: "api.php?" + params,
					success: function(data){
						$("#conf").html(data);
					},
					dataType: 'html'
				});
			}
			$('input, select').on('change',makeConf);
			$("#app").on('change', function(){
				var app = $(this).val();
				if(app.match(/wordpress|laravel|durpal|php/i)){
					if($("#php").val()==''){$("#php").val('php7.1').change();}
				}else{
					$("#php").val('').change();
				}
				if(app=='node'){
					$("#_node").show();$("#_php").hide();
				}else{
					$("#_node").hide();$("#_php").show();
				}
			});
		</script>
	</body>
</html>
