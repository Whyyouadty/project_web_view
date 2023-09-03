<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;
    protected $table = 'user';
    protected $fillable = [
        'id',
        'username',
        'password',
        'level',
        'created_at',
        'updated_at'
    ];

    // protected $hidden = [
    //     'password'
    // ];
}
