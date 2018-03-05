<?php
namespace Forum\Login;
use Forum\Database\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Forum\Utility\Config;
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


	public function forgotPassword($email) {

		
		$this->db = Database::getInstance();
		$this->db_conn = $this->db->getConnection();
		//check email
		$sql = 'SELECT id FROM users WHERE email = ?';
		$stmt = $this->db_conn->prepare($sql);
		$stmt->bindValue(1, $email);

		if ($stmt->execute() && count($stmt->fetchAll()) == 1) {

		   	
		    $mail = new PHPMailer(true);// Passing `true` enables exceptions
		    try {
		     
		        $mail->SMTPDebug = 0; // Enable verbose debug output
		        $mail->isSMTP();      
		        $mail->Host = Config::get('MAIL_HOST');

		        $mail->SMTPAuth = false;                               
		        $mail->Username = Config::get('MAIL_USERNAME');                 
		        $mail->Password = Config::get('MAIL_PASSWORD');                           
		        //$mail->SMTPSecure = 'tls';                      

		        $mail->Port = Config::get('MAIL_PORT');                                    
		        //Recipients
		        $mail->setFrom(Config::get('MAIL_FROM'), 'Forum Sender');
		        $mail->addAddress(Config::get('MAIL_TO'), 'Form receipient');     
		        
		        //Content
		        $mail->isHTML(true);                                  
		        $mail->Subject = 'Password reset';

		        $reset_token = bin2hex(random_bytes(64));
		        $mail->Body    = '<a href="'.Config::get("SITE_URL").'/password_reset.php/?token='.$reset_token.'">Click here to reset password</a>';
		        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		        return $mail->send();
		        

		    } catch (Exception $e) {
		    	\Forum\Utility\Flash::set('Mail could not be sent');
		        return false;
		    }
		}
		
		\Forum\Utility\Flash::set('Invalid email ID');
		return false;
	}

}