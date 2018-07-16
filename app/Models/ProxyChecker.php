<?php

namespace App\Models;

class ProxyChecker
{
	protected $_sIP;
	protected $_sPort;
	protected $_sProxyType;

	protected static $_aProxyTypes = [
		'http' => CURLPROXY_HTTP,
		//'https' => CURLPROXY_HTTPS,
		'http1.0' => CURLPROXY_HTTP_1_0,
		'socks4' => CURLPROXY_SOCKS4,
		'socks4a' => CURLPROXY_SOCKS4A,
		'socks5' => CURLPROXY_SOCKS5,
		'socks5hostname' => CURLPROXY_SOCKS5_HOSTNAME,
	];

	function __construct($in_sIP, $in_sPort, $in_sProxyType) 
	{
		$this->_sIP = $in_sIP;
		$this->_sPort = $in_sPort;
		$this->_sProxyType = $in_sProxyType;	
	}

	public function fnCheckWithType($in_iCURLProxyType)
	{
		$aOptions = [
			CURLOPT_URL => "http://www.google.com",
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS => 3,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FAILONERROR => true,
			CURLOPT_PROXY => $this->_sIP,
			CURLOPT_PROXYPORT => $this->_sPort,
			CURLOPT_PROXYTYPE => $in_iCURLProxyType,
		];

		$oCURL = curl_init();
		curl_setopt_array($oCURL, $aOptions);
		$sHTML = curl_exec($oCURL);
		curl_close($oCURL);

		return preg_match("/google/", $sHTML);
	}

	public function fnCheck()
	{
		$aResult = [
			'sProxyType' => $this->_sProxyType,
			'bSuccess' => false,
		];

		if (!isset(ProxyChecker::$_aProxyTypes[$this->_sProxyType])) {
			foreach(ProxyChecker::$_aProxyTypes as $sProxyType => $sCURLProxyType) {
				$aResult['sProxyType'] = $sProxyType;
				$aResult['bSuccess'] = $this->fnCheckWithType($sCURLProxyType);
				if ($aResult['bSuccess'])
					break;
			}
		} else {
			$aResult['bSuccess'] = $this->fnCheckWithType(ProxyChecker::$_aProxyTypes[$this->_sProxyType]);
		}

		return $aResult;
	}
}