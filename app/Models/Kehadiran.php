<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;
    protected $table = 'kehadiran';
    protected $fillable = [
        'id',
        'pegawai_id',
        'koordinat_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status',
        'gate_id',
        'created_at',
        'updated_at'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function koordinat()
    {
        return $this->belongsTo(Koordinat::class, 'koordinat_id');
    }

    public function gate()
    {
        return $this->belongsTo(Gate::class, 'gate_id');
    }

    public function scopejoinList($query)
    {
        return $query
            ->leftJoin('pegawai as model_a', 'kehadiran.pegawai_id', '=', 'model_a.id')
            ->leftJoin('koordinat as model_b', 'kehadiran.koordinat_id', '=', 'model_b.id')
            ->leftJoin('gate as model_c', 'kehadiran.gate_id', '=', 'model_c.id')
            ->select(
                'kehadiran.id',
                'model_a.nama',
                'model_b.latitude',
                'model_b.longtitude',
                'kehadiran.tanggal',
                'kehadiran.jam_masuk',
                'kehadiran.jam_keluar',
                'kehadiran.status',
                'model_c.no_sesi as sesi',
                'kehadiran.created_at',
                'kehadiran.updated_at',
            );
    }
}
