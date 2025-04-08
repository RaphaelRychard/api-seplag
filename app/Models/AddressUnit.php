<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressUnit extends Model
{
    /** @use HasFactory<\Database\Factories\AddressUnitFactory> */
    use HasFactory;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $table = 'unidade_endereco';

    public $timestamps = false;

    public function address()
    {
        return $this->belongsTo(Address::class, 'end_id');
    }
}
