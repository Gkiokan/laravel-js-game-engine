<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $cols = [
        'size',
        'quality',
        'video_stream',
        'video_codec',
        'audio_stream',
        'bitrate',                
    ];

    public function up()
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->dropColumn($this->cols);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entries', function (Blueprint $table) {
            foreach($this->cols as $col)
                $table->string($col)->nullable()->default(null)->after('time');
        });
    }
};
