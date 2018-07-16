<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
//use App\Models\Setting;
use App\Models\{Process,Page,Proxy,Setting};

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
        */

        Schema::create(
            'Processes', 
            function (Blueprint $oTable) 
            {
                $oTable->increments('iProcessID');
                $oTable->unsignedInteger('iPID');
                $oTable->string('sType', 20);
                $oTable->string('sCommand', 255);
                $oTable->json('sParameters');
                $oTable->charset = 'utf8';
                $oTable->collation = 'utf8_unicode_ci';
                $oTable->engine = 'InnoDB';
            }
        );

        Schema::create(
            'Pages', 
            function (Blueprint $oTable) 
            {
                $oTable->increments('iPageID');
                $oTable->string('sURL', 2000);
                $oTable->boolean('bIsLinksScanned')->default(0);
                $oTable->boolean('bIsProxyScanned')->default(0);
                $oTable->charset = 'utf8';
                $oTable->collation = 'utf8_unicode_ci';
                $oTable->engine = 'InnoDB';
            }
        );

        Schema::create(
            'Proxies', 
            function (Blueprint $oTable) 
            {
                $oTable->increments('iProxyID');
                $oTable->string('sIP', 45);
                $oTable->unsignedInteger('iPort');
                $oTable->string('sType', 20)->default('');
                $oTable->boolean('bIsChecked')->default(0);
                $oTable->boolean('bIsWork')->default(0);
                $oTable->charset = 'utf8';
                $oTable->collation = 'utf8_unicode_ci';
                $oTable->engine = 'InnoDB';
            }
        );

        Schema::create(
            'Settings', 
            function (Blueprint $oTable) 
            {
                $oTable->increments('iSettingID');
                $oTable->string('sName', 255);
                $oTable->string('sDescribtionName', 255);
                $oTable->string('sType', 20);
                $oTable->string('sValue', 255);
                $oTable->charset = 'utf8';
                $oTable->collation = 'utf8_unicode_ci';
                $oTable->engine = 'InnoDB';
            }
        );

        Setting::fnSet('iScanWaitTime', 'text', 2, 'Время ожидания при парсинге страниц сайта (с)');
        Setting::fnSet('iCheckProxyWaitTime', 'text', 2, 'Время ожидания при проверке прокси (с)');
        Setting::fnSet('bWriteLog', 'boolean', 1, 'Вести лог');
    }
}
