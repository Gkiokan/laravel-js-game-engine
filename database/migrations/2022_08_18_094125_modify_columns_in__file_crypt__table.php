<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_crypts', function (Blueprint $table) {
            // $table->primary(['container_id', 'releasename']);

            $table->unique(['container_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_crypts', function (Blueprint $table) {
            // $table->dropPrimary('container_id'); 
            // $table->dropPrimary('releasename');

            $table->dropUnique(['container_id']);
        });
    }
};
