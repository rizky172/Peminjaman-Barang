<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\UserModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

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
            $response= UserModel::select('id','name','email','role')
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
            'name' => 'required',
            'email' => 'required',
        ];
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
                // if($request->password != $request->retype_password){
                //     $message = 'Password tidak sama !';
                // }else{
                    $data->country_slug = $request->country_slug;
                    $data->region = $request->region;
                    $data->region_short = $request->region_short;
                    $data->region_slug = $request->region_slug;
                    $data->weight = $request->weight;
                    // $data->password = bcrypt($request->password);
                    $data->save();
                    $class = 'success';
                    $message = 'Data has ben Saved !';
                // }
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
            $response= UserModel::find($id);
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
                $data = UserModel::where('id',$request->id)
                ->update([
                    'country_slug'  => $request->country_slug,
                    'region' => $request->region,
                    'region_short' => $request->region_short,
                    'region_slug' => $request->region_slug,
                    'weight' => $request->weight,
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

    // public function search(Request $request)
    // {   
    //     try {
    //         $response =  UserModel::where('name','LIKE',"%$request->q%")
    //         ->orWhere('email','LIKE',"%$request->q%")
    //         ->get();  
    //         if(sizeof($response)==0){
    //             $response = array("data"=>"empty");
    //         }
    //     } catch (\Exception $e) {
    //         $response = $e->getMessage();
    //     }
    //     return response()->json($response);
    // }

    // public function table(Request $request)
    // {   
    //     try {
    //         $response= UserModel::paginate($request->p);
    //         if(sizeof($response)==0){
    //             $response = array("data"=>"empty");
    //         }
    //     } catch (\Exception $e) {
    //         $response = $e->getMessage();
    //     }
    //     return response()->json($response);
    // }

    // public function rules(){
    //     return [
    //         'name' => 'required',
    //         'email' => 'required'
    //     ];
    // }

    // public function store(Request $request)
    // {   $class = "error";
    //     $message = "";
    //     $data = null;
    //     try {
    //         $data =  new UserModel();
    //         $validator = Validator::make($request->all(), $this->rules());
    //         if ($validator->fails()) {
    //             $message = $validator->errors()->first();
    //         }else{
    //             if($request->password != $request->retype_password){
    //                 $message = 'Password tidak sama !';
    //             }else{
    //                 $data->name = $request->name;
    //                 $data->email = $request->email;
    //                 $data->password = bcrypt($request->password);
    //                 $data->save();
    //                 $class = 'success';
    //                 $message = 'Data has ben Saved !';
    //             }
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

    // public function show($id)
    // {   
    //     try {
    //         $response= UserModel::find($id);
    //     } catch (\Exception $e) {
    //         $response = $e->getMessage();
    //     }
    //     return response()->json($response);
    // }

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
    //             $data = UserModel::where('id',$request->id)
    //             ->update([
    //                 'name'  => $request->name,
    //                 'email' => $request->email
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
    //             UserModel::where('id',$request->id)
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
    //         $data = UserModel::find($id);
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
