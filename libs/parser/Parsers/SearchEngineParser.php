<?php

/**
 * class SearchEngineParser
 * 
 */
abstract class SearchEngineParser extends Parsers
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/

	/**
	 * 
	 * @access protected
	 */
	protected $url;

	/**
	 * 
	 * @access protected
	 */
	protected $curl;
		
	/**
	 * 
	 * @access protected
	 */
	protected $start;

	/**
	 * 
	 * @access protected
	 */
	protected $step;

	/**
	 * 
	 * @access protected
	 */
	protected $message;

	/**
	 * 
	 * @access protected
	 */
	protected $banned = array();

	/**
	 * 
	 * @access protected
	 */
	protected $local;

	/**
	 * 
	 * @access protected
	 */
	protected $smart;

	/**
	 * 
	 * @access protected
	 */
	protected $pattern;

	/**
	 * 
	 * @access protected
	 */
	protected $smart_search;

	/**
	 * 
	 * @access protected
	 */
	protected $keyword;

	/**
	 * 
	 * @access protected
	 */
	protected $site;

	/**
	 * 
	 * @access protected
	 */
	protected $last_position;

	/**
	 * 
	 * @access protected
	 */
	protected $page;


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
		
    	$this->last_position 				= 	$param["last_position"];
    	$this->local 						= 	$param["local"];
    	$this->smart_search					=	$param["smart_search"];
    	$this->keyword 						= 	$param["keyword"];
    	$this->site 						= 	$param["site"];  	
        $this->url 							= 	str_replace("%%Value%%", urlencode($this->keyword), $param["request"]);  
        		
	} // end of member function __construct

	/**
	 * 
	 *  
	 * @author   Борисов Алексей <programming@semcrm.net>
	 * @return   array result
	 */
    public function parsePattern(){   	
       
        $ret_arr = $this->go();      
        
        return $this->result($ret_arr);
        
    }  
    
	
	protected function result($ans){

        $result = array();
        
        $result["keyword"]=$this->keyword;
        
        if ($ans){           
            $result["position"]=$ans["result"];
            $result["page_href"] = $ans["page_href"];
        } else {
            $result["position"]=0;    
            $result["page_href"] =$this->url;
        }
        
        if (!empty($this->message)){
        	$result["message"] = $this->message;
        }
        
        if (!empty($this->banned)){
        	$result["banned"] = $this->banned;
        }
        
        $ip=$this->proxyStrategy->getIp();
        
        $result["ip"] = $ip;
        
        return $result;		
	}    

	/**
	 *  
	 * @author   Борисов Алексей <programming@semcrm.net>
	 * @return   int position
	 */  
      
    protected function getPosition(){
	      
		if ((strlen($this->last_position) == 1) || (empty($this->last_position))): 
			$this->page = 0; 
		else: 
			$this->page = substr($this->last_position,0,1);
			
			if (substr($this->last_position,1,2)==0):
				$this->page--;
			endif;
						
		endif;
		
		$ret = $this->getResult();
		
		if (!$ret):
		    $ret = $this->cycle();				
		endif;		
	   		  
	    return $ret;    

    }
    
    /**
     * 
     * @author Борисов Алексей <programming@semcrm.net>
     * @return int position || false
     */
    
    protected function cycle(){
    	
		for ($page = 0; $page<=4; $page++): 
		 
			if ($page==$this->page) continue;

			$this->page = $page;
			
			$ret = $this->getResult();	
									
			if ($ret) return $ret;
								        		         	
		endfor;   

		return false;
		
    }
    
    protected function getResult(){
    	
		$smart="";
		$ret_array=array();
  		
		$prefix = $this->page*$this->step+$this->start;
	       		
		if (($this->last_position==0) && ($this->smart_search==1)):
			$prefix = $this->start;
			$smart = $this->smart;
		endif;	        
	        
		$this->curl = $this->url.$prefix.$smart;
   
		$this->getContent($this->curl);
		$this->eregContent();      

		if (empty($this->content)):
            
			$this->nextProxy();
				
            $this->getContent($this->curl);           	
            $this->eregContent();             
		endif;
            
		$pos = $this->analyzeContent( );
		
	    if ($pos):
	    	
	        $this->content = substr($this->content, 0, $pos);

	        $position = preg_match_all($this->pattern, $this->content, $matches);

	    	return $this->analyzePosition($prefix, $position);		        

		endif; 		

		return $pos;
    }
    
    /**
     * 
     * @return string content
     */
    
    protected function getContentBlock(){
    	
		$this->nextProxy();		            	
		$this->getContent($this->curl);           	
		$this->eregContent();    

    }
    
    /**
     * 
     * Crop content
     * @param string $content
     */
    
    protected function eregContent(){
		$this->content=preg_replace('/[\n\r\t]/', '', $this->content);	
		$this->content=str_replace("/<b>|<\/b>|<strong>|<\/strong>/", '', $this->content);   
    }

	/**
	 * 
	 *
	 * @return 
	 * @abstract
	 * @access public
	 */
	abstract protected function go( );

	/**
	 * 
	 *
	 * @param string content 

	 * @return 
	 * @abstract
	 * @access public
	 */
	abstract protected function analyzeContent( );

	/**
	 * 
	 *
	 * @param string prefix 

	 * @param int position 

	 * @return 
	 * @abstract
	 * @access public
	 */
	abstract protected function analyzePosition( $prefix,  $position );

	/**
	 * 
	 *
	 * @param string content 

	 * @return 
	 * @abstract
	 * @access public
	 */
	abstract protected function cropContent( );



} // end of SearchEngineParser
?>
