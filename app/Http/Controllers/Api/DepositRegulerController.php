<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DepositReguler;
use App\Models\Promo;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DepositRegulerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depositReguler = DB::table('transaksi_deposit_reguler')
            ->join('member', 'transaksi_deposit_reguler.id_member', '=', 'member.id_member')
            ->join('pegawai', 'transaksi_deposit_reguler.id_kasir', '=', 'pegawai.id_pegawai')
            ->join('promo', 'transaksi_deposit_reguler.id_promo', '=', 'promo.id')
            ->select('transaksi_deposit_reguler.*', 'member.nama_member', 'member.deposit_member','pegawai.nama_pegawai', 'promo.nama_promo' )
            ->get();

        if(count($depositReguler) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $depositReguler
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
        $storeData = $request -> all();
        $validate = Validator::make($storeData, [
            'id_kasir' => 'required',
            'id_member' => 'required',
            'id_promo' => 'required',
            'deposit' => 'required',
        ]);
        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $member = Member::where('id_member', $storeData['id_member'])->first();
        $count = DB::table('transaksi_deposit_reguler')->count()+300;
        $generate = sprintf("%d", $count);
        $date = Carbon::now()->format('y.m');
        $storeData['no_struk'] = $date.'.'.$generate;
        $storeData['tanggal_transaksi'] = date('Y-m-d H:i:s');
        $storeData['sisa_deposit'] = $member->deposit_member;
        $storeData['bonus_deposit'] = 0;
        if($storeData['id_promo'] == 2) {
            if($storeData['deposit'] < 500000) {
                return response([
                    'message' => 'Minimal Deposit 500.000',
                ], 400);
            } else {
                if($storeData['deposit'] >= 3000000) {
                    $storeData['bonus_deposit'] = 300000;
                }
            }
        }

        $storeData['total_deposit'] = $storeData['deposit'] + $storeData['bonus_deposit'] + $storeData['sisa_deposit'];
        $member->deposit_member = $storeData['total_deposit'];
        $member->save();
        $depositReguler = DepositReguler::create($storeData);
        return response([
            'message' => 'Add Deposit Reguler Success',
            'data' => $depositReguler,
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
        $depositReguler = DepositReguler::with(['Member', 'Pegawai', 'Promo'])->where('no_struk', $id)->first();
        if (!is_null($depositReguler)) {
            return response([
                'message' => 'Retrieve Deposit Reguler Success',
                'data' => $depositReguler
            ], 200);
        }
        return response([
            'message' => 'Deposit Reguler Not Found',
            'data' => null
        ], 404);
    }

    public function showDepositRegulerMember($id)
    {
        $depositReguler = DB::table('transaksi_deposit_reguler')
            ->join('member', 'transaksi_deposit_reguler.id_member', '=', 'member.id_member')
            ->join('pegawai', 'transaksi_deposit_reguler.id_kasir', '=', 'pegawai.id_pegawai')
            ->join('promo', 'transaksi_deposit_reguler.id_promo', '=', 'promo.id')
            ->select('transaksi_deposit_reguler.*', 'member.nama_member', 'member.deposit_member','pegawai.nama_pegawai', 'promo.nama_promo' )
            ->where('transaksi_deposit_reguler.id_member', '=', $id)
            ->get();

        if ($depositReguler) {
            return response([
                'message' => 'Retrieve Deposit Reguler Success',
                'data' => $depositReguler
            ], 200);
        }
        // return response([
        //     'message' => 'You have not made a regular deposit yet',
        //     'data' => null
        // ], 404);
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
