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
            $table->id('id');
            $table->foreignIdFor(Person::class)->constrained('pessoa');
            $table->foreignIdFor(Address::class)->constrained('endereco');
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
