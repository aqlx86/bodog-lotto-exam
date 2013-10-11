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

		$this->orm = new NotORM( new PDO("mysql:dbname=$config[name]", $config['user'], $config['password']) );
	}

	public function status()
	{
		$status = array('status' => 1);

		// get game config
		$config = $this->config['game'];

		// check if has drawn today
		$drawn = $this->orm->draws('draw_date = ?', date('Y-m-d'))->fetch();

		if($drawn)
		{
			$status = array(
				'status' => -1,
				'combinations' => $drawn['combinations']
			);
		}

		// check if still in game period.
		if(date('H:i:s') >= $config['start'] AND date('H:i:s') <= $config['end'])
		{
			$status = 0;
		}

		return $status;
	}

	public function draw($date = null)
	{
		$status = $this->status();

		if($status['status'] < 1)
			return $status;
		
		$date = is_null($date) ? date('Y-m-d') : $date;

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

		// select six numbers
		$indexes = array_rand($numbers, 6);

		$numbers = implode(',', array_intersect_key($numbers, $indexes));

		// save winning combinations
		$this->orm->draws->insert(array(
			'combinations' => $numbers,
			'draw_date' => $date
		));	

		return $numbers;
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
		// check if claimed.
		if($this->orm->claims()->where('code = ?', $code)->fetch())
		{
			return array('message' => 'You already claimed this.');
		}

		$message = array();

		// check if in bet
		$bet = $this->orm->bets()->where('code = ?', $code)->fetch();

		if(! $bet)
		{
			return array('message' => 'Code not found.');
		}

		// check if winner
		$winner = $this->orm->draws()->where('combinations = ?', $bet['combinations'])->fetch();

		if(! $winner)
		{
			return array('message' => 'Not a winning combination.');
		}

		// claim
		$this->orm->claims->insert(array(
			'code' => $code,
			'date_claimed' => date('Y-m-d')
		));

		return array('message' => 'Congratulations.');
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