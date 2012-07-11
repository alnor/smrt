<?php

namespace core;

/**
 * class Smrt_Common
 * 
 */

class Smrt_Common
{

	/** Aggregations: */

	/** Compositions: */
	
	 /*** Attributes: ***/
	
	/**
	 * Возвращает перевод
	 * Простая обертка для класса Smrt_Lang.
	 * @return 
	 * @access public
	 */
	public function lang( $type, $message ) {
		return \core\Smrt_Lang::get($type, $message);
	} // end of member function $column	

} // end of Smrt_Request
?>
