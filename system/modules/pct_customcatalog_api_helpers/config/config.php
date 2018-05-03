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
 * @api			http://www.eautoseller.de/	
 * @link		http://contao.org
 */

/**
 * Constants
 */
define('PCT_CUSTOMCATALOG_API_HELPERS_VERSION', '1.1.0');

/**
 * Hooks
 */
$GLOBALS['CUSTOMCATALOG_HOOKS']['executeApiJob'][] = array('PCT\CustomCatalog\API\Helpers\Callbacks','executeXmlApiJobs');