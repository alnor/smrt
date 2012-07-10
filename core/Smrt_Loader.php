<?php

/**
 * class Smrt_FrontController
 * 
 */
class Smrt_Loader
{
	
	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public static function loading( $class ) {
		$path = str_replace("\\", "/", $class);
		require_once $path.".php";
	} // end of member function loading
		
}

function __autoload( $class ) {
	Smrt_Loader::loading( $class );
} 
?>