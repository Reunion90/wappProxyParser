<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Page, Process, Setting};

class ScanPageForLinksCommand extends Command
{
    protected $description = 'Scan page for links';
    protected $signature = 'spfl {iPageID}';

    public function handle()
    {
        try {
            Process::fnCreate('ScanPageForLinks');

            $oPage = Page::find($this->argument('iPageID'));

            if ($oPage) {
                $oPage->fnScanLinks(parse_url($oPage->sURL)['host']);
            }
        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }
}