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
            $iPID = getmypid();

            $oProcess = new Process();
            $oProcess->iPID = $iPID;
            $oProcess->sType = 'CheckProxyByID';
            $oProcess->sCommand = implode(' ', $_SERVER['argv']);
            $oProcess->sParameters = '{}';
            $oProcess->save();

            if ($oProxy = Proxy::where([ 'iProxyID' => $this->argument('iProxyID') ])->first()) {
                $oProxy->fnCheck();
            }
        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }
}