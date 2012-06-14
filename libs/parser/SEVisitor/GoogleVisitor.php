<?php
/**
 * class GoogleVisitor
 * 
 */
class GoogleVisitor extends SEVisitor
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
		
		if (preg_match("/\/sorry\/image\?id=\d+\w+/", $this->content, $matches)){
			return true;
		}
		
		return false;	
			
	} // end of member function is_banned



} // end of GoogleVisitor
?>
