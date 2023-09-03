<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Koordinat extends Model
{
    use HasFactory;
    protected $table = 'koordinat';
    protected $fillable = [
        'id',
        'latitude',
        'longtitude',
        'created_at',
        'updated_at'
    ];
}
