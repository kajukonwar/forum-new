<?php
namespace Forum\Session;
class Session
{


	public static function set($name, $value)
	{

		$_SESSION[$name] = $value;
	}

	public static function get($name)

	{

		return isset($_SESSION[$name])? $_SESSION[$name] : null;
		
	}

	public static function delete($name)
	{
		if (isset($_SESSION[$name])) {

			unset($_SESSION[$name]);
		}

	}

	public static function exist($name)

	{
		return isset($_SESSION[$name]) ? true :false;
	}	

}