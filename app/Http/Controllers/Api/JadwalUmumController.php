<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\JadwalUmum;
use Illuminate\Support\Facades\DB;
class JadwalUmumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jadwalUmum = JadwalUmum::with(['Kelas', 'Instruktur'])->get();
        if(count($jadwalUmum) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalUmum
            ], 200);
        }
        return response([
            'message' => 'Empty',
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

        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_kelas' => 'required|numeric',
            'id_instruktur' => 'required|numeric',
            'hari' => 'required',
            'jam_mulai' => 'required|date_format:H:i:s',
            'sesi_jadwal' => 'required|boolean',
        ]);
        if($validate->fails()) {
            return response(['message' => $validate->errors()],400);
        }
        $checkData = DB::SELECT("SELECT * FROM jadwal_umum WHERE id_instruktur = $storeData[id_instruktur] AND jam_mulai = '$storeData[jam_mulai]'" );
        
        if($checkData){
            return response([
                'message' => 'Schedule Already Exist',
                'data' => $checkData
            ], 400);
        }
        $jadwalUmum = JadwalUmum::create($storeData);
        return response([
            'message' => 'Add Jadwal Umum Success',
            'data' => $jadwalUmum
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
        $jadwalUmum = JadwalUmum::with(['Kelas', 'Instruktur'])->find($id);
        if($jadwalUmum){
            return response([
                'message' => 'Retrieve Jadwal Umum Success',
                'data' => $jadwalUmum
            ], 200);
        }
        return response([
            'message' => 'Jadwal Umum Not Found',
            'data' => null
        ], 404);
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
        $jadwalUmum = JadwalUmum::find($id);
        if(is_null($jadwalUmum)){
            return response([
                'message' => 'Jadwal Umum Not Found',
                'data' => null
            ], 404);
        }
        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_kelas' => 'required',
            'id_instruktur' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required|date_format:H:i:s',
            'sesi_jadwal' => 'required|boolean',
        ]);
        if($validate->fails()){
            return response(['message' => $validate->errors()],400);
        }
        $checkData = DB::SELECT("SELECT * FROM jadwal_umum WHERE id_instruktur = $storeData[id_instruktur] AND jam_mulai = '$storeData[jam_mulai]'" );
        
        if($checkData){
            return response([
                'message' => 'Schedule Already Exist',
                'data' => $checkData
            ], 400);
        } else {
            $jadwalUmum->id_kelas = $updateData['id_kelas'];
            $jadwalUmum->id_instruktur = $updateData['id_instruktur'];
            $jadwalUmum->hari = $updateData['hari'];
            $jadwalUmum->jam_mulai = $updateData['jam_mulai'];
            $jadwalUmum->sesi_jadwal = $updateData['sesi_jadwal'];
            
            if($jadwalUmum->save()){
                return response([
                    'message' => 'Update Jadwal Umum Success',
                    'data' => $jadwalUmum
                ], 200);
            }
            return response([
                'message' => 'Update Jadwal Umum Failed',
                'data' => null
            ], 400);
        }
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jadwalUmum = JadwalUmum::find($id);

        if(is_null($jadwalUmum)){
            return response([
                'message' => 'Jadwal Umum Not Found',
                'data' => null
            ], 404);
        }

        if($jadwalUmum->delete()){
            return response([
                'message' => 'Delete Jadwal Umum Success',
                'data' => $jadwalUmum,
            ], 200);
        }

        return response([
            'message' => 'Delete Jadwal Umum Failed',
            'data' => null,
        ], 400);
    }

    public function showMonday() {
        // $jadwalUmum = JadwalUmum::with(['Kelas', 'Instruktur'])->where('hari', 'Monday')->get();
        $jadwalUmum = DB::table('jadwal_umum')
            ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
            ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
            ->select('jadwal_umum.*', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
            ->where('jadwal_umum.hari', '=', 'Monday')
            ->orderby('jadwal_umum.jam_mulai', 'asc')
            ->get();
        if(count($jadwalUmum) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalUmum
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function showTuesday() {
        // $jadwalUmum = JadwalUmum::with(['Kelas', 'Instruktur'])->where('hari', 'Tuesday')->get();
        $jadwalUmum = DB::table('jadwal_umum')
            ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
            ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
            ->select('jadwal_umum.*', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
            ->where('jadwal_umum.hari', '=', 'Tuesday')
            ->orderby('jadwal_umum.jam_mulai', 'asc')
            ->get();
        
        if(count($jadwalUmum) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalUmum
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }


    public function showWednesday() {
        // $jadwalUmum = JadwalUmum::with(['Kelas', 'Instruktur'])->where('hari', 'Wednesday')->get();
        $jadwalUmum = DB::table('jadwal_umum')
            ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
            ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
            ->select('jadwal_umum.*', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
            ->where('jadwal_umum.hari', '=', 'Wednesday')
            ->orderby('jadwal_umum.jam_mulai', 'asc')
            ->get();
        if(count($jadwalUmum) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalUmum
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function showThursday() {
        // $jadwalUmum = JadwalUmum::with(['Kelas', 'Instruktur'])->where('hari', 'Thursday')->get();
        $jadwalUmum = DB::table('jadwal_umum')
            ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
            ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
            ->select('jadwal_umum.*', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
            ->where('jadwal_umum.hari', '=', 'Thursday')
            ->orderby('jadwal_umum.jam_mulai', 'asc')
            ->get();
        if(count($jadwalUmum) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalUmum
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function showFriday() {
        // $jadwalUmum = JadwalUmum::with(['Kelas', 'Instruktur'])->where('hari', 'Friday')->get();
        $jadwalUmum = DB::table('jadwal_umum')
            ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
            ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
            ->select('jadwal_umum.*', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
            ->where('jadwal_umum.hari', '=', 'Friday')
            ->orderby('jadwal_umum.jam_mulai', 'asc')
            ->get();
        if(count($jadwalUmum) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalUmum
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function showSaturday() {
        // $jadwalUmum = JadwalUmum::with(['Kelas', 'Instruktur'])->where('hari', 'Saturday')->get();
        $jadwalUmum = DB::table('jadwal_umum')
            ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
            ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
            ->select('jadwal_umum.*', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
            ->where('jadwal_umum.hari', '=', 'Saturday')
            ->orderby('jadwal_umum.jam_mulai', 'asc')
            ->get();
        if(count($jadwalUmum) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalUmum
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function showSunday() {
        // $jadwalUmum = JadwalUmum::with(['Kelas', 'Instruktur'])->where('hari', 'Sunday')->get();
        $jadwalUmum = DB::table('jadwal_umum')
            ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
            ->join('instruktur', 'jadwal_umum.id_instruktur', '=', 'instruktur.id')
            ->select('jadwal_umum.*', 'kelas.nama_kelas', 'instruktur.nama_instruktur')
            ->where('jadwal_umum.hari', '=', 'Sunday')
            ->orderby('jadwal_umum.jam_mulai', 'asc')
            ->get();
        if(count($jadwalUmum) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalUmum
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

}
