<?php

declare(strict_types = 1);

use App\Models\Person;
use App\Models\Unit;
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
        Schema::create('lotacao', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Person::class, 'pes_id')->constrained('pessoa');
            $table->foreignIdFor(Unit::class, 'unid_id')->constrained('unidade');
            $table->date('data_lotacao');
            $table->date('data_remocao')->nullable();
            $table->string('portaria', 100);

            // Índice único composto para pes_id e data_remocao
            $table->unique(['pes_id', 'data_remocao']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotacao');
    }
};
