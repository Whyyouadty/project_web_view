<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    public function index()
    {
        $data =  Departement::all();
        return view('pages.departement', ['data' => $data]);
    }

    public function all() 
    {
        $data =  Departement::all();
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
            'nama_departement' => $request->nama_departement,
            'created_at' => $date,
        );
        $data = Departement::create($data);
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
            $data = Departement::whereId($id)->first();

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
            'nama_departement' => $request->nama_departement,
            'updated_at' => $date,
        ];
        $data = Departement::where(['id' => $id])->update($data);
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
            $data =  Departement::find($id);
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
