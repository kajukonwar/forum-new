<?php
require_once('../bootstrap.php');

use Forum\Session\Session;
use Forum\Utility\Flash;	
use Forum\Utility\Config;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {	

	//csrf token
	if (Session::exist('register_csrf') && Session::get('register_csrf') == $_POST['register_csrf'] ) {
		Session::delete('register_csrf');
		

	} else {

		Flash::set('csrf token missing');
		$redirect_url = Config::get('SITE_URL').'/register.php';
		header('Location:'.$redirect_url);
		exit();
	}

	$fullname = isset($_POST['fullname']) ? $_POST['fullname']: null;
	$user_name = isset($_POST['user_name']) ? $_POST['user_name'] : null;
	$password = isset($_POST['password'])? $_POST['password'] : null;
	$email = isset($_POST['email']) ? $_POST['email'] : null;
	
	$validation = new Forum\Validation\Validation();

	if ($validation->check()) {

		$user = new Forum\User\User();

		try {

			$user->register($fullname, $user_name, $email, $password);
			Flash::set('Registration successful');
			$redirect_url = Config::get('SITE_URL').'/home.php';
			header('Location:'.$redirect_url);
			exit();
		} catch (Exception $e) {

			echo $e->getMessage();
		}

			Flash::set('We are sorry. We couldn\'t process your registration at this moment. Please try again.');

			echo $twig->render('register.html', array('status' => Flash::display('danger')));

	} else {// validation fail

		$csrf_token = bin2hex(random_bytes(64));
		Session::set('register_csrf' , $csrf_token);

		Flash::set('Registration failed, validation error');
		$errors = $validation->getErrors();
		echo $twig->render('register.html', array('status' => Flash::display('danger'), 'errors' => $errors, 'csrf' => $csrf_token));
	}
} else {

	$csrf_token = bin2hex(random_bytes(64));
	Session::set('register_csrf', $csrf_token);
	echo $twig->render('register.html', array('status' => Flash::display('danger') ,'csrf' => $csrf_token));
}