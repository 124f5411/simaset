<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\HakTanah;
use Illuminate\Http\Request;

class hakTanahController extends Controller
{
    public function index(){
        return view('referensi.hak',[
            'title' => 'Referensi',
            'page' => 'Hak'
        ]);
    }

    public function data(){
        $hak = HakTanah::all();
        return datatables()->of($hak)
        ->addIndexColumn()
                ->addColumn('aksi', function($hak){
                    return '
                    <div class="btn-group">
                        <a href="javascript:void(0)" onclick="editHak(`'.route('hak_tanah.update',$hak->id).'`,'.$hak->id.')" class="btn btn-warning" ><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" onclick="hapusHak(`'.route('hak_tanah.destroy',$hak->id).'`)" class="btn btn-danger" ><i class="fas fa-trash"></i></i></a>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function store(Request $request){
        $field = [
            'hak' => ['required']
        ];

        $pesan = [
            'hak.required' => 'Status tanah tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);


        HakTanah::create($request->all());
        return response()->json('hak tanah berhasil ditambahkan',200);
    }

    public function show($id){
        $hak = HakTanah::find($id);
        return response()->json($hak);
    }

    public function update(Request $request, $id){
        $hak = HakTanah::find($id);
        $field = [
            'hak' => ['required']
        ];

        $pesan = [
            'hak.required' => 'Hak tanah tidak boleh kosong <br />'
        ];
        $this->validate($request, $field, $pesan);
        $hak->update($request->all());
        return response()->json('hak tanah berhasil diubah',200);
    }

    public function destroy($id){
        $hak = HakTanah::find($id);
        $hak->delete();
        return response('hak tanah berhasil dihapus', 204);
    }
}
