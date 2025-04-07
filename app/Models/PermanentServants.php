<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermanentServants extends Model
{
    /** @use HasFactory<\Database\Factories\PermanentServantsFactory> */
    use HasFactory;

    protected $table = 'servidor_efetivo';

    public $timestamps = false;

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'pes_id');
    }
}
