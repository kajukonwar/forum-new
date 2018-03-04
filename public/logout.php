<?php
require_once('../bootstrap.php');
use Forum\User\User;
use Forum\Session\Session;
use Forum\Utility\Config;
$user = new User();

if ($user->logout(Session::get('u_id'))) {

	$redirect_url = Config::get('SITE_URL').'/login.php';
	header('Location:'.$redirect_url);
	exit();
}