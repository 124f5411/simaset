<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\KodeOpd;
use Illuminate\Http\Request;

class KodeOpdController extends Controller
{
    public function index(){
        return view('referensi.kodeOpd',[
            'title' => 'Referensi',
            'page' => 'Kode OPD'
        ]);
    }

    public function data(){
        $opd = KodeOpd::query();
        return datatables()
                ->eloquent($opd)
                ->addIndexColumn()
                ->addColumn('aksi', function($opd){
                    return '
                    <div class="btn-group">
                    <a href="javascript:void(0)" onclick="hapusOpd(`'.route('opd.destroy',$opd->id).'`)" class="btn btn-sm btn-danger" ><i class="fas fa-trash"></i></i></a>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function store(Request $request){
        $field = [
            'kd_opd' => ['required'],
            'nm_opd' =>  ['required'],
        ];

        $pesan = [
            'kd_opd.required' => 'Kod OPD tidak boleh kosong <br />',
            'nm_opd.required' => 'Nama OPD tidak boleh kosong <br />'
        ];

        $this->validate($request, $field, $pesan);
        $data = [
            'kd_opd' => $request->kd_opd,
            'nm_opd' => $request->nm_opd
        ];

        KodeOpd::create($data);
        return response()->json('OPD berhasil ditambahkan',200);
    }


    public function destroy($id){
        $opd = KodeOpd::find($id);
        $opd->delete();
        return response('OPD berhasil dihapus', 204);
    }


}
