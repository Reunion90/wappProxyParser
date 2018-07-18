<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Page, Process, Setting};

class ScanForProxiesCommand extends Command
{
    protected $description = 'Loop scan for proxies';
    protected $signature = 'sfp';

    public static $bIsTerminated = false;

    public function handle()
    {
        try {
            set_time_limit(0);
            declare(ticks = 1);
            pcntl_signal(SIGTERM, function() {
                ScanForProxiesCommand::$bIsTerminated = true;
            });

            Process::fnCreate('ScanForProxies');

            while (!self::$bIsTerminated && Page::fnNotScanedForProxies()>0) {
                try {
                    $oPage = Page::fnNotScanedForProxiesFirst();

                    Process::fnStart("php artisan spfp {$oPage->iPageID}");

                    if (!Process::fnIsProcessWithTypeRunning('CheckAllProxies')) {
                        Process::fnStart("php artisan cap");
                    }

                    sleep(Setting::fnGet('iScanProxiesWaitTime'));
                } catch (Exception $oException) {
                    echo "fnScanAll ", $oException->getMessage(), "\n";
                }
            }
        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }
}