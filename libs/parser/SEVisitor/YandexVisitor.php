<?php

/**
 * class YandexVisitor
 * 
 */
class YandexVisitor extends SEVisitor
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/


	/**
	 * 
	 * Проверка на каптчу
	 * @param string content 

	 * @return 
	 * @access protected
	 */
	protected function is_banned( ) {
		
		if (preg_match("/http:\/\/yandex.ru\/captchaimg\?\S+(?=\")/", $this->content, $matches)):
			return true;
		endif;
		
		return false;		
		
	} // end of member function is_banned



} // end of YandexVisitor
?>
