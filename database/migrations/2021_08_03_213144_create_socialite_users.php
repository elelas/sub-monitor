<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('socialite_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('socialite_user_id')->index();
            $table->string('provider_name')->index();
            $table->string('user_id')->index();
            $table->timestamps();

            $table->unique(['socialite_user_id', 'provider_name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('socialite_users');
    }
};