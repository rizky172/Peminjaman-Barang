<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\UserModel;
use \App\PegawaiModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function rules(){
        return [
            'name'              => 'required',
            'nip'               => 'required',
            'role'              => 'required'
        ];
    }

    public function count(){
        try {
            $response['totalRecords'] = UserModel::count();
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
            $response= UserModel::select('*')
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

    public function show($id)
    {   
        try {
            $response= UserModel::find($id);
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }

    public function store(Request $request)
    {   $class = "error";
        $message = "";
        $data = null;
        try {
            $data =  new UserModel();
            $validator = Validator::make($request->all(), $this->rules());
            if ($validator->fails()) {
                $message = $validator->errors()->first();
            }else{
                if($request->password != $request->retype_password){
                    $message = 'Password tidak sama !';
                }else if($data = UserModel::where('nip', '=', $request->nip)->first()){
                    $message = 'NIP sudah digunakan !';
                }else{
                    do {
                        // random number for 4 digit
                        $int = mt_rand(1000,9999);
                        $cek_id = UserModel::where('account_id',$int);
                    } while (!$cek_id);
                        $account_id = $int;
                    $data =  new UserModel();
                    $data->account_id   = $account_id;
                    $data->name         = $request->name;
                    $data->nip          = $request->nip;
                    $data->role         = $request->role;
                    $data->password     = Hash::make($request->password);
                    $data->status       = 'actived';
                    $data->save();
                    
                    // jika berhasil lalu insert ke table pegawai
                    if($data){
                        $cekUser= UserModel::find($data->id);
                        if($cekUser){
                            $dataPegawai =  new PegawaiModel();
                            $dataPegawai->account_id   = $cekUser->account_id;
                            $dataPegawai->nama         = $cekUser->name;
                            $dataPegawai->nip          = $cekUser->nip;
                            $dataPegawai->created_by   = session('account_id');
                            $dataPegawai->save();
                            $class = 'success';
                            $message = 'Data has ben Saved !';
                        }
                    }
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
                $data = UserModel::where('id',$request->id)
                ->update([
                    'name'  => $request->name,
                    'nip'   => $request->nip,
                    'role'  => $request->role,
                ]);

                // jika berhasil lalu update ke table pegawai
                if($data){
                    $cekUser= UserModel::find($request->id);
                    if($cekUser){
                        $data = PegawaiModel::where('account_id',$cekUser->account_id)
                        ->update([
                            'nama'          => $request->name,
                            'nip'           => $request->nip,
                            'updated_by'    => session('account_id')
                        ]);
                        $class = 'success';
                        $message = 'Data has ben Saved !';
                    }
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

    public function change(Request $request){

        $class = "error";
        $message = "";
        $data = null;
        try {
            if($request->password != $request->retype_password){
                $message = 'Password tidak sama !';
            }else{
                UserModel::where('id',$request->id)
                ->update([
                    'password'  => Hash::make($request->password)
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
            $data = UserModel::find($id);
            $data->delete();
            if($data){
                $dataPegawai = PegawaiModel::where('account_id',$data->account_id)->first();
                $dataPegawai->delete();
                $class = 'success';
                $message = 'Data has ben Delete !';
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
