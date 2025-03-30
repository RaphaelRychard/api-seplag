<?php

declare(strict_types = 1);

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
        Schema::create('foto_pessoa', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Person::class, 'pes_id')->constrained('pessoa');
            $table->date('data');
            $table->string('bucket', 50);
            $table->string('hash', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_pessoa');
    }
};
