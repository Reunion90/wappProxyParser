<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Page, Process, Setting};
use App\Network\LastLocationFinder;

class CheckURLCommand extends Command
{
    protected $description = 'Check URL';
    protected $signature = 'cu {sURL}';

    public function handle()
    {
        try {
            Process::fnCreate('CheckURL');

            $sURL = $this->argument('sURL');

            $oLastLocationFinder = new LastLocationFinder($sURL, Setting::fnGet('iLocationFinderMaxRedirects'));

            if (!empty($oLastLocationFinder->sLastLocation)) {
                Page::firstOrCreate(['sURL' => $oLastLocationFinder->sLastLocation]);
            }
        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }
}