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
        Schema::create('entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->default(null)->references('id')->on('users');
            $table->string('uid')->nullable()->default(null);
            
            $table->string('type')->nullable()->default(null);
            $table->text('lang')->nullable()->default(null);                        
            $table->text('tags')->nullable()->default(null);                        
            $table->text('genre')->nullable()->default(null);
            $table->boolean('active')->default(1);
            $table->integer('downloads')->default(0);

            $table->string('title')->nullable()->default(null);
            $table->string('subtitle')->nullable()->default(null);
            $table->string('fulltitle')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            
            $table->string('time')->nullable()->default(null);
            
            $table->integer('size')->nullable()->default(null);
            $table->string('quality')->nullable()->default(null);
            $table->string('video_stream')->nullable()->default(null);            
            $table->string('video_codec')->nullable()->default(null);
            $table->string('audio_stream')->nullable()->default(null);
            $table->string('bitrate')->nullable()->default(null);                     

            $table->string('cover')->nullable()->default(null);
            $table->string('fsk')->nullable()->default(null);
            $table->string('rating')->nullable()->default(null);

            $table->nullableMorphs('source');

            $table->text('options')->nullable()->default(null);
            $table->string('trailer')->nullable()->default(null);

            $table->date('released_at')->nullable()->default(null);
            $table->date('verified_at')->nullable()->default(null);

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
        Schema::table('entries', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::dropIfExists('entries');
    }
};
