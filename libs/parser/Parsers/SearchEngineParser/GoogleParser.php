<?php


/**
 * class GoogleParser
 * 
 */
class GoogleParser extends SearchEngineParser
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
		
		$this->url 		.= 	"&start=";
		$this->pattern 	= 	'~<br/><div><cite>~';
		$this->start	=	0;
		$this->step		=	10;
		$this->smart	=	"&num=100";
		
		if (!empty($this->local)):
		
			$this->proxyStrategy->setCookiePeace("PREF=ID=ed1c9ee7e917732f:FF=0:L=".$this->local.";");
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
		
		$googleVisitor = new GoogleVisitor();

		if (!$this->content || $this->is_banned($googleVisitor)){

			$this->message = "Капча Google.";
				
			$this->getContentBlock();

			$i=0;
					
			$getBannedBool = $this->content ? $this->is_banned($googleVisitor) : true;
            	
			while ($getBannedBool===true){
		            	
				$this->getContentBlock();	           		
	           		
				$i++;	            	

				$getBannedBool = $this->content ? $this->is_banned($googleVisitor) : true;
		            	
				if ($i==15) break;
			
			}  	            	   		
        		
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

    	if (empty($this->content)) return "";
    	
    	preg_match('/<div id="res"(.*)/', $this->content, $matches);
    	
		if (isset($matches[0])){
			$this->content = $matches[0];
		}    	

	} // end of member function cropContent



} // end of YandexParser
?>
