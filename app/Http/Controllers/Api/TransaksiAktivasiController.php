<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TransaksiAktivasi;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiAktivasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaksiAktivasi = DB::table('transaksi_aktivasi')
            ->join('member', 'transaksi_aktivasi.id_member', '=', 'member.id_member')
            ->join('pegawai', 'transaksi_aktivasi.id_kasir', '=', 'pegawai.id_pegawai')
            ->select('transaksi_aktivasi.*', 'member.nama_member', 'pegawai.nama_pegawai', 'member.masa_berlaku', 'pegawai.id_pegawai', 
            'member.id_member')
            ->get();

        if(count($transaksiAktivasi) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksiAktivasi
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
            'id_kasir' => 'required',
            'id_member' => 'required',
        ]);

        if($validate->fails()) {
            return response(['message' => $validate->errors()],400);
        }
        $count = DB::table('transaksi_aktivasi')->count()+200;
        $generate = sprintf("%d", $count);
        $date = Carbon::now()->format('y.m');
        $storeData['no_struk_akt'] = $date.'.'.$generate;
        $storeData['tanggal_transaksi'] = date('Y-m-d H:i:s');
        $storeData['biaya_transaksi'] = 3000000;
        $member =  Member::where('id_member', $storeData['id_member'])->first();
        
        if($member->status == 1){
            return response([
                'message' => 'Member has been activated',
                'data' => $member
            ], 400);
        } else {
            $member->update([
                'status' => 1,
                'masa_berlaku' => date('Y-m-d', strtotime('+1 year -1 day')),
            ]);
            $member->save();
            $transaksiAktivasi = TransaksiAktivasi::create($storeData);
            return response([
                'message' => 'Add Transaksi Aktivasi Success',
                'data' => $transaksiAktivasi
            ],200);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function showActivationMember($id){
        $aktivasi = DB::table('transaksi_aktivasi')
                    ->select('transaksi_aktivasi.*')
                    ->where('id_member', $id)
                    ->get();
        
        if($aktivasi){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $aktivasi
            ], 200);
        }
        // return response([
        //     'message' => 'Empty',
        //     'data' => null
        // ], 400);
    }

    public function show($id)
    {
        $transaksiAktivasi = TransaksiAktivasi::with(['Pegawai', 'Member'])->where('no_struk_akt', $id)->first();
        if(!is_null($transaksiAktivasi)){
            return response([
                'message' => 'Retrieve Transaksi Aktivasi Success',
                'data' => $transaksiAktivasi
            ], 200);
        }
        return response([
            'message' => 'Data not Found',
            'data' => null
        ], 400);
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
