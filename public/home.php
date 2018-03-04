<?php
require_once('../bootstrap.php');
use Forum\Session\Session;
use Forum\Utility\Flash;
echo $twig->render('home.html', array('status' => Flash::display('success'), 'user_name' => Session::get('u_name')));