<?php

namespace App\Network;

use Exception;

class LastLocationFinder
{
	protected $_sURL;

	public $sLastLocation;

	function __construct($in_sURL, $in_iMaxRedirects) 
	{
		$this->fnFindLastLocation($in_sURL, $in_iMaxRedirects);
	}

	public function fnFindLastLocation($in_sURL, $in_iMaxRedirects)
	{
		$this->_sURL = $in_sURL;
	
		$aOptions = [
			CURLOPT_URL => $in_sURL,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS => $in_iMaxRedirects,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FAILONERROR => true,
		];

		$oCURL = curl_init();
		curl_setopt_array($oCURL, $aOptions);
		curl_exec($oCURL);

		if (curl_errno($oCURL)>0)
			new Exception(curl_error($oCURL));

		$this->sLastLocation = curl_getinfo($oCURL, CURLINFO_EFFECTIVE_URL);

		curl_close($oCURL);

		return $this->sLastLocation;
	}
}