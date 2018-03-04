<?php
namespace Forum\Database;
use PDO;
use Forum\Utility\Config;
class Database
{

	private static $instance;

	private $pdo;

	private $db_name;

	private $db_host;

	private $db_user;

	private $db_pass;

	private function __construct()

	{

		$opt = [

			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
			PDO::ATTR_EMULATE_PREPARES => FALSE,
		];

		$this->db_name=Config::get('DB_NAME');
		$this->db_host = Config::get('DB_HOST');
		$this->db_user = Config::get('DB_USER');
		$this->db_pass = Config::get('DB_PASSWORD');
		$charset = 'utf8mb4';

		$dsn = 'mysql:host='.$this->db_host.';dbname='.$this->db_name.';charset='.$charset;
		$this->pdo = new PDO($dsn, $this->db_user, $this->db_pass, $opt);
	}

	public static function getInstance()

	{

		if (self::$instance === null) {

			self::$instance = new self;
		}

		return self::$instance;
	}

	public function getConnection()

	{
		return $this->pdo;
	}

	private function __clone()
	{
		
	}
}