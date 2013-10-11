$(document).ready(function(){

	var ws = new WebSocket(ws_host);
	var stack = [];

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

		if(data.status < 1){
			document.location.href = 'claim.php';
			return;
		}

		switch(data.command){
			case 'bet':
				$('#response').html('Combinations submitted, your Ticket id: <b>'+data.id+'</b> and your Code: <b>'+data.code+'</b>');
				stack = [];
				$('.number').removeClass('selected');
				break;
			case 'settings':
				$('#settings-start').text(data.start);
				$('#settings-end').text(data.end);
				$('#settings-draw').text(data.draw);
				break;
		}
	};

	$('.number').bind('click', function(){
		var n = $(this).data('number');

		var i = stack.indexOf(n);

		if(i == -1){
			if(stack.length==6){
				return;
			}
			stack.push(n);
		}else{
			stack.splice(i, 1);
		}

		$(this).toggleClass('selected');
	});

	$('#draw').bind('click', function(){
		ws.send('draw');
	});

	$('#submit').bind('click', function(){
		if(stack.length < 6){
			alert('select 6 numbers');
			return;
		}

		ws.send('bet:'+stack.toString());
	});
});

