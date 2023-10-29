<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\DataSatuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index(){
        return view('referensi.satuan',[
            'title' => 'Referensi',
            'page' => 'Satuan'
        ]);
    }

    public function data(){
        $satuan = DataSatuan::query();
        return datatables()->of($satuan)
            ->addIndexColumn()
            ->addColumn('aksi', function($satuan){
                return '
                <div class="btn-group">
                    <a href="javascript:void(0)" onclick="editSatuan(`'.route('satuan.update',$satuan->id).'`,'.$satuan->id.')" class="btn btn-warning" ><i class="fas fa-edit"></i></a>
                    <a href="javascript:void(0)" onclick="hapusSatuan(`'.route('satuan.destroy',$satuan->id).'`)" class="btn btn-danger" ><i class="fas fa-trash"></i></i></a>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function store(Request $request){
        $field = [
            'nm_satuan' => ['required']
        ];

        $pesan = [
            'nm_satuan.required' => 'Nama Satuan tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);


        DataSatuan::create($request->all());
        return response()->json('data satuan berhasil ditambahkan',200);
    }

    public function show($id){
        $satuan = DataSatuan::find($id);
        return response()->json($satuan);
    }

    public function update(Request $request,$id){
        $satuan = DataSatuan::find($id);
        $field = [
            'nm_satuan' => ['required']
        ];

        $pesan = [
            'nm_satuan.required' => 'Nama Satuan tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);
        $satuan->update($request->all());
        return response()->json('data satuan berhasil diubah',200);
    }

    public function destroy($id){
        $satuan = DataSatuan::find($id);
        $satuan->delete();

        return response()->json('data satuan berhasil dihapus', 204);
    }
}
