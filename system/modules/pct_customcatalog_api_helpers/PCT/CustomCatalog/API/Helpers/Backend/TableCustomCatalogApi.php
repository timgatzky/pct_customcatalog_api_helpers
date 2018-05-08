<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @copyright	Tim Gatzky 2014
 * @author		Tim Gatzky <info@tim-gatzky.de>
 * @package		pct_customelements
 * @subpackage	pct_customelements_plugin_customcatalog
 * @link		http://contao.org
 */

/**
 * Namespace
 */
namespace PCT\CustomCatalog\API\Helpers\Backend;


/**
 * Class TableCustomCatalogApi
 */
class TableCustomCatalogApi extends \Backend
{
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		\System::import('BackendUser', 'User');
		\System::loadLanguageFile('apis');
	}
	
	
	/**
	 * Modify the dca
	 * @param object
	 */
	public function modifyDca($objDC)
	{
		if(!$objDC->activeRecord)
		{
			$objDC->activeRecord = \Database::getInstance()->prepare("SELECT * FROM ".$objDC->table." WHERE id=?")->limit(1)->execute($objDC->id);
		}
		
		// reduce source options to select a xml file only
		$GLOBALS['TL_DCA'][$objDC->table]['fields']['source']['options'][] = 'xml';
		
		if($objDC->activeRecord->source == 'xml')
		{
			// reduce target options
			$GLOBALS['TL_DCA'][$objDC->table]['fields']['target']['options'] = array('hook','xml','external');
			
			// allow xml files only
			$GLOBALS['TL_DCA'][$objDC->table]['fields']['singleSRC']['eval']['extensions'] = 'xml';
			
			// add backend message 
			\Message::addInfo($GLOBALS['TL_LANG']['tl_pct_customcatalog_api']['uniqueSource']['xml_info']);
		}
	}
}