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
        Schema::create('endereco', function (Blueprint $table): void {
            $table->id('end_id')->primary();
            $table->string('end_tipo_logradouro', 50);
            $table->string('end_logradouro', 200);
            $table->integer('end_numero');
            $table->string('end_bairro', 100);

            // Aqui, especificamos que a chave estrangeira vai referenciar a coluna 'cid_id' da tabela 'cidade'
            $table->foreignId('cid_id')->constrained('cidade', 'cid_id')->onDelete('cascade');
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
