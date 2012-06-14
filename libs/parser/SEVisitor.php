<?php

/**
 * class SEVisitor
 * 
 */
abstract class SEVisitor
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/

	/**
	 * 
	 * @access protected
	 */
	protected $content;	

	/**
	 * 
	 *
	 * @param string content 

	 * @return 
	 * @access public
	 */
	public function captchaFinder( $content ) {
		$this->content = $content;
		return $this->is_banned();
	} // end of member function captchaFinder

	/**
	 * 
	 *
	 * @return 
	 * @abstract
	 * @access public
	 */
	abstract protected function is_banned( );





} // end of SEVisitor
?>
