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
        
            Schema::table('propinas', function (Blueprint $table) {
        $table->string('referente_mes')->after('metodo_pagamento');
    });

        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('propinas', function (Blueprint $table) {
        $table->dropColumn('referente_mes');
    });

    }
};
