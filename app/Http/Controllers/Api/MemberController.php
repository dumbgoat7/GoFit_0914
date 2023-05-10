<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $member = Member::all();

        if(count($member) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $member
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
            'nama_member' => 'required|max:60',
            'alamat_member' => 'required',
            'no_telp' => 'required|max:13',
            'deposit_member' => 'required|numeric',
            'email_member' => 'required|email:rfc,dns',
            'tanggal_lahir' => 'required|date_format:Y-m-d',
            'username' => 'required',
            'password' => 'required',
        ]);
        if($validate->fails()) {
            return response(['message' => $validate->errors()],400);
        }
        $count = DB::table('member')->count()+1;
        $generate = sprintf("%d", $count);
        $date = Carbon::now()->format('y.m');
        $storeData['id_member'] = $date.'.'.$generate;
        $storeData['password'] = bcrypt($request->password);
        $storeData['status'] = '0';
        $storeData['masa_berlaku'] = null;
        $member = Member::create($storeData);
        return response([
            'message' => 'Add Member Success',
            'data' => $member
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $member = Member::find($id);
        if(!is_null($member)){
            return response([
                'message' => 'Retrieve Member Success',
                'data' => $member
            ],200);
        }

        return response([
            'message' => 'Member Not Found',
            'data' => null
        ],404);
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
        $member = Member::find($id);
        if(is_null($member)){
            return response([
                'message' => 'Member Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_member' => 'required|max:60',
            'alamat_member' => 'required',
            'no_telp' => 'required|max:13',
            'deposit_member' => 'required|numeric',
            'email_member' => 'required|email:rfc,dns',
            'tanggal_lahir' => 'required|date_format:Y-m-d',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()],400);
        }
        $member->nama_member = $updateData['nama_member'];
        $member->alamat_member = $updateData['alamat_member'];
        $member->no_telp = $updateData['no_telp'];
        $member->deposit_member = $updateData['deposit_member'];
        $member->email_member = $updateData['email_member'];
        $member->tanggal_lahir = $updateData['tanggal_lahir'];
        if($member->save()){
            return response([
                'message' => 'Update Member Success',
                'data' => $member
            ],200);
        }
        return response([
            'message' => 'Update Member Failed',
            'data' => null
        ],400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = Member::find($id);

        if(is_null($member)){
            return response([
                'message' => 'Member Not Found',
                'data' => null
            ],404);
        }

        if($member->delete()){
            return response([
                'message' => 'Delete Member Success',
                'data' => $member,
            ],200);
        }

        return response([
            'message' => 'Delete Member Failed',
            'data' => null,
        ],400);
    }

    public function resetPassword($id) {
        $member = Member::find($id);

        if(is_null($member)){
            return response([
                'message' => 'Member Not Found',
                'data' => null
            ],404);
        }
        $member->password = bcrypt($member->tanggal_lahir);
        
        if($member->save()){
            return response([
                'message' => 'Reset Password Member Success',
                'data' => $member,
            ], 200);
        }

        return response([
            'message' => 'Reset Password Member Failed',
            'data' => null,
        ], 400);
    }

    public function deactiveMember($id) {
        $member = Member::find($id);

        if(is_null($member)){
            return response([
                'message' => 'Member Not Found',
                'data' => null
            ],404);
        }
        $member->status = '0';
        $member->masa_berlaku = null;
        
        if($member->save()){
            return response([
                'message' => 'Deactive Member Success',
                'data' => $member,
            ], 200);
        }

        return response([
            'message' => 'Deactive Member Failed',
            'data' => null,
        ], 400);

    }

    public function ActiveMember() {
 
        $member = DB::table('member')
            ->where('status', '=', '1')
            ->get();

        if(count($member) > 0){
            return response([
                'message' => 'Retrieve All Active Member Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }
}
