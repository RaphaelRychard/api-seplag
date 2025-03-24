<?php

declare(strict_types = 1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unit>
 */
class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitSeplag = [
            "GAB"  => "Gabinete do Secretário de Estado de Planejamento e Gestão",
            "STI"  => "Superintendência de Tecnologia da Informação Setorial",
            "SES"  => "Superintendência de Estudos Socioeconômicos",
            "CSST" => "Conselho de Saúde e Segurança no Trabalho",
            "NAP"  => "Núcleo de Ações Prioritárias",
            "OUV"  => "Ouvidoria Setorial",
            "USCI" => "Unidade Setorial de Controle Interno",
            "SGFP" => "Superintendência de Gestão de Folha de Pagamento",
            "SAP"  => "Superintendência de Arquivo Público",
            "SPP"  => "Superintendência de Patrimônio Público",
        ];

        return [
            'unid_name'  => fake()->randomElement(array_values($unitSeplag)),
            'unid_sigla' => fake()->randomElement(array_keys($unitSeplag)),
        ];
    }
}
