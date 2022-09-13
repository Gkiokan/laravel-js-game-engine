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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->references('id')->on('users');
            $table->integer('belongsTo')->nullable()->default(null);
            $table->morphs('attachments');

            $table->string('uid')->nullable()->default(null);
            $table->string('name')->nullable()->default(null);
            $table->string('slug')->nullable()->default(null);
            $table->integer('size')->nullable()->default(null);
            $table->string('mime')->nullable()->default(null);
            $table->string('ext')->nullable()->default(null);
            $table->string('path')->nullable()->default(null);
            $table->string('thumbnail')->nullable()->default(null);
            $table->string('type')->nullabel()->default(null);
            $table->string('cat')->nullable()->default(null);

            $table->string('version')->nullable()->default(null);
            $table->string('file')->nullable()->default(null);

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
        Schema::table('attachments', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::dropIfExists('attachments');
    }
};
