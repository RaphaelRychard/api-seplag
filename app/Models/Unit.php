<?php

declare(strict_types = 1);

namespace App\Models;

use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    /** @use HasFactory<UnitFactory> */
    use HasFactory;

    protected $table = 'unidade';

    public $timestamps = false;

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'unid_id');
    }
}
