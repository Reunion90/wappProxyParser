<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Page;

class Process extends Model
{
	protected $table = 'Processes';
	protected $primaryKey = 'iProcessID';
	public $timestamps = false;

	protected $fillable = ['iPID', 'sCommand', 'sParameters'];

	protected $casts = [
        //'sParameters' => 'array',
    ];

	public static function fnIsProcessRunning($iPID)
	{
		$sResult = exec("ps -C $iPID");
		return preg_match("/$iPID/", $sResult);
	}

	public static function fnCheckAllRunningProcesses()
	{
		$oProcesses = self::all();
		foreach ($oProcesses as $oProcess) {
			if (!self::fnIsProcessRunning($oProcess->iPID)) {
				$oProcess->forceDelete();
			}
		}
	}

	public static function fnKill($iPID)
	{
		exec("kill -9 $iPID");
		$bResult = !self::fnIsProcessRunning($iPID);

		if ($bResult) {
			Process::where('iPID', $iPID)->delete();
		}

		return $bResult;
	}

	public static function fnStart($sCommand)
	{
		chdir(base_path());
		$oHandler = popen("nohup $sCommand &", 'r');
		$bResult = $oHandler !== false;
		/*		
		while (!feof($oHandler)) { 
			echo fgets($oHandler, 4096);
		}
		*/
		pclose($oHandler);
		return $bResult;
	}

	public static function fnIsProcessWithTypeRunning($sType)
	{
		return Process::where([ 'sType' => $sType ])->count()>0;
	}
}