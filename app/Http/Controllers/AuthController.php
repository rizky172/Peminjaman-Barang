<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Http\Requests;
use App\UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Validator;
use App\User;
use App\Mail\VerifyEmail;
use Crypt;
use Mail;


class AuthController extends Controller
{

    function login(Request $request){
        $user = User::where('email', '=', $request->email)->first();
        $class = "error";
        $message = "";
        $data = null;
        try {
            if($user){
                if($user['status'] == 'actived'){
                    if (Hash::check($request->password, $user->password)) {
                        session([
                            'id'    =>$user->id,
                            'name'  => $user->name,
                            'role'  => $user->role,
                            'login' => TRUE
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
                $message = "Email Wrong !";
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
        if ($user) {
            $request->session()->flush();
            $user->api_token = null;
            $user->save();
        }
        return response()->json([
            'class' => 'success',
            'message' => 'logout berhasil',
            'data' => $user
        ], 200); 
    }

    public function register(Request $request)
    {
        
        
        $class = "error";
        $message = "";
        $data = null;
        $data =  new UserModel();
        // $code = 400;
                // 'name'      => $request->name,
                // 'email'     => $request->email,
                // 'password'  => Hash::make($request->password),
                // 'role'      => 'admin',
                // 'status'    => 'pending'
            $data->name         = $request->name;
            $data->email         = $request->email;
            $data->password       = Hash::make($request->password);
            $data->role       = 'admin';
            $data->status   = 'pending';
            $data->save();
            
            // if($user){
                // Auth::login($user);
                $class = 'success';
                $message = 'Data has ben Saved !';
            // }
            // else{
            //     $message = 'register failed';
            // }
        // }

        return response()->json([
            'class'     => $class,
            'message'   => $message,
            'data'      => $data
        ], 200);
    }

    // function register(Request $req){
    //     $data =  new ModelUser();
    //     $data->username = $req->username;
    //     $data->password = bcrypt($req->password);
    //     $data->save();
    //     return redirect('login');
    // }
}
