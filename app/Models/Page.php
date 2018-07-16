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
        		if ($sHost != $aURL['host'])
        			continue;
        	}
        	self::firstOrCreate(['sURL' => $sLink]);
        }
	}

	public function fnScanProxies() 
	{
        $this->bIsProxyScanned = true;
        $this->save();

        $oPageScanner = new PageScanner($this->sURL);
        $aResults = $oPageScanner->fnScanForProxies();

        foreach ($aResults as $aRow) {
        	Proxy::firstOrCreate(['sIP' => $aRow[0], 'iPort' => $aRow[1]]);
    	}
	}

}