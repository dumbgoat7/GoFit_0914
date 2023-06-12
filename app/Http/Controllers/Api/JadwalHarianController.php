<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\JadwalHarian;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\JadwalUmum;

class JadwalHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jadwalHarian = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_harian.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari', 'jadwal_umum.jam_mulai', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->orderByRaw("CASE 
                            WHEN jadwal_umum.hari = 'Monday' THEN 1 
                            WHEN jadwal_umum.hari = 'Tuesday' THEN 2 
                            WHEN jadwal_umum.hari = 'Wednesday' THEN 3 
                            WHEN jadwal_umum.hari = 'Thursday' THEN 4 
                            WHEN jadwal_umum.hari = 'Friday' THEN 5 
                            WHEN jadwal_umum.hari = 'Saturday' THEN 6 
                            WHEN jadwal_umum.hari = 'Sunday' THEN 7 
                            ELSE 8 
                        END")
                        ->get();

        if(count($jadwalHarian) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalHarian
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);

    }

    public function jadwalHarianMonday() {
        $startDate = Carbon::now()->startOfWeek(); // Get the start date of the current week (Monday)
        $endDate = $startDate->copy()->addDays(6); // Get the end date of the current week (Sunday)

        $jadwalHarianMonday = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_harian.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari', 
                        'jadwal_umum.jam_mulai', 'jadwal_umum.id_kelas', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('jadwal_umum.hari', '=', 'Monday')
                        ->whereBetween('jadwal_harian.tanggal', [$startDate,$endDate])
                        ->orderBy('jadwal_umum.jam_mulai', 'asc')
                        ->get();

        if(count($jadwalHarianMonday) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalHarianMonday
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function jadwalHarianTuesday() {
        $startDate = Carbon::now()->startOfWeek(); // Get the start date of the current week (Monday)
        $endDate = $startDate->copy()->addDays(6);

        $jadwalHarianTuesday = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_harian.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari', 
                        'jadwal_umum.jam_mulai', 'jadwal_umum.id_kelas', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('jadwal_umum.hari', '=', 'Tuesday')
                        ->whereBetween('jadwal_harian.tanggal', [$startDate,$endDate])
                        ->orderby('jadwal_umum.jam_mulai', 'asc')
                        ->get();

        if(count($jadwalHarianTuesday) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalHarianTuesday
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function jadwalHarianWednesday() {
        $startDate = Carbon::now()->startOfWeek(); // Get the start date of the current week (Monday)
        $endDate = $startDate->copy()->addDays(6);

        $jadwalHarianWednesday = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_harian.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari', 
                        'jadwal_umum.jam_mulai', 'jadwal_umum.id_kelas', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('jadwal_umum.hari', '=', 'Wednesday')
                        ->whereBetween('jadwal_harian.tanggal', [$startDate,$endDate])
                        ->orderby('jadwal_umum.jam_mulai', 'asc')
                        ->get();

        if(count($jadwalHarianWednesday) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalHarianWednesday
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function jadwalHarianThursday() {
        $startDate = Carbon::now()->startOfWeek(); // Get the start date of the current week (Monday)
        $endDate = $startDate->copy()->addDays(6);

        $jadwalHarianThursday = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_harian.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari', 
                        'jadwal_umum.jam_mulai', 'jadwal_umum.id_kelas', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('jadwal_umum.hari', '=', 'Thursday')
                        ->whereBetween('jadwal_harian.tanggal', [$startDate,$endDate])
                        ->orderby('jadwal_umum.jam_mulai', 'asc')
                        ->get();

        if(count($jadwalHarianThursday) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalHarianThursday
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function jadwalHarianFriday() {
        $startDate = Carbon::now()->startOfWeek(); // Get the start date of the current week (Monday)
        $endDate = $startDate->copy()->addDays(6);

        $jadwalHarianFriday = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_harian.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari',
                        'jadwal_umum.jam_mulai', 'jadwal_umum.id_kelas', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('jadwal_umum.hari', '=', 'Friday')
                        ->whereBetween('jadwal_harian.tanggal', [$startDate,$endDate])
                        ->orderby('jadwal_umum.jam_mulai', 'asc')
                        ->get();

        if(count($jadwalHarianFriday) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalHarianFriday
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function jadwalHarianSaturday() {
        $startDate = Carbon::now()->startOfWeek(); // Get the start date of the current week (Monday)
        $endDate = $startDate->copy()->addDays(6);

        $jadwalHarianSaturday = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_harian.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari',
                        'jadwal_umum.jam_mulai', 'jadwal_umum.id_kelas', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('jadwal_umum.hari', '=', 'Saturday')
                        ->whereBetween('jadwal_harian.tanggal', [$startDate,$endDate])
                        ->orderby('jadwal_umum.jam_mulai', 'asc')
                        ->get();

        if(count($jadwalHarianSaturday) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalHarianSaturday
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function jadwalHarianSunday() {
        $startDate = Carbon::now()->startOfWeek(); // Get the start date of the current week (Monday)
        $endDate = $startDate->copy()->addDays(6); // Get the end date of the current week (Sunday)

        $jadwalHarianSunday = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_harian.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari', 
                        'jadwal_umum.jam_mulai', 'jadwal_umum.id_kelas', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('jadwal_umum.hari', '=', 'Sunday')
                        ->whereBetween('jadwal_harian.tanggal', [$startDate,$endDate])
                        ->orderby('jadwal_umum.jam_mulai', 'asc')
                        ->get();

        if(count($jadwalHarianSunday) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalHarianSunday
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function search($class) {
        $startDate = Carbon::now()->startOfWeek(); // Get the start date of the current week (Monday)

        $jadwalHarian = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_harian.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari','jadwal_umum.jam_mulai', 
                        'jadwal_umum.id_kelas', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('kelas.nama_kelas', 'like', '%'.$class.'%')
                        ->where('jadwal_harian.tanggal', '>=', $startDate)
                        ->get();

        if(count($jadwalHarian) > 0){
            return response([
                'message' => 'Jadwal Harian Found',
                'data' => $jadwalHarian
            ], 200);
        }
        return response([
            'message' => 'There is no such class here',
            'data' => null
        ], 400);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storeData = $request -> all();
        $validate = Validator::make($storeData, [
            'id_jadwal_umum' => 'required',
        ]);
        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $jadwalUmum = DB::table('jadwal_umum')
                        ->where('id_jadwal', '=', $storeData['id_jadwal_umum'])
                        ->get();
        $jadwalHarian = JadwalHarian::create($storeData);
        return response([
            'message' => 'Add Jadwal Harian Success',
            'data' => $jadwalHarian,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $jadwalHarian = JadwalHarian::find($id);
        if (is_null($jadwalHarian)) {
            return response([
                'message' => 'Jadwal Harian Not Found',
                'data' => null
            ], 404);
        }
        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_jadwal_umum' => 'required',
            'id_instruktur' => 'required',
            'status' => 'required',
        ]);

        $jadwalHarian->id_jadwal_umum = $updateData['id_jadwal_umum'];
        $jadwalHarian->status = $updateData['status'];
        $jadwalHarian->save();
        return response([
            'message' => 'Update Jadwal Harian Success',
            'data' => $jadwalHarian,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function generateJadwal(){
        $startDate = Carbon::now()->startOfWeek()->addWeek(); // Get the start date of the current week (Monday)
        $endDate = $startDate->copy()->addDays(6); // Get the end date of the current week (Sunday)

        $jadwalUmumIds = JadwalUmum::pluck('id_jadwal')->all();

        $jadwalHarians = [];
        $currentDate = $startDate->copy();

        while($currentDate <= $endDate) {

            foreach($jadwalUmumIds as $jadwalUmumId) {
                $jadwalUmum = JadwalUmum::find($jadwalUmumId);
                if($jadwalUmum->hari == $currentDate->format('l')) {

                    $jadwalHarian = JadwalHarian::where('id_jadwal_umum', $jadwalUmumId)
                                                ->where('tanggal', $currentDate->format('Y-m-d'))
                                                ->first();
                    if($jadwalHarian) {
                        return response([
                            'message' => 'Already Generated',
                            'data' => null
                        ], 400);
                    } else {
                        $jadwalHarians[] = [
                            'id_jadwal_umum' => $jadwalUmumId,
                            'id_instruktur' => $jadwalUmum->id_instruktur, 
                            'tanggal' => $currentDate->format('Y-m-d'),
                            'kapasitas' => 10,
                            'status' => '',
                        ];
                    }
                }
            }

            $currentDate->addDay();
        }

        JadwalHarian::insert($jadwalHarians);
        return response([
            'message' => 'Next Week Schedule Has Generated Successfully',
            'data' => $jadwalHarians,
        ], 200);
    }
}
