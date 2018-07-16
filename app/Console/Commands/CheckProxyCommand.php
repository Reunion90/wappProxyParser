<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProxyChecker;

class CheckProxyCommand extends Command
{
    protected $description = 'Check proxy';
    protected $signature = 'cp {sProxy} {sPort} {--sType=http}';

    public function handle()
    {
        try {
            $oProxyChecker = new ProxyChecker(
                $this->argument('sProxy'), 
                $this->argument('sPort'), 
                $this->option('sType')
            );
            var_dump($oProxyChecker->fnCheck());
        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }
}