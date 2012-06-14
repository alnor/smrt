<?php


/**
 * class YandexParser
 * 
 */
class YandexParser extends SearchEngineParser
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/



	/**
	 * 
	 * Устанавливаем свойства
	 * @return position || false
	 * @access protected
	 */
	protected function go( ) {
		$this->url 		.= 	$this->local."&p=";
		$this->pattern 	= 	'~<li class="b-serp-item\s*">~';
		$this->start	=	0;
		$this->step		=	1;
		$this->smart	=	"&numdoc=50";
		
		if (!empty($this->local)){
			
			$this->proxyStrategy->setCookiePeace("yandex_gid=".$this->local."; ");
			$this->proxyStrategy->modCookie();

		}

	    return $this->getPosition();
	} // end of member function go



	protected function result($ans){

		$result = parent::result($ans);

		if (!$ans){ 
			$result["page_href"] =$this->url."0";
		}
		
		return $result;
	}

	/**
	 * 
	 * Анализируем контент и ищем позицию
	 * @param string content 

	 * @return 
	 * @access protected
	 */
	protected function analyzeContent( ) {
		
		$yandexVisitor = new YandexVisitor();
		
		if (!$this->content || $this->is_banned($yandexVisitor)){

			$this->message = "Капча Yandex.";

			$i=0;
					
			do {
		            	
				$this->getContentBlock();	           		
	           		
				$i++;	            	

				$getBannedBool = $this->content ? $this->is_banned($yandexVisitor) : true;
		            	
				if ($i==15) break;
				
				$getBannedBool = $this->content ? $this->is_banned($yandexVisitor) : true;
			
			} while ($getBannedBool); 	

			$this->banned = $this->proxyStrategy->getBannedProxy();
	
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
	 * Вычисляем позицию в зависимости от префикса страницы
	 * @param string prefix 

	 * @param int position 

	 * @return 
	 * @access protected
	 */
	protected function analyzePosition( $prefix,  $position ) {

		$prefix_alt=$prefix*10;
		$result = $prefix_alt+$position;
		
		$ret_array["result"] = $result;
		$ret_array["page_href"] = html_entity_decode($this->curl);		

		return $ret_array;

	} // end of member function analyzePosition

	/**
	 * 
	 * Вырезаем рабочую часть
	 * @param string content 

	 * @return 
	 * @access protected
	 */
	protected function cropContent( ) {

		if (empty($this->content)) return "";
	    	
		preg_match('/(<div class="b-body-items)(.*)/', $this->content, $matches);
		
		if (isset($matches[0])){
			$this->content = $matches[0];
		}	

	} // end of member function cropContent


} // end of YandexParser
?>
