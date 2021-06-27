<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\ProdukModel;
use \App\KategoriModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function count(){
        try {
            $response['totalRecords'] = ProdukModel::count();
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
            $response= DB::table('produk as p')
            ->leftJoin('kategori as k','p.id_kategori','=','k.id')
            ->offset($scroll)
            ->limit(100)
            ->select('p.id','p.nama','p.stok','p.harga_beli','p.harga_jual','k.nama AS nama_kategori')
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
            'stok'          => 'required',
            'harga_jual'    => 'required',
            'harga_beli'    => 'required'
        ];
    }

    public function store(Request $request)
    {   $class = "error";
        $message = "";
        $data = null;
        try {
            $data =  new ProdukModel();
            $validator = Validator::make($request->all(), $this->rules());
            if ($validator->fails()) {
                $message = $validator->errors()->first();
            }else{  
                    if(ProdukModel::where('nama', $request->nama)->first()){
                        $message = "Duplicate data $request->nama !";
                    }else{
                        $data->nama         = $request->nama;
                        $data->id_kategori  = $request->id_kategori;
                        $data->stok         = $request->stok;
                        $data->harga_jual   = $request->harga_jual;
                        $data->harga_beli   = $request->harga_beli;
                        $data->created_by   = session('id');
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
            $response= ProdukModel::find($id);
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
                $data = ProdukModel::where('id',$request->id)
                ->update([
                    'id_kategori'   => $request->id_kategori,
                    'nama'          => $request->nama,
                    'stok'          => $request->stok,
                    'harga_beli'    => $request->harga_beli,
                    'harga_jual'    => $request->harga_jual,
                    'updated_by'    => session('id')
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
            $data = ProdukModel::find($id);
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
}
