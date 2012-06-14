<?php

require_once 'GetStrategy.php';
require_once 'GetProxyStrategy.php';
require_once 'SEVisitor.php';

require_once 'SEVisitor/YandexVisitor.php';
require_once 'SEVisitor/GoogleVisitor.php';
require_once 'SEVisitor/YahooVisitor.php';

require_once 'Parsers/SearchEngineParser.php';

require_once 'Parsers/SearchEngineParser/YandexParser.php';
require_once 'Parsers/SearchEngineParser/GoogleParser.php';
require_once 'Parsers/SearchEngineParser/RamblerParser.php';
require_once 'Parsers/SearchEngineParser/YahooParser.php';
require_once 'Parsers/SearchEngineParser/MailParser.php';
require_once 'Parsers/SearchEngineParser/MSNParser.php';

require_once 'Parsers/SeoParametersParser.php';
require_once 'memcache.php';

/**
 * class Parsers
 * 
 */
abstract class Parsers
{

	/** Aggregations: */

	/** Compositions: */
	var $m_;

	 /*** Attributes: ***/

	/**
	 * 
	 * @access protected
	 */
	protected $proxyStrategy;

	/**
	 * 
	 * @access private
	 */
	public $component;

	/**
	 * 
	 * @access private
	 */
	protected $content;	
	
	/**
	 * 
	 * @access private
	 */
	protected $visitor;		

	/**
	 * 
	 *
	 * @param GetStrategy gs 

	 * @param GetProxyStrategy gps 

	 * @return 
	 * @access public
	 */
	public function __construct( GetStrategy $gs,  GetProxyStrategy $gps ) {
		$this->component =  $gs;
		$this->proxyStrategy = $gps;
		//file_put_contents("/home/alex/git/semcrm/tmp/main_".(rand(1,1000000)).".txt", 1);
		$this->component->setProxyStrategy($this->proxyStrategy);
	} // end of member function __construct

	/**
	 * 
	 *
	 * @param string url 

	 * @return string
	 * @access public
	 */
	public function getContent( $url ) {
		$this->content = $this->component->getContent( $url );
	} // end of member function getContent

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function nextProxy( ) {
		$this->proxyStrategy->nextProxy();
	} // end of member function nextProxy


	/**
	 * 
	 * Проверяет на каптчу контент
	 * @param SEVisitor visitor 

	 * @return 
	 * @access protected
	 */
	protected function is_banned( SEVisitor $visitor ) {
		return $visitor->captchaFinder($this->content);
	} // end of member function is_banned


} // end of Parsers
?>
