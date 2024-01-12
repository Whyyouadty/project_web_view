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
            'pegawai' => Pegawai::with('user', 'departement', 'jabatan')->get(),
        );
        // return response()->json($data);

        return view('pages.pegawai', ['data' => $data]);
    }

    public function all()
    {
        $data =  Pegawai::all();
        return response()->json(
            [
                'message' => 'success',
                'data' => $data,
                'code' => 200
            ],
            200
        );
    }

    public function store(Request $request)
    {
        try {
            $fileUpload = $request->file('foto');
            $nameFile = time() . '.' . $fileUpload->getClientOriginalExtension();
            $fileUpload->move(public_path('storage/foto'), $nameFile);

            $data = [
                'user_id'        => $request->user_id,
                'foto'           => $nameFile,
                'nama'           => $request->nama,
                'nidn'           => $request->nidn,
                'departement_id' => $request->departement_id,
                'jabatan_id'     => $request->jabatan_id,
                'ttl'            => $request->ttl,
                'alamat'         => $request->alamat,
                'agama'          => $request->agama,
                'jk'             => $request->jk,
                'no_hp'          => $request->no_hp,
            ];
            $data = Pegawai::create($data);
            $result = [
                'message' => 'success',
                'data' => [
                    'foto_url' => url('storage/foto/' . $nameFile),
                    // ... (data lainnya)
                ],
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
        $fileUpload = $request->file('foto');
        $nameFile = time() . '.' . $fileUpload->getClientOriginalExtension();
        $fileUpload->move(public_path('storage/foto'), $nameFile);
        try {
            $date = Carbon::now();
            $data = [
                'user_id'        => $request->user_id,
                'nama'           => $request->nama,
                'nidn'           => $request->nidn,
                'departement_id' => $request->departement_id,
                'jabatan_id'     => $request->jabatan_id,
                'ttl'            => $request->ttl,
                'alamat'         => $request->alamat,
                'agama'          => $request->agama,
                'jk'             => $request->jk,
                'no_hp'          => $request->no_hp,
                'updated_at' => $date,
            ];
            $data = Pegawai::where(['id' => $id])->update($data);
            $result = [
                'message' => 'success',
                'data' => [
                    'foto_url' => url('storage/foto/' . $nameFile),
                    // ... (data lainnya)
                ],
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
