<?php

/**
 * class YahooParser
 * 
 */
class YahooParser extends SearchEngineParser
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/



	/**
	 * 
	 *
	 * @return position || false
	 * @access protected
	 */
	protected function go( ) {
		
		$this->url 			.=	"&b=";
		$this->pattern		=	'~<div class="res">~';
		$this->start		=	1;
		$this->step			=	10;
		$this->smart		=	"&n=100";		
	
	    return $this->getPosition();

	} // end of member function go

	/**
	 * 
	 *
	 * @param string content 

	 * @return 
	 * @access protected
	 */
	protected function analyzeContent( ) {
		
		$yahooVisitor = new YahooVisitor();

		if ($this->is_banned($yahooVisitor)){

			$this->nextProxy();
					
			$this->getContent($this->curl);           	
			$this->eregContent(); 					
		}		
		
		$this->cropContent();
		
		if (stripos($this->content, $this->site.'.') === false){
			
			$pos=stripos($this->content, $this->site);
			
		}else{
			
			$pos=false;
		}	

		return $pos;

	} // end of member function analyzeContent

	/**
	 * 
	 *
	 * @param string prefix 

	 * @param int position 

	 * @return 
	 * @access protected
	 */
	protected function analyzePosition( $prefix,  $position ) {

		if ($prefix==1){
			
			$result=$position;
			
		} else {
			
			$result=$prefix+$position-1;
			
		}
		
		$ret_array["result"] = $result;
		$ret_array["page_href"] = html_entity_decode($this->curl);		

		return $ret_array;

	} // end of member function analyzePosition

	/**
	 * 
	 *
	 * @param string content 

	 * @return 
	 * @access protected
	 */
	protected function cropContent( ) {

		return $this->content;

	} // end of member function cropContent



} // end of YandexParser
?>
