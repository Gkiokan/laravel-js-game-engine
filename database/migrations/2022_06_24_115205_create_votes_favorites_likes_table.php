<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public $tables = ['votes', 'favorites', 'likes', 'ratings'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach($this->tables as $t)
        Schema::create($t, function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('vote')->nullable()->default(null);

            $table->morphs('vote');
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
        foreach($this->tables as $t)
        Schema::table($t, function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        foreach($this->tables as $t)
        Schema::dropIfExists($t);
    }
};
