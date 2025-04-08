<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressPersons extends Model
{
    /** @use HasFactory<\Database\Factories\AddressPersonsFactory> */
    use HasFactory;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $table = 'pessoa_endereco';

    public $timestamps = false;
}
