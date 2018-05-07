<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @copyright	Tim Gatzky 2018
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
 * Callbacks
 */
class Callbacks extends \PCT\CustomCatalog\API\Controller
{
	/**
	 * Handle XML api jobs
	 * @param string	Name of the table
	 * @param object	API object
	 * @param object	Job object
	 *
	 * called from: $GLOBALS['CUSTOMCATALOG_HOOKS']['executeApiJob']
	 */
	public function executeApiJobs($strTable, $objApi, $objJob)
	{
		if($objJob->action != 'xml')
		{
			return;
		}
		
		$objXml = null;
		$arrInput = $objJob->input();
		$arrOutput = $arrInput;
		
		if(!is_array($arrOutput))
		{
			$arrOutput = array();
		}
		
		$intIndex = $objJob->get('data_index'); // CC >= 2.6.0
		
		// look up from cache
		$strCacheKey = 'xml_'.$objApi->id.'_'.$intInternalId;
		if( \PCT\CustomElements\Plugins\CustomCatalog\Core\Cache::getApiAffectedData($strCacheKey) )
		{
			$objXml = \PCT\CustomElements\Plugins\CustomCatalog\Core\Cache::getApiAffectedData($strCacheKey);
		}
		
		// local file
		if($objXml === null && strlen($objApi->singleSRC) > 0)
		{
			$strFile = TL_ROOT.'/'.\FilesModel::findByPk($objApi->singleSRC)->path;
			$objXml = new \PCT\CustomCatalog\API\Helpers\Xml($strFile);
			// create a simple local xml object to gain access via xpath to the current xml only
			$objXml = new \SimpleXMLElement( $objXml->parse()->saveXML() );
		}

		if($objXml === null)
		{
			\System::log('API ('.$objApi->id.') No XML data found in index:'.$intIndex,__METHOD__,TL_ERROR);
			return;
		}
		
		// add to cache
		\PCT\CustomElements\Plugins\CustomCatalog\Core\Cache::addApiAffectedData($strCacheKey,$objXml);
		
		// use the helper to find the value
		$varReturn = '';
		if(!empty($objJob->valueSRC))
		{
			$varReturn = \PCT\CustomCatalog\API\Helpers\Xml::findValue($objJob->valueSRC, $objXml);
			
			if($varReturn === false)
			{
				return;
			}
			
			// point to a value depending on the index
			if(isset($varReturn[ $intIndex ]))
			{
				$varReturn = $varReturn[ $intIndex ];
			}
			else
			{
				$varReturn = implode(',',$varReturn);
			}
		}
		
		
		// pass couple information to the callback function
		$objParams = new \StdClass;
		$objParams->xml = $objXml;
		$objParams->api = $objApi;
		$objParams->table = $strTable;
		$objParams->job = $objJob;
		$objParams->data = $arrInput;
		$objParams->index = $intIndex;
				
		// Hook to modify the xml results before passing them to the output department
		if (isset($GLOBALS['CUSTOMCATALOG_HOOKS']['xmlDataOutput']) && count($GLOBALS['CUSTOMCATALOG_HOOKS']['xmlDataOutput']) > 0)
		{
			foreach($GLOBALS['CUSTOMCATALOG_HOOKS']['xmlDataOutput'] as $callback)
			{
				$varReturn = \System::importStatic($callback[0])->{$callback[1]}($varReturn,$objParams);
			}
		}
		
		$arrOutput[$objJob->target] = $varReturn;
		
		
		// apply	
		$objJob->output($arrOutput);
	}

}

