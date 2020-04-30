<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) Leo Feyer
 * 
 * @copyright	Tim Gatzky 2020
 * @author		Tim Gatzky <info@tim-gatzky.de>
 * @package		pct_customelements
 * @subpackage	pct_customcatalog_api_helpers
 * @link		http://contao.org
 */


/**
 * Namespace
 */

namespace PCT\CustomCatalog\API\Connections;

/**
 * Imports
 */
use Contao\System;


/**
 * Class file
 * FTP
 * Provide methods to communicate with FTP servers
 */
class FTP
{
	/**
	 * FTP identifyer
	 * @var integer
	 */
	protected $intResource;

	/**
	 * The config array
	 * @var array
	 */
	protected $arrConfig = array();


	/**
	 * Create a new FTP connection
	 * @param array $arrConfig
	 * @return integer ID of the FTP buffer resource
	 */
	public function __construct($arrConfig)
	{
		if (empty($arrConfig) === true) {
			throw new \Exception('Missing connection information');
		}

		$this->arrConfig = $arrConfig;

		// establish connection
		$this->intResource = \ftp_connect($arrConfig['host'], $arrConfig['port']);

		if ($this->intResource === null) 
		{
			System::log('Cannot connect to host ' . $arrConfig['host'], __METHOD__, \TL_ERROR);
			return null;
		}

		// login
		if ( $this->login() ) 
		{
			System::log('Cannot refused for user ' . $arrConfig['user'], __METHOD__, \TL_ERROR);
			return null;
		}

		return $this->intResource;
	}


	/**
	 * Login
	 * @return boolean
	 */
	public function login()
	{
		if (empty($this->arrConfig) === true) 
		{
			throw new \Exception('Missing connection information');
		}
		return \ftp_login($this->intResource, $this->arrConfig['user'], $this->arrConfig['pass']);
	}


	/**
	 * Close connection
	 */
	public function close()
	{
		return \ftp_close($this->intResource);
	}


	/**
	 * Send a file to a destination 
	 * @param string $strSource      The source file
	 * @param string $strDestination The remote file / path
	 */
	public function send($strSource, $strDestination)
	{
		// set root folder
		if( empty($this->arrConfig['path']) === false )
		{
			$strDestination = $this->arrConfig['path'] .'/'.$strDestination;
		}
		return \ftp_put($this->intResource,$strDestination,$strSource);
	}
}
