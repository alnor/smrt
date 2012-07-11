<?php

namespace core;

/**
 * class Smrt_Request
 * 
 */
use core\Smrt_Controller;

class Smrt_Module extends \core\Smrt_Common
{

	/** Aggregations: */

	/** Compositions: */
	
	 /*** Attributes: ***/

	/**
	 * Объект контроллера.
	 * Содержит объект контроллера, который создает данный модуль.
	 * @access protected
	 */
	protected $controller;
		
	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function __construct( \core\Smrt_Controller $controller) {
		$this->controller = $controller;
	} // end of member function __construct
	

	/**
	 * Возвращает содержимое модуля.
	 * 
	 * Вызывается после метода модуля, который устанавливает необходимые переменные для представления.
	 * Возвращается готовый блок представления, который используется контроллером.
	 * @return 
	 * @access public
	 */	
	public function getView( $action=false, $theme=false ){
		return $this->controller->render( $action, $theme, true);
	}	

} // end of Smrt_Request
?>
