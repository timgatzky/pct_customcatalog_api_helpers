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
 * 
 * Usage:
 *
 *     $config = array('host'=>'myServer','user'=>'myUser','password'=>'myPassword','ssl'=>boolean);
 * 
 *     $ftp = new PCT\CustomCatalog\Connections\FTP( $config );
 *     $ftp->__set('root','myRootFolder'); // optional root folder
 *     $ftp->send($source,$destination);
 *     $ftp->close();
 * 	
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
	 * The root path
	 * @var string
	 */
	protected $strRoot = '';


	/**
	 * Create a new FTP connection
	 * @param array $arrConfig
	 * @return integer ID of the FTP buffer resource
	 */
	public function __construct($arrConfig)
	{
		if (empty($arrConfig) === true) 
		{
			throw new \Exception('Missing connection information');
		}

		// set the config
		$this->arrConfig = $arrConfig;

		// establish connection
		if( (boolean)$arrConfig['ssl'] === true )
		{
			$this->intResource = \ftp_ssl_connect($arrConfig['host'], $arrConfig['port']);
		}
		else 
		{
			$this->intResource = \ftp_connect($arrConfig['host'], $arrConfig['port']);
		}
		
		// passive mode off
		if( (boolean)$arrConfig['passive'] === false && empty($arrConfig['passive']) === false )
		{
			\ftp_pasv($this->intResource, false);
		}
		else
		{
			\ftp_pasv($this->intResource, true);
		}
		
		if ($this->intResource === null) 
		{
			System::log('Cannot connect to host ' . $arrConfig['host'], __METHOD__, \TL_ERROR);
			return null;
		}

		// login
		if ( $this->login() === false ) 
		{
			System::log('Connection refused for user ' . $arrConfig['user'], __METHOD__, \TL_ERROR);
			return null;
		}

		// set root
		if( empty($arrConfig['path']) === false )
		{
			$this->__set('root',$arrConfig['path']);
		}
	}


	/**
	 * Setters
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch($strKey)
		{
			case 'root':
				$this->strRoot = $varValue;
				break;
			default:
				$this->{$strKey} = $varValue;
				break;
		}
	}


	/**
	 * Getters
	 * @param string
	 * @param mixed
	 */
	public function __get($strKey)
	{
		switch($strKey)
		{
			case 'root':
				return $this->strRoot;
				break;
			case 'config':
				return $this->arrConfig;
				break;
			case 'user':
				return $this->arrConfig['user'];
				break;
			case 'password':
				return $this->arrConfig['password'];
				break;
			case 'host':
				return $this->arrConfig['host'];
				break;
			case 'port':
				return $this->arrConfig['port'];
				break;
			default:
				break;
		}
		return $this->{$strKey};
	}


	/**
	 * Login
	 * @return boolean
	 */
	public function login()
	{
		if (empty($this->arrConfig) === true || empty($this->intResource) === true) 
		{
			throw new \Exception('Missing connection information');
		}	
		return \ftp_login($this->intResource, $this->arrConfig['user'], $this->arrConfig['password']);
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
	 * @param string $strMode 		 The transfer mode
	 */
	public function send($strSource, $strDestination, $strMode=FTP_ASCII)
	{
		return \ftp_put($this->intResource,$this->strRoot .'/'.$strDestination,$strSource,$strMode);
	}
}
