<?php

namespace App\Http\Controllers\Kib;

use App\Http\Controllers\Controller;
use App\Models\DetailKontrak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KibBController extends Controller
{
    public function index(){
        if(Auth::user()->level == 'aset'){
            return view('kib.aset.b',[
                'title' => 'Kartu Inventaris Barang',
                'page' => 'KIB B'
            ]);
        }

        if(Auth::user()->level == 'bendahara' || Auth::user()->level == 'operator'){
            return view('kib.opd.b',[
                'title' => 'Kartu Inventaris Barang',
                'page' => 'KIB B'
            ]);
        }
    }

    protected function getData(){
        if(Auth::user()->level == 'aset'){
            $detail =  DetailKontrak::join('ref_barang_kontrak','detail_kontrak.kode','=','ref_barang_kontrak.kode')
                        ->where('ref_barang_kontrak.kib','=','B');
        }

        if(Auth::user()->level == 'bendahara' || Auth::user()->level == 'operator'){
            $detail =  DetailKontrak::join('ref_barang_kontrak','detail_kontrak.kode','=','ref_barang_kontrak.kode')
                        ->join('data_kontrak','detail_kontrak.id_kontrak','=','data_kontrak.id')
                        ->where('data_kontrak.opd','=',Auth::user()->id_opd)
                        ->where('ref_barang_kontrak.kib','=','B');
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
                        ->addColumn('harga', function($detail){
                            return number_format($detail->harga, 2, ",", ".");
                        })
                        ->addColumn('uraian', function($detail) {
                            return getValue("nama","ref_barang_kontrak"," kode = '$detail->kode'");
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
                        ->addColumn('harga', function($detail){
                            return number_format($detail->harga, 2, ",", ".");
                        })
                        ->addColumn('uraian', function($detail) {
                            return getValue("nama","ref_barang_kontrak"," kode = '$detail->kode'");
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
