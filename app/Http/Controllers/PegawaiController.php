<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\PegawaiModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class PegawaiController extends Controller
{
    public function count(){
        try {
            $response['totalRecords'] = PegawaiModel::count();
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
            $response= PegawaiModel::select('*')
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

    // public function rules(){
    //     return [
    //         'nip'           => 'required',
    //         'nama'          => 'required',
    //         'tgl_lahir'     => 'required',
    //         'agama'         => 'required',
    //         'unit_kerja'    => 'required',
    //         'jabatan'       => 'required',
    //         'tlp'           => 'required',
    //         'email'         => 'required'
    //     ];
    // }

    // public function store(Request $request)
    // {   $class = "error";
    //     $message = "";
    //     $data = null;

    //     try {
    //         $data =  new PegawaiModel();
    //         $validator = Validator::make($request->all(), $this->rules());
    //         if ($validator->fails()) {
    //             $message = $validator->errors()->first();
    //         }else{
    //                 $data->nip          = $request->nip;
    //                 $data->nama         = $request->nama;
    //                 $data->tgl_lahir    = $request->tgl_lahir;
    //                 $data->agama        = $request->agama;
    //                 $data->unit_kerja   = $request->unit_kerja;
    //                 $data->jabatan      = $request->jabatan;
    //                 $data->tlp          = $request->tlp;
    //                 $data->email        = $request->email;
    //                 $data->alamat       = $request->alamat;
    //                 $data->created_by   = session('account_id');
    //                 $data->save();
    //                 $class = 'success';
    //                 $message = 'Data has ben Saved !';
    //         }
    //     } catch (\Exception $e) {
    //         $message = $e->getMessage();
    //     }
    //     return response()->json([
    //         'class'     => $class,
    //         'message'   => $message,
    //         'data'      => $data
    //     ]);
    // }

    public function show($id)
    {   
        try {
            $response= PegawaiModel::find($id);
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }

    // public function update(Request $request)
    // {   
    //     $class = "error";
    //     $message = "";
    //     $data = null;
    //     try {
    //         $validator = Validator::make($request->all(), $this->rules());
    //         if ($validator->fails()) {
    //             $message = $validator->errors()->first();
    //         }else{
    //             $data = PegawaiModel::where('id',$request->id)
    //             ->update([
    //                 'nip'           => $request->nip,
    //                 'nama'          => $request->nama,
    //                 'tgl_lahir'     => $request->tgl_lahir,
    //                 'alamat'        => $request->alamat,
    //                 'agama'         => $request->agama,
    //                 'jabatan'       => $request->jabatan,
    //                 'unit_kerja'    => $request->unit_kerja,
    //                 'email'         => $request->email,
    //                 'tlp'           => $request->tlp
    //             ]);
    //             $class = 'success';
    //             $message = 'Data has ben Saved !';
    //         }
    //     } catch (\Exception $e) {
    //         $message = $e->getMessage();
    //     }
    //     return response()->json([
    //         'class'     => $class,
    //         'message'   => $message,
    //         'data'      => $data
    //     ]);
    // }

    // public function change(Request $request){

    //     $class = "error";
    //     $message = "";
    //     $data = null;
    //     try {
    //         if($request->password != $request->retype_password){
    //             $message = 'Password tidak sama !';
    //         }else{
    //             PegawaiModel::where('id',$request->id)
    //             ->update([
    //                 'password'  => Hash::make($request->password)
    //             ]);
    //             $class = 'success';
    //             $message = 'Data has ben Saved !';
    //         }
    //     } catch (\Exception $e) {
    //         $message = $e->getMessage();
    //     }
    //     return response()->json([
    //         'class'     => $class,
    //         'message'   => $message,
    //         'data'      => $data
    //     ]);
    // }
    
    // public function delete($id)
    // {   
    //     $class = "error";
    //     $message = "";
    //     $data = null;
    //     try {
    //         $data = PegawaiModel::find($id);
    //         $data->delete();
    //         $class = 'success';
    //         $message = 'Data has ben Delete !';
    //     } catch (\Exception $e) {
    //         $message = $e->getMessage();
    //     }
    //     return response()->json([
    //         'class'     => $class,
    //         'message'   => $message,
    //         'data'      => $data
    //     ]);
    // }
}
