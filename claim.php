<?php

	$config = include 'server/config.php';

	$config = $config['server'];

	$socket_server = $config['server_name'].':'.$config['port'].$config['server_script'];

?>
<!DOCTYPE html>
<html>
<head>
	<title>Lotto Claim</title>

	<link rel="stylesheet" type="text/css" href="assets/css/style.css" />

	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src='assets/js/claim.js'></script>
	<script type="text/javascript">
		var ws_host = 'ws://<?php echo $socket_server; ?>';
	</script>
</head>
<body>
	<div class="container">
		

		<label for="code">Code: </label>
		<input id='code' type="text" />

		<input id='claim' type="button" value="claim" />
	</div>

	<div class="container">
		Status: <span id='status'>Connecting to lotto server...</span> 
	</div>

	<div class="container">
		<div id="results">
			<div>
				Draw Date: <span class='draw_date'></span>
				Winning Combination: <span class='winning_combination'></span>
			</div>

			<div>
				Your combination <span id='combinations'> 
			</div>

			<div>
				Other combinations with 3 or more numbers matched: 
				<ul id="winners"></ul>
			</div>
		</span>
	</div>
	
</body>
</html>