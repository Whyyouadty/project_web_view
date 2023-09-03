<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index()
    {
        $data =  array(
            'user' => User::all(),
            'departement' => Departement::all(),
            'jabatan' => Jabatan::all(),
            'pegawai' => Pegawai::with('user','departement','jabatan')->get(),
        );
        // return response()->json($data);
        
        return view('pages.pegawai', ['data' => $data]);
    }

    public function store(Request $request)
    {
       try {
        $date = Carbon::now();
        $data = array(
            'user_id'=>$request->user_id,
            'nama'=>$request->nama,
            'nidn'=>$request->nidn,
            'departement_id'=>$request->departement_id,
            'jabatan_id'=>$request->jabatan_id,
            'ttl'=>$request->ttl,
            'alamat'=>$request->alamat,
            'agama'=>$request->agama,
            'jk'=>$request->jk,
            'no_hp'=>$request->no_hp,
            'created_at'=>$date,
        );
        $data = Pegawai::create($data);
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
            $data = Pegawai::whereId($id)->first();

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
            'user_id'        => $request->       user_id,
            'nama'           => $request->          nama,
            'nidn'           => $request->          nidn,
            'departement_id' => $request->departement_id,
            'jabatan_id'     => $request->    jabatan_id,
            'ttl'            => $request->           ttl,
            'alamat'         => $request->        alamat,
            'agama'          => $request->         agama,
            'jk'             => $request->            jk,
            'no_hp'          => $request->         no_hp,
            'updated_at' => $date,
        ];
        $data = Pegawai::where(['id' => $id])->update($data);
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
            $data =  Pegawai::find($id);
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
