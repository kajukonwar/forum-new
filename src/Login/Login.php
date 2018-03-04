<?php
namespace Forum\Login;
use Forum\Database\Database;
class Login
{

	private $db;
	private $db_conn;

	/**
	 * Validate 
	 */
	public function validate() {

	}


	/**
	 * login user
	 */
	public function login($user_name, $password) {
		
		$this->db = Database::getInstance();
		$this->db_conn = $this->db->getConnection();

		$sql = 'SELECT id, password FROM users WHERE user_name = ?';

		$stmt = $this->db_conn->prepare($sql);
		$stmt->bindValue(1, $user_name);

		if ($stmt->execute()) {

			$result = $stmt->fetchAll();

			if (!empty($result) && count($result) === 1) {

				$hashed_password = $result[0]->password;

				if (password_verify($password, $hashed_password)) {

					return $result[0]->id;
				}
			}
		}

		return false;
	}

}