<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PageScanner;

class ScanPageForLinksCommand extends Command
{
    protected $description = 'Scan page for links';
    protected $signature = 'sp:fl {sURL}';

    public function handle()
    {
        try {
            $oPageScanner = new PageScanner($this->argument('sURL'));
            var_dump($oPageScanner->fnScanForLinks());
        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }
}