<?php

	$socket_server = $_SERVER['SERVER_NAME'].':9000/server/start-server.php';

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
		<h2>Winning Combination: <span id='combination'></span></h2>

		<label for="code">Code: </label>
		<input id='code' type="text" />

		<input id='claim' type="button" value="claim" />
	</div>

	<div class="container">
		Status: <span id='status'>Connecting to lotto server...</span> 
		
		<div id="settings">
			Game period will starts at <span id='settings-start'>..</span> and will end <span id='settings-end'>..</span>
			and drawing of winning combination at <span id='settings-draw'>..</span>
		</div>
	</div>
</body>
</html>