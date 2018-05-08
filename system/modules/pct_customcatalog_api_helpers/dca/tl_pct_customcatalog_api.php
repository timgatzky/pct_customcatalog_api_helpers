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
 * Table tl_pct_customcatalog_api
 */

/**
 * Config
 */
$GLOBALS['TL_DCA']['tl_pct_customcatalog_api']['config']['onload_callback'][] = array('PCT\CustomCatalog\API\Helpers\Backend\TableCustomCatalogApi', 'modifyDca');


/**
 * Subpalettes 
 */
$GLOBALS['TL_DCA']['tl_pct_customcatalog_api']['subpalettes']['source_xml'] = 'singleSRC';
$GLOBALS['TL_DCA']['tl_pct_customcatalog_api']['subpalettes']['target_xml'] = 'filenameLogic,path,sendToBrowser';