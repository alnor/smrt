<?php

/**
 * class RamblerParser
 * 
 */
class RamblerParser extends SearchEngineParser
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
		
		$this->url		.=	"&page=";
		$this->pattern	= 	'~<li class="b-serp__list_item">~';
		$this->start	=	1;
		$this->step		=	1;	
		$this->smart	=	"&pagelen=50";	
		
		if (!empty($this->local)):
		
			$this->proxyStrategy->setCookiePeace("geoid=".$this->local.";");
			$this->proxyStrategy->modCookie(); 

		endif;
	
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
		
		$yandexVisitor = new YandexVisitor();

		if (!$this->content || $this->is_banned($yandexVisitor)){

			$this->message = "Капча Rambler.";
				
			$this->getContentBlock();

			$i=0;
					
			$getBannedBool = $this->content ? $this->is_banned($yandexVisitor) : true;
            	
			while ($getBannedBool===true){
		            	
				$this->getContentBlock();	           		
	           		
				$i++;	            	

				$getBannedBool = $this->content ? $this->is_banned($yandexVisitor) : true;
		            	
				if ($i==15) break;
			
			}  	            	   		
        		
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

    	$prefix_alt=($prefix-1)*10;
		$result = $prefix_alt+$position;
		
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

    	if (empty($this->content)) return "";
    	
    	$this->content=preg_replace('/<style.*?<\/style>/', '', $this->content);
    	$this->content=preg_replace('/<script.*?<\/script>/', '', $this->content);
    	preg_match('/<div class="b-global-wrapper">(.*)/', $this->content, $matches);

		if (isset($matches[0])){
			$this->content = $matches[0];
		}    	

	} // end of member function cropContent



} // end of RamblerParser
?>
