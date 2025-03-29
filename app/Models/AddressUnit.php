<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressUnit extends Model
{
    /** @use HasFactory<\Database\Factories\AddressUnitFactory> */
    use HasFactory;

    protected $table = 'unidade_endereco';

    public $timestamps = false;
}
