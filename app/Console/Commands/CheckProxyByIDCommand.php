<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Proxy, Process};

class CheckProxyByIDCommand extends Command
{
    protected $description = 'Check proxy by id';
    protected $signature = 'cpbi {iProxyID}';

    public function handle()
    {
        try {
            Process::fnCreate('CheckProxyByID');

            if ($oProxy = Proxy::where([ 'iProxyID' => $this->argument('iProxyID') ])->first()) {
                $oProxy->fnCheck();
            }
        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }
}