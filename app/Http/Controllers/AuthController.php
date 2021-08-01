<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserModel;
use App\PegawaiModel;
use App\LogModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Mail\VerifyEmail;
use Crypt;
use Mail;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{

    public function rules(){
        return [
            'name'              => 'required',
            'nip'               => 'required',
            'password'          => 'required',
            'retype_password'   => 'required'
        ];
    }

    function login(Request $request){
        $user = User::where('nip', '=', $request->nip)->first();
        $class = "error";
        $message = "";
        $data = null;
        try {
            if($user){
                if($user['status'] == 'actived'){
                    if (Hash::check($request->password, $user->password)) {
                        $dataLog =  new LogModel();
                        $dataLog->account_id    = $user->account_id;
                        $dataLog->last_login    = date("Y-m-d H:i:s");
                        $dataLog->save();
                        
                        session([
                            'id'            => $user->id,
                            'account_id'    => $user->account_id,
                            'name'          => $user->name,
                            'role'          => $user->role,
                            'login'         => TRUE
                        ]);
                        $user->generateToken();
                        $data = $user->toArray();
                        $class = 'success';
                        $message = 'Login Succes !'; 
                    }
                    else{
                        $message = "Password Wrong !";
                    }
                }else{
                    $message = "Silahkan Verifikasi Email terlebih dahulu";
                }       
            }
            else{
                $message = "NIP Wrong !";
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

    public function register(Request $request)
    {
        $class = "error";
        $message = "";
        $data = null;
        try {
            $validator = Validator::make($request->all(), $this->rules());
            if ($validator->fails()) {
                $message = $validator->errors()->first();
            }else{
                if($request->password != $request->retype_password){
                    $message = 'Password tidak sama !';
                }else if($user = User::where('nip', '=', $request->nip)->first()){
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
                    $data->nip        = $request->nip;
                    $data->password     = Hash::make($request->password);
                    $data->role         = 'member';
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
                            $dataPegawai->created_by   = $cekUser->account_id;
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

    function logout(Request $request){
        $user = Auth::user();
        $account_id = session('account_id');
        if ($user) {
            $dataLog =  new LogModel();
            $dataLog->account_id    = $account_id;
            $dataLog->last_logout   = date("Y-m-d H:i:s");
            $dataLog->save();

            $request->session()->flush();
            $user->api_token = null;
            $user->save();
        }
        return response()->json([
            'class' => 'success',
            'message' => 'logout berhasil',
            'data' => $user
        ]); 
    }

    

    
}
