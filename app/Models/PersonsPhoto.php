<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonsPhoto extends Model
{
    /** @use HasFactory<\Database\Factories\PersonsPhotoFactory> */
    use HasFactory;


    protected $table = 'foto_pessoa';

    public $timestamps = false;
}
