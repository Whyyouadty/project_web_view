<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Kehadiran;
use App\Models\Pegawai;
use App\Traits\HttpResponseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiProfileController extends Controller
{
    use HttpResponseModel;

    public function getCurrentUserData()
    {
        try {
            $userId = Auth::user()->id;
            $pegawai = Pegawai::where('user_id', $userId)->with('jabatan', 'departement')->first();
            
            $response = $this->success($pegawai, "success getting data");
        } catch (\Throwable $th) {
            $response = $this->error($th->getMessage(), 500, $th, class_basename($this), __FUNCTION__ );
        }
        return response()->json($response, $response['code']);
    }

    public function getCurrnetUserKehadiran()
    {
        try {
            $userId = Auth::user()->id;
            $pegawai = Pegawai::where('user_id', $userId)->first();
            $kehadiran = Kehadiran::where('pegawai_id', $pegawai->id)->get();

            $response = $this->success($kehadiran, "success getting data");
        } catch (\Throwable $th) {
            $response = $this->error($th->getMessage(), 500, $th, class_basename($this), __FUNCTION__ );
        }

        return response()->json($response, $response['code']);
    }
}
