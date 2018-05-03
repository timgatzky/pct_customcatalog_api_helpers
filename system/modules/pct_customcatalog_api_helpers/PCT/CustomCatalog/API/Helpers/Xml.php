<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @copyright	Tim Gatzky 2017
 * @author		Tim Gatzky <info@tim-gatzky.de>
 * @package		pct_customelements
 * @subpackage	pct_customcatalog_api_helpers
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
	 * @param string	Path to the file
	 * @retrun object	SimpleXML
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
			// write error log
			\System::log('PHP SimpleXML not found or not loaded. : http://php.net/manual/de/book.simplexml.php',__METHOD__,TL_ERROR);

			return null;
		}
		
		if(file_get_contents($this->strFile) == '')
		{
			\System::log('File source ('.$this->strFile.') is empty',__METHOD__,TL_ERROR);
			return null;
		}
	
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
		
		if($objXml === null)
		{
			return '';
		}
		
		return $objXml->xpath('//'.$strNode);
	}
	
	
	/**
	 * Find a value in an xml object
	 * @param string 			Xpath search string
	 * @param object 			Optional the xml object to search
	 * @return array|boolean	Return value or boolean false if nothing was found
	 * 
	 * Example return node values: myParentNode/myChildNode
	 * Example return attribute values: myParentNode/myChildNode->myAttribute
	 */
	public function findValue($strSearch, \SimpleXMLElement $objXml=null)
	{
		if($objXml === null)
		{
			$objXml = $this->parse();
		}
		
		// syntax: /query->attribute
		$arrQuery = explode('->', str_replace('"', "'", $strSearch) );
		$strXpath = ltrim($arrQuery[0],'//');
		
		// do an xpath search on the xml
		$arrResults = $objXml->xpath("//".$strXpath);
		
		if($arrResults === null)
		{
			return false;
		}
		
		// attribute values
		$strAttribute = '';
		if(isset($arrQuery[1]) && strlen($arrQuery[1]) > 0)
		{
			$strAttribute = str_replace(array('[',']'),'',$arrQuery[1]);
		}
		
		$arrReturn = array();
		foreach($arrResults as $result)
		{
			// attribute value
			if(strlen($strAttribute) > 0)
			{
				$arrReturn[] = (string)$result->attributes()->{$strAttribute};
				continue;
			}
			// pass the whole found
			$arrReturn[] = $result;
		}
		
		if(empty($arrReturn))
		{
			return false;
		}
		
		return $arrReturn;
	}
}