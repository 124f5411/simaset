<?php

namespace App\Http\Controllers\Kib;

use App\Http\Controllers\Controller;
use App\Models\DetailKontrak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KibAController extends Controller
{
    public function index(){
        if(Auth::user()->level == 'aset'){
            return view('kib.aset.a',[
                'title' => 'Kartu Inventaris Barang',
                'page' => 'KIB A'
            ]);
        }

        if(Auth::user()->level == 'bendahara' || Auth::user()->level == 'operator'){
            return view('kib.opd.a',[
                'title' => 'Kartu Inventaris Barang',
                'page' => 'KIB A'
            ]);
        }

    }

    protected function getData(){

        if(Auth::user()->level == 'aset'){
            $detail =  DetailKontrak::join('ref_barang_kontrak','detail_kontrak.kode','=','ref_barang_kontrak.kode')
                        ->where('ref_barang_kontrak.kib','=','A');
        }

        if(Auth::user()->level == 'bendahara' || Auth::user()->level == 'operator'){
            $detail =  DetailKontrak::join('ref_barang_kontrak','detail_kontrak.kode','=','ref_barang_kontrak.kode')
                        ->join('data_kontrak','detail_kontrak.id_kontrak','=','data_kontrak.id')
                        ->where('data_kontrak.opd','=',Auth::user()->id_opd)
                        ->where('ref_barang_kontrak.kib','=','A');
        }

        return $detail;
    }

    public function data(){
        $detail = $this->getData();
        if(Auth::user()->level == 'aset'){
            $datatable =  datatables()->eloquent($detail)
                        ->addIndexColumn()
                        ->addColumn('nm_opd', function($detail) {
                            $id_opd = getValue("opd","data_kontrak"," id = ".$detail->id_kontrak);
                            $nm_opd = getValue("nm_opd","kode_opd"," id = ".$id_opd);
                            return $nm_opd;
                        })
                        ->addColumn('uraian', function($detail) {
                            return getValue("nama","ref_barang_kontrak"," kode = '$detail->kode'");
                        })
                        ->addColumn('tgl_sertifikat', function($detail) {
                            return indo_dates($detail->tgl_sertifikat);
                        })
                        ->addColumn('hak', function($detail){
                            return getValue("hak","jns_hak"," id = ".$detail->id_hak);
                        })
                        ->addColumn('aksi', function($detail){
                            return '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="" class="btn btn-sm btn-warning" title="Ubah Kontrak" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="" class="btn btn-sm btn-danger" title="Hapus Kontrak" ><i class="fas fa-trash"></i></a>
                            </div>
                            ';
                        })
                        ->rawColumns(['aksi'])
                        ->make(true);
        }

        if(Auth::user()->level == 'bendahara' || Auth::user()->level == 'operator'){
            $datatable =  datatables()->eloquent($detail)
                        ->addIndexColumn()
                        ->addColumn('uraian', function($detail) {
                            return getValue("nama","ref_barang_kontrak"," kode = '$detail->kode'");
                        })
                        ->addColumn('tgl_sertifikat', function($detail) {
                            return indo_dates($detail->tgl_sertifikat);
                        })
                        ->addColumn('hak', function($detail){
                            return getValue("hak","jns_hak"," id = ".$detail->id_hak);
                        })
                        ->addColumn('aksi', function($detail){
                            return '
                            <div class="btn-group">
                                <a href="javascript:void(0)" onclick="" class="btn btn-sm btn-warning" title="Ubah Kontrak" ><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" onclick="" class="btn btn-sm btn-danger" title="Hapus Kontrak" ><i class="fas fa-trash"></i></a>
                            </div>
                            ';
                        })
                        ->rawColumns(['aksi'])
                        ->make(true);
        }
        return $datatable;
    }
}
