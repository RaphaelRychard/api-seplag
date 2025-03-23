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
            $table->id('fp_id');
            $table->foreignIdFor(Person::class, 'pes_id')->constrained('pessoa');
            $table->date('fp_data');
            $table->date('fp_bucket');
            $table->string('fp_hash', 50);
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
