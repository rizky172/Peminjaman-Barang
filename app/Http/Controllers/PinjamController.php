<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\PinjamModel;
use \App\BarangModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PinjamController extends Controller
{
    public function count(){
        try {
            $response['totalRecords'] = PinjamModel::count();
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
            $response= PinjamModel::select('*')
            ->offset($scroll)
            ->limit(100)
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
            // $response= BarangModel::all();
            if(sizeof($response)==0){
                $response = array("data"=>"empty");
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }
}
