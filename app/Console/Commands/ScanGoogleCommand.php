<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Page, Process, Setting};
use App\Models\PageScanner;
use App\Network\LastLocationFinder;

class ScanGoogleCommand extends Command
{
    protected $description = 'Scan google search results';
    protected $signature = 'sg {sParameters}';

    public static $bIsTerminated = false;

    public function handle()
    {
        try {
            set_time_limit(0);
            declare(ticks = 1);
            pcntl_signal(SIGTERM, function() {
                ScanGoogleCommand::$bIsTerminated = true;
            });

            $aParameters = json_decode($this->argument('sParameters'), true);

            Process::fnCreate('ScanGoogle', $this->argument('sParameters'));

            $sSearchString = urlencode($aParameters['sSearchString']);
            $sURL = "https://www.google.com/search?num=100&q=$sSearchString";

            $oPageScanner = new PageScanner();

            do {
                $oPageScanner->fnGetPage($sURL);
                $aResult = $oPageScanner->fnScanGoogleSearchResultForLinks();

                $sURL = $aResult['sNextLink'];

                foreach ($aResult['aLinks'] as $sLink) {
                    Process::fnStart("php artisan cu \"https://www.google.com$sLink\"");
                }

                $this->fnCheckScanSiteProcess();

                sleep(Setting::fnGet('iScanGoogleWaitTime'));
                break;
            } while(!self::$bIsTerminated && !empty($aResult['sNextLink']));

            while (!self::$bIsTerminated && Page::fnNotScanedForLinks()>0) {
                $this->fnCheckScanSiteProcess();

                sleep(Setting::fnGet('iScanGoogleWaitTime'));
            }

            while (!self::$bIsTerminated && Page::fnNotScanedForProxies()>0) {
                $this->fnCheckScanSiteProcess();

                sleep(Setting::fnGet('iScanGoogleWaitTime'));
            }
        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }

    public function fnCheckScanSiteProcess()
    {
        Process::fnCheckAllRunningProcesses();

        if (!Process::fnIsProcessWithTypeRunning('ScanSite')) {
            $sParameters = addslashes(json_encode([ ]));
            Process::fnStart("php artisan ss \"$sParameters\"");
        }        
    }
}