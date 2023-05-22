<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;
use App\Models\bookingKelas;
use App\Models\JadwalHarian;
use App\Models\JadwalUmum;
use App\Models\Kelas;
use App\Models\DepositKelas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class bookingKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $booking = DB::table('booking_kelas')
            ->join('member', 'booking_kelas.id_member', '=', 'member.id_member')
            ->join('jadwal_harian', 'booking_kelas.id_jadwal', '=', 'jadwal_harian.id')
            ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
            ->join('instruktur', 'jadwal_harian.id_instruktur', '=', 'instruktur.id')
            ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
            ->select('booking_kelas.*', 'member.nama_member', 'member.deposit_member',
             'kelas.nama_kelas', 'kelas.harga', 'instruktur.nama_instruktur', 'jadwal_umum.jam_mulai', 'jadwal_umum.hari')
            ->get();
        
        if(count($booking) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $booking
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function getDatabookingKelas($id){
        $booking = DB::table('booking_kelas')
        ->join('member', 'booking_kelas.id_member', '=', 'member.id_member')
        ->join('jadwal_harian', 'booking_kelas.id_jadwal', '=', 'jadwal_harian.id')
        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
        ->join('instruktur', 'jadwal_harian.id_instruktur', '=', 'instruktur.id')
        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
        ->select('booking_kelas.*', 'member.nama_member', 'member.deposit_member',
         'kelas.nama_kelas', 'kelas.harga', 'instruktur.nama_instruktur', 'jadwal_umum.jam_mulai', 'jadwal_umum.hari')
        ->where('booking_kelas.metode_pembayaran', '=', 0)
        ->where('booking_kelas.id', '=', $id)
        ->get();
        if(count($booking) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $booking
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function getDatabookingKelasPaket($id){
        $booking = DB::table('booking_kelas')
        ->join('member', 'booking_kelas.id_member', '=', 'member.id_member')
        ->join('jadwal_harian', 'booking_kelas.id_jadwal', '=', 'jadwal_harian.id')
        ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
        ->join('instruktur', 'jadwal_harian.id_instruktur', '=', 'instruktur.id')
        ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
        ->join('transaksi_deposit_kelas', 'booking_kelas.id_member', '=', 'transaksi_deposit_kelas.id_member')
        ->select('booking_kelas.*', 'member.nama_member', 'member.deposit_member',
         'kelas.nama_kelas', 'kelas.harga', 'instruktur.nama_instruktur', 'transaksi_deposit_kelas.deposit_kelas', 'transaksi_deposit_kelas.masa_berlaku')
        ->where('booking_kelas.metode_pembayaran', '=', 1)
        ->where('booking_kelas.id', '=', $id)
        ->get();
        if(count($booking) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $booking
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
            'id_member' => 'required',
            'id_jadwal' => 'required',
            'metode_pembayaran' => 'required',
        ]);

        // $checkjadwal = DB::table('jadwal_harian')
        //     ->where('id', '=', $storeData['id_jadwal'])
        //     ->get();

        // if(count($checkjadwal) == 0){
        //     return response([
        //         'message' => 'There is no class in the schedule on that day',
        //         'data' => null
        //     ], 400);
        // }
        $checkBooking = DB::table('booking_kelas')
            ->where('id_jadwal', '=', $storeData['id_jadwal'])
            ->where('id_member', '=', $storeData['id_member'])
            ->where('status_booking', '=', 'BOOKING')
            ->get();

        if(count($checkBooking) > 0){
            return response([
                'message' => 'You have booked this class',
                'data' => null
            ], 400);
        }
        
        $jadwalharian = JadwalHarian::find($storeData['id_jadwal']);
        if(is_null($jadwalharian)){
            return response([
                'message' => 'Class Not Found',
                'data' => null
            ], 404);
        }
        if($jadwalharian->kapasitas == 0){
            return response([
                'message' => 'Class is full',
                'data' => null
            ], 400);
        }
        if($jadwalharian->tanggal < date('Y-m-d')){
            return response([
                'message' => 'You cannot book a class that has passed',
                'data' => $jadwalharian
            ], 400);
        }
        $jadwalUmum = JadwalUmum::find($jadwalharian->id_jadwal_umum);
        $kelas = Kelas::find($jadwalUmum->id_kelas);
        $checkMember = Member::find($storeData['id_member']);

        // $query = "SELECT * FROM transaksi_deposit_kelas WHERE id_member = '".$storeData['id_member']."' AND id_kelas = ".$kelas->id_kelas."";
        // $checkdepositKelas = DB::select(DB::raw($query));
        // var_dump($checkdepositKelas);
        $depositKelas = DepositKelas::where('id_member', '=', $storeData['id_member'])
            ->where('id_kelas', '=', $kelas->id_kelas)
            ->first();
        // var_dump($depositKelas);


        // 0 = regular, 1 = class bundle

        if($storeData['metode_pembayaran'] == 0) {
            if($checkMember->deposit_member == 0 || $checkMember->deposit_member < $kelas->harga ){
                return response([
                    'message' => 'Your deposit is not enough',
                    'data' => null
                ], 400);
            }
            // $checkMember->deposit_member = $checkMember->deposit_member - $kelas->harga;
        } else {
            if(is_null($depositKelas)){
                return response([
                    'message' => 'You have not purchased this class bundle',
                    'data' => null
                ], 400);
            } else {
                if($depositKelas->masa_berlaku < date('Y-m-d') || $depositKelas->deposit_kelas == 0 || $checkMember->deposit_kelas == 0){
                    return response([
                        'message' => 'Your class bundle has expired',
                        'data' => null
                    ], 400);
                }
                // $depositKelas->deposit_kelas = $depositKelas->deposit_kelas - 1;
                // $checkMember->deposit_kelas = $checkMember->deposit_kelas - $kelas->harga;        
                // $depositKelas->save();
            }
        }
        

        $jadwalharian->kapasitas = $jadwalharian->kapasitas - 1;
        $jadwalharian->save();
        // $checkMember->save();

        $count = DB::table('booking_kelas')->count()+400;
        $generate = sprintf("%d", $count);
        $date = Carbon::now()->format('y.m');
        $storeData['id'] = $date.'.'.$generate;
        $storeData['tanggal_pembuatan_booking'] = date('Y-m-d');
        $storeData['tanggal_booking'] = $jadwalharian->tanggal;
        $storeData['status_booking'] = 'BOOKING';
        $storeData['status_presensi'] = 0;
        $storeData['tanggal_presensi'] = null;
        $booking = bookingKelas::create($storeData);
        return response([
            'message' => 'Booking Successfully',
            'data' => $booking,
        ], 200);
    }

    public function presensi($id){
        $bookingKelas = bookingKelas::find($id);

        $checkMember = Member::find($bookingKelas->id_member);
        $jadwalHarian = JadwalHarian::find($bookingKelas->id_jadwal);
        $jadwalUmum = JadwalUmum::find($jadwalHarian->id_jadwal_umum);
        $kelas = Kelas::find($jadwalUmum->id_kelas);

        $jadwalHarian->kapasitas = $jadwalHarian->kapasitas + 1;
        $jadwalHarian->save();
       
        if($bookingKelas->metode_pembayaran == 0){
            $checkMember->deposit_member = $checkMember->deposit_member - $kelas->harga;
            $checkMember->save();
        } else {
            $depositKelas = DepositKelas::where('id_member', '=', $bookingKelas->id_member)
                ->where('id_kelas', '=', $kelas->id_kelas)
                ->first();
            $depositKelas->deposit_kelas = $depositKelas->deposit_kelas - 1;
            $checkMember->deposit_kelas = $checkMember->deposit_kelas - $kelas->harga;        
            $depositKelas->save();
        }

        $bookingKelas->status_booking = 'COMPLETE';
        $bookingKelas->status_presensi = 1;
        $bookingKelas->tanggal_presensi = date('Y-m-d H:i:s');
        $bookingKelas->save();
        return response([
            'message' => 'Your Attandance Has Been Recorded',
            'data' => $bookingKelas,
        ], 200);
        

    }

    public function memberListClass($id){
        $bookingKelas = DB::table('booking_kelas')
            ->join('member', 'booking_kelas.id_member', '=', 'member.id_member')
            ->join('jadwal_harian', 'booking_kelas.id_jadwal', '=', 'jadwal_harian.id')
            ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
            ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
            ->select('booking_kelas.*', 'member.nama_member', 'kelas.nama_kelas', 'jadwal_umum.jam_mulai', 'jadwal_umum.hari')
            ->where('booking_kelas.id_jadwal', '=', $id)
            ->get();
        
            return response([
                'message' => 'Retrieve All Success',
                'data' => $bookingKelas
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
        $bookingkelas = DB::table('booking_kelas')
            ->join('member', 'booking_kelas.id_member', '=', 'member.id_member')
            ->join('jadwal_harian', 'booking_kelas.id_jadwal', '=', 'jadwal_harian.id')
            ->join('jadwal_umum', 'jadwal_harian.id_jadwal_umum', '=', 'jadwal_umum.id_jadwal')
            ->join('kelas', 'jadwal_umum.id_kelas', '=', 'kelas.id_kelas')
            ->select('booking_kelas.*', 'member.nama_member', 'kelas.nama_kelas', 'jadwal_umum.jam_mulai', 'jadwal_umum.hari')
            ->where('booking_kelas.id_member', '=', $id)
            ->get();

        if(count($bookingkelas) == 0){
            return response([
                'message' => 'Member has not booked any class',
                'data' => null
            ], 404);
        }
        return response([
            'message' => 'Retrieve Booking Success',
            'data' => $bookingkelas
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
