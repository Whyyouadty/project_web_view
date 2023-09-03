<?php

namespace App\Http\Controllers;

use App\Models\Koordinat;
use App\Models\Log;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;


class LogController extends Controller
{
    public function index()
    {
            $data =  array(
            'koordinat' => Koordinat::all(),
            'pegawai' => Pegawai::all(),
            'log' => Log::with('koordinat','pegawai')->get(),
        );
        return view('pages.log', ['data' => $data]);
    }

    public function store(Request $request)
    {
        try {
            $date = Carbon::now();
            $Data = [
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'koordinat_id' => $request->koordinat_id,
                'pegawai_id' => $request->pegawai_id,
                'status' => $request->status,
                'created_at' => $date,
            ];

            $log = Log::create($Data);

            $result = [
                'message' => 'success',
                'data' => $log,
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
            $data = Log::whereId($id)->first();

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
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'koordinat_id' => $request->koordinat_id,
            'pegawai_id' => $request->pegawai_id,
            'status' => $request->status,
            'updated_at' => $date,
        ];
        $data = Log::where(['id' => $id])->update($data);
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
            $data =  Log::find($id);
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
