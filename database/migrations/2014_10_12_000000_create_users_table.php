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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('bday')->nullable();

            $table->string('username')->unique();
            $table->string('mobile')->nullable()->unique();

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('avatar')->nullable()->default(null);
            $table->string('password');

            $table->string('role')->nullable()->default('user');

            $table->string('secret')->nullable()->default(null);
            $table->boolean('g2fa')->default(0);

            $table->boolean('valid')->nullable()->default(0);
            $table->boolean('active')->nullable()->default(0);
            $table->boolean('gain_access')->nullable()->default(0);

            $table->boolean('info_send')->nullable()->default(0);
            $table->datetime('info_send_at')->nullable()->default(null);

            $table->datetime('activate_send')->nullable()->default(null);
            $table->datetime('activate_at')->nullable()->default(null);
            $table->string('activate_token')->nullable()->default(null);

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
