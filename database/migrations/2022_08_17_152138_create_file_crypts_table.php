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
        Schema::create('file_crypts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->default(null)->references('id')->on('users');
            $table->string('container_id')->nullable()->default(null);
            $table->string('releasename')->nullable()->default(null);
            $table->string('releasename_patched')->nullable()->default(null);

            $table->string('status')->nullable()->default(null);
            $table->boolean('checked')->default(0);
            $table->boolean('patchable')->default(0);
            $table->boolean('patched')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_crypts', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });        
        Schema::dropIfExists('file_crypts');
    }
};
