<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernmentPosting extends Model
{
    /** @use HasFactory<\Database\Factories\GovernmentPostingFactory> */
    use HasFactory;

    protected $primaryKey = 'lot_id';

    protected $table = 'locatacao';

    public $timestamps = false;
}
