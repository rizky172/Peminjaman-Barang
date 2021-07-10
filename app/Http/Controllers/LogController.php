<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\LogModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
class LogController extends Controller
{
    
    public function count(){
        try {
            $response['totalRecords'] = LogModel::count();
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
            $response= LogModel::select('*')
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
}
