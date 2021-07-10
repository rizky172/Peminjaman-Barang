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

class CheckPeminjamanController extends Controller
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
            $response['totalRecords'] = PeminjamanModel::where('account_id', $account_id)->count();
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

        $scroll     = ((int)$request->s - 1) * 100;
        try {
            $response= DB::table('pinjam as p')
            ->leftJoin('barang as b','b.id','=','p.barang_id')
            ->leftJoin('kategori as k','k.id','=','b.id_kategori')
            ->leftJoin('pegawai as u','u.account_id','=','p.pegawai_id')
            ->offset($scroll)
            ->limit(100)
            ->select('p.*','b.nama as nama_barang','k.nama AS nama_kategori','u.nama as pegawai')
            ->get();
            if(sizeof($response)==0){
                $response = array("data"=>"empty");
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }

    public function setStatus(Request $request)
    {   $class = "error";
        $message = "";
        $data = null;
        $account_id = session('account_id');
        // dd($request);
        DB::beginTransaction();
        try {

            $data = PeminjamanModel::where('id',$request->id)
            ->update([
                'status'   => $request->status,
                'notes'    => $request->notes ? $request->notes : null
            ]);
            $class = 'success';
            $message = "Data has ben $request->status !";

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

    public function show($id)
    {   
        try {
            $response= PeminjamanModel::find($id);
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }
}
