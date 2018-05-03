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
namespace PCT\CustomCatalog\API\Helpers\Backend;


/**
 * Class TableCustomElement
 */
class TableCustomCatalogJob extends \Backend
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
		
		// fallback
		$GLOBALS['TL_DCA'][$objDC->table]['fields']['hookSRC']['eval']['decodeEntities'] = true;
		
		// @var object
		$objApi = \PCT\CustomCatalog\Models\ApiModel::findByPk($objDC->activeRecord->pid);
		
		// include xml as action option
		$GLOBALS['PCT_CUSTOMCATALOG']['API']['helpers']['actions'] = array('xml'=>'xml');
		
		// include xml as mode option
		$GLOBALS['TL_DCA'][$objDC->table]['fields']['mode']['options'][] = 'xml';
		$GLOBALS['TL_DCA'][$objDC->table]['fields']['mode']['reference'] = $GLOBALS['TL_LANG'][$objDC->table]['mode_api_helpers'];
		
		// use valueSRC for xpath strings
		$GLOBALS['TL_DCA'][$objDC->table]['fields']['valueSRC']['eval']['decodeEntities'] = true;
		if($objDC->activeRecord->action == 'xml')
		{
			$GLOBALS['TL_DCA'][$objDC->table]['fields']['valueSRC']['label'] = &$GLOBALS['TL_LANG'][$objDC->table]['valueSRC_xpath'];
		}
		// never use the source field
		unset($GLOBALS['TL_DCA'][$objDC->table]['fields']['source']);
		
		if($objDC->activeRecord->action == 'source' && $objDC->activeRecord->mode == 'xml')
		{
			$GLOBALS['TL_DCA'][$objDC->table]['fields']['mode']['eval']['tl_class'] .= ' clr';
		}
	}
}