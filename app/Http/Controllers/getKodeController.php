<?php

namespace App\Http\Controllers;

use App\Models\KodeBarangKontrak;
use Illuminate\Http\Request;

class getKodeController extends Controller
{
    public function kodeKelompok(){

        $kode = KodeBarangKontrak::select('kode')->whereRaw('length(kode) = 3')->orderBy('kode','ASC')->get();

        if($kode->count() == 0){
            $kd = '1.3';
        }else{
            $kd = $kode[0]->kode;
        }

        $urut = (int) substr($kd, 2, 1);
		$urut++;
		$code = "1.".$urut;
        return response()->json($code);
    }

    public function kodeJenis($id){

        $kode = KodeBarangKontrak::select('kode')->whereRaw('length(kode) = 5')
                                    ->where('kode','like','%'.$id.'%')
                                    ->orderBy('kode','ASC')->get();
        if($kode->count() == 0){
            $code = $id.'.1';
        }else{
            $kd = $kode->max('kode');
            $kd_kelompok = substr($kd,0,3);


            $urut = (int) substr($kd, 4, 1);
            $urut++;
            $code = $kd_kelompok.'.'.$urut;
        }
        return response()->json($code);
    }

    public function kodeObjek($id){

        $kode = KodeBarangKontrak::select('kode')
                            ->whereRaw('length(kode) = 8')
                            ->where('kode','like','%'.$id.'%')
                            ->orderBy('kode','ASC')->get();

        if($kode->count() == 0){
            $code = $id.'.01';
        }else{
            $kd = $kode->max('kode');
            $kd_jenis = substr($kd,0,5);

            $urut = (int) substr($kd, 6, 2);
            $urut++;
            $reg = sprintf("%02s", $urut);
            $code = $kd_jenis.'.'.$reg;
        }

        return response()->json($code);
    }

    public function kodeRincian($id){

        $kode = KodeBarangKontrak::select('kode')
                            ->whereRaw('length(kode) = 11')
                            ->where('kode','like','%'.$id.'%')
                            ->orderBy('kode','ASC')->get();

        if($kode->count() == 0){
            $code = $id.'.01';
        }else{
            $kd = $kode->max('kode');
            $kd_objek = substr($kd,0,8);

            $urut = (int) substr($kd, 9, 2);
            $urut++;
            $reg = sprintf("%02s", $urut);
            $code = $kd_objek.'.'.$reg;
        }

        return response()->json($code);
    }

    public function kodeSubRincian($id){

        $kode = KodeBarangKontrak::select('kode')
                            ->whereRaw('length(kode) = 14')
                            ->where('kode','like','%'.$id.'%')
                            ->orderBy('kode','ASC')->get();

        if($kode->count() == 0){
            $code = $id.'.01';
        }else{
            $kd = $kode->max('kode');
            $kd_rincian = substr($kd,0,11);

            $urut = (int) substr($kd, 12, 2);
            $urut++;
            $reg = sprintf("%02s", $urut);
            $code = $kd_rincian.'.'.$reg;
        }

        return response()->json($code);
    }

    public function kodeBarang($id){

        $kode = KodeBarangKontrak::select('kode')
                            ->whereRaw('length(kode) = 18')
                            ->where('kode','like','%'.$id.'%')
                            ->orderBy('kode','ASC')->get();

        if($kode->count() == 0){
            $code = $id.'.001';
        }else{
            $kd = $kode->max('kode');
            $kd_subrincian = substr($kd,0,14);

            $urut = (int) substr($kd, 16, 3);
            $urut++;
            $reg = sprintf("%03s", $urut);
            $code = $kd_subrincian.'.'.$reg;
        }

        return response()->json($code);
    }

    public function getJenis($id){
        $jenis = KodeBarangKontrak::select('kode','nama')
                            ->whereRaw('length(kode) = 5 ')
                            ->where('kode','like','%'.$id.'%')
                            ->orderBy('kode','ASC')->get();


        return response()->json($jenis);
    }

    public function getObjek($id){
        $objek = KodeBarangKontrak::select('kode','nama')
                            ->whereRaw('length(kode) = 8 AND SUBSTR(kode,1,5) like "%'.$id.'%" ')
                            // ->where('kode','like','%'.$id.'%')
                            ->orderBy('kode','ASC')->get();


        return response()->json($objek);
    }

    public function getRincian($id){
        $rincian = KodeBarangKontrak::select('kode','nama')
                            ->whereRaw('length(kode) = 11 AND SUBSTR(kode,1,8) like "%'.$id.'%" ')
                            // ->where('kode','like','%'.$id.'%')
                            ->orderBy('kode','ASC')->get();


        return response()->json($rincian);
    }

    public function getSubRincian($id){
        $rincian = KodeBarangKontrak::select('kode','nama')
                            ->whereRaw('length(kode) = 14 AND SUBSTR(kode,1,11) like "%'.$id.'%" ')
                            // ->where('kode','like','%'.$id.'%')
                            ->orderBy('kode','ASC')->get();


        return response()->json($rincian);
    }

    public function getBarang($id){
        $barang = KodeBarangKontrak::select('kode','nama')
                            ->whereRaw('length(kode) = 18 AND SUBSTR(kode,1,14) like "%'.$id.'%" ')
                            ->orderBy('kode','ASC')->get();
        return response()->json($barang);
    }
}
