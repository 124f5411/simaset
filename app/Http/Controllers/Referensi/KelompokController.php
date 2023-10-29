<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\KelompokSsh;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    public function index(){
        return view('referensi.kelompok',[
            'title' => 'Referensi',
            'page' => 'Kelompok'
        ]);
    }

    public function data(){
        $kelompok = KelompokSsh::query();
        return datatables()->of($kelompok)
                ->addIndexColumn()
                ->addColumn('aksi', function($kelompok){
                    return '
                    <div class="btn-group">
                        <a href="javascript:void(0)" onclick="editKelompok(`'.route('kelompok.update',$kelompok->id).'`,'.$kelompok->id.')" class="btn btn-warning" ><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="hapusKelompok(`'.route('kelompok.destroy',$kelompok->id).'`)" class="btn btn-danger" ><i class="fas fa-trash"></i></i></a>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function store(Request $request){
        $field = [
            'kelompok' => ['required']
        ];

        $pesan = [
            'kelompok.required' => 'Kelompok ssh tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);

        KelompokSsh::create($request->all());
        return response()->json('kelompok ssh berhasil ditambahkan',200);
    }

    public function show($id){
        $kelompok = KelompokSsh::find($id);
        return response()->json($kelompok);
    }

    public function update(Request $request, $id){
        $kelompok = KelompokSsh::find($id);
        $field = [
            'kelompok' => ['required']
        ];

        $pesan = [
            'kelompok.required' => 'Kelompok ssh tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);

        $kelompok->update($request->all());
        return response()->json('kelompok ssh berhasil diubah', 200);
    }

    public function destroy($id){
        $kelompok = KelompokSsh::find($id);
        $kelompok->delete();
        return response()->json('kelompok ssh berhasil dihapus', 204);
    }
}
