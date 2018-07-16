<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Page, Process, Setting};
use PDO;

class ScanSiteCommand extends Command
{
    protected $description = 'Scan site for proxies';
    protected $signature = 'ss {sParameters}';

    public static $bIsTerminated = false;

    public function handle()
    {
        try {
            set_time_limit(0);
            declare(ticks = 1);
            pcntl_signal(SIGTERM, function() {
                ScanSiteCommand::$bIsTerminated = true;
            });
            $iPID = getmypid();
            $aParameters = json_decode($this->argument('sParameters'), true);

            $oProcess = new Process();
            $oProcess->iPID = $iPID;
            $oProcess->sType = 'ScanSite';
            $oProcess->sCommand = implode(' ', $_SERVER['argv']);
            $oProcess->sParameters = $this->argument('sParameters');
            $oProcess->save();

            if (!empty($aParameters['bScanFullSite'])) {
                $aURL = parse_url($aParameters['sURL']);

                Page::fnScanLinksURL($aParameters['sURL'], $aURL['host']);

                while (!self::$bIsTerminated && Page::fnNotScanedForLinks()>0) {
                    $oPage = Page::fnNotScanedForLinksFirst();
                    $oPage->fnScanLinks($aURL['host']);
                    sleep(Setting::fnGet('iScanWaitTime'));
                }

                while (!self::$bIsTerminated && Page::fnNotScanedForProxies()>0) {
                    $oPage = Page::fnNotScanedForProxiesFirst();
                    $oPage->fnScanProxies();

                    if (!Process::fnIsProcessWithTypeRunning('CheckAllProxies')) {
                        Process::fnStart("php artisan cap");
                    }

                    sleep(Setting::fnGet('iScanWaitTime'));
                }
            } else {
                $oPage = Page::firstOrCreate(['sURL' => $aParameters['sURL']]);
                $oPage->fnScanProxies();

                if (!Process::fnIsProcessWithTypeRunning('CheckAllProxies')) {
                    Process::fnStart("php artisan cap");
                }
            }
        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }
}