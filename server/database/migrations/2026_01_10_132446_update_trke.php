<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //update trke
        Schema::table('trke', function (Blueprint $table) {
            $table->foreign('lokacija_id')->references('id')->on('lokacije')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trke', function (Blueprint $table) {
            $table->dropForeign(['lokacija_id']);
        });
    }
};
