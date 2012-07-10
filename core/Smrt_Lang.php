<?php

namespace core;

class Smrt_Lang 
{
	/**
	 * 
	 * @access private
	 */
	private $params;
		
	static function get($a, $b){
		require 'config/config.ini.php';
		require 'config/locales/'.$lang.".php";
		return $t[$a][$b];
	}
}

?>