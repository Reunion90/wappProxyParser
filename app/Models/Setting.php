<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
	protected $table = 'Settings';
	protected $primaryKey = 'iSettingID';
	public $timestamps = false;

	protected $fillable = ['sName', 'sType', 'sValue', 'sDescribtionName'];

	public static function fnGet($sName) 
	{
		return self::where('sName', $sName)->first()->sValue;
	}

	public static function fnSet($sName, $sType, $sValue, $sDescribtionName=false) 
	{
		$oSetting = self::firstOrNew(['sName' => $sName]);
		$oSetting->sType = $sType;
		$oSetting->sValue = $sValue;
		if ($sDescribtionName)
			$oSetting->sDescribtionName = $sDescribtionName;
		$oSetting->save();
	}
}