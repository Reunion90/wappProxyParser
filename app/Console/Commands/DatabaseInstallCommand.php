<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;
use PDO;
use PDOException;

class DatabaseInstallCommand extends Command
{
    protected $description = 'Database installation';
    protected $signature = 'db:install';

    public function handle()
    {
        $sDatabase = env('DB_DATABASE', false);

        try {
            $oPDO = $this->getPDOConnection();
            $oPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $oPDO->exec(sprintf(
                'CREATE DATABASE IF NOT EXISTS %s CHARACTER SET %s COLLATE %s;',
                $sDatabase,
                env('DB_CHARSET', 'utf8'),
                env('DB_COLLATION', 'utf8_unicode_ci')
            ));

            $this->info(sprintf('Successfully created %s database', $sDatabase));

            Artisan::call('migrate:fresh');

        } catch (PDOException $oException) {
            $this->error($oException->getMessage());
        }
    }

    private function getPDOConnection()
    {
        return new PDO(sprintf('mysql:host=%s;port=%d;', 
            env('DB_HOST'), 
            env('DB_PORT')), 
            env('DB_USERNAME'), 
            env('DB_PASSWORD')
        );
    }
}