$(document).ready(function(){

	var ws = new WebSocket(ws_host);

	ws.onopen = function(){
		$('#status').text('Connected to lotto server.');
		$('#status').addClass('connected');
		$('#settings').css('display', 'block');
		ws.send('settings');
	};

	ws.onclose = function(){
		$('#status').text('Connection closed.');
		$('#status').removeClass('connected');
	};

	ws.onmessage = function(evt){
		data = $.parseJSON(evt.data);
		console.log(data);
		switch(data.command){
			case 'claim':
				if(data.status < 1){
					$('#results').css('display', 'block');

					$('.draw_date').text(data.bet_date);
					$('.winning_combination').text(data.winning_combination);
					$('#combinations').text(data.combinations);

					$('#winners li').remove();
					$.each(data.winners, function(i,v){
						$('#winners').append('<li>'+v+'</li>');
					});
				}

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
		$('#results').css('display', 'none');
	});
});

