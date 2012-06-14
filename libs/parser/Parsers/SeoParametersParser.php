<?php

/**
 * class SeoParametersParser
 * 
 */
class SeoParametersParser extends Parsers
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/
	
	/**
	 * 
	 * @access public
	 */
	public $lastValue;	
	
	/**
	 * Counstructor
	 *
	 * @param string params
	 * @param GetStartegy gs 
	 * @param GetProxyStrategy gps 

	 * @return 
	 * @access public
	 */
	public function __construct( GetStrategy $gs,  GetProxyStrategy $gps, $param=array() ) {
		
		parent::__construct($gs, $gps);

    	$this->site = 	$param["site"];  	 
        		
	} // end of member function __construct	


	/**
	 * setLastValue
	 *
	 * @param int val

	 * @return 
	 * @access public
	 */
	public function setLastValue( $val ) {

    	$this->lastValue = 	$val;  	 
        		
	} // end of member function setLastValue	
	
		
    public function getPR() {
		
		$pr = GooglePR::GetPR($this->site); 

    	if (empty($this->lastValue)) $this->lastValue=0;     	                      	
		
		if (($pr == 0) && ($this->lastValue>0)):
		
			$this->getContent("http://www.google-pr.net/check-pagerank.php?u=".$this->site);
			
			$googleVisitor = new GoogleVisitor();
			
			if (!$this->content || $this->is_banned($googleVisitor)):
			
				$this->nextProxy();
				$this->getContent("http://www.google-pr.net/check-pagerank.php?u=".$this->site);
				
			endif;			
			
			preg_match("/http:\/\/www\.google-pr\.net\/images\/pr\/Google\/pr(\d+)\.gif/", $this->content, $matches);
			
			$pr=$matches[1];	
				
		endif;

		return (($pr == 0) && ($this->lastValue>0)) ? $this->lastValue : $pr;
    }	
    
	/**
	 * Парсим Yandex Тиц 
	 */	    
	
    public function getTCY() { 

    	if (empty($this->lastValue)) $this->lastValue=0;    	                      	
    	                      			    	
      	$xml = simplexml_load_file('http://bar-navig.yandex.ru/u?ver=2&show=32&url=http://'.urlencode($this->site));
      	$result = $xml ? $xml->tcy->attributes() : $this->lastValue;
      	
       	return (int) $result['value'];
    }
    
	/**
	 * Парсим Yahoo Backlinks  
	 */    

	public function getYahooBl() {
		
		$yahoo_inlinks_url = "http://siteexplorer.search.yahoo.com/search?p=".urlencode($this->site)."&bwm=i&bwmf=s&bwmo=d";
		
		$this->getContent($yahoo_inlinks_url);
		
		preg_match('#<span\s*class\s*=\s*["\']?btn["\']?\s*>Inlinks\s*\(([0-9,]+)\)\s*</span>\s*#ui', $this->content, $matches);
		
		$last_yahoo_bl = DB::GetAssoc("	SELECT t1.yahoo_bl
    	                      				FROM semcrm_site_params t1
    	                      				WHERE t1.project_id = %d 
    	                      				ORDER BY created_on DESC
    	                      				LIMIT 1",
    	                      				array($project_id)
    	                      				);	
    	                      					
    	if (empty($this->lastValue)) $this->lastValue=0;		
    	                      				
		return (!empty($matches[1])) ? str_replace(',', '', $matches[1]) : $this->lastValue;	
	}
	
	/**
	 * Парсим Google Backlinks  
	 */    

	public function getGoogleIndexed() {   

		$google_indexed_url = "http://www.google.com/search?hl=en&q=site:".urlencode($this->site);
		
		$this->getContent($google_indexed_url);
		
		$googleVisitor = new GoogleVisitor();
		
		if (!$this->content || $this->is_banned($googleVisitor)):
		
			$this->nextProxy();
			$this->getContent($google_indexed_url);
				
	    	$i=0;

	    	$getBannedBool = $this->content ? $this->is_banned($googleVisitor) : true;
	            	
	    	while ($getBannedBool===true):
	    	
				$this->nextProxy();
				$this->getContent($google_indexed_url);	   
				
				$getBannedBool = $this->content ? $this->is_banned($googleVisitor) : true;
						            
		        $i++;    	
				if ($i==15) break;
				
	    	endwhile;			
			
		endif;	
    	                      				
		if (empty($this->lastValue)) $this->lastValue=0;     	                      				

		if (preg_match('#<div>\s*About\s*([0-9,]+)\s*results<\/div>#ui', $this->content, $gmatches) != 0):
			return (!empty($gmatches[1])) ? str_replace(',', '', $gmatches[1]) : $this->lastValue;	
		endif;
		
		if (preg_match('#<div>\s*([0-9,]+)\s*results<\/div>#ui', $this->content, $gmatches) != 0):
			return (!empty($gmatches[1])) ? str_replace(',', '', $gmatches[1]) : $this->lastValue;	
		endif;	

		return 0;	
	}	
	
	public function getGooglePis() {   

		$google_indexed_url = "http://www.google.com/search?hl=en&q=site:".urlencode($this->site)."/*";
				
		$this->getContent($google_indexed_url);	
		
		$googleVisitor = new GoogleVisitor();
		
		if (!$this->content || $this->is_banned($googleVisitor)):
		
			$this->nextProxy();
			$this->getContent($google_indexed_url);
				
	    	$i=0;

	    	$getBannedBool = $this->content ? $this->is_banned($googleVisitor) : true;
	            	
	    	while ($getBannedBool===true):
	    	
				$this->nextProxy();
				$this->getContent($google_indexed_url);	   
				
				$getBannedBool = $this->content ? $this->is_banned($googleVisitor) : true;	
						            
		        $i++;    	
				if ($i==15) break;
				
	    	endwhile;			
			
		endif;		

		if (empty($this->lastValue)) $this->lastValue=0;     	                      				

		if (preg_match('#<div>\s*About\s*([0-9,]+)\s*results<\/div>#ui', $this->content, $gmatches) != 0):
			return (!empty($gmatches[1])) ? str_replace(',', '', $gmatches[1]) : $this->lastValue;	
		endif;
		
		if (preg_match('#<div>\s*([0-9,]+)\s*results<\/div>#ui', $this->content, $gmatches) != 0):
			return (!empty($gmatches[1])) ? str_replace(',', '', $gmatches[1]) : $this->lastValue;	
		endif;	

		return 0;
		
	}		
	

	public function getYandexIndexed() {
		$buf="";
		$buf_www="";
		$yandex_indexed_url = 'http://webmaster.yandex.ru/check.xml?hostname='.urlencode($this->site);
		$yandex_indexed_url_www = 'http://webmaster.yandex.ru/check.xml?hostname=www.'.urlencode($this->site);
		
		$this->getContent($yandex_indexed_url);
		
		$yandexVisitor = new YandexVisitor();
		
		if (!$this->content || $this->is_banned($yandexVisitor)):
			$this->nextProxy();
			$this->getContent($yandex_indexed_url);		
			$buf = $this->content;
		endif;	
		
		$this->getContent($yandex_indexed_url_www);
		
		if (!$this->content || $this->is_banned($yandexVisitor)):
			$this->nextProxy();	
			$this->getContent($yandex_indexed_url_www);	
			$buf_www = $this->content;	
		endif;
	
		if (preg_match('#Страницы:\s*([0-9]+)\s*#ui',$buf,$match)==1):
			return $match[1];
		endif;
		
		if (preg_match('#Страницы:\s*([0-9]+)\s*#ui', $buf_www, $gmatch)==1):
			return $gmatch[1];
		endif;	
			
		return 0;
	}	


	/*
	 * Парсим Alexa Rank  
	 */	
		
    public function getAlexaRank() {
    	                      		    	
      	$alexabody = simplexml_load_file('http://data.alexa.com/data?cli=10&dat=snbamz&url='.$this->site);
      	
      	if (isset($alexabody->SD[1])):
      		if (isset($alexabody->SD[1]->POPULARITY)):
      			$result = $alexabody->SD[1]->POPULARITY->ATTRIBUTES();		
      			return (int) $result["TEXT"];
      		endif;	
      	endif;	
      	
      	return 0;
    }    
    
    public function getDomainAge() {
    	
		$domain_age_url = "http://who.is/whois/".$this->site."/";
		$this->getContent($domain_age_url);
		
		//(strpos($site, ".com") || strpos($site, ".net"))
		
		if (strpos($this->site, ".ru") || strpos($this->site, ".su")):
			if (preg_match('#created:\s*(.*)\s*<br>#ui', $this->content, $match)):
				if (isset($match[1])):
					$match[1] = str_replace("&nbsp;", "", $match[1]);
					$match[1] = str_replace(".", "-", $match[1]);
				endif;  
				return $match[1];    
			endif;		
		else:
			if (preg_match('#Creation\s*Date:\s(.*)\s*<br>#ui', $this->content, $match)):  
				if (isset($match[1])):
					$match[1] = preg_replace("/&nbsp;/", "", $match[1]);
				endif;  
				return $match[1];    
			endif;	
		endif;
		
		return 0;
    }



} // end of SeoParametersParser
?>
