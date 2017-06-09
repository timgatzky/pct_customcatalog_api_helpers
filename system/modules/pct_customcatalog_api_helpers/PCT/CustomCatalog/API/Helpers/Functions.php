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
 * Namespace
 */
namespace PCT\CustomCatalog\API\Helpers;

/**
 * Class file
 * Functions
 * Helper functions
 */
class Functions
{
	/**
	 * Recursively return all keys of an array in a folder structure
	 * @param array		The input array
	 * @param string	The string to glue
	 * @param array		Array keys to be ignored
	 * @param integer	Internal helper integer that counts the depth of the current array structure
	 * @param array		Internal helper array that stores the processed keys on the ways down the array structure
	 * @param array		The return array
	 * @return array
	 */
	public static function array_keys_recursive_flat($arrInput,$strDelimiter='/',$arrIgnore=array('@attributes'),$intLevel=-1,$arrTrail=array(),$arrReturn=array())
	{
		if(!is_array($arrInput))
		{
			return $arrReturn;
		}
		
		$intLevel++;
			
		foreach($arrInput as $key => $value)
		{
			if( in_array($key, $arrIgnore) && (!is_numeric($key) || !is_integer($key)) )
			{
				continue;
			}
			
			// remove all unwanted keys
			if(is_array($value) && count($value) > 0)
			{
				foreach($arrIgnore as $k)
				{
					if(isset($value[$k]))
					{
						unset($value[$k]);
					}
				}
			}
			
			if(is_array($value) && count($value) > 0)
			{
				$arrTrail[$intLevel] = $key;
				$arrReturn = array_merge($arrReturn,self::array_keys_recursive_flat($value,$strDelimiter,$arrIgnore,$intLevel,$arrTrail));
			}
			else
			{
				$arrReturn[] = implode($strDelimiter, $arrTrail).($intLevel > 0 ? $strDelimiter:'').$key;
			}
		}

		return $arrReturn;
	}
	
	
	
	/**
	 * Recursively return all keys of an array in a folder structure
	 * @param array		The input array
	 * @param string	The string to glue
	 * @param integer	Internal helper integer that counts the depth of the current array structure
	 * @param array		Internal helper array that stores the processed keys on the ways down the array structure
	 * @param array		The return array
	 * @return array 
	 */
	public static function array_keys_recursive_flat_2($arrInput,$strDelimiter='/',$intLevel=-1,$arrTrail=array(),$arrReturn=array())
	{
		if(!is_array($arrInput))
		{
			return $arrReturn;
		}
		
		$intLevel++;
		
		foreach($arrInput as $key => $value)
		{
			if($key == '@attributes' && is_array($value) && count($value) > 0)
			{
				foreach($value as $attr => $val)
				{
					$key = '@attributes['.$attr.']';
					$s = (count($arrTrail) > 0 ? implode($strDelimiter, $arrTrail) : '').($intLevel >= 0 ? $strDelimiter:'').$key;
					$s = ltrim($s,$strDelimiter);
					
					$arrReturn[] = $s;
				}
				continue;
			}	
					
			if(is_array($value) && count($value) > 0)
			{
				$arrTrail[$intLevel] = $key;
				
				$arrTrail = array_filter($arrTrail,'is_string');
				
				$arrReturn = array_merge($arrReturn,self::array_keys_recursive_flat_2($value,$strDelimiter,$intLevel,$arrTrail));
			}
			else
			{
				$s = (count($arrTrail) > 0 ? implode($strDelimiter, $arrTrail) : '').($intLevel >= 0 ? $strDelimiter:'').$key;
				$s = ltrim($s,$strDelimiter);
				$arrReturn[] = $s;
			}
		}

		return $arrReturn;
	}
	
	
	/**
	 * Create an associate array from an array with flat keys that might contain a delimiter like a path structure e.g. a/aa/aaa = 'foo'
	 * @param array		The input array
	 * @param string	The string to split
	 * @param array		The return array
	 */
	public static function array_associate_from_array_keys_flat($arrInput,$strDelimiter='/',$arrReturn=array())
	{
		if(!is_array($arrInput))
		{
			return $arrReturn;
		}
		
		foreach($arrInput as $key => $value)
		{
			if(strlen(strpos($key, $strDelimiter)) > 0)
			{
				$a = explode($strDelimiter, $key);
				$a[] = $value;
				
				// taken from http://stackoverflow.com/posts/30365801/edit
				$result = array_reduce(array_reverse($a), function($_prevArray, $_key)
				{
				    return $_prevArray ? [$_key => $_prevArray] : [$_key];
				}, null);
				
				if(is_array($result))
				{
					$arrReturn = array_merge_recursive($arrReturn,$result);
				}
				
				unset($a);
			}
			else
			{
				$arrReturn[$key] = $value;
			}
		}
		
		return $arrReturn;
	}

}