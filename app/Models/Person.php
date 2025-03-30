<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    /** @use HasFactory<\Database\Factories\PersonFactory> */
    use HasFactory;

    protected $table = 'pessoa';

    public $timestamps = false;

    public function permanentServant()
    {
        return $this->hasOne(PermanentServants::class, 'pes_id');
    }

    public function temporaryServant()
    {
        return $this->hasOne(TemporaryServants::class, 'pes_id');
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class, 'pes_id');
    }

    public function personsPhoto()
    {
        return $this->hasOne(PersonsPhoto::class, 'pes_id');
    }
}
