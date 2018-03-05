<?php
require_once('../bootstrap.php');
use Forum\Session\Session;
use Forum\Utility\Flash;
use Forum\Utility\Config;
use Forum\Login\Login;
if ($_SERVER['REQUEST_METHOD'] == "POST") {

	if (Session::exist('forgot_password_csrf') && Session::get('forgot_password_csrf') == $_POST['forgot_password_csrf']) {

		Session::delete('forgot_password_csrf');
	} else {

		Flash::set('csrf token missing');
		echo $twig->render('forgot_password.html', array('status' => Flash::display('danger')));	

	}

	$email = isset($_POST['email']) ? $_POST['email'] : null;


	$validation = new Forum\Validation\Validation();

	if ($validation->check()) {

		//send password reset email

		$user = new Login();
		
		if ($user->forgotPassword($email)) {

			Flash::set('A password reset link has been sent to the email ID you have provided');
			echo $twig->render('forgot_password.html', array('status' => Flash::display('success')));
		} else {
			
			echo $twig->render('forgot_password.html', array('status' => Flash::display('danger')));
		}

		
	} else { //validation failed

		Flash::set('validation error');
		$csrf_token = bin2hex(random_bytes(64));
		Session::set('forgot_password_csrf', $csrf_token);
		echo $twig->render('forgot_password.html', array('csrf' => $csrf_token, 'status' => Flash::display('danger')));
	}

} else {

	$csrf_token = bin2hex(random_bytes(64));
	Session::set('forgot_password_csrf', $csrf_token);
	echo $twig->render('forgot_password.html', array('csrf' => $csrf_token));	
}
