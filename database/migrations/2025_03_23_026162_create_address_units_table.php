<?php

declare(strict_types = 1);

use App\Models\Address;
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
        Schema::create('unidade_endereco', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Unit::class, 'unid_id')->constrained('unidade');
            $table->foreignIdFor(Address::class, 'end_id')->constrained('endereco');

            $table->primary(['unid_id', 'end_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidade_endereco');
    }
};
