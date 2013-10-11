<?php

class LottoServer extends WebSocketServer {

	protected function connected($user)
	{
		// check current game status
		$game_status = LottoData::instance()->status();

		if($game_status['status'] < 1)
		{
			$this->send($user, json_encode($game_status));
		}
	}

	protected function closed($user) { }

	protected function process($user, $message)
	{
		$this->stdout('> message: '. $message);

		list($command) = explode(':', $message);

		switch ($command) 
		{
/*			case 'status':
				$reply = LottoData::instance()->status();
				break;
*/
			case 'bet':
				list(, $combination) = explode(':', $message);

				$reply = LottoData::instance()->bet($combination);
				break;

			case 'settings':
				$reply = LottoData::instance()->settings();
				break;

			case 'draw':
				LottoData::instance()->draw();

				$reply = LottoData::instance()->status();
				break;

			case 'claim':
				list(, $code) = explode(':', $message);

				$reply = LottoData::instance()->claim($code);
				break;
			
			default:
				$this->stdout('> Unknown command: '. $command);
				break;
		}

		// attach command
		$reply = array('command' => $command) + $reply;

		$this->send($user, json_encode($reply));
	}

}