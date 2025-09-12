<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReciboIdToSalariosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
            
Schema::table('salarios', function (Blueprint $table) {
            $table->string('recibo_id')->unique()->after('referente_mes');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salarios', function (Blueprint $table) {
            //
        });
    }
};
