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

class GeneratePDF extends Controller
{
    public function peminjaman($id){
        try {
            $response = DB::select(DB::raw(
                "SELECT t1.*, t3.no_plat, t3.jenis, t4.nama as pegawai, t4.nip,
                t4.jabatan, t5.name as user, t6.nip as nip_user
                FROM tbl_pinjam t1
                LEFT JOIN tbl_detail_pinjam t2 ON t2.pinjam_id=t1.id AND t2.id=
                (SELECT MAX(id) FROM tbl_detail_pinjam WHERE pinjam_id=t1.id)
                LEFT JOIN tbl_mobil t3 ON t3.id=t1.mobil_id
                LEFT JOIN tbl_pegawai t4 ON t4.account_id=t1.pegawai_id
                LEFT JOIN users t5 ON t5.account_id=t2.user_id
                LEFT JOIN tbl_pegawai t6 ON t6.account_id=t5.account_id
                WHERE t1.id=$id"
            ));
            $pdf = PDF::loadview('generatePDF.peminjaman',['data'=>$response]);
            $pdf->setPaper('A4', 'potrait');
        } catch (\Exception $e) {
            $pdf = $e->getMessage();
        }
    	return $pdf->stream();
    }
}
