<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Gate;
use App\Models\Kehadiran;
use App\Models\Koordinat;
use App\Models\Log;
use App\Models\Pegawai;
use App\Models\Setup;
use App\Traits\HttpResponseModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    use HttpResponseModel;
    private $destinationCoordinate = null;

    private function isONTimePresent($date)
    {
        $now = Carbon::now();

        $startTime = Carbon::parse($date['start']);
        $endTime   = Carbon::parse($date['end']);

        return $now->between($startTime, $endTime);
    }

    public function getCurrentGate()
    {
        try {
            $today = Carbon::today();
            $data = Gate::whereDate('created_at', $today)->first();

            if ($data) {
                $isOnTime = @$this->isONTimePresent($data) ?? null;
            } else {
                $isOnTime = null;
            }

            $response = $this->success($isOnTime ? @$data['id'] : null, "success getting data");
        } catch (\Throwable $th) {
            $response = $this->error($th->getMessage(), 500, $th, class_basename($this), __FUNCTION__);
        }
        return response()->json($response, $response['code']);
    }

    private function saveCoordinate($latitude, $longtitude)
    {
        try {
            $model = new Koordinat();

            $payload = [
                "latitude" => $latitude,
                "longtitude" => $longtitude,

            ];
            $result = $model->create($payload);
            return $result['id'];
        } catch (\Throwable $th) {
            return null;
        }
    }

    private function writeLogs($coordinate)
    {
        try {
            $userId = Auth::user()->id;
            $pegawai = Pegawai::where('user_id', $userId)->first();

            $payload = [
                "tanggal" => Carbon::now(),
                "waktu" => Carbon::now()->format('H:i:s'),
                "koordinat_id" => $coordinate,
                "pegawai_id" => $pegawai['id'],
                "status" => "-"
            ];

            Log::create($payload);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function getPresentStatus($employe)
    {
        try {
            $date    = new Carbon;
            $timeNow = $date->toTimeString();

            $waktuKerja     = Setup::where('tipe', 'waktu_kerja')->first();
            $waktiIstirahat = Setup::where('tipe', 'waktu_istirahat')->first();

            $present = Kehadiran::where('pegawai_id', $employe['id'])->first();

            if ($present && $present['jam_masuk']) {

                if (($timeNow < $waktiIstirahat['start'] || $timeNow > $waktiIstirahat['end']) && ($timeNow > $waktuKerja['end'])) {
                    return [
                        'msg'  => 'pulang tepat waktu',
                        'sts'  => 3, //pulang tepat waktu
                        'code' => 200
                    ];
                }

                if (($timeNow < $waktiIstirahat['start'] || $timeNow > $waktiIstirahat['end']) && ($timeNow < $waktuKerja['end'])) {
                    return [
                        'msg'  => 'terlalu cepat pulang',
                        'sts'  => 4, //terlalu cepat pulang
                        'code' => 200
                    ];
                }

                if (($timeNow >= $waktiIstirahat || $timeNow <= $waktiIstirahat)) {
                    return [
                        'msg'  => 'waktu istirahat',
                        'sts'  => 0, //istirahat
                        'code' => 422
                    ];
                }

                return [
                    'msg'  => 'pulang tepat waktu',
                    'sts'  => 3, //pulang tepat waktu
                    'code' => 200
                ];
            }

            if ($present && (!$present['jam_masuk'])) {
                if (($timeNow < $waktiIstirahat['start'] || $timeNow > $waktiIstirahat['end']) && ($timeNow > Carbon::createFromTimeString($waktuKerja['start'])->toTimeString())) {
                    return [
                        'msg'  => 'datang terlambat',
                        'sts'  => 2, //terlambat
                        'code' => 200
                    ];
                }

                return [
                    'msg'  => 'datang tepat waktu',
                    'sts'  => 1, //tepat waktu
                    'code' => 200
                ];
            }

            if (!$present) {
                if (($timeNow > Carbon::createFromTimeString($waktuKerja['start'])->addHours()->toTimeString())) {
                    return [
                        'msg'  => 'datang terlambat',
                        'sts'  => 2, //terlambat
                        'code' => 200
                    ];
                }

                return [
                    'msg'  => 'datang tepat waktu',
                    'sts'  => 1, //tepat waktu
                    'code' => 200
                ];
            }
        } catch (\Throwable $th) {
            return [
                'msg'  => $th->getMessage(),
                'sts'  => 0, //error
                'code' => 500
            ];
        }
    }

    // Untuk mengukur jarak dari koordinat satu ke lainnya
    private function haversine_distance($lat1, $lon1, $lat2, $lon2)
    {
        // Konversi koordinat dari derajat ke radian
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Hitung selisih lintang dan bujur
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        // Rumus Haversine
        $a = sin($dlat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dlon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Jari-jari Bumi dalam meter
        $R = 6371000;

        // Hitung jarak
        $distance = $R * $c;

        return number_format($distance, 2);
    }

    //Check avaible day
    private function checkAvaiblePresentDay()
    {
        $auth = auth()->user()->id;
        $nowDay = Carbon::now()->toDateString();

        $setupModel = new Kehadiran;
        $setupData = $setupModel
            ->select(
                'kehadiran.*'
            )
            ->leftJoin('pegawai as pg', 'kehadiran.pegawai_id', '=', 'pg.id')
            ->leftJoin('user as usr', 'pg.user_id', '=', 'usr.id')
            ->where('usr.id', $auth)
            ->whereDate('kehadiran.created_at', $nowDay)
            ->first();

        return $setupData;
    }

    // Check avaible present time
    private function checkAvaiblePresentTime()
    {
        $setupModel = new Setup;
        $setupData = $setupModel->first();

        //jika data tidak ditemukan
        if (!$setupData) {
            return false;
        }

        $sDate = Carbon::createFromTimeString($setupData->start);
        $eDate = Carbon::createFromTimeString($setupData->end);

        $now = Carbon::now();

        if ($now->greaterThan($sDate) && $now->lessThan($eDate)) {
            return [
                "avaible" => true,
                "status" => $now->greaterThan($sDate->addMinute(30)) ? "lambat" : "tepat waktu"
            ];
        }
        return [
            "avaible" => false,
            "status" => null
        ];
    }

    public function presentPegawai(Request $request)
    {
        $lat1 = $request->lat;
        $lon1 = $request->lon;

        // Koordinat kedua
        $lat2 = config('coordinat.latitude');
        $lon2 = config('coordinat.longitude');

        try {
            $statusKehadiran = "";
            $now = Carbon::now();
            
            if ($this->haversine_distance($lat1, $lon1, $lat2, $lon2) > 30) {
                return response()->json([
                    "message" => "kamu tidak berada pada lingkungan absensi"
                ], 400);
            }

            $avaibleTime     = $this->checkAvaiblePresentTime();
            $statusKehadiran = $avaibleTime['status'];

            if (!$avaibleTime['avaible']) {
                return response()->json([
                    "message" => "waktu absensi tidak berlaku"
                ], 400);
            }

            $presentDay = $this->checkAvaiblePresentDay();

            $kehadiran = new Kehadiran;

            if (!$presentDay) {
                // Absen sebagai jam masuk, perlu mengecek keterlambatan
                $kehadiran->create([
                    "pegawai_id" => $request->pegawai_id,
                    "tanggal"    => $now->format('Y-m-d'),
                    "jam_masuk"  => $now->format('H:i:s'),
                    "status"     => $statusKehadiran,
                    "created_at" => $now,
                    "updated_at" => $now,
                ]);
                return response()->json([
                    "message" => "berhasil melakukan absen datang"
                ], 200);
            }

            if (!$presentDay->jam_keluar) {
                // Absen sebagai jam pulang, perlu mengecek keterlambatan
                $kehadiran->where("id", $presentDay->id)->update([
                    "pegawai_id" => $request->pegawai_id ,
                    "tanggal"    => $now->format('Y-m-d'),
                    "jam_keluar" => $now->format('H:i:s'),
                    "created_at" => $now,
                    "updated_at" => $now,
                ]);
                return response()->json([
                    "message" => "berhasil melakukan pulang"
                ], 200);
            }

            return response()->json([
                "message" => "kamu sudah absen hari ini"
            ], 400);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function getCurrentPresentHistory()
    {
        try {
            $payload = [
                'start' => Carbon::createFromDate(request('start')) ?? Carbon::now()->firstOfMonth()->toDateString(),
                'end'   => Carbon::createFromDate(request('end')) ?? Carbon::now()->endOfMonth()->toDateString()
            ];

            $userId  = Auth::user()->id;
            $pegawai = Pegawai::where('user_id', $userId)->first();

            if (!$userId) {
                return response()->json("Pegawai tidak ditemukan", 404);
            }

            $result = Kehadiran::where('pegawai_id', $pegawai['id'])
                ->when(request('start') || request('end'), function ($query) use ($payload) {
                    return $query->whereBetween('tanggal', [$payload['start'], $payload['end']]);
                })
                ->select('id', 'tanggal', 'jam_masuk', 'jam_keluar', 'status')
                ->get();

            $response = $this->success(@$result ?? null, "success get data");
        } catch (\Throwable $th) {
            $response = $this->error($th->getMessage(), 500, $th, class_basename($this), __FUNCTION__);
        }
        return response()->json($response, $response['code']);
    }
}
