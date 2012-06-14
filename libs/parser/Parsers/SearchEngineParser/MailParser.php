<?php


/**
 * class MailParser
 * 
 */
class MailParser extends SearchEngineParser
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
		$this->url 		.=	$this->local."&sf=";
		$this->pattern 	= 	'~<div class="upll"><cite class="src">~';
		$this->start	=	0;
		$this->step		=	10;	
		$this->smart	=	"&pagelen=50";	
	
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

		if ($this->is_banned()){

			$this->nextProxy();
					
			$this->getContent($this->curl);           	
			$this->eregContent(); 					
		}		
		
		$this->cropContent();
		
		if (stripos($this->content, '/'.$this->site.'/') === false){
			
			$pos=stripos($this->content, '/www.'.$this->site.'/');
                
			if (empty($pos)){
				$pos=stripos($this->content, $this->site.'/'); 
			}    
			
		} else {
			
			$pos=stripos($this->content, '/'.$this->site.'/');
			
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

		$result = $prefix+$position+1;
		
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

	/**
	 * 
	 *
	 * @param string content 

	 * @return 
	 * @access protected
	 */
	protected function is_banned( ) {
		
		return false;

	} // end of member function is_banned




} // end of YandexParser
?>
