<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GateController extends Controller
{
    public function index()
    {
        $data =  Gate::all();
        return view('pages.Gate', ['data' => $data]);
    }

    public function all() 
    {
        $data =  Gate::all();
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
            'no_sesi' => $request->no_sesi,
            'start' => $request->start,
            'end' => $request->end,
            'created_at' => $date,
        );
        $data = Gate::create($data);
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
            $data = Gate::whereId($id)->first();

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
            'no_sesi' => $request->no_sesi,
            'start' => $request->start,
            'end' => $request->end,
            'updated_at' => $date,
        ];
        $data = Gate::where(['id' => $id])->update($data);
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
            $data =  Gate::find($id);
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
