<?php

namespace App\Http\Controllers;

use View;
use Illuminate\Http\Request;
use App\Models\{Process,Page,Proxy,Setting};

class IndexController extends Controller
{
	public function fnIndex() 
	{
		return view($this->layout, [
			'sContent' => View::make('index', [
				'sProcesses' => $this->fnShowProcesses(),
				'sPages' => $this->fnShowPages(),
				'sAllProxies' => $this->fnShowProxies(),
				'sWorkProxies' => $this->fnShowWorkProxies(),
				'sSettings' => $this->fnShowSettings(),
			]),
		]);
	}

	public function fnShowProcesses() {
		Process::fnCheckAllRunningProcesses();
		
		return View::make('process_list', ['oProcesses' => Process::all()]);
	}

	public function fnShowPages() {
		return View::make('pages_list', ['oPages' => Page::all()]);
	}

	public function fnShowProxies() {
		return View::make('proxy_list', ['oProxies' => Proxy::all()]);
	}

	public function fnShowWorkProxies() {
		return View::make('work_proxy_list', ['oWorkProxies' => Proxy::where([ 'bIsWork' => 1 ])->take(1e10)->get()]);
	}

	public function fnShowSettings() {
		return View::make('settings_list', ['oSettings' => Setting::all()]);
	}

	public function fnSaveSettings(Request $oRequest) 
	{
		$aResult = ['success' => true];

		foreach ($oRequest->input('sValue', []) as $iID => $sValue) {
			$oSetting = Setting::where('iSettingID', $iID)->first();
			$oSetting->sValue = $sValue;
			$aResult['success'] = $aResult['success'] && $oSetting->save();
		}

		return json_encode($aResult);
	}

	public function fnCreateGoogleProcess(Request $oRequest)
	{
		$aResult = ['success' => true];

		if ($oRequest->input('bTruncatePages', false)) {
			Page::truncate();
		}

		$sParameters = addslashes(json_encode($oRequest->all()));
		$aResult['success'] = Process::fnStart("php artisan sg \"$sParameters\"");

		return json_encode($aResult);
	}

	public function fnCreateSiteProcess(Request $oRequest)
	{
		$aResult = ['success' => true];

		if ($oRequest->input('bTruncatePages', false)) {
			Page::truncate();
		}

		$sParameters = addslashes(json_encode($oRequest->all()));
		$aResult['success'] = Process::fnStart("php artisan ss \"$sParameters\"");

		return json_encode($aResult);
	}

	public function fnKillProcess(Request $oRequest)
	{
		$aResult = ['success' => true];

		foreach ($oRequest->input('bSelected', []) as $iPID => $sValue) {
			$bResult = Process::fnKill($iPID);
			$aResult['result'][$iPID] = $bResult;
			$aResult['success'] = $aResult['success'] && $bResult;
		}

		return json_encode($aResult);
	}

	public function fnProxyDelete(Request $oRequest)
	{
		$aResult = ['success' => true];

		foreach ($oRequest->input('bSelected', []) as $iProxyID => $sValue) {
			$aResult['success'] = $aResult['success'] && Proxy::where(['iProxyID' => $iProxyID])->delete();
		}		

		return json_encode($aResult);
	}
}
