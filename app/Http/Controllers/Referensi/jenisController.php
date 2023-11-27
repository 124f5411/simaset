<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\DataJenis;
use App\Models\MasterKib;
use Illuminate\Http\Request;

class jenisController extends Controller
{
    public function index(){
        $master = MasterKib::all();
        return view('referensi.jenis',[
            'title' => 'Referensi',
            'page' => 'Jenis',
            'drops' => $master
        ]);
    }

    public function data(){
        $jenis = DataJenis::query();
        return datatables()->eloquent($jenis)
        ->addIndexColumn()
        ->addColumn('kib', function($jenis) {
            return getValue("kib", " kib_master", " id = ".$jenis->id_master);
        })
        ->addColumn('aksi', function($jenis){
            return '
            <div class="btn-group">
                <a href="javascript:void(0)" onclick="editJenis(`'.route('jenis.update',$jenis->id).'`,'.$jenis->id.')" class="btn btn-warning" ><i class="fas fa-edit"></i></a>
                <a href="javascript:void(0)" onclick="hapusJenis(`'.route('jenis.destroy',$jenis->id).'`)" class="btn btn-danger" ><i class="fas fa-trash"></i></i></a>
            </div>
            ';
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function store(Request $request){
        $field = [
            'jenis' => ['required'],
            'id_master' => ['required']
        ];

        $pesan = [
            'jenis.required' => 'Jenis aset tidak boleh kosong <br />',
            'id_master.required' => 'Silahkan pilih master kib <br />'
        ];
        $this->validate($request, $field, $pesan);

        DataJenis::create($request->all());
        return response()->json('jenis aset berhasil ditambahkan',200);
    }

    public function show($id){
        $jenis = DataJenis::find($id);
        return response()->json($jenis);
    }

    public function update(Request $request,$id){
        $jenis = DataJenis::find($id);
        $field = [
            'jenis' => ['required'],
            'id_master' => ['required']
        ];

        $pesan = [
            'jenis.required' => 'Jenis aset tidak boleh kosong <br />',
            'id_master.required' => 'Silahkan pilih master kib <br />'
        ];
        $this->validate($request, $field, $pesan);
        $jenis->update($request->all());
        return response()->json('jenis aset berhasil diubah',200);
    }

    public function destroy($id){
        $jenis = DataJenis::find($id);
        $jenis->delete();
        return response('jenis aset berhasil dihapus', 204);
    }
}
