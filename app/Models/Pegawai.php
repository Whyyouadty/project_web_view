<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'pegawai';
    protected $fillable = [
        'id',
        'user_id',
        'foto',
        'nama',
        'nidn',
        'departement_id',
        'jabatan_id',
        'ttl',
        'alamat',
        'agama',
        'jk',
        'no_hp',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function scopejoinList($query)
    {
        return $query
        ->leftJoin('user as model_a', 'pegawai.user_id', '=', 'model_a.id')
        ->leftJoin('departement as model_b', 'pegawai.departement_id', '=', 'model_b.id')
        ->leftJoin('jabatan as model_c', 'pegawai.jabatan_id', '=', 'model_c.id')
        ->select(
            'pegawai.id',
            'model_a.username as user',
            'pegawai.nama',
            'pegawai.nidn',
            'model_b.nama_departement as departement',
            'model_c.nama_jabatan as jabatan',
            'pegawai.ttl',
            'pegawai.alamat',
            'pegawai.agama',
            'pegawai.jk',
            'pegawai.no_hp',
            'pegawai.created_at',
            'pegawai.updated_at',
        );
    }
}
