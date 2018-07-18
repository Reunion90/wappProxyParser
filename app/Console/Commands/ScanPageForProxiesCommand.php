<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Page, Process, Setting};

class ScanPageForProxiesCommand extends Command
{
    protected $description = 'Scan page for proxies';
    protected $signature = 'spfp {iPageID}';

    public function handle()
    {
        try {
            Process::fnCreate('ScanPageForProxies');

            $oPage = Page::find($this->argument('iPageID'));

            if ($oPage) {
                $oPage->fnScanProxies();
            }
        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }
}