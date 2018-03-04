<?php
namespace Forum\Utility;

class Config 
{

	
	public static function get($config_name = null) 
	{

		if (empty($config_name)) {

			throw new \Exception('Configuration name was not provided');
		}

		$config = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/../config/config.ini');

		return $config[$config_name];
	}

}
