<?php
namespace Forum\Utility;
use Forum\Session\Session;
class Flash
{

	private static $flash_session_key = 'flash';

	public static function set($msg)

	{

		Session::set(self::$flash_session_key, $msg);

	}

	public static function display($css_class)

	{

		$output= '';
		
		if (Session::exist(self::$flash_session_key)) {

			$msg = Session::get(self::$flash_session_key);
			$output = '<div class="alert alert-'.$css_class.'" role="alert">'.$msg.'</div>';

			self::remove();

		} 
		
		return $output;
	}

	public static function remove()

	{

		Session::delete(self::$flash_session_key);
	}
}