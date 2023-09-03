<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use App\Models\Kehadiran;
use App\Models\Koordinat;
use App\Models\Pegawai;
use App\Models\Setup;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    public function index()
    {
        $data =  array(
            'pegawai' => Pegawai::all(),
            'koordinat' => Koordinat::all(),
            'gate' => Gate::all(),
            'kehadiran' => Kehadiran::with('pegawai','koordinat','gate')->get(),
        );
        $setupData = Setup::first();
        // return response()->json($data);
        return view('pages.kehadiran', compact('data', 'setupData'));
    }

    
    public function store(Request $request)
    {
        try {
            $tipe = 'waktu_kerja';
            $setupData = Setup::where('tipe', $tipe)->first();

            if (!$setupData) {
                throw new \Exception('Data setup not found.');
            }

            $jamMasuk = Carbon::parse($request->jam_masuk);
            $waktuMulaiKerja = Carbon::parse($setupData->start);
            $waktuSelesaiKerja = Carbon::parse($setupData->end);

            $selisihMenit = $jamMasuk->diffInMinutes($waktuMulaiKerja);
            $batasTerlambat = 60;
            
            if ($jamMasuk->lt($waktuMulaiKerja) || $jamMasuk->gt($waktuSelesaiKerja)) {
                $status = 'Tidak Masuk';
            } elseif ($selisihMenit <= $batasTerlambat) {
                $status = 'Masuk Tepat Waktu';
            } else {
                $status = 'Terlambat';
            }

            $date = Carbon::now();
            $data = [
                'pegawai_id'   => $request->pegawai_id,
                'koordinat_id' => $request->koordinat_id, 
                'tanggal'      => $request->tanggal,
                'jam_masuk'    => $request->jam_masuk, 
                'jam_keluar'   => $request->jam_keluar,
                'status'       => $status, 
                'keterangan'   => $request->keterangan,
                'gate_id'      => $request->gate_id,
                'created_at'   => $date,
            ];

            $data = Kehadiran::create($data);
            $result = [
                'message' => 'success',
                'data' => $data,
                'code' => 200
            ];
        } catch (\Throwable $th) {
            $result = [
                'message' => $th->getMessage(),
                'code' => 500
            ];
        }
        return response()->json($result, $result['code']);
    }
    

    public function getById($id)
    {
		try {
            $data = Kehadiran::whereId($id)->first();

            if ($data) {
                $result = [
                    'message' => 'success',
                    'data' => $data,
                    'code' => 200
                ];
            } else {
                $result = [
                    'message' => 'not found',
                    'code' => 404
                ];
            }
        } catch (\Throwable $th) {
            $result = [
                'message' => $th->getMessage(),
                'code' => 500
            ];
        }
        return response()->json($result, $result['code']);
    }

    public function update(Request $request, $id)
    {
        try {
            $date = Carbon::now();
            $data = [
                'pegawai_id'    => $request->pegawai_id,
                'koordinat_id'  => $request->koordinat_id,
                'tanggal'       => $request->tanggal,
                'jam_masuk'     => $request->jam_masuk,
                'jam_keluar'    => $request->jam_keluar,
                'status'        => $request->status,
                'keterangan'    => $request->keterangan,
                'gate_id'       => $request->gate_id,
                'updated_at'    => $date,
            ];
            $data = Kehadiran::where(['id' => $id])->update($data);
            $result = [
                'message' => 'success',
                'data' => $data,
                'code' => 200
            ];
        } catch (\Throwable $th) {
            $result = [
                'message' => $th->getMessage(),
                'code' => 500
            ];
        }
        return response()->json($result, $result['code']);
    }

    public function delete($id)
    {
        try {
            $data =  Kehadiran::find($id);
            $data->delete();
            $result = [
                'message' => 'success',
                'data' => $data,
                'code' => 200
            ];
        } catch (\Throwable $th) {
            $result = [
                'message' => $th->getMessage(),
                'code' => 500
            ];
        }
        return response()->json($result, $result['code']);
    }
}
