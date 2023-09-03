<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use App\Models\Koordinat;
use App\Models\Setup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SetupController extends Controller
{
    public function index()
    {
        $data =  Setup::all();
        return view('pages.setup', ['data' => $data]);
    }

    public function store(Request $request)
    {
        try {
         $date = Carbon::now();
         $data = array(
             'start'      => $request->start,
             'end'        => $request->end,
             'tipe'       => $request->tipe,
             'created_at' => $date,
         );
         $data = Setup::create($data);
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
            $data = Setup::whereId($id)->first();

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
            'start'      => $request->start,
            'end'        => $request->  end,
            'tipe'       => $request-> tipe,
            'updated_at' => $date,
        ];
        $data = Setup::where(['id' => $id])->update($data);
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
            $data =  Setup::find($id);
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
