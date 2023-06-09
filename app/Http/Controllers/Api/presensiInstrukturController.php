<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\presensiInstruktur;
use App\Models\HadwalHarian;
use App\Models\JadwalUmum;
use App\Models\Instruktur;
use App\Models\Kelas;
use Carbon\Carbon;

class presensiInstrukturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $presensi = DB::table('presensi_instruktur')
        ->join('jadwal_harian', 'jadwal_harian.id', '=', 'presensi_instruktur.id_jadwal')
        ->join('instruktur', 'instruktur.id', '=', 'presensi_instruktur.id_instruktur')
        ->join('jadwal_umum', 'jadwal_umum.id_jadwal', '=', 'jadwal_harian.id_jadwal_umum')
        ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umum.id_kelas')
        ->select('presensi_instruktur.*', 'instruktur.nama_instruktur as nama_instruktur', 'kelas.nama_kelas as nama_kelas')
        ->get();

        return response()->json([
            'message' => 'Retrieve All Success',
            'data' => $presensi
        ]);
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
        $presensiData = $request->all();

        $validate = Validator::make($presensiData,[
            'id_instruktur' => 'required',
            'id_jadwal' => 'required',
            'tanggal_presensi' => 'required',
            'jam_datang' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $jadwalHarian = JadwalHarian::find($presensiData['id_jadwal']);
        $jadwal = JadwalUmum::find($jadwalHarian->id_jadwal_umum);
        
        $presensiData['jam_datang'] = date("H:i:s");

        if($presensiData['jam_datang'] > $jadwal->jam_mulai){
            //Terlambat
            $presensiData['status'] = "1";
            $startTime = Carbon::parse($jadwal->jam_mulai);
            $endTime = Carbon::parse($presensiData['jam_datang']);

            $timeDifference = $endTime->diffInSeconds($startTime);
            $presensiData['waktu_terlambat'] = $timeDifference;

        }else{
            $presensiData['status'] = "0";
        }
        
        $presensi = presensiInstruktur::create($presensiData);

        return response([
            'message' => 'Your attendance has been recorded',
            'data' => $presensi,
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $presensi = DB::table('presensi_instruktur')
        ->join('jadwal_harian', 'jadwal_harian.id', '=', 'presensi_instruktur.id_jadwal')
        ->join('instruktur', 'instruktur.id', '=', 'presensi_instruktur.id_instruktur')
        ->join('jadwal_umum', 'jadwal_umum.id_jadwal', '=', 'jadwal_harian.id_jadwal_umum')
        ->join('kelas', 'kelas.id_kelas', '=', 'jadwal_umum.id_kelas')
        ->select('presensi_instruktur.*', 'instruktur.nama_instruktur as nama_instruktur', 'kelas.nama_kelas as nama_kelas')
        ->where('presensi_instruktur.id_instruktur', '=', $id)
        ->get();
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
        //
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
