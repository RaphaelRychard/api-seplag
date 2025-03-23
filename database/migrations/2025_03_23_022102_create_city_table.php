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
        Schema::create('cidade', function (Blueprint $table) {
            $table->id('cid_id')->primary();
            $table->string('cid_name', 200);
            $table->char('cid_uf', 12);
            $table->integer('end_numero');
            $table->string('end_bairro', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cidade');
    }
};
