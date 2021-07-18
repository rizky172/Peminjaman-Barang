<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use \App\PeminjamanModel;
use \App\BarangModel;
use \App\TmpPinjamModel;

class PeminjamanController extends Controller
{   
    
    public function rules(){
        return [
            'barang_id'     => 'required',
            'tgl_pinjam'    => 'required',
            'tgl_kembali'   => 'required',
            'keperluan'     => 'required'
        ];
    }

    public function count(){
        $account_id = session('account_id');
        try {
            $response['totalRecords'] = PeminjamanModel::where('pegawai_id', $account_id)->count();
            if($response == 0){
                $response = array("totalRecords"=>"0");
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }

    public function table(Request $request)
    {   
        $account_id = session('account_id');
        $scroll     = ((int)$request->s - 1) * 100;
        try {
            $response= DB::table('pinjam as p')
            ->leftJoin('barang as b','b.id','=','p.barang_id')
            ->leftJoin('pegawai as u','u.account_id','=','p.pegawai_id')
            ->where('p.pegawai_id', $account_id)
            ->offset($scroll)
            ->limit(100)
            ->select('p.*','b.no_plat','b.jenis')
            ->get();
            if(sizeof($response)==0){
                $response = array("data"=>"empty");
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }

    public function getBarangAll()
    {   
        try {
            $getPinjam = PeminjamanModel::all();
            if(sizeof($getPinjam)==0){
                $response = DB::table('barang')
                ->select('*')
                ->get();
            }else{
                $response = DB::select(DB::raw(
                    "SELECT t1.* FROM barang t1
                    LEFT JOIN pinjam t2 ON t2.barang_id=t1.id AND t2.id=
                    (SELECT MAX(id) FROM pinjam WHERE barang_id=t1.id)
                    WHERE t2.id IS NULL OR (t2.status='return' OR t2.status='rejected')"
                ));
            }
            
            if(sizeof($response)==0){
                $response = array("data"=>"empty");
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }

    public function store(Request $request)
    {   $class = "error";
        $message = "";
        $data = null;
        $account_id = session('account_id');

        DB::beginTransaction();
        try {
            $data =  new PeminjamanModel();
            $validator = Validator::make($request->all(), $this->rules());
            if ($validator->fails()) {
                $message = $validator->errors()->first();
            }else{  
                $data->pegawai_id   = $account_id;
                $data->barang_id    = $request->barang_id;
                $data->tgl_pinjam   = $request->tgl_pinjam;
                $data->tgl_kembali  = $request->tgl_kembali;
                $data->keperluan    = $request->keperluan;
                $data->status       = 'pending';
                $data->created_by   = $account_id;
                $data->save();
                $class      = 'success';
                $message    = 'Data has ben Saved !';
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = $e->getMessage();
        }
        return response()->json([
            'class'     => $class,
            'message'   => $message,
            'data'      => $data
        ]);
    }

    public function delete($id)
    {   
        $class = "error";
        $message = "";
        $data = null;
        try {
            $data = PeminjamanModel::find($id);
            $data->delete();
            $class = 'success';
            $message = 'Data has ben Delete !';
            
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json([
            'class'     => $class,
            'message'   => $message,
            'data'      => $data
        ]);
    }

    public function getHistoryById(Request $request)
    {   
        $scroll     = ((int)$request->s - 1) * 100;
        try {
            $response= DB::table('tmp_pinjam as tmp')
            ->leftJoin('pinjam as pinjam','pinjam.id','=','tmp.pinjam_id')
            ->leftJoin('users as user','user.account_id','=','tmp.user_id')
            ->leftJoin('pegawai as pegawai','pegawai.account_id','=','pinjam.pegawai_id')
            ->leftJoin('barang as barang','barang.id','=','pinjam.barang_id')
            ->where('tmp.pinjam_id', $request->pinjam_id)
            ->offset($scroll)
            ->limit(100)
            ->select(
                'tmp.*','pegawai.nama as pegawai',
                'user.name as user','barang.jenis','barang.no_plat',
                'pinjam.keperluan'
            )
            ->get();

            if(sizeof($response)==0){
                $response = array("data"=>"empty");
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }

    public function countHistory($id){
        try {
            $response['totalRecords'] = TmpPinjamModel::where('pinjam_id', $id)->count();
            if($response == 0){
                $response = array("totalRecords"=>"0");
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }
}
