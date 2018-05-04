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
 * Table tl_pct_customcatalog_job
 */

/**
 * Config
 */
$GLOBALS['TL_DCA']['tl_pct_customcatalog_job']['config']['onload_callback'][] = array('PCT\CustomCatalog\API\Helpers\Backend\TableCustomCatalogJob', 'modifyDca');

/**
 * Subpalettes 
 */
$GLOBALS['TL_DCA']['tl_pct_customcatalog_job']['subpalettes']['action_xml'] = 'valueSRC';