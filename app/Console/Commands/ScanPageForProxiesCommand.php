<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\PageScanner;

class ScanPageForProxiesCommand extends Command
{
    protected $description = 'Scan page for proxies';
    protected $signature = 'sp:fp {sURL}';

    public function handle()
    {
        try {
            $oPageScanner = new PageScanner($this->argument('sURL'));
            var_dump($oPageScanner->fnScanForProxies());
        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }
}