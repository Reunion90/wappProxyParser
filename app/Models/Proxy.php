<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProxyChecker;

class Proxy extends Model
{
	protected $table = 'Proxies';
	protected $primaryKey = 'iProxyID';
	public $timestamps = false;

	protected $fillable = ['sIP', 'iPort', 'sType', 'bIsChecked', 'bIsWork'];

	public static function fnNotCheckedFirst()
	{
		return self::where([ ['bIsChecked', 0] ])->first();
	}

	public static function fnNotChecked()
	{
		return self::where([ ['bIsChecked', 0] ])->count();
	}

	public function fnCheck()
	{
		$this->bIsChecked = true;
		$this->save();

		$oProxyChecker = new ProxyChecker($this->sIP, $this->iPort, $this->sType);
		$aResult = $oProxyChecker->fnCheck();
		
		if ($this->sType != $aResult['bSuccess']) {
			$this->sType = $aResult['sProxyType'];
		}

		$this->bIsWork = $aResult['bSuccess'];
		$this->save();

		return $aResult['bSuccess'];
	}
}