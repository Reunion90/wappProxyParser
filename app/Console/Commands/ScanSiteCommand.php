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
                    Page::fnScanLinksURL($aParameters['sURL'], $aURL['host']);

                    $this->fnScanAll();
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
        if (!Process::fnIsProcessWithTypeRunning('ScanForLinks')) {
            Process::fnStart("php artisan sfl");
        }
        if (!Process::fnIsProcessWithTypeRunning('ScanForProxies')) {
            Process::fnStart("php artisan sfp");
        }
    }
}