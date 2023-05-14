<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DepositKelas;
use App\Models\Promo;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class DepositKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depositKelas = DB::table('transaksi_deposit_kelas')
                        ->join('member', 'transaksi_deposit_kelas.id_member', '=', 'member.id_member')
                        ->join('pegawai','transaksi_deposit_kelas.id_kasir', '=', 'pegawai.id_pegawai')
                        ->join('promo','transaksi_deposit_kelas.id_promo', '=', 'promo.id')
                        ->join('kelas','transaksi_deposit_kelas.id_kelas', '=', 'kelas.id_kelas')
                        ->select('transaksi_deposit_kelas.*', 'member.nama_member', 'pegawai.nama_pegawai', 'promo.nama_promo', 'kelas.nama_kelas')
                        ->get();

        if(count($depositKelas) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $depositKelas
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
            'id_kasir' => 'required',
            'id_member' => 'required',
            'id_promo' => 'required',
            'deposit_kelas' => 'required',
            'id_kelas' => 'required',
        ]);

        $kelas = DB::table('kelas')
                    ->where('id_kelas', $storeData['id_kelas'])
                    ->first();

        $depositKelas = DB::table('transaksi_deposit_kelas')
                        ->where('id_member', $storeData['id_member'])
                        ->where('status', '1')
                        ->first();
        
        if($depositKelas){
            return response([
                'message' => 'Member already have a class deposit',
                'data' => null
            ], 400);
        }
        $count = DB::table('transaksi_deposit_kelas')->count()+200;
        $generate = sprintf("%d", $count);
        $date = Carbon::now()->format('y.m');
        $storeData['no_struk'] = $date.'.'.$generate;
        $storeData['tanggal_transaksi'] = date('Y-m-d');
        $storeData['status'] = '1';
        if($storeData['id_promo'] == 3){
            if ($storeData['deposit_kelas'] == 5) {

                $storeData['deposit_kelas'] = $storeData['deposit_kelas'] + 1;
                $storeData['deposit'] = $kelas->harga * $storeData['deposit_kelas'];
                $storeData['masa_berlaku'] = date('Y-m-d', strtotime('+1 month'));

            } else if ($storeData['deposit_kelas'] == 10) {

                $storeData['deposit_kelas'] = $storeData['deposit_kelas'] + 3;
                $storeData['deposit'] = $kelas->harga * $storeData['deposit_kelas'];
                $storeData['masa_berlaku'] = date('Y-m-d', strtotime('+2 month'));
            }

            $depositKelas = DepositKelas::create($storeData);
            return response([
                'message' => 'Add Deposit Kelas Success',
                'data' => $depositKelas,
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $depositKelas = DB::table('transaksi_deposit_kelas')
                        ->join('member', 'transaksi_deposit_kelas.id_member', '=', 'member.id_member')
                        ->join('pegawai','transaksi_deposit_kelas.id_kasir', '=', 'pegawai.id_pegawai')
                        ->join('promo','transaksi_deposit_kelas.id_promo', '=', 'promo.id')
                        ->join('kelas','transaksi_deposit_kelas.id_kelas', '=', 'kelas.id_kelas')
                        ->select('transaksi_deposit_kelas.*', 'member.nama_member', 'pegawai.nama_pegawai', 'promo.nama_promo', 'kelas.nama_kelas')
                        ->where('transaksi_deposit_kelas.no_struk', $id)
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
