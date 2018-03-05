<?php
namespace Forum\User;
use Forum\Register\Register;
use Forum\Session\Session;
use Forum\Login\Login;
class User
{

	public function register($fullname = null, $user_name = null, $email = null, $password = null)
	{

        $user = new Register();

        $user_id = $user->register($fullname, $user_name, $email, $password);

        if ($user_id) {

        	Session::set('u_name', trim($user_name));
        	Session::set('u_id', trim($user_id));
        	//send verification email

        	return true;
        }

        return false;

        
	}


	public function login($user_name, $password) {

		$user = new Login();

		$user_id = $user->login($user_name, $password);

		if ($user_id) {

			Session::set('u_name', trim($user_name));
			Session::set('u_id', $user_id);

			return true;
		}

		return false;
	}

	public function logout($u_id) {

		if (Session::exist('u_id') && Session::get('u_id') == $u_id) {
			unset($_SESSION);
			session_destroy();

			return true;
		}

		return false;
	}



}
