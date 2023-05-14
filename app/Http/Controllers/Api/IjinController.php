<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\IjinInstruktur;

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
            ->select('ijin_instruktur.*', 'instruktur.nama_instruktur')
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
            ->select('ijin_instruktur.*', 'instruktur.nama_instruktur')
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

    public function confirmIjin($id) {
        $ijin = IjinInstruktur::find($id);
        $ijin->status = '1';
        $ijin->save();

        return response([
            'message' => 'Confirm Ijin Success',
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
