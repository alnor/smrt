<?php

/**
 * class YahooVisitor
 * 
 */
class YahooVisitor extends SEVisitor
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/




	/**
	 * 
	 *
	 * @return 
	 * @access private
	 */
	protected function is_banned( ) {
	
		if (preg_match("/http:\/\/arc.help.yahoo.com\/error.gif/", $this->content, $matches)){
			return true;
		}
		
		return false;	
		
	} // end of member function is_banned



} // end of YahooVisitor
?>
