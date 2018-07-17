<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Proxy, Process, Setting};

class CheckAllProxiesCommand extends Command
{
    protected $description = 'Check all proxies';
    protected $signature = 'cap';

    public static $bIsTerminated = false;

    public function handle()
    {
        try {
            set_time_limit(0);
            declare(ticks = 1);
            pcntl_signal(SIGTERM, function() {
                CheckAllProxiesCommand::$bIsTerminated = true;
            });

            Process::fnCreate('CheckAllProxies');

            while (!self::$bIsTerminated && Proxy::fnNotChecked()>0) {
                $oProxy = Proxy::fnNotCheckedFirst();
                Process::fnStart("php artisan cpbi {$oProxy->iProxyID}");
                sleep(Setting::fnGet('iCheckProxyWaitTime'));
            }
        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }
}