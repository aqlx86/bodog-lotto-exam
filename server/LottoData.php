<?php

class LottoData {

	protected static $instance;
	
	protected $orm;

	public $config;

	public static function instance()
	{
		if(is_null(self::$instance))
		{
			self::$instance = new LottoData;	
		}

		return self::$instance;
	}

	protected function __construct()
	{
		$this->config = include 'config.php';

		// get db config
		$config = $this->config['database'];

		$structure = new NotORM_Structure_Convention(
			'id', "%s_id", "%ss"
		);

		$this->orm = new NotORM( new PDO("mysql:dbname=$config[name]", $config['user'], $config['password']), $structure );
	}

	public function status()
	{
		$status = array('status' => 0);

		// get game config
		$config = $this->config['game'];

		// check if has drawn today
		$drawn = $this->orm->draws('draw_date = ?', date('Y-m-d'))->fetch();

		if($drawn)
		{
			$status = array(
				'status' => -1,
				'combinations' => $drawn['combinations'],
				'message' => 'winning combinations has been selected. go to claim page now?'
			);

			return $status;
		}

		// check if still in game period.
		if(date('H:i:s') >= $config['start'] AND date('H:i:s') <= $config['end'])
		{
			$status['status'] = 1;
		}
		else
		{
			$status['message'] = 'past cutoff period, not accepting bets anymore today. game will start again '. $config['start'].
				' go to claim page now?';
		}

		return $status;
	}

	public function draw($date = null)
	{
		$status = $this->status();

		if($status['status'] < 1)
			return $status;
		
		$date = is_null($date) ? date('Y-m-d') : $date;

		// bets in date.
		$combinations = $this->orm->bets()->where('DATE(bet_date) = ?', $date);

		$numbers = array();

		foreach($combinations as $value)
		{
			$numbers = array_merge($numbers, explode(',', $value['combinations']));
		}

		// get only unique numbers
		$numbers = array_unique($numbers);

		// shuffle numbers
		shuffle($numbers);

		// select six randome numbers, this will return the array keys so we need to intersect this with the original array 
		// to get the values
		$indexes = array_rand($numbers, 6);

		$numbers = implode(',', array_intersect_key($numbers, $indexes));

		// save winning combinations
		$draw =  $this->orm->draws->insert(array(
			'combinations' => $numbers,
			'draw_date' => $date
		));	

		$results = array();

		// get all the winning bets
		foreach($combinations as $value)
		{
			// matche the combinations
			$matched = array_intersect(explode(',', $value['combinations']), explode(',', $numbers));

			// at least 3 numbers matched the winning combinations is a winner
			if(count($matched) >= 3)
			{	
				$this->orm->winners->insert(array(
					'draw_id' => $draw['id'],
					'bet_id' => $value['id'],
				));
			}

			$results[] = array(
				'matched' => implode(',', $matched),
				'combination' => $value['combinations'],
				'winner' => (count($matched) >= 3) ? true : false
			);
		}

		return array(
			'combination' => $numbers,
			'draw_date' => $date,
			'results' => $results
		);
	}

	public function bet($combinations)
	{
		$status = $this->status();

		if($status['status'] < 1)
			return $status;

		$result = $this->orm->bets->insert(array(
			'combinations' => $combinations,
			'code' => $this->generate_key(),
			'bet_date' => date('Y-m-d H:i:s')
		));

		return array(
			'status' => 1, 
			'id' => $result['id'], 
			'code' => $result['code']
		);
	}

	public function claim($code)
	{
		$response = array(
			'status' => 1,
		);

		// check if in bet
		$bet = $this->orm->bets()->where('code = ?', $code)->fetch();

		if(! $bet)
		{
			$response['message'] = 'Code not found.';

			return $response;
		}

		$draw_date = date('Y-m-d', strtotime($bet['bet_date']));

		$response['combinations'] = $bet['combinations'];
		$response['bet_date'] = $draw_date;

		// get draw details
		$draw = $this->orm->draws()->where('draw_date = ?', $draw_date)->fetch();

		if(! $draw)
		{
			$response['message'] = 'no winning combination draw yet for '. $draw_date;

			return $response;
		}

		$response['winning_combination'] = $draw['combinations'];

		// get all winning combinations
		$winners = $this->orm->winners()->where('draw_id = ?', $draw['id']);

		foreach($winners as $values)
		{
			$response['winners'][] = $values->bet['combinations'];
		}

		// check if winner
		$winner = $this->orm->winners()->where('bet_id = ?', $bet['id'])->fetch();

		if(! $winner)
		{
			$response['status'] = -1;
			$response['message'] = 'Not a winning combination.';

			return $response;
		}

		if($winner['claimed'] == 1)
		{
			$response['message'] = 'Already claimed on '. $winner['date_claimed'];

			return $response;
		}

		// mark as claimed
		$winner['claimed'] = 1;
		$winner['date_claimed'] = date('Y-m-d');

		// update
		$winner->update();

		$response['status'] = 0;
		$response['message'] = 'Congratulations.';

		return $response;
	}

	public function settings()
	{
		return $this->config['game'];
	}

	public function generate_key()
	{
		while(true)
		{
			$code = substr(md5(uniqid(mt_rand(), true)), 0, 8);

			if(! $this->orm->bets('code = ?', $code)->fetch())
			{
				return $code;
			}
		}
	}
}