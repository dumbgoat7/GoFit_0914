<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BookingGym;
use App\Models\Member;
use App\Models\detailsBooking;

class bookingGymController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookingGym = DB::table('booking_gym')
            ->join('member', 'booking_gym.id_member', '=', 'member.id_member')
            ->join('details_booking_gym', 'booking_gym.id_details_booking', '=', 'details_booking_gym.id')
            ->select('booking_gym.*', 'member.nama_member', 'details_booking_gym.slot_waktu')
            ->get();
        
        if(count($bookingGym) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $bookingGym
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
            'id_details_booking' => 'required',
            'tanggal_booking' => 'required',
        ]);
        if($storeData['tanggal_booking'] < date('Y-m-d')){
            return response([
                'message' => 'Invalid date input',
                'data' => null
            ], 400);
        }

        $member = Member::find($storeData['id_member']);
        if($member->status == 0 ) {
            return response([
                'message' => 'Member is not active',
                'data' => null
            ], 400);
        }
        $details = detailsBooking::find($storeData['id_details_booking']);
        if($details->sisa_kapasitas == 0){
            return response([
                'message' => 'Session is full',
                'data' => null
            ], 400);
        }
        $details->sisa_kapasitas = $details->sisa_kapasitas - 1;
        $details->save();

        $count = DB::table('booking_gym')->count()+500;
        $generate = sprintf("%d", $count);
        $date = Carbon::now()->format('y.m');
        $storeData['id'] = $date.'.'.$generate;
        $storeData['tanggal_pembuatan_booking'] = date('Y-m-d');
        $storeData['status_booking'] = 'BOOKING';
        $storeData['status_presensi'] = 0;
        $storeData['tanggal_presensi'] = null;

        $bookingGym = BookingGym::create($storeData);
        return response([
            'message' => 'Booking Successfully',
            'data' => $bookingGym,
        ], 200);
    }

    public function presensiGym($id){
        $bookingGym = bookingGym::find($id);

        $details = detailsBooking::find($bookingGym->id_details_booking);
        $details->sisa_kapasitas = $details->sisa_kapasitas + 1;

        $bookingGym->tanggal_presensi = date('Y-m-d H:i');
        $bookingGym->status_presensi = 1;
        $bookingGym->status_booking = 'COMPLETE';
        $bookingGym->save();

        return response([
            'message' => 'Your Attendance has been recorded',
            'data' => $bookingGym,
        ], 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function cancelBooking($id) {
        $bookingGym = BookingGym::find($id);

        $today = Carbon::now()->format('Y-m-d');
        $todayDate = Carbon::parse($today);
        $bookingDate = Carbon::parse($bookingGym->tanggal_booking);
        $diff = $todayDate->diffInDays($bookingDate);
        if($diff == 1) {
            return response([
                'message' => 'You cannot cancel your booking',
                'data' => null
            ], 400);
        }
        $bookingGym->status_booking = 'CANCELLED';
        $details = detailsBooking::find($bookingGym->id_details_booking);
        $details->sisa_kapasitas = $details->sisa_kapasitas + 1;
        $details->save();
        $bookingGym->delete();
        return response([
            'message' => 'Your Booking has Successfully Cancelled',
            'data' => $bookingGym,
        ], 200);
    }


    
    public function showbyMember($id){
        $bookingGym = DB::table('booking_gym')
            ->join('member', 'booking_gym.id_member', '=', 'member.id_member')
            ->join('details_booking_gym', 'booking_gym.id_details_booking', '=', 'details_booking_gym.id')
            ->select('booking_gym.*', 'member.nama_member', 'details_booking_gym.slot_waktu')
            ->where('booking_gym.id_member', '=', $id)
            ->get();
        
            if($bookingGym){
                return response([
                    'message' => 'Retrieve All Success',
                    'data' => $bookingGym
                ], 200);
            }
    }


    public function showbyDates($date){
        $bookingGym = DB::table('booking_gym')
            ->join('member', 'booking_gym.id_member', '=', 'member.id_member')
            ->join('details_booking_gym', 'booking_gym.id_details_booking', '=', 'details_booking_gym.id')
            ->select('booking_gym.*', 'member.nama_member', 'details_booking_gym.slot_waktu')
            ->where('booking_gym.tanggal_booking', '=', $date)
            ->get();
        
        if(count($bookingGym) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $bookingGym
            ], 200);
        }
        return response([
            'message' => 'No Booking on that date',
            'data' => null
        ], 400);

    }

    public function show($id)
    {
        // 
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
