<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

class ScanGoogleCommand extends Command
{
    protected $description = 'Scan google search results';
    protected $signature = 'sg';

    public static $bIsTerminated = false;

    public function handle()
    {
        try {

        } catch (Exception $oException) {
            $this->error($oException->getMessage());
        }
    }
}