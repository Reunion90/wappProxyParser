<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Page, Process, Setting};

class ScanSiteCommand extends Command
{
    protected $description = 'Scan site for proxies';
    protected $signature = 'ss {sParameters}';

    public static $bIsTerminated = false;

    public function handle()
    {
        try {
            set_exception_handler(function() {
                echo "fnExceptionHandler ", $oException->getMessage(), "\n";
            });

            set_time_limit(0);
            declare(ticks = 1);
            pcntl_signal(SIGTERM, function() {
                ScanSiteCommand::$bIsTerminated = true;
            });

            $aParameters = json_decode($this->argument('sParameters'), true);

            Process::fnCreate('ScanSite', $this->argument('sParameters'));

            if (!empty($aParameters['sURL'])) {
                if (!empty($aParameters['bScanFullSite'])) {
                    $aURL = parse_url($aParameters['sURL']);

                    Page::fnScanLinksURL($aParameters['sURL'], $aURL['host']);

                    $this->fnScanAll($aURL['host']);
                } else {
                    $oPage = Page::firstOrCreate(['sURL' => $aParameters['sURL']]);
                    $oPage->fnScanProxies();

                    if (!Process::fnIsProcessWithTypeRunning('CheckAllProxies')) {
                        Process::fnStart("php artisan cap");
                    }
                }
            } else {
                $this->fnScanAll();
            }
        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }

    public function fnScanAll($sSiteHost='')
    {
        while (!self::$bIsTerminated && Page::fnNotScanedForLinks()>0) {
            try {
                $oPage = Page::fnNotScanedForLinksFirst();
                
                if (empty($sSiteHost)) {
                    $sSiteHost = parse_url($oPage->sURL)['host'];
                }

                $oPage->fnScanLinks($sSiteHost);

                sleep(Setting::fnGet('iScanLinksWaitTime'));
            } catch (Exception $oException) {
                echo "fnScanAll ", $oException->getMessage(), "\n";
            }
        }

        while (!self::$bIsTerminated && Page::fnNotScanedForProxies()>0) {
            try {
                $oPage = Page::fnNotScanedForProxiesFirst();
                $oPage->fnScanProxies();

                if (!Process::fnIsProcessWithTypeRunning('CheckAllProxies')) {
                    Process::fnStart("php artisan cap");
                }

                sleep(Setting::fnGet('iScanProxiesWaitTime'));
            } catch (Exception $oException) {
                echo "fnScanAll ", $oException->getMessage(), "\n";
            }
        }
    }
}