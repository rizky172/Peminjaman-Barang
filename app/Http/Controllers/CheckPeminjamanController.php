<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use \App\PeminjamanModel;
use \App\MobilModel;
use \App\TmpPinjamModel;
use PDF;

class CheckPeminjamanController extends Controller
{   
    
    public function rules(){
        return [
            'mobil_id'     => 'required',
            'tgl_pinjam'    => 'required',
            'tgl_kembali'   => 'required',
            'keperluan'     => 'required'
        ];
    }

    public function count(){
        $account_id = session('account_id');
        try {
            $response['totalRecords'] = PeminjamanModel::count();
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

        $scroll     = ((int)$request->s - 1) * 100;
        try {
            $response= DB::table('tbl_pinjam as p')
            ->leftJoin('tbl_mobil as b','b.id','=','p.mobil_id')
            ->leftJoin('tbl_pegawai as u','u.account_id','=','p.pegawai_id')
            ->offset($scroll)
            ->limit(100)
            ->select('p.*','b.no_plat','b.jenis','b.gambar','u.nama as pegawai')
            ->get();
            if(sizeof($response)==0){
                $response = array("data"=>"empty");
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }

    public function setStatus(Request $request)
    {   
        $class = "error";
        $message = "";
        $data = null;
        $account_id = session('account_id');
        
        DB::beginTransaction();
        try {

            $data = PeminjamanModel::where('id',$request->id)
            ->update([
                'status'   => $request->status,
                'notes'    => $request->notes ? $request->notes : null
            ]);

            $getPinjamById = PeminjamanModel::find($request->id);

            if($request->status == 'approved'){
                $getMobilById = MobilModel::find($getPinjamById->mobil_id);

                $total      = $getMobilById->total_pinjam + 1;
                $persentase = $getMobilById->persentase - 5;
                
                $data_mobil = MobilModel::where('id',$getMobilById->id)
                ->update([
                    'total_pinjam'   => $total,
                    'persentase'     => $persentase
                ]);
            }

            if($getPinjamById){
                $tmpPinjam =  new TmpPinjamModel();
                $tmpPinjam->pinjam_id       = $getPinjamById->id;
                $tmpPinjam->status          = $request->status;
                $tmpPinjam->notes           = $request->notes ? $request->notes : null;
                $tmpPinjam->user_id         = $account_id;
                $tmpPinjam->created_by      = $account_id;
                $tmpPinjam->save();
            }

            $class = 'success';
            $message = "Data has ben $request->status !";

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
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
            $response= PeminjamanModel::find($id);
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }


    public function getHistoryById(Request $request)
    {   
        $scroll     = ((int)$request->s - 1) * 100;
        try {
            $response= DB::table('tbl_detail_pinjam as tmp')
            ->leftJoin('tbl_pinjam as pinjam','pinjam.id','=','tmp.pinjam_id')
            ->leftJoin('users as user','user.account_id','=','tmp.user_id')
            ->leftJoin('tbl_pegawai as pegawai','pegawai.account_id','=','pinjam.pegawai_id')
            ->leftJoin('tbl_mobil as mobil','mobil.id','=','pinjam.mobil_id')
            ->where('tmp.pinjam_id', $request->pinjam_id)
            ->offset($scroll)
            ->limit(100)
            ->select(
                'tmp.*','pegawai.nama as pegawai',
                'user.name as user','mobil.no_plat','mobil.jenis',
                'pinjam.keperluan'
            )
            ->get();

            if(sizeof($response)==0){
                $response = array("data"=>"empty");
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }

    public function countHistory($id){
        try {
            $response['totalRecords'] = TmpPinjamModel::where('pinjam_id', $id)->count();
            if($response == 0){
                $response = array("totalRecords"=>"0");
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }

    public function grafik($id){
        try {
            $response = DB::select(DB::raw(
                "SELECT t1.tgl_pinjam as date, COUNT(t1.id) as jumlah, t2.jenis as mobil
                FROM tbl_pinjam t1
                LEFT JOIN tbl_mobil t2 ON t2.id=t1.mobil_id
                WHERE t1.mobil_id=$id
                GROUP BY t1.tgl_pinjam"
            ));
            if(sizeof($response)==0){
                $response = array("data"=>"empty");
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
    	return response()->json($response);
    }

    public function getMobilAll()
    {   
        try {
            $response= MobilModel::all();
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return response()->json($response);
    }

    public function getReportBulanan(Request $request){
        try {
            $response = DB::select(DB::raw(
                "SELECT t1.tgl_pinjam, t1.tgl_kembali, t1.keperluan, t2.notes, 
                t3.no_plat, t3.jenis, 
                t4.nama as pegawai, t4.nip, t4.jabatan, 
                t5.name as user, t6.nip as nip_user
                FROM tbl_pinjam t1
                LEFT JOIN tbl_detail_pinjam t2 ON t2.pinjam_id=t1.id AND t2.id=
                (SELECT MAX(id) FROM tbl_detail_pinjam WHERE pinjam_id=t1.id)
                LEFT JOIN tbl_mobil t3 ON t3.id=t1.mobil_id
                LEFT JOIN tbl_pegawai t4 ON t4.account_id=t1.pegawai_id
                LEFT JOIN users t5 ON t5.account_id=t2.user_id
                LEFT JOIN tbl_pegawai t6 ON t6.account_id=t5.account_id
                WHERE (t1.status='approved' OR t1.status='return') AND (t1.tgl_pinjam BETWEEN '$request->dari_tanggal' AND '$request->sampai_tanggal')"
            ));
            $pdf = PDF::loadview('generatePDF.bulanan',['data'=>$response]);
            $pdf->setPaper('A4', 'potrait');
        } catch (\Exception $e) {
            $pdf = $e->getMessage();
        }
    	return $pdf->stream();
    }
}
