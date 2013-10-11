<?php

	$socket_server = $_SERVER['SERVER_NAME'].':9000/server/start-server.php';

?>
<!DOCTYPE html>
<html>
<head>
	<title>Lotto Game Client</title>

	<link rel="stylesheet" type="text/css" href="assets/css/style.css">

	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src='assets/js/script.js'></script>
	<script type="text/javascript">
		var ws_host = 'ws://<?php echo $socket_server; ?>';
	</script>
</head>
<body>
	<div class="container">
		<div id="numbers">
			<?php foreach(range(1,42) as $number) : ?>
				<div data-number='<?php echo $number; ?>' class="number"><?php echo $number; ?></div>
			<?php endforeach; ?>
		</div>

		<input id='submit' type="button" value="submit combinations" />
		
	</div>

	<div class="container">
		Status: <span id='status'>Connecting to lotto server...</span> 
		
		<div id="settings">
			Game period will starts at <span id='settings-start'>..</span> and will end <span id='settings-end'>..</span>
			and drawing of winning combination at <span id='settings-draw'>..</span>
			<br /><br />
			<input id='draw' type="button" value="click here to manually trigger drawing of numbers." />
		</div>
	</div>

	<div class="container" id='response'></div>
</body>
</html>