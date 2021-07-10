<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use \App\BarangModel;
use \App\KategoriModel;


class BarangController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('guest:admin')->except('count')->except('table');
    // }

    public function count(){
        try {
            $response['totalRecords'] = BarangModel::count();
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

        $scroll = ((int)$request->s - 1) * 100;
        try {
            $response= DB::table('barang as b')
            ->leftJoin('kategori as k','b.id_kategori','=','k.id')
            ->offset($scroll)
            ->limit(100)
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

    public function rules(){
        return [
            'nama'          => 'required',
            'merk'          => 'required',
            'id_kategori'   => 'required'
        ];
    }

    public function store(Request $request)
    {   $class = "error";
        $message = "";
        $data = null;
        // $explode = explode(',', $request->file);
        // $decode = base64_decode($explode[1]);
        // if(str_contains($explode[0],'png')){
        //     $extention = 'png';
        // }

        // $filename = time().".".$extention;
        // $path = public_path().'/images/'.$filename;
        // file_put_contents($path, $decode);
        // dd($request);
        // $digit = mt_rand(1000,9999);
        // dd($digit);
        try {
            $data =  new BarangModel();
            $validator = Validator::make($request->all(), $this->rules());
            if ($validator->fails()) {
                $message = $validator->errors()->first();
            }else{  
                    if(BarangModel::where('nama', $request->nama)->first()){
                        $message = "Duplicate data $request->nama !";
                    }else{
                        $data->nama         = $request->nama;
                        $data->merk         = $request->merk;
                        $data->id_kategori  = $request->id_kategori;
                        $data->stok         = $request->stok ? $request->stok : 0;
                        $data->created_by   = session('account_id');
                        $data->save();
                        $class = 'success';
                        $message = 'Data has ben Saved !';
                    }
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

    public function show($id)
    {   
        try {
            $response= BarangModel::find($id);
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }

    public function update(Request $request)
    {   
        $class = "error";
        $message = "";
        $data = null;
        try {
            $validator = Validator::make($request->all(), $this->rules());
            if ($validator->fails()) {
                $message = $validator->errors()->first();
            }else{
                $data = BarangModel::where('id',$request->id)
                ->update([
                    'id_kategori'   => $request->id_kategori,
                    'nama'          => $request->nama,
                    'stok'          => $request->stok ? $request->stok : 0,
                    'merk'          => $request->merk,
                    'updated_by'    => session('account_id')
                ]);
                $class = 'success';
                $message = 'Data has ben Saved !';
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
    
    public function delete($id)
    {   
        $class = "error";
        $message = "";
        $data = null;
        try {
            $data = BarangModel::find($id);
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

    public function getKategori()
    {   
        try {
            $response= KategoriModel::all();
            if(sizeof($response)==0){
                $response = array("data"=>"empty");
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }

    public function getKategoriById($id)
    {   
        try {
            $response= KategoriModel::find($id);
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }
}
