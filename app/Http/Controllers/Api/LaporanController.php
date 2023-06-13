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
            WHERE MONTH(jh.tanggal) = ? 
            AND bk.status_presensi = "1"
            GROUP BY k.nama_kelas, i.nama_instruktur
        ', [$bulan]);

        //akumulasi terlambat direset tiap bulan jam mulai tiap bulan - jam selesai bulan         
        return response([
            'data' => $aktivitasKelas,
            'tanggal_cetak' => $tanggalCetak,
        ]);
        
    }

    public function laporanPendapatan($year){
        $tahun = Carbon::now()->year;
        if (!empty($year)) {
            $tahun = $year;
        }
        $tanggalCetak = Carbon::now();
        $pendapatanPerTahun = DB::select("
        SELECT
            bulan.nama_bulan,
            COALESCE(SUM(jumlah_bayar), 0) AS total_pendapatan_aktivasi,
            COALESCE(SUM(pendapatan_reguler + pendapatan_paket), 0) AS total_pendapatan_deposit,
            COALESCE(SUM(jumlah_bayar + pendapatan_reguler + pendapatan_paket), 0) AS total_pendapatan
        FROM (
            SELECT 1 AS bulan_id, 'January' AS nama_bulan UNION ALL
            SELECT 2 AS bulan_id, 'February' AS nama_bulan UNION ALL
            SELECT 3 AS bulan_id, 'March' AS nama_bulan UNION ALL
            SELECT 4 AS bulan_id, 'April' AS nama_bulan UNION ALL
            SELECT 5 AS bulan_id, 'May' AS nama_bulan UNION ALL
            SELECT 6 AS bulan_id, 'June' AS nama_bulan UNION ALL
            SELECT 7 AS bulan_id, 'July' AS nama_bulan UNION ALL
            SELECT 8 AS bulan_id, 'August' AS nama_bulan UNION ALL
            SELECT 9 AS bulan_id, 'September' AS nama_bulan UNION ALL
            SELECT 10 AS bulan_id, 'October' AS nama_bulan UNION ALL
            SELECT 11 AS bulan_id, 'November' AS nama_bulan UNION ALL
            SELECT 12 AS bulan_id, 'December' AS nama_bulan
        ) AS bulan
        LEFT JOIN (
            SELECT
                MONTH(am.tanggal_transaksi) AS bulan_id,
                am.biaya_transaksi AS jumlah_bayar,
                0 AS pendapatan_reguler,
                0 AS pendapatan_paket
            FROM transaksi_aktivasi AS am
            WHERE YEAR(am.tanggal_transaksi) = $year
            UNION ALL
            SELECT
                MONTH(du.tanggal_transaksi) AS bulan_id,
                0 AS jumlah_bayar,
                du.deposit AS pendapatan_reguler,
                0 AS pendapatan_paket
            FROM transaksi_deposit_reguler AS du
            WHERE YEAR(du.tanggal_transaksi) = $year
            UNION ALL
            SELECT
            MONTH(dk.tanggal_transaksi) AS bulan_id,
            0 AS jumlah_bayar,
            0 AS pendapatan_reguler,
            dk.deposit AS pendapatan_paket
            FROM transaksi_deposit_kelas AS dk
            WHERE YEAR(dk.tanggal_transaksi) = $year
        ) AS transaksi ON bulan.bulan_id = transaksi.bulan_id
        GROUP BY bulan.bulan_id, bulan.nama_bulan
        ORDER BY bulan.bulan_id
    ");

        return response([
            'data' => $pendapatanPerTahun,
            'tanggal_cetak' => $tanggalCetak,
        ]);

    }

}
