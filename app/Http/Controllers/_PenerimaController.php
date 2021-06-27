<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\PenerimaModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PenerimaController extends Controller
{
    public function search(Request $request)
    {   
        try {
            $response =  PenerimaModel::where('name','LIKE',"%$request->q%")
            ->orWhere('email','LIKE',"%$request->q%")
            ->get();  
            if(sizeof($response)==0){
                $response = array("data"=>"empty");
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }

    public function table(Request $request)
    {   
        try {
            $response= PenerimaModel::paginate($request->p);
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
            'name' => 'required',
            'email' => 'required'
        ];
    }

    public function store(Request $request)
    {   $class = "error";
        $message = "";
        $data = null;
        try {
            $validator = Validator::make($request->all(),
                [
                    'name' => 'required|string',
                    'email' => 'required|string|email',
                    'kontak' => 'required|integer|size:12',
                    'alamat' => 'required',
                    'keterangan' => 'required',
                ]
            );
            if ($validator->fails()) {
                $message = $validator->errors()->first();
            }else{
                $response = \App\PenerimaModel::create([
                    'name'          => $request->name,
                    'email'         => $request->email,
                    'kontak'        => $request->kontak,
                    'alamat'        => $request->alamat,
                    'keterangan'    => $request->keterangan
                ]);
                $data = $response->toArray();
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

    public function show($id)
    {   
        try {
            $response= PenerimaModel::find($id);
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
            $validator = Validator::make($request->all(), 
                [
                    'name' => 'required|string',
                    'email' => 'required|string|email',
                    'kontak' => 'required|integer|size:12',
                    'alamat' => 'required',
                    'keterangan' => 'required',
                ]
            );
            if ($validator->fails()) {
                $message = $validator->errors()->first();
            }else{
                $data = PenerimaModel::where('id',$request->id)
                ->update([
                    'name'          => $request->name,
                    'email'         => $request->email,
                    'kontak'        => $request->kontak,
                    'alamat'        => $request->alamat,
                    'keterangan'    => $request->keterangan
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
            $data = PenerimaModel::find($id);
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
}
