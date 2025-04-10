<?php

declare(strict_types = 1);

use App\Models\Address;
use App\Models\Person;
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
        Schema::create('pessoa_endereco', function (Blueprint $table): void {
            $table->foreignIdFor(Person::class, 'pes_id')->constrained('pessoa');
            $table->foreignIdFor(Address::class, 'end_id')->constrained('endereco');

            $table->primary(['pes_id', 'end_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoa_endereco');
    }
};
