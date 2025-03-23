<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pessoa', function (Blueprint $table): void {
            $table->id('pes_id');
            $table->string('pes_nome', 200);
            $table->date('pes_data_nascimento');
            $table->string('pes_sexo', 9);
            $table->string('per_mae', 200);
            $table->string('per_pai', 200);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoa');
    }
};
