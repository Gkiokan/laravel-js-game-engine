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
        Schema::create('releases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->default(null)->references('id')->on('users');
            $table->foreignId('entry_id')->nullable()->default(null)->references('id')->on('entries');
            $table->string('title')->nullable()->default(null);
            $table->string('fulltitle')->nullable()->default(null);
            $table->string('type')->nullable()->default(0);
            
            $table->text('containerID')->nullable()->default(null);
            $table->text('links')->nullable()->default(null);
            $table->text('crypted_links')->nullable()->default(null);
            
            $table->integer('size')->nullable()->default(0);
            $table->integer('parts')->nullable()->default(0);
            $table->string('group')->nullable()->default(null);

            $table->string('quality')->nullable()->default(null);
            $table->string('video_stream')->nullable()->default(null);
            $table->string('video_codec')->nullable()->default(null);
            $table->string('audio_stream')->nullable()->default(null);
            $table->string('bitrate')->nullable()->default(null);

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
        Schema::table('releases', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->dropForeign(['entry_id']);
            $table->dropColumn('entry_id');
        });
        
        Schema::dropIfExists('releases');
    }
};
