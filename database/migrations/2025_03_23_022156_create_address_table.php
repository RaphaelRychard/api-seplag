<?php

declare(strict_types = 1);

use App\Models\City;
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
        Schema::create('endereco', function (Blueprint $table): void {
            $table->id();
            $table->string('tipo_logradouro', 50);
            $table->string('logradouro', 200);
            $table->integer('numero');
            $table->string('bairro', 100);
            $table->foreignIdFor(City::class, 'cid_id')->constrained('cidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('endereco');
    }
};
