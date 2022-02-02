<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class ModifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('last_device')
                ->nullable()
                ->after('remember_token');

            $table->unsignedInteger('profile_image_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('set null')
                ->after('last_device');

            $table->unsignedInteger('timezone_id')
                ->nullable()
                ->index()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('set null')
                ->after('profile_image_id');
            
            $table->string('phone')
                ->nullable()
                ->after('profile_image_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn([
                'phone',
                'timezone_id',
                'profile_image_id',
                'last_device',
            ]);
        });
    }
}
