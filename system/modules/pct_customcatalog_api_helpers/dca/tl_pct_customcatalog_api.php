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

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_pct_customcatalog_api']['fields']['url'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_pct_customcatalog_api']['url'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('tl_class'=>'clr long','decodeEntities'=>true),
	'sql'					  => "varchar(1024) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_pct_customcatalog_api']['fields']['connection'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_pct_customcatalog_api']['connection'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'				  => array('ftp'),
	'reference'				  => &$GLOBALS['TL_LANG']['tl_pct_customcatalog_api']['connection'],
	'eval'                    => array('tl_class'=>'clr w50','submitOnChange'=>true,'includeBlankOption'=>true),
	'sql'					  => "varchar(128) NOT NULL default ''",
);

$GLOBALS['TL_DCA']['tl_pct_customcatalog_api']['fields']['connections'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_pct_customcatalog_api']['connections'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'		  => array('PCT\CustomCatalog\API\Helpers\Backend\TableCustomCatalogApi', 'getConnections'),
	'eval'                    => array('tl_class'=>'w50','includeBlankOption'=>true,'chosen'=>true),
	'sql'					  => "varchar(128) NOT NULL default ''",
);
