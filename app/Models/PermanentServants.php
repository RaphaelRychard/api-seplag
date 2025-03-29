<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermanentEmployee extends Model
{
    /** @use HasFactory<\Database\Factories\PermanentEmployeeFactory> */
    use HasFactory;

    public $incrementing = false;

    protected $primaryKey = null;

    protected $table = 'servidor_efetivo';

    public $timestamps = false;
}
