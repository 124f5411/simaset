<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\MasterKib;
use Illuminate\Http\Request;

class KibController extends Controller
{
    public function index(){
        return view('referensi.kib',[
            'title' => 'Referensi',
            'page' => 'Kib',
        ]);
    }

    public function data(){
        $master = MasterKib::all();
        return datatables()->of($master)
                ->addIndexColumn()
                ->addColumn('aksi', function($master){
                    return '
                    <div class="btn-group">
                        <a href="javascript:void(0)" onclick="editKib(`'.route('kib.update',$master->id).'`,'.$master->id.')" class="btn btn-warning" ><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="hapusKib(`'.route('kib.destroy',$master->id).'`)" class="btn btn-danger" ><i class="fas fa-trash"></i></i></a>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function store(Request $request){
        $field = [
            'type' => ['required'],
            'kode' => ['required'],
            'kib' => ['required'],
            'form' => ['required']
        ];

        $pesan = [
            'type.required' => 'Tipe kib tidak boleh kosong <br />',
            'kode.required' => 'Kode kib tidak boleh kosong <br />',
            'kib.required' => 'Kib tidak boleh kosong <br />',
            'form.required' => 'form kib tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);

        MasterKib::create($request->all());
        return response()->json('master kib berhasil ditambahkan',200);
    }

    public function show($id){
        $master = MasterKib::find($id);
        return response()->json($master);
    }

    public function update(Request $request,$id){
        $master = MasterKib::find($id);
        $field = [
            'type' => ['required'],
            'kode' => ['required'],
            'kib' => ['required'],
            'form' => ['required']
        ];

        $pesan = [
            'type.required' => 'Tipe kib tidak boleh kosong <br />',
            'kode.required' => 'Kode kib tidak boleh kosong <br />',
            'kib.required' => 'Kib tidak boleh kosong <br />',
            'form.required' => 'form kib tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);
        $master->update($request->all());
        return response()->json('master kib berhasil diubah',200);
    }

    public function destroy($id){
        $master = MasterKib::find($id);
        $master->delete();
        return response('master kib berhasil dihapus', 204);
    }

}
