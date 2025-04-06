<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryServants extends Model
{
    /** @use HasFactory<\Database\Factories\TemporaryServantsFactory> */
    use HasFactory;

    protected $table = 'servidor_temporario';

    public $timestamps = false;

    public function person()
    {
        return $this->belongsTo(Person::class, 'pes_id');
    }
}
