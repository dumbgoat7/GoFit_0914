<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\JadwalUmum;

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
            'tanggal_jadwal' => 'required|date_format:Y-m-d',
            'jam_mulai' => 'required|date_format:H:i',
            'sesi' => 'required|numeric',
        ]);
        if($validate->fails()) {
            return response(['message' => $validate->errors()],400);
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
            'tanggal_jadwal' => 'required|date_format:Y-m-d',
            'jam_mulai' => 'required|date_format:H:i',
            'sesi' => 'required|boolean',
        ]);
        if($validate->fails()){
            return response(['message' => $validate->errors()],400);
        }
        $jadwalUmum->id_kelas = $updateData['id_kelas'];
        $jadwalUmum->id_instruktur = $updateData['id_instruktur'];
        $jadwalUmum->tanggal_jadwal = $updateData['tanggal_jadwal'];
        $jadwalUmum->jam_mulai = $updateData['jam_mulai'];
        $jadwalUmum->sesi = $updateData['sesi'];
        
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
}
