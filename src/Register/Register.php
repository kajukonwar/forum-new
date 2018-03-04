<?php
namespace Forum\Register;
use Forum\Database\Database;
class Register
{


	private $db;
	private $db_conn;
	/**
	 * Validate user information
	 */
	public function check() {

		
	}

	/**
	 * Register the user
	 */
	public function register($fullname, $user_name, $email, $password) {

		//take input and store in DB

		$this->db = Database::getInstance();
		$this->db_conn = $this->db->getConnection();
		
		date_default_timezone_set('UTC');
		$date = new \DateTime();
		$now = $date->format('Y-m-d H:i:s');

		$password = password_hash(trim($password), PASSWORD_DEFAULT);
		
		$sql = 'INSERT INTO users (`fullname`, `user_name`, `email`, `password`, `created_at`) VALUES(:fullname, :user_name, :email, :password, :created_at)'; 

		$stmt = $this->db_conn->prepare($sql);
		$stmt->bindValue(':fullname', trim($fullname));
		$stmt->bindValue(':user_name', trim($user_name));
		$stmt->bindValue(':email', trim($email));
		$stmt->bindValue(':password', $password);
		$stmt->bindValue(':created_at', $now);

		if ($stmt->execute()) {

			return $this->db_conn->lastInsertId();
		}
		
		return false;
		
	}


}