<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\PegawaiModel;
use \App\UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfilController extends Controller
{

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
                    'tlp'           => $request->tlp
                ]);
                if($data){
                    $cekPegawai = PegawaiModel::where('account_id',$request->account_id)->first();
                    if($cekPegawai){
                        $data = UserModel::where('account_id',$cekPegawai->account_id)
                        ->update([
                            'nama'          => $request->name,
                            'email'         => $request->email,
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
}
