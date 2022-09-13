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
        Schema::create('i_m_d_b_s', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->default(null)->references('id')->on('users');
            $table->string('url')->nullable()->default(null);
            $table->string('source')->nullable()->default(null);
            $table->string('source_id')->nullable()->default(null);
            $table->string('lang')->nullable()->default(null);
            $table->string('key')->nullable()->default(null);
            $table->text('value')->nullable()->default(null);

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
        Schema::table('i_m_d_b_s', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
        Schema::dropIfExists('i_m_d_b_s');
    }
};
