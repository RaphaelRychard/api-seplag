<?php

declare(strict_types = 1);

namespace App\Models;

use Database\Factories\TemporaryServantsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemporaryServants extends Model
{
    /** @use HasFactory<TemporaryServantsFactory> */
    use HasFactory;

    protected $table = 'servidor_temporario';

    public $timestamps = false;

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'pes_id');
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class, 'pes_id', 'pes_id');
    }
}
