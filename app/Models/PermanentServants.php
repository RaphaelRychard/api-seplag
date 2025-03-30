<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermanentServants extends Model
{
    /** @use HasFactory<\Database\Factories\PermanentServantsFactory> */
    use HasFactory;

    protected $table = 'servidor_efetivo';

    public $timestamps = false;

    public function person()
    {
        return $this->belongsTo(Person::class, 'pes_id');
    }

    public function getUnitOfLotacao()
    {
        return $this->person->assignment ? $this->person->assignment->unit : null;
    }

    // No modelo PermanentServants
    public function unidade_lotacao()
    {
        return $this->belongsTo(Unit::class, 'unid_id'); // Ajuste o nome da relação conforme seu esquema
    }
}
