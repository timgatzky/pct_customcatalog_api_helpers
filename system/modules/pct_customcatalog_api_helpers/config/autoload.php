<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'PCT',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'PCT\CustomCatalog\API\Helpers\Functions' 	=> 'system/modules/pct_customcatalog_api_helpers/PCT/CustomCatalog/API/Helpers/Functions.php',
	'PCT\CustomCatalog\API\Helpers\Xml' 		=> 'system/modules/pct_customcatalog_api_helpers/PCT/CustomCatalog/API/Helpers/Xml.php',
	'PCT\CustomCatalog\API\Helpers\Callbacks' 	=> 'system/modules/pct_customcatalog_api_helpers/PCT/CustomCatalog/API/Helpers/Callbacks.php',
	'PCT\CustomCatalog\API\Helpers\Backend\TableCustomCatalogJob' 		=> 'system/modules/pct_customcatalog_api_helpers/PCT/CustomCatalog/API/Helpers/Backend/TableCustomCatalogJob.php',
	'PCT\CustomCatalog\API\Helpers\Backend\TableCustomCatalogApi' 		=> 'system/modules/pct_customcatalog_api_helpers/PCT/CustomCatalog/API/Helpers/Backend/TableCustomCatalogApi.php',
));
