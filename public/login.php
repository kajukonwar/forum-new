<?php
require_once('../bootstrap.php');
use Forum\Session\Session;
use Forum\Utility\Flash;
use Forum\Utility\Config;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (Session::exist('login_csrf') && Session::get('login_csrf') == $_POST['login_csrf']) {

		Session::delete('login_csrf');
	} else {

		Flash::set('csrf token missing');
		$redirect_url = Config::get('SITE_URL').'/login.php';
		header('Location:'.$redirect_url);
		exit();
	}

	$user_name = isset($_POST['user_name']) ? $_POST['user_name']: null;
	$password = isset($_POST['password']) ? $_POST['password'] : null;


	$validation = new Forum\Validation\Validation();

	if ($validation->check()) {

		$user = new Forum\User\User();

		try {


			if ($user->login($user_name, $password)) {

				//redirect to home
				Flash::set('Welcome, Login successful');
				$redirect_url = Config::get('SITE_URL').'/home.php';
				header('Location:'.$redirect_url);
				exit();
			}

			

		} catch (Exception $e) {


			Flash::set('Login unsuccessful');
		}

		echo $twig->render('login.html', array('status' => Flash::display('danger')));

	} else {

		$csrf_token = bin2hex(random_bytes(64));
		Session::set('login_csrf', $csrf_token);
		Flash::set('Validation failed');
		$errors = $validation->getErrors();
		echo $twig->render('login.html', array('errors' => $errors, 'csrf' => $csrf_token, 'status' => Flash::display('danger')));
	}

} else {
	$csrf_token = bin2hex(random_bytes(64));
	Session::set('login_csrf', $csrf_token);
	echo $twig->render('login.html', array('csrf' => $csrf_token));
}
