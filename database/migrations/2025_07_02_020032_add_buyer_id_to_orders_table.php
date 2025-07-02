<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rendre buyer_id nullable
            $table->unsignedBigInteger('buyer_id')->nullable()->change();
            
            // Ajouter la référence au guest_buyer
            $table->unsignedBigInteger('guest_buyer_id')->nullable()->after('buyer_id');
            $table->foreign('guest_buyer_id')->references('id')->on('guest_buyers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['guest_buyer_id']);
            $table->dropColumn('guest_buyer_id');
            $table->unsignedBigInteger('buyer_id')->nullable(false)->change();
        });
    }
};
