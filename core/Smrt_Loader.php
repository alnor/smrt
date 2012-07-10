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
		
		if (!file_exists($path.".php")){
			echo $path.".php", "<br />";
			throw new \core\Smrt_Exception();
		}
		
		require_once $path.".php";
		
		if (!class_exists($class)){
			throw new \core\Smrt_Exception();	
		}		
		
	} // end of member function loading
		
}

function __autoload( $class ) {
	Smrt_Loader::loading( $class );
} 
?>