$(document).ready(function(){

	var ws = new WebSocket(ws_host);

	ws.onopen = function(){
		$('#status').text('Connected to lotto server.');
		$('#settings').css('display', 'block');
		ws.send('settings');
	};

	ws.onclose = function(){
		$('#status').text('Connection closed.');
	};

	ws.onmessage = function(evt){
		data = $.parseJSON(evt.data);

		$('#combination').text(data.combinations);

		switch(data.command){
			case 'claim':
				alert(data.message);
				break;
			case 'settings':
				$('#settings-start').text(data.start);
				$('#settings-end').text(data.end);
				$('#settings-draw').text(data.draw);
				break;
		}
	};

	$('#claim').bind('click', function(){
		if($('#code').val() == '')
		{
			alert('enter code');
			return;
		}

		ws.send('claim:'+$('#code').val());
		$('#code').val('');
	});
});

