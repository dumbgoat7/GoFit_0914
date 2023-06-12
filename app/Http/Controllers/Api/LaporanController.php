<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\bookingGym;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function laporanGym($month)
    {
        $bulan = Carbon::now()->month;
        if (!empty($month)) {
            $bulan = $month;
        }
        //* Tanggal Cetak
        $tanggalCetak = Carbon::now();
        $aktivitasGym = bookingGym::where('tanggal_booking', '<', $tanggalCetak)
            ->where('status_presensi','1')
            ->whereMonth('tanggal_booking', $bulan) //* lewat parmas
            ->get()
            ->groupBy(function ($item) {
                //*group by tanggal
                $carbonDate = Carbon::createFromFormat('Y-m-d', $item->tanggal_booking);
                return $carbonDate->format('Y-m-d');
            });
        //* Data yang diambil data booking gym yang udah lewat(tanggal sesi gymnya status kehadiran 1) dan tidak dibatalin

        //* Count 
        $responseData = [];

        foreach ($aktivitasGym as $tanggal => $grup) {
            $count = $grup->count();
            $responseData[] = [
                'tanggal' => $tanggal,
                'count' => $count,
            ];
        }

        return response([
            'data' => $responseData,
            'tanggal_cetak' => $tanggalCetak
        ]);
    }

    // public function laporanGymThisMonth()
    // {
    //     $bulan = Carbon::now()->month;

    //     //* Tanggal Cetak
    //     $tanggalCetak = Carbon::now();
    //     $aktivitasGym = bookingGym::where('tanggal_booking', '<', $tanggalCetak)
    //         ->where('status_presensi','1')
    //         ->whereMonth('tanggal_booking', $bulan) //* lewat parmas
    //         ->get()
    //         ->groupBy(function ($item) {
    //             //*group by tanggal
    //             $carbonDate = Carbon::createFromFormat('Y-m-d', $item->tanggal_booking);
    //             return $carbonDate->format('Y-m-d');
    //         });
    //     //* Data yang diambil data booking gym yang udah lewat(tanggal sesi gymnya status kehadiran 1) dan tidak dibatalin

    //     //* Count 
    //     $responseData = [];

    //     foreach ($aktivitasGym as $tanggal => $grup) {
    //         $count = $grup->count();
    //         $responseData[] = [
    //             'tanggal' => $tanggal,
    //             'count' => $count,
    //         ];
    //     }

    //     return response([
    //         'data' => $responseData,
    //         'tanggal_cetak' => $tanggalCetak
    //     ]);
    // }

    public function laporanKelas($month)
    {
        $bulan = Carbon::now()->month;
        if (!empty($month)) {
            $bulan = $month;
        }
        //* Tanggal Cetak
        $tanggalCetak = Carbon::now();
        $aktivitasKelas = DB::select('
            SELECT k.nama_kelas AS kelas, i.nama_instruktur AS instruktur, COUNT(bk.id) AS jumlah_peserta, 
                COUNT(CASE WHEN jh.status = "libur" THEN 1 ELSE NULL END) AS jumlah_libur
            FROM booking_kelas AS bk
            JOIN jadwal_harian AS jh ON bk.id_jadwal = jh.id
            JOIN jadwal_umum AS ju ON jh.id_jadwal_umum = ju.id_jadwal
            JOIN instruktur AS i ON jh.id_instruktur = i.id
            JOIN kelas AS k ON ju.id_kelas = k.id_kelas
            WHERE MONTH(jh.tanggal) = ? AND bk.status_presensi = "1"
            GROUP BY k.nama_kelas, i.nama_instruktur
        ', [$bulan]);

        //akumulasi terlambat direset tiap bulan jam mulai tiap bulan - jam selesai bulan         
        return response([
            'data' => $aktivitasKelas,
            'tanggal_cetak' => $tanggalCetak,
        ]);
        
    }

}
