<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryEmployee extends Model
{
    /** @use HasFactory<\Database\Factories\TemporaryEmployeeFactory> */
    use HasFactory;

    public $incrementing = false;

    protected $primaryKey = null;

    protected $table = 'servidor_temporario';

    public $timestamps = false;
}
