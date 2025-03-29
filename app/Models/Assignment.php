<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    /** @use HasFactory<\Database\Factories\Assignment> */
    use HasFactory;

    protected $table = 'lotacao';

    public $timestamps = false;

    public function person()
    {
        return $this->belongsTo(Person::class, 'pes_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unid_id');
    }
}
