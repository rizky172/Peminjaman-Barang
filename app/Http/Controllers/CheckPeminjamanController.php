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
use PDF;
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
            $response['totalRecords'] = PeminjamanModel::count();
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
            ->leftJoin('pegawai as u','u.account_id','=','p.pegawai_id')
            ->offset($scroll)
            ->limit(100)
            ->select('p.*','b.no_plat','b.jenis','u.nama as pegawai')
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
    {   
        $class = "error";
        $message = "";
        $data = null;
        $account_id = session('account_id');
        
        DB::beginTransaction();
        try {

            $data = PeminjamanModel::where('id',$request->id)
            ->update([
                'status'   => $request->status,
                'notes'    => $request->notes ? $request->notes : null
            ]);

            $getPinjamById = PeminjamanModel::find($request->id);
            if($getPinjamById){
                $tmpPinjam =  new TmpPinjamModel();
                $tmpPinjam->pinjam_id       = $getPinjamById->id;
                $tmpPinjam->status          = $request->status;
                $tmpPinjam->notes           = $request->notes ? $request->notes : null;
                $tmpPinjam->user_id         = $account_id;
                $tmpPinjam->created_by      = $account_id;
                $tmpPinjam->save();
            }

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
                'user.name as user','barang.no_plat','barang.jenis',
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
