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
            $response = $this->error($th->getMessage(), 500, $th, class_basename($this), __FUNCTION__ );
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

    public function getPresentStatus($employe) {
        try {
            $date    = new Carbon;
            $timeNow = $date->toTimeString();

            $waktuKerja     = Setup::where('tipe', 'waktu_kerja')->first();
            $waktiIstirahat = Setup::where('tipe', 'waktu_istirahat')->first();

            $present = Kehadiran::where('pegawai_id', $employe['id'])->first();
           
            if ($present && $present['jam_masuk']) {
    
                if (($timeNow < $waktiIstirahat['start'] || $timeNow > $waktiIstirahat['end']) && ($timeNow > $waktuKerja['end']) ) {
                    return [
                        'msg'  => 'pulang tepat waktu',
                        'sts'  => 3, //pulang tepat waktu
                        'code' => 200
                    ];
                }
                
                if (($timeNow < $waktiIstirahat['start'] || $timeNow > $waktiIstirahat['end']) && ($timeNow < $waktuKerja['end']) ) {
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
                if (($timeNow < $waktiIstirahat['start'] || $timeNow > $waktiIstirahat['end']) && ($timeNow > Carbon::createFromTimeString($waktuKerja['start'])->toTimeString()) ) {
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
                if (($timeNow > Carbon::createFromTimeString($waktuKerja['start'])->addHours()->toTimeString()) ) {
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

    public function presentPegawai(Request $request)
    {
        info(json_encode($request->all()));
        DB::beginTransaction();
        $onTime = json_decode($this->getCurrentGate()->getContent(), true);

        if (!$onTime) {
            return $this->error('gate', 500);
        }

        $koordinateId = $this->saveCoordinate($request->latitude, $request->longtitude);
        if (!$koordinateId || null) {
            return $this->error('coordinate', 500);
        }

        $writeLogs = $this->writeLogs($koordinateId);

        if (!$writeLogs || null) {
            return $this->error('logs', 500);
        }

        try {
            $userId = Auth::user()->id;
            $pegawai = Pegawai::where('user_id', $userId)->first();
            
            $status = $this->getPresentStatus($pegawai);
            if ($status['code'] !== 200) {
                return response()->json($status, $status['code']);
            }

            if ($status['sts'] === 1 || $status['sts'] === 2) {
                $payload = [
                    "koordinat_id" => $koordinateId,
                    "pegawai_id" => $pegawai['id'],
                    "tanggal" => Carbon::now(),
                    "jam_masuk" => Carbon::now()->format('H:i:s'),
                    "status" => $status['msg'],
                    "gate_id" => $onTime['data']
                ];
    
                $result = Kehadiran::create($payload);
            }
            
            if ($status['sts'] === 3 || $status['sts'] === 4) {
                $present = Kehadiran::where('pegawai_id', $pegawai['id'])->first();
                $payload = [
                    "jam_keluar" => Carbon::now()->format('H:i:s'),
                    "status"     => $present['status'] . ', ' . $status['msg'],
                    "updated_at" => Carbon::now()
                ];
                
                $result  = Kehadiran::whereId($present['id'])->update($payload);
            }

            $response = $this->success(@$result ?? null, "success update data");

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $response = $this->error($th->getMessage(), 500, $th, class_basename($this), __FUNCTION__ );
        }
        return response()->json($response, $response['code']);
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

            if(!$pegawai) {
                return $this->error('Pegawai tidak ditemukan', 404);
            }

            $result = Kehadiran::where('pegawai_id', $pegawai['id'])
            ->when(request('start') || request('end'), function ($query) use ($payload) {
                return $query->whereBetween('tanggal', [$payload['start'], $payload['end']]);
            })
            ->select('id', 'tanggal', 'jam_masuk', 'jam_keluar', 'status')
            ->get();

            $response = $this->success(@$result ?? null, "success get data");
        } catch (\Throwable $th) {
            $response = $this->error($th->getMessage(), 500, $th, class_basename($this), __FUNCTION__ );
        }
        return response()->json($response, $response['code']);
    }
}
