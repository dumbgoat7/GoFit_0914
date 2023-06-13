<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Instruktur;
use Illuminate\Support\Facades\DB;
class InstrukturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $instruktur = DB::table('instruktur')
        //     ->join('ijin_instruktur', 'instruktur.id', '=', 'ijin_instruktur.id_instruktur')
        //     ->select('instruktur.id','instruktur.nama_instruktur', 'instruktur.no_telp','instruktur.alamat_instruktur','instruktur.gaji_instruktur','instruktur.email_instruktur','instruktur.tanggal_lahir','instruktur.username','instruktur.password', DB::raw('COUNT(ijin_instruktur.id_instruktur) as jumlah_ijin'))
        //     ->groupBy('instruktur.id', 'instruktur.nama_instruktur', 'instruktur.no_telp', 'instruktur.alamat_instruktur', 'instruktur.gaji_instruktur', 'instruktur.email_instruktur', 'instruktur.tanggal_lahir', 'instruktur.username', 'instruktur.password')
        //     ->get();
        
            $query = "Select *, (SELECT COUNT(id_instruktur) FROM ijin_instruktur WHERE ijin_instruktur.id_instruktur = instruktur.id) as jumlah_ijin, (SELECT SUM(waktu_terlambat) FROM presensi_instruktur WHERE presensi_instruktur.id_instruktur = instruktur.id) as waktu_terlambat FROM instruktur";
            $instruktur = DB::select(DB::raw($query));

        if(count($instruktur) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $instruktur
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
            'nama_instruktur' => 'required|max:60',
            'no_telp' => 'required|max:13',
            'alamat_instruktur' => 'required',
            'gaji_instruktur' => 'required|numeric',
            'email_instruktur' => 'required|email:rfc,dns',
            'tanggal_lahir' => 'required|date_format:Y-m-d',
            'username' => 'required',
            'password' => 'required',
        ]);
        if($validate->fails()) {
            return response(['message' => $validate->errors()],400);
        }
        $storeData['password'] = bcrypt($request->password);
        $instruktur = Instruktur::create($storeData);
        return response([
            'message' => 'Add Instruktur Success',
            'data' => $instruktur
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
        $query = "Select *, (SELECT COUNT(id_instruktur) FROM ijin_instruktur WHERE ijin_instruktur.id_instruktur = instruktur.id) as jumlah_ijin, (SELECT SUM(waktu_terlambat) FROM presensi_instruktur WHERE presensi_instruktur.id_instruktur = instruktur.id) as waktu_terlambat FROM instruktur WHERE id = $id ";
        $instruktur = DB::select(DB::raw($query));

        if (!is_null($instruktur)) {
            return response([
                'message' => 'Retrieve Instruktur Success',
                'data' => $instruktur
            ], 200);
        }

        return response([
            'message' => 'Instruktur Not Found',
            'data' => null
        ], 404);
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
        $instruktur = Instruktur::find($id);
        if(is_null($instruktur)){
            return response([
                'message' => 'Instruktur Not Found',
                'data' => null
            ], 404);
        }
        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_instruktur' => 'required|max:60',
            'no_telp' => 'required|max:13',
            'alamat_instruktur' => 'required',
            'gaji_instruktur' => 'required|numeric',
            'email_instruktur' => 'required|email:rfc,dns',
            'tanggal_lahir' => 'required|date_format:Y-m-d',
        ]);
        if($validate->fails()){
            return response(['message' => $validate->errors()],400);
        }
        $instruktur->nama_instruktur = $updateData['nama_instruktur'];
        $instruktur->no_telp = $updateData['no_telp'];
        $instruktur->alamat_instruktur = $updateData['alamat_instruktur'];
        $instruktur->gaji_instruktur = $updateData['gaji_instruktur'];
        $instruktur->email_instruktur = $updateData['email_instruktur'];
        $instruktur->tanggal_lahir = $updateData['tanggal_lahir'];
        if($instruktur->save()){
            return response([
                'message' => 'Update Instruktur Success',
                'data' => $instruktur,
            ],200);
        }

        return response([
            'message' => 'Update Instruktur Failed',
            'data' => null,
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
        $instruktur = Instruktur::find($id);

        if(is_null($instruktur)){
            return response([
                'message' => 'Instruktur Not Found',
                'data' => null
            ], 404);
        }
        if($instruktur->delete()){
            return response([
                'message' => 'Delete Instruktur Success',
                'data' => $instruktur,
            ],200);
        }
        response([
            'message' => 'Delete Instruktur Failed',
            'data' => null,
        ],400);
    }

    public function resetPassword($username) {
        $instruktur = Instruktur::where('username', $username)->first();

        if(is_null($instruktur)){
            return response([
                'message' => 'Instruktur Not Found',
                'data' => null
            ], 404);
        }
        $instruktur->password = bcrypt($instruktur->tanggal_lahir);
        $instruktur->save();
        return response([
            'message' => 'Reset Password Success',
            'data' => $instruktur
        ], 200);

    }
}
