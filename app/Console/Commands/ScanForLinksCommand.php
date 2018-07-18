<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Page, Process, Setting};

class ScanForLinksCommand extends Command
{
    protected $description = 'Loop scan for links';
    protected $signature = 'sfl';

    public static $bIsTerminated = false;

    public function handle()
    {
        try {
            set_time_limit(0);
            declare(ticks = 1);
            pcntl_signal(SIGTERM, function() {
                ScanForLinksCommand::$bIsTerminated = true;
            });

            Process::fnCreate('ScanForLinks');

            while (!self::$bIsTerminated && Page::fnNotScanedForLinks()>0) {
                try {
                    $oPage = Page::fnNotScanedForLinksFirst();
                    
                    Process::fnStart("php artisan spfl {$oPage->iPageID}");

                    sleep(Setting::fnGet('iScanLinksWaitTime'));
                } catch (Exception $oException) {
                    echo "fnScanAll ", $oException->getMessage(), "\n";
                }
            }
        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }
}