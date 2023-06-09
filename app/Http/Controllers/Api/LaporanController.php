<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\bookingGym;

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

    public function laporanGymThisMonth()
    {
        $bulan = Carbon::now()->month;

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
}
