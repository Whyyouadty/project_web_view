<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $data =  Jabatan::all();
        return view('pages.jabatan', ['data' => $data]);
    }

    public function all() 
    {
        $data =  Jabatan::all();
        return response()->json(
            [
                'message' => 'success',
                'data' => $data,
                'code' => 200
            ],200
        );
    }

    public function store(Request $request)
    {
       try {
        $date = Carbon::now();
        $data = array(
            'nama_jabatan' => $request->nama_jabatan,
            'created_at' => $date,
        );
        $data = Jabatan::create($data);
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
            $data = Jabatan::whereId($id)->first();

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
            'nama_jabatan' => $request->nama_jabatan,
            'updated_at' => $date,
        ];
        $data = Jabatan::where(['id' => $id])->update($data);
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
            $data =  Jabatan::find($id);
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
