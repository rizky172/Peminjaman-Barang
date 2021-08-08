<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use \App\PegawaiModel;
use \App\UserModel;
use \App\LogModel;

class ProfilController extends Controller
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
        $account_id = session('account_id');
        $scroll = ((int)$request->s - 1) * 100;
        try {
            $response= LogModel::select('*')
            ->where('account_id', $account_id)
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
    
    public function rules(){
        return [
            'nip'           => 'required',
            'nama'          => 'required',
            'tgl_lahir'     => 'required',
            'jenis_kelamin' => 'required',
            'agama'         => 'required',
            'unit_kerja'    => 'required',
            'jabatan'       => 'required',
            'tlp'           => 'required',
            'email'         => 'required'
        ];
    }

    public function show($account_id)
    {   
        try {
            $response = PegawaiModel::where('account_id',$account_id)->first();
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

                $file = PegawaiModel::find($request->id);

                if($request->file){
                    $file = PegawaiModel::where('id',$request->id)->first();
                    if($file){
                        File::delete('images/profil/'.$file->foto);
                    }
                    
                    $explode = explode(',', $request->file);
                    $decode = base64_decode($explode[1]);
                    if(str_contains($explode[0],'png')){
                        $extention = 'png';
                    }else if(str_contains($explode[0],'gif')){
                        $extention = 'gif';
                    }else{
                        $extention = 'jpg';
                    }

                    $filename = $request->nama."-".time().".".$extention;
                    $path = public_path().'/images/profil/'.$filename;
                    file_put_contents($path, $decode);
                }else{
                    $filename = $file->gambar;
                }

                $data = PegawaiModel::where('account_id',$request->account_id)
                ->update([
                    'nip'           => $request->nip,
                    'nama'          => $request->nama,
                    'tgl_lahir'     => $request->tgl_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'alamat'        => $request->alamat,
                    'agama'         => $request->agama,
                    'jabatan'       => $request->jabatan,
                    'unit_kerja'    => $request->unit_kerja,
                    'email'         => $request->email,
                    'tlp'           => $request->tlp,
                    'foto'          => $filename
                ]);

                if($data){
                    $cekPegawai = PegawaiModel::where('account_id',$request->account_id)->first();
                    if($cekPegawai){
                        $data = UserModel::where('account_id',$cekPegawai->account_id)
                        ->update([
                            'name'          => $request->nama,
                            'email'         => $request->email
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
                UserModel::where('account_id',$request->account_id)
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
}
