<?php

declare(strict_types = 1);

namespace App\Models;

use Database\Factories\PersonFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Model
{
    /** @use HasFactory<PersonFactory> */
    use HasFactory;

    protected $table = 'pessoa';

    public $timestamps = false;

    public function permanentServant(): HasOne
    {
        return $this->hasOne(PermanentServants::class, 'pes_id');
    }

    public function temporaryServant(): HasOne
    {
        return $this->hasOne(TemporaryServants::class, 'pes_id');
    }

    public function assignment(): HasOne
    {
        return $this->hasOne(Assignment::class, 'pes_id')
            ->whereNull('data_remocao');
    }

    public function personsPhoto(): HasOne
    {
        return $this->hasOne(PersonsPhoto::class, 'pes_id');
    }
}
