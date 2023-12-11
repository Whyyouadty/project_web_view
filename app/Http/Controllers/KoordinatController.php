<?php

namespace App\Http\Controllers;

use App\Models\Koordinat;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KoordinatController extends Controller
{
    public function index()
    {
        $data =  Koordinat::all();
        return view('pages.koordinat', ['data' => $data]);
    }

    public function all() 
    {
        $data =  Koordinat::all();
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
            'latitude' => $request->latitude,
            'longtitude'   => $request->longtitude  ,
            'created_at'    => $date,
        );
        $data = Koordinat::create($data);
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
            $data = Koordinat::whereId($id)->first();

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
            'latitude' => $request->latitude,
            'longtitude'   => $request->longtitude,
            'updated_at'    => $date,
        ];
        $data = Koordinat::where(['id' => $id])->update($data);
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
            $data =  Koordinat::find($id);
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
