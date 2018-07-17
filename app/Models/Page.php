<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Proxy, PageScanner};

class Page extends Model
{
	protected $table = 'Pages';
	protected $primaryKey = 'iPageID';
	public $timestamps = false;

	protected $fillable = ['sURL', 'bIsLinksScanned', 'bIsProxyScanned'];

	public static function fnNotScanedForLinks()
	{
		return self::where([ ['bIsLinksScanned', 0] ])->count();
	}

	public static function fnNotScanedForProxies()
	{
		return self::where([ ['bIsProxyScanned', 0] ])->count();
	}

	public static function fnNotScanedForLinksFirst()
	{
		return self::where([ ['bIsLinksScanned', 0] ])->first();
	}

	public static function fnNotScanedForProxiesFirst()
	{
		return self::where([ ['bIsProxyScanned', 0] ])->first();
	}

	public static function fnScanLinksURL($sURL, $sHost=false)
	{
        $oPage = self::firstOrCreate(['sURL' => $sURL]);
		$oPage->fnScanLinks($sHost);
	}

	public function fnScanLinks($sHost=false) 
	{
        $this->bIsLinksScanned = true;
        $this->save();

        $oPageScanner = new PageScanner($this->sURL);
        $aResults = $oPageScanner->fnScanForLinks();

        foreach ($aResults as $sLink) {
        	if ($sHost) {
        		$aURL = parse_url($sLink);

        		if (!isset($aURL['host']))
        			continue;
        		if ($sHost != $aURL['host'])
        			continue;
        	}
        	self::firstOrCreate(['sURL' => $sLink]);
        }
	}

	public static function fnScanProxiesURL($sURL)
	{
        $oPage = self::firstOrCreate(['sURL' => $sURL]);
		$oPage->fnScanProxies();
	}

	public function fnScanProxies() 
	{
        $this->bIsProxyScanned = true;
        $this->save();

        $oPageScanner = new PageScanner($this->sURL);
        $aResults = $oPageScanner->fnScanForProxies();

        foreach ($aResults as $aRow) {
        	if ($aRow[1]<1 || $aRow[1]>65535)
        		continue;

        	Proxy::firstOrCreate(['sIP' => $aRow[0], 'iPort' => $aRow[1]]);
    	}
	}

}