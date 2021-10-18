<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceDetailsToAttendances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('device_origin')->after('schedule_out')->nullable();
            $table->string('device_name')->after('device_origin')->nullable();
            $table->string('ip_address')->after('device_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('device_origin');
            $table->dropColumn('device_name');
            $table->dropColumn('ip_address');
        });
    }
}
