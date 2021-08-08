<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use \App\MobilModel;


class MobilController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('guest:admin')->except('count')->except('table');
    // }

    public function count(){
        try {
            $response['totalRecords'] = MobilModel::count();
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
            $response= DB::table('tbl_mobil')
            ->offset($scroll)
            ->limit(100)
            ->select('*')
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
            'no_plat'        => 'required',
            'jenis'          => 'required'
        ];
    }

    public function store(Request $request)
    {   
        $class = "error";
        $message = "";
        $data = null;
        $filename = null;

        if($request->file){
            $explode = explode(',', $request->file);
            $decode = base64_decode($explode[1]);
            if(str_contains($explode[0],'png')){
                $extention = 'png';
            }else if(str_contains($explode[0],'gif')){
                $extention = 'gif';
            }else{
                $extention = 'jpg';
            }

            $filename = $request->jenis."-".time().".".$extention;
            $path = public_path().'/images/mobil/'.$filename;
            file_put_contents($path, $decode);
        }
        
        try {
            $data =  new MobilModel();
            $validator = Validator::make($request->all(), $this->rules());
            if ($validator->fails()) {
                $message = $validator->errors()->first();
            }else{  
                    if(MobilModel::where('no_plat', $request->no_plat)->first()){
                        $message = "Duplicate data $request->no_plat !";
                    }else{
                        $data->no_plat      = $request->no_plat;
                        $data->jenis        = $request->jenis;
                        $data->gambar       = $filename;
                        $data->persentase   = 100;
                        $data->created_by   = session('account_id');
                        $data->save();
                        $class = 'success';
                        $message = 'Data has ben Saved !';
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

    public function show($id)
    {   
        try {
            $response= MobilModel::find($id);
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

                $file = MobilModel::find($request->id);

                if($request->file){
                    $file = MobilModel::where('id',$request->id)->first();
                    if($file){
                        File::delete('images/mobil/'.$file->gambar);
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

                    $filename = $request->jenis."-".time().".".$extention;
                    $path = public_path().'/images/mobil/'.$filename;
                    file_put_contents($path, $decode);
                }else{
                    $filename = $file->gambar;
                }

                $data = MobilModel::where('id',$request->id)
                ->update([
                    'no_plat'       => $request->no_plat,
                    'jenis'         => $request->jenis,
                    'gambar'        => $filename,
                    'updated_by'    => session('account_id')
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
            $file = MobilModel::where('id',$id)->first();
            if($file){
                File::delete('images/mobil/'.$file->gambar);
            }

            $data = MobilModel::find($id);
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
