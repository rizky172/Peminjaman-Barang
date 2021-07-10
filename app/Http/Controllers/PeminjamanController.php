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
        $account_id = session('account_id');
        $scroll     = ((int)$request->s - 1) * 100;
        try {
            $response= DB::table('pinjam as p')
            ->leftJoin('barang as b','b.id','=','p.barang_id')
            ->leftJoin('kategori as k','k.id','=','b.id_kategori')
            ->leftJoin('pegawai as u','u.account_id','=','p.pegawai_id')
            ->where('p.pegawai_id', $account_id)
            ->offset($scroll)
            ->limit(100)
            ->select('p.*','b.nama as nama_barang','k.nama AS nama_kategori')
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
            $response= DB::table('barang as b')
            ->leftJoin('kategori as k','b.id_kategori','=','k.id')
            ->select('b.*','k.nama AS nama_kategori')
            ->get();
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
                    $cekPinjam = PeminjamanModel::where('barang_id', $request->barang_id)
                    ->where('pegawai_id', $account_id)
                    ->where('status','pending')
                    ->orWhere('status','rejected')
                    ->orWhere('status','approved')
                    ->first();

                    if($cekPinjam){
                        $message = 'Duplicate data !';
                    }else{

                        $cekBarang= DB::table('barang as b')
                            ->leftJoin('kategori as k','k.id','=','b.id_kategori')
                            ->where('b.id', $request->barang_id)
                            ->select('b.*','k.nama AS nama_kategori')
                            ->first();
                        if($cekBarang->stok == 0 && $cekBarang->nama_kategori != 'Mobil'){
                            $message = 'Stok Barang Kosong !';
                        }else{

                            $data->pegawai_id   = $account_id;
                            $data->barang_id    = $request->barang_id;
                            $data->tgl_pinjam   = $request->tgl_pinjam;
                            $data->tgl_kembali  = $request->tgl_kembali;
                            $data->keperluan    = $request->keperluan;
                            $data->status       = 'pending';
                            $data->created_by   = $account_id;
                            $data->save();
                            $class = 'success';
                            $message = 'Data has ben Saved !';
                        }
                    }
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
            if($data['status'] == 'pending' || $data['status'] == 'rejected'){
                $data->delete();
                $class = 'success';
                $message = 'Data has ben Delete !';
            }else{
                $message = 'Data sudah di APPROVED tidak bisa dihapus !';
            }
            
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }
        return response()->json([
            'class'     => $class,
            'message'   => $message,
            'data'      => $data
        ]);
    }
}
