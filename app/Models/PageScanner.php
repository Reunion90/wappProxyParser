<?php

namespace App\Models;

use DOMDocument;
use DOMXpath;
use Exception;

class PageScanner
{
	protected $_sURL;
	protected $_sHTML;

	function __construct($in_sURL='') 
	{
		if (!empty($in_sURL))
			$this->fnGetPage($in_sURL);
	}

	public function fnGetPage($in_sURL)
	{
		$this->_sURL = $in_sURL;
	
		$aOptions = [
			CURLOPT_URL => $in_sURL,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS => 3,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FAILONERROR => true,
		];

		$oCURL = curl_init();
		curl_setopt_array($oCURL, $aOptions);
		$this->_sHTML = curl_exec($oCURL);

		if (empty($this->_sHTML))
			echo $in_sURL, " EMPTY", "\n";

		if (curl_errno($oCURL)>0)
			new Exception(curl_error($oCURL));

		curl_close($oCURL);
	}

	public function fnScanForLinks()
	{
		$aResult = [];

		try {
			$oDOM = new DOMDocument;
			libxml_use_internal_errors(true);

			$oDOM->loadHTML($this->_sHTML);

			$oLinks = $oDOM->getElementsByTagName('a');

			foreach ($oLinks as $oLink) {
				$sLink = $oLink->getAttribute('href');
				if (!preg_match("/^[\s\t]*#/", $sLink))
					$aResult[] = $oLink->getAttribute('href');
			}
		} catch (Exception $oException) {
        	echo "fnScanForLinks ", $oException->getMessage(), "\n";
		}

		return $aResult;
	}

	public function fnScanGoogleSearchResultForLinks()
	{
		$aResult = ['aLinks' => [], 'sNextLink' => ''];

		try {
			$oDOM = new DOMDocument;
			libxml_use_internal_errors(true);
		
			$oDOM->loadHTML($this->_sHTML);

			$oDOMXPath = new DOMXpath($oDOM);
			$oLinks = $oDOMXPath->query("//div[contains(@class, 'g')]//h3/a");

			if ($oLinks->length>0) {
				foreach ($oLinks as $oLink) {
					$aResult['aLinks'][] = $oLink->getAttribute('href');
				}
			}

			$oNextPageLinks = $oDOMXPath->query("//td[contains(@class, 'b')]/a");

			if ($oNextPageLinks->length>0) {
				$aResult['sNextLink'] = $oNextPageLinks[0]->getAttribute('href');
			}
		} catch (Exception $oException) {
        	echo "fnScanGoogleSearchResultForLinks ", $oException->getMessage(), "\n";
		}

		return $aResult;
	}

	public function fnScanForProxies()
	{
		$aResult = [];
		preg_match_all(
			"/".
			"((".
			"([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|".
			"([0-9a-fA-F]{1,4}:){1,7}:|".
			"([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|".
			"([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|".
			"([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|".
			"([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|".
			"([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|".
			"[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|".  
			":((:[0-9a-fA-F]{1,4}){1,7}|:)|".   
			"fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|".
			"::(ffff(:0{1,4}){0,1}:){0,1}".
			"((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}".
			"(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|".
			"([0-9a-fA-F]{1,4}:){1,4}:".
			"((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}".
			"(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])".
			")|".
			"((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])".
			").*?(\d+)/m", 
			$this->_sHTML, 
			$aMatches
		);

		foreach ($aMatches[1] as $iKey => $aMatch) {
			$aResult[] = [$aMatches[1][$iKey], $aMatches[37][$iKey]];
		}

		return $aResult;
	}
}