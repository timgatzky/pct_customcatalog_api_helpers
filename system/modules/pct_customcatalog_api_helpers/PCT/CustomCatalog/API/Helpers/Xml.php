<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @copyright	Tim Gatzky 2017
 * @author		Tim Gatzky <info@tim-gatzky.de>
 * @package		pct_customelements
 * @subpackage	pct_customcatalog_api_eautoseller
 * @api			http://www.eautoseller.de/	
 * @link		http://contao.org
 */

/**
 * Namespace
 */
namespace PCT\CustomCatalog\API\Helpers;

/**
 * Class file
 * Xml
 * Provides methods to handle xml files and content related to eautoseller api
 */
class Xml extends \PCT\CustomCatalog\API\Controller
{
	/**
	 * Path to the file
	 * @var string
	 */
	protected $strFile = '';

	/**
	 * The xml object
	 * @var object
	 */
	protected $objXml;


	/**
	 * Create new instance for a certain file
	 * @param string Path to the file
	 */
	public function __construct($strFile)
	{
		$this->strFile = $strFile;
	}


	/**
	 * Parse an xml string and return as array
	 * @param boolean Return xml object as array
	 * @return array
	 */
	public function getData($blnReturnAsArray=false)
	{
		$varReturn = $this->parse();

		if($blnReturnAsArray)
		{
			$varReturn = @json_decode(@json_encode($varReturn),1);
		}

		return $varReturn;
	}


	/**
	 * Return the xml data as array structure
	 * @return
	 */
	public function getDataAsArray()
	{
		return $this->getData(true);
	}


	/**
	 * Parse an xml file and return the xml as object/array
	 * @param string Path to the file
	 * @retrun string XML content
	 */
	public function parse()
	{
		if($this->isModified('objXml'))
		{
			return $this->get('objXml');
		}

		// check if simplexml is loaded
		if(extension_loaded('simplexml') === false)
		{
			\System::loadLanguageFiles('default');

			// write error log
			\System::log($GLOBALS['TL_LANG']['PCT_CUSTOMCATALOG_API_EAUTOSELLER']['ERR']['no_simplexml'],__METHOD__,TL_ERROR);

			return '';
		}

		// parse the file without cdata
		$objXml = simplexml_load_file( $this->strFile,'SimpleXMLElement',LIBXML_NOCDATA );

		// set
		$this->set('objXml',$objXml);

		// mark as modified
		$this->markAsModified('objXml');

		return $objXml;
	}
	
	
	/**
	 * Find a specific node using xpath
	 * @param string
	 */
	public function find($strNode='')
	{
		// parse the xml file and retrieve simple xml object
		$objXml = $this->parse();
		return $objXml->xpath('//'.$strNode);
	}
}