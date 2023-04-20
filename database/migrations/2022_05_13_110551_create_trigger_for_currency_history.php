<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS  `rate_trigger_update`');
        DB::unprepared('DROP TRIGGER IF EXISTS  `rate_trigger_create`');
        DB::unprepared('CREATE TRIGGER `rate_trigger_update` AFTER UPDATE ON `currencies`
        FOR EACH ROW
            INSERT INTO `currencies_histories` (currency_id, rate,created_at,updated_at) VALUES (new.id,new.rate,CURRENT_TIMESTAMP,null)');

        DB::unprepared('CREATE TRIGGER `rate_trigger_create` AFTER INSERT ON `currencies`
        FOR EACH ROW
            INSERT INTO `currencies_histories` (currency_id, rate,created_at,updated_at) VALUES (new.id,new.rate,CURRENT_TIMESTAMP,null)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS  `rate_trigger_update`');
        DB::unprepared('DROP TRIGGER IF EXISTS  `rate_trigger_create`');
    }
};
