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
        Schema::create('r_e_q_s', function (Blueprint $table) {
            $table->id();

            $table->string('fulltitle')->nullable()->default(null);
            $table->string('error')->nullable()->default(null);
            $table->integer('release_id')->nullable()->default(null);
            $table->integer('entry_id')->nullable()->default(null);
            $table->date('filled_at')->nullable()->default(null);

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
        Schema::dropIfExists('r_e_q_s');
    }
};
