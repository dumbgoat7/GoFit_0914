<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\IjinInstruktur;
use App\Models\JadwalHarian;
use App\Models\Instruktur;
class IjinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ijin = DB::table('ijin_instruktur')
            ->join('instruktur', 'ijin_instruktur.id_instruktur', '=', 'instruktur.id')
            ->join('instruktur as instruktur_pengganti', 'ijin_instruktur.id_instruktur_pengganti', '=', 'instruktur_pengganti.id')
            ->select('ijin_instruktur.*', 'instruktur.nama_instruktur', 'instruktur_pengganti.nama_instruktur as nama_instruktur_pengganti')
            ->get();
        
        if(count($ijin) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $ijin
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
        $storeData = request()->all();

        $validate = Validator::make($storeData, [
            'id_instruktur' => 'required',
            'id_mo' => 'required',
            'id_instruktur_pengganti' => 'required',
            'tanggal_ijin' => 'required',
            'keterangan' => 'required',
        ]);

        $storeData['tanggal_pembuatan_ijin'] = date('Y-m-d');
        $storeData['status'] = '0';
        $ijin = IjinInstruktur::create($storeData);
        return response([
            'message' => 'Add Ijin Success',
            'data' => $ijin,
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
        $ijin = DB::table('ijin_instruktur')
            ->join('instruktur', 'ijin_instruktur.id_instruktur', '=', 'instruktur.id')
            ->join('instruktur as instruktur_pengganti', 'ijin_instruktur.id_instruktur_pengganti', '=', 'instruktur_pengganti.id')
            ->select('ijin_instruktur.*', 'instruktur.nama_instruktur', 'instruktur_pengganti.nama_instruktur as nama_instruktur_pengganti')
            ->where('ijin_instruktur.id', '=', $id)
            ->get();
        
        if(count($ijin) > 0){
            return response([
                'message' => 'Retrieve Data Success',
                'data' => $ijin
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);   
    }


    public function countIjin($id) {
        // $ijin = DB::table('ijin_instruktur')
        //     ->join('instruktur', 'ijin_instruktur.id_instruktur', '=', 'instruktur.id')
        //     ->select('ijin_instruktur.*', 'instruktur.nama_instruktur')
        //     ->where('ijin_instruktur.id_instruktur', '=', $id)
        //     ->count();

        // $instruktur = DB::table('instruktur')
        //     ->select('instruktur.*')
        //     ->where('instruktur.id', '=', $id)
        //     ->get();
        
        // $result = [
        //     'countIjin' => $ijin,
        //     'instruktur' => $instruktur
        // ];
        $ijin = DB::table('ijin_instruktur')
            ->join('instruktur', 'ijin_instruktur.id_instruktur', '=', 'instruktur.id')
            ->select('instruktur.nama_instruktur', DB::raw('count(ijin_instruktur.id) as countIjin'))
            ->where('ijin_instruktur.id_instruktur', '=', $id)
            ->groupBy('instruktur.nama_instruktur')
            ->get();

        return response([
            'message' => 'Retrieve Data Success',
            'data' => $ijin
        ], 200);
    }

    public function isConfirmed($id) {
        $ijin = IjinInstruktur::find($id);
        if($ijin->status == '1') {
            return response([
                'message' => 'Absence Request Already Confirmed',
                'data' => $ijin,
            ], 400);
        }
        $ijin->status = '1';
        // $jadwalHarian = DB::table('jadwal_harian')
        //     ->join('instruktur', 'jadwal_harian.id_instruktur', '=', 'instruktur.id')
        //     ->select('jadwal_harian.*', 'instruktur.nama_instruktur')
        //     ->where('jadwal_harian.id_instruktur', '=', $ijin->id_instruktur)
        //     ->where('jadwal_harian.tanggal', '=', $ijin->tanggal_ijin)
        //     ->get();
        $query = "Select * FROM jadwal_harian WHERE id_instruktur = $ijin->id_instruktur AND tanggal = '$ijin->tanggal_ijin'";
        $jadwalHarian = DB::select(DB::raw($query));

        if(count($jadwalHarian) == 0) {
            return response([
                'message' => 'You have no schedule on that day',
                'data' => null
            ], 400);
        }
        $idValue = $jadwalHarian[0]->id;
        $updateJadwal = jadwalHarian::find($idValue);
        $instruktur = Instruktur::find($updateJadwal->id_instruktur);
        $updateJadwal->status = "Menggantikan " . $instruktur->nama_instruktur;
        $updateJadwal->id_instruktur = $ijin->id_instruktur_pengganti;
        
        $ijin->save();
        $updateJadwal->save();
        return response([
            'message' => 'Absence Request Confirmed',
            'data' => $ijin,
        ], 200);

        
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
