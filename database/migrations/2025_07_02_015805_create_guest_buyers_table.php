<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guest_buyers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->string('wilaya');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guest_buyers');
    }
};
