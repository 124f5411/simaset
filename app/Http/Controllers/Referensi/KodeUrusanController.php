<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\KodeOpd;
use App\Models\KodeUrusan;
use Illuminate\Http\Request;

class KodeUrusanController extends Controller
{
    public function index(){
        return view('referensi.kodeUrusan',[
            'title' => 'Referensi',
            'page' => 'Kode Urusan'
        ]);
    }

    public function kodeBiro(){
        $dtbiro = KodeOpd::whereRaw('SUBSTR(kd_opd,1,4) = "4.01"')->get();
        if($dtbiro->count() < 1){
            $kode = "Data Tidak ditemukan";
        }else{
            $kd = $dtbiro->max('kd_opd');
            $urutan = (int) substr($kd, 18, 2);
            $urutan++;
            $urut = sprintf("%02s", $urutan);
            $parent = substr($kd,0,17);
            $kode = $parent.'.'.$urut;
        }
        return response()->json($kode);
    }


    public function dropUrusan(){
        $urusan = KodeUrusan::whereRaw('length(kode) = 1')->orderBy('kode','ASC')->get();
        return response()->json($urusan);
    }

    public function dropSubUrusan(){
        $urusan = KodeUrusan::whereRaw('length(kode) = 1')->orderBy('kode','ASC')->get();
        return response()->json($urusan);
    }

    public function getKodeBidang($id){
        $kode = KodeUrusan::whereRaw('length(kode) = 4 AND SUBSTR(kode,1,1) like "%'.$id.'%" ')
                            ->orderBy('kode','ASC')->get();
        return response()->json($kode);

    }

    public function kodeUrusan($id){
        $kode_opd = KodeOpd::all();
        if($kode_opd->count() > 0){
            $kd = $kode_opd->max('kd_opd');
            $urutan = (int) substr($kd, 15, 2);
            $urutan++;
            $urut = sprintf("%02s", $urutan);
            if(strlen($id) == 4){
                $kode = $id.".0.00.0.00.".$urut.".00";
            }
            if(strlen($id) == 9){
                $kode = $id.".0.00.".$urut.".00";
            }

            if(strlen($id) == 14){
                $kode = $id.".".$urut.".00";
            }
        }else{
            if(strlen($id) == 4){
                $kode = $id.".0.00.0.00.01.00";
            }
            if(strlen($id) == 9){
                $kode = $id.".0.00.01.00";
            }
            if(strlen($id) == 14){
                $kode = $id.".01.00";
            }
        }

        return response()->json($kode);
    }
}
