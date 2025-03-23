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
        Schema::create('locatacao', function (Blueprint $table): void {
            $table->id('lot_id');
            $table->foreignIdFor(Person::class)->constrained('pessoa');
            $table->foreignIdFor(Unit::class)->constrained('unidade');
            $table->date('lot_data_lotacao');
            $table->date('lot_data_remocao');
            $table->string('lot_portaria', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locatacao');
    }
};
