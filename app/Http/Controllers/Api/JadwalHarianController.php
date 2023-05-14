<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\JadwalHarian;
use Illuminate\Support\Facades\DB;
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
                        ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari', 'jadwal_umum.jam_mulai', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
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
        $jadwalHarianMonday = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari', 
                        'jadwal_umum.jam_mulai', 'jadwal_umum.id_kelas', 'jadwal_umum.id_instruktur', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('jadwal_umum.hari', '=', 'Monday')
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
        $jadwalHarianTuesday = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari', 
                        'jadwal_umum.jam_mulai', 'jadwal_umum.id_kelas', 'jadwal_umum.id_instruktur', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('jadwal_umum.hari', '=', 'Tuesday')
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
        $jadwalHarianWednesday = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari', 
                        'jadwal_umum.jam_mulai', 'jadwal_umum.id_kelas', 'jadwal_umum.id_instruktur', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('jadwal_umum.hari', '=', 'Wednesday')
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
        $jadwalHarianThursday = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari', 
                        'jadwal_umum.jam_mulai', 'jadwal_umum.id_kelas', 'jadwal_umum.id_instruktur', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('jadwal_umum.hari', '=', 'Thursday')
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
        $jadwalHarianFriday = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari',
                        'jadwal_umum.jam_mulai', 'jadwal_umum.id_kelas', 'jadwal_umum.id_instruktur', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('jadwal_umum.hari', '=', 'Friday')
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
        $jadwalHarianSaturday = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari',
                        'jadwal_umum.jam_mulai', 'jadwal_umum.id_kelas', 'jadwal_umum.id_instruktur', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('jadwal_umum.hari', '=', 'Saturday')
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
        $jadwalHarianSunday = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari', 'jadwal_umum.jam_mulai', 'jadwal_umum.id_kelas', 'jadwal_umum.id_instruktur', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('jadwal_umum.hari', '=', 'Sunday')
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
        $jadwalHarian = DB::table('jadwal_harian')
                        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
                        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
                        ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
                        ->select('jadwal_harian.*','jadwal_harian.id_jadwal_umum', 'jadwal_umum.sesi_jadwal', 'jadwal_umum.hari','jadwal_umum.jam_mulai', 
                        'jadwal_umum.id_kelas', 'jadwal_umum.id_instruktur', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
                        ->where('kelas.nama_kelas', 'like', '%'.$class.'%')
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
}
