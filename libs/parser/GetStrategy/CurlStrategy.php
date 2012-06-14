<?php


/**
 * class CurlStrategy
 * 
 */
class CurlStrategy extends GetStrategy
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/


	/**
	 * 
	 *
	 * @param string url 

	 * @return string content
	 * @access public
	 */
	public function getContent( $url ) {
		
		$ch = curl_init();
		
	    curl_setopt ($ch, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt ($ch, CURLOPT_HEADER, 1);
	    	
	    curl_setopt ($ch, CURLOPT_TIMEOUT, 30);
	    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
	    
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt ($ch, CURLOPT_MAXREDIRS, 10);
	    curl_setopt ($ch, CURLOPT_USERAGENT, $this->useragent);	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);	  
  		
		$cookie = $this->proxyStrategy->getCookie();

    	if (!empty($cookie)):
    		curl_setopt($ch, CURLOPT_COOKIE, $cookie);   	 
		endif;	
		
		$password = $this->proxyStrategy->getPassword();
		$user = $this->proxyStrategy->getUser();
		
        if (!empty($password)):
            curl_setopt ($ch, CURLOPT_PROXYUSERPWD, $user.':'.$password); 
        endif;
        
        $ip = $this->proxyStrategy->getIp();
        
        if (!empty($ip)):
            curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt ($ch, CURLOPT_PROXY, $ip);
        endif;
        
		//curl_setopt ($ch, CURLOPT_VERBOSE, 1);	
		//$fp=fopen("/home/alex/git/semcrm/tmp/err.txt", "a+");
		//curl_setopt ($ch, CURLOPT_STDERR, $fp);        
		
        curl_setopt ($ch, CURLOPT_URL, html_entity_decode( $url ));
	
	    $content = $this->curl_redirect($ch);
  		
	    curl_close ($ch);	
		//fclose($fp);
		
        return $content;	
        	
	} // end of member function getContent
	
	/**
	 * 
	 * @return string content
	 * @param descriptor $ch
	 */

    protected function curl_redirect( $ch ){
        $loops = 0;
        $max_loops = 10;
        $url = array();

        if ($loops++ >= $max_loops):
            $loops = 0;
            return false;
        endif;	
        	
        $data = curl_exec($ch);       
		        
		$header=substr($data,0,curl_getinfo($ch,CURLINFO_HEADER_SIZE));
		$data=substr($data,curl_getinfo($ch,CURLINFO_HEADER_SIZE));   	

        $temp = $data;	
      
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($http==407):    
			return false;
		endif;  
		
		if (preg_match("/(captcha|Captcha)/", $header, $m)==1):    
			return false;
		endif;		
		
        if ($http == 301 || $http == 302):
            $matches = array();

            preg_match('/Location:(.*?)\r\n/', $header, $matches);
            $url = @parse_url(trim(array_pop($matches)));

            if (!$url):
                $loops = 0;
                return $data;
            endif;
           
            $last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
 
            if (!isset($url['scheme'])):
                $url['scheme'] = $last_url['scheme'];
            endif;
               
            if (!isset($url['host'])):
                $url['host'] = $last_url['host'];
            endif;
           
            if (!isset($url['path'])):
                $url['path'] = $last_url['path'];
            endif;    

			//$new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] .'?'.str_replace(" ", "+", $url['query']);

			$new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . (isset($url['query'])?'?'.str_replace(" ", "+", $url['query']):'');

            curl_setopt($ch, CURLOPT_URL, html_entity_decode($new_url));
            
            return $this->curl_redirect($ch);	           
            
        else:
            $loops=0;
            return $temp;
        endif;
     }


} // end of CurlStrategy
?>
